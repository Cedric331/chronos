<template>
    <!-- This example requires Tailwind CSS v2.0+ -->
    <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div @click="this.$emit('closeConfirm')" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start flex justify-center">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title"><b>Création d'un nouveau Collaborateur</b></h3>
                            <div class="mt-2">
                                <div>
                                    <input v-model="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" placeholder="Nom du Collaborateur">
                                </div>
                            </div>
                            <span v-if="errors" class="flex items-center font-medium tracking-wide text-red-500 text-xs mt-1 ml-1">
                                Le Collaborateur existe déjà !
                            </span>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button @click="store()" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Créer
                    </button>
                    <button @click="this.collaborateur = null; this.$emit('closeModal')" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ModalCollaborateur",
    data () {
        return {
            collaborateur: null,
            errors: false
        }
    },
    methods: {
        closeModal () {
            this.name = null
            this.errors = false
            this.$emit('closeConfirm')
        },
        store () {
           axios.post('collaborateur', {
               name: this.name
           })
            .then(response => {
                this.$emit('closeStore', response.data)
            })
            .catch(err => {
                console.log(err)
            })
            .finally(() => {
                this.name = null
                this.errors = false
            })
        }
    }
}
</script>

<style scoped>

</style>
