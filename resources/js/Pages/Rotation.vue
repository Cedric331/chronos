<template>
    <notifications position="bottom right" />
    <Head>
        <title>Rotations</title>
    </Head>
    <Loading
        :show="show"
        :loader-class="loadClass"
        :label="label">
    </Loading>
    <BreezeAuthenticatedLayout>
            <div class="flex overflow-hidden">
                <div class="h-full w-full bg-white relative overflow-y-auto lg:m-24 mx-auto p-5">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Création du Planning</h3>
                    </div>
                    <main>
                        <div class="pt-6 px-4">
                            <div class="w-full gap-4 flex justify-between">
                                <div>
                                    <div class="flex flex-col justify-between">
                                        <label for="start">Semaine de début:</label>
                                        <input type="week" id="start" name="trip-start"
                                               v-model="dateStart"
                                               :min="dateLimitStart" :max="dateLimitEnd">
                                        <label for="end">Semaine de Fin :</label>
                                        <input type="week" id="end" name="trip-end"
                                               v-model="dateEnd"
                                               :min="dateLimitStart" :max="dateLimitEnd">
                                    </div>
                                    <div class="border border-4 shadow-2xl my-5 w-full mx-auto rounded-md p-16 flex flex-col sm:flex-row sm:justify-evenly">
                                        <div class="p-16 flex flex-col bg-white rounded-lg">
                                            <h1 class="font-semibold tracking-wide mb-2">Choisir le conseiller</h1>

                                            <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
                                                <div class="flex justify-center">
                                                    <div class="w-auto">
                                                        <select v-model="collaborateur" class=" block w-full text-base font-normal text-gray-700 bg-white bg-clip-padding bg-no-repeat border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600" aria-label="Voir le planning">
                                                            <option v-for="member in collaborateurs" :key="member.id" :value="member">
                                                                {{ member.name }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="p-16 flex flex-col bg-white rounded-lg">
                                            <h1 class="font-semibold tracking-wide mb-2">Ordre des rotations</h1>
                                            <draggable v-if="listRotation.length > 0" class="dragArea list-group w-full" :list="listRotation">
                                                <div
                                                    class="list-group-item bg-gray-700 m-1 p-3 rounded-md text-center text-white"
                                                    v-for="(rotation, index) in listRotation"
                                                    :key="index">
                                                    <div class="flex justify-between">
                                                        <div class="mr-5">
                                                            {{ rotation.type }}
                                                        </div>
                                                        <div @click="deleteListRotation(index)">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>
                                            </draggable>
                                            <div v-else class="w-full">
                                                <p>-- Ajouter des rotations --</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex justify-center">
                                        <button @click="generatePlanning()" class="text-sm font-medium hover:bg-gray-700 bg-black text-white rounded-lg p-2">Confirmer le Planning</button>
                                    </div>
                                </div>

                                <div class="rounded-lg p-4 sm:p-6 xl:p-8 ">
                                    <div class="mb-4 flex items-center justify-between">
                                        <div>
                                            <h3 class="text-xl font-bold text-gray-900 mb-2">Les Rotations</h3>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <button @click="showModal()" class="text-sm font-medium hover:bg-gray-700 bg-black text-white rounded-lg p-2">Créer une rotation</button>
                                        </div>
                                    </div>
                                    <div class="flex flex-col mt-8">
                                        <div class="overflow-x-auto drop-shadow-xl rounded-lg">
                                            <div class=" drop-shadow-xl align-middle inline-block min-w-full">
                                                <div class="shadow overflow-y-auto h-[400px] sm:rounded-lg">
                                                    <table class="min-w-full divide-y divide-gray-200">
                                                        <thead class="bg-black">
                                                        <tr>
                                                            <th scope="col" class="p-4 text-left text-xs font-medium text-white uppercase tracking-wider">
                                                            </th>
                                                            <th scope="col" class="p-4 text-left text-xs font-medium text-white uppercase tracking-wider">
                                                                Nom de la Rotation
                                                            </th>
                                                            <th scope="col" class="p-4 text-left text-xs font-medium text-white uppercase tracking-wider">
                                                                Nombre d'heures sur la rotation
                                                            </th>
                                                            <th scope="col" class="p-4 text-left text-xs font-medium text-white uppercase tracking-wider">
                                                                Actions
                                                            </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody class="bg-white">
                                                        <tr v-for="rotation in indexRotation">
                                                            <td @click="this.listRotation.push(rotation)" class="p-4 cursor-pointer bg-black text-white whitespace-nowrap text-sm font-bold">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15m0 0l6.75 6.75M4.5 12l6.75-6.75" />
                                                                </svg>
                                                            </td>
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
                                                                <button @click="confirmDelete(rotation)">
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
    <ModalConfirmDelete
        v-if="confirmDel"
        @closeModal="this.closeModal()"
        @deleted="this.delete()"
    />
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import {Head} from "@inertiajs/inertia-vue3"
import ModalRotation from "@/Components/ModalRotation";
import ModalConfirmDelete from "@/Components/ModalConfirmDelete";
import { VueDraggableNext } from 'vue-draggable-next'
import Loading from 'vue-full-loading'

export default {
    name: "Rotation",
    components: {
        ModalConfirmDelete,
        ModalRotation,
        Loading,
        BreezeAuthenticatedLayout,
        draggable: VueDraggableNext,
        Head
    },
    props: {
        dateLimitStart: String,
        dateLimitEnd: String,
        rotations: Object,
        collaborateurs: Object
    },
    data () {
        return {
            show: false,
            label: 'Création du planning en cours...',
            loadClass: 'load-class',
            confirmDel: false,
            collaborateur: null,
            showModalRotation: false,
            listRotation: [],
            indexRotation: null,
            dateStart: null,
            dateEnd: null,
            rotation: null,
            isUpdate: false
        }
    },
    methods: {
        generatePlanning () {
        this.show = true
          axios.post('generate', {
                  rotations: this.listRotation,
                  collaborateur: this.collaborateur,
                  dateStart: this.dateStart,
                  dateEnd: this.dateEnd,
              })
            .then(() => {
                this.$notify({
                    title: "Succès",
                    text: "Planning crée avec succès !",
                    type: 'success',
                });
                this.$inertia.reload()
            })
            .catch(err => {
                console.log(err)
            })
            .finally(() => {
                this.show = false
            })
        },
        deleteListRotation (index) {
            this.listRotation = this.listRotation.filter((item, key) => {
                return key !== index
            })
        },
        delete () {
            this.confirmDel = false
            axios.delete('rotation/' + this.rotation.id)
            .then(() => {
                this.indexRotation = this.indexRotation.filter(item => {
                    return item !== this.rotation
                })
            })
            .catch(err => {
                console.log(err)
            })
            .finally(() => {
                this.rotation = null
            })
        },
        confirmDelete (data) {
            this.rotation = data
            this.confirmDel = true
        },
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
            this.confirmDel = false
            this.showModalRotation = false
            this.isUpdate = false
            this.rotation = null
        }
    },
    mounted () {
        this.dateStart = this.dateLimitStart
        this.dateEnd = this.dateLimitEnd
        this.indexRotation = this.rotations
    }
}
</script>

<style>
.load-class {
    position: absolute;
    display: inline-block;
    right:40%;
    bottom:50%;
}
</style>
