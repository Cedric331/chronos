<template>
    <div class="bg-gray-900 relative">
        <div class="h-full w-full flex items-center justify-center">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <transition name="fade">

                <div class="fixed inset-0 z-30">

                    <div class="bg-filter bg-white opacity-25 fixed inset-0 w-full h-full z-20">
                    </div>
                    <main class="flex flex-col items-center justify-center h-full w-full">
                        <transition name="fade-up-down">
                            <div class="modal-wrapper inline-block flex items-center z-30">
                                <div class="modal max-w-md mx-auto xl:max-w-5xl lg:max-w-5xl md:max-w-2xl bg-white max-h-screen shadow-lg flex-row rounded relative">

                                    <div class="modal-header p-5 bg-gray-900 text-gray-900 rounded-t">
                                        <h1 class="text-white text-2xl uppercase">Mise à jour de Chronos</h1>
                                    </div>
                                    <div class="modal-body p-5 w-full h-full overflow-y-auto ">
                                        <h2 class="text-lg">
                                            <b>1 - Ajout téléchargement du planning (format excel)</b>
                                        </h2>
                                        <p class="text-justify">
                                            Vous pouvez désormais télécharger le planning au format Excel (mode simplifié).
                                        </p>
                                        <h2 class="text-lg">
                                            <b>2 - Affichage du planning sur la semaine</b>
                                        </h2>
                                        <p class="text-justify">
                                            Visualisation du planning sur la semaine au lieu de ne le voir que sur un jour. Affichage non modifié sur le mobile.
                                        </p>
                                        <h2 class="text-lg">
                                            <b>3 - Possibilité de voir le planning antérieur</b>
                                        </h2>
                                        <p class="text-justify">
                                            Sous réserve que celui-ci soit disponible, il est désormais possible de voir l'ensemble du planning sans filtre de date.
                                            Cela n'est possible que lors de la génération du planning via le système interne de Chronos.
                                        </p>
                                    </div>
                                    <div class="modal-footer py-3 px-5 border0-t text-right">
                                        <button class="bg-blue-500 px-5 py-2 text-white" @click="closeModal()">Fermer</button>
                                    </div>
                                </div>
                            </div>
                        </transition>
                    </main>
                </div>
            </transition>
      </div>
    </div>
</template>

<script>
export default {
    name: "CheckUpdate",
    methods: {
        closeModal () {
            axios.post('update/check')
            .then(() => {
                this.$emit('closeModal')
            })
            .catch(error => {
                console.log(error)
            })
        }
    }
}
</script>

<style scoped>

.modal-body{
    max-height: 500px;
}
.bg-gray-800-opacity{
    background-color: #2D374850;
}
@media screen and (max-width: 768px){
    .modal-body {
        max-height: 400px;
    }
}

.fade-up-down-enter-active {
    transition: all 0.3s ease;
}
.fade-up-down-leave-active {
    transition: all 0.3s ease;
}
.fade-up-down-enter {
    transform: translateY(10%);
    opacity: 0;
}
.fade-up-down-leave-to {
    transform: translateY(10%);
    opacity: 0;
}

.fade-enter-active{
    -webkit-transition: opacity 2s;
    transition: opacity .3s;

}
.fade-leave-active {
    transition: opacity .3s;
}

.fade-enter,
.fade-leave-to{
    opacity: 0;
}

</style>
