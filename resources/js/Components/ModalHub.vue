<template>
    <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div @click="closeModal()" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <Loading
                :show="show"
                :loader-class="loadClass"
                :label="label">
            </Loading>
            <div class="py-12 transition duration-150 ease-in-out z-10 absolute top-0 right-0 bottom-0 left-0" id="modal">
                <div role="alert" class="container mx-auto w-11/12 md:w-2/3 max-w-lg">
                    <div class="relative py-8 px-5 md:px-10 bg-white shadow-md rounded border border-gray-400">
                        <h1 class="text-gray-800 font-lg font-bold tracking-normal leading-tight mb-4">Création d'un HUB</h1>
                        <label for="ville" class="text-gray-800 text-sm font-bold leading-tight tracking-normal">Nom du Hub</label>
                        <input id="ville" v-model="ville" class="mb-5 mt-2 text-gray-600 focus:outline-none focus:border focus:border-indigo-700 font-normal w-full h-10 flex items-center pl-3 text-sm border-gray-300 rounded border" placeholder="Paris, Gujan..." />
                        <label for="name" class="text-gray-800 text-sm font-bold leading-tight tracking-normal">Nom du Coordinateur (Si compte non existant)</label>
                        <div class="relative mb-5 mt-2">
                            <div class="absolute text-gray-600 flex items-center px-4 border-r h-full">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </div>
                            <input v-model="name" type="text" id="name" class="text-gray-600 focus:outline-none focus:border focus:border-indigo-700 font-normal w-full h-10 flex items-center pl-16 text-sm border-gray-300 rounded border" placeholder="Nom du Coordinateur" />
                        </div>
                        <label for="email" class="text-gray-800 text-sm font-bold leading-tight tracking-normal">Email du Coordinateur (Si compte non existant)</label>
                        <div class="relative mb-5 mt-2">
                            <div class="absolute text-gray-600 flex items-center px-4 border-r h-full">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" d="M16.5 12a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zm0 0c0 1.657 1.007 3 2.25 3S21 13.657 21 12a9 9 0 10-2.636 6.364M16.5 12V8.25" />
                                </svg>
                            </div>
                            <input type="email" id="email" v-model="email" class="text-gray-600 focus:outline-none focus:border focus:border-indigo-700 font-normal w-full h-10 flex items-center pl-16 text-sm border-gray-300 rounded border" placeholder="Adresse email du Coordinateur" />
                        </div>
                        <div class="flex items-center justify-between w-full">
                            <button class="focus:outline-none focus:ring-2 focus:ring-offset-2  focus:ring-gray-400 ml-3 bg-gray-100 transition duration-150 text-gray-600 ease-in-out hover:border-gray-400 hover:bg-gray-300 border rounded px-8 py-2 text-sm" @click="closeModal()">Annuler</button>
                            <button class="focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-700 transition duration-150 ease-in-out hover:bg-indigo-600 bg-indigo-700 rounded text-white px-8 py-2 text-sm" @click="store()">Valider</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Loading from 'vue-full-loading'

export default {
    name: "ModalHub",
    components: {
        Loading
    },
    data () {
        return {
            show: false,
            label: 'Chargement...',
            loadClass: 'load-class',
            ville: null,
            name: null,
            email: null
        }
    },
    methods: {
        closeModal () {
            this.ville = null
            this.name = null
            this.email = null
            this.$emit('closeModal', false)
        },
        store () {
            this.show = true
            axios.post('administration/create/hub',{
                ville: this.ville,
                name: this.name,
                email: this.email
            }).then(response => {
                this.$emit('update', response.data)
            }).catch(error => {
              this.$emit('error')
              console.log(error)
            })
            .finally(() => {
                this.show = false
                this.closeModal()
            })
        }
    }
}
</script>

<style scoped>
.load-class {
    position: absolute;
    display: inline-block;
    right:40%;
    bottom:50%;
}
</style>
