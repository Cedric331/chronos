<template>

    <div class="fixed fade z-10 inset-0 w-full h-full outline-none overflow-x-hidden overflow-y-auto" aria-labelledby="modification planning" role="dialog" aria-modal="true">

        <div class="flex items-end justify-center pt-4 px-4 pb-20 text-center sm:block sm:p-0 w-10/12 mb-6 mx-auto">
            <div @click="closeModal()" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left shadow-xl transform transition-all sm:my-4 sm:align-middle w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <section class="container p-6 mx-auto bg-white">
                            <h2 class="font-bold text-center text-3xl text-gray-800 md:text-2xl mb-5">
                                Modification du planning de {{ collaborateur.name }} {{radio === '1'}}
                            </h2>
                            <div v-if="errors" class="bg-red-100 rounded-lg py-5 px-6 mb-4 tex t-base text-red-700 mb-3" role="alert">
                                {{ errors }}
                            </div>
                             <div>
                                    <form class="rounded-lg w-auto">
                                        <div>
                                            <label class="text-gray-800 font-semibold block my-3 text-md">Type</label>
                                            <select v-model="radio" class="block w-full text-sm leading-4 font-medium rounded-md text-gray-500 rounded transition ease-in-out m-0">
                                                <option :value="null" selected> -- Veuillez sélectionner le type de planification --</option>
                                                <option value="1">
                                                    Planifié
                                                </option>
                                                <option value="2">
                                                    Non Planifié
                                                </option>
                                                <option value="3">
                                                    Congés payés
                                                </option>
                                            </select>
                                        </div>
                                        <div v-if="radio === '1'">
                                            <div>
                                                <label class="text-gray-800 font-semibold block my-3 text-md">Début journée</label>
                                                <select v-model="debut_journee" class="block w-full text-sm leading-4 font-medium rounded-md text-gray-500 rounded transition ease-in-out m-0">
                                                    <option v-for="(horaire, index) in horaires" :key="index" :value="horaire">
                                                        {{ horaire }}
                                                    </option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="text-gray-800 font-semibold block my-3 text-md">Début pause</label>
                                                <select v-model="debut_pause" class="block w-full text-sm leading-4 font-medium rounded-md text-gray-500 rounded transition ease-in-out m-0">
                                                    <option selected>Pas de pause</option>
                                                    <option v-for="(horaire, index) in horaires" :key="index" :value="horaire">
                                                        {{ horaire }}
                                                    </option>
                                                </select>
                                            </div>
                                            <div v-if="debut_pause !== null && debut_pause !== 'Pas de pause'">
                                                <label class="text-gray-800 font-semibold block my-3 text-md">Fin pause</label>
                                                <select v-model="fin_pause" class="block w-full text-sm leading-4 font-medium rounded-md text-gray-500 rounded transition ease-in-out m-0">
                                                    <option v-for="(horaire, index) in horaires" :key="index" :value="horaire">
                                                        {{ horaire }}
                                                    </option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="text-gray-800 font-semibold block my-3 text-md">Fin journée</label>
                                                <select v-model="fin_journee" class="block w-full text-sm leading-4 font-medium rounded-md text-gray-500 rounded transition ease-in-out m-0">
                                                    <option v-for="(horaire, index) in horaires" :key="index" :value="horaire">
                                                        {{ horaire }}
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="flex justify-start">
                                                <div class="form-check mt-6">
                                                    <input v-model="isTech" class="form-check-input appearance-none h-4 w-4 border border-gray-300 rounded-sm bg-white checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200 mt-1 align-top bg-no-repeat bg-center bg-contain float-left mr-2 cursor-pointer" type="checkbox">
                                                    <label class="form-check-label inline-block text-gray-800">
                                                        Technicien
                                                    </label>
                                                </div>
                                                <div class="form-check mt-6 ml-4">
                                                    <input v-model="teletravail" class="form-check-input appearance-none h-4 w-4 border border-gray-300 rounded-sm bg-white checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200 mt-1 align-top bg-no-repeat bg-center bg-contain float-left mr-2 cursor-pointer" type="checkbox">
                                                    <label class="form-check-label inline-block text-gray-800">
                                                        Télétravail
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                             </div>
                        </section>

                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button @click="submit()" type="button" class="mt-3 w-full inline-flex justify-center bg-blue-500 rounded-md border shadow-sm px-4 py-2 text-base font-medium text-white sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Valider</button>
                    <button @click="closeModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Fermer</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ModalUpdatePlanning",
    props: {
        selected: Array,
        collaborateur: Object
    },
    data () {
        return {
            horaires: [
                '8h00',
                '8h30',
                '9h00',
                '9h30',
                '10h00',
                '10h30',
                '11h00',
                '11h30',
                '12h00',
                '12h30',
                '13h00',
                '13h30',
                '14h00',
                '14h30',
                '15h00',
                '15h30',
                '16h00',
                '16h30',
                '17h00',
                '17h30',
                '18h00',
                '18h30',
                '19h00',
                '19h30',
                '20h00',
                '20h30',
                '21h00'
            ],
            errors: null,
            radio: null,
            isTech: false,
            debut_journee: null,
            debut_pause: 'Pas de pause',
            fin_pause: null,
            fin_journee: null,
            teletravail: false
        }
    },
    methods: {
        closeModal (bool = false) {
            this.$emit('closeModal', bool)
        },
        submit () {
                this.debut_pause === 'Pas de pause' ? this.debut_pause = null : ''
                axios.patch('planning/update', {
                    selected: this.selected,
                    user: this.collaborateur,
                    planification: this.radio,
                    isTech: this.isTech,
                    debut_journee: this.debut_journee,
                    debut_pause: this.debut_pause,
                    fin_pause: this.fin_pause,
                    fin_journee: this.fin_journee,
                    teletravail: this.teletravail,
                })
                .then(() => {
                    this.radio = null
                    this.isTech = false
                    this.debut_journee = null
                    this.debut_pause = 'Pas de pause'
                    this.fin_pause = null
                    this.fin_journee = null
                    this.teletravail = false
                    this.closeModal(true)
                })
                .catch(error => {
                    console.log(error)
                })
        },
        valideData () {
            this.errors = null
            if (this.radio === null) {
                this.errors = 'Vous n\'avez pas indiqué le type de planification'

                return false
            }
            if (this.radio === '1') {
                if (this.debut_journee === null || this.fin_journee === null) {
                    this.errors = 'Vous devez indiquer les horaires'
                    return false
                }
                if (this.debut_pause !== 'Pas de pause' && this.fin_pause ===  null) {
                    this.errors = 'Vous devez indiquer la fin de la pause'
                    return false
                }
                return true
            } else {
                this.debut_journee = null
                this.debut_pause = null
                this.fin_pause = null
                this.fin_journee = null

                return true
            }
        }
    }
}
</script>

<style scoped>

</style>
