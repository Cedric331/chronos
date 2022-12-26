<template>
    <div id="main" :style="'background-image: url(/images/background-accueil.jpg);'" class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div>
            <Link href="/">
                <BreezeApplicationLogo class="w-25 h-25 fill-current"
                        :source="'/images/logo.png'"
                        :widthLogo="'100px'"
                        :heightLogo="'100px'"
                />
            </Link>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg bg-opacity-90">
            <slot />
        </div>
    </div>
</template>

<script>
import BreezeApplicationLogo from '@/Components/ApplicationLogo.vue'
import { Link } from '@inertiajs/inertia-vue3';

export default {
    components: {
        BreezeApplicationLogo,
        Link,
    }
}
</script>

<style>
#main {
    background-size: cover;
}
</style>
