<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SlevomatEET\Cryptography\CryptographyService;
use SlevomatEET\Driver\GuzzleSoapClientDriver;
use SlevomatEET\Configuration;
use SlevomatEET\EvidenceEnvironment;
use SlevomatEET\Client;
use SlevomatEET\Receipt;

define('DIR_CERT', __DIR__ . "/../../../resources/assets/cert/");

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.home');
//        $crypto = new CryptographyService(DIR_CERT . 'EET_CA1_Playground-CZ00000019.key', DIR_CERT . 'EET_CA1_Playground-CZ00000019.pub');
//        $configuration = new Configuration('CZ00000019', '273', '/5546/RO24', new EvidenceEnvironment(EvidenceEnvironment::PLAYGROUND), false);
//        $client = new Client($crypto, $configuration, new GuzzleSoapClientDriver(new \GuzzleHttp\Client()));
//
//        $receipt = new Receipt(
//            true,
//            'CZ683555118',
//            '0/6460/ZQ42',
//            new \DateTimeImmutable('2016-12-05 00:30:12'),
//            3411300
//        );
//
//        try {
//            $response = $client->send($receipt);
//            echo $response->getFik();
//        } catch (\SlevomatEET\FailedRequestException $e) {
//            echo $e->getRequest()->getPkpCode(); // if request fails you need to print the PKP and BKP codes to receipt
//        } catch (\SlevomatEET\InvalidResponseReceivedException $e) {
//            echo $e->getResponse()->getRequest()->getPkpCode(); // on invalid response you need to print the PKP and BKP too
//        }
    }
}
