<template>
    <notifications position="bottom right" />
    <Head title="Traitement Volant" />
    <BreezeAuthenticatedLayout>
        <div class="mb-16">
            <div class="w-full bg-gray-100 px-10 pt-10 h-16 content-center">
                <div v-if="this.$page.props.auth.user.coordinateur" class="flex justify-center my-auto">
                    <button @click.stop="showModal = true" class="inline-flex items-center flex justify-center h-10 px-5 text-indigo-100 transition-colors duration-150 bg-indigo-700 rounded-lg focus:shadow-outline hover:bg-indigo-800">
                        <span>Ajouter un traitement</span>
                    </button>
                </div>

                <div class="flex justify-between">
                <div class="shadow-lg rounded-xl w-full md:w-80 p-4 bg-white relative overflow-y-auto" style="height: 500px">
                    <div class="w-full flex items-center justify-between mb-6">
                        <p class="text-gray-800 text-xl font-medium">
                            Liste des hubs
                        </p>
                    </div>
                    <div @click="this.hub = item" v-for="item in hubs" :key="item.id" :class="[item.id === hub.id ? 'bg-green-100' :  '']" class="flex items-center mb-2 rounded justify-between p-3 bg-gray-100">
                        <div class="flex w-full ml-2 items-center justify-between">
                            <p>
                                {{ item.ville }}
                            </p>
                        </div>
                    </div>
                </div>


            <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-4 mt-4">
                <div class="text-gray-600 flex justify-start w-full my-12">
                    <input v-model="search" type="search" name="search" placeholder="Rechercher..." class="h-10 w-72 px-5 pr-10 rounded-full text-sm focus:outline-none">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-6 h-96">

                    <div v-for="traitement in listTraitements" class="w-full p-6 h-96 overflow-x-auto bg-gray-900 rounded-lg sahdow-lg flex flex-col items-center break-all">
                        <div class="text-center">
                            <p class="text-xl text-white font-bold mb-2">{{ traitement.titre }}</p>
                            <p v-for="line in traitement.description.split('\n')" class="text-white font-normal w-full">
                                {{ line }}<br>
                            </p>
                        </div>
                        <div>
                            <button @click="this.showModal = true; this.selected = traitement" class="inline-flex mr-6 mt-2 items-center flex justify-center h-10 px-5 text-indigo-100 transition-colors duration-150 bg-indigo-700 rounded-lg focus:shadow-outline hover:bg-indigo-800">
                                <span>Modifier</span>
                            </button>
                            <button @click="confirmDelete(traitement)" class="inline-flex items-center flex justify-center h-10 px-5 text-indigo-100 transition-colors duration-150 bg-red-700 rounded-lg focus:shadow-outline hover:bg-red-800">
                                <span>Supprimer</span>
                            </button>
                        </div>
                    </div>

                </div>
            </section>
            </div>
        </div>
    </div>
        <ModalConfirmDelete
        v-show="showDelete"
        @closeModal="this.showDelete = false; this.selected = null"
        @deleted="this.delete()">
        </ModalConfirmDelete>
        <ModalTraitementVolant
            v-show="showModal"
            :selected="this.selected"
            @store="data => this.add(data)"
            @update="data => this.updated(data)"
            @closeModal="this.showModal = false; this.selected = null ">
        </ModalTraitementVolant>
    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head } from '@inertiajs/inertia-vue3';
import ModalTraitementVolant from "@/Components/ModalTraitementVolant.vue";
import ModalConfirmDelete from "@/Components/ModalConfirmDelete";

export default {
    name: "TraitementVolant",
    components: {
        ModalConfirmDelete,
        ModalTraitementVolant,
        BreezeAuthenticatedLayout,
        Head
    },
    props: {
        traitements: Object,
        hubs: Object
    },
    data () {
        return {
            listTraitements: [],
            saveTraitements: [],
            search: '',
            hub: this.$page.props.hub,
            selected: null,
            showDelete: false,
            showModal: false
        }
    },
    watch: {
        search ()  {
            this.listTraitements = this.saveTraitements
            if (this.search.length > 0) {
                this.listTraitements = this.listTraitements.filter(element => {
                   const titre = element.titre.toLowerCase()
                   const search = this.search.toLowerCase()
                    if (titre.includes(search)) {
                        return element
                    }
                })
            }
        },
        hub () {
            axios.post('/volant/traitement/hub', {
                hub: this.hub.id
            })
            .then(response => {
                this.listTraitements = response.data
                this.saveTraitements = response.data
            })
            .catch(error => {
                console.log(error)
            })
        }
    },
    methods: {
        // ligne (data) {
        //   if (data.includes('https')) {
        //
        //     let a = data.indexOf('https')
        //
        //     let href = ''
        //       console.log(data)
        //           data.forEach((element, index) => {
        //           if (!data[a]) {
        //               data[index] += '</a>'
        //           } else {
        //               href += data[a]
        //               a++
        //           }
        //       })
        //   }
        //   return data
        // },
        confirmDelete (data) {
            this.selected = data
            this.showDelete = true
        },
        add (data) {
            this.traitements.push(data)
            this.$notify({
                title: "Succès",
                text: "Traitement ajouté avec succès !",
                type: 'success',
            });
        },
        updated (data) {
            this.listTraitements.forEach((element, index) => {
                if (element.id === data.id) {
                    this.listTraitements[index] = data
                    this.$notify({
                        title: "Succès",
                        text: "Modification effectuée avec succès !",
                        type: 'success',
                    });
                }
            })
        },
        delete () {
            axios.delete('/volant/traitement/' + this.selected.id)
            .then(() => {
                this.listTraitements = this.listTraitements.filter(element => {
                    return element !== this.selected
                })
                this.selected = null
                this.showDelete = false
                this.$notify({
                    title: "Succès",
                    text: "Traitement supprimé avec succès !",
                    type: 'success',
                });
            })
            .catch(error => {
                console.log(error)
            })
        }
    },
    mounted() {
        this.listTraitements = this.traitements
        this.saveTraitements = this.traitements
    }
}
</script>

<style scoped>

</style>
