<template>
    <div class="h-screen dark:bg-gray-800">
        <div class="grid grid-cols-2 max-w-3xl">
            <div class="col-span-2 h-14 text-center">
                <h1 class="mb-4 font-bold text-4xl dark:text-white mt-2">{{ pair.base_currency.short + '/' + pair.target_currency.short }}</h1>
            </div>
            <div class="flex h-24 w-1/2 justify-self-end justify-center mr-1" :class="bid_color">
                <span class="font-bold text-3xl self-center">{{ currency_data !== null ? currency_data[pair.id].bid : pair.latest_price.bid }}</span>
            </div>
            <div class="flex h-24 w-1/2 justify-self-start justify-center ml-1" :class="ask_color">
                <span class="font-bold text-3xl self-center">{{ currency_data !== null ? currency_data[pair.id].ask : pair.latest_price.ask }}</span>
            </div>
            <div class="flex w-3/4 justify-self-center col-span-2 dark:bg-gray-700 h-24 justify-center">
                <span class="font-bold text-3xl self-center">Spread {{ currency_data !== null ? Math.abs(currency_data[pair.id].bid - currency_data[pair.id].ask) : Math.abs(pair.latest_price.bid - pair.latest_price.ask) }}</span>
            </div>
            <div class="col-span-2">
                <canvas id="chart"></canvas>
            </div>
        </div>
    </div>
</template>

<script>
import Chart from 'chart.js';
import moment from 'moment';

export default {
    components: {
        Chart,
    },
    props: {
        pair: Object,
        labels: Object,
        bids: Object,
        asks: Object,
    },
    data() {
        return {
            currency_data: null,
            bid_color: 'white',
            ask_color: 'white',
            chart_data: {
                type: "line",
                options: {
                    responsive: true,
                    lineTension: 1,
                    scales: {
                        yAxes: [
                            {
                                ticks: {
                                    beginAtZero: true,
                                    padding: 25
                                }
                            }
                        ]
                    }
                },
                data: {
                    labels: this.labels,
                    datasets: [
                        {
                            label: "Bids",
                            data: this.bids,
                            backgroundColor: "rgba(180,73,93,.5)",
                            borderColor: "#36495d",
                            borderWidth: 3
                        },
                        {
                            label: "Asks",
                            data: this.asks,
                            backgroundColor: "rgba(20, 60,180,.5)",
                            borderColor: "#47b784",
                            borderWidth: 3
                        }
                    ]
                },
            },
        }
    },
    created() {
        Echo.channel('currency_prices').listen('CurrencyDataUpdated', event => {
            if (this.currency_data === null) {
                if (this.pair.latest_price.bid > event.currency_prices[this.pair.id].bid) {
                    this.bid_color = 'red';
                } else {
                    this.bid_color = 'green';
                }

                if (this.pair.latest_price.ask < event.currency_prices[this.pair.id].ask) {
                    this.ask_color = 'red';
                } else {
                    this.ask_color = 'green';
                }
            } else {
                if (this.currency_data[this.pair.id].bid > event.currency_prices[this.pair.id].bid) {
                    this.bid_color = 'red';
                } else {
                    this.bid_color = 'green';
                }

                if (this.currency_data[this.pair.id].ask < event.currency_prices[this.pair.id].ask) {
                    this.ask_color = 'red';
                } else {
                    this.ask_color = 'green';
                }
            }

            if (this.labels.length > 19) {
                this.labels.shift();
                this.bids.shift();
                this.asks.shift();
            }


            this.labels.push(moment(new Date(event.currency_prices[this.pair.id].created_at)).format('DD.MM.YYYY HH:mm:ss'));
            this.bids.push(event.currency_prices[this.pair.id].bid);
            this.asks.push(event.currency_prices[this.pair.id].ask);

            this.createChart();

            this.currency_data = event.currency_prices;
        });
    },
    mounted() {
        this.createChart();
    },
    methods: {
        createChart() {
            const ctx = document.getElementById('chart');
            new Chart(ctx, this.chart_data);
        }
    }
}
</script>

<style>
.white {
    background-color: white;
}

.red {
    background-color: red;
}

.green {
    background-color: lightgreen;
}
</style>
