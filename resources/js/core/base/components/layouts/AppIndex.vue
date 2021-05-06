<template>
    <v-app>
        <div v-if="isLoadingLocal">
            <AppLoading/>
        </div>
        <div v-else>
            <div v-if="authenticatedUserToken">
                <SideBar/>
                <NavBar/>
            </div>
            <v-main>
                <router-view></router-view>
            </v-main>
        </div>
        <SnackBar/>
    </v-app>
</template>

<script>
    import { mapGetters, mapActions, mapMutations } from 'vuex';
    import NavBar from './NavBar';
    import SideBar from './SideBar';
    import AppLoading from '../ui/loading/AppLoading';
    import config from '../../../../config';
    import SnackBar from '../ui/messages/SnackBar';

    export default {
        name: 'AppIndex',
        components: { NavBar, SideBar, AppLoading, SnackBar },
        data(){
            return {
                isLoadingLocal: true
            }
        },
        computed: {
            ...mapGetters('base.authentication', [
                'authenticatedUserToken', 
                'authenticatedUserTokenExpiration'
            ])
        },
        watch: {
            authenticatedUserToken: function(authenticatedUserToken){
                if(authenticatedUserToken){
                    this.getAuthenticatedUser();
                }
            }
        },
        methods: {
            ...mapActions('base.authentication', ['refresh', 'getAuthenticatedUser']),
            ...mapMutations('base.system', ['setAppName']),
            /**
             * Refresh token of authenticated user if token's expiration date is equal or greater than
             * the seconds to be refreshed that was set in configuration
             */
            refreshToken(){
                if(this.authenticatedUserToken && this.authenticatedUserTokenExpiration){
                    const tokenExpiration = new Date(this.authenticatedUserTokenExpiration);
                    const currentDate = new Date();
                    const differenceInSeconds = (tokenExpiration.getTime() - currentDate.getTime()) / 1000;
                    const secondsToRefreshToken = parseFloat(config.TOKEN_TO_BE_REFRESHED_SECONDS);
                    if(differenceInSeconds <= secondsToRefreshToken){
                        this.refresh()
                            .finally(() => this.isLoadingLocal = false);
                    } else {
                        this.isLoadingLocal = false;
                    }
                } else {
                    this.isLoadingLocal = false;
                }
            },
        },
        mounted() {
            this.refreshToken();
            this.setAppName(config.APP_NAME);
            if(this.authenticatedUserToken){
                this.getAuthenticatedUser();
            }
        }
    }
</script>
