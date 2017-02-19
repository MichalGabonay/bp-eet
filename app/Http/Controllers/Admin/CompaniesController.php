<?php

namespace App\Http\Controllers\Admin;

use App\Model\Companies;
use Illuminate\Http\Request;
//use App\Http\Requests;
use Flash;
use Auth;

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
    public function index()
    {
        $this->page_description = "prehlad";

        $companies = $this->companies->getAll()->get();
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
    public function store(Request $request)
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

        Flash::success('Společnost bola úspešne vytvorená!');

        return redirect(route('admin.companies.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detail($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->page_description = 'upraviť';

        $companies = $this->companies->findOrFail($id);

        return view('admin.companies.edit', compact('companies'));
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
}
