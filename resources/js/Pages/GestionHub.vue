<template>
    <notifications position="bottom right" />
    <Head title="Import Chronos" />
    <BreezeAuthenticatedLayout>
    <div class="flex flex-wrap min-h-screen h-full w-10/12 mx-auto">
        <div class="w-full">
            <ul class="flex mb-0 list-none flex-wrap pt-3 pb-4 flex-row">
                <li class="-mb-px mr-2 last:mr-0 flex-auto text-center">
                    <a class="cursor-pointer text-xs font-bold uppercase px-5 py-3 shadow-lg rounded block leading-normal" v-on:click="toggleTabs('GestionEquipe')" v-bind:class="{'bg-white': currentTabComponent !== 'GestionEquipe', 'text-white bg-black': currentTabComponent === 'GestionEquipe'}">
                        Gestion des utilisateurs
                    </a>
                </li>
                <li class="-mb-px mr-2 last:mr-0 flex-auto text-center">
                    <a class="cursor-pointer text-xs font-bold uppercase px-5 py-3 shadow-lg rounded block leading-normal" v-on:click="toggleTabs('GestionCollaborateur')" v-bind:class="{'bg-white': currentTabComponent !== 'GestionCollaborateur', 'text-white bg-black': currentTabComponent === 'GestionCollaborateur'}">
                        Gestion des conseillers
                    </a>
                </li>
                <li class="-mb-px mr-2 last:mr-0 flex-auto text-center">
                    <a class="cursor-pointer text-xs font-bold uppercase px-5 py-3 shadow-lg rounded block leading-normal" v-on:click="toggleTabs('GestionJoursFerie')" v-bind:class="{'bg-white': currentTabComponent !== 'GestionJoursFerie', 'text-white bg-black': currentTabComponent === 'GestionJoursFerie'}">
                        Gestion des jours fériés
                    </a>
                </li>
            </ul>
            <div class="relative flex flex-col min-w-0 break-words w-full shadow-lg rounded">
                <div class="px-4 py-5 flex-auto">
                    <div class="tab-content tab-space">
                        <KeepAlive>
                            <component :collaborateurs="collaborateurs" :users="users" :annees="annees" :is="currentTabComponent"></component>
                        </KeepAlive>
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
import GestionEquipe from "@/Pages/GestionEquipe.vue";
import GestionCollaborateur from "@/Pages/GestionCollaborateur.vue";
import GestionJoursFerie from "@/Pages/GestionJoursFerie.vue";

export default {
    name: "GestionHub",
    inheritAttrs: false,
    components: {
        GestionCollaborateur,
        GestionJoursFerie,
        GestionEquipe,
        BreezeAuthenticatedLayout,
        Head,
    },
    props: {
        collaborateurs: Array,
        annees: Object,
        users: Array
    },
    data() {
        return {
            currentTabComponent: 'GestionEquipe'
        }
    },
    methods: {
        toggleTabs: function(component){
            this.currentTabComponent = component
        }
    }
}
</script>
