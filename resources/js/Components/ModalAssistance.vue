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
                                    <option value="bug" selected>Signaler un bug</option>
                                    <option value="amelioration">Demander une amélioration</option>
                                </select>
                            </div>

                            <label for="text" class="text-gray-800 text-sm font-bold">Message</label>
                            <div class="relative mb-5 mt-2">
                                <textarea id="text" v-model="text" rows="10" cols="10" class="text-gray-600 focus:outline-none focus:border focus:border-indigo-700 font-normal w-full flex items-center text-sm border-gray-300 rounded border" placeholder="Votre message..." />
                            </div>

                            <div class="flex items-center justify-between w-full">
                                <button class="focus:outline-none focus:ring-2 focus:ring-offset-2  focus:ring-gray-400 ml-3 bg-gray-100 transition duration-150 text-gray-600 ease-in-out hover:border-gray-400 hover:bg-gray-300 border rounded px-8 py-2 text-sm" @click="closeModal()">Annuler</button>
                                <button class="focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-700 transition duration-150 ease-in-out hover:bg-indigo-600 bg-indigo-700 rounded text-white px-8 py-2 text-sm" @click="send()">
                                    Envoyer
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
    name: "ModalAssistance",
    data () {
        return {
            subject: 'bug',
            text: null,
            errors: null
        }
    },
    methods: {
        closeModal (value = false) {
            this.subject = null
            this.text = null
            this.$emit('closeModal', value)
        },
        send () {
            axios.post('/equipe/information/' + this.userUpdate.id, {
                subject: this.subject,
                text: this.text
            })
            .then(() => {
                this.subject = null
                this.text = null
                this.closeModal(true)
            })
            .catch(error => {
                console.log(error)
            })
        }
    }
}
</script>

<style scoped>

</style>
