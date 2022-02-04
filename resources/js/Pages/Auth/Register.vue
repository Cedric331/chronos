<template>
    <Head title="Register" />

    <BreezeValidationErrors class="mb-4" />

    <form @submit.prevent="submit">
        <div class="flex justify-center my-auto">
            <h1 class="justify-center items-center inline-flex text-3xl">Bienvenue</h1>
        </div>
        <div class="mt-4">
            <BreezeLabel for="password" value="Mot de passe" />
            <BreezeInput id="password" type="password" class="mt-1 block w-full" v-model="password" required autocomplete="new-password" />
        </div>

        <div class="mt-4">
            <BreezeLabel for="password_confirmation" value="Confirmer le mot de passe" />
            <BreezeInput id="password_confirmation" type="password" class="mt-1 block w-full" v-model="password_confirmation" required autocomplete="new-password" />
        </div>

        <div class="flex items-center justify-end mt-4">

            <BreezeButton class="ml-4" :disabled="!password && !password_confirmation">
                S'inscrire
            </BreezeButton>
        </div>
    </form>
</template>

<script>
import BreezeButton from '@/Components/Button.vue'
import BreezeGuestLayout from '@/Layouts/Guest.vue'
import BreezeInput from '@/Components/Input.vue'
import BreezeLabel from '@/Components/Label.vue'
import BreezeValidationErrors from '@/Components/ValidationErrors.vue'
import { Head, Link } from '@inertiajs/inertia-vue3';

export default {
    layout: BreezeGuestLayout,

    components: {
        BreezeButton,
        BreezeInput,
        BreezeLabel,
        BreezeValidationErrors,
        Head,
        Link,
    },
    props: {
        name: String,
        email: String,
        hub: String,
        signature: String,
    },
    data() {
        return {
            name: this.name,
            email: this.email,
            password: '',
            password_confirmation: ''
        }
    },

    methods: {
        submit() {
            axios.post(this.signature , {
                name: this.name,
                email: this.email,
                hub: this.hub,
                password: this.password,
                password_confirmation: this.password_confirmation,
            })
            .then(() => {
                this.password = null
                this.password_confirmation = null
            })
            .catch(error => {
                console.log(error)
            })
        }
    }
}
</script>
