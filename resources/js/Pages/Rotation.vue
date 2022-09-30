<template>
    <notifications position="bottom right" />
    <Head>
        <title>Rotations</title>
    </Head>
    <BreezeAuthenticatedLayout>
            <div class="flex overflow-hidden">
                <div class="h-full w-full bg-gray-50 relative overflow-y-auto lg:m-24 m-auto">
                    <main>
                        <div class="pt-6 px-4">
                            <div class="w-full grid grid-cols-1 xl:grid-cols-2 2xl:grid-cols-3 gap-4">
                                <div class="bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8  2xl:col-span-2">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex-shrink-0">
                                            <span class="text-2xl sm:text-3xl leading-none font-bold text-gray-900">$45,385</span>
                                            <h3 class="text-base font-normal text-gray-500">Sales this week</h3>
                                        </div>
                                        <div class="flex items-center justify-end flex-1 text-green-500 text-base font-bold">
                                            12.5%
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div id="main-chart"></div>
                                </div>
                                <div class="shadow rounded-lg p-4 sm:p-6 xl:p-8 ">
                                    <div class="mb-4 flex items-center justify-between">
                                        <div>
                                            <h3 class="text-xl font-bold text-gray-900 mb-2">Les Rotations</h3>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <button @click="showModal()" class="text-sm font-medium hover:bg-gray-700 bg-black text-white rounded-lg p-2">Créer une rotation</button>
                                        </div>
                                    </div>
                                    <div class="flex flex-col mt-8">
                                        <div class="overflow-x-auto rounded-lg">
                                            <div class="align-middle inline-block min-w-full">
                                                <div class="shadow overflow-hidden sm:rounded-lg">
                                                    <table class="min-w-full divide-y divide-gray-200">
                                                        <thead class="bg-gray-50">
                                                        <tr>
                                                            <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                Nom de la Rotation
                                                            </th>
                                                            <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                Nombres d'heures sur la rotation
                                                            </th>
                                                            <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                Actions
                                                            </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody class="bg-white">
                                                        <tr v-for="rotation in indexRotation">
                                                            <td class="p-4 whitespace-nowrap text-sm font-bold">
                                                                {{ rotation.type }}
                                                            </td>
                                                            <td class="p-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                                                {{ rotation.hours }}
                                                            </td>
                                                            <td class="flex justify-between p-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                                                <button @click="showModal(true, rotation)">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 26 26" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                                    </svg>
                                                                </button>
                                                                <button>
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 25 26" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                                    </svg>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 w-full grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                                <div class="bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8 ">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <span class="text-2xl sm:text-3xl leading-none font-bold text-gray-900">2,340</span>
                                            <h3 class="text-base font-normal text-gray-500">New products this week</h3>
                                        </div>
                                        <div class="ml-5 w-0 flex items-center justify-end flex-1 text-green-500 text-base font-bold">
                                            14.6%
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8 ">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <span class="text-2xl sm:text-3xl leading-none font-bold text-gray-900">5,355</span>
                                            <h3 class="text-base font-normal text-gray-500">Visitors this week</h3>
                                        </div>
                                        <div class="ml-5 w-0 flex items-center justify-end flex-1 text-green-500 text-base font-bold">
                                            32.9%
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8 ">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <span class="text-2xl sm:text-3xl leading-none font-bold text-gray-900">385</span>
                                            <h3 class="text-base font-normal text-gray-500">User signups this week</h3>
                                        </div>
                                        <div class="ml-5 w-0 flex items-center justify-end flex-1 text-red-500 text-base font-bold">
                                            -2.7%
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M14.707 12.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
    </BreezeAuthenticatedLayout>
    <ModalRotation
        v-if="showModalRotation"
        :isUpdate="isUpdate"
        :rotation="rotation"
        @storeRotation="(data, update = false) => this.postRotation(data, update)"
        @closeModal="this.closeModal()"
    />
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import {Head} from "@inertiajs/inertia-vue3"
import ModalRotation from "@/Components/ModalRotation";

export default {
    name: "Rotation",
    components: {
        ModalRotation,
        BreezeAuthenticatedLayout,
        Head
    },
    props: {
      rotations: Object
    },
    data () {
        return {
            showModalRotation: false,
            indexRotation: null,
            rotation: null,
            isUpdate: false
        }
    },
    methods: {
        postRotation (data, update) {
            if (update) {
                this.indexRotation.forEach((item, index) => {
                    if (item.id === data.id) {
                        this.indexRotation[index] = data
                    }
                })
            } else {
                this.indexRotation.push(data)
            }
        },
        showModal (isUpdated = false, data = null) {
            if (isUpdated) {
                this.isUpdate = isUpdated
                this.rotation = data
            }
            this.showModalRotation = true
        },
        closeModal () {
            this.showModalRotation = false
            this.isUpdate = false
            this.rotation = null
        }
    },
    mounted () {
        this.indexRotation = this.rotations
    }
}
</script>

<style scoped>

</style>
