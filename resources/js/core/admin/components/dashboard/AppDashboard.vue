<template>
    <v-container>
        <AppLoading 
            v-if="isLoadingFetchingOfDashboardData"
            :heightInVH="70" 
        />
        <div v-else>
            <v-row dense>
                <v-col 
                    v-for="(item, index) in totalItems"
                    :key="index"
                    cols="12"
                    md="4"
                >
                    <v-card 
                        class="text-xs-center" 
                        height="100%"
                    >
                        <v-card-text class="d-flex justify-space-between">
                            <div>
                                <div class="text-h4 mb-2">{{ item.value }}</div>{{ item.label }}
                            </div>
                            <div>
                                <v-icon size="70">
                                    {{`mdi-${item.icon}`}}
                                </v-icon>
                            </div>
                        </v-card-text>
                        <v-card-actions class="primary">
                            <v-spacer></v-spacer>
                            <div class="subtitle-2">
                                 <v-btn
                                    color="white" 
                                    text 
                                    small
                                >
                                    More Info
                                    <v-icon large>
                                        mdi-chevron-right
                                    </v-icon>
                                </v-btn>
                            </div> 
                            <v-spacer></v-spacer>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>
            <v-row dense>
                <v-col cols="12">
                    <v-card 
                        class="text-xs-center" 
                        height="100%"
                    >
                        <v-card-title class="primary white--text">Activity Log</v-card-title>
                        <v-timeline dense>
                            <v-slide-x-reverse-transition
                                group
                                hide-on-leave
                            >
                            <v-timeline-item
                                v-for="systemLog in systemLogData"
                                :key="systemLog.id"
                                :color="getSystemLogAttributeByActionType(systemLog.description).color"
                                :icon="getSystemLogAttributeByActionType(systemLog.description).icon"
                                small
                                fill-dot
                            >
                                <v-card
                                    :color="getSystemLogAttributeByActionType(systemLog.description).color"
                                    dark
                                >
                                    <v-card-title class="text-subtitle-1 pa-2">
                                        {{ formatActivityLogDescription(systemLog.causer ? systemLog.causer.name : null, systemLog.description, systemLog.log_name) | capitalize }}
                                    </v-card-title>
                                    <v-card-text class="white text--primary pa-2">
                                        <div class="grey--text ms-4">
                                            <v-icon
                                                dense 
                                                color="grey lighten-1"
                                            >
                                                mdi-clock-outline
                                            </v-icon>
                                            {{ $moment(systemLog.created_at).format('YYYY-MM-DD hh:mm A') }}
                                        </div>
                                        <SystemLogChanges
                                            :propertiesData="systemLog.changes"
                                            :isExpansionPanelsOpen="true"
                                        />
                                    </v-card-text>
                                </v-card>
                            </v-timeline-item>
                            </v-slide-x-reverse-transition>
                        </v-timeline>
                        <v-card-actions class="primary">
                            <v-spacer></v-spacer>
                            <div class="subtitle-2">
                                 <v-btn
                                    color="white" 
                                    text 
                                    small
                                >
                                    More Info
                                    <v-icon large>
                                        mdi-chevron-right
                                    </v-icon>
                                </v-btn>
                            </div> 
                            <v-spacer></v-spacer>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>
        </div>
    </v-container>
</template>

