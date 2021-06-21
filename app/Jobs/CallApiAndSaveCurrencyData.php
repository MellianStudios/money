<?php

namespace App\Jobs;

use App\Events\CurrencyDataUpdated;
use App\Models\CurrencyPair;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CallApiAndSaveCurrencyData
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /*
         * This process is nonsense created just to demonstrate functionality.
         *
         * HTTP requests are too slow for this and using PHP as websocket client is just wrong.
         * It would require infinite loop for thread to stay open.
         * Node.js would be better as it can be booted and ran as process.
         *
         * 3rd party APIs are not good tool for this purpose as they are expensive and limited unnecessary middle step.
         * In real application we should connect directly to source
         *
         * Also this process is not optimal we should not pull data from multiple threads.
         * We should have 1 source of all needed data in continuous stream to minimize delay.
         */

        $minimum = Config::get('currency.minimum');
        $base_currency = Config::get('currency.base_currency');
        $currency_general_api = Config::get('currency.api.general');
        $currency_order_book_api = Config::get('currency.api.order_book');

        $currency_pairs = CurrencyPair::all();

        $trades = [];
        $currency_prices = [];

        // This should run in parallel not in loop
        // PHP by default can't make parallel processes
        foreach ($currency_pairs as $currency_pair) {
            $current_base_currency_to_base_currency = null;

            try {
                // I'm assuming that base currency (usd) makes pair with each base currency from all pairs
                // That is probably not true
                if ($currency_pair->baseCurrency->short !== $base_currency && $currency_pair->targetCurrency->short !== $base_currency) {
                    $current_base_currency_to_base_currency = Http::get($currency_general_api . strtolower($currency_pair->baseCurrency->short . $base_currency));
                }

                $pair_code = strtolower($currency_pair->baseCurrency->short . $currency_pair->targetCurrency->short);

                $current_currency_pair_general = Http::get($currency_general_api . $pair_code);
                $current_currency_order_book = Http::get($currency_order_book_api . $pair_code);

                // Silencing all errors as this process is not meant for real use and i don't even know what we would like to do with them
                if (
                    (isset($current_base_currency_to_base_currency) && $current_base_currency_to_base_currency->status() !== 200)
                    ||
                    $current_currency_pair_general->status() !== 200
                    ||
                    $current_currency_order_book->status() !== 200
                ) {
                    continue;
                }
            } catch (\Exception $exception) {
                continue;
            }

            $current_currency_pair_general = $current_currency_pair_general->json();
            $current_currency_order_book = $current_currency_order_book->json();

            $current_minimum = $minimum / $current_currency_pair_general['last'];

            if (isset($current_base_currency_to_base_currency)) {
                $base_currency_to_current_pair_base_currency = $current_base_currency_to_base_currency->json();

                $current_minimum = $minimum / $base_currency_to_current_pair_base_currency['last'];
            }

            $order_book_time = Carbon::createFromTimestamp($current_currency_order_book['timestamp']);

            foreach ($current_currency_order_book['asks'] as $ask) {
                if ($ask[1] > $current_minimum && $ask[0] > $current_currency_pair_general['last']) {
                    $trades[] = [
                        'currency_pair_id' => $currency_pair->id,
                        'operation' => 'ask',
                        'amount' => $ask[1],
                        'price' => $ask[0],
                        'created_at' => $order_book_time,
                        'updated_at' => $order_book_time,
                    ];
                }
            }

            foreach ($current_currency_order_book['bids'] as $bid) {
                if ($bid[1] > $current_minimum && $bid[0] < $current_currency_pair_general['last']) {
                    $trades[] = [
                        'currency_pair_id' => $currency_pair->id,
                        'operation' => 'bid',
                        'amount' => $bid[1],
                        'price' => $bid[0],
                        'created_at' => $order_book_time,
                        'updated_at' => $order_book_time,
                    ];
                }
            }

            $general_data_time = Carbon::createFromTimestamp($current_currency_pair_general['timestamp']);

            $currency_prices[] = [
                'currency_pair_id' => $currency_pair->id,
                'last' => $current_currency_pair_general['last'],
                'high' => $current_currency_pair_general['high'],
                'low' => $current_currency_pair_general['low'],
                'volume' => $current_currency_pair_general['volume'],
                'bid' => $current_currency_pair_general['bid'],
                'ask' => $current_currency_pair_general['ask'],
                'created_at' => $general_data_time,
                'updated_at' => $general_data_time,
            ];
        }

        CurrencyDataUpdated::dispatch(collect($currency_prices)->keyBy('currency_pair_id'));

        DB::table('currency_pair_prices')->insert($currency_prices);

        DB::table('currency_pair_trades')->insert($trades);
    }
}
