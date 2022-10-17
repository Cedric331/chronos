<template>
    <notifications position="bottom right" />
    <Head title="Administration" />
    <BreezeAuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <b>Administration</b>
                        </h3>
                    </div>
                    <div class="flex justify-between ml-5">
                        <div class="shadow-lg rounded-xl w-full md:w-80 p-4 bg-white relative overflow-y-auto" style="height: 400px">
                            <div class="w-full flex items-center justify-between mb-6">
                                <p class="text-gray-800 text-xl font-medium">
                                    Liste des hubs
                                </p>
                                <button @click.prevent="create()" class="flex items-center hover:text-black text-gray-800 border-0 focus:outline-none">
                                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1600 736v192q0 40-28 68t-68 28h-416v416q0 40-28 68t-68 28h-192q-40 0-68-28t-28-68v-416h-416q-40 0-68-28t-28-68v-192q0-40 28-68t68-28h416v-416q0-40 28-68t68-28h192q40 0 68 28t28 68v416h416q40 0 68 28t28 68z"></path>
                                    </svg>
                                </button>
                            </div>
                            <div @click="this.hub = item" v-for="item in hubs" :key="item.id" :class="[item.id === hub.id ? 'bg-green-100' :  '']" class="flex items-center mb-2 rounded justify-between p-3 bg-gray-100">
                                <div class="flex w-full ml-2 items-center justify-between">
                                    <p>
                                        {{ item.ville }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="container mx-auto px-4 sm:px-8 ">
                            <div>
                                <div class="px-4 py-4 overflow-x-auto">
                                    <div class="inline-block min-w-full shadow rounded-lg overflow-hidden">
                                        <div class="w-full flex items-center justify-around m-5">

                                            <button @click.prevent="createUser()" type="button" class="py-2 px-4 flex justify-center items-center  bg-blue-600 hover:bg-blue-700 text-white transition ease-in duration-200 text-center text-base font-semibold shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2  rounded-lg ">
                                                <svg width="20" height="20" class="mr-2" fill="currentColor" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M1600 736v192q0 40-28 68t-68 28h-416v416q0 40-28 68t-68 28h-192q-40 0-68-28t-28-68v-416h-416q-40 0-68-28t-28-68v-192q0-40 28-68t68-28h416v-416q0-40 28-68t68-28h192q40 0 68 28t28 68v416h416q40 0 68 28t28 68z"></path>
                                                </svg>
                                                Ajouter un membre
                                            </button>

                                            <button @click.prevent="checkUpdate()" type="button" class="py-2 px-4 flex justify-center items-center  bg-blue-600 hover:bg-blue-700 text-white transition ease-in duration-200 text-center text-base font-semibold shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2  rounded-lg ">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                                </svg>
                                                Nouvelle mise à jour
                                            </button>

                                        </div>
                                        <table class="min-w-full leading-normal">
                                            <thead>
                                            <tr>
                                                <th scope="col" class="px-5 py-3 bg-white  border-b border-gray-200 text-gray-800  text-left text-sm uppercase font-normal">
                                                    Prénom
                                                </th>
                                                <th scope="col" class="px-5 py-3 bg-white  border-b border-gray-200 text-gray-800  text-left text-sm uppercase font-normal">
                                                    Email
                                                </th>
                                                <th scope="col" class="px-5 py-3 bg-white  border-b border-gray-200 text-gray-800  text-left text-sm uppercase font-normal">
                                                    Status
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr v-for="member in hub.members" :key="member.id">
                                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                    <div class="flex items-center">
                                                        <div class="ml-3">
                                                            <p class="text-gray-900 whitespace-no-wrap">
                                                                {{ member.name }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                    <p class="text-gray-900 whitespace-no-wrap">
                                                        {{ member.email }}
                                                    </p>
                                                </td>
                                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                    <span class="relative inline-block px-3 py-1 font-semibold text-green-900 leading-tight">
                                                        <span aria-hidden="true" class="absolute inset-0 bg-green-200 opacity-50 rounded-full">
                                                        </span>
                                                        <span class="relative">
                                                            {{ member.status }}
                                                        </span>
                                                    </span>
                                                </td>
                                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                    <p class="text-gray-900 whitespace-no-wrap">
                                                        Supprimer
                                                    </p>
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
        <ModalHub v-show="showModalHub" @update="data => updateHub(data)" @closeModal="data => this.showModalHub = data"></ModalHub>
        <ModalUser v-show="showModalUser" :hub="this.hub.id" :isAdmin="true" @update="updateUser()" @closeModal="data => this.showModalUser = data"></ModalUser>
    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import ModalHub from "@/Components/ModalHub.vue";
import ModalUser from "@/Components/ModalUser.vue";
import { Head } from '@inertiajs/inertia-vue3';

export default {
    components: {
        BreezeAuthenticatedLayout,
        ModalUser,
        ModalHub,
        Head,
    },
    props: {
        hubs: Array,
    },
    data () {
        return {
            annee: null,
            hub: null,
            showModalHub: false,
            showModalUser: false
        }
    },
    methods: {
        create () {
            this.showModalHub = true
        },
        createUser () {
            this.showModalUser = true
        },
        updateHub (data) {
            this.hubs.push(data)
        },
        updateUser () {
            this.$notify({
                title: "Succès",
                text: "Invitation envoyée avec succès !",
                type: 'success',
            });
        },
        checkUpdate () {
            axios.post('administration/check-update/user')
            .then(() => {
                this.$notify({
                    title: "Succès",
                    text: "Mise à jour effectuée avec succès !",
                    type: 'success',
                });
            })
            .catch(error => {
                console.log(error)
            })
        }
    },
    beforeMount() {
        this.hub = this.hubs[0]
    }
}
</script>

<style>
.success {
    margin: 5px;
    padding: 10px;
    font-size: 12px;
    background: #68cd86;
    border-left-color: #42a85f;
}

.warn {
    background: #ffb648;
    border-left-color: #f48a06;
}

.error {
    background: #e54d42;
    border-left-color: #b82e24;
}
.load-class {
    position: absolute;
    display: inline-block;
    right:40%;
    bottom:50%;
}
</style>
