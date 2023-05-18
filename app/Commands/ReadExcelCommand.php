<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use Spatie\SimpleExcel\SimpleExcelReader;

class ReadExcelCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'app:read-excel {--filter=}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Display the entries of the Excel file in the console, as a table.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $pathToExcelFile = 'assets/excel-data.xlsx';
        $filter = $this->option('filter');
        $rows = SimpleExcelReader::create($pathToExcelFile)->getRows();
        $columns = ['Host', 'Site Name', 'Expiration Date', 'Support Active', 'Bandwidth', 'Size of Website', 'Owner Email'];

        try {
            if ($filter == 'active') {

                $rows = $rows->filter(function (array $row) {
                    return $row['Support Active'] === 'No';
                });

            }elseif ($filter == 'email') {

                $rows = $rows->filter(function (array $row) {
                    return $row['Owner Email'] !== '-' && $row['Owner Email'] !== '';
                });
            }elseif ($filter == 'bandWidth') {

                $rows = $rows->filter(function (array $row) {
                    return $row['Bandwidth'] !== '-' && $row['Owner Email'] !== '';
                });

            }else{
                // do nothing
            }

            $this->table($columns, $rows);

        } catch (\Exception $e) {

            // handle the exception here
            echo "Error: " . $e->getMessage();

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
