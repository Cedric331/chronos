<template>
    <notifications position="bottom right" />
    <Head title="Planning" />
    <BreezeAuthenticatedLayout>
        <div v-if="allPlannings && members && member">
                <div class="min-h-screen bg-gray-700">
                    <NavbarPlanning style="z-index: 1" :collaborateur="member" :collaborateurs="members" @notificationUpdate="notificationUpdate()" @updateCollaborateur="data => updateCollaborateur(data)"/>
                    <div class="p-4 gap-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-7 select-none">
                        <div v-for="(planning, index) in allPlannings" :key="planning.date" :style="colorPlanning(planning)" :class="[planning.today && !this.isSelected(planning) ? ' shadow-blue-400/80 shadow-2xl bg-white border-solid border-4 border-blue-500' : '', planning.today && this.isSelected(planning) ? ' shadow-blue-400/80 shadow-2xl bg-white': '', this.isSelected(planning) ? ' border-4 border-red-500' : '', this.$page.props.auth.user.coordinateur ? ' cursor-pointer' : '']" class="hover:scale-105 relative w-full rounded-md shadow-md shadow-dark hover:shadow-red-400/80 hover:shadow-2xl hover:bg-white">
                            <div class="absolute top-0 right-0">
                                <div @click="viewDate(planning.date_id, index)" class=" flex justify-end p-1">
                                    <svg class="fill-current" :style="texte" height="16px" version="1.1" viewBox="0 0 24 24" width="16px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><title/>
                                        <g stroke-width="1">
                                            <path d="M8,2 L8,5 L16,5 L16,2 L18,2 L18,5 L19.4361148,5 C20.3276335,5 20.6509198,5.09282561 20.9768457,5.2671327 C21.3027716,5.4414398 21.5585602,5.69722837 21.7328673,6.0231543 C21.9071744,6.34908022 22,6.67236646 22,7.5638852 L22,19.4361148 C22,20.3276335 21.9071744,20.6509198 21.7328673,20.9768457 C21.5585602,21.3027716 21.3027716,21.5585602 20.9768457,21.7328673 C20.6509198,21.9071744 20.3276335,22 19.4361148,22 L4.5638852,22 C3.67236646,22 3.34908022,21.9071744 3.0231543,21.7328673 C2.69722837,21.5585602 2.4414398,21.3027716 2.2671327,20.9768457 C2.10623385,20.675991 2.014763,20.3773855 2.00164263,19.6320228 L2,7.5638852 C2,6.67236646 2.09282561,6.34908022 2.2671327,6.0231543 C2.4414398,5.69722837 2.69722837,5.4414398 3.0231543,5.2671327 C3.324009,5.10623385 3.6226145,5.014763 4.36797723,5.00164263 L6,5 L6,2 L8,2 Z M13,18 L11,18 L11,20 L13,20 L13,18 Z M13.2,12 L10.8,12 L11,16.5 L13,16.5 L13.2,12 Z M19,7 L5,7 C4.44771525,7 4,7.44771525 4,8 L4,8 L4,10 L20,10 L20,8 C20,7.44771525 19.5522847,7 19,7 L19,7 Z" />
                                        </g>
                                    </svg>
                                </div>
                                <div v-if="!planning.horaires.teletravail">
                                    <div v-if="$page.props.auth.user.coordinateur || this.$page.props.hub.droit_update === 1 && planning.horaires && planning.type !== 'Iti'" @click="changeHome(planning, planning.horaires.teletravail, index)" class="flex justify-end p-1">
                                        <svg class="fill-current" :style="texte" height="16px" version="1.1" viewBox="0 0 20 20" width="16px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><title/>
                                            <g stroke-width="1">
                                                <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd" />
                                                <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z" />
                                            </g>
                                        </svg>
                                    </div>
                                </div>
                                <div v-else>
                                    <div v-if="$page.props.auth.user.coordinateur || this.$page.props.hub.droit_update === 1 && planning.horaires && planning.type !== 'Iti'" @click="changeHome(planning, planning.horaires.teletravail, index)" class="flex justify-end p-1">
                                        <svg class="fill-current" :style="texte" height="16px" version="1.1" viewBox="0 0 20 20" width="16px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><title/>
                                            <g stroke-width="1">
                                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                                            </g>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div @click="selectPlanning(planning)" class="p-2">
                                <div>
                                    <div>
                                        <p class="font-bold text-sm py-1 underline" :style="texte">{{ planning.date }}</p>
                                    </div>
                                </div>
                                <div v-if="planning.horaires">
                                    <p v-if="planning.type !== 'Iti'" class="font-bold text-justify line-clamp-3" :style="texte">
                                       {{ planning.horaires.teletravail ? 'Télétravail' : 'Hub'}} {{ planning.horaires.rotation ? ' - ' + planning.horaires.rotation : ''}}
                                    </p>
                                    <p v-else class="font-bold text-justify line-clamp-3" :style="texte">
                                        Technicien {{ planning.horaires.rotation ? ' - ' + planning.horaires.rotation : ''}}
                                    </p>
                                    <p class="font-semibold text-justify line-clamp-3" :style="texte">
                                        Début :  <span class="font-bold">{{ planning.horaires.debut_journee }}</span>
                                    </p>
                                    <div v-if="planning.horaires.debut_pause">
                                        <p class="font-semibold text-justify line-clamp-3" :style="texte">
                                            Pause Déj:  <span class="font-bold">{{ planning.horaires.debut_pause }}</span>
                                        </p>
                                        <p class="font-semibold text-justify line-clamp-3" :style="texte">
                                            Fin Déj :  <span class="font-bold">{{ planning.horaires.fin_pause }}</span>
                                        </p>
                                    </div>
                                    <p class="font-semibold text-justify line-clamp-3" :style="texte">
                                        Fin : <span class="font-bold">{{ planning.horaires.fin_journee }}</span>
                                    </p>
                                </div>
                                <div v-else>
                                    <p class="font-bold text-justify line-clamp-3" :style="texte">
                                     {{ getType(planning.type) }}
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            <SelectedDate @click="updatePlanning = true" v-if="selectedPlanning.length > 0 && $page.props.auth.user.coordinateur || selectedPlanning.length > 0 && this.$page.props.hub.droit_update === 1" :classCss="'fixed bottom-3 right-6 bg-blue-500 rounded-full'" :value="'Modifier horaires'" :selected="selectedPlanning.length"></SelectedDate>

            <div v-if="showAllPlanning" class="fixed bottom-3 left-6 bg-green-600 rounded-full">
                <button @click="showPlanningUpdate()" class="relative text-white p-3 rounded-lg text-sm uppercase font-semibold tracking-tight overflow-visible">
                    Revenir au planning du jour
                </button>
            </div>

            <div v-else class="fixed bottom-3 left-6 bg-green-500 rounded-full">
                <button @click="showPlanningUpdate()" class="relative text-white p-3 rounded-lg text-sm uppercase font-semibold tracking-tight overflow-visible">
                    Voir tout le planning
                </button>
            </div>

        </div>
        <div v-else class="min-h-screen bg-gray-700">
            <h1 class="text-3l text-center font-bold text-white py-12">
                -- Aucun Planning --
            </h1>
        </div>
        <ModalPlanning
            v-if="showPlanning"
            :showDates="showDates"
            :datePlanning="datePlanning"
            @refreshViewDate="(data, index) => this.viewDate(data, index)"
            @closeModal="this.showPlanning = false">
        </ModalPlanning>
        <ModalPlanningWeek
            v-if="showPlanningWeek"
            :showDates="showDates"
            @closeModal="this.showPlanningWeek = false">
        </ModalPlanningWeek>
        <ModalUpdatePlanning v-if="updatePlanning && $page.props.auth.user.coordinateur || this.$page.props.hub.droit_update === 1 && updatePlanning"
                             :collaborateur="member"
                             :selected="this.selectedPlanning"
                             @closeModal="bool => { this.closeUpdate(bool) }">
        </ModalUpdatePlanning>
