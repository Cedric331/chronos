<template>
    <Head title="Modification du mot de passe" />

    <BreezeValidationErrors class="mb-4" />
    <BreezeAuthenticatedLayout>
        <div class="py-12 min-h-screen">
            <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                            Mot de passe modifié avec succès !
                        </div>
                        <div v-if="errors" class="mb-4 font-medium text-sm text-red-600">
                            <div v-for="error in errors">
                                {{ error[0] }}
                            </div>
                        </div>
                <form>
                    <div>
                        <BreezeLabel for="old_password" value="Mot de passe actuel" />
                        <BreezeInput id="old_password" type="password" class="mt-1 block w-full" v-model="password" required autofocus autocomplete="username" />
                    </div>

                    <div class="mt-4">
                        <BreezeLabel for="password" value="Nouveau mot de passe" />
                        <BreezeInput id="password" type="password" class="mt-1 block w-full" v-model="new_password" required autocomplete="new-password" />
                    </div>

                    <div class="mt-4">
                        <BreezeLabel for="password_confirmation" value="Confirmer le nouveau mot de passe" />
                        <BreezeInput id="password_confirmation" type="password" class="mt-1 block w-full" v-model="new_password_confirmation" required autocomplete="new-password" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <BreezeButton @click.prevent="update()">
                            Modifier le mot de passe
                        </BreezeButton>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import BreezeButton from '@/Components/Button.vue'
import BreezeInput from '@/Components/Input.vue'
import BreezeLabel from '@/Components/Label.vue'
import BreezeValidationErrors from '@/Components/ValidationErrors.vue'
import { Head } from '@inertiajs/inertia-vue3';

export default {
    name: "UpdatePassword",
    components: {
        BreezeAuthenticatedLayout,
        BreezeButton,
        BreezeInput,
        BreezeLabel,
        BreezeValidationErrors,
        Head,
    },
    data () {
        return {
            errors: [],
            status: null,
            password: null,
            new_password: null,
            new_password_confirmation: null
        }
    },
    methods: {
        update () {
            this.errors = []
            axios.patch('/update-user', {
                password: this.password,
                new_password: this.new_password,
                new_password_confirmation: this.new_password_confirmation,
            })
            .then(() => {
                this.status = true
            })
            .catch(error => {
                this.errors = error.response.data.errors
            })
        }
    }
}
</script>

<style scoped>

</style>
