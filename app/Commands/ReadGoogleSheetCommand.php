<?php

namespace App\Commands;

use App\Services\GoogleSheetService;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;


class ReadGoogleSheetCommand extends Command
{

    protected $spreadsheetService;
    protected $signature = 'app:read-google-sheet';
    protected $description = 'Command description';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(GoogleSheetService $spreadsheetService)
    {
        $response = $spreadsheetService->getSpreadsheetData();
        dd($response);
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
