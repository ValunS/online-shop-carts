<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\ExchangeRate;
use Generator;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Http;

class UpdateExchangesRateCommand extends Command
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
    public function handle()
    {
        $exchangeRates = ExchangeRate::all();

        foreach ($this->getCurrencies($exchangeRates) as $currency) {
            $this->updateExchangeRate($currency);
        }

        $this->info('Exchange rates updated successfully.');
        var_dump(ExchangeRate::select(['currency', 'rate'])->get()->toArray());
    }

    /**
     * Get currencies generator.
     *
     * @param  Collection  $exchangeRates
     * @return Generator
     */
    private function getCurrencies(Collection $exchangeRates): Generator
    {
        foreach ($exchangeRates as $exchangeRate) {
            yield $exchangeRate->currency;
        }
    }

    /**
     * Update exchange rate for a specific currency.
     *
     * @param  string  $currency
     * @return void
     */
    private function updateExchangeRate(string $currency)
    {
        // Используем RUB как основную валюту
        $response = Http::get("https://api.exchangerate-api.com/v4/latest/RUB"); // Пример API

        if ($response->successful()) {
            $data = $response->json();

            //  Получаем курс к рублю
            $rate = $data['rates'][$currency];

            ExchangeRate::where('currency', $currency)->update(['rate' => $rate]);

            $this->info("Exchange rate for {$currency} updated to {$rate}");
        } else {
            $this->error("Failed to update exchange rate for {$currency}");
        }
    }
}