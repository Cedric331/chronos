<template>
    <Head title="Planning" />
    <BreezeAuthenticatedLayout>
    <NavbarPlanning :collaborateur="collaborateur" />
        <div class="min-h-screen">
            <div class="p-4 gap-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-7 select-none">
                <div v-for="planning in plannings" :key="planning.date" class="w-full cursor-pointer rounded-md shadow-md shadow-dark bg-gray-50 hover:shadow-blue-400/80 hover:shadow-2xl hover:bg-gray-50">
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
                                Non Planifié
                            </p>
                        </div>
                    </div>
                </div>

            </div>
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
        collaborateur: String,
        plannings: Array
    }
}
</script>

<style scoped>

</style>
