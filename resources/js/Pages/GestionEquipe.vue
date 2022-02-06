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
<!--                                    <div class="w-full flex justify-center pt-5 pb-5">-->
<!--                                        Ajouté le 01/01/2022-->
<!--                                    </div>-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
        <ModalUser v-show="showModal" @update="data => update(data)" @closeModal="data => this.showModal = data"></ModalUser>
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
        },
        update (data) {
            this.user = data
            this.$notify({
                title: "Succès",
                text: "Invitation envoyée avec succès !",
                type: 'success',
            });
        }
    }
}
</script>

<style scoped>

</style>
