<?php

namespace App\Commands;

use App\Services\GoogleSheetService;
use Carbon\Carbon;
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
        $daysBeforeExpiration = 15;

        # initialize the arrays
        $emailsSent = [];
        $emailsNotSent = [];

        #iterate through data and send email to each client
        foreach ($response as $key => $hostingRow) {

            $expirationDate = Carbon::createFromFormat('d/m/Y', $hostingRow->{"Expiration Date"});
            $now = Carbon::now();
            $cost = $hostingRow->{"Cost"};
            $ownerEmail = $hostingRow->{"Owner Email"};
            $period = $hostingRow->{"Period"};

            if ($expirationDate->diffInDays($now) <= $daysBeforeExpiration && is_numeric($cost)) {

                if ($cost == 0) {
                    $emailsNotSent[$ownerEmail] = 'Free hosting'; // add email to the not sent list with reason
                    continue; // skip free hosting
                }

                // if ($key == 5) break; // comment this line to send email to all clients

                $this->mailer->send('email', ['hostingRow' => $hostingRow], function ($message) use ($hostingRow) {
                    $message->subject('Your hosting is about to expire!')
                    ->to($hostingRow->{"Owner Email"})
                    ->from('support@dtek.gr', 'DTek Networking');
                });

                $emailsSent[] = $ownerEmail; // add email to the sent list
                $this->info("Email has been sent to {$ownerEmail}");
            } else {
                $emailsNotSent[$ownerEmail] = 'Hosting does not expire soon or cost is not numeric'; // add email to the not sent list with reason
            }
        }

        # send report email
        $this->mailer->send('emails.report', ['emailsSent' => $emailsSent, 'emailsNotSent' => $emailsNotSent], function ($message) {
            $message->subject('Email Sending Report')
                    ->to('support@dtek.gr')
                    ->from('support@dtek.gr', 'DTek Networking');
        });

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
