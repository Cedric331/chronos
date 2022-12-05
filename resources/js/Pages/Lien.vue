<template>
    <notifications position="bottom right" />
    <Head>
        <title>Liens</title>
    </Head>
    <BreezeAuthenticatedLayout>
            <div>
                <div class="w-full min-h-screen px-10 pt-10 h-16 content-center">
                    <div class="flex justify-center my-auto">
                        <button @click.stop="showModal = true" class="inline-flex items-center flex justify-center h-10 px-5 text-indigo-100 transition-colors duration-150 bg-indigo-700 rounded-lg focus:shadow-outline hover:bg-indigo-800">
                            <span>Ajouter un lien</span>
                        </button>
                    </div>

                    <div class="w-4/6 mx-auto sm:visible invisible">
                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-5">

                            <div class="flex mb-2">
                                <div class="relative m-1 mb-1">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                  d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                  clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <input v-model="search" type="text" class="text-black text-sm block w-80 pl-10 p-2.5 bg-white" placeholder="Rechercher un lien par son nom">
                                </div>

                                <div class="flex items-center sm:ml-6">
                                    <div class="ml-3 relative">
                                        <select v-model="filtre" class="p-3 hover:bg-black hover:text-white font-bold rounded-full bg-white block w-full h-full text-sm leading-4 font-medium rounded-md rounded transition ease-in-out m-0 focus:border-blue-500" style="border-width: 0">
                                            <option selected :value="1">
                                                Voir tous les liens
                                            </option>
                                            <option v-if="!$page.props.auth.user.coordinateur" :value="2">
                                                Coordinateur
                                            </option>
                                            <option :value="3">
                                                 Mes liens
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-white">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Nom
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Description
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Lien
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
                                <tr v-for="lien in listeArray.data" :key="lien.id" class="border-b bg-gray-800">
                                    <th scope="row" class="px-6 py-4 font-medium text-white whitespace-nowrap">
                                        {{ lien.name }}
                                    </th>
                                    <td class="px-6 py-4 text-white text-ellipsis overflow-hidden">
                                        {{ lien.description }}
                                    </td>
                                    <td class="px-6 py-4 text-white text-ellipsis overflow-hidden">
                                        <a :href="lien.url" target="_blank" class="text-blue-500 text-ellipsis overflow-hidden">Lien</a>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <button @click="edit(lien)" :disabled="$page.props.auth.user.id !== lien.user_id && !$page.props.auth.user.coordinateur" :class="$page.props.auth.user.id !== lien.user_id && !$page.props.auth.user.coordinateur ? 'bg-gray-400 hover:bg-gray-400' : 'bg-blue-700 hover:bg-blue-800'" class="text-white font-bold py-2 px-4 rounded-full">
                                            Modifier
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <button @click="deleteLien(lien)" :disabled="$page.props.auth.user.id !== lien.user_id && !$page.props.auth.user.coordinateur" :class="$page.props.auth.user.id !== lien.user_id && !$page.props.auth.user.coordinateur ? 'bg-gray-400 hover:bg-gray-400' : 'bg-red-700 hover:bg-red-800'" class="text-white font-bold py-2 px-4 rounded-full">
                                            Supprimer
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <Pagination v-if="listeArray.length > 12" class="w-full flex justify-center mt-6" :key="listeArray.length" :links="listeArray.links"></Pagination>
                </div>

                <div class="w-full mt-5 bg-gray-200 sm:invisible visible">

                    <div class="flex my-2">
                        <div class="relative m-1 mb-1">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                          d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                          clip-rule="evenodd"></path>
                                </svg>
                            </div>

                            <input v-model="search" type="text" class="text-gray-900 text-sm block w-full pl-10 p-2.5 dark:bg-gray-700 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Recherche">
                        </div>

                        <div class="flex items-center sm:ml-6">
                            <div class="ml-3 relative">
                                <select v-model="filtre" class="bg-gray-700 text-white block w-full h-full text-sm leading-4 font-medium rounded-md rounded transition ease-in-out m-0 focus:border-blue-500" style="border-width: 0">
                                    <option selected :value="1" class="text-white">
                                        Voir tous les liens
                                    </option>
                                    <option :value="2" class="text-white">
                                        Coordinateur
                                    </option>
                                    <option :value="3" class="text-white">
                                        Mes liens
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-center">
                        <div class="text-center md:text-left">
                            <div v-for="lien in listeArray.data" :key="lien.id" class="border-b bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 mt-2 p-4">
                                <div class="font-medium text-white whitespace-nowrap mt-2">
                                    {{ lien.name }}
                                </div>
                                <div class="font-medium text-white">
                                    <a :href="lien.url" target="_blank" class="text-blue-500 text-ellipsis overflow-hidden">Lien</a>
                                </div>
                                <div class=" text-right flex justify-center mt-2">
                                    <button @click="edit(lien)" :disabled="$page.props.auth.user.id !== lien.user_id && !$page.props.auth.user.coordinateur" :class="$page.props.auth.user.id != lien.user_id && !$page.props.auth.user.coordinateur ? 'bg-gray-400' : 'bg-blue-700'" class="text-white py-1 px-2 rounded-full">
                                        Modifier
                                    </button>
                                    <button @click="deleteLien(lien)" :disabled="$page.props.auth.user.id !== lien.user_id && !$page.props.auth.user.coordinateur" :class="$page.props.auth.user.id != lien.user_id && !$page.props.auth.user.coordinateur ? 'bg-gray-400' : 'bg-red-700'" class="text-white py-1 px-2 rounded-full ml-1">
                                        Supprimer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <Pagination class="w-full flex justify-center mt-4" :key="listeArray.length" :links="listeArray.links"></Pagination>
                </div>
            </div>
        <ModalLien
            v-show="showModal"
            :isUpdate="isUpdate"
            :selected="selected"
            @store="data => this.store(data)"
            @update="data => update(data)"
            @closeModal="this.closeModal()">
        </ModalLien>
        </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import {Head} from "@inertiajs/inertia-vue3"