<script>
    import { mapGetters, mapActions, mapMutations } from 'vuex';
    import AppLoading from 'base/components/ui/loading/AppLoading';
    import SystemLogChanges from '../system-log/lists/SystemLogChanges';

    const SYSTEM_LOG_ATTRIBUTES = {
        created: {
            color: 'success',
            icon: 'mdi-check-circle',
        },
        updated: {
            color: 'info',
            icon: 'mdi-information'
        },
        deleted: {
            color: 'error',
            icon: 'mdi-alert',
        },
        viewed: {
            color: 'warning',
            icon: 'mdi-eye',
        }
    };

    export default {
        name: 'AppDashboard',
        components: { 
            AppLoading,
            SystemLogChanges,
        },
        data(){
            return {
                totalItems: [
                    {
                        'type': 'user',
                        'label': 'Total Users',
                        'value': 0,
                        'icon': 'account-group-outline',
                    },
                    {
                        'type': 'role',
                        'label': 'Total Roles',
                        'value': 0,
                        'icon': 'account-lock-outline',
                    },
                    {
                        'type': 'permission',
                        'label': 'Total Permissions',
                        'value': 0,
                        'icon': 'account-check-outline',
                    },
                ],
                systemLogData: [],
            }
        },
        computed: {
            ...mapGetters('admin.users', [
                'totalUsers',
                'isLoadingGetTotalUsers',
            ]),
            ...mapGetters('admin.roles', [
                'totalRoles',
                'isLoadingGetTotalRoles',
            ]),
            ...mapGetters('admin.permissions', [
                'totalPermissions',
                'isLoadingGetTotalPermissions',
            ]),
            ...mapGetters('admin.system-log', [
                'systemLog',
                'isLoadingGetSystemLog',
            ]),
            isLoadingFetchingOfDashboardData(){
                return this.isLoadingGetTotalUsers 
                    || this.isLoadingGetTotalRoles
                    || this.isLoadingGetTotalPermissions
                    || this.isLoadingGetSystemLog;
            },
        },
        methods: {
            ...mapActions('admin.users', [
                'getTotalUsers',
            ]),
            ...mapMutations('admin.users', [
                'setIncrementOnTotalUsers',
            ]),
            ...mapActions('admin.roles', [
                'getTotalRoles',
            ]),
            ...mapMutations('admin.roles', [
                'setIncrementOnTotalRoles',
            ]),
            ...mapActions('admin.permissions', [
                'getTotalPermissions',
            ]),
            ...mapMutations('admin.permissions', [
                'setIncrementOnTotalPermissions',
            ]),
            ...mapActions('admin.system-log', [
                'getSystemLog'
            ]),
            ...mapMutations('admin.system-log', [
                'setFirstDataAndRemoveLastDataInSystemLog'
            ]),
            /**
             * Get the attributes of system log based on given action type
             */
            getSystemLogAttributeByActionType(actionTypeName){
                return SYSTEM_LOG_ATTRIBUTES[actionTypeName];
            },
            /**
             * Get total item based on given type
             */
            getTotalItemByType(type){
                return this.totalItems.find(item => item.type === type);
            },
            /**
             * Format description of activity log based on given values
             */
            formatActivityLogDescription(causerName, description, logName){
                const defaultCauserName = 'Admin';
                return `${causerName || defaultCauserName} ${description} a ${logName}`;
            },
            /**
             * Set total data based on given type
             */
            setTotalDataValue(type, value){
                this.getTotalItemByType(type).value = value;
            },
            /**
             * Increment total data based on given type
             */
            setIncrementOnTotalDataValue(type){
                const item = this.getTotalItemByType(type);
                item.value = parseInt(item.value) + 1;
            },
            setSystemLogData(data){
                this.systemLogData = data;
            },
            /**
             * Fetch all dashboard data
             */
            fetchDashboardData(){
                this.getTotalUsers().then(() => this.setTotalDataValue('user', this.totalUsers));
                this.getTotalRoles().then(() => this.setTotalDataValue('role', this.totalRoles));
                this.getTotalPermissions().then(() => this.setTotalDataValue('permission', this.totalPermissions));
                this.getSystemLog().then(() => this.setSystemLogData(this.systemLog.data));
            },
            /**
             * Setup websocket for realtime update of data
             */
            setDashboardDataRealtime(){
                Echo.channel('Users').listen('.UserCreated', event => {
                    this.setIncrementOnTotalUsers();
                    this.setIncrementOnTotalDataValue('user');
                });
                Echo.channel('Roles').listen('.RoleCreated', event => {
                    this.setIncrementOnTotalRoles();
                    this.setIncrementOnTotalDataValue('role');
                });
                Echo.channel('Permissions').listen('.PermissionCreated', event => {
                    this.setIncrementOnTotalPermissions();
                    this.setIncrementOnTotalDataValue('permission');
                });
                Echo.channel('Activities').listen('.ActivityCreated', event => {
                    this.setFirstDataAndRemoveLastDataInSystemLog(event.activity);
                    this.setSystemLogData(this.systemLog.data);
                });
            },
        },
        mounted(){
            this.fetchDashboardData();
            this.setDashboardDataRealtime();
        },
    }
</script>

<style scoped>
</style>