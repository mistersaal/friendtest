<template>
    <b-navbar :mobile-burger="false" shadow>
        <template slot="brand">
            <b-navbar-item @click="$router.back()" v-if="back">
                <b-icon icon="chevron-left"></b-icon>
            </b-navbar-item>
            <b-navbar-item
                    tag="h1"
                    class="title is-4 has-text-weight-bold"
                    :style="{'cursor': 'default', 'padding-left': back ? 0 : ''}"
            >
                <slot></slot>
            </b-navbar-item>
        </template>
    </b-navbar>
</template>

<script>
    export default {
        name: "AppNavbar",
        props: {
            back: Boolean
        }
    }
</script>

<style scoped>

</style>