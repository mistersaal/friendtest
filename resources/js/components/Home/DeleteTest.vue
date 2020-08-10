<template>
    <b-modal :active="isOpen"
             has-modal-card
             trap-focus
             :can-cancel="['escape', 'outside']"
             @close="close"
             destroy-on-hide
    >
        <form @submit.prevent="deleteTest">
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title has-text-weight-bold">
                        Удаление
                    </p>
                </header>
                <section class="modal-card-body">
                    <p>
                        Тест и все результаты будут удалены.<br>
                        Вы уверены, что хотите удалить тест?
                    </p>
                </section>
                <footer class="modal-card-foot">
                    <b-button @click="close">Отмена</b-button>
                    <b-button type="is-danger" native-type="submit" :loading="isLoading">
                        Удалить тест
                    </b-button>
                </footer>
            </div>
        </form>
    </b-modal>
</template>

<script>
    export default {
        name: "DeleteTest",
        data() {
            return {
                isLoading: false
            }
        },
        computed: {
            isOpen() {
                return this.$route.hash === '#delete'
            },
        },
        methods: {
            close() {
                this.$router.back()
            },
            deleteTest() {
                this.isLoading = true
                axios.delete('/test').then((r) => {
                    this.$buefy.toast.open({message: 'Тест удален!', queue: false})
                    this.isLoading = false
                    this.$store.commit('setTest', false)
                    this.close()
                }).catch((e) => {
                    this.isLoading = false
                    this.$buefy.toast.open({
                        message: 'Произошла ошибка.',
                        type: 'is-danger',
                        queue: false
                    })
                })
            }
        }
    }
</script>

<style scoped>

</style>
