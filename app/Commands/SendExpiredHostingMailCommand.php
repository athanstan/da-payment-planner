<?php

namespace App\Commands;

use App\Services\GoogleSheetService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Mail\Mailer;
use LaravelZero\Framework\Commands\Command;

class SendExpiredHostingMailCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'app:send-expired-hosting-mail';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Reads data from the google sheet and sends an email to the client if their hosting is expired.';

    protected $mailer;

    /**
     * Create a new command instance.
     *
     * @param Mailer $mailer
     * @return void
     */
    public function __construct(Mailer $mailer)
    {
        parent::__construct();

        $this->mailer = $mailer;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(GoogleSheetService $spreadsheetService)
    {

        #get data from google sheet
        $response = $spreadsheetService->getSpreadsheetData();

        #iterate through data and send email to each client
        foreach ($response as $key => $hostingRow) {
            if ($hostingRow->Period == 1) {

                // $this->mailer->raw('This is a test email from Laravel Zero.', function ($message) use ($email) {
                //     $message->subject('Test Email')
                //         ->to($email)
                //         ->from('you@domain.com');
                // });

                $this->info("Email has been sent to {$hostingRow->{"Owner Email"}}");
            }
        }



        // $this->info("Email has been sent to {$email}");
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
