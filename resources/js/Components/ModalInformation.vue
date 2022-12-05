<template>
<!--    <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="utilisateur" role="dialog" aria-modal="true">-->
<!--        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">-->
<!--            <div @click="closeModal()" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>-->
<!--            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>-->
<!--            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:w-full" :style="isUpdate ? 'max-width: 38rem' : 'max-width: 32rem'">-->
<!--                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">-->
<!--                    <div class="mb-4 md:flex md:justify-between">-->
<!--                        <div class="mb-4 md:mr-2 md:mb-0">-->
<!--                            <label class="block mb-2 text-sm font-bold text-gray-700" for="prenom">-->
<!--                                Prénom-->
<!--                            </label>-->
<!--                            <input-->
<!--                                class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"-->
<!--                                id="prenom"-->
<!--                                type="text"-->
<!--                                v-model="name"-->
<!--                                placeholder="Prénom"-->
<!--                            />-->
<!--                        </div>-->
<!--                        <div class="md:ml-2">-->
<!--                            <label class="block mb-2 text-sm font-bold text-gray-700" for="mail">-->
<!--                                Email-->
<!--                            </label>-->
<!--                            <input-->
<!--                                class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"-->
<!--                                id="mail"-->
<!--                                type="email"-->
<!--                                v-model="email"-->
<!--                                placeholder="Email"-->
<!--                            />-->
<!--                        </div>-->
<!--                        <div class="md:ml-2">-->
<!--                            <label class="block mb-2 text-sm font-bold text-gray-700" for="mail">-->
<!--                                Téléphone-->
<!--                            </label>-->
<!--                            <input-->
<!--                                class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"-->
<!--                                id="phone"-->
<!--                                type="number"-->
<!--                                v-model="phone"-->
<!--                                placeholder="Téléphone"-->
<!--                            />-->
<!--                        </div>-->
<!--                        <div class="md:ml-2">-->
<!--                            <label class="block mb-2 text-sm font-bold text-gray-700" for="mail">-->
<!--                                Anniversaire-->
<!--                            </label>-->
<!--                            <input-->
<!--                                class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"-->
<!--                                id="anniversaire"-->
<!--                                type="date"-->
<!--                                v-model="anniversaire"-->
<!--                                placeholder="Anniversaire"-->
<!--                            />-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">-->
<!--                    <button @click="store()" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">-->
<!--                        {{ isUpdate ? 'Modifier' : 'Créer' }}-->
<!--                    </button>-->
<!--                    <button @click="closeModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">-->
<!--                        Annuler-->
<!--                    </button>-->
<!--                    <div v-if="errors" class="mb-4 sm:align-middle font-medium text-sm text-red-600">-->
<!--                        {{ errors }}-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->

    <div class="fixed z-10 inset-0" aria-labelledby="utilisateur" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div @click="closeModal()" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="py-12 transition duration-150 ease-in-out z-10 absolute top-0 right-0 bottom-0 left-0" id="modal">
                    <div role="alert" class="container mx-auto w-11/12 md:w-2/3 max-w-lg">
                        <div class="relative py-8 px-5 md:px-10 bg-white shadow-md rounded border border-gray-400">
                            <h1 class="text-gray-800 font-lg font-bold tracking-normal leading-tight mb-4">Information de l'utilisateur</h1>
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

                            <label for="phone" class="text-gray-800 text-sm font-bold leading-tight tracking-normal">Numéro de l'Utilisateur</label>
                            <div class="relative mb-5 mt-2">
                                <div class="absolute text-gray-600 flex items-center px-4 border-r h-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                                    </svg>
                                </div>
                                <input type="number" id="phone" v-model="phone" class="text-gray-600 focus:outline-none focus:border focus:border-indigo-700 font-normal w-full h-10 flex items-center pl-16 text-sm border-gray-300 rounded border" placeholder="Adresse email de l'utilisateur" />
                            </div>

                            <label for="anniversaire" class="text-gray-800 text-sm font-bold leading-tight tracking-normal">Anniversaire de l'Utilisateur</label>
                            <div class="relative mb-5 mt-2">
                                <div class="absolute text-gray-600 flex items-center px-4 border-r h-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8.25v-1.5m0 1.5c-1.355 0-2.697.056-4.024.166C6.845 8.51 6 9.473 6 10.608v2.513m6-4.87c1.355 0 2.697.055 4.024.165C17.155 8.51 18 9.473 18 10.608v2.513m-3-4.87v-1.5m-6 1.5v-1.5m12 9.75l-1.5.75a3.354 3.354 0 01-3 0 3.354 3.354 0 00-3 0 3.354 3.354 0 01-3 0 3.354 3.354 0 00-3 0 3.354 3.354 0 01-3 0L3 16.5m15-3.38a48.474 48.474 0 00-6-.37c-2.032 0-4.034.125-6 .37m12 0c.39.049.777.102 1.163.16 1.07.16 1.837 1.094 1.837 2.175v5.17c0 .62-.504 1.124-1.125 1.124H4.125A1.125 1.125 0 013 20.625v-5.17c0-1.08.768-2.014 1.837-2.174A47.78 47.78 0 016 13.12M12.265 3.11a.375.375 0 11-.53 0L12 2.845l.265.265zm-3 0a.375.375 0 11-.53 0L9 2.845l.265.265zm6 0a.375.375 0 11-.53 0L15 2.845l.265.265z" />
                                    </svg>
                                </div>
                                <input type="date" id="anniversaire" v-model="anniversaire" class="text-gray-600 focus:outline-none focus:border focus:border-indigo-700 font-normal w-full h-10 flex items-center pl-16 text-sm border-gray-300 rounded border" placeholder="Adresse email de l'utilisateur" />
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
    name: "ModalInformation",
    props: {
        isUpdate: Boolean,
        userUpdate: Object
    },
    data () {
        return {
            name: null,
            email: null,
            phone: null,
            anniversaire: null,
            errors: null
        }
    },
    watch: {
        isUpdate: function () {
            if (this.isUpdate) {
                this.name = this.userUpdate.name
                this.email = this.userUpdate.email
                this.phone = '0' + this.userUpdate.phone
                this.anniversaire = this.userUpdate.anniversaire_en
            }
        }
    },
    methods: {
        closeModal () {
            this.name = null
            this.email = null
            this.phone = null
            this.anniversaire = null
            this.errors = null
            this.$emit('closeModal', false)
        },
        verification () {
            if (this.phone !== null) {
                if (this.phone.toString().length !== 10 && this.phone.toString().length !== 9) {
                    this.errors = "Le champs téléphone est incorrect"
                    return false
                }
            }
            if (this.name === null || this.email === null) {
                this.errors = "Le champs nom et mail sont obligatoires"
                return false
            }  else {
                return true
            }
        },
        update () {
            axios.patch('/equipe/information/' + this.userUpdate.id, {
                name: this.name,
                email: this.email,
                anniversaire: this.anniversaire,
                phone: this.phone
            })
                .then(() => {
                    this.$emit('update', 'user')
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
                }
            }
        }
    }
}
</script>

<style scoped>

</style>
