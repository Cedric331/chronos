<template>
    <notifications position="bottom right" />
    <Head>
        <title>Information Équipe</title>
    </Head>
    <BreezeAuthenticatedLayout>
            <div>
                <div class="w-9/12 mx-auto sm:visible invisible">
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-5">
                        <table class="w-full font-bold text-left">
                            <thead class="text-xs uppercase bg-white">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Nom
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Email
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Téléphone
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Anniversaire
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    <span class="sr-only">Éditer</span>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="user in users" :key="user.id" class="border-b bg-gray-800">
                                <th scope="row" class="px-6 py-4 font-medium text-white whitespace-nowrap">
                                    {{ user.name }}
                                </th>
                                <td class="px-6 py-4 text-white">
                                    {{ user.email }}
                                </td>
                                <td class="px-6 py-4 text-white">
                                    {{  user.phone ? '0' + user.phone : '' }}
                                </td>
                                <td class="px-6 py-4 text-white">
                                    {{ user.anniversaire }}
                                </td>
                                <td v-if="this.$page.props.auth.user.coordinateur || this.$page.props.auth.user.id === user.id" class="px-6 py-4 text-right">
                                    <button @click="edit(user)" class="bg-blue-700 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-full">
                                        Modifier
                                    </button>
                                </td>
                                <td v-else class="px-6 py-4 text-right">
                                    <button class="bg-slate-400 text-white font-bold py-2 px-4 rounded-full" disabled>
                                        Modifier
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
                        <div class="font-medium text-white whitespace-nowrap">
                            {{ user.phone ? '0' + user.phone : '' }}
                        </div>
                        <div class="font-medium text-white whitespace-nowrap">
                            {{ user.anniversaire }}
                        </div>
                        <div v-if="this.$page.props.auth.user.coordinateur || this.$page.props.auth.user.id === user.id" class=" text-right flex justify-between mt-1">
                            <button @click="edit(user)" class="bg-blue-700 text-white py-1 px-2 rounded-full">
                                Modifier
                            </button>
                        </div>
                        <div v-else class="text-right flex justify-between mt-1">
                            <button class="bg-slate-400 text-white py-1 px-2 rounded-full" disabled>
                                Modifier
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        <ModalInformation
            v-show="showModal"
            :isUpdate="isUpdate"
            :userUpdate="user"
            @refresh="refreshData()"
            @error="data => sendError(data)"
            @update="update()"
            @closeModal="data => this.closeModal(data)">
        </ModalInformation>
    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head } from '@inertiajs/inertia-vue3';
import ModalInformation from "@/Components/ModalInformation.vue";

export default {
    name: "InformationEquipe",
    components: {
        BreezeAuthenticatedLayout,
        Head,
        ModalInformation
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
        update () {
            this.$notify({
                title: "Succès",
                text: "Modification effectuée avec succès !",
                type: 'success',
            });
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
        refreshData () {
            this.$inertia.reload()
        }
    }
}
</script>

<style scoped>

</style>
