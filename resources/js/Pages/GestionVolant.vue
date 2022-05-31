<template>
    <notifications position="bottom right" />
    <Head title="Gestion Volant" />
    <BreezeAuthenticatedLayout>
        <div class=" mx-auto h-full flex flex-col max-w-3xl  items-center justify-center bg-white rounded-xl p-4">
            <h1 class="font-bold uppercase text-xl py-4">Gestion des volants</h1>

            <div class="flex  flex-row justify-between space-x-4 items-center mx-auto">
                <div class="flex flex-col">
                    <div class="items-center w-full">
                        <h2>Volant</h2>
                    </div>
                    <select v-model="volant" class="p-3 h-96 border-2 border-one w-56 capitalize" multiple>
                        <option v-for="user in isVolant" :value="user.id">{{ user.name }}</option>
                    </select>
                </div>

                <div class="flex flex-col">
                    <button @click="addVolant()" class="inline-flex items-center flex justify-center h-10 px-5 text-indigo-100 transition-colors duration-150 bg-indigo-700 rounded-lg focus:shadow-outline hover:bg-indigo-800">
                        <span>Ajouter</span>
                    </button>
                    <button @click="deleteVolant()" class="inline-flex mt-4 items-center flex justify-center h-10 px-5 text-red-100 transition-colors duration-150 bg-red-700 rounded-lg focus:shadow-outline hover:bg-red-800">
                        <span>Supprimer</span>
                    </button>
                </div>

                <div class="flex flex-col">
                    <div class="items-center">
                        <h2>Non Volant</h2>
                    </div>
                    <select v-model="notVolant" class="p-3 h-96 border-2 border-one w-56 capitalize" multiple>
                        <option v-for="user in isNotVolant" :value="user.id">{{ user.name }}</option>
                    </select>
                </div>
            </div>

        </div>
    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head } from '@inertiajs/inertia-vue3';

export default {
    name: "GestionVolant",
    components: {
        BreezeAuthenticatedLayout,
        Head
    },
    props: {
        users: Array
    },
    data () {
        return {
            volant: [],
            notVolant: [],
            isVolant: [],
            isNotVolant: []
        }
    },
    methods: {
        checkVolant (data) {
            this.isVolant = []
            this.isNotVolant = []

            data.forEach(element => {
                const user = element.roles.find(item => item.name === "Volant")
                user ? this.isVolant.push(element) : this.isNotVolant.push(element)
            })
        },
        addVolant () {
            if (this.notVolant.length > 0) {
                axios.post('/volant/add', {
                    users: this.notVolant
                })
                .then(response => {
                    this.checkVolant(response.data)
                    this.$notify({
                        title: "Succès",
                        text: "Modification effectuée avec succès !",
                        type: 'success',
                    });
                })
                .catch(error => {
                    console.log(error)
                })
            } else {
                this.$notify({
                    title: "Erreur",
                    text: "Aucun collaborateur sélectionné !",
                    type: 'warn',
                });
            }
        },
        deleteVolant () {
            if (this.volant.length > 0) {
                axios.delete('/volant/delete', {
                    data: {
                        users: this.volant
                    }
                })
                    .then(response => {
                        this.checkVolant(response.data)
                        this.$notify({
                            title: "Succès",
                            text: "Modification effectuée avec succès !",
                            type: 'success',
                        });
                    })
                    .catch(error => {
                        console.log(error)
                    })
            } else {
                this.$notify({
                    title: "Erreur",
                    text: "Aucun collaborateur sélectionné !",
                    type: 'warn',
                });
            }
        }
    },
    mounted() {
        this.checkVolant(this.users)
    }
}
</script>

<style scoped>

</style>
