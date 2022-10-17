<template>
    <notifications position="bottom right" />
    <Head title="Import Chronos" />
    <Loading
        :show="show"
        :loader-class="loadClass"
        :label="label">
    </Loading>
    <BreezeAuthenticatedLayout>
        <div class="py-12 h-screen">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Gestion du Hub de <b>{{ $page.props.hub.ville }}</b>
                        </h3>
                    </div>
                    <div class="border-t border-gray-200">
                        <dl>
                            <div v-if="$page.props.auth.user.coordinateur" class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500">
                                    Importer Planning (Excel)
                                </dt>
                                <dd class="text-sm text-gray-900 mt-3 sm:col-span-2">
                                    <form @submit.prevent="confirmImport" enctype="multipart/form-data">
                                        <input type="file" @change="onChange" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" class="inline-flex items-center w-full md:w-auto px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150" required/>
                                        <button type="submit" class="ml-0 md:ml-5 mt-3 md:mt-0 w-full md:w-auto bg-black text-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium hover:bg-white hover:text-black">
                                            Importer
                                        </button>
                                    </form>
                                </dd>
                            </div>
                            <div v-if="horodatage" class="text-sm italic font-light text-slate-400 flex justify-center w-auto">
                                <p>Dernier import effectué le {{ horodatage }}</p>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
            <ModalConfirmImport v-if="confirModal" @closeConfirm="this.confirModal = false" @validated="this.validated()"></ModalConfirmImport>
        </div>
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
            file: null,
            show: false,
            confirModal: false,
            label: 'Import du fichier en cours...',
            loadClass: 'load-class'
        }
    },
    computed: {
        horodatage: {
            get () {
                return this.$page.props.hub.horodatage
            },
            set (value) {
                this.$page.props.hub.horodatage = value
            }
        }
    },
    methods: {
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
                .then(response => {
                    this.show = false
                    this.horodatage = response.data
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
