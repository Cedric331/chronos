<template>
    <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="utilisateur" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div @click="closeModal()" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle w-auto">

                    <div class="mx-auto w-full border bg-white p-4">
                        <div class="mt-6 flex justify-between">
                            <div>
                                <label for="type" class="block mb-2 text-lg font-medium text-gray-700">*Nom de la Rotation</label>
                                <input v-model="type" type="text" id="type" max="3" class="border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="3 caractères maximum" required>
                            </div>
                            <div class="border-t-0 px-6 align-middle border-l-0 border-r-0 flex justify-between p-4">
                                <label class="inline-flex items-center mt-3">
                                    <input v-model="synchronise" :checked="synchronise" type="checkbox" class="form-checkbox h-5 w-5 text-black"><span class="ml-2 text-gray-700">Synchroniser les jours</span>
                                </label>
                            </div>
                        </div>

                        <div class="mt-6">
                            <div class="block w-full overflow-x-auto">
                                <table class="items-center bg-transparent w-full border-collapse ">
                                    <thead>
                                    <tr>
                                        <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                            Jour de la semaine
                                        </th>
                                        <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                            Technicien
                                        </th>
                                        <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                            Jour OFF
                                        </th>
                                        <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                           Début Journée
                                        </th>
                                        <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                            Début Pause Déjeuner
                                        </th>
                                        <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                            Fin Pause Déjeuner
                                        </th>
                                        <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                            Fin Journée
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(jour, days) in jours" :key="days">
                                            <th class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-lg whitespace-nowrap p-4 text-left font-bold">
                                                {{ days.charAt(0).toUpperCase() + days.slice(1) }}
                                            </th>
                                            <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                                <checkbox v-model="jours[days]['technicien']" :checked="jours[days]['technicien']"></checkbox>
                                            </td>
                                            <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                               <checkbox v-model="jours[days]['is_off']" :checked="jours[days]['is_off']"></checkbox>
                                            </td>
                                            <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                                <select @change="checkHours(jours[days], days); synchroniseValue(jours[days], 'debut_journee')" :disabled="jours[days]['is_off']" v-model="jours[days]['debut_journee']" class="block w-full text-sm leading-4 font-medium rounded-md text-gray-500 rounded transition ease-in-out m-0">
                                                    <option v-for="(horaire, index) in horaires" :key="index" :value="horaire">
                                                        {{ horaire }}
                                                    </option>
                                                </select>
                                            </td>
                                            <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                                <select @change="checkHours(jours[days], days); synchroniseValue(jours[days], 'debut_pause')" :disabled="jours[days]['is_off']" v-model="jours[days]['debut_pause']" class="block w-full text-sm leading-4 font-medium rounded-md text-gray-500 rounded transition ease-in-out m-0">
                                                    <option v-for="(horaire, index) in horaires" :key="index" :value="horaire">
                                                        {{ horaire }}
                                                    </option>
                                                </select>
                                            </td>
                                            <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                                <select @change="checkHours(jours[days], days); synchroniseValue(jours[days], 'fin_pause')" :disabled="jours[days]['is_off'] || !jours[days]['debut_journee'] || !jours[days]['debut_pause']" v-model="jours[days]['fin_pause']" class="block w-full text-sm leading-4 font-medium rounded-md text-gray-500 rounded transition ease-in-out m-0">
                                                    <option v-for="(horaire, index) in horaires" :key="index" :value="horaire">
                                                        {{ horaire }}
                                                    </option>
                                                </select>
                                            </td>
                                            <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                                <select @change="checkHours(jours[days], days); synchroniseValue(jours[days], 'fin_journee')" :disabled="jours[days]['is_off'] || !jours[days]['debut_journee']" v-model="jours[days]['fin_journee']" class="block w-full text-sm leading-4 font-medium rounded-md text-gray-500 rounded transition ease-in-out m-0">
                                                    <option v-for="(horaire, index) in horaires" :key="index" :value="horaire">
                                                        {{ horaire }}
                                                    </option>
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody>

                                </table>
                            </div>
                        </div>


                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-between sm:flex sm:flex-row-reverse">
                        <div>
                            <button @click="closeModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Annuler
                            </button>
                            <button :disabled="errors !== null" @click="submit()" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">
                                {{ isUpdate ? 'Modifier' : 'Créer' }}
                            </button>
                        </div>
                        <div v-if="errors" class="sm:align-middle font-medium text-lg text-red-600">
                            {{ errors }}
                        </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Checkbox from "@/Components/Checkbox";

