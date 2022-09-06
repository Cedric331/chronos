<template>
    <div class="fixed fade z-10 inset-0 max-w-2xl h-full mx-auto outline-none overflow-x-hidden overflow-y-hidden" aria-labelledby="modification planning" role="dialog" aria-modal="true">

        <div class="flex items-end justify-center pt-4 px-4 pb-20 text-center sm:block sm:p-0 w-10/12 mb-6 mx-auto">
            <div @click="closeModal()" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left shadow-xl transform transition-all sm:my-4 sm:align-middle w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <section class="container p-6 mx-auto bg-white">
                            <h2 class="font-bold text-center text-3xl text-gray-800 md:text-2xl mb-5">
                                Switch du planning de {{ collaborateur.name }}
                            </h2>
                            <hr/>
                            <div class="max-w-2xl mx-auto">
                                <div class="flex flex-col">
                                    <div class="overflow-x-auto shadow-md sm:rounded-lg">
                                        <div class="inline-block min-w-full align-middle">
                                            <div class="overflow-hidden ">
                                                <table class="min-w-full divide-y divide-gray-200 table-fixed bg-gray-700 dark:divide-gray-700">
                                                    <thead class="bg-gray-700">
                                                    <tr>
                                                        <th class="font-bold bg-gray-700 text-center text-lg text-white md:text-lg mb-5">
                                                            Veuillez indiquer avec qui switch le planning
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th scope="col" class="py-3 px-6 text-xs text-center font-medium tracking-wider text-gray-700 uppercase dark:text-gray-400">
                                                            Collaborateur
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                                    <tr v-for="member in collaborateurs" class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                                        <td class="py-4 px-6 text-sm font-medium text-center text-gray-900 whitespace-nowrap dark:text-white">{{member.name}}</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" class="mt-3 w-full inline-flex justify-center bg-blue-500 rounded-md border shadow-sm px-4 py-2 text-base font-medium text-white sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Valider</button>
                    <button @click="closeModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Fermer</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ModalSwitchPlanning",
    props: {
        collaborateurs: Object,
        collaborateur: Object,
        selected: Array
    }
}
</script>

<style scoped>

</style>
