<template>
    <notifications position="bottom right" />
    <Head title="Planning" />
    <BreezeAuthenticatedLayout>
        <div v-if="allPlannings && members && member">
                <div class="min-h-screen">
                    <NavbarPlanning style="z-index: 1" :collaborateur="member" :collaborateurs="members" @notificationUpdate="notificationUpdate()" @updateCollaborateur="data => updateCollaborateur(data)"/>
                    <div class="p-4 gap-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-7 select-none">
                        <div v-for="(planning, index) in allPlannings" :key="planning.date" :class="[!planning.horaires && planning.type !== 'CP' ? 'bg-stone-200' : '', planning.type === 'CP' ? 'bg-blue-200' : '', planning.type === 'Iti' ? 'bg-red-200' : '',  planning.type !== 'Iti' &&  planning.type !== 'CP' && !planning.today ? 'bg-green-100' : '', planning.today ? 'shadow-blue-400/80 shadow-2xl bg-gray-50' : '', isSelected(planning) ? 'border-2 border-green-500' : '', $page.props.auth.user.coordinateur ? 'cursor-pointer' : '']" class="w-full rounded-md shadow-md shadow-dark hover:shadow-blue-400/80 hover:shadow-2xl hover:bg-gray-50">
                            <div @click="viewDate(planning.date_id, index)" class="p-1 flex justify-end">
                                <svg height="16px" version="1.1" viewBox="0 0 24 24" width="16px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><title/><g fill="none" fill-rule="evenodd" id="calendar-error" stroke="none" stroke-width="1">
                                    <path d="M8,2 L8,5 L16,5 L16,2 L18,2 L18,5 L19.4361148,5 C20.3276335,5 20.6509198,5.09282561 20.9768457,5.2671327 C21.3027716,5.4414398 21.5585602,5.69722837 21.7328673,6.0231543 C21.9071744,6.34908022 22,6.67236646 22,7.5638852 L22,19.4361148 C22,20.3276335 21.9071744,20.6509198 21.7328673,20.9768457 C21.5585602,21.3027716 21.3027716,21.5585602 20.9768457,21.7328673 C20.6509198,21.9071744 20.3276335,22 19.4361148,22 L4.5638852,22 C3.67236646,22 3.34908022,21.9071744 3.0231543,21.7328673 C2.69722837,21.5585602 2.4414398,21.3027716 2.2671327,20.9768457 C2.10623385,20.675991 2.014763,20.3773855 2.00164263,19.6320228 L2,7.5638852 C2,6.67236646 2.09282561,6.34908022 2.2671327,6.0231543 C2.4414398,5.69722837 2.69722837,5.4414398 3.0231543,5.2671327 C3.324009,5.10623385 3.6226145,5.014763 4.36797723,5.00164263 L6,5 L6,2 L8,2 Z M13,18 L11,18 L11,20 L13,20 L13,18 Z M13.2,12 L10.8,12 L11,16.5 L13,16.5 L13.2,12 Z M19,7 L5,7 C4.44771525,7 4,7.44771525 4,8 L4,8 L4,10 L20,10 L20,8 C20,7.44771525 19.5522847,7 19,7 L19,7 Z" fill="#000000" fill-rule="nonzero" id="Combined-Shape"/></g>
                                </svg>
                            </div>
                            <div @click="selectPlanning(planning)" class="p-2">
                                <div>
                                    <div>
                                        <p class="font-semibold text-sm py-1">{{ planning.date }}</p>
                                    </div>
                                </div>
                                <div v-if="planning.horaires">
                                    <p class="font-light text-gray-700 text-justify line-clamp-3">
                                        Début : {{ planning.horaires.debut_journee }}
                                    </p>
                                    <div v-if="planning.horaires.debut_pause">
                                        <p class="font-light text-gray-700 text-justify line-clamp-3">
                                            Pause Déj: {{ planning.horaires.debut_pause }}
                                        </p>
                                        <p class="font-light text-gray-700 text-justify line-clamp-3">
                                            Fin Déj : {{ planning.horaires.fin_pause }}
                                        </p>
                                    </div>
                                    <p class="font-light text-gray-700 text-justify line-clamp-3">
                                        Fin : {{ planning.horaires.fin_journee }}
                                    </p>
                                </div>
                                <div v-else>
                                    <p class="font-light text-gray-700 text-justify line-clamp-3">
                                     {{planning.type === 'CP' ? 'Congés payés' : 'Repos'}}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <SelectedDate @click="updatePlanning = true" v-if="selectedPlanning.length > 0 && $page.props.auth.user.coordinateur" :selected="selectedPlanning.length"></SelectedDate>
        </div>
        <div v-else>
            <h1 class="text-3l text-center font-bold text-dark mt-8">
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
        <ModalUpdatePlanning v-if="updatePlanning && $page.props.auth.user.coordinateur" :collaborateur="member" :selected="this.selectedPlanning" @closeModal="bool => { this.closeUpdate(bool) }"></ModalUpdatePlanning>
    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head } from '@inertiajs/inertia-vue3';
import NavbarPlanning from "@/Components/NavbarPlanning.vue";
import ModalPlanning from "@/Components/ModalPlanning.vue";
import ModalUpdatePlanning from "@/Components/ModalUpdatePlanning.vue";
import SelectedDate from "@/Components/SelectedDate.vue";

export default {
    name: "Planning",
    components: {
        ModalPlanning,
        NavbarPlanning,
        ModalUpdatePlanning,
        SelectedDate,
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
            member: this.collaborateur,
            members: this.collaborateurs,
            allPlannings: this.plannings,
            selectedPlanning: [],
            showDates: null,
            datePlanning: null,
            showPlanning: false,
            updatePlanning: false
        }
    },
    methods: {
        selectPlanning (data) {
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
        notificationUpdate () {
            this.$notify({
                title: "Succès",
                text: "Modification effectuée avec succès !",
                type: 'success',
            });
        },
        viewDate(data, index) {
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
        }
    }
}
</script>

<style scoped>

</style>
