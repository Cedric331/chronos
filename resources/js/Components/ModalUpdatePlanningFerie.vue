<template>

    <div class="fixed fade z-10 inset-0 w-full h-full outline-none overflow-x-hidden overflow-y-auto" aria-labelledby="modification planning" role="dialog" aria-modal="true">

        <div class="flex items-end justify-center pt-4 px-4 pb-20 text-center sm:block sm:p-0 sm:w-auto lg:w-6/12 mb-6 mx-auto">
            <div @click="closeModal()" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left shadow-xl transform transition-all sm:my-4 sm:align-middle w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h2 class="font-bold text-center text-xl text-gray-800 mb-5">
                        Gestion du jour Férié "{{ this.selected.name }}"
                    </h2>
                    <div class="sm:flex sm:items-start">
                        <section class="container p-6 mx-auto bg-white">
                            <h2 class="font-bold text-center text-xl text-gray-800 mb-5">
                                Création d'un horaire
                            </h2>
                            <div v-if="errors" class="bg-red-100 rounded-lg py-5 px-6 mb-4 tex t-base text-red-700 mb-3" role="alert">
                                {{ errors }}
                            </div>
                            <div>
                                <div class="rounded-lg w-auto w-4/6">
                                    <div>
                                        <label class="text-gray-800 font-semibold block my-3 text-md">Type</label>
                                        <select v-model="radio" class="block w-full text-sm leading-4 font-medium rounded-md text-gray-500 rounded transition ease-in-out m-0">
                                            <option value="1">
                                                Planifié
                                            </option>
                                            <option value="6">
                                                Férié
                                            </option>
                                        </select>
                                    </div>
                                    <div v-if="radio === '1'">
                                        <div>
                                            <label class="text-gray-800 font-semibold block my-3 text-md">Début journée</label>
                                            <select v-model="debut_journee" class="block w-full text-sm leading-4 font-medium rounded-md text-gray-500 rounded transition ease-in-out m-0">
                                                <option v-for="(horaire, index) in horaires" :key="index" :value="horaire">
                                                    {{ horaire }}
                                                </option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="text-gray-800 font-semibold block my-3 text-md">Début pause</label>
                                            <select v-model="debut_pause" class="block w-full text-sm leading-4 font-medium rounded-md text-gray-500 rounded transition ease-in-out m-0">
                                                <option selected>Pas de pause</option>
                                                <option v-for="(horaire, index) in horaires" :key="index" :value="horaire">
                                                    {{ horaire }}
                                                </option>
                                            </select>
                                        </div>
                                        <div v-if="debut_pause !== null && debut_pause !== 'Pas de pause'">
                                            <label class="text-gray-800 font-semibold block my-3 text-md">Fin pause</label>
                                            <select v-model="fin_pause" class="block w-full text-sm leading-4 font-medium rounded-md text-gray-500 rounded transition ease-in-out m-0">
                                                <option v-for="(horaire, index) in horaires" :key="index" :value="horaire">
                                                    {{ horaire }}
                                                </option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="text-gray-800 font-semibold block my-3 text-md">Fin journée</label>
                                            <select v-model="fin_journee" class="block w-full text-sm leading-4 font-medium rounded-md text-gray-500 rounded transition ease-in-out m-0">
                                                <option v-for="(horaire, index) in horaires" :key="index" :value="horaire">
                                                    {{ horaire }}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="flex justify-start">
                                            <div class="form-check mt-6">
                                                <input v-model="isTech" class="form-check-input appearance-none h-4 w-4 border border-gray-300 rounded-sm bg-white checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200 mt-1 align-top bg-no-repeat bg-center bg-contain float-left mr-2 cursor-pointer" type="checkbox">
                                                <label class="form-check-label inline-block text-gray-800">
                                                    Technicien
                                                </label>
                                            </div>
                                            <div class="form-check mt-6 ml-4">
                                                <input v-model="teletravail" class="form-check-input appearance-none h-4 w-4 border border-gray-300 rounded-sm bg-white checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200 mt-1 align-top bg-no-repeat bg-center bg-contain float-left mr-2 cursor-pointer" type="checkbox">
                                                <label class="form-check-label inline-block text-gray-800">
                                                    Télétravail
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <div>
                            <hr>
                        </div>
                        <section class="container p-6 mx-auto bg-white w-full">
                            <div>
                                <h2 class="font-bold text-center text-xl text-gray-800 mb-5">
                                  Choisir le Conseiller
                                </h2>
                                <div
                                    :class="[this.collaborateur === collaborateur ? 'border-4 border-red-500 border-solid' : '']"
                                    class="list-group-item bg-gray-700 m-1 p-3 rounded-md text-center text-white flex justify-between"
                                    v-for="(collaborateur, index) in collaborateurs"
                                    :key="index">
                                    <div class="w-full" @click="selecteCollaborateur(collaborateur)">
                                        <div class="mr-5">
                                            {{ collaborateur.name }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-between">
                    <div>
                        <button @click="closeModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Annuler</button>
                    </div>
                    <div>
                        <button @click="submit(false)" type="button" class="mt-3 w-full inline-flex justify-center bg-blue-500 rounded-md border shadow-sm px-4 py-2 text-base font-medium text-white sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Valider</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: "ModalUpdatePlanningFerie",
    emits: ["closeModal", "updateData"],
    props: {
        selected: Object,
        collaborateurs: Object
    },
    data () {
        return {
            content: null,
            horaires: [
                '8h00',
                '8h30',
                '9h00',
                '9h30',
                '10h00',
                '10h30',
                '11h00',
                '11h30',
                '12h00',
                '12h30',
                '13h00',
                '13h30',
                '14h00',
                '14h30',
                '15h00',
                '15h30',
                '16h00',
                '16h30',
                '17h00',
                '17h30',
                '18h00',
                '18h30',
                '19h00',
                '19h30',
                '20h00',
                '20h30',
                '21h00'
            ],
            errors: null,
            radio: '1',
            isTech: false,
            debut_journee: null,
            debut_pause: 'Pas de pause',
            fin_pause: null,
            fin_journee: null,
            teletravail: false,
            collaborateur: null
        }
    },
    methods: {
        closeModal (bool = false) {
            this.$emit('closeModal', bool)
        },
        submit (sendMail) {
            if (this.valideData) {
                this.debut_pause === 'Pas de pause' ? this.debut_pause = null : ''
                axios.patch('/planning/update', {
                    sendMail: sendMail,
                    ferie: true,
                    selected: this.selected,
                    user: this.collaborateur,
                    planification: this.radio,
                    isTech: this.isTech,
                    debut_journee: this.debut_journee,
                    debut_pause: this.debut_pause,
                    fin_pause: this.fin_pause,
                    fin_journee: this.fin_journee,
                    teletravail: this.teletravail,
                })
                    .then(res => {
                        this.radio = null
                        this.isTech = false
                        this.debut_journee = null
                        this.debut_pause = 'Pas de pause'
                        this.fin_pause = null
                        this.fin_journee = null
                        this.teletravail = false
                        this.rotation = null
                        this.$emit('updateData', res.data)
                        this.closeModal(true)
                    })
                    .catch(error => {
                        console.log(error)
                        this.$notify({
                            title: "Erreur",
                            text: "Erreur lors de la modification !",
                            type: 'warn',
                        });
                    })
            }
        },
        valideData () {
            this.errors = null
            if (this.radio === null) {
                this.errors = 'Vous n\'avez pas indiqué le type de planification'

                return false
            }
            if (this.radio === '1') {
                if (this.debut_journee === null || this.fin_journee === null) {
                    this.errors = 'Vous devez indiquer les horaires'
                    return false
                }
                if (this.debut_pause !== 'Pas de pause' && this.fin_pause ===  null) {
                    this.errors = 'Vous devez indiquer la fin de la pause'
                    return false
                }
                return true
            } else {
                this.debut_journee = null
                this.debut_pause = null
                this.fin_pause = null
                this.fin_journee = null

                return true
            }
        },
        defineContent (data) {
            let text = 'Semaine de ' + data.hours + '<br><br>'
            data.rotations.forEach(item => {
                const horaire = JSON.parse(item.horaire)
                const debut_journee = horaire.debut_journee ? horaire.debut_journee : 'Non Planifié'
                const debut_pause = horaire.debut_pause ? ' - ' + horaire.debut_pause : ''
                const fin_pause = horaire.fin_pause ? ' - ' + horaire.fin_pause : ''
                const fin_journee = horaire.fin_journee ? ' - ' + horaire.fin_journee : ''
                text += this.capitalizeFirstLetter(item.day) + ' : ' + debut_journee + debut_pause + fin_pause + fin_journee + '<br>'
            })
            this.content = text
        },
        capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        },
        selecteCollaborateur (data) {
            // this.defineContent(data)
            this.collaborateur = data
        }
    }
}
</script>

<style>
:root {
    --popper-theme-background-color: black;
    --popper-theme-background-color-hover: #333333;
    --popper-theme-text-color: #ffffff;
    --popper-theme-border-width: 0px;
    --popper-theme-border-style: solid;
    --popper-theme-border-radius: 6px;
    --popper-theme-padding: 32px;
    --popper-theme-box-shadow: 0 6px 30px -6px rgba(0, 0, 0, 0.25);
}
hr{
    border:         none;
    border-left:    1px solid hsla(200, 10%, 50%,100);
    height:         50vh;
    width:          1px;
}
</style>
