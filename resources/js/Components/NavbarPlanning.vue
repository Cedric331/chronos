<template>
    <header class="bg-white shadow sticky top-0 z-50">
        <nav class="bg-gray-800">
            <div class="w-full mx-auto px-2 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div v-if="isFrame" class="inset-y-0 flex items-center pr-2 text-white">
                        <p>Planning de {{ collaborateur.name }}</p>
                    </div>
                    <div>
                        <div v-if="isFrame">
                            <iframe src="https://rcm-eu.amazon-adsystem.com/e/cm?o=8&p=13&l=ur1&category=last_minute_deals&banner=17YD76J7T1TQ0WKR1WR2&f=ifr&linkID=7144b5daa8d09c904e61c82fd1bb825b&t=premium0a-21&tracking_id=premium0a-21" width="468" height="60" scrolling="no" border="0" marginwidth="0" style="border:none;" frameborder="0" sandbox="allow-scripts allow-same-origin allow-popups allow-top-navigation-by-user-activation"></iframe>
                        </div>
                        <div v-else>
                            <iframe src="https://rcm-eu.amazon-adsystem.com/e/cm?o=8&p=20&l=ur1&category=amazongeneric&banner=0X5CF34MDV21N0SHARR2&f=ifr&linkID=d598636dc7ada7bf8060b437e6cae712&t=premium0a-21&tracking_id=premium0a-21" width="120" height="40" scrolling="no" border="0" marginwidth="0" style="border:none;" frameborder="0" sandbox="allow-scripts allow-same-origin allow-popups allow-top-navigation-by-user-activation"></iframe>
                        </div>
                    </div>
                    <div class="inset-y-0 right-0 flex items-center pr-2">
                        <div v-if="isFrame" class="form-check mr-4">
                            <input @change="updateFavori()"
                                   :checked="this.$page.props.auth.user.collaborateur_id === this.selected.id"
                                   class="form-check-input appearance-none h-4 w-4 border border-gray-300 rounded-sm bg-white checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200 mt-1 align-top bg-no-repeat bg-center bg-contain float-right ml-2 cursor-pointer"
                                   type="checkbox">
                            <label class="form-check-label inline-block text-white flex justify-center">
                                Mon planning
                            </label>
                        </div>
                        <div class="flex justify-center">
                            <div class="w-auto">
                                <select v-model="selected" @change="updateCollaborateur()" class=" block w-full text-base font-normal text-gray-700 bg-white bg-clip-padding bg-no-repeat border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600" aria-label="Voir le planning">
                                    <option v-for="member in collaborateurs" :key="member.id" :value="member">
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
            selected: null,
            isFrame : true
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
        handleResize () {
            this.isFrame = window.innerWidth > 1200 ? true : false
        },
        updateCollaborateur() {
            this.$emit('updateCollaborateur', this.selected)
        },
      updateFavori () {
          axios.post('update/favori', {
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
    },
    beforeMount() {
        this.collaborateurs.forEach(item => {
            if (item.id === this.collaborateur.id) {
                this.selected = item
            }
        })
    }
}
</script>

<style scoped>

</style>
