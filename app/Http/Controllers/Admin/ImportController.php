<?php

namespace App\Http\Controllers\Admin;

use App\Model\Sales;
use Illuminate\Http\Request;
use Auth;

class ImportController extends AdminController
{
    /**
     * Create a new import controller instance.
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

    /**
     * Read a csv file, parse him and import into database
     */
    private function importSales()
    {
        $sales = new Sales();
        $userid = Auth::user()->id;

        if (($handle = fopen($_FILES['import_file']['tmp_name'], "r")) !== FALSE) {
            $i = 0;
            while (($row = fgets($handle, 4096)) !== FALSE) {
                $i++;
                if ($i == 1) continue;

                $csvRow = str_getcsv($row, "\n");
                foreach($csvRow as &$row){
                    $row = str_getcsv($row, ";");

                    if (count($row) < 7){
                        echo "<span style='color: red'>Záznam nemá správny formát</span><br>" . count($row);
                        continue;
                    }

                    $products = '';
                    if (isset($row[7])){
                        $products = $row[7];
                    }

                    try{
                        $store = $sales->create([
                            'user_id' => $userid,
                            'company_id' => session('selectedCompany'),
                            'products' => $products,
                            'total_price' => $row[0],
                            'fik' => $row[1],
                            'bkp' => $row[2],
                            'receiptNumber' => $row[3],
                            'premiseId' => $row[5],
                            'cash_register' => $row[6],
                            'receipt_time' => $row[4],
                            'not_sent' => 0,
                        ]);
                        echo "<span style='color: green'>Záznam " . implode(";",$row) . " sa úspešne naimportoval.</span><br>";
                    }
                    catch (\Illuminate\Database\QueryException $e){
                        echo "<span style='color: red'>Záznam " . implode(";",$row) . " sa nepodarilo importovať.</span><br>";
                    }
                }
            }
            fclose($handle);
        }

        echo 'hotovo';
    }

    public function download(){
        $file = file_get_contents(public_path('media\import.csv'));
        header('Content-Disposition: attachment; filename="example-import' . '.csv"');
        header("Cache-control: private");
        header("Content-type: text/csv");
        header("Content-transfer-encoding: binary\n");
        echo "\xEF\xBB\xBF" . $file;
        exit;
    }
}
