<template>
    <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="utilisateur" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div @click="this.closeModal()" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:w-1/3" >
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="mb-4 md:flex md:justify-between">
                        <div class="mb-4 md:mr-2 md:mb-0">
                            <label class="block mb-2 text-sm font-bold text-gray-700" for="name">
                                Nom
                            </label>
                            <input
                                class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                id="name"
                                type="text"
                                v-model="name"
                                placeholder="Nom du lien"
                            />
                        </div>
                        <div class="md:ml-2">
                            <label class="block mb-2 text-sm font-bold text-gray-700" for="description">
                                Description
                            </label>
                            <textarea
                                v-model="description"
                                class="form-control
                                    block
                                    w-full
                                    px-3
                                    py-1.5
                                    text-base
                                    font-normal
                                    text-gray-700
                                    bg-white bg-clip-padding
                                    border border-solid border-gray-300
                                    rounded
                                    transition
                                    ease-in-out
                                    m-0
                                    focus:shadow-outline
                                    focus:outline-none"
                                    id="description"
                                    rows="3"
                                    placeholder="Description (facultatif)"
                            ></textarea>
                        </div>
                        <div class="mb-4 md:ml-2 md:mb-0">
                            <label class="block mb-2 text-sm font-bold text-gray-700" for="url">
                                Url
                            </label>
                            <input
                                class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                id="url"
                                type="text"
                                v-model="url"
                                placeholder="Lien url"
                            />
                        </div>
                    </div>
                    <div v-if="errors" class="mb-4 sm:align-middle font-medium text-sm text-red-600">
                        {{ errors }}
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button v-if="!isUpdate" @click="store()" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">
                        Ajouter
                    </button>
                    <button v-else @click="update()" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">
                        Modifier
                    </button>
                    <button @click="this.closeModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ModalLien",
    props: {
      liens: Object,
      selected: Object,
      isUpdate: Boolean
    },
    data () {
        return {
            name: null,
            description: null,
            url: null,
            errors: null
        }
    },
    watch: {
        isUpdate: function () {
            if (this.isUpdate) {
                this.name = this.selected.name
                this.description = this.selected.description
                this.url =  this.selected.url
            } else {
                this.name = null
                this.description = null
                this.url =  null
            }
        }
    },
    methods: {
        store () {
            this.errors = null
            axios.post('lien', {
              name: this.name,
              description: this.description,
              url: this.url
          })
          .then(response => {
              this.$emit('store', response.data)
          })
          .catch(error => {
              let key = Object.keys(error.response.data['errors'])
              if (key.length > 1) {
                  key = key[0]
              }
              this.errors = error.response.data['errors'][key][0]
              console.log(error)
          })
        },
        update () {
            this.errors = null
            axios.patch('lien/' + this.selected.id , {
                name: this.name,
                description: this.description,
                url: this.url
            })
            .then(response => {
                this.$emit('update', response.data)
            })
            .catch(error => {
                let key = Object.keys(error.response.data['errors'])
                if (key.length > 1) {
                    key = key[0]
                }
                this.errors = error.response.data['errors'][key][0]
                console.log(error)
            })
        },
        closeModal () {
            this.name = null
            this.description = null
            this.url = null
            this.errors = null
            this.$emit('closeModal')
        },
        beforeMounted () {
            if (this.isUpdate) {
                this.name = this.selected.name
            }
        }
    }
}
</script>

<style scoped>

</style>
