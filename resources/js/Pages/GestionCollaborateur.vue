<template>
    <notifications position="bottom right" />
    <Head title="Gestion Conseillers" />
        <div class="mb-5 min-h-screen">
            <div class="w-full content-center">
                <div class="flex justify-center my-auto">
                    <button @click.stop="this.openModal = true" class="inline-flex items-center flex justify-center h-10 px-5 text-indigo-100 transition-colors duration-150 bg-indigo-700 rounded-lg focus:shadow-outline hover:bg-indigo-800">
                        <span>Ajouter un conseiller</span>
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

<!--            <div class="w-screen my-5 bg-gray-200 flex items-center justify-center sm:invisible visible">-->
<!--                <div class="text-center md:text-left">-->
<!--                    <div v-for="collaborateur in collaborateurs" :key="collaborateur.id" class="border-b bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 mt-2 p-4">-->
<!--                        <div class="font-medium text-white whitespace-nowrap">-->
<!--                            {{ collaborateur.name }}-->
<!--                        </div>-->
<!--                        <div class=" text-right flex justify-between mt-1">-->
<!--                            <button @click="deleteConfirm(collaborateur)" class="bg-red-700 text-white py-1 px-2 rounded-full">-->
<!--                                Supprimer-->
<!--                            </button>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
        </div>

        <Dialog
            v-if="confirm"
            @closeConfirm="this.closeDialogue()">
        </Dialog>
        <ModalCollaborateur
            v-if="openModal"
            @closeModal="this.openModal = false"
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
            collaborateur: null,
            openModal: false,
            confirm: false
        }
    },
    methods: {
        closeStore (data) {
            this.collaborateurs.push(data)
            this.$notify({
                title: "Succès",
                text: "Collaborateur ajouté avec succès !",
                type: 'success',
            });
            this.openModal = false
        },
        deleteConfirm (data) {
            this.collaborateur = data
            this.confirm = true
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
