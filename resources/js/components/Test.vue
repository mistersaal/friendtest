<template>
    <div>
        <app-navbar v-if="createTest" back>Новый тест</app-navbar>
        <app-navbar v-else back>Тест</app-navbar>
        <section class="section">
            <div class="container">
                <b-loading :active="!loaded"></b-loading>
                <div class="level">
                    <div class="level-item">
                        <div class="questions" ref="questionCards">
                            <div
                                class="box question-card has-text-weight-medium has-text-centered has-text-black"
                                v-for="(question, key, i) in testQuestions"
                                :key="key"
                                :style="{'background-color': question.color}"
                            >
                                <p class="is-size-5">Правда ли, что:</p>
                                <p class="is-size-5" style="margin-top: 30px;" v-if="!settingYourQuestion">
                                    {{ question.value }}
                                </p>
                                <b-input type="textarea"
                                         v-if="i === 0 && settingYourQuestion"
                                         maxlength="70"
                                         style="margin-top: 30px; width: 100%"
                                         v-model="yourQuestion"
                                ></b-input>
                                <b-button style="margin-top: 30px;"
                                          @click="setYourQuestion"
                                          :loading="loadYourQuestion"
                                          v-if="!settingYourQuestion && createTest"
                                >Написать своё</b-button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section">
            <div class="container">
                <div class="level">
                    <div class="level-item">
                        <div class="answers">
                            <div class="level is-mobile">
                                <div class="level-left">
                                    <b-icon icon="check"
                                            class="answer-button answer-button__right"
                                            @click.native="answer(true)"
                                    ></b-icon>
                                </div>
                                <div class="level-right">
                                    <b-icon icon="times"
                                            class="answer-button answer-button__wrong"
                                            @click.native="answer(false)"
                                    ></b-icon>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section">
            <div class="container">
                <div class="has-text-centered has-text-grey-light">
                    <p class="is-size-5">{{current + 1}}/{{count}}</p>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    import AppNavbar from "./AppNavbar";
    export default {
        name: "Test",
        props: {
            createTest: {
                type: Boolean,
                default: false
            },
            anonymously: {
                type: Boolean,
                default: true
            },
        },
        components: {AppNavbar},
        data() {
            return {
                testQuestions: {},
                current: 0,
                answers: [],
                count: 0,
                colors: [
                    '#fdd4b2',
                    '#dbffb0',
                    '#fff6a9',
                    '#b6ffee',
                    '#b6ffd1',
                    '#bbc4ff',
                    '#bde4ff',
                    '#f6c0ff',
                    '#d8bdff',
                    '#ffc2cf',
                    '#ffbee4',
                ],
                loaded: false,
                animation: false,
                loadYourQuestion: false,
                settingYourQuestion: false,
                yourQuestion: '',
                testId: 0,
            }
        },
        computed: {
            ready() {
                return this.answers.length === this.count && this.loaded
            },
            questionCards() {
                return this.$refs['questionCards']
            },
            vkid() {
                return this.$route.params['vkid']
            },
        },
        watch: {
            ready(value) {
                if (value) {
                    if (this.createTest) {
                        this.sendNewTest()
                    } else {
                        this.sendAnswer()
                    }
                }
            }
        },
        created() {
            if (this.createTest) {
                axios.get('/questions/default')
                    .then(response => {
                        let i = 0
                        Object.entries(response.data).forEach(entry => {
                            let [key, value] = entry
                            this.$set(this.testQuestions, key, {value: value, color: this.colors[i]})
                            this.count++
                            i++
                        })
                        this.loaded = true
                    })
            } else {
                axios.get('/test/' + this.vkid)
                    .then(response => {
                        this.testId = response.data.id
                        let yourAnswer = response.data.yourAnswer
                        if (yourAnswer) {
                            this.$router.replace('/results/' + yourAnswer.id)
                        }
                        let i = 0
                        Object.entries(response.data.questions).forEach(entry => {
                            let [key, value] = entry
                            this.$set(this.testQuestions, key, {
                                value: value.value ?? value.default_question.value,
                                color: this.colors[i]
                            })
                            this.count++
                            i++
                        })
                        this.loaded = true
                    })
                    .catch((e) => {
                        let error = 'Похоже, этот тест больше не существует';
                        if (e.response.status === 403) {
                            error = 'Вы автор этого теста'
                        }
                        this.$buefy.toast.open({
                            message: error,
                            type: 'is-danger',
                            queue: false
                        })
                        this.$router.replace('/')
                    })
            }
        },
        methods: {
            answer(answer) {
                if (this.animation || this.settingYourQuestion && this.yourQuestion === '' || this.loadYourQuestion) {
                    return
                }
                if (this.settingYourQuestion) {
                    this.answers.push({
                        value: this.yourQuestion,
                        answer: answer
                    })
                } else {
                    if (this.createTest) {
                        this.answers.push({
                            default_question_id: Object.keys(this.testQuestions)[0],
                            answer: answer
                        })
                    } else {
                        this.answers.push({
                            question_id: Object.keys(this.testQuestions)[0],
                            answer: answer
                        })
                    }
                }
                this.nextQuestion()
            },
            nextQuestion() {
                this.current++
                if (this.questionCards.children[0]) {
                    this.questionCards.children[0].classList.add('question-card__out')
                }
                if (this.questionCards.children[1]) {
                    this.questionCards.children[1].classList.add('question-card__in')
                }
                this.animation = true
                this.settingYourQuestion = false
                this.yourQuestion = ''
                setTimeout(() => {
                    this.$delete(this.testQuestions, Object.keys(this.testQuestions)[0])
                    this.$nextTick(() => {
                        if (this.questionCards.children[0]) {
                            this.questionCards.children[0].classList.remove('question-card__in')
                        }
                        this.animation = false
                    })
                }, 300)
            },
            sendNewTest() {
                this.loaded = false
                axios.post('/test', {
                    questions: this.answers
                }).then((r) => {
                    this.$buefy.toast.open({
                        message: 'Тест создан!',
                        queue: false,
                        type: 'is-success',
                    })
                    this.$store.commit('setTest', true)
                    this.$router.push('/')
                }).catch((e) => {
                    this.$buefy.toast.open({
                        message: 'Произошла ошибка.',
                        type: 'is-danger',
                        queue: false
                    })
                    this.$router.push('/')
                })
            },
            sendAnswer() {
                this.loaded = false
                axios.post('/test/' + this.testId, {
                    answers: this.answers,
                    anonymously: this.anonymously,
                }).then((r) => {
                    this.$router.replace('/results/' + r.data.answerId)
                }).catch((e) => {
                    this.$buefy.toast.open({
                        message: 'Произошла ошибка.',
                        type: 'is-danger',
                        queue: false
                    })
                    this.$router.push('/')
                })
            },
            setYourQuestion() {
                this.loadYourQuestion = true
                if (this.$store.state.isWeb) {
                    this.loadYourQuestion = false
                    this.settingYourQuestion = true
                } else {
                    bridge.send("VKWebAppShowNativeAds", {ad_format: "reward"})
                        .then(data => {
                            this.loadYourQuestion = false
                            this.settingYourQuestion = true
                        })
                        .catch(() => {
                            this.loadYourQuestion = false
                        })
                }
            },
        },
    }
