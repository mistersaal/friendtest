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
                    <b-button type="is-primary" expanded
                              @click="showPrediction"
                              :loading="isLoading"
                    >Получить предсказание
                    </b-button>
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
        <section class="section">
            <div class="container">
                <apps></apps>
            </div>
        </section>
        <subscribe ref="sub" @next="goToCreatePage"></subscribe>
    </div>
</template>

<script>
    import AppNavbar from "../components/AppNavbar"
    import DeleteTest from "../components/Home/DeleteTest"
    import Apps from "../components/Apps";
    import Subscribe from "../components/Subscribe";
    export default {
        name: 'Home',
        components: {Subscribe, Apps, DeleteTest, AppNavbar},
        data() {
            return {
                results: [],
                resultLoaded: false,
                predictions: [
                    "Если Вы проявите инициативу, успех не заставит себя ждать.",
                    "Ваши надежды и планы сбудутся сверх всяких ожиданий.",
                    "Готовьтесь к романтическим приключениям.",
                    "В этом месяце ночная жизнь для вас.",
                    "Вам пора отдохнуть.",
                    "Вам предлагается мечта всей жизни. Скажите да!",
                    "Вас ждет приятный сюрприз.",
                    "Ваши надежды и планы сбудутся сверх всяких ожиданий.",
                    "Время – ваш союзник, лучше отложить принятие важного решения хотя бы на день.",
                    "Время и терпение, вас ждут много сюрпризов!",
                    "Время осушит все слезы и исцелит все раны.",
                    "Все загаданные желания и намеченные планы осуществятся",
                    "Не стоит пренебрегать чужим мнением. Рядом с Вами находятся люди, которые искренне хотят помочь",
                    "Пришло время заявить о себе, даже если это кому-то не понравится",
                    "Сейчас в Вашей жизни наступает переломный момент, от которого зависит будущее",
                    "Пришло время показать, кем же Вы являетесь на самом деле",
                    "На протяжении многих лет Вам будут сопутствовать счастье, здоровье, удача и благополучие",
                    "Впереди Вас ждет неожиданное получение денег, кото…оправит Ваше пошатнувшееся материальное положение",
                    "Не огорчайтесь, если дела идут не так, как Вам бы этого хотелось, удача уже на пороге.",
                    "Пора собирать чемоданы: Вас ждет путешествие в приятной компании",
                    "Ваши отношения с любимым человеком продлятся долго… Вы не будете рассказывать о них незнакомым людям",
                    "Пролема в том, что Вы думаете, что это проблема",
                    "Не думай о том, правильно ли это или нет... Делай то, что делает тебя счастливым",
                    "Один добытый опыт важнее семи правил мудрости",
                    "Когда Бог закрывает дверь, он открывает окно",
                    "Дорога в тысячу миль начинается с первого шага.",
                    "Никогда не бойся делать то, что ты не умеешь. Помни, ковчег был построен любителем. Профессионалы строили Титаник!",
                    "Лучше жалеть о том, что сделал, а не о том, что не сделал.",
                    "Кто стоит на месте, тот идёт назад.",
                    "Что не делается, всё к лучшему.",
                    "Никто не побежден до тех пор, пока не признает себя побежденным.",
                    "Борьба всегда оправдана, если знаешь к чему стремишься.",
                    "Главное — не забыть главное. А то забудешь главное, а это главное!",
                    "Не рвись в герои, пока не позовут.",
                    "Эти люди и эти события твоей жизни оказались здесь потому, что ты сам их сюда привел. То, что будет сними дальше, зависит от тебя.",
                    "Никогда ни у кого ничего не проси, особенно у тех, кто сильнее тебя — сами придут и всё дадут.",
                    "Один раз везет только дуракам. Умным везет всегда.",
                    "Зло не в том, что входит в уста человека, а в том, что выходит из них.",
                    "Делайте, что можете, используя то, что есть, там, где вы сейчас.",
                    "Не думай.",
                    "Если думаешь — не говори.",
                    "Если думаешь и говоришь — не пиши.",
                    "Если думаешь, говоришь, пишешь — не подписывайся.",
                    "Если думаешь, говоришь, пишешь и подписываешься — не удивляйся.",
                    "Если в себя не веришь, то ничего и не начнешь. А если ничего не начинать, то ничего и не произойдет.",
                    "Люди любят побеждать. Если вы не определились с окончательной целью, шансов на победу у вас нет.",
                    "Цель определяет успех.",
                    "Цель — основная точка отсчета для инвестиций энергии и времени.",
                    "Успех женщины- не много мужчин, а один.",
                    "Секрет успешного продвижения — это начало.",
                    "Секрет начала — это разбиение ваших сложносочиненных дел на мелкие, легко выполнимые задачи и выполнение их, начиная с первой.",
                    "Не важно, насколько большая и сложная проблема перед вами -сделайте маленький шажок к её решению.",
                    "Из безвыходной ситуации всегда найдётся выход.",
                    "Никогда не отказывайся от своей мечты.",
                    "Когда ты чего-нибудь хочешь, вся Вселенная будет способствовать тому, чтобы желание твоё сбылось.",
                    "Сегодня как раз наступило то завтра, о котором вы беспокоились вчера.",
                    "Нет безвыходных ситуаций: даже если вас съели, у вас, по крайней мере, есть два выхода.",
                    "Если ты родился без крыльев — не мешай им расти.",
                    "У собаки столько друзей потому, что она виляет хвостом, а не болтает языком",
                    "Ты уникален, так же, как и все остальные.",
                    "Любите врагов ваших… это точно приведёт их в бешенство!",
                    "Нет безвыходных ситуаций. Обыкновенно выход есть там же где и вход.",
                    "Мало знать себе цену — надо еще пользоваться спросом.",
                    "Вы будете очень успешны в бизнесе.",
                    "Вас ожидают перемены.",
                    "Если Вы еще не встретили свою любовь, то в этом году обязательно встретите.",
                    "Не позволяйте никому стоять на Вашем пути к счастью.",
                    "Не осуждайте никого, чтобы не осуждали Вас.",
                    "Жизнь — это чудо, дарованное нам свыше.",
                    "Зависть разрушает душу человека",
                ],
                isLoading: false,
            }
        },
        methods: {
            showPrediction() {
                this.isLoading = true
                if (!this.$store.state.isWeb) {
                    bridge.send("VKWebAppShowNativeAds", {ad_format: "reward"})
                        .then(data => {
                            this.createResult()
                        })
                        .catch(() => {
                            this.isLoading = false
                        })
                } else {
                    this.createResult()
                }
            },
            createResult() {
                let prediction = this.predictions[Math.floor(Math.random() * this.predictions.length)]
                this.$buefy.dialog.alert({
                    message: '<div class="has-text-centered is-size-4">' + prediction + '</div>',
                    confirmText: 'Ок'
                })
                this.isLoading = false
            },
            deleteTest() {
                this.$router.push(this.$route.fullPath + '#delete')
            },
            createTest() {
                this.$refs['sub'].open()
            },
            goToCreatePage() {
                this.$router.push('/create')
            },
            share() {
                bridge.send("VKWebAppShare", {
                    link: "https://vk.com/app" + this.$store.state.appId + "#" + this.vkid
                });
            },
            history() {
                bridge.send("VKWebAppShowStoryBox", {
                    background_type: "image",
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
                                        {x: 350, y: 0},
                                        {x: 350, y: 263},
                                        {x: 0, y: 263},
                                    ]
                                }],
                                original_width: 350,
                                original_height: 263,
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
