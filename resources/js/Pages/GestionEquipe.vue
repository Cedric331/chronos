<template>
    <BreezeAuthenticatedLayout>
    <div class="mb-16">
        <!-- Code block starts -->
            <div class="container flex justify-center mx-auto pt-16">
                <div>
                    <h1 class="xl:text-5xl text-3xl text-center text-gray-800 font-extrabold pb-6 sm:w-6/6 w-6/6 mx-auto">
                        Gestion d'équipe
                    </h1>
                </div>
            </div>
            <div class="w-full bg-gray-100 px-10 pt-10">
                <button @click="openModal" class="inline-flex items-center h-10 px-5 text-indigo-100 transition-colors duration-150 bg-indigo-700 rounded-lg focus:shadow-outline hover:bg-indigo-800">
                    <svg class="w-4 h-4 mr-3 fill-current" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" fill-rule="evenodd"></path></svg>
                    <span>Ajouter un membre</span>
                </button>
                <div class="container mx-auto">
                    <div role="list" aria-label="Behind the scenes People " class="lg:flex md:flex sm:flex items-center xl:justify-between flex-wrap md:justify-around sm:justify-around lg:justify-around">

                        <div v-for="user in users" :key="user.id" class="xl:w-1/3 sm:w-3/4 md:w-2/5 relative mt-16 mb-32 sm:mb-24 xl:max-w-sm lg:w-2/5">
                            <div class="rounded overflow-hidden shadow-md bg-white">
                                <div class="px-2 mt-8">
                                    <p class="text-gray-800 text-lg text-center">{{ user.name }}</p>
                                    <p class="text-gray-800 text-lg text-center">{{ user.email }}</p>
                                    <div class="w-full flex justify-center pt-5 pb-5">
                                        Ajouté le 01/01/2022
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!-- Code block ends -->
        <!--- more free and premium Tailwind CSS components at https://tailwinduikit.com/ --->
    </div>
        <ModalUser v-show="showModal" @update="data => this.users = data" @closeModal="data => this.showModal = data"></ModalUser>
</BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import ModalUser from "@/Components/ModalUser.vue";
export default {
    name: "GestionEquipe",
    components: {
        BreezeAuthenticatedLayout,
        ModalUser
    },
    props: {
        users: Array
    },
    data () {
        return {
            showModal: false
        }
    },
    methods: {
        openModal() {
            this.showModal = true
        }
    }
}
</script>

<style scoped>

</style>
