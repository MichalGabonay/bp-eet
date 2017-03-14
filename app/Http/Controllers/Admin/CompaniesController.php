<?php

namespace App\Http\Controllers\Admin;

use SlevomatEET\Cryptography\CryptographyService;
use SlevomatEET\Driver\GuzzleSoapClientDriver;
use SlevomatEET\Configuration;
use SlevomatEET\EvidenceEnvironment;
use SlevomatEET\Client;
use SlevomatEET\Receipt;
use App\Model\Certs;
use App\Model\Companies;
use App\Model\Roles;
use App\Model\UsersCompanyRole;
use App\User;
use App\Model\UsersCompany;
use Illuminate\Http\Request;
//use App\Http\Requests;
use Flash;
use Auth;
use Image;

class CompaniesController extends AdminController
{
    /**
     * @var companies
     */
    protected $companies;

    /**
     * Create a new dashboard controller instance.
     *
     * @return void
     */
    public function __construct(Request $request, Companies $companies)
    {
        parent::__construct($request);
        $this->companies = $companies;
        $this->page_title = 'Spoločnosti';
        $this->page_icon = 'fa fa-building';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UsersCompany $usersCompany)
    {
        $this->page_description = "prehlad";

        $companies = $this->companies->getAllWhereAdmin(Auth::user()->id);
        
        foreach($companies as $c){
            $c->users = $usersCompany->getUsersFromCompany($c->id)->count();
        }
        return view('admin.companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->page_description = 'vytvoriť';

        return view('admin.companies.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, UsersCompany $usersCompany, UsersCompanyRole $usersCompanyRole)
    {
        $this->validate($request,
            [
                'name' => 'required',
            ]);

        $request['cert_id'] = NULL;
        $request['logo'] = '';

//        try {
            $store = $this->companies->create($request->all());
//        }
//        catch (\Illuminate\Database\QueryException $e){
//            if ($e) {
//                return redirect()
//                    ->back()
//                    ->withInput($request->all())
//                    ->withErrors([
//                        'Nepodarilo sa pridať novú spoločnosť.',
//                    ]);
//            }
//        }

        $company = $this->companies->findOrFail($store->id);



        $uc_store = $usersCompany->create([
            'user_id' => Auth::user()->id,
            'company_id' => $company->id,
            'enabled' => 1
        ]);

        $usersCompanyRole->create([
            'user_company_id' => $uc_store->id,
            'role_id' => 1
        ]);

        Flash::success('Společnost bola úspešne vytvorená!');

        return redirect(route('admin.companies.detail', $company->id));
//        return view('admin.companies.cert', compact('company'));
    }

    /**
     * Add certificate for company
     *
     * @param  int  $company_id
     * @return \Illuminate\Http\Response
     */
    public function addCert(Request $request, $company_id, Certs $certs){

        $company = $this->companies->findOrFail($company_id);

        $this->validate($request,
            [
                'cert' => 'required',
                'password' => 'required',
            ]);

        $file = $request->file('cert');
        $cert_name = $file->getClientOriginalName();
        $file->move('uploads/certs/' . $company_id . '/', $cert_name);
        $expiration_date = null;

        if (!$cert_store = file_get_contents('uploads/certs/' . $company_id . '/'.$cert_name)) {
            echo "Error: Unable to read the cert file\n";
            exit;
        }

        if (openssl_pkcs12_read($cert_store, $certificate, $request['password'])) {
            if (isset($certificate['pkey'])) {
                file_put_contents('uploads/certs/' . $company_id . '/private.key', $certificate['pkey']);
            }

            if (isset($certificate['cert'])) {
                $cert = null;
                openssl_x509_export($certificate['cert'], $cert);
                file_put_contents('uploads/certs/' . $company_id . '/public.pub', $cert);
                $p12data = openssl_x509_parse($certificate['cert']);
                $expiration_date = date('Y-m-d', $p12data['validTo_time_t']);
            }

        } else {
            echo "Error: Unable to read the cert store.\n";
            exit;
        }

        if($company->cert_id != null){
            $cert = $certs->findOrFail($company->cert_id);
            $valid = $this->testOfCert($company_id);
            $cert->update([
                'pks12' => $cert_name,
                'password' => $request['password'],
                'expiration_date' => $expiration_date,
                'valid' => $valid
            ]);
            $valid = $this->testOfCert($company_id);
        }else{
            $valid = $this->testOfCert($company_id);
            $store = $certs->create([
                'pks12' => $cert_name,
                'password' => $request['password'],
                'expiration_date' => $expiration_date,
                'valid' => $valid
            ]);
            $company->cert_id = $store->id;
            $company->update();
        }


//        Flash::success('Spoločnosť bola úspešne upravená!');
        return redirect(route('admin.companies.detail', $company_id));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detail($id, Roles $roles, UsersCompany $usersCompany, User $users, UsersCompanyRole $usersCompanyRole, Certs $certs)
    {
        $company = $this->companies->findOrFail($id);
        if ($company->cert_id != null){
            $cert = $certs->findOrFail($company->cert_id);
        }
        $this->page_description = 'detail - ' . $company->name;

        $company->users = $usersCompany->getUsersFromCompany($company->id)->count();

        $users_in = $usersCompany->getUsersFromCompany($id);

//        dd($users_in);
        $roles = $roles->getAll();
        $all_users = $users->getAll()->get();

        foreach ($all_users as $u){
            $is_in = false;
            foreach ($users_in as $ui){
                if ($u->id == $ui->user_id){
                    $is_in = true;
                    break;
                }
            }
            if ($is_in == true){
                $all_users = $all_users->except($u->id);
            }

        }


        $usersCompanyRole = $usersCompanyRole->getAll()->get();

//        dd($usersCompanyRole);

        foreach($users_in as $u){
            foreach($roles as $r){
                $users_role[$u->id][$r->id] = false;
            }
            foreach($usersCompanyRole as $ucr){
                if($ucr->user_company_id == $u->id){
                    $users_role[$u->id][$ucr->role_id] = true;
                }
            }
        }



        return view('admin.companies.detail', compact('company', 'users_in', 'roles', 'all_users', 'users_role', 'cert'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, UsersCompany $usersCompany, Roles $roles, User $all_users, UsersCompanyRole $usersCompanyRole)
    {
        $this->page_description = 'upraviť';

        $companies = $this->companies->findOrFail($id);
        $users_in = $usersCompany->getUsersFromCompany($id);
        $roles = $roles->getAll();
        $all_users = $all_users->getAll();

        $usersCompanyRole = $usersCompanyRole->getAll()->get();

//        dd($usersCompanyRole);

        foreach($users_in as $u){
            foreach($roles as $r){
                $users_role[$u->id][$r->id] = false;
            }
            foreach($usersCompanyRole as $ucr){
                if($ucr->user_company_id == $u->id){
                    $users_role[$u->id][$ucr->role_id] = true;
                }
            }
        }

        $company_id = $companies->id;

//        $users_role[][];

        return view('admin.companies.edit', compact('companies', 'users_in', 'roles', 'all_users', 'users_role', 'company_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validate = [
            'name' => 'required',
        ];

        $this->validate($request, $validate);
        $companies = $this->companies->findOrFail($id);

        $companies->update($request->all());


        Flash::success('Spoločnosť bola úspešne upravená!');
        return redirect(route('admin.companies.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $companies = $this->companies->findOrFail($id);
        $companies->delete();

        Flash::success('Spoločnost bola zmazaná!');

        return redirect(route('admin.companies.index'));
    }

    /**
     * Remove logo of company
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteLogo($id)
    {
        $companies = $this->companies->findOrFail($id);
        $companies->logo = '';
        $companies->update();

        Flash::success('Logo bolo odstránené!');

        return redirect(route('admin.companies.detail', $id));
    }

    /**
     * Change logo of company
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeLogo(Request $request, $id)
    {
        $companies = $this->companies->findOrFail($id);

        $file = $request->file('logo');
        $image_name = $id ."-".$file->getClientOriginalName();
        $file->move('uploads/logos', $image_name);
        $image = Image::make(sprintf('uploads/logos/%s', $image_name))->save();

        $companies->logo = $image_name;
        $companies->update();

        Flash::success('Nové logo bolo vložené!');

        return redirect(route('admin.companies.detail', $id));
    }

    private function testOfCert($company_id){
        $cert_dir = public_path() . '/uploads/certs/' . $company_id;
        $crypto = new CryptographyService($cert_dir . '/private.key', $cert_dir . '/public.pub');
        $configuration = new Configuration('CZ00000019', '273', '/5546/RO24', new EvidenceEnvironment(EvidenceEnvironment::PLAYGROUND), true);
        $client = new Client($crypto, $configuration, new GuzzleSoapClientDriver(new \GuzzleHttp\Client()));

        $receipt = new Receipt(
            true,
            'CZ683555118',
            '0/6460/ZQ42',
            new \DateTimeImmutable('2016-12-05 00:30:12'),
            3411300
        );

        try {
        $response = $client->send($receipt);
        echo $response->getFik();
//        die();
        } catch (\SlevomatEET\FailedRequestException $e) {
            echo $e->getRequest()->getPkpCode(); // if request fails you need to print the PKP and BKP codes to receipt
        } catch (\SlevomatEET\InvalidResponseReceivedException $e) {
//            echo $e->getResponse()->getRequest()->getPkpCode(); // on invalid response you need to print the PKP and BKP too
            if ($e->getResponse()->getRawData()->Chyba->kod == '0'){
                return 1;
            }else{
                return 0;
            }
        }
    }
}
