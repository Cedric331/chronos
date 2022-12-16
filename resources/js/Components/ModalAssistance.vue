<template>
    <div class="fixed z-10 inset-0" aria-labelledby="utilisateur" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div @click="closeModal()" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="py-12 transition duration-150 ease-in-out z-10 absolute top-0 right-0 bottom-0 left-0" id="modal">
                    <div role="alert" class="container mx-auto w-11/12 md:w-2/3 max-w-lg">
                        <div class="relative py-8 px-5 md:px-10 bg-white shadow-md rounded border border-gray-400">
                            <h1 class="text-gray-800 font-lg font-bold tracking-normal leading-tight mb-4">Formulaire de contact</h1>

                            <label for="subject" class="text-gray-800 text-sm font-bold leading-tight tracking-normal">Sujet</label>
                            <div class="relative mb-5 mt-2">
                                <select v-model="subject" id="subject" class="text-gray-600 focus:outline-none focus:border focus:border-indigo-700 font-normal w-full h-12 flex items-center text-lg border-gray-300 rounded border">
                                    <option value="Signaler un bug" selected>Signaler un bug</option>
                                    <option value="Demander une amélioration">Demander une amélioration</option>
                                </select>
                            </div>

                            <label for="text" class="text-gray-800 text-sm font-bold">Message</label>
                            <div class="relative mb-5 mt-2">
                                <textarea id="text" v-model="text" rows="10" cols="10" class="text-gray-600 focus:outline-none focus:border focus:border-indigo-700 font-normal w-full flex items-center text-sm border-gray-300 rounded border" placeholder="Votre message..." />
                            </div>

                            <div class="flex items-center justify-between w-full">
                                <button class="focus:outline-none focus:ring-2 focus:ring-offset-2  focus:ring-gray-400 ml-3 bg-gray-100 transition duration-150 text-gray-600 ease-in-out hover:border-gray-400 hover:bg-gray-300 border rounded px-8 py-2 text-sm" @click="closeModal()">Annuler</button>
                                <button @click="send()" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 inline-flex items-center">
                                    <svg v-if="isLoading" class="inline mr-3 w-4 h-4 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
                                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
                                    </svg>
                                    Envoyer
                                </button>
                            </div>
                            <div v-if="errors" class="mt-2 sm:align-middle font-medium text-sm text-red-600">
                                {{ errors }}
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
    name: "ModalAssistance",
    data () {
        return {
            subject: 'Signaler un bug',
            isLoading: false,
            text: null,
            errors: null
        }
    },
    methods: {
        closeModal (value = false) {
            this.subject = 'Signaler un bug'
            this.text = null
            this.errors = null
            this.$emit('closeModal', value)
        },
        send () {
            this.errors = null
            this.isLoading = true
            axios.post('/contact', {
                subject: this.subject,
                text: this.text
            })
            .then(() => {
                this.subject = 'Signaler un bug'
                this.text = null
                this.closeModal(true)
            })
            .catch(error => {
                this.errors = 'Vous devez indiquer le Sujet et le Message'
                console.log(error)
            })
            .finally(() => {
                this.isLoading = false
            })
        }
    }
}
</script>

<style scoped>

</style>
