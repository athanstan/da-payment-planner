<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Mail\Mailer;
use LaravelZero\Framework\Commands\Command;

class TestMailFunctionCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'app:test-mail';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Command description';
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
    public function handle()
    {
        try {
            $this->mailer->raw('Mailing functions are working correctly! Good Morning Dtek Workers and have a great week day! Remember, be like water my friends!', function ($message) {
                $message->subject('Test Email')
                        ->to('support@dtek.gr') // replace with your email
                        ->from('support@dtek.gr', 'DTek Networking');
            });

            $this->info("Test email has been sent successfully!");
        } catch (\Exception $e) {
            $this->error("Failed to send test email. Error: {$e->getMessage()}");
        }
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
