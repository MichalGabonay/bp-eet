<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SlevomatEET\Cryptography\CryptographyService;
use SlevomatEET\Driver\GuzzleSoapClientDriver;
use SlevomatEET\Configuration;
use SlevomatEET\EvidenceEnvironment;
use SlevomatEET\Client;
use SlevomatEET\Receipt;


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
//        return view('admin.home');
        $crypto = new CryptographyService(DIR_CERT . 'private.key', DIR_CERT . 'public.pub');
        $configuration = new Configuration(
            'CZ00000019',
            '273',
            '/5546/RO24',
            new EvidenceEnvironment(EvidenceEnvironment::PLAYGROUND),
            false
        );


        $client = new Client($crypto, $configuration, new GuzzleSoapClientDriver(new \GuzzleHttp\Client()));

        $receipt = new Receipt(
            true,
            null,
            '0/6460/ZQ42',
            new \DateTimeImmutable('now'),
            3411300
        );
//        dd($receipt);

        try {
            $response = $client->send($receipt);
            echo $response->getFik();
        }
        catch (\SlevomatEET\FailedRequestException $e) {
            echo $e->getRequest()->getPkpCode(); // if request fails you need to print the PKP and BKP codes to receipt
            dd($e);
        } catch (\SlevomatEET\InvalidResponseReceivedException $e) {
            dd($e);
            echo $e->getResponse()->getRequest()->getPkpCode(); // on invalid response you need to print the PKP and BKP too
        }
    }
}
