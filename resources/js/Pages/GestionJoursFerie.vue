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
                        <div class="w-full gap-4 flex justify-between">
                            <div>
                                <div class="flex justify-start w-auto">
                                    <div class="w-2/6">
                                        <label>Année : </label>
                                        <input type="number" v-model="annee" minlength="4" maxlength="4" placeholder="2022...">
                                    </div>
                                    <div>
                                        <button @click="generateFerie()" class="text-sm font-medium hover:bg-gray-700 bg-black text-white rounded-lg p-2">
                                            Générer les jours fériés
                                        </button>
                                    </div>
                                </div>
                                <span class="font-light text-sm text-gray-600">
                                    Les jours fériés doivent être générés pour chaque année
                                </span>
                                <hr class="my-5">
                                <div class="border border-4 shadow-2xl my-5 w-full mx-auto rounded-md p-16 flex flex-col sm:flex-row sm:justify-evenly">
                                    <div class="p-16 flex flex-col bg-white rounded-lg">
                                        <div class="flex justify-start">
                                            <h1 class="font-semibold tracking-wide">Voir les jours fériés de l'année : </h1>

                                            <div class="absolute inset-y-0 mb-4 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
                                                <div class="flex justify-center">
                                                    <div class="w-full">
                                                        <select v-model="selectYear" class="block w-full text-base font-normal text-gray-700 bg-white bg-clip-padding bg-no-repeat border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600" aria-label="sélection de l'année">
                                                            <option v-for="(value, year) in years" :key="year" :value="value" selected>
                                                                {{ year }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <button v-if="selectYear !== null" @click="this.confirmDel = true" class="text-sm font-medium hover:bg-gray-700 bg-black text-white rounded-lg p-2">
                                                Supprimer les jours fériés de cette année
                                            </button>
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
                                                            Collaborateurs
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
                                                                    {{ collaborateur.name }}
                                                                </span>
                                                            </div>
                                                            <div v-else>
                                                                <span>
                                                                    -- Aucun Collaborateur --
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4 text-right">
                                                            <button class="bg-blue-700 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-full">
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
</template>

<script>
import ModalConfirmDelete from "@/Components/ModalConfirmDelete.vue";

export default {
    name: "GestionJoursFerie",
    components: {
        ModalConfirmDelete
    },
    props: {
        annees: Object
    },
    data () {
        return {
            confirmDel: false,
            annee: null,
            years : this.annees,
            selectYear: null
        }
    },
    methods: {
        generateFerie () {
            axios.post('/generate/jf', {
                annee: this.annee
            })
            .then(res => {
                this.years = res.data
                this.$notify({
                    title: "Succès",
                    text: "Les jours fériés sont créés avec succès !",
                    type: 'success',
                });
            })
            .catch(err => {
                console.log(err)
            })
        },
        delete () {
            axios.delete('/jf', {
                data: {
                    annee: this.selectYear
                }
            })
            .then(() => {
                // TODO Corriger delete filter
                this.years = this.years.filter(item => {
                    return item !== this.selectYear
                })
                this.$notify({
                    title: "Succès",
                    text: "Les jours fériés sont supprimés avec succès !",
                    type: 'success',
                });
            })
            .catch(err => {
                console.log(err)
            })
            .finally(() => {
                this.confirmDel = false
            })
        }
    },
    mounted () {
        var data = null
        Object.entries(this.annees).forEach(([key, value]) => {
            if (data === null) {
                data = value
            }
        });
        this.selectYear = data
    }
}
</script>

<style scoped>

</style>
