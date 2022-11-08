<template>
    <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div @click="this.$emit('closeConfirm')" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="inline-block align-bottom p-4 bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <h1 class="text-center font-bold text-xl">Nombre de jours fériés travaillés en {{annee}}</h1>
                <Pie
                    v-if="loaded"
                    :chart-options="chartOptions"
                    :chart-data="chartData"
                    :chart-id="chartId"
                    :dataset-id-key="datasetIdKey"
                    :plugins="plugins"
                    :css-classes="cssClasses"
                    :styles="styles"
                    :width="width"
                    :height="height"
                />
                <div v-else class="flex justify-center w-full my-5">
                    Aucune information disponible
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Pie } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, ArcElement, CategoryScale, LinearScale } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, ArcElement, CategoryScale, LinearScale)

export default {
    name: 'ModalChart',
    components: { Pie },
    props: {
        annee: {
            type: Number,
            default: 0
        },
        chartId: {
            type: String,
            default: 'pie-chart'
        },
        datasetIdKey: {
            type: String,
            default: 'label'
        },
        width: {
            type: Number,
            default: 100
        },
        height: {
            type: Number,
            default: 100
        },
        cssClasses: {
            default: 'z-30',
            type: String
        },
        styles: {
            type: Object,
            default: () => {}
        },
        plugins: {
            type: Object,
            default: () => {}
        }
    },
    data() {
        return {
            loaded: false,
            chartData: {
                labels: [],
                datasets: [{
                    label: 'Nombre de jours Fériés travaillés',
                    data: [],
                    backgroundColor: [
                        'rgb(255, 80, 132)',
                        'rgb(99, 10, 132)',
                        'rgb(60, 74, 215)',
                        'rgb(1, 201, 150)',
                        'rgb(255, 205, 86)',
                        'rgb(224, 78, 78)',
                        'rgb(60, 223, 221)',
                        'rgb(30, 201, 21)',
                        'rgb(203, 64, 201)'
                    ],
                    hoverOffset: 9
                }]
            },
            chartOptions: {
                responsive: true
            }
        }
    },
    methods: {
        getData () {
            let uses = []
            this.loaded = false
            axios.get('/chart/' + this.annee)
                .then(response => {
                    response.data.forEach(item => {
                        this.chartData.labels.push(item.name)
                        uses.push(item.jours_ferie_count)
                    })
                    this.chartData.datasets[0].data = uses
                })
                .catch(err => {
                    console.log(err)
                })
            .finally(() => {
                this.loaded = true
            })
        }
    },
    mounted () {
        this.getData()
    }
}
</script>