<!--        <ModalSwitchPlanning v-if="updateSwitch && $page.props.auth.user.coordinateur"-->
<!--                             :collaborateurs="members"-->
<!--                             :collaborateur="member"-->
<!--                             :selected="this.selectedPlanning">-->
<!--        </ModalSwitchPlanning>-->
        <CheckUpdate v-if="!$page.props.auth.user.check_update"
                             @closeModal="this.closeCheck()">
        </CheckUpdate>
    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head } from '@inertiajs/inertia-vue3';
import NavbarPlanning from "@/Components/NavbarPlanning.vue";
import ModalPlanning from "@/Components/ModalPlanning.vue";
import ModalUpdatePlanning from "@/Components/ModalUpdatePlanning.vue";
import SelectedDate from "@/Components/SelectedDate.vue";
import Checkbox from "@/Components/Checkbox";
import CheckUpdate from "@/Components/CheckUpdate";
import ModalSwitchPlanning from "@/Components/ModalSwitchPlanning.vue";
import ModalPlanningWeek from "@/Components/ModalPlanningWeek.vue";

export default {
    name: "Planning",
    components: {
        ModalSwitchPlanning,
        CheckUpdate,
        Checkbox,
        ModalPlanning,
        NavbarPlanning,
        ModalUpdatePlanning,
        SelectedDate,
        ModalPlanningWeek,
        BreezeAuthenticatedLayout,
        Head
    },
    props: {
        collaborateurs: Array,
        collaborateur: Object,
        plannings: Array
    },
    data () {
        return {
            showAllPlanning: false,
            member: this.collaborateur,
            members: this.collaborateurs,
            allPlannings: this.plannings,
            selectedPlanning: [],
            showDates: null,
            datePlanning: null,
            showPlanning: false,
            showPlanningWeek: false,
            updatePlanning: false,
            updateSwitch: false,
            texte: this.colorTexte()
        }
    },
    methods: {
        showPlanningUpdate () {
            this.showAllPlanning = !this.showAllPlanning
            axios.get('/planning', {
                params: {
                    showPlanning: this.showAllPlanning
                }
            })
            .then(response => {
                this.member = response.data.collaborateur
                this.members = response.data.collaborateurs
                this.allPlannings = response.data.plannings
            })
            .catch(err => {
                console.log(err)
            })
        },
        getType (data) {
            switch (data) {
                case 'CP':
                    return 'Congés payés'
                    break;
                case 'FOR':
                    return 'Formation'
                    break;
                case 'RJF':
                    return 'Récup. jour férié'
                    break;
                case 'F':
                    return 'Férié'
                    break;
                case 'AM':
                    return 'Arrêt Maladie'
                    break;
                default:
                   return 'Repos'
            }
        },
        selectPlanning (data) {
            if (this.$page.props.auth.user.coordinateur || this.$page.props.hub.droit_update === 1) {
                const inArray = this.selectedPlanning.filter(function (item) {
                    return item === data
                })
                if (inArray.length === 0) {
                    this.selectedPlanning.push(data)
                } else {
                    this.selectedPlanning = this.selectedPlanning.filter(function (item) {
                        return item !== data
                    })
                }
            }
        },
        isSelected (data) {
            const inArray = this.selectedPlanning.filter(function (item) {
                return item === data
            })
            return inArray.length > 0;
        },
        closeUpdate (bool) {
          if (bool) {
              this.$notify({
                  title: "Succès",
                  text: "Modification effectuée avec succès !",
                  type: 'success',
              });
              this.updateCollaborateur(this.member)
          }
          this.updatePlanning = false
          this.selectedPlanning = []
        },
        notificationUpdate (success = true) {
            if (success) {
                this.$notify({
                    title: "Succès",
                    text: "Modification effectuée avec succès !",
                    type: 'success',
                });
            }  else {
                this.$notify({
                    title: "Erreur",
                    text: "Erreur lors de la modification !",
                    type: 'warn',
                });
            }
        },
        changeHome (data, home, index) {
            axios.patch('planning/update/teletravail', {
                date: data,
                home: home
            })
            .then(() => {
                this.updateCollaborateur(this.member)
                this.notificationUpdate()
            })
            .catch(error => {
                console.log(error)
                this.notificationUpdate(false)
            })
        },
        viewDate(data, index) {
            if (window.innerWidth < 1200) {
                let previous = index === 0 ? null : this.allPlannings[index - 1].date_id
                let next = index === this.allPlannings.length - 1 ? null : this.allPlannings[index + 1].date_id
                axios.get('planning/date', {
                    params: {
                        date: data,
                        previous: previous,
                        next: next,
                        index: index
                    }
                })
                    .then(response => {
                        this.showDates = response.data.planning
                        this.datePlanning = response.data.date
                        this.showPlanning = true
                    })
                    .catch(error => {
                        console.log(error)
                    })
            } else {
                axios.get('planning/week', {
                    params: {
                        date: data
                    }
                })
                    .then(response => {
                        this.showDates = response.data.planning
                        this.showPlanningWeek = true
                    })
                    .catch(error => {
                        console.log(error)
                    })
            }
        },
        updateCollaborateur (data) {
            axios.get('planning', {
                params: {
                    id: data.id,
                    loadData: true
                }
            })
            .then(response => {
                this.member = response.data.collaborateur
                this.members = response.data.collaborateurs
                this.allPlannings = response.data.plannings
            })
            .catch(err => {
                this.$notify({
                    title: "Erreur",
                    text: "Erreur lors de la modification !",
                    type: 'warn',
                });
            })
        },
        closeCheck () {
            this.$page.props.auth.user.check_update = true
        },
        colorTexte () {
            return this.$page.props.auth.user.color_texte ? 'color: ' + this.$page.props.auth.user.color_texte : '#000000'
        },
        colorPlanning (planning) {
            var colors = null

            if (planning.type === 'CP') {
                !this.$page.props.auth.user.color_conge ?  colors = 'background-color: #bfdbfe' : colors = 'background-color: ' + this.$page.props.auth.user.color_conge
            }

            if (planning.type === 'Iti') {
                !this.$page.props.auth.user.color_technicien ? colors = 'background-color: #fecaca' : colors = 'background-color: ' + this.$page.props.auth.user.color_technicien
            }

            if (planning.type !== 'Iti' &&  planning.type !== 'CP' && !planning.today) {
                !this.$page.props.auth.user.color_travaille ? colors = 'background-color: #dcfce7' : colors = 'background-color: ' + this.$page.props.auth.user.color_travaille
            }

            if (!planning.horaires && planning.type !== 'CP') {
                !this.$page.props.auth.user.color_repos ? colors = 'background-color: #a5f3fc' : colors = 'background-color: ' + this.$page.props.auth.user.color_repos
            }

            return colors
        }
    }
}
</script>

<style scoped>

</style>
