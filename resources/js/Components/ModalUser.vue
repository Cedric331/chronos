<template>
    <div class="fixed z-10 inset-0" aria-labelledby="utilisateur" role="dialog" aria-modal="true">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
          <div @click="closeModal()" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

          <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
              <div class="py-12 transition duration-150 ease-in-out z-10 absolute top-0 right-0 bottom-0 left-0" id="modal">
                  <div role="alert" class="container mx-auto w-11/12 md:w-2/3 max-w-lg">
                      <div class="relative py-8 px-5 md:px-10 bg-white shadow-md rounded border border-gray-400">
                          <h1 class="text-gray-800 font-lg font-bold tracking-normal leading-tight mb-4">Création d'un utilisateur</h1>
                          <label for="name" class="text-gray-800 text-sm font-bold leading-tight tracking-normal">Nom de l'Utilisateur</label>
                          <div class="relative mb-5 mt-2">
                              <div class="absolute text-gray-600 flex items-center px-4 border-r h-full">
                                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                  </svg>
                              </div>
                              <input v-model="name" type="text" id="name" class="text-gray-600 focus:outline-none focus:border focus:border-indigo-700 font-normal w-full h-10 flex items-center pl-16 text-sm border-gray-300 rounded border" placeholder="Nom de l'utilisateur" />
                          </div>
                          <label for="email" class="text-gray-800 text-sm font-bold leading-tight tracking-normal">Email de l'Utilisateur</label>
                          <div class="relative mb-5 mt-2">
                              <div class="absolute text-gray-600 flex items-center px-4 border-r h-full">
                                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                      <path stroke-linecap="round" d="M16.5 12a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zm0 0c0 1.657 1.007 3 2.25 3S21 13.657 21 12a9 9 0 10-2.636 6.364M16.5 12V8.25" />
                                  </svg>
                              </div>
                              <input type="email" id="email" v-model="email" class="text-gray-600 focus:outline-none focus:border focus:border-indigo-700 font-normal w-full h-10 flex items-center pl-16 text-sm border-gray-300 rounded border" placeholder="Adresse email de l'utilisateur" />
                          </div>
                          <div v-if="isUpdate" class="relative mb-5 mt-2">
                              <label class="block mb-2 text-sm font-bold text-gray-700">
                                  Hub
                              </label>
                              <select v-model="hub_id" class="form-select appearance-none block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding bg-no-repeat border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" style="min-width: 135px">
                                  <option v-for="item in hubs" :key="item.id" :value="item.id">{{ item.ville }}</option>
                              </select>
                          </div>
                          <div v-if="isAdmin" class="relative mb-5 mt-2">
                              <div class="relative w-full h-full flex justify-around">
                                  <div>
                                      <input v-model="status" type="radio" class="cursor-pointer mx-2" id="Conseiller" name="status" value="Conseiller" checked>
                                      <label for="Conseiller">Conseiller</label>
                                  </div>
                                  <div>
                                      <input v-model="status" type="radio" class="cursor-pointer mx-2" id="Coordinateur" name="status" value="Coordinateur">
                                      <label for="Coordinateur">Coordinateur</label>
                                  </div>
                              </div>
                          </div>
                          <div class="flex items-center justify-between w-full">
                              <button class="focus:outline-none focus:ring-2 focus:ring-offset-2  focus:ring-gray-400 ml-3 bg-gray-100 transition duration-150 text-gray-600 ease-in-out hover:border-gray-400 hover:bg-gray-300 border rounded px-8 py-2 text-sm" @click="closeModal()">Annuler</button>
                              <button class="focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-700 transition duration-150 ease-in-out hover:bg-indigo-600 bg-indigo-700 rounded text-white px-8 py-2 text-sm" @click="store()">
                                  {{ isUpdate ? 'Modifier' : 'Créer' }}
                              </button>
                              <div v-if="errors" class="mb-4 sm:align-middle font-medium text-sm text-red-600">-->
                                  {{ errors }}
                              </div>
                          </div>
                      </div>
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
            status: 'Conseiller',
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
            this.status = 'Conseiller'
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
          axios.patch('/equipe/' + this.userUpdate.id, {
              name: this.name,
              email: this.email,
              hub: this.hub_id
          })
            .then(() => {
                this.$emit('update', 'user')
            })
            .catch(error => {
                this.$emit('error')
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
                       this.$emit('error')
                       console.log(error)
                   })
                       .finally(() => {
                           this.closeModal()
                       })
               } else {
                   axios.post('/equipe',{
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
            axios.get('/hub')
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
