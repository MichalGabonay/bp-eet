<?php

namespace App\Http\Controllers\Admin;

use App\Model\Sales;
use Illuminate\Http\Request;
use Response;
use Storage;


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
    public function submit(Request $request)
    {
        $filename = "export_" . date('Y-m-d_H-i-s') . ".csv";
        $storage_path_dir = date('Y-m');
        $f = null;

        $sales = new Sales();
        $sales = $sales->getAllForExport(session('selectedCompany'));

        if ($request->input('total_price'))         $f .= "\"Celkoá cena\";";
        if ($request->input('fik'))                 $f .= "\"FIK\";";
        if ($request->input('bkp'))                 $f .= "\"BKP\";";
        if ($request->input('receipt_number'))      $f .= "\"Číslo účtenky\";";
        if ($request->input('receipt_time'))        $f .= "\"Datum uskutečnění tržby\";";
        if ($request->input('premise_id'))          $f .= "\"ID provozovny\";";
        if ($request->input('cash_register'))       $f .= "\"ID pokladny\";";
        if ($request->input('products'))            $f .= "\"Produkty\";";

        $f .= "\n";

        foreach( $sales as $s){

            if($request->input('total_price'))       $f .= "\"" . $s->total_price . "\";";
            if($request->input('fik'))               $f .= "\"" . $s->fik . "\";";
            if($request->input('bkp'))               $f .= "\"" . $s->bkp . "\";";
            if($request->input('receipt_number'))    $f .= "\"" . $s->receiptNumber . "\";";
            if($request->input('receipt_time'))      $f .= "\"" . $s->receipt_time . "\";";
            if($request->input('premise_id'))        $f .= "\"" . $s->premiseId . "\";";
            if($request->input('cash_register'))     $f .= "\"" . $s->cash_register . "\";";
            if($request->input('products'))          $f .= "\"" . $s->products . "\";";


            $f .= "\n";
        }//foreach

        Storage::put('export/' . $storage_path_dir . '/' . $filename, "\xEF\xBB\xBF" . $f);
        $storage_path = Storage::disk()->getDriver()->getAdapter()->getPathPrefix();
        return Response::download($storage_path . 'export/' . $storage_path_dir . '/' . $filename, $filename, ['Content-Type' => 'text/csv']);
    }
}