</script>

<style scoped lang="scss">
    .questions {
        width: 80vw;
        height: 85vw;
        max-width: 320px;
        max-height: 350px;
    }
    .answers {
        width: 65vw;
        max-width: 290px;
    }
    .question-card {
        position: absolute;
        width: 80vw;
        height: 80vw;
        max-width: 320px;
        max-height: 320px;
        transform: scale(0.85);
        top: 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        box-shadow: none;
    }
    .question-card:first-child {
        transform: scale(1);
        top: 0;
        margin: 0;
        z-index: 4;
    }
    .question-card:nth-child(2) {
        z-index: 3;
    }
    .question-card:nth-child(3) {
        z-index: 2;
    }
    .answer-button {
        height: 70px;
        width: 70px;
        border-radius: 100%;
        color: white;
        &__right {
            background-color: #5dec5d;
        }
        &__wrong {
            background-color: #ec5d5d;
        }
    }
    @keyframes out {
        from {top: 0; opacity: 1}
        to {top: -10px; opacity: 0}
    }
    .question-card__out {
        animation-name: out;
        animation-duration: 0.3s;
        animation-iteration-count: 1;
        animation-fill-mode: forwards;
    }
    @keyframes in {
        from {
            transform: scale(0.85);
            top: 40px;
        }
        to {
            transform: scale(1);
            top: 0;
        }
    }
    .question-card__in {
        animation-name: in;
        animation-duration: 0.3s;
        animation-iteration-count: 1;
        animation-fill-mode: forwards;
    }
</style>
