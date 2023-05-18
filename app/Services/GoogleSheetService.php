<?php

namespace App\Services;
use Illuminate\Support\Facades\Cache;
use SheetDB\SheetDB;

class GoogleSheetService
{
    protected $apiKey;
    protected $cacheKey;

    public function __construct()
    {
        $this->apiKey = env('SHEETDB_SHEET_API_KEY');
        $this->cacheKey = 'spreadsheet_data';
    }

    public function getSpreadsheetData()
    {
        if (Cache::has($this->cacheKey)) {
            return Cache::get($this->cacheKey);
        } else {
            $sheetdb    = new SheetDB($this->apiKey); // create object
            // Cache doesn't exist, so fetch the data from the spreadsheet
            $response   = $sheetdb->get(); // returns all spreadsheets data

            // Cache the excel data for 12 hours
            Cache::put($this->cacheKey, $response, now()->addHours(12));

            return $response;
        }
    }
}
