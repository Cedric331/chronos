<template>
    <notifications position="bottom right" />
    <BreezeAuthenticatedLayout>
    <div class="mb-16">
            <div class="w-full bg-gray-100 px-10 pt-10 h-16 content-center">
                <div class="flex justify-center my-auto">
                    <button @click.stop="openModal" class="inline-flex items-center flex justify-center h-10 px-5 text-indigo-100 transition-colors duration-150 bg-indigo-700 rounded-lg focus:shadow-outline hover:bg-indigo-800">
                        <span>Ajouter un membre</span>
                    </button>
                </div>
                <div class="container mx-auto">
                    <div role="list" aria-label="Behind the scenes People " class="lg:flex md:flex sm:flex items-center xl:justify-between flex-wrap md:justify-around sm:justify-around lg:justify-around">

                        <div v-for="user in users" :key="user.id" class="xl:w-1/3 sm:w-3/4 md:w-2/5 relative mt-16 mb-32 sm:mb-24 xl:max-w-sm lg:w-2/5">
                            <div class="rounded overflow-hidden shadow-md bg-white">
                                <div class="px-2 mt-8">
                                    <p class="text-gray-800 text-lg text-center">{{ user.name }}</p>
                                    <p class="text-gray-800 text-lg text-center">{{ user.email }}</p>
                                    <div class="w-full flex justify-around pt-5 pb-5">
                                        <button @click="edit(user)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">
                                            Éditer
                                        </button>
                                        <button @click="deleteConfirm(user)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-full">
                                            Supprimer
                                        </button>
                                    </div>
                                </div>
                            </div>
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
            @error="sendError()"
            @update="data => update(data)"
            @closeModal="data => this.closeModal(data)">
        </ModalUser>
</BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import ModalUser from "@/Components/ModalUser.vue";
import Dialog from "@/Components/Dialog.vue";

export default {
    name: "GestionEquipe",
    components: {
        BreezeAuthenticatedLayout,
        Dialog,
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
            this.$notify({
                title: "Succès",
                text: "Invitation envoyée avec succès !",
                type: 'success',
            });
        },
        sendError () {
            this.$notify({
                title: "Erreur",
                text: "Erreur lors de l\'envoi de l\'invitation !",
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
