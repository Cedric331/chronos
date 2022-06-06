<template>
    <div class="fixed fade z-10 inset-0 w-full h-full outline-none overflow-x-hidden overflow-y-auto" aria-labelledby="modification planning" role="dialog" aria-modal="true">

        <div class="flex items-end justify-center pt-4 px-4 pb-20 text-center sm:block sm:p-0 w-10/12 mb-6 mx-auto">
            <div @click="closeModal()" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left shadow-xl transform transition-all sm:my-4 sm:align-middle w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <section class="container p-6 mx-auto bg-white">
                            <h2 class="font-bold text-center text-3xl text-gray-800 md:text-2xl mb-5">
                                Gestion des traitements
                            </h2>
                            <div>
                                <form class="rounded-lg w-auto">
                                    <div class="mb-5">
                                        <label
                                            for="titre"
                                            class="mb-3 block text-base font-medium text-[#07074D]">
                                            Titre
                                        </label>
                                        <input
                                            v-model="titre"
                                            type="text"
                                            name="titre"
                                            id="titre"
                                            placeholder="Titre du traitement"
                                            class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                                        />
                                    </div>
                                    <div class="mb-5">
                                        <label
                                            for="description"
                                            class="mb-3 block text-base font-medium text-[#07074D]"
                                        >
                                            Description
                                        </label>
                                        <textarea
                                            v-model="description"
                                            rows="4"
                                            name="description"
                                            id="description"
                                            placeholder="Description du traitement"
                                            class="w-full resize-none rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                                        ></textarea>
                                    </div>
                                </form>
                            </div>
                        </section>

                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <div v-if="this.isUpdated">
                        <button @click="updated()" type="button" class="mt-3 w-full inline-flex justify-center bg-blue-500 rounded-md border shadow-sm px-4 py-2 text-base font-medium text-white sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Valider</button>
                    </div>
                    <div v-else>
                        <button @click="add()" type="button" class="mt-3 w-full inline-flex justify-center bg-blue-500 rounded-md border shadow-sm px-4 py-2 text-base font-medium text-white sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Valider</button>
                    </div>
                    <button @click="closeModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Fermer</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ModalTraitementVolant",
    props: {
      selected: Object
    },
    data () {
        return {
            isUpdated: false,
            titre: null,
            description: null
        }
    },
    methods: {
        add () {
          axios.post('/volant/traitement', {
              titre: this.titre,
              description: this.description
          })
            .then(response => {
                this.$emit('store', response.data)
                this.closeModal()
            })
            .catch(error => {
                console.log(error)
            })
        },
        updated () {
            axios.patch('/volant/traitement/' + this.selected.id, {
                titre: this.titre,
                description: this.description
            })
            .then(response => {
                this.$emit('update', response.data)
                this.closeModal()
            })
            .catch(error => {
                console.log(error)
            })
        },
        closeModal () {
            this.titre = null
            this.description = null
            this.isUpdated = false
            this.$emit('closeModal')
        }
    },
    watch: {
        selected () {
            if (this.selected) {
                this.isUpdated = true
                this.titre = this.selected.titre
                this.description = this.selected.description
            }
        }
    }
}
</script>

<style scoped>

</style>