export default {
    name: "ModalRotation",
    components: {Checkbox},
    props: {
        isUpdate: Boolean,
        rotation: Object,
    },
    data () {
        return {
            synchronise: false,
            jours: {
                lundi: {
                    'technicien': false,
                    'is_off': false,
                    'debut_journee': null,
                    'debut_pause': null,
                    'fin_pause': null,
                    'fin_journee': null,
                 },
                mardi: {
                    'technicien': false,
                    'is_off': false,
                    'debut_journee': null,
                    'debut_pause': null,
                    'fin_pause': null,
                    'fin_journee': null,
                },
                mercredi: {
                    'technicien': false,
                    'is_off': false,
                    'debut_journee': null,
                    'debut_pause': null,
                    'fin_pause': null,
                    'fin_journee': null,
                },
                jeudi: {
                    'technicien': false,
                    'is_off': false,
                    'debut_journee': null,
                    'debut_pause': null,
                    'fin_pause': null,
                    'fin_journee': null,
                },
                vendredi: {
                    'technicien': false,
                    'is_off': false,
                    'debut_journee': null,
                    'debut_pause': null,
                    'fin_pause': null,
                    'fin_journee': null,
                },
                samedi: {
                    'technicien': false,
                    'is_off': true,
                    'debut_journee': null,
                    'debut_pause': null,
                    'fin_pause': null,
                    'fin_journee': null,
                },
                dimanche: {
                    'technicien': false,
                    'is_off': true,
                    'debut_journee': null,
                    'debut_pause': null,
                    'fin_pause': null,
                    'fin_journee': null,
                },
    },
            horaires: [
                null,
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
            type: null,
            errors: null
        }
    },
    methods: {
        synchroniseValue (data, element) {
            if (this.synchronise) {
                Object.keys(this.jours).forEach((item, key) => {
                    if (!this.jours[item]['is_off']) {
                        this.jours[item][element] = data[element]
                    }
                })
            }
        },
        checkHours (data, days) {
            var debut_journee = data['debut_journee'] ? data['debut_journee'].split('h') : null
            var debut_pause =  data['debut_pause'] ? data['debut_pause'].split('h') : null
            var fin_pause = data['fin_pause'] ? data['fin_pause'].split('h') : null
            var fin_journee = data['fin_journee'] ? data['fin_journee'].split('h') : null

            if (debut_journee) {
                var debut_journee = (+debut_journee[0]) * 60 * 60 + (+debut_journee[1]) * 60;
            }
            if (debut_pause) {
                var debut_pause = (+debut_pause[0]) * 60 * 60 + (+debut_pause[1]) * 60;
            }
            if (fin_pause) {
                var fin_pause = (+fin_pause[0]) * 60 * 60 + (+fin_pause[1]) * 60;
            }
            if (fin_journee) {
                var fin_journee = (+fin_journee[0]) * 60 * 60 + (+fin_journee[1]) * 60;
            }

            if (debut_journee && fin_journee) {
                if (debut_journee > fin_journee) {
                    this.errors = 'Le début de journée de ' + days + ' doit commencer avant la fin de journée'
                } else {
                    this.errors = null
                }
            }
            if (debut_pause && fin_pause) {
                if (debut_pause > fin_pause) {
                    this.errors = 'Le début de pause de ' + days + ' doit commencer avant la fin de pause'
                } else {
                    this.errors = null
                }
            }
        },
        submit () {
          if (this.isUpdate) {
              this.update()
        } else {
              this.store()
          }
        },
        store () {
            axios.post('rotation', {
                type: this.type,
                jours: this.jours,
            })
            .then(res => {
                this.$emit('storeRotation', res.data)
                this.closeModal()
            })
        },
        update () {
            axios.patch('rotation', {
                id: this.rotation.id,
                type: this.type,
                jours: this.jours,
            })
                .then(res => {
                    this.$emit('storeRotation', res.data, true)
                    this.closeModal()
                })
        },
        closeModal () {
            this.type = null
            this.errors = null
            this.$emit('closeModal', false)
        },
    },
    mounted () {
        if (this.isUpdate) {
            this.rotation.rotations.forEach(item => {
                this.type = this.rotation.type
                this.jours[item.day] = JSON.parse(item.horaire)
                this.jours[item.day]['id'] = item.id
            })
        }
    }
}
</script>

<style scoped>

</style>
