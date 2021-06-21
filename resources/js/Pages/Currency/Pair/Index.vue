<template>
    <div class="h-screen dark:bg-gray-800">
        <div class="grid justify-items-stretch">
            <h1 class="mb-4 font-bold text-3xl dark:text-white justify-self-center mt-2">Currency Pairs</h1>
            <div class="w-11/12 bg-white justify-self-center dark:bg-gray-700 rounded-md shadow overflow-x-auto">
                <table class="w-full whitespace-nowrap dark:text-white">
                    <tr class="h-12 text-left font-bold">
                        <th class="px-2 py-1.5 text-center">Pair</th>
                        <th class="px-2 py-1.5 text-center">Last</th>
                        <th class="px-2 py-1.5 text-center">Bid</th>
                        <th class="px-2 py-1.5 text-center">Ask</th>
                        <th class="px-2 py-1.5 text-center">High</th>
                        <th class="px-2 py-1.5 text-center">Low</th>
                    </tr>
                    <tr v-for="pair in pairs" class="h-14 dark:hover:bg-gray-500 hover:bg-gray-100 focus-within:bg-gray-100">
                        <td class="border-t text-center">
                            <span class="px-2 py-1.5">
                                {{ pair.base_currency.short + '/' + pair.target_currency.short }}
                            </span>
                        </td>
                        <td class="border-t text-center">
                            <span class="px-2 py-1.5">
                                {{ currency_data !== null ? currency_data[pair.id].last : pair.latest_price.last }}
                            </span>
                        </td>
                        <td class="border-t text-center">
                            <span class="px-2 py-1.5">
                                {{ currency_data !== null ? currency_data[pair.id].bid : pair.latest_price.bid }}
                            </span>
                        </td>
                        <td class="border-t text-center">
                            <span class="px-2 py-1.5">
                                {{ currency_data !== null ? currency_data[pair.id].ask : pair.latest_price.ask }}
                            </span>
                        </td>
                        <td class="border-t text-center">
                            <span class="px-2 py-1.5">
                                {{ currency_data !== null ? currency_data[pair.id].high : pair.latest_price.high }}
                            </span>
                        </td>
                        <td class="border-t text-center">
                            <span class="px-2 py-1.5">
                                {{ currency_data !== null ? currency_data[pair.id].low : pair.latest_price.low }}
                            </span>
                        </td>
                    </tr>
                    <tr v-if="pairs.length === 0">
                        <td class="border-t px-6 py-4" colspan="4">No records found.</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        pairs: Object,
    },
    data() {
        return {
            currency_data: null,
        }
    },
    created() {
        Echo.channel('currency_prices').listen('CurrencyDataUpdated', event => {
            this.currency_data = event.currency_prices;
        });
    },
}
</script>
