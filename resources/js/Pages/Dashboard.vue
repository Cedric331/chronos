<template>
    <notifications position="bottom right" />
    <Head title="Tableau de bord" />
    <Loading
        :show="show"
        :loader-class="loadClass"
        :label="label">
    </Loading>
    <BreezeAuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Information sur le HUB de <b>{{ $page.props.hub.ville }}</b>
                        </h3>
                    </div>
                    <div class="border-t border-gray-200">
                        <dl>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">
                                    Abonné Mobile
                                </dt>
                                <div v-if="$page.props.auth.user.coordinateur">
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        <input type="number" maxlength="5" minlength="5" v-model="abonne_mobile" @change="update()">
                                    </dd>
                                </div>
                                <div v-else>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ abonne_mobile }}
                                    </dd>
                                </div>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">
                                    Abonné Freebox
                                </dt>
                                <div v-if="$page.props.auth.user.coordinateur">
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        <input type="number" maxlength="5" minlength="5" v-model="abonne_freebox" @change="update()">
                                    </dd>
                                </div>
                                <div v-else>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ abonne_freebox }}
                                    </dd>
                                </div>
                            </div>
                            <div v-if="$page.props.auth.user.coordinateur" class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">
                                    Importé Planning (Excel)
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <form @submit.prevent="confirmImport" enctype="multipart/form-data">
                                        <input type="file" @change="onChange" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150" required/>
                                        <button type="submit" class="ml-5 bg-white sm:mt-5 py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            Importer
                                        </button>
                                    </form>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
        <ModalConfirmImport v-if="confirModal" @closeConfirm="this.confirModal = false" @validated="this.validated()"></ModalConfirmImport>
    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head } from '@inertiajs/inertia-vue3';
import Loading from 'vue-full-loading'
import ModalConfirmImport from "@/Components/ModalConfirmImport";

export default {
    components: {
        ModalConfirmImport,
        BreezeAuthenticatedLayout,
        Loading,
        Head,
    },
    data() {
        return {
            abonne_freebox: this.$page.props.hub.abonne_freebox,
            abonne_mobile: this.$page.props.hub.abonne_mobile,
            file: null,
            show: false,
            confirModal: false,
            label: 'Chargement en cours...',
            loadClass: 'load-class'
        }
    },
    methods: {
        update () {
            axios.patch('hub/' + this.$page.props.hub.id, {
                abonne_freebox: this.abonne_freebox,
                abonne_mobile: this.abonne_mobile,
            }).then(() => {
                this.$notify({
                    title: "Succès",
                    text: "Modification du hub effectuée avec succès!",
                    type: 'success',
                });
            })
        },
        confirmImport () {
          this.confirModal = true
        },
        onChange (event) {
          this.file = event.target.files[0]
        },
        validated () {
            this.confirModal = false
            this.importExcel()
        },
        importExcel (event) {
            this.show = true
            let data = new FormData()
            data.append('file', this.file)

            const config = {
                headers: {
                    'content-type': 'multipart/form-data'
                }
            }

            axios.post('import/hub/' + this.$page.props.hub.id, data, config)
                .then(() => {
                    this.show = false
                    this.$notify({
                        title: "Succès",
                        text: "Le fichier est bien importé!",
                        type: 'success',
                    });
                }).catch(error => {
                    if (error.response.status === 403) {
                        this.$notify({
                            title: "Action non autorisé",
                            text: "Vous n\'avez pas les droits !",
                            type: 'danger',
                        });
                    } else {
                        this.$notify({
                            title: "Échec",
                            text: "Oups désolé il y a une erreur!",
                            type: 'danger',
                        });
                    }
                }).finally(() => {
                    this.show = false
                })
        }
    }
}
</script>

<style>
.success {
    margin: 5px;
    padding: 10px;
    font-size: 12px;
     background: #68cd86;
     border-left-color: #42a85f;
 }

.warn {
     background: #ffb648;
     border-left-color: #f48a06;
 }

.error {
     background: #e54d42;
     border-left-color: #b82e24;
 }
.load-class {
    position: absolute;
    display: inline-block;
    right:40%;
    bottom:50%;
}
</style>
