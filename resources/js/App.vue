<template>
    <div id="app">
        <router-view v-if="isReady"/>
        <b-loading :is-full-page="true" :active="!isReady"></b-loading>
    </div>
</template>
<script>
    export default {
        name: 'App',
        computed: {
            isReady() {
                return this.$store.state.appInit && this.$store.state.preview
            }
        },
    }
</script>
