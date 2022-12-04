<template>

    <div class="fixed fade z-10 inset-0 w-full h-full outline-none overflow-x-hidden overflow-y-auto" aria-labelledby="planning par date" role="dialog" aria-modal="true">

        <div class="flex items-end justify-center pt-4 px-4 pb-20 text-center sm:block sm:p-0 w-10/12 mb-6 mx-auto">
            <div @click="closeModal()" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left shadow-xl transform transition-all sm:my-8 sm:align-middle sm:w-auto">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <section class="container p-6 mx-auto bg-white">

                            <div class="flex items-center justify-center">
                                <div class="grid gap-8 grid-cols-1">
                                        <div class="w-full mx-auto">
                                            <header class="px-5 py-4 border-b border-gray-100">
                                                <h2 class="font-bold text-2xl text-center">
                                                    Planning sur la semaine du {{showWeeks[0]}} au {{showWeeks[1]}}
                                                </h2>
                                            </header>
                                            <div>
                                                <div class="overflow-x-auto">
                                                    <table class="table-auto w-full">
                                                        <colgroup>
                                                            <col>
                                                            <col>
                                                            <col :class="this.today === 'Lundi' ? 'border-4 border-red-600 rounded-lg' : null">
                                                            <col :class="this.today === 'Mardi' ? 'border-4 border-red-600 rounded-lg' : null">
                                                            <col :class="this.today === 'Mercredi' ? 'border-4 border-red-600 rounded-lg' : null">
                                                            <col :class="this.today === 'Jeudi' ? 'border-4 border-red-600 rounded-lg' : null">
                                                            <col :class="this.today === 'Vendredi' ? 'border-4 border-red-600 rounded-lg' : null">
                                                            <col :class="this.today === 'Samedi' ? 'border-4 border-red-600 rounded-lg' : null">
                                                            <col :class="this.today === 'Dimanche' ? 'border-4 border-red-600 rounded-lg' : null">
                                                        </colgroup>
                                                        <thead class="text-xs font-semibold uppercase text-gray-400 bg-gray-50">
                                                        <tr>
                                                            <th class="p-2 whitespace-nowrap">
                                                                <div class="font-bold text-center">Conseiller</div>
                                                            </th>
                                                            <th class="p-2 whitespace-nowrap">
                                                                <div class="font-bold text-center">Heures</div>
                                                            </th>
                                                            <th :style="this.today === 'Lundi' ? 'bg-color-blue' : null" class="p-2 whitespace-nowrap">
                                                                <div class="font-bold text-center">Lundi</div>
                                                            </th>
                                                            <th class="p-2 whitespace-nowrap">
                                                                <div class="font-bold text-center">Mardi</div>
                                                            </th>
                                                            <th class="p-2 whitespace-nowrap">
                                                                <div class="font-bold text-center">Mercredi</div>
                                                            </th>
                                                            <th class="p-2 whitespace-nowrap">
                                                                <div class="font-bold text-center">Jeudi</div>
                                                            </th>
                                                            <th class="p-2 whitespace-nowrap">
                                                                <div class="font-bold text-center">Vendredi</div>
                                                            </th>
                                                            <th class="p-2 whitespace-nowrap">
                                                                <div class="font-bold text-center">Samedi</div>
                                                            </th>
                                                            <th class="p-2 whitespace-nowrap">
                                                                <div class="font-bold text-center">Dimanche</div>
                                                            </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody class="text-sm divide-y divide-gray-100">
                                                        <tr v-for="(element, index) in showDates" :key="index">
                                                            <td class="p-2 whitespace-nowrap border-2 border-black">
                                                                <div class="flex items-center">
                                                                    <div class="font-bold">{{index}}</div>
                                                                </div>
                                                            </td>
                                                            <td v-if="element.time === '35h00'" class="p-2 whitespace-nowrap border-2 border-black">
                                                                <div class="flex items-center">
                                                                    <div class="font-bold">{{element.time}}</div>
                                                                </div>
                                                            </td>
                                                            <td v-else class="p-2 whitespace-nowrap border-2 border-black">
                                                                <div class="flex items-center">
                                                                    <div class="text-red-600 font-bold">{{element.time}}</div>
                                                                </div>
                                                            </td>
                                                            <td v-for="i in numbers" :style="colorPlanning(element[i])" class="border-2 border-black p-3 m-1 whitespace-nowrap">

                                                                <div v-if="element[i].horaires">
                                                                    <p v-if="element[i].type !== 'Iti'" class="font-bold text-justify line-clamp-3" :style="texte">
                                                                        {{ element[i].horaires.teletravail ? 'Télétravail' : 'Hub'}} {{ element[i].horaires.rotation ? ' - ' + element[i].horaires.rotation : ''}}
                                                                    </p>
                                                                    <p v-else class="font-bold text-justify line-clamp-3" :style="texte">
                                                                        Technicien {{ element[i].horaires.rotation ? ' - ' + element[i].horaires.rotation : ''}}
                                                                    </p>
                                                                    <p class="font-semibold text-justify line-clamp-3" :style="texte">
                                                                        Début :  <span class="font-bold">{{ element[i].horaires.debut_journee }}</span>
                                                                    </p>
                                                                    <div v-if="element[i].horaires.debut_pause">
                                                                        <p class="font-semibold text-justify line-clamp-3" :style="texte">
                                                                            Pause Déj:  <span class="font-bold">{{ element[i].horaires.debut_pause }}</span>
                                                                        </p>
                                                                        <p class="font-semibold text-justify line-clamp-3" :style="texte">
                                                                            Fin Déj :  <span class="font-bold">{{ element[i].horaires.fin_pause }}</span>
                                                                        </p>
                                                                    </div>
                                                                    <p class="font-semibold text-justify line-clamp-3" :style="texte">
                                                                        Fin : <span class="font-bold">{{ element[i].horaires.fin_journee }}</span>
                                                                    </p>
                                                                </div>
                                                                <div v-else>
                                                                    <p class="font-bold text-justify line-clamp-3" :style="texte">
                                                                        {{ getType(element[i].type) }}
                                                                    </p>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button @click="closeModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Fermer</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ModalPlanningWeek",
    props: {
        showDates: Object,
        today: String,
        showWeeks: Array
    },
    data () {
        return {
            numbers: [ 0, 1, 2, 3, 4, 5, 6],
            texte: this.colorTexte()
        }
    },
    methods: {
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
                default:
                    return 'Repos'
            }
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
        },
        closeModal () {
            this.$emit('closeModal')
        },
    }
}
</script>

<style scoped>

</style>
