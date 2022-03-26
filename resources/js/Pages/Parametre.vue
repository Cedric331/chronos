<template>
    <notifications position="bottom right" />
    <Head title="Gestion Équipe" />
    <BreezeAuthenticatedLayout>
    <div class="2xl:container 2xl:mx-auto mt-5">

        <div id="filterSection" class="block relative md:py-10 lg:px-20 md:px-6 py-9 px-4 bg-gray-50 dark:bg-gray-800 w-full">

            <div class="flex space-x-2 text-gray-800 dark:text-white mb-5 flex justify-center items-center w-full">
                <h1 class="lg:text-2xl text-xl lg:leading-6 leading-5 font-bold">Modification des couleurs du planning</h1>
            </div>

            <hr class="bg-gray-200 lex justify-center items-center w-full md:my-10 my-8" />

            <div>
                <div class="flex space-x-2 text-gray-800 dark:text-white flex justify-start">
                    <p class="lg:text-2xl text-xl lg:leading-6 leading-5 font-medium">Jour travaillé</p>
                    <input type="color" v-model="jourTravaille">
                </div>
            </div>

            <hr class="bg-gray-200 lg:w-6/12 w-full md:my-10 my-8" />

            <div>
                <div class="flex space-x-2 text-gray-800 dark:text-white flex justify-start">
                    <p class="lg:text-2xl text-xl lg:leading-6 leading-5 font-medium">Congé Payé</p>
                    <input type="color" v-model="congePaye">
                </div>
            </div>

            <hr class="bg-gray-200 lg:w-6/12 w-full md:my-10 my-8" />

            <div>
                <div class="flex space-x-2 text-gray-800 dark:text-white flex justify-start">
                    <p class="lg:text-2xl text-xl lg:leading-6 leading-5 font-medium">Repos</p>
                    <input type="color" v-model="repos">
                </div>
            </div>

            <hr class="bg-gray-200 lg:w-6/12 w-full md:my-10 my-8" />

            <div>
                <div class="flex space-x-2 text-gray-800 dark:text-white flex justify-start">
                    <p class="lg:text-2xl text-xl lg:leading-6 leading-5 font-medium">Technicien</p>
                    <input type="color" v-model="technicien">
                </div>
            </div>

            <hr class="bg-gray-200 lg:w-6/12 w-full md:my-10 my-8" />

            <div>
                <div class="flex space-x-2 text-gray-800 dark:text-white flex justify-start">
                    <p class="lg:text-2xl text-xl lg:leading-6 leading-5 font-medium">Couleur du texte</p>
                    <input type="color" v-model="texte">
                </div>

            </div>
            <!-- (Large Screen) -->

            <div class="hidden md:block absolute right-0 bottom-0 md:py-10 lg:px-20 md:px-6 py-9 px-4">
                <button @click.stop="resetParametre()" class="hover:bg-gray-100 bg-gray-200 focus:ring focus:ring-offset-2 focus:ring-gray-800 text-base leading-4 font-medium py-4 px-10 text-black mr-5">Réinitialiser</button>
                <button @click.stop="updateParametre()" class="hover:bg-blue-700 bg-blue-600 focus:ring focus:ring-offset-2 focus:ring-gray-800 text-base leading-4 font-medium py-4 px-10 text-white">Enregistrer</button>
            </div>

            <!-- (lower Screen) -->

            <div class="block md:hidden w-full mt-10">
                <button @click.stop="updateParametre()" class="w-full hover:bg-blue-700 bg-blue-600 focus:ring focus:ring-offset-2 focus:ring-gray-800 text-base leading-4 font-medium py-4 px-10 text-white mb-4">Enregistrer</button>
                <button @click.stop="resetParametre()" class="w-full hover:bg-gray-100 bg-gray-200 focus:ring focus:ring-offset-2 focus:ring-gray-800 text-base leading-4 font-medium py-4 px-10 text-black">Réinitialiser</button>
            </div>
        </div>
    </div>
</BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head } from '@inertiajs/inertia-vue3';

export default {
    name: "Parametre",
    components: {
        BreezeAuthenticatedLayout,
        Head,
    },
    data () {
        return {
            jourTravaille: this.$page.props.auth.user.color_travaille ? this.$page.props.auth.user.color_travaille : '#000000',
            congePaye: this.$page.props.auth.user.color_conge ? this.$page.props.auth.user.color_conge : '#000000',
            repos: this.$page.props.auth.user.color_repos ? this.$page.props.auth.user.color_repos : '#000000',
            technicien: this.$page.props.auth.user.color_technicien ? this.$page.props.auth.user.color_technicien : '#000000',
            texte: this.$page.props.auth.user.color_texte ? this.$page.props.auth.user.color_texte : '#000000'
        }
    },
    methods: {
        updateParametre () {
            axios.patch('parametre/update', {
                jourTravaille: this.jourTravaille,
                congePaye: this.congePaye,
                repos: this.repos,
                technicien: this.technicien,
                texte: this.texte,
            })
            .then(() => {
                this.$notify({
                    title: "Succès",
                    text: "Modification effectuée avec succès !",
                    type: 'success',
                });
            })
            .catch(error => {
                console.log(error)
            })
        },
        resetParametre () {
            axios.patch('parametre/reset')
                .then(() => {
                    this.jourTravaille = '#000000'
                    this.congePaye = '#000000'
                    this.repos = '#000000'
                    this.technicien = '#000000'
                    this.texte = '#000000'

                    this.$notify({
                        title: "Succès",
                        text: "Réinitialisation effectuée avec succès !",
                        type: 'success',
                    });
                })
                .catch(error => {
                    console.log(error)
                })
        }
    }
}
</script>

<style scoped>

.checkbox:checked + .check-icon {
    display: flex;
}

</style>

