<?php

namespace App\Http\Controllers;

use App\Model\CompanyPhones;
use App\Model\Notes;
use App\Model\Sales;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Auth;

use SlevomatEET\Cryptography\CryptographyService;
use SlevomatEET\Driver\GuzzleSoapClientDriver;
use SlevomatEET\Configuration;
use SlevomatEET\EvidenceEnvironment;
use SlevomatEET\Client;
use SlevomatEET\Receipt;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function redirect() {
        if (Auth::check())
        {
            return redirect(route('admin.select_company'));
        }
        else
        {
            return redirect(route('login'));
        }
    }


    /**
     * Recieve SMS and submit sale to EET
     *
     */
    public function receiveSms(Request $request, CompanyPhones $companyPhones, Sales $sales, Notes $notes){

        $company = $companyPhones->getCompanyByNumber($request['tel-number'])->first();

        if (is_null($company))
            die();

        $lastid = $sales->getAll($company->company_id)->first();

        if ($lastid != null){
            $lastid = $lastid->receiptNumber;
        }else{
            $lastid = 0;
        }

        $receiptNumber = ($lastid+1);
        $premiseId = 1;
        $cashregister = 'sms';

        $price = $request['msg-text']*100;

        $crypto = new CryptographyService(DIR_CERT . $company->company_id.'/private.key', DIR_CERT . $company->company_id.'/public.pub');
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

        try {
            $response = $client->send($receipt);
            $error = 0;
        }
        catch (\SlevomatEET\FailedRequestException $e) {
            echo $e->getRequest()->getPkpCode(); // if request fails you need to print the PKP and BKP codes to receipt
            $error = 1;
        } catch (\SlevomatEET\InvalidResponseReceivedException $e) {
            echo $e->getResponse()->getRequest()->getBkpCode(); // on invalid response you need to print the PKP and BKP too
            $error = 1;
        }

        if ($error == 0){
            $msg = $request['msg-text'].';'.$response->getFik();

            $store = $sales->create([
                'user_id' => 0,
                'company_id' => $company->company_id,
                'products' => $request['products'],
                'total_price' => $request['msg-text'],
                'fik' => '',
                'bkp' => $response->getBkp(),
                'receiptNumber' => $receiptNumber,
                'premiseId' => $premiseId,
                'cash_register' => $cashregister,
                'receipt_time' => date("Y-m-d H:i:s"),
                'not_sent' => 1
            ]);

            $note = $notes->create([
                'sale_id' => $store->id,
                'company_id' => $company->company_id,
                'user_id' => 0,
                'note' => 'Tržba zaevidovaná pomocou SMS.',
                'from' => NULL,
                'to' => NULL,
                'type' => 0
            ]);
        }else{
            $msg = 'Tržbu sa nepodarilo zaevidovať!';
        }

        $post = [
            'number' => $request['tel-number'],
            'msg-text' => $msg,
        ];

        $ch = curl_init('http://www.sms-brana-example.cz/send-sms');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        $response = curl_exec($ch);

        curl_close($ch);
    }
}
