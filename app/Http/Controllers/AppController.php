<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use URL;
use DB;
use App\Http\Requests;
use Artisan;
use Cache;


class AppController extends Controller
{
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
//        Artisan::call('migrate:reset');
        Artisan::call('migrate');
//        Artisan::call('db:seed');

        $migrations = DB::select('SELECT migration FROM migrations');
        foreach ($migrations as $i => $m)
        {
            $migrations[$i] = $m->migration;
        }

        dd($migrations);
    }

    public function clearCache() {
        Cache::flush();
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

        dd('Cleared!');
    }
}
