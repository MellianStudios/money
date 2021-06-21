<?php

namespace App\Http\Controllers;

use App\Models\CurrencyPair;
use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class CurrencyPairController extends Controller
{
    /**
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render('Currency/Pair/Index', [
            'pairs' => CurrencyPair::with('latestPrice')->get(),
        ]);
    }

    /**
     * @param int $id
     *
     * @return Response
     */
    public function show(int $id): Response
    {
        $pair = CurrencyPair::where('id', $id)->with([
            'prices' => function ($query) {
                $query->orderBy('created_at', 'desc')->take(20);
            },
            'latestPrice'
        ])->firstOrFail();

        foreach ($pair->prices as $price) {
            $labels[] = Carbon::make($price->created_at)->format('d.m.Y H:i:s');
            $bids[] = $price->bid;
            $asks[] = $price->ask;
        }

        return Inertia::render('Currency/Pair/Show', [
            'pair' => $pair,
            'labels' => array_reverse($labels),
            'bids' => array_reverse($bids),
            'asks' => array_reverse($asks),
        ]);
    }
}
