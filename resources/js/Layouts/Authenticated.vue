<template>
    <notifications position="bottom right" />
    <div>
        <div class="min-h-screen bg-white">
            <nav class="bg-white border-b border-gray-400">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('planning')">
                                    <BreezeApplicationLogo class="block h-9 w-auto" />
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden sm:flex sm:items-center sm:ml-6">
                                <div class="relative">
                                    <BreezeDropdown align="left" width="48">
                                        <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                                Gestion du Hub

                                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                        </template>

                                        <template #content>
                                            <BreezeDropdownLink style="z-index: 9999" v-if="$page.props.auth.user.coordinateur" :href="route('dashboard')" method="get" as="button">
                                                Import fichier
                                            </BreezeDropdownLink>
                                            <BreezeDropdownLink style="z-index: 9999" v-if="$page.props.auth.user.coordinateur" :href="route('equipe')" method="get" as="button">
                                                Gestion Équipe
                                            </BreezeDropdownLink>
                                            <BreezeDropdownLink style="z-index: 9999" :href="route('equipe.information')" method="get" as="button">
                                                Information Équipe
                                            </BreezeDropdownLink>
                                            <BreezeDropdownLink style="z-index: 9999" :href="route('lien')" method="get" as="button">
                                                Partage de Lien
                                            </BreezeDropdownLink>
                                        </template>
                                    </BreezeDropdown>
                                </div>
                            </div>

                            <div class="hidden space-x-8 sm:-my-px sm:flex">
                                <BreezeNavLink :href="route('planning')" :active="route().current('planning')" as="button">
                                    Planning
                                </BreezeNavLink>
                                <BreezeNavLink v-if="$page.props.auth.user.admin" :href="route('administration')" :active="route().current('administration')" as="button">
                                    Administration
                                </BreezeNavLink>
                            </div>
                        </div>

                        <div class="flex">
                            <div v-if="this.$page.props.auth.user.coordinateur" class="flex items-center sm:ml-6">
                                <div class="ml-3 relative">
                                    <select v-model="selected" class="block w-full overflow-y-auto text-sm leading-4 font-medium rounded-md rounded transition ease-in-out m-0" style="border-width: 0">
                                        <option v-for="hub in this.$page.props.hubs" :key="hub.id" :value="hub.ville">
                                            {{ hub.ville }}
                                        </option>
                                    </select>
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
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                                {{ $page.props.auth.user.name }}

                                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <BreezeDropdownLink style="z-index: 9999" :href="route('parametre')" method="get" as="button">
                                            Paramètres
                                        </BreezeDropdownLink>
                                        <BreezeDropdownLink style="z-index: 9999" :href="route('user.update')" method="get" as="button">
                                            Modifier mon mot de passe
                                        </BreezeDropdownLink>
                                        <BreezeDropdownLink style="z-index: 9999" :href="route('logout')" method="post" as="button">
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

                <!-- Responsive Navigation Menu -->
                <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}" class="sm:hidden">

                    <div class="pt-2 pb-3 space-y-1">
                        <BreezeResponsiveNavLink v-if="$page.props.auth.user.coordinateur" :href="route('dashboard')" method="get" as="button">
                            Import fichier
                        </BreezeResponsiveNavLink>
                        <BreezeResponsiveNavLink v-if="$page.props.auth.user.coordinateur" :href="route('equipe')" method="get" as="button">
                            Gestion Équipe
                        </BreezeResponsiveNavLink>
                        <BreezeResponsiveNavLink :href="route('equipe.information')" :active="route().current('equipe.information')" method="get" as="button">
                            Information Équipe
                        </BreezeResponsiveNavLink>
                        <BreezeResponsiveNavLink :href="route('lien')" :active="route().current('lien')" method="get" as="button">
                            Partage de Lien
                        </BreezeResponsiveNavLink>
                        <BreezeResponsiveNavLink :href="route('planning')" :active="route().current('planning')" as="button">
                            Planning
                        </BreezeResponsiveNavLink>
                        <BreezeResponsiveNavLink v-if="$page.props.auth.user.admin" :href="route('administration')" :active="route().current('administration')" as="button">
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
                            <BreezeResponsiveNavLink :href="route('parametre')" method="get" as="button">
                                Paramètres
                            </BreezeResponsiveNavLink>
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
            <main>
                <slot ref="ChildComponent" />
            </main>
        </div>
    </div>
</template>

<script>
import BreezeApplicationLogo from '@/Components/ApplicationLogo.vue'
import BreezeDropdown from '@/Components/Dropdown.vue'
import BreezeDropdownLink from '@/Components/DropdownLink.vue'
import BreezeNavLink from '@/Components/NavLink.vue'
import BreezeResponsiveNavLink from '@/Components/ResponsiveNavLink.vue'
import { Link } from '@inertiajs/inertia-vue3';

export default {
    components: {
        BreezeApplicationLogo,
        BreezeDropdown,
        BreezeDropdownLink,
        BreezeNavLink,
        BreezeResponsiveNavLink,
        Link,
    },

    data() {
        return {
            showingNavigationDropdown: false,
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
    methods: {
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
