<template>
    <Head title="Planning" />
    <BreezeAuthenticatedLayout>
        <div v-if="allPlannings && members && member">
                <div class="min-h-screen">
                    <NavbarPlanning style="z-index: 1" :collaborateur="member" :collaborateurs="members" @updateCollaborateur="data => updateCollaborateur(data)"/>
                    <div class="p-4 gap-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-7 select-none">
                        <div v-for="planning in allPlannings" :key="planning.date" :class="[planning.type === 'CP' ? 'bg-blue-50' : '', planning.type === 'Iti' ? 'bg-red-50' : '',  planning.type !== 'Iti' &&  planning.type !== 'CP' ? 'bg-green-50' : '']" class="w-full cursor-pointer rounded-md shadow-md shadow-dark hover:shadow-blue-400/80 hover:shadow-2xl hover:bg-gray-50">
                            <div class="p-4">
                                <p class="font-semibold text-sm py-2">{{ planning.date }}</p>
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
                                     {{planning.type === 'CP' ? 'Congés payés' : 'Non planifié'}}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        <div v-else>
            <h1 class="text-3l text-center font-bold text-dark mt-8">
                -- Aucun Planning --
            </h1>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head } from '@inertiajs/inertia-vue3';
import NavbarPlanning from "@/Components/NavbarPlanning";

export default {
    name: "Planning",
    components: {
        NavbarPlanning,
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
        }
    },
    methods: {
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
