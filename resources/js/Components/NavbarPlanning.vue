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
                  <div class="form-check">
                    <input @change="updateFavori()"
                           :checked="this.$page.props.auth.user.collaborateur_id === this.selected.id"
                           class="form-check-input appearance-none h-4 w-4 border border-gray-300 rounded-sm bg-white checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200 mt-1 align-top bg-no-repeat bg-center bg-contain float-right ml-2 cursor-pointer"
                           type="checkbox">
                    <label class="form-check-label inline-block text-white flex justify-center">
                      <button type="button" class="text-white inline-block cursor-default" style="margin-right: 6px; display: grid;"
                              data-bs-toggle="tooltip" data-bs-placement="top" title="Si le fichier Excel est importé, il faudra une nouvelle fois indiquer votre planning.">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                      </button>
                      C'est mon planning
                    </label>
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
        },
      updateFavori () {
          axios.post('update/favori', {
            user: this.$page.props.auth.user.id,
            collaborateur: this.selected.id
          })
        .then(() => {
          this.$page.props.auth.user.collaborateur_id = this.selected.id
          this.$emit('notificationUpdate')
        })
        .catch(error => {
          console.log(error)
        })
      }
    }
}
</script>

<style scoped>

</style>
