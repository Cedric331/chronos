<template>
    <notifications position="bottom right" />
    <Head>
        <title>Gestion des jours fériés</title>
    </Head>
        <div class="flex overflow-hidden">
            <div class="h-full w-full bg-white relative overflow-y-auto lg:m-24 mx-auto p-5">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Gestion des jours fériés</h3>
                </div>
                    <div class="pt-6 px-4">
                        <div class="w-full gap-4 flex flex-col justify-between">
                            <div>
                                <div class="w-full flex justify-start mx-auto p-2">
                                    <div class="w-auto flex justify-between">
                                        <div>
                                            <label class="text-sm">Année : </label>
                                            <input type="number" v-model="annee" class="text-sm" minlength="4" maxlength="4" placeholder="2022...">
                                        </div>
                                        <div>
                                            <select v-model="zone" class="block mx-5 text-sm font-normal rounded transition ease-in-out m-0" aria-label="sélection de la zone">
                                                <option value="metropole" selected>
                                                    Métropole
                                                </option>
                                                <option value="alsace-moselle">
                                                    Alsace-moselle
                                                </option>
                                            </select>
                                        </div>
                                        <div>
                                            <button @click="generateFerie()" class="text-sm font-medium hover:bg-gray-700 bg-black text-white rounded-lg p-2">
                                                Générer les jours fériés
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <span class="font-light text-sm text-gray-600">
                                    *Limiter à 3 ans dans le futur
                                </span>
                                <div class="border border-4 shadow-2xl my-5 w-full mx-auto rounded-md p-16 flex flex-col sm:flex-row sm:justify-evenly">
                                    <div class="p-16 flex flex-col bg-white rounded-lg">
                                        <div class="w-auto flex justify-start">
                                            <div>
                                                <select v-model="selectYear" class="block w-full text-base font-normal text-gray-700 bg-white bg-clip-padding bg-no-repeat border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600" aria-label="sélection de l'année">
                                                    <option v-for="(value, year) in years" :key="year" :value="value" selected>
                                                        {{ year }}
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="ml-5">
                                                <button :disabled="!this.selectYear" @click="this.openChart = true" class="text-sm font-medium hover:bg-gray-700 bg-black text-white rounded-lg p-2">
                                                    Voir graphique
                                                </button>
                                            </div>
                                        </div>

                                        <div class="inset-y-0 right-0 w-full flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
                                                <table class="w-full text-left">
                                                    <thead class="uppercase bg-white">
                                                    <tr>
                                                        <th scope="col" class="px-6 py-3">
                                                            Jour Férié
                                                        </th>
                                                        <th scope="col" class="px-6 py-3">
                                                            Date
                                                        </th>
                                                        <th scope="col" class="px-6 py-3">
                                                            Conseillers
                                                        </th>
                                                        <th scope="col" class="px-6 py-3">
                                                            <span class="sr-only">Éditer</span>
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr v-for="ferie in selectYear" class="border-b bg-gray-800">
                                                        <th scope="row" class="px-6 py-4 font-medium text-white whitespace-nowrap">
                                                            {{ ferie.name }}
                                                        </th>
                                                        <td class="px-6 py-4 text-white">
                                                            {{ ferie.date }}
                                                        </td>
                                                        <td class="px-6 py-4 text-white">
                                                            <div v-if="ferie.collaborateurs.length > 0">
                                                                 <span v-for="collaborateur in ferie.collaborateurs">
                                                                    {{ collaborateur.name }}<br>
                                                                </span>
                                                            </div>
                                                            <div v-else>
                                                                <span>
                                                                    - Aucun Conseiller -
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4 text-right">
                                                            <button @click="openModal(ferie)" class="bg-blue-700 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-full">
                                                                Modifier
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    <ModalConfirmDelete
        v-if="confirmDel"
        @closeModal="this.confirmDel = false"
        @deleted="this.delete()"
    />
    <ModalUpdatePlanningFerie
        v-if="updateModal"
        :collaborateurs="collaborateurs"
        :selected="selected"
        @updateData="data => this.updateData(data)"
        @closeModal="this.updateModal = false"
    />
    <ModalChart v-if="openChart"
                :annee="this.selectYear[0].annee"
                @closeConfirm="this.openChart = false"
    />
</template>

<script>
import ModalConfirmDelete from "@/Components/ModalConfirmDelete.vue";
import ModalUpdatePlanningFerie from "@/Components/ModalUpdatePlanningFerie.vue";
import ModalChart from "@/Components/ModalChart.vue";
import {Head} from "@inertiajs/inertia-vue3"

export default {
    name: "GestionJoursFerie",
    inheritAttrs: false,
    components: {
        Head,
        ModalChart,
        ModalUpdatePlanningFerie,
        ModalConfirmDelete
    },
    props: {
        annees: Object,
        collaborateurs: Array
    },
    data () {
        return {
            openChart: false,
            confirmDel: false,
            updateModal: false,
            selected: null,
            annee: null,
            zone: 'metropole',
            years : this.annees,
            selectYear: null
        }
    },
    methods: {
        generateFerie () {
            axios.post('/generate/jf', {
                annee: this.annee,
                zone: this.zone
            })
            .then(res => {
                this.years = res.data
                this.getFirstElement()
                this.$notify({
                    title: "Succès",
                    text: "La création des jours fériés pour l'année " + this.annee + " est réussie !",
                    type: 'success',
                });
            })
            .catch(err => {
                this.$notify({
                    title: "Erreur",
                    text: "L'année est incorrecte !",
                    type: 'warn',
                });
                console.log(err)
            })
        },
        updateData (data) {
            this.years = data
            this.getFirstElement()
            this.$notify({
                title: "Succès",
                text: "Modification effectuée avec succès !",
                type: 'success',
            });
        },
        getFirstElement () {
            if (this.selectYear !== null) {
                Object.entries(this.years).forEach(([key, value]) => {
                    if (key === this.selectYear[0].annee) {
                        this.selectYear = value
                    }
                });
            } else {
                Object.entries(this.years).forEach(([key, value]) => {
                        this.selectYear = value
                });
            }
            const today = new Date();
            const year = today.getFullYear();
            Object.entries(this.years).forEach(([key, value]) => {
                if (key == year) {
                    this.selectYear = value
                }
            });
        },
        openModal (ferie) {
            this.selected = ferie
            this.updateModal = true
        }
    },
    mounted () {
        this.getFirstElement()
    }
}
</script>

<style scoped>

</style>
