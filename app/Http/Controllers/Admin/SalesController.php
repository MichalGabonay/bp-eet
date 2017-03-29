<?php

namespace App\Http\Controllers\Admin;

use App\Model\Certs;
use App\Model\Companies;
use App\Model\Sales;
use App\User;

use Illuminate\Http\Request;
//use App\Http\Requests;
use Flash;
use Auth;
use SlevomatEET\Cryptography\CryptographyService;
use SlevomatEET\Driver\GuzzleSoapClientDriver;
use SlevomatEET\Configuration;
use SlevomatEET\EvidenceEnvironment;
use SlevomatEET\Client;
use SlevomatEET\Receipt;



class SalesController extends AdminController
{

    private $sales;

    /**
     * Create a new dashboard controller instance.
     *
     * @return void
     */
    public function __construct(Request $request, Sales $sales)
    {
        parent::__construct($request);

        $this->middleware(function ($request, $next) {

            if (session('isAdmin') == false && session('isCashier') == false && session('isManager') == false){
                return redirect(route('admin.dashboard'));
            }

            return $next($request);
        });

        $this->sales = $sales;

        $this->page_title = 'Tržby';
        $this->page_icon = 'fa fa-money';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Companies $companies, Certs $certs)
    {
        if (session('isAdmin') || session('isManager')){
//            $sales = $this->sales->getAll()->get();
            $sales = $this->sales->getAll()->get();
        }elseif (session('isCashier') && session('haveStorno')){
            $sales = $this->sales->getAll()->take(10)->get();
        }else{
            $sales = $this->sales->getAll()->where('user_id', Auth::user()->id)->take(5);
        }

        $companies = $companies->findOrFail(session('selectedCompany'));
        $cert = $certs->findOrFail($companies->cert_id);


        return view('admin.sales.index', compact('sales', 'cert'));
    }

    /**
     * Storno the eet
     *
     * @param $id
     */
    public function storno($id){
        $sale = $this->sales->findOrFail($id);

        $sale->storno = 1;
        $sale->save();

        //TODO: storno tržby(odoslanie so zapornou hodnotou), buď ukladať stornované tržby, alebo len pridávať príznak

        return redirect(route('admin.sales.index'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $total_price = $request['total_price'];
        $userid = Auth::user()->id;

        $lastid = $this->sales->getAll()->first()->id;

        $receiptNumber = session('selectedCompany').'C/'.$userid.'U/'.($lastid+1).'/'. date('dmy');
        $premiseId = '1';
        $cashregister = 'pokl-user-'. Auth::user()->id;

        $response = $this->eetSend($total_price*100, $receiptNumber, $premiseId, $cashregister);

        $store = $this->sales->create([
            'user_id' => $userid,
            'company_id' => session('selectedCompany'),
            'products' => $request['products'],
            'total_price' => $total_price,
            'fik' => $response->getFik(),
            'bkp' => $response->getBkp(),
            'receiptNumber' => $receiptNumber,
            'premiseId' => $premiseId,
            'cash_register' => $cashregister,

        ]);

//        dd($response->getFik());



        return redirect(route('admin.sales.create_new'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detail($id, User $user)
    {
        $sales = $this->sales->findOrFail($id);
        $user = $user->findOrFail($sales->user_id);

        $products = [];

        $product = explode(";", $sales->products);

        foreach ($product as $key => $p){
            $temp = explode("||", $p);

            $products[$key]['name'] = $temp[0];
            $products[$key]['price'] = $temp[1];
        }

        return view('admin.sales.detail', compact('sales', 'user', 'products'));
    }


    /**
     * Send eet to finances server
     *
     * @param  string  $price, $receiptNumber, $premiseId, $cashregister
     * @return array
     */
    private function eetSend($price, $receiptNumber, $premiseId, $cashregister){
        $companies = new Companies();
        $company = $companies->findOrFail(session('selectedCompany'));
        $crypto = new CryptographyService(DIR_CERT . 'private.key', DIR_CERT . 'public.pub');
        $configuration = new Configuration(
            $company->dic,
            $premiseId,
            $cashregister,
            new EvidenceEnvironment(EvidenceEnvironment::PLAYGROUND),
            false
        );


        $client = new Client($crypto, $configuration, new GuzzleSoapClientDriver(new \GuzzleHttp\Client()));

        $receipt = new Receipt(
            true,
            null,
            $receiptNumber,
            new \DateTimeImmutable('now'),
            $price
        );
//        dd($receipt);


        //TODO: platba sa nepodarila
        try {
            $response = $client->send($receipt);
            return $response;
        }
        catch (\SlevomatEET\FailedRequestException $e) {
            echo $e->getRequest()->getPkpCode(); // if request fails you need to print the PKP and BKP codes to receipt
        } catch (\SlevomatEET\InvalidResponseReceivedException $e) {
            echo $e->getResponse()->getRequest()->getPkpCode(); // on invalid response you need to print the PKP and BKP too
        }
    }
}
