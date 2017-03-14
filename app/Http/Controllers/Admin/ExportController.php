<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class ExportController extends AdminController
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

            if (session('isAdmin') == false && session('haveExport') == false){
                return redirect(route('admin.dashboard'));
            }

            return $next($request);
        });

        $this->page_title = 'Export';
        $this->page_icon = 'fa fa-download';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->page_description = "Export tržieb do CSV";

        return view('admin.export.index');
    }

    /**
     * Export for sales.
     *
     * @return \Illuminate\Http\Response
     */
    public function submit()
    {
        $filename = "stores_" . date('Y-m-d_H-i-s') . ".csv";
        $storage_path_dir = date('Y-m');
        $f = null;



        return view('admin.export.index');
    }

}
