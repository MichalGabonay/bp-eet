<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Robots;
use Response;
use URL;
use DB;
use App\Http\Requests;
use Illuminate\Support\Facades\App;
use Artisan;
use Cache;
use App\Models\PhpZipSeeder;
use App\Models\Service\Service;
use App\Models\Products\Products;

class AppController extends Controller
{
    /**
     * Display robots.txt for environments
     *
     * @return \Illuminate\Http\Response
     */
    public function robots()
    {
        if (App::environment() == 'production') {
            // If on the live server, serve a nice, welcoming robots.txt.
            Robots::addUserAgent('*');
            Robots::addDisallow('/admin');
            Robots::addSitemap(URL::to('sitemap.xml'));
        } else {
            // If you're on any other server, tell everyone to go away.
            Robots::addDisallow('*');
        }

        return Response::make(Robots::generate(), 200, ['Content-Type' => 'text/plain']);
    }


    /**
     * Generates sitemap.xml
     *
     * @return \Illuminate\Http\Response
     */
    public function sitemap()
    {
        // https://github.com/RoumenDamianoff/laravel-sitemap
        // create new sitemap object
        $sitemap = App::make("sitemap");

        // set cache (key (string), duration in minutes (Carbon|Datetime|int), turn on/off (boolean))
        // by default cache is disabled
        $sitemap->setCache('laravel.sitemap', 3600);

        // check if there is cached sitemap and build new only if is not
        if (!$sitemap->isCached())
        {
            // add item to the sitemap (url, date, priority, freq)
            $sitemap->add(URL::to('/'), '2012-08-25T20:10:00+02:00', '1.0', 'daily');
            $sitemap->add(URL::to('page'), '2012-08-26T12:30:00+02:00', '0.9', 'monthly');

            // add item with translations (url, date, priority, freq, images, title, translations)
            $translations = [
                ['language' => 'fr', 'url' => URL::to('pageFr')],
                ['language' => 'de', 'url' => URL::to('pageDe')],
                ['language' => 'bg', 'url' => URL::to('pageBg')],
            ];
            $sitemap->add(URL::to('pageEn'), '2015-06-24T14:30:00+02:00', '0.9', 'monthly', [], null, $translations);

            // add item with images
            $images = [
                ['url' => URL::to('images/pic1.jpg'), 'title' => 'Image title', 'caption' => 'Image caption', 'geo_location' => 'Plovdiv, Bulgaria'],
                ['url' => URL::to('images/pic2.jpg'), 'title' => 'Image title2', 'caption' => 'Image caption2'],
                ['url' => URL::to('images/pic3.jpg'), 'title' => 'Image title3'],
            ];
            $sitemap->add(URL::to('post-with-images'), '2015-06-24T14:30:00+02:00', '0.9', 'monthly', $images);

            // get all posts from db
            /*
            $posts = DB::table('posts')->orderBy('created_at', 'desc')->get();
            */

            // add every post to the sitemap
            /*
            foreach ($posts as $post)
            {
                $sitemap->add($post->slug, $post->modified, $post->priority, $post->freq);
            }
            */
        }

        // show your sitemap (options: 'xml' (default), 'html', 'txt', 'ror-rss', 'ror-rdf')
        return $sitemap->render('xml');
    }


    /**
     * Switch language
     *
     * @return Redirect
     */
    public function switchLanguage(Request $request, $locale)
    {
        $locales = config('loreal.locales');

        if (isset($locales[(int) $locale]))
        {
            $request->session()->set('lang_id', (int) $locale);
        }

        return redirect()->back();
    }


    /**
     * Migrate DB
     */
    public function migrate()
    {
        ini_set('max_execution_time', 10000);

        /*
        // delete all tables
        $tables = DB::select('SHOW TABLES');

        foreach ($tables as $table) {
            foreach ($table as $key => $value)
            {
                DB::delete('DROP TABLE ' . $value);
            }
        }
        */

        Artisan::call('migrate');
        //Artisan::call('db:seed');

        $migrations = DB::select('SELECT migration FROM migrations');
        foreach ($migrations as $i => $m)
        {
            $migrations[$i] = $m->migration;
        }

        dd($migrations);
    }

