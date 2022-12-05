<template>
    <notifications position="bottom right" />
    <Head title="Gestion Conseillers" />
        <div class="mb-5 min-h-screen">
            <div class="w-full content-center">
                <div class="flex justify-center my-auto">
                    <button @click.stop="this.openModal = true" class="inline-flex items-center flex justify-center h-10 px-5 text-indigo-100 transition-colors duration-150 bg-indigo-700 rounded-lg focus:shadow-outline hover:bg-indigo-800">
                        <span>Créer un conseiller</span>
                    </button>
                </div>

                <div class="max-w-2xl mx-auto sm:visible invisible">
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-5">
                        <table class="w-full text-left">
                            <thead class="uppercase bg-white">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Nom
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    <span class="sr-only">Supprimer</span>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="collaborateur in collaborateurs" :key="collaborateur.id" class="border-b bg-gray-800">
                                <th scope="row" class="px-6 py-4 font-medium text-white whitespace-nowrap">
                                    {{ collaborateur.name }}
                                </th>
                                <td class="px-6 py-4 text-right">
                                    <button @click="updateCollaborateur(collaborateur)" class="bg-blue-700 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-full mr-3">
                                        Modifier
                                    </button>
                                    <button @click="deleteConfirm(collaborateur)" class="bg-red-700 hover:bg-red-800 text-white font-bold py-2 px-4 rounded-full">
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

        <Dialog
            v-if="confirm"
            @closeConfirm="this.closeDialogue()">
        </Dialog>
        <ModalCollaborateur
            v-if="openModal"
            :isUpdate="isUpdate"
            :collaborateurUpdate="collaborateurUpdate"
            @closeModal="this.openModal = false; this.collaborateurUpdate = {}; this.isUpdate = false"
            @closeStore="data => this.closeStore(data)">
        </ModalCollaborateur>
</template>

<script>
import Dialog from "@/Components/Dialog.vue";
import { Head } from '@inertiajs/inertia-vue3';
import ModalCollaborateur from "@/Components/ModalCollaborateur.vue";

export default {
    name: "GestionCollaborateur",
    inheritAttrs: false,
    components: {
        ModalCollaborateur,
        Dialog,
        Head
    },
    props: {
        collaborateurs: Array
    },
    data () {
        return {
            collaborateurUpdate: {},
            collaborateur: null,
            isUpdate: false,
            openModal: false,
            confirm: false
        }
    },
    methods: {
        closeStore (data) {
            if (this.isUpdate) {
                this.collaborateurs.forEach((item, index) => {
                    if (item.id === data.id) {
                        this.collaborateurs[index] = data
                    }
                })
                this.$notify({
                    title: "Succès",
                    text: "Collaborateur modifié avec succès !",
                    type: 'success',
                });
                this.isUpdate = false
            } else {
                this.collaborateurs.push(data)
                this.$notify({
                    title: "Succès",
                    text: "Collaborateur ajouté avec succès !",
                    type: 'success',
                });
            }
            this.openModal = false
            this.collaborateur = null
        },
        deleteConfirm (data) {
            this.collaborateur = data
            this.confirm = true
        },
        updateCollaborateur (data) {
            this.collaborateurUpdate = data
            this.isUpdate = true
            this.openModal = true

        },
        closeDialogue () {
            axios.delete('/collaborateur/' + this.collaborateur.id)
                .then(() => {
                    this.$notify({
                        title: "Succès",
                        text: "Collaborateur supprimé avec succès !",
                        type: 'success',
                    });
                    this.$inertia.reload()
                })
                .catch(error => {
                    console.log(error)
                })
            .finally(() => {
                this.confirm = false
                this.collaborateur = null
            })
        }
    }
}
</script>

<style scoped>

</style>
