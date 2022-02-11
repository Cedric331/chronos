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
                                <div>
                                    <button v-if="datePlanning.previous !== null" @click="refreshData(datePlanning.previous, false)" class="flex items-center px-2 py-2 font-medium tracking-wide text-white capitalize transition-colors duration-200 transform bg-blue-600 rounded-md hover:bg-blue-500 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-80">
                                        <span class="mx-1">Précédant</span>
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

                                    <div v-for="planning in showDates" :key="planning.date" :class="[planning.type === 'CP' ? 'bg-blue-200' : '', planning.type === 'Iti' ? 'bg-red-200' : '',  planning.type !== 'Iti' &&  planning.type !== 'CP' ? 'bg-green-100' : '']" class="w-full rounded-md shadow-md shadow-dark">
                                        <div class="p-4">
                                            <div class="flex justify-between">
                                                <div>
                                                    <p class="font-semibold text-lg py-2">{{ planning.collaborateur }}</p>
                                                </div>
                                            </div>
                                            <div v-if="planning.horaires">
                                                <p class="font-light text-gray-700 text-justify line-clamp-3">
                                                    Début : {{ planning.horaires.debut_journee }}
                                                </p>
                                                <div v-if="planning.horaires.debut_pause">
                                                    <p class="font-light text-gray-700 text-justify line-clamp-3">
                                                        Pause Déj: {{ planning.horaires.debut_pause }}
                                                    </p>
                                                    <p class="font-light text-gray-700 text-justify line-clamp-3">
                                                        Fin Déj : {{ planning.horaires.fin_pause }}
                                                    </p>
                                                </div>
                                                <p class="font-light text-gray-700 text-justify line-clamp-3">
                                                    Fin : {{ planning.horaires.fin_journee }}
                                                </p>
                                            </div>
                                            <div v-else>
                                                <p class="font-light text-gray-700 text-justify line-clamp-3">
                                                    {{planning.type === 'CP' ? 'Congés payés' : 'Non planifié'}}
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
    methods: {
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
