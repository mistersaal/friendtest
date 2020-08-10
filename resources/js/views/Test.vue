<template>
    <div>
        <div v-if="start">
            <app-navbar>Тест</app-navbar>
            <section class="section">
                <div class="container">
                    <h1 class="title">Готов узнать, насколько хорошо ты знаешь своего друга/подругу?</h1>
                    <b-field>
                        <b-button expanded style="margin-top: 50px;" type="is-primary" @click="start=false">Да!</b-button>
                    </b-field>
                    <b-field>
                        <b-button expanded @click="$router.replace('/')">Выйти на главную</b-button>
                    </b-field>
                </div>
            </section>
        </div>
        <div v-if="!start && !ready">
            <app-navbar>Тест</app-navbar>
            <section class="section">
                <div class="container">
                    <h1 class="title">Хочешь остаться анонимным, или не боишься раскрыть свою личность?</h1>
                    <b-field>
                        <b-button expanded style="margin-top: 50px;" type="is-primary" @click="go(false)">
                            Публично
                        </b-button>
                    </b-field>
                    <b-field>
                        <b-button expanded @click="go(true)">Анонимно</b-button>
                    </b-field>
                </div>
            </section>
        </div>
        <test :anonymously="anonymously" v-show="ready"></test>
    </div>
</template>

<script>
    import Test from "../components/Test";
    import AppNavbar from "../components/AppNavbar";
    export default {
        name: "TestAnswer",
        data() {
            return {
                start: true,
                anonymously: true,
                ready: false,
            }
        },
        methods: {
            go(anonymously) {
                this.anonymously = anonymously
                this.ready = true
            }
        },
        components: {AppNavbar, Test}
    }
</script>

<style scoped>

</style>
