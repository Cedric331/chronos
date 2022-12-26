<template>
    <div class="fixed z-10 inset-0" aria-labelledby="utilisateur" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div @click="this.$emit('closeModal')" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="flex items-end justify-center pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="py-12 transition duration-150 ease-in-out z-10 absolute top-0 right-0 bottom-0 left-0" id="modal">
                    <div role="alert" class="container mx-auto max-w-[60rem]">
                        <div class="relative py-8 px-5 md:px-10 bg-white shadow-md rounded max-h-[40rem] overflow-auto">
                            <h1 class="text-gray-800 font-lg font-bold tracking-normal leading-tight mb-4">Information sur les logs</h1>

                            <div class="relative mb-5 mt-2 overflow-y-auto">
                                <div v-for="log in logs" class="text-gray-600 font-normal w-full h-12 flex items-center text-sm">
                                    <p>{{ log }}</p>
                                </div>
                            </div>

                            <div class="flex items-center justify-center w-full">
                                <button v-if="logs[0]" @click="deleteLog()" class="focus:outline-none focus:ring-2 focus:ring-offset-2 bg-green-600 focus:ring-green-400 ml-3 transition duration-150 text-white ease-in-out hover:border-green-400 border rounded px-8 py-2 text-sm">Vider les logs</button>
                                <button class="focus:outline-none focus:ring-2 focus:ring-offset-2 bg-blue-600 focus:ring-gray-400 ml-3 transition duration-150 text-white ease-in-out hover:border-blue-400 border rounded px-8 py-2 text-sm" @click="this.$emit('closeModal')">Fermer</button>
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
    name: "ModalLog",
    emits: ['closeModal'],
    props: {
        logs: Object
    },
    methods: {
        deleteLog () {
            axios.get('/administration/delete/log')
            .then(() => {
                this.$inertia.reload()
            })
        }
    }
}
</script>

<style scoped>

</style>
