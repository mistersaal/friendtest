<template>
    <b-modal :active="active" @close="close" :can-cancel="['escape', 'outside']">
        <div class="box">
            <div class="level mb-4">
                <div class="level-item">
                    <div class="image is-64x64">
                        <img class="is-rounded" :src="image" alt="">
                    </div>
                </div>
            </div>
            <div class="content has-text-centered mb-4">
                <p class="title is-5" v-html="name"></p>
                <p class="subtitle is-6">Подписаться на сообщество?</p>
            </div>
            <b-field>
                <b-button expanded type="is-primary" @click="allow">Да</b-button>
            </b-field>
            <b-field>
                <b-button expanded @click="deny">Нет</b-button>
            </b-field>
        </div>
    </b-modal>
</template>

<script>
export default {
    name: "Subscribe",
    data() {
        return {
            image: '',
            name: '',
            active: false,
            isMember: false,
        };
    },
    created() {
        bridge.send("VKWebAppGetGroupInfo", {"group_id": this.$store.state.group})
            .then((data) => {
                this.name = data.name
                this.image = data.photo_100
                this.isMember = data.is_member
            })
    },
    methods: {
        open() {
            if (this.isMember) {
                this.next()
            } else {
                this.active = true
            }
        },
        allow() {
            this.close()
            bridge.send("VKWebAppJoinGroup", {group_id: this.$store.state.group})
                .then(() => {
                    bridge.send("VKWebAppAllowMessagesFromGroup", {group_id: this.$store.state.group})
                        .then(() => {
                            this.next()
                        })
                        .catch(() => {
                            this.next()
                        })
                })
                .catch(() => {
                    bridge.send("VKWebAppAllowMessagesFromGroup", {group_id: this.$store.state.group})
                        .then(() => {
                            this.next()
                        })
                        .catch(() => {
                            this.next()
                        })
                })
        },
        next() {
            this.$emit('next')
        },
        deny() {
            this.close()
            this.next()
        },
        close() {
            this.active = false
        }
    }
}
</script>

<style scoped>

</style>
