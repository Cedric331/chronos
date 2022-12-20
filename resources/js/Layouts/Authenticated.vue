<template>
    <notifications position="bottom right" />
        <div class="min-h-screen h-full">
            <nav class="bg-white border-b border-gray-400">
                <!-- Primary Navigation Menu -->
                <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <Link :href="route('planning')">
                                <BreezeApplicationLogo class="block w-auto"
                                                       :source="'/images/logo.png'"
                                                       :widthLogo="'80px'"
                                                       :heightLogo="'50px'"
                                />
                            </Link>
                        </div>
                            <!-- Navigation Links -->
                            <div class="flex hidden sm:flex sm:items-center sm:ml-6">
                                <div class="relative">
                                    <BreezeDropdown align="left" width="48">
                                        <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button" class="hover:bg-black hover:text-white font-bold rounded-full inline-flex items-center p-2 border border-transparent leading-4 bg-white focus:outline-none transition ease-in-out duration-150">
                                                Hub
                                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                        </template>

                                        <template #content>
                                            <BreezeDropdownLink class="p-2 hover:bg-black hover:text-white font-bold rounded-full" style="z-index: 9999" v-if="$page.props.auth.user.coordinateur" :href="route('gestion.hub')" method="get" as="button">
                                                Gestion du Hub
                                            </BreezeDropdownLink>
                                            <BreezeDropdownLink class="p-2 hover:bg-black hover:text-white font-bold rounded-full" style="z-index: 9999" v-if="$page.props.auth.user.coordinateur" :href="route('dashboard')" method="get" as="button">
                                                Import fichier
                                            </BreezeDropdownLink>
                                            <BreezeDropdownLink class="p-2 hover:bg-black hover:text-white font-bold rounded-full" style="z-index: 9999" :href="route('equipe.information')" method="get" as="button">
                                                Information Équipe
                                            </BreezeDropdownLink>
                                            <BreezeDropdownLink class="p-2 hover:bg-black hover:text-white font-bold rounded-full" style="z-index: 9999" :href="route('lien')" method="get" as="button">
                                                Partage de Lien
                                            </BreezeDropdownLink>
                                        </template>
                                    </BreezeDropdown>
                                </div>

                            <div class="hidden space-x-8 sm:-my-px sm:flex">
                                <BreezeNavLink class="p-2 hover:bg-black hover:text-white font-bold rounded-full" v-if="$page.props.auth.user.coordinateur" :href="route('rotation')" :active="route().current('rotation')" as="button">
                                    Gestion des rotations
                                </BreezeNavLink>
                                <BreezeNavLink class="p-2 hover:bg-black hover:text-white font-bold rounded-full" :href="route('planning')" :active="route().current('planning')" as="button">
                                    Planning
                                </BreezeNavLink>
                                <BreezeNavLink @click="generatePlanning()" class="p-2 hover:bg-black hover:text-white font-bold rounded-full" as="button">
                                    Télécharger le Planning
                                </BreezeNavLink>
                                <BreezeNavLink class="p-2 hover:bg-black hover:text-white font-bold rounded-full" v-if="$page.props.auth.user.responsable" :href="route('administration')" :active="route().current('administration')" as="button">
                                    Administration
                                </BreezeNavLink>
                            </div>
                        </div>

                        <div class="flex">
                            <div v-if="this.$page.props.auth.user.coordinateur" class="flex items-center sm:ml-6">
                                <div v-if="this.window.width > 1200">
                                    <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                                        <input v-model="isAuthorize" @click="updateAuthorizeHub(!isAuthorize)" type="checkbox" name="toggle" id="toggle" class="toggle-checkbox absolute block w-4 h-4 rounded-full bg-white border-4 appearance-none cursor-pointer" />
                                        <label for="toggle" class="toggle-label block overflow-hidden h-4 rounded-full bg-gray-300 cursor-pointer"></label>
                                    </div>
                                    <label for="toggle" class="text-xs font-bold">Autoriser la modification des horaires</label>
                                </div>
                                <div v-if="this.window.width < 1200">
                                    <select v-model="selected" class="ml-2 font-bold rounded-full w-full text-sm leading-4 font-medium rounded-md rounded transition ease-in-out m-0">
                                        <option v-for="hub in this.$page.props.hubs" :key="hub.id" :value="hub.ville">
                                            {{ hub.ville }}
                                        </option>
                                    </select>
                                </div>
                                <div v-else>
                                    <label for="hub" class="font-bold rounded-full w-full text-sm leading-4 mx-5">Hub :</label>
                                    <input v-model="selected" list="hub" id="ice-cream-choice" name="hub">
                                    <datalist id="hub" style="height:5.1em;overflow:hidden">
                                        <option class="font-bold text-sm" v-for="hub in this.$page.props.hubs" :key="hub.id" :value="hub.ville">{{ hub.ville }} </option>
                                    </datalist>
                                </div>
                            </div>
                            <div v-else class="flex items-center sm:ml-6">
                                <p class="block w-full text-sm font-medium rounded-md text-gray-500 bg-white m-0">
                                    {{ selected }}
                                </p>
                            </div>

                            <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <div class="ml-3 relative">
                                <BreezeDropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button" class="hover:bg-black hover:text-white font-bold rounded-full inline-flex items-center px-3 py-2 border border-transparent leading-4 font-medium rounded-md bg-white focus:outline-none transition ease-in-out duration-150">
                                                {{ $page.props.auth.user.name }}

                                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <BreezeDropdownLink class="p-2 hover:bg-black hover:text-white font-bold rounded-full" style="z-index: 9999" :href="route('parametre')" method="get" as="button">
                                            Modification des couleurs
                                        </BreezeDropdownLink>
                                        <BreezeDropdownLink class="p-2 hover:bg-black hover:text-white font-bold rounded-full" style="z-index: 9999" :href="route('user.update')" method="get" as="button">
                                            Modifier mon mot de passe
                                        </BreezeDropdownLink>
                                        <BreezeDropdownLink class="p-2 hover:bg-black hover:text-white font-bold rounded-full" style="z-index: 9999" :href="route('logout')" method="post" as="button">
                                            Déconnexion
                                        </BreezeDropdownLink>
                                    </template>
                                </BreezeDropdown>
                            </div>
                        </div>
                    </div>

                        <!-- Hamburger -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <button @click="showingNavigationDropdown = ! showingNavigationDropdown" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{'hidden': showingNavigationDropdown, 'inline-flex': ! showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{'hidden': ! showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}" class="sm:hidden">

                    <div class="pt-2 pb-3 space-y-1">
                        <BreezeResponsiveNavLink :href="route('equipe.information')" :active="route().current('equipe.information')" method="get" as="button">
                            Information Équipe
                        </BreezeResponsiveNavLink>
                        <BreezeResponsiveNavLink :href="route('lien')" :active="route().current('lien')" method="get" as="button">
                            Partage de Lien
                        </BreezeResponsiveNavLink>
                        <BreezeResponsiveNavLink :href="route('planning')" :active="route().current('planning')" as="button">
                            Planning
                        </BreezeResponsiveNavLink>
                        <BreezeResponsiveNavLink v-if="$page.props.auth.user.responsable" :href="route('administration')" :active="route().current('administration')" as="button">
                            Administration
                        </BreezeResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800">{{ $page.props.auth.user.name }}</div>
                            <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <BreezeResponsiveNavLink :href="route('user.update')" as="button">
                                Modifier mon mot de passe
                            </BreezeResponsiveNavLink>
                            <BreezeResponsiveNavLink :href="route('logout')" method="post" as="button">
                                Déconnexion
                            </BreezeResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main id="main" :style="'background-image: url(/images/background'+ this.$page.props.season +'.jpg);'">
                <slot />
                <div @click="this.showModal = true" id="bug" class="w-72 cursor-pointer text-center flex justify-end fixed bottom-16 bg-red-600 rounded-full">
                    <p class="text-white font-bold my-auto">Contacter administrateur</p>
                    <div class="float-right relative text-white p-3 rounded-lg text-sm uppercase font-semibold tracking-tight overflow-visible">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 12.75c1.148 0 2.278.08 3.383.237 1.037.146 1.866.966 1.866 2.013 0 3.728-2.35 6.75-5.25 6.75S6.75 18.728 6.75 15c0-1.046.83-1.867 1.866-2.013A24.204 24.204 0 0112 12.75zm0 0c2.883 0 5.647.508 8.207 1.44a23.91 23.91 0 01-1.152 6.06M12 12.75c-2.883 0-5.647.508-8.208 1.44.125 2.104.52 4.136 1.153 6.06M12 12.75a2.25 2.25 0 002.248-2.354M12 12.75a2.25 2.25 0 01-2.248-2.354M12 8.25c.995 0 1.971-.08 2.922-.236.403-.066.74-.358.795-.762a3.778 3.778 0 00-.399-2.25M12 8.25c-.995 0-1.97-.08-2.922-.236-.402-.066-.74-.358-.795-.762a3.734 3.734 0 01.4-2.253M12 8.25a2.25 2.25 0 00-2.248 2.146M12 8.25a2.25 2.25 0 012.248 2.146M8.683 5a6.032 6.032 0 01-1.155-1.002c.07-.63.27-1.222.574-1.747m.581 2.749A3.75 3.75 0 0115.318 5m0 0c.427-.283.815-.62 1.155-.999a4.471 4.471 0 00-.575-1.752M4.921 6a24.048 24.048 0 00-.392 3.314c1.668.546 3.416.914 5.223 1.082M19.08 6c.205 1.08.337 2.187.392 3.314a23.882 23.882 0 01-5.223 1.082" />
                        </svg>
                    </div>
                </div>
            </main>
            <ModalAssistance
                v-show="showModal"
                @closeModal="data => this.closeModal(data)">
            </ModalAssistance>
        </div>
</template>

<script>
import BreezeApplicationLogo from '@/Components/ApplicationLogo.vue'
import BreezeDropdown from '@/Components/Dropdown.vue'
import BreezeDropdownLink from '@/Components/DropdownLink.vue'
import BreezeNavLink from '@/Components/NavLink.vue'
import BreezeResponsiveNavLink from '@/Components/ResponsiveNavLink.vue'
import ModalAssistance from "@/Components/ModalAssistance.vue";
import { Link } from '@inertiajs/inertia-vue3';

export default {
    components: {
        BreezeApplicationLogo,
        BreezeDropdown,
        ModalAssistance,
        BreezeDropdownLink,
        BreezeNavLink,
        BreezeResponsiveNavLink,
        Link,
    },

    data() {
        return {
            showingNavigationDropdown: false,
            showModal: false,
            window: {
                width: 0
            },
            isAuthorize: this.$page.props.hub.droit_update === 1,
            selected: this.$page.props.hub.ville
        }
    },
    watch: {
        selected: function () {
          this.$page.props.hubs.forEach(item => {
                  if (item.ville === this.selected) {
                      this.updateHub(item.id)
                  }
              })
      }
    },
    created() {
        window.addEventListener('resize', this.handleResize)
        this.handleResize()
    },
    destroyed() {
        window.removeEventListener('resize', this.handleResize)
    },
    methods: {
        closeModal (data) {
            this.showModal = false
            if (data) {
                this.$notify({
                    title: "Succès",
                    text: "Message envoyé avec succès !",
                    type: 'success',
                });
            }
        },
        handleResize () {
            this.window.width = window.innerWidth
        },
        generatePlanning () {
            axios.get('/planning/generate')
                .then(response => {
                    const linkSource = 'data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,'+ response.data ;
                    const downloadLink = document.createElement("a");
                    const fileName = 'planning.xlsx';

                    downloadLink.href = linkSource;
                    downloadLink.download = fileName;
                    downloadLink.click();
                    this.$notify({
                        title: "Succès",
                        text: "Génération du planning effectué avec succès !",
                        type: 'success',
                    });
                })
                .catch(error => {
                    console.log(error)
                    this.$notify({
                        title: "Erreur",
                        text: "Oups désolé il y a une erreur !",
                        type: 'info',
                    });
                })
        },
        updateAuthorizeHub (data) {
            axios.patch('/hub/' + this.$page.props.hub.id + '/authorize', {
                isAuthorize: data
            })
                .then(() => {
                    this.$notify({
                        title: "Succès",
                        text: "Modification effectuée avec succès !",
                        type: 'success',
                    });
                })
                .catch(error => {
                    console.log(error)
                    this.$notify({
                        title: "Erreur",
                        text: "Oups désolé il y a une erreur !",
                        type: 'info',
                    });
                })
        },
        updateHub (id) {
            axios.patch('/hub/' + id + '/user')
            .then(() => {
                this.$inertia.visit(this.$page.url)
            })
            .catch(error => {
                console.log(error)
                this.$notify({
                    title: "Erreur",
                    text: "Oups désolé il y a une erreur !",
                    type: 'info',
                });
            })
        }
    }
}
</script>

<style>
#main {
    background-attachment: fixed;
    background-size: cover;
}
#bug {
    transition-duration: 0.5s;
    left: -250px;
}
#bug:hover {
    transition-duration: 0.5s;
    left: -40px;
}
.toggle-checkbox:checked {
    @apply: right-0 border-green-400;
    right: 0;
    border-color: #1a202c;
}
.toggle-checkbox:checked + .toggle-label {
    @apply: bg-green-400;
    background-color: #1a202c;
}
</style>
