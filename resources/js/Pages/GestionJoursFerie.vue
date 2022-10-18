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
                <main>
                    <div class="pt-6 px-4">
                        <div class="w-full gap-4 flex justify-between">
                            <div>
                                <div class="flex justify-start w-auto">
                                    <div class="w-2/6">
                                        <label>Année : </label>
                                        <input type="number" v-model="annee" minlength="4" maxlength="4">
                                    </div>
                                    <div>
                                        <button @click="generateFerie()" class="text-sm font-medium hover:bg-gray-700 bg-black text-white rounded-lg p-2">
                                            Générer les jours fériés
                                        </button>
                                    </div>
                                </div>
                                <hr class="my-5">
                                <div class="border border-4 shadow-2xl my-5 w-full mx-auto rounded-md p-16 flex flex-col sm:flex-row sm:justify-evenly">
                                    <div class="p-16 flex flex-col bg-white rounded-lg">
                                        <h1 class="font-semibold tracking-wide mb-2">Choisir l'année</h1>

                                        <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
                                            <div class="flex justify-center">
                                                <div class="w-full">
                                                    <select class="block w-full text-base font-normal text-gray-700 bg-white bg-clip-padding bg-no-repeat border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600" aria-label="sélection de l'année">
                                                        <option v-for="(value, year) in years" :key="year">
                                                            {{ year }}
                                                        </option>
                                                    </select>
                                                </div>
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
                                                            Collaborateurs
                                                        </th>
                                                        <th scope="col" class="px-6 py-3">
                                                            <span class="sr-only">Éditer</span>
                                                        </th>
                                                        <th scope="col" class="px-6 py-3">
                                                            <span class="sr-only">Supprimer</span>
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr class="border-b bg-gray-800">
                                                        <th scope="row" class="px-6 py-4 font-medium text-white whitespace-nowrap">
                                                            Test
                                                        </th>
                                                        <td class="px-6 py-4 text-white">
                                                            Test
                                                        </td>
                                                        <td class="px-6 py-4 text-right">
                                                            <button class="bg-blue-700 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-full">
                                                                Modifier
                                                            </button>
                                                        </td>
                                                        <td class="px-6 py-4 text-right">
                                                            <button class="bg-red-700 hover:bg-red-800 text-white font-bold py-2 px-4 rounded-full">
                                                                Supprimer
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
                </main>
            </div>
        </div>
<!--    <ModalConfirmDelete-->
<!--        v-if="confirmDel"-->
<!--        @closeModal="this.closeModal()"-->
<!--        @deleted="this.delete()"-->
<!--    />-->
</template>

<script>

export default {
    name: "GestionJoursFerie",
    props: {
        annees: Object
    },
    data () {
        return {
            annee: null,
            years : this.annees
        }
    },
    methods: {
        generateFerie () {
            axios.post('/generate/jf', {
                annee: this.annee
            })
            .then(res => {
                this.years = res.data
            })
            .catch(err => {
                console.log(err)
            })
        }
    }
}
</script>

<style scoped>

</style>
