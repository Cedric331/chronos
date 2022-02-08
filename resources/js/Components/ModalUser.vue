<template>
    <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="utilisateur" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:w-full" :style="isUpdate ? 'max-width: 38rem' : 'max-width: 32rem'">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="mb-4 md:flex md:justify-between">
                        <div class="mb-4 md:mr-2 md:mb-0">
                            <label class="block mb-2 text-sm font-bold text-gray-700" for="prenom">
                                Prénom du collaborateur
                            </label>
                            <input
                                class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                id="prenom"
                                type="text"
                                v-model="name"
                                placeholder="Prénom"
                            />
                        </div>
                        <div class="md:ml-2">
                            <label class="block mb-2 text-sm font-bold text-gray-700" for="mail">
                                Email
                            </label>
                            <input
                                class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                id="mail"
                                type="email"
                                v-model="email"
                                placeholder="Email"
                            />
                        </div>
                        <div v-if="isUpdate" class="md:ml-2">
                            <label class="block mb-2 text-sm font-bold text-gray-700">
                                Hub
                            </label>
                            <select v-model="hub_id" class="form-select appearance-none block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding bg-no-repeat border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" style="min-width: 135px">
                                <option v-for="item in hubs" :key="item.id" :value="item.id">{{ item.ville }}</option>
                            </select>
                        </div>
                        <div v-if="isAdmin" class="md:ml-2">
                            <div class="form-check form-switch">
                                <input v-model="status" class="form-check-input appearance-none w-9 -ml-10 rounded-full float-left h-5 align-top bg-white bg-no-repeat bg-contain bg-gray-300 focus:outline-none cursor-pointer shadow-sm" type="checkbox" role="switch" id="status" checked>
                                <label class="form-check-label inline-block text-gray-800" for="status">
                                    {{ status ? 'Coordinateur' : 'Conseiller'}}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button @click="store()" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">
                        {{ isUpdate ? 'Modifier' : 'Créer' }}
                    </button>
                    <button @click="closeModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Annuler
                    </button>
                    <div v-if="errors" class="mb-4 sm:align-middle font-medium text-sm text-red-600">
                        {{ errors }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ModalUser",
    props: {
        isAdmin: Boolean,
        isUpdate: Boolean,
        userUpdate: Object,
        hub: Number
    },
    data () {
        return {
            name: null,
            email: null,
            hubs: null,
            hub_id: null,
            status: false,
            errors: null
        }
    },
    watch: {
        isUpdate: function () {
            if (this.isUpdate) {
                this.name = this.userUpdate.name
                this.email = this.userUpdate.email
                this.hub_id = this.userUpdate.hub_id
            }
        }
    },
    methods: {
        closeModal () {
            this.name = null
            this.email = null
            this.errors = null
            this.status = false
            this.$emit('closeModal', false)
        },
        verification () {
          if (this.name === null || this.email === null) {
              this.errors = "Vous devez remplir tous les champs"
          }  else {
              return true
          }
        },
        update () {
          axios.patch('equipe/' + this.userUpdate.id, {
              name: this.name,
              email: this.email,
              hub: this.hub_id
          })
            .then(() => {
                this.$emit('refresh')
            })
            .catch(error => {
                console.log(error)
            })
          .finally(() => {
              this.closeModal()
          })
        },
        store () {
           if (this.verification()) {
               if (this.isUpdate) {
                   this.update()
                   return
               }
               if (this.isAdmin) {
                   axios.post('administration/create/user',{
                       name: this.name,
                       email: this.email,
                       status: this.status,
                       hub: this.hub
                   }).then(() => {
                       this.$emit('update', true)
                   }).catch(error => {
                       console.log(error)
                   })
                       .finally(() => {
                           this.closeModal()
                       })
               } else {
                   axios.post('equipe',{
                       name: this.name,
                       email: this.email
                   }).then(response => {
                       this.$emit('update', response.data)
                   }).catch(error => {
                       this.$emit('error', error.response.data)
                   })
                   .finally(() => {
                       this.closeModal()
                   })
               }
           }
        },
        getHub () {
            axios.get('hub')
            .then(response => {
                this.hubs = response.data
            })
            .catch(error => {
                console.log(error)
            })
        }
    },
    mounted() {
        this.getHub()
    }
}
</script>

<style scoped>

</style>
