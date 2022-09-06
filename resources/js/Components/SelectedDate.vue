<template>
    <div :class="classCss">
        <button class="relative text-white p-3 rounded-lg text-sm uppercase font-semibold tracking-tight overflow-visible">
            {{ value }}
            <div class="absolute -top-2 -right-2 px-2.5 py-0.5 bg-red-500 rounded-full text-xs">{{ selected }}</div>
        </button>
    </div>
</template>

<script>
export default {
    name: "SelectedDate",
    props: {
        selected: Number,
        classCss: String,
        value: String
    }
}
</script>

<style scoped>

</style>
