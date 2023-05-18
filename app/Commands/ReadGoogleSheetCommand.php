<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Cache;
use LaravelZero\Framework\Commands\Command;
use SheetDB\SheetDB;

class ReadGoogleSheetCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'app:read-google-sheet';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $apiKey = env('SHEETDB_SHEET_API_KEY');
        $cacheKey = 'spreadsheet_data';

         // Check if the excel data is already cached
         if (Cache::has($cacheKey)) {
            $response = Cache::get($cacheKey);
        }else {
            // Cache doesn't exist, so fetch the data from the spreadsheet
            $sheetdb    = new SheetDB($apiKey); // create object
            $response   = $sheetdb->get(); // returns all spreadsheets data
            Cache::put($cacheKey, $response, now()->addHours(12));
        }

        // No matter what, we should have the data now
        // $data = $response->whereJsonContains('Support Active', 'No');

        // Cache the excel data for 12 hours
        dd($response[0]->Host);
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
