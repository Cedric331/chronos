<template>
    <notifications position="bottom right" />
    <Head title="Gestion Équipe" />
    <BreezeAuthenticatedLayout>
    <div class="mb-16">
            <div class="w-full bg-gray-100 px-10 pt-10 h-16 content-center">
                <div class="flex justify-center my-auto">
                    <button @click.stop="openModal" class="inline-flex items-center flex justify-center h-10 px-5 text-indigo-100 transition-colors duration-150 bg-indigo-700 rounded-lg focus:shadow-outline hover:bg-indigo-800">
                        <span>Ajouter un membre</span>
                    </button>
                </div>

                <div class="max-w-2xl mx-auto sm:visible invisible">
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-5">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Nom
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Email
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
                            <tr v-for="user in users" :key="user.id" class="border-b bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <th scope="row" class="px-6 py-4 font-medium text-white whitespace-nowrap">
                                    {{ user.name }}
                                </th>
                                <td class="px-6 py-4 text-white">
                                    {{ user.email }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button @click="edit(user)" class="bg-blue-700 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-full">
                                        Modifier
                                    </button>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button @click="deleteConfirm(user)" class="bg-red-700 hover:bg-red-800 text-white font-bold py-2 px-4 rounded-full">
                                        Supprimer
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        <div class="w-screen my-5 bg-gray-200 flex items-center justify-center sm:invisible visible">
                <div class="text-center md:text-left">
                    <div v-for="user in users" :key="user.id" class="border-b bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 mt-2 p-4">
                        <div class="font-medium text-white whitespace-nowrap">
                            {{ user.name }}
                        </div>
                        <div class="font-medium text-white whitespace-nowrap">
                            {{ user.email }}
                        </div>
                        <div class=" text-right flex justify-between mt-1">
                            <button @click="edit(user)" class="bg-blue-700 text-white py-1 px-2 rounded-full">
                                Modifier
                            </button>
                            <button @click="deleteConfirm(user)" class="bg-red-700 text-white py-1 px-2 rounded-full">
                                Supprimer
                            </button>
                        </div>
                    </div>
                </div>
        </div>
    </div>

        <Dialog
            v-if="confirm"
            :user="user"
            @closeConfirm="data => this.closeDialogue(data)">
        </Dialog>
        <ModalUser
            v-show="showModal"
            :isUpdate="isUpdate"
            :userUpdate="user"
            @refresh="refreshData()"
            @error="data => sendError(data)"
            @update="data => update(data)"
            @closeModal="data => this.closeModal(data)">
        </ModalUser>
</BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import ModalUser from "@/Components/ModalUser.vue";
import Dialog from "@/Components/Dialog.vue";
import { Head } from '@inertiajs/inertia-vue3';

export default {
    name: "GestionEquipe",
    components: {
        BreezeAuthenticatedLayout,
        Dialog,
        Head,
        ModalUser
    },
    props: {
        users: Array
    },
    data () {
        return {
            showModal: false,
            confirm: false,
            isUpdate: false,
            user: null
        }
    },
    methods: {
        openModal() {
            this.showModal = true
            this.isUpdate = false
        },
        closeModal() {
            this.showModal = false
            this.isUpdate = false
        },
        closeDialogue (data) {
            this.confirm = false
            if (data) {
                this.$notify({
                    title: "Succès",
                    text: "Membre supprimé avec succès !",
                    type: 'success',
                });
            }
            this.refreshData()
        },
        update (data) {
            if (data === 'user') {
                this.$notify({
                    title: "Succès",
                    text: "Modification effectuée avec succès !",
                    type: 'success',
                });
            } else {
                this.$notify({
                    title: "Succès",
                    text: "Invitation envoyée avec succès !",
                    type: 'success',
                });
            }
            this.refreshData()
        },
        sendError (data) {
            const error = data['errors']['email'][0] ? data['errors']['email'][0] : data['errors']['name'][0]
            this.$notify({
                title: "Erreur",
                text: error,
                type: 'danger',
            });
        },
        edit (user) {
            this.user = user
            this.isUpdate = true
            this.showModal = true
        },
        deleteConfirm (user) {
            this.user = user
            this.confirm = true
        },
        refreshData () {
            this.$inertia.reload()
        }
    }
}
</script>

<style scoped>

</style>
