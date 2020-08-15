<template>
    <div>
        <app-navbar>Правда-ложь</app-navbar>
        <section class="section">
            <b-loading :is-full-page="true" :active="!loaded"></b-loading>
            <delete-test></delete-test>
            <div class="container" v-if="loaded">
                <div class="box" v-if="!hasTest">
                    <h2 class="title is-4">Создайте тест</h2>
                    <p class="subtitle is-6">Ответьте на пару вопросов или напишите свои</p>
                    <b-button expanded type="is-primary" @click="createTest">Создать тест</b-button>
                </div>
                <div class="box" v-else>
                    <h2 class="title is-4">Ваш тест готов!</h2>
                    <p class="subtitle is-6">
                        Время узнать, как хорошо Вас знают друзья!
                        Запостите тест у себя в историях или на стене.
                    </p>
                    <b-field>
                        <b-button expanded type="is-primary" icon-left="camera" @click="history">Поделиться в истории</b-button>
                    </b-field>
                    <b-field>
                        <b-button expanded type="is-primary" icon-left="share" @click="share">Поделиться</b-button>
                    </b-field>
                    <b-field>
                        <b-button expanded type="is-light" icon-left="trash" @click="deleteTest">Удалить</b-button>
                    </b-field>
                </div>
                <div class="box">
                    <div class="level is-mobile">
                        <div class="level-left">
                            <h2 class="title is-4">Статистика</h2>
                        </div>
                        <div class="level-right">
<!--                            <b-icon icon="bell-slash" style="cursor: pointer"></b-icon>-->
                        </div>
                    </div>
                    <div class="columns is-centered is-mobile" v-if="!hasTest">
                        <div class="column is-three-quarters">
                            <div class="has-text-centered has-text-grey">
                                <p>Пока тут ничего нет, но обязательно будет, когда Вы создадите тест</p>
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <template v-if="results.length !== 0">
                            <router-link tag="div" :to="'/results/' + result.id" class="box" v-for="(result, key) in results" :key="key">
                                <div class="level is-mobile">
                                    <div class="level-left">
                                        <p v-if="result.anonymously">
                                            Анонимно
                                        </p>
                                        <template v-else>
                                            <img :src="result.responder.img" class="profile-image">
                                            <p>
                                                {{result.responder.first_name}} {{result.responder.last_name}}
                                            </p>
                                        </template>
                                    </div>
                                    <div class="level-right">
                                        <p>{{result.right}}/{{result.count}}</p>
                                        <b-icon icon="chevron-right" style="margin-right: -3px;margin-left: 3px;"></b-icon>
                                    </div>
                                </div>
                            </router-link>
                        </template>
                        <div class="columns is-centered is-mobile" v-else>
                            <div class="column is-three-quarters">
                                <div class="has-text-centered has-text-grey">
                                    <p v-if="loaded && resultLoaded">
                                        Разместите историю о своём тесте, чтобы друзья узнали о нём!
                                    </p>
                                    <p v-else>
                                        Загрузка...
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    import AppNavbar from "../components/AppNavbar"
    import DeleteTest from "../components/Home/DeleteTest"
    export default {
        name: 'Home',
        components: {DeleteTest, AppNavbar},
        data() {
            return {
                results: [],
                resultLoaded: false,
            }
        },
        methods: {
            deleteTest() {
                this.$router.push(this.$route.fullPath + '#delete')
            },
            createTest() {
                bridge.send("VKWebAppJoinGroup", {group_id: this.$store.state.group})
                    .then(() => {
                        bridge.send("VKWebAppAllowMessagesFromGroup", {group_id: this.$store.state.group})
                    })
                    .catch(() => {
                        bridge.send("VKWebAppAllowMessagesFromGroup", {group_id: this.$store.state.group})
                    })
                this.$router.push('/create')
            },
            share() {
                bridge.send("VKWebAppShare", {
                    link: "https://vk.com/app" + this.$store.state.appId + "#" + this.vkid
                });
            },
            history() {
                bridge.send("VKWebAppShowStoryBox", {
                    background_type: this.$store.state.isWeb ? "image" : "none",
                    blob: this.getDataUrl(document.getElementById('background')),
                    stickers: [
                        {
                            sticker_type: 'renderable',
                            sticker: {
                                content_type: 'image',
                                blob: this.getDataUrl(document.getElementById('sticker')),
                                clickable_zones: [{
                                    action_type: 'link',
                                    action: {
                                        link: "https://vk.com/app" + this.$store.state.appId + "#" + this.vkid,
                                        tooltip_text_key: 'tooltip_open_default'
                                    },
                                    clickable_area: [
                                        {x: 0, y: 0},
                                        {x: 1440, y: 0},
                                        {x: 1440, y: 1080},
                                        {x: 0, y: 1080},
                                    ]
                                }],
                                original_width: 1440,
                                original_height: 1080,
                                can_delete: false,
                            }
                        }
                    ]
                }).then(() => {
                    ym(66061846,'reachGoal','friendsecretshistory')
                })
            },
            getDataUrl(img) {
                // Create canvas
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                // Set width and height
                canvas.width = img.width;
                canvas.height = img.height;
                // Draw the image
                ctx.drawImage(img, 0, 0);
                return canvas.toDataURL('image/png');
            }
        },
        computed: {
            hasTest() {
                return this.$store.state.hasTest
            },
            vkid() {
                return this.$store.state.vkid
            },
            loaded() {
                return this.$store.state.loaded
            },
        },
        watch: {
            hasTest(value) {
                if (!value) {
                    this.results = []
                }
            },
        },
        created() {
            axios.get('/test/info')
                .then(response => {
                    this.results = response.data.answers
                    this.resultLoaded = true
                }).catch(() => {})
            this.$store.commit('mainPageOpened')
        }
    }
</script>

<style scoped>
    .profile-image {
        height: 30px;
        width: 30px;
        border-radius: 100%;
        margin-right: 7px;
    }
</style>
