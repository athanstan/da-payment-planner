<?php

namespace App\Commands;

use App\Services\GoogleSheetService;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Mail\Mailer;
use LaravelZero\Framework\Commands\Command;

class SendExpiredHostingMailCommand extends Command
{

    protected $signature = 'app:send-expired-hosting-mail';
    protected $description = 'Reads data from the Google Sheet and sends an email to the client if their hosting is expired.';

    protected $mailer; // mailer object
    protected $daysBeforeExpiration = 15; // send email if hosting expires in 15 days

    protected $emailsSent = []; // list of emails sent
    protected $emailsNotSent = []; // list of emails not sent

    public function __construct(Mailer $mailer)
    {
        parent::__construct();

        $this->mailer = $mailer;
    }

    public function handle(GoogleSheetService $spreadsheetService)
    {
        #get data from google sheet
        $response = $spreadsheetService->getSpreadsheetData();

        #iterate through data and send email to each client
        foreach ($response as $key => $hostingRow) {

            $expirationDate = Carbon::createFromFormat('d/m/Y', $hostingRow->{"Expiration Date"});
            $now = Carbon::now();
            $cost = $hostingRow->{"Cost"};
            $ownerEmail = $hostingRow->{"Owner Email"};

            if ($expirationDate->diffInDays($now) <= $this->daysBeforeExpiration && is_numeric($cost)) {

                if ($cost == 0) {
                    $this->emailsNotSent[$ownerEmail] = 'Free hosting'; // add email to the not sent list with reason
                    continue; // skip free hosting
                }

                if ($key == 5) break; // comment this line to send email to all clients

                $this->sendHostingExpirationEmail($hostingRow);
                $emailsSent[] = $ownerEmail; // add email to the sent list
                $this->info("Email has been sent to {$ownerEmail}");
            } else {
                $emailsNotSent[$ownerEmail] = 'Hosting does not expire soon or cost is not numeric'; // add email to the not sent list with reason
            }
        }

        # send report email
        $this->sendReportEmail();

    }

    /**
     * Sends an email to the client based on hosting data.
     *
     * @param object $hostingRow
     * @return void
     */
    protected function sendHostingExpirationEmail($hostingRow): void
    {
        $this->mailer->send('email', ['hostingRow' => $hostingRow], function ($message) use ($hostingRow) {
            $message->subject('Your hosting is about to expire!')
                ->to($hostingRow->{"Owner Email"})
                ->from('support@dtek.gr', 'DTek Networking');
        });
    }

    /**
     * Sends the report email containing sent and unsent emails.
     *
     * @return void
     */
    protected function sendReportEmail(): void
    {
        $this->mailer->send('emails.report', ['emailsSent' => $this->emailsSent, 'emailsNotSent' => $this->emailsNotSent], function ($message) {
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
