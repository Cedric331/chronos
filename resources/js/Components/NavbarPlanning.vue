<template>
    <header class="bg-white shadow sticky top-0 z-50">
        <nav class="bg-gray-800">
            <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
                <div class="relative flex items-center justify-between h-16">
                    <div class="flex-1 flex items-center justify-center sm:justify-start hidden sm:block">
                        <div class="flex-shrink-0 flex items-center text-white xl:visible">
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
