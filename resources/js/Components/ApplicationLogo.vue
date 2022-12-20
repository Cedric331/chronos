<template>
    <img :src="source" :style="'width:' + heightLogo + '; margin-top:' + heightLogo + ';margin-top: 0px'" alt="logo du site">
</template>

<script>

export default {
    props: {
        source: String,
        widthLogo: String,
        heightLogo: String
    }
}
</script>
