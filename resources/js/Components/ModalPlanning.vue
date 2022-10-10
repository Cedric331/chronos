<template>

    <div class="fixed fade z-10 inset-0 w-full h-full outline-none overflow-x-hidden overflow-y-auto" aria-labelledby="planning par date" role="dialog" aria-modal="true">

        <div class="flex items-end justify-center pt-4 px-4 pb-20 text-center sm:block sm:p-0 w-10/12 mb-6 mx-auto">
            <div @click="closeModal()" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left shadow-xl transform transition-all sm:my-8 sm:align-middle sm:w-auto">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <section class="container p-6 mx-auto bg-white">
                            <h2 class="font-bold text-center text-3xl text-gray-800 capitalize md:text-2xl">{{ datePlanning.format }}</h2>
                            <div class="flex justify-around mt-6">
                                <div class="mr-2">
                                    <button v-if="datePlanning.previous !== null" @click="refreshData(datePlanning.previous, false)" class="flex items-center px-2 py-2 font-medium tracking-wide text-white capitalize transition-colors duration-200 transform bg-blue-600 rounded-md hover:bg-blue-500 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-80">
                                        <span class="mx-1">Précédent</span>
                                    </button>
                                </div>
                                <div>
                                    <button v-if="datePlanning.next !== null" @click="refreshData(datePlanning.next, true)" class="flex items-center px-2 py-2 font-medium tracking-wide text-white capitalize transition-colors duration-200 transform bg-blue-600 rounded-md hover:bg-blue-500 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-80">
                                        <span class="mx-1">Suivant</span>
                                    </button>
                                </div>
                            </div>

                            <div class="flex items-center justify-center">
                                <div class="grid gap-8 mt-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">

                                    <div v-for="planning in showDates" :key="planning.date" :style="colorPlanning(planning)" class="w-full rounded-md shadow-md shadow-dark">
                                        <div class="p-4">
                                            <div class="flex justify-between">
                                                <div>
                                                    <p class="font-semibold text-sm py-1" :style="texte">{{ planning.collaborateur }}</p>
                                                </div>
                                            </div>
                                            <div v-if="planning.horaires">
                                                <p class="font-light text-justify line-clamp-3" :style="texte">
                                                    {{ planning.horaires.teletravail ? 'Télétravail' : 'Hub'}} {{ planning.horaires.rotation ? ' - ' + planning.horaires.rotation : ''}}
                                                </p>
                                                <p class="font-light text-justify line-clamp-3" :style="texte">
                                                    Début : {{ planning.horaires.debut_journee }}
                                                </p>
                                                <div v-if="planning.horaires.debut_pause">
                                                    <p class="font-light text-justify line-clamp-3" :style="texte">
                                                        Pause Déj: {{ planning.horaires.debut_pause }}
                                                    </p>
                                                    <p class="font-light text-justify line-clamp-3" :style="texte">
                                                        Fin Déj : {{ planning.horaires.fin_pause }}
                                                    </p>
                                                </div>
                                                <p class="font-light text-justify line-clamp-3" :style="texte">
                                                    Fin : {{ planning.horaires.fin_journee }}
                                                </p>
                                            </div>
                                            <div v-else>
                                                <p class="font-light text-justify line-clamp-3" :style="texte">
                                                    {{ getType(planning.type) }}
                                                </p>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </section>

                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button @click="closeModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Fermer</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ModalPlanning",
    props: {
        showDates: Array,
        datePlanning: Object
    },
    data () {
        return {
            texte: this.colorTexte()
        }
    },
    methods: {
        getType (data) {
            switch (data) {
                case 'CP':
                    return 'Congés payés'
                    break;
                case 'FOR':
                    return 'Formation'
                    break;
                case 'RJF':
                    return 'Récup. jour férié'
                    break;
                case 'F':
                    return 'Férié'
                    break;
                default:
                    return 'Repos'
            }
        },
        colorTexte () {
            return this.$page.props.auth.user.color_texte ? 'color: ' + this.$page.props.auth.user.color_texte : '#000000'
        },
        colorPlanning (planning) {
            var colors = null

            if (planning.type === 'CP') {
                !this.$page.props.auth.user.color_conge ?  colors = 'background-color: #bfdbfe' : colors = 'background-color: ' + this.$page.props.auth.user.color_conge
            }

            if (planning.type === 'Iti') {
                !this.$page.props.auth.user.color_technicien ? colors = 'background-color: #fecaca' : colors = 'background-color: ' + this.$page.props.auth.user.color_technicien
            }

            if (planning.type !== 'Iti' &&  planning.type !== 'CP' && !planning.today) {
                !this.$page.props.auth.user.color_travaille ? colors = 'background-color: #dcfce7' : colors = 'background-color: ' + this.$page.props.auth.user.color_travaille
            }

            if (!planning.horaires && planning.type !== 'CP') {
                !this.$page.props.auth.user.color_repos ? colors = 'background-color: #a5f3fc' : colors = 'background-color: ' + this.$page.props.auth.user.color_repos
            }

            return colors
        },
        closeModal () {
            this.$emit('closeModal')
        },
        refreshData (data, next) {
            let index = next ? Number(this.datePlanning.index) + 1 : Number(this.datePlanning.index) - 1
            this.$emit('refreshViewDate', data, index)
        }
    }
}
</script>

<style scoped>

</style>
