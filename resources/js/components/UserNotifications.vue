<template>
    <div class="dropdown" v-if="notifications.length">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <span class="fas fa-bell"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
            <span v-for="notification in notifications">
                <a class="dropdown-item" :href="notification.data.link"
                   v-text="notification.data.message"
                   @click="markAsRead(notification)"
                ></a>
            </span>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {notifications: false}
        },
        created() {
            axios.get("/profiles/" + window.App.user.name + "/notifications")
            .then(response => this.notifications = response.data);
        },

        methods: {
            markAsRead(notification) {
                axios.delete('/profiles/' + window.App.user.name + '/notifications/' + notification.id)
            }
        }
    }

</script>
