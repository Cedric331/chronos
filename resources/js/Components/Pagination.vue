<template>
    <div v-if="links.length > 6">
        <div class="flex flex-wrap -mb-1">
            <template v-for="(link, p) in links" :key="p">
                <div v-if="link.url === null" />
                <Link v-else
                              class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:text-black hover:bg-white"
                              :class="[ link.active ? 'text-black bg-white' : 'bg-black text-white']" :href="link.url" v-html="link.label" />
            </template>
        </div>
    </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3'

export default {
    name: "Pagination",
    components: {
        Link
    },
    props: {
        links: Array
    }
}
</script>

