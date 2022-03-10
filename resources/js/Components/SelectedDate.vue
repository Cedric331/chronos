<template>
    <div class="fixed bottom-3 right-6">
        <button class="relative bg-blue-500 text-white p-3 rounded-lg text-sm uppercase font-semibold tracking-tight overflow-visible">
            Modifier horaires
            <div class="absolute -top-3 -right-3 px-2.5 py-0.5 bg-green-500 rounded-full text-xs">{{ selected }}</div>
        </button>
    </div>
</template>

<script>
export default {
    name: "SelectedDate",
    props: {
        selected: Number
    }
}
</script>

<style scoped>

</style>
