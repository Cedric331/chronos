<template>
    <header class="bg-white shadow sticky top-0 z-50">
        <nav class="bg-gray-800">
            <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
                <div class="relative flex items-center justify-between h-16">
                    <div class="flex-1 flex items-center justify-center sm:items-stretch sm:justify-start">
                        <div class="flex-shrink-0 flex items-center text-white">
                            Planning de {{ collaborateur.name }}
                        </div>
                    </div>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
                        <div class="flex justify-center">
                            <div class="xl:w-96">
                                <select v-model="selected" @change="updateCollaborateur()" class=" block w-full text-base font-normal text-gray-700 bg-white bg-clip-padding bg-no-repeat border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600" aria-label="Voir le planning">
                                    <option v-for="member in collaborateurs" :key="member.id" :value="member" :selected="selected.id === member.id">
                                        {{ member.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile menu, show/hide based on menu state. -->
<!--            <div class="sm:hidden" id="mobile-menu">-->
<!--                <div class="px-2 pt-2 pb-3 space-y-1">-->
<!--                    &lt;!&ndash; Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" &ndash;&gt;-->
<!--                    <a href="#" class="bg-gray-900 text-white block px-3 py-2 rounded-md text-base font-medium" aria-current="page">Dashboard</a>-->

<!--                    <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Team</a>-->

<!--                    <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Projects</a>-->

<!--                    <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Calendar</a>-->
<!--                </div>-->
<!--            </div>-->
        </nav>
    </header>
</template>

<script>
export default {
    name: "NavbarPlanning",
    props:  {
        collaborateur: Object,
        collaborateurs: Array,
    },
    data () {
        return {
            selected: this.collaborateur
        }
    },
    methods: {
        updateCollaborateur() {
            this.$emit('updateCollaborateur', this.selected)
        }
    }
}
</script>

<style scoped>

</style>
