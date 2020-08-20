<template>
    <div>
        <p class="title is-6" style="margin-bottom: 12px;">
            Также наши приложения:
        </p>
        <div class="level is-mobile h-scroll">
            <div class="level-left">
                <div class="level-item" v-for="app in apps">
                    <a class="box has-text-centered app__box" :href="app.link" target="_blank">
                        <img :src="app.image" class="image is-48x48" alt="" style="border-radius: 10px">
                        <p class="title is-6" style="margin-top: 5px;">{{ app.name }}</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import apps from "../data/apps"
    export default {
        name: "Apps",
        data() {
            return {
                apps,
            }
        },
    }
</script>

<style scoped>

</style>