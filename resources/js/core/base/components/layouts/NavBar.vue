<template>
    <div>
        <v-app-bar
            color="primary"
            dark
            dense
            app
        >
            <v-app-bar-nav-icon @click.stop="handleSideBarToggle"></v-app-bar-nav-icon>
            <v-spacer></v-spacer>
            <v-menu
                bottom
                min-width="150px"
                rounded
                offset-y
            >
                <template v-slot:activator="{ on }">
                    <v-btn
                        icon
                        x-large
                        v-on="on"
                    >
                        <template v-if="authenticatedUserInformation">
                            <v-badge
                                v-if="authenticatedUserInformation.image"
                                bordered
                                bottom
                                color="green"
                                dot
                                offset-x="10"
                                offset-y="10"
                            >
                                <v-avatar size="30">
                                    <v-img 
                                        :src="authenticatedUserInformation.image" 
                                        alt="User Image"
                                    ></v-img>
                                </v-avatar>
                            </v-badge>
                            <v-avatar
                                v-else
                                size="30" 
                                color="white"
                            >
                                <span class="black--text overline">
                                    {{ authenticatedUserInformation.name.slice(0,2) }}
                                </span>
                            </v-avatar>
                        </template>
                        <template v-else>
                            <v-avatar
                                size="30" 
                                color="white"
                            >
                                <v-icon color="primary">
                                    mdi-account-circle
                                </v-icon>
                            </v-avatar>
                        </template>
                    </v-btn>
                </template>
                <v-card v-if="authenticatedUserInformation">
                    <v-list-item-content class="justify-center">
                        <div class="mx-auto text-center">
                            <div class="subtitle-1">{{ authenticatedUserInformation.name | capitalize }}</div>
                            <p class="caption mt-1">
                                {{ authenticatedUserInformation.email }}
                            </p>
                            <v-divider class="my-2"></v-divider>
                            <v-btn
                                depressed
                                small
                                rounded
                                text
                            >
                                Edit Account
                            </v-btn>
                            <v-divider class="my-2"></v-divider>
                            <v-btn
                                @click="handleLogout"
                                :loading="isLoadingAuthenticatedUser"
                                depressed
                                small
                                rounded
                                text
                            >
                                Logout
                            </v-btn>
                        </div>
                    </v-list-item-content>
                </v-card>
            </v-menu>
        </v-app-bar>
    </div>
</template>

<script>
    import { mapGetters, mapActions, mapMutations } from 'vuex'; 

    export default {
        name: 'NavBar',
        computed: {
            ...mapGetters('base.authentication', [
                'authenticatedUserInformation', 
                'isLoadingAuthenticatedUser'
            ]),
            ...mapGetters('base.system', ['isSidebarOpen'])
        },
        methods: {
            ...mapActions('base.authentication', ['logout']),
            ...mapMutations('base.system', ['setIsSidebarOpen']),
            /**
             * Triggered when toggle icon is clicked
             * 
             * @event click
             * @type {event}
             */
            handleSideBarToggle(){
                this.setIsSidebarOpen(!!!this.isSidebarOpen);
            },
            /**
             * Triggered when logout button is clicked
             * 
             * @event click
             * @type {event}
             */
            handleLogout(){
                this.logout()
                    .then((response) => {
                        this.$router.push({ name: 'login' });
                    });
            },
        },
        mounted() {
            //
        }
    }
</script>