import ModalLien from "@/Components/ModalLien.vue";
import Pagination from "@/Components/Pagination.vue";

export default {
    name: "Lien",
    components: {
        BreezeAuthenticatedLayout,
        ModalLien,
        Pagination,
        Head
    },
    props: {
        liens: Object
    },
    data () {
        return {
            filtre: 1,
            search: null,
            listeArray: this.liens,
            showModal: false,
            isUpdate: false,
            selected: null,
            error: null
        }
    },
    watch: {
        search: function () {
            axios.post('lien/search', {
                search: this.search,
                filtre: this.filtre
            })
            .then(response => {
                this.listeArray = response.data
            })
            .catch(error => {
                console.log(error)
            })
        },
        filtre: function () {
            axios.post('lien/search', {
                search: this.search,
                filtre: this.filtre
            })
                .then(response => {
                    this.listeArray = response.data
                })
                .catch(error => {
                    console.log(error)
                })
        }
    },
    methods: {
        store (data) {
            this.$notify({
                title: "Succès",
                text: "Lien ajouté avec succès !",
                type: 'success',
            });
            this.listeArray = data
            this.closeModal()
        },
        update (data) {
            this.$notify({
                title: "Succès",
                text: "Lien modifié avec succès !",
                type: 'success',
            });

            this.listeArray.data.forEach((item, index) => {
                if (item.id === data.id) {
                    this.listeArray.data[index] = data
                }
            })
            this.closeModal()
        },
        edit (lien) {
            this.isUpdate = true
            this.selected = lien
            this.showModal = true
        },
        deleteLien (lien) {
            axios.delete('lien/' + lien.id)
            .then(response => {
                this.$notify({
                    title: "Succès",
                    text: "Lien supprimé avec succès !",
                    type: 'success',
                });
                this.listeArray = response.data
            })
            .catch(error => {
                console.log(error)
            })
        },
        refreshData () {
            this.showModal = false
            this.$inertia.reload()
            this.listeArray = this.liens
        },
        closeModal () {
            this.showModal = false
            this.isUpdate = false
            this.selected = null
        }
    }
}
</script>

<style scoped>

</style>
