<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class ImportController extends AdminController
{
    /**
     * Create a new dashboard controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->middleware(function ($request, $next) {

            if (session('isAdmin') == false && session('isManager') == false){
                return redirect(route('admin.dashboard'));
            }

            return $next($request);
        });

        $this->page_title = 'Import';
        $this->page_icon = 'fa fa-upload';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->page_description = "CSV";

        return view('admin.import.index');
    }

    /**
     * Import of sales.
     *
     * @return void
     */
    public function submit()
    {
        $this->importSales();
    }

    private function importSales()
    {
        echo 'done';
    }






}
