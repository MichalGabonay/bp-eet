<?php

namespace App\Http\Controllers\Admin;

use App\Model\Certs;
use App\Model\Companies;
use App\Model\Notes;
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
use App\Helpers\ApiXml30;
use CloudPrint;


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
    public function index(Companies $companies, Certs $certs, Notes $notes)
    {
        if (session('isAdmin') || session('isManager')){
//            $sales = $this->sales->getAll()->get();
//            $sales = $this->sales->getAll(session('selectedCompany'))->get();
            $sales = $this->sales->getAllForChart(session('selectedCompany'))->get();
            $sales_all = $this->sales->getAll(session('selectedCompany'))->get();
        }elseif (session('isCashier') && session('haveStorno')){
            $sales = $this->sales->getAll(session('selectedCompany'))->take(10)->get();
        }else{
            $sales = $this->sales->getAll(session('selectedCompany'))->where('user_id', Auth::user()->id)->take(5)->get();
        }

        foreach ($sales as $sale){
            if ($sale->products != ''){
                $product = explode(";", $sale->products);

                $products = '';
                foreach ($product as $key => $p){
                    $temp = explode("||", $p);

                    $products .= $temp[0] . '; ';

                }
                $products = substr($products, 0, -2);
                $sale->products = $products;
            }

        }

        $all_notes = $notes->getAllFromCompany(session('selectedCompany'))->get();
        $note = [];

        foreach ($all_notes as $n){
            if ($n->sale_id){
                if (isset($note[$n->sale_id]) && strlen($note[$n->sale_id])< 50){
                    $note[$n->sale_id] = $note[$n->sale_id] .';'. $n->note;
                }else{
                    $note[$n->sale_id] = $n->note;
                }
                if (strlen($note[$n->sale_id]) > 50)
                    $note[$n->sale_id] = substr($note[$n->sale_id], 0, 47) . '...';
            }
        }

        $period_notes = $notes->getAllPeriod(session('selectedCompany'))->get()->take(5);

        $not_sent = $this->sales->getNotSent(session('selectedCompany'))->toArray();

        $companies = $companies->findOrFail(session('selectedCompany'));
        $cert = $certs->find($companies->cert_id);

        return view('admin.sales.index', compact('sales', 'cert', 'sales_all', 'note', 'period_notes', 'not_sent'));
    }

    /**
     * Storno the eet
     *
     * @param $id
     */
    public function storno($id){
        $sale = $this->sales->findOrFail($id);


        $total_price = $sale->total_price*(-100);
        $userid = Auth::user()->id;

        $receiptNumber = $sale->receiptNumber . '-storno';
        $premiseId = '1';
        $cashregister = 'pokl-user-'. $userid;
        if ($sale->not_sent == 0){
            $response = $this->eetSend($total_price, $receiptNumber, $premiseId, $cashregister);
        }else{
            $sale->not_sent = 2;
        }

        $sale->storno = 1;
        $sale->save();

        Flash::success('Tržba bola stornovaná!');

        return redirect()->back();
    }

    /**
     * generate receipt
     *
     * @param $id
     */
    public function generateReceipt($id, Companies $companies){
        $sale = $this->sales->findOrFail($id);

        $company = $companies->findOrFail($sale->company_id);

        if ($sale->products != ''){
            $product = explode(";", $sale->products);

            foreach ($product as $key => $p){
                $temp = explode("||", $p);

                $products[$key]['name'] = $temp[0];
                $products[$key]['price'] = $temp[1];
            }
        }else{
            $products[0]['name'] = 'Produkty';
            $products[0]['price'] = $sale->total_price;
        }

        return view('admin.sales.receipt', compact('sale', 'company', 'products'));
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


        $lastid = $this->sales->getAll(session('selectedCompany'))->first();

        if ($lastid != null){
            $lastid = $lastid->receiptNumber;
        }else{
            $lastid = 0;
        }

        $receiptNumber = ($lastid+1);
        $premiseId = '1';
        $cashregister = 'pokl-'. $userid;

        $response = $this->eetSend($total_price*100, $receiptNumber, $premiseId, $cashregister);

//        dd($response);

        if ( $response['fik'] == 'error' ){
            $store = $this->sales->create([
                'user_id' => $userid,
                'company_id' => session('selectedCompany'),
                'products' => $request['products'],
                'total_price' => $total_price,
                'fik' => '',
                'bkp' => $response['bkp'],
                'receiptNumber' => $receiptNumber,
                'premiseId' => $premiseId,
                'cash_register' => $cashregister,
                'receipt_time' => date("Y-m-d H:i:s"),
                'not_sent' => 1
            ]);

            Flash::success('Tržba bola úspešne vytvorná a zaevidovaná!');
        }else{
            $store = $this->sales->create([
                'user_id' => $userid,
                'company_id' => session('selectedCompany'),
                'products' => $request['products'],
                'total_price' => $total_price,
                'fik' => $response['fik'],
                'bkp' => $response['bkp'],
                'receiptNumber' => $receiptNumber,
                'premiseId' => $premiseId,
                'cash_register' => $cashregister,
                'receipt_time' => date("Y-m-d H:i:s"),
                'not_sent' => 0
            ]);

            Flash::success('Tržba bola úspešne vytvorná a zaevidovaná!');
        }

        if (isset($request['with_receipt'])){
            return redirect(route('admin.sales.generate_receipt', $store->id));
        }else{
            return redirect(route('admin.sales.create_new'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detail($id, User $user, Notes $notes)
    {
        $sales = $this->sales->findOrFail($id);
        $user = $user->findOrFail($sales->user_id);

        $products = [];

        if ($sales->products != ''){
            $product = explode(";", $sales->products);

            foreach ($product as $key => $p){
                $temp = explode("||", $p);

                $products[$key]['name'] = $temp[0];
                $products[$key]['price'] = $temp[1];
            }
        }

        $notes = $notes->getAllBySaleId($id)->get();



        return view('admin.sales.detail', compact('sales', 'user', 'products', 'notes'));
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
        $crypto = new CryptographyService(DIR_CERT . session('selectedCompany').'/private.key', DIR_CERT . session('selectedCompany').'/public.pub');
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
            return ['fik' => $response->getFik(), 'bkp' => $response->getBkp()];
        }
        catch (\SlevomatEET\FailedRequestException $e) {
            echo $e->getRequest()->getPkpCode(); // if request fails you need to print the PKP and BKP codes to receipt
            return ['fik' => 'error', 'bkp' => $e->getRequest()->getBkpCode()];
        } catch (\SlevomatEET\InvalidResponseReceivedException $e) {
            echo $e->getResponse()->getRequest()->getBkpCode(); // on invalid response you need to print the PKP and BKP too
//            $response->error = $e->getResponse()->getRequest()->getBkpCode();
            return ['fik' => 'error', 'bkp' => $e->getResponse()->getRequest()->getBkpCode()];
//            return $e->getResponse()->getRequest()->getBkpCode();
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete($id)
    {
        $products = $this->sales->findOrFail($id);
        $products->delete();

        Flash::success('Tržba bola odstránená z databázy!');

        return redirect()->back();
    }

    public function showNotSent(){
        $this->page_description = "neodoslané na EET";

        $sales = $this->sales->getNotSent(session('selectedCompany'));

        foreach ($sales as $s){
            $diff = date_diff(date_create(date('Y-m-d H:i:s')), date_create($s->receipt_time));
            $s->hours = ($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h;
        }

        $not_sent = $sales->toArray();

        return view('admin.sales.not_sent', compact('sales', 'not_sent'));
    }

    public function TrySentAgain(){
        $sales = $this->sales->getNotSent(session('selectedCompany'));

        foreach ($sales as $s){
            $total_price = $s->total_price;
            $receiptNumber = $s->receiptNumber;
            $premiseId = $s->premiseId;
            $cashregister = $s->cash_register;

            $response = $this->eetSend($total_price*100, $receiptNumber, $premiseId, $cashregister);

            if ($response['fik'] != 'error'){
                $s->not_sent = 0;
                $s->fik = $response['fik'];
                $s->bkp = $response['bkp'];
                $s->save();
            }
        }

        return redirect()->back();
    }

    public function test(){

    }


}