    public function myseed(){
        ini_set('max_execution_time', 100000);

//        DB::statement('ALTER TABLE product CHANGE note note VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL');
//
//        DB::statement('ALTER TABLE product MODIFY COLUMN coolant_1_unit DOUBLE(8,2) UNSIGNED NOT NULL');
//        DB::statement('ALTER TABLE product MODIFY COLUMN coolant_2_unit DOUBLE(8,2) UNSIGNED NOT NULL');
//
//        $products = new Products();
//        $product = $products->getAll()->get();
//        foreach($product as $p){
//            if($p->coolant_1_type == 0){
//                $p->coolant_1_amount = 0;
//                $p->coolant_1_unit = 0;
//                $p->save();
//            }
//            if($p->coolant_2_type == 0){
//                $p->coolant_2_amount = 0;
//                $p->coolant_2_unit = 0;
//                $p->save();
//            }
//        }
//
//        DB::statement('ALTER TABLE product CHANGE COLUMN coolant_1_amount coolant_1_amount_kg DOUBLE(8,2) UNSIGNED NOT NULL');
//        DB::statement('ALTER TABLE product CHANGE COLUMN coolant_1_unit coolant_1_amount_co2 DOUBLE(8,2) UNSIGNED NOT NULL');
//        DB::statement('ALTER TABLE product CHANGE COLUMN coolant_2_amount coolant_2_amount_kg DOUBLE(8,2) UNSIGNED NOT NULL');
//        DB::statement('ALTER TABLE product CHANGE COLUMN coolant_2_unit coolant_2_amount_co2 DOUBLE(8,2) UNSIGNED NOT NULL');

//        $product = DB::connection('mysql-old')->select('select * from product');
//        foreach ($product as $p)
//        {
//            $newproduct = new Products();
//            $newproduct = $newproduct->find($p->id_product);
//
//            if(!empty($newproduct)){
//                if($newproduct->updated_at == NULL && $p->poznamky != '' ){
//                    $newproduct->note = $p->poznamky;
//                    $newproduct->save();
//                }
//            }
//        }
//
//        DB::statement('ALTER TABLE service MODIFY COLUMN planned_date VARCHAR(255) NOT NULL');
//        DB::statement('ALTER TABLE service MODIFY COLUMN failure VARCHAR(255) NOT NULL');
//
//        $failure = config('web.failure');
//
//        $service = new Service();
//        $services = $service->getAll()->get();
//        foreach($services as $s){
//            $pom = $service->findOrFail($s->id);
//            if(!empty($failure[$s->failure]))
//                $pom->failure = $failure[$s->failure];
//            else
//                $pom->failure = $s->failure;
//            $pom->save();
//        }

//        $product = new Products();
//        $product = $product->getAll()->get();
//
//        foreach($product as $p){
//            if($p->coolant_1_amount_kg != 0){
//                $value = $p->coolant_1_amount_kg;
//                switch($p->coolant_1_type){
//                    case '1':
//                        $value = $value*3.922;
//                        $p->coolant_1_amount_co2 = $value;
//                        $p->save();
////                        $value = ($value).toFixed(2);
////                        $('input[name=coolant_1_amount_co2]').val($value);
//                        break;
//                    case '2':
//                        $value = $value*11.7;
//                        $p->coolant_1_amount_co2 = $value;
//                        $p->save();
//                        break;
//                    case '3':
//                        $value = $value*1.43;
//                        $p->coolant_1_amount_co2 = $value;
//                        $p->save();
//                        break;
//                    case '4':
//                        $value = $value*3.985;
//                        $p->coolant_1_amount_co2 = $value;
//                        $p->save();
//                        break;
//                    case '5':
//                        $value = $value*3;
//                        $p->coolant_1_amount_co2 = $value;
//                        $p->save();
//                        break;
//                    case '6':
//                        $value = $value*1.774;
//                        $p->coolant_1_amount_co2 = $value;
//                        $p->save();
//                        break;
//                    default:
//
//                }
//                $value = $p->coolant_2_amount_kg;
//                switch($p->coolant_2_type){
//                    case '1':
//                        $value = $value*3.922;
//                        $p->coolant_2_amount_co2 = $value;
//                        $p->save();
////                        $value = ($value).toFixed(2);
////                        $('input[name=coolant_1_amount_co2]').val($value);
//                        break;
//                    case '2':
//                        $value = $value*11.7;
//                        $p->coolant_2_amount_co2 = $value;
//                        $p->save();
//                        break;
//                    case '3':
//                        $value = $value*1.43;
//                        $p->coolant_2_amount_co2 = $value;
//                        $p->save();
//                        break;
//                    case '4':
//                        $value = $value*3.985;
//                        $p->coolant_2_amount_co2 = $value;
//                        $p->save();
//                        break;
//                    case '5':
//                        $value = $value*3;
//                        $p->coolant_2_amount_co2 = $value;
//                        $p->save();
//                        break;
//                    case '6':
//                        $value = $value*1.774;
//                        $p->coolant_2_amount_co2 = $value;
//                        $p->save();
//                        break;
//                    default:
//
//                }
//            }
//        }
//
//        if(!empty($newproduct)){
//            if($newproduct->updated_at == NULL && $p->poznamky != '' ){
//                $newproduct->note = $p->poznamky;
//                $newproduct->save();
//            }
//        }



        echo 'OK';

//        $myseeder = new PhpZipSeeder();
//        $myseeder->run();
    }

    public function clearCache() {
        Cache::flush();
        opcache_reset();
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');

        $cachedViewsDirectory = app('path.storage').'/views/';
        $files = glob($cachedViewsDirectory.'*');
        foreach($files as $file) {
            if(is_file($file)) {
                @unlink($file);
            }
        }

        dd('Cleared.');
    }
}
