<template>
    <div>
        <h3>THIS IS Navbar!</h3>
        <div v-if="isLoadingAuthenticatedUser">Loading...</div>
        <div v-if="authenticatedUserInformation">
            Welcome <span style="color: red">{{ authenticatedUserInformation.email }}</span>
        </div>
        <button @click="handleLogout">Logout</button>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'; 

    export default {
        name: 'NavBar',
        computed: {
            ...mapGetters('base.authentication', [
                'authenticatedUserInformation', 
                'isLoadingAuthenticatedUser'
            ])
        },
        methods: {
            ...mapActions('base.authentication', [
                'getAuthenticatedUser', 
                'logout'
            ]),
            /**
             * Triggered when logout button is clicked
             * 
             * @event click
             * @type {Event}
             */
            handleLogout(){
                this.logout()
                    .then((response) => {
                        this.$router.push({ name: 'login' });
                    });
            }
        },
        mounted() {
            this.getAuthenticatedUser();
        }
    }
</script>
