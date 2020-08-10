<template>
    <div>
        <app-navbar back>Результат</app-navbar>
        <b-loading :active="!loaded"></b-loading>
        <section class="section">
            <div class="container">
                <div class="level level-item has-text-weight-bold">
                    <p class="is-size-2">
                        {{right}}/{{count}}
                    </p>
                </div>
            </div>
        </section>
        <section class="section" style="padding-top: 0;">
            <div class="container">
                <div v-for="(question, key) in questions" :key="key" class="box has-text-weight-medium">
                    <p style="margin-bottom: 3px;">{{question.value ? question.value : question.default_question.value}}</p>
                    <p
                        :class="[question.answer === answers[key].value ? 'right' : 'wrong']"
                    >Правильный ответ: {{question.answer ? 'Да' : 'Нет'}}</p>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    import AppNavbar from "../components/AppNavbar";
    export default {
        name: "Result",
        components: {AppNavbar},
        data() {
            return {
                answers: {},
                questions: {},
                count: 0,
                right: 0,
                loaded: false,
            }
        },
        created() {
            axios.get('/results/' + this.$route.params['id'])
                .then(response => {
                    Object.entries(response.data.questions).forEach(entry => {
                        let [key, value] = entry
                        this.$set(this.questions, key, {
                            value: value.value ?? value.default_question.value,
                            answer: value.answer
                        })
                        this.count++
                    })
                    Object.entries(response.data.yourAnswer.answers).forEach(entry => {
                        let [key, value] = entry
                        this.$set(this.answers, key, {
                            value: value.answer,
                        })
                        if (value.answer === this.questions[key].answer) {
                            this.right++
                        }
                    })
                    this.loaded = true
                })
        },
    }
</script>

<style scoped>
    .right {
        color: #5dec5d;
    }
    .wrong {
         color: #ec5d5d;
     }
</style>
