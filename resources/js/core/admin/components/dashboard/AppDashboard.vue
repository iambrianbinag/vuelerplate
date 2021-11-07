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
                    <AttributeDetails :attributeDetails="item" />
                </v-col>
            </v-row>
            <v-row dense>
                <v-col cols="12">
                    <SystemLog :systemLogData="systemLogData" />
                </v-col>
            </v-row>
        </div>
    </v-container>
</template>

<script>
    import { mapGetters, mapActions, mapMutations } from 'vuex';
    import AppLoading from 'base/components/ui/loading/AppLoading';
    import AttributeDetails from './lists/AttributeDetails';
    import SystemLog from './lists/SystemLog';

    export default {
        name: 'AppDashboard',
        components: { 
            AppLoading,
            AttributeDetails,
            SystemLog,
        },
        data(){
            return {
                totalItems: [
                    {
                        'type': 'user',
                        'label': 'Total Users',
                        'value': 0,
                        'icon': 'account-group-outline',
                        'pathName': 'user-list',
                    },
                    {
                        'type': 'role',
                        'label': 'Total Roles',
                        'value': 0,
                        'icon': 'account-lock-outline',
                        'pathName': 'role-list',
                    },
                    {
                        'type': 'permission',
                        'label': 'Total Permissions',
                        'value': 0,
                        'icon': 'account-check-outline',
                        'pathName': 'permission-list',
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
             * Get total item based on given type
             */
            getTotalItemByType(type){
                return this.totalItems.find(item => item.type === type);
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