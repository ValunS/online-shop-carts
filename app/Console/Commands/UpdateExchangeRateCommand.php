<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\ExchangeRate;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class UpdateExchangeRateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exchange-rates:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update exchange rates from external API';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $exchangeRates = ExchangeRate::all();

        foreach ($exchangeRates as $exchangeRate) {
            $this->updateExchangeRate($exchangeRate);
        }

        var_dump(ExchangeRate::select(['currency', 'rate'])->get()->toArray());
        $this->info('Exchange rates updated successfully.');
    }

    /**
     * Update exchange rate for a specific currency.
     *
     * @param  ExchangeRate  $exchangeRate
     * @return void
     */
    private function updateExchangeRate(ExchangeRate $exchangeRate): void
    {
        $currency = $exchangeRate->currency;

        $response = Http::get("https://api.exchangerate-api.com/v4/latest/$currency");

        if ($response->successful()) {
            $data = $response->json();

            // В рублях
            $rate = $data['rates']['RUB']; //  'RUB' 

            $exchangeRate->update(['rate' => $rate]);

            $this->info("Exchange rate for {$currency} updated to {$rate}");
        } else {
            $this->error("Failed to update exchange rate for {$currency}");
        }
    }
}