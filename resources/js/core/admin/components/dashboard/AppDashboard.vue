<template>
    <v-container>
        <AppLoading 
            v-if="false"
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
                                v-for="item in items"
                                :key="item.id"
                                :color="item.color"
                                :icon="item.icon"
                                small
                                fill-dot
                            >
                                <v-card
                                    :color="item.color"
                                    dark
                                >
                                    <v-card-title class="text-subtitle-1 pa-2">
                                        Admin created a user
                                    </v-card-title>
                                    <v-card-text class="white text--primary pa-2">
                                        <div class="grey--text ms-4">
                                            <v-icon
                                                dense 
                                                color="grey lighten-1"
                                            >
                                                mdi-clock-outline
                                            </v-icon>
                                            2021-09-31 11:25 PM
                                        </div>
                                        <SystemLogChanges
                                            :propertiesData="testChangesData"
                                            :isExpansionPanelsOpen="true"
                                        />
                                    </v-card-text>
                                </v-card>
                            </v-timeline-item>
                            </v-slide-x-reverse-transition>
                        </v-timeline>
                    </v-card>
                </v-col>
            </v-row>
        </div>
    </v-container>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';
    import AppLoading from 'base/components/ui/loading/AppLoading';
    import SystemLogChanges from '../system-log/lists/SystemLogChanges';

    const COLORS = [
        'info',
        'warning',
        'error',
        'success',
    ]
    const ICONS = {
        info: 'mdi-information',
        warning: 'mdi-alert',
        error: 'mdi-alert-circle',
        success: 'mdi-check-circle',
    }

    export default {
        name: 'AppDashboard',
        components: { 
            AppLoading,
            SystemLogChanges,
        },
        data(){
            return {
                testChangesData:{
                    old: {
                        name: "dsadsa", 
                        email: "admin@example.coms",
                        tae: ['ABC', 'DEF', 'DASDSAD', 'DSADasdasdASDA']
                    },
                    attributes: {
                        name: "dsadsass", 
                        email: "admin@example.comsss"
                    },
                },

                totalItems: [
                    {
                        'label': 'Total Users',
                        'value': 10,
                        'icon': 'account-group-outline',
                    },
                    {
                        'label': 'Total Roles',
                        'value': 20,
                        'icon': 'account-lock-outline',
                    },
                    {
                        'label': 'Total Permissions',
                        'value': 20,
                        'icon': 'account-check-outline',
                    },
                ],
                interval: null,
                items: [
                    {
                        id: 1,
                        color: 'info',
                        icon: ICONS.info,
                    },
                ],
                nonce: 2,
            }
        },
        computed: {
            ...mapGetters('admin.system-log', [
                'systemLog',
                'isLoadingGetSystemLog',
            ]),
        },
        methods: {
            ...mapActions('admin.system-log', ['getSystemLog']),
            addEvent () {
                let { color, icon } = this.genAlert()

                const previousColor = this.items[0].color

                while (previousColor === color) {
                    color = this.genColor()
                }

                this.items.unshift({
                    id: this.nonce++,
                    color,
                    icon,
                })

                if (this.nonce > 6) {
                    // this.items.pop()
                }
            },
            genAlert () {
                const color = this.genColor()

                return {
                    color,
                    icon: this.genIcon(color),
                }
            },
            genColor () {
                return COLORS[Math.floor(Math.random() * 3)]
            },
            genIcon (color) {
                return ICONS[color]
            },
            start () {
                this.interval = setInterval(this.addEvent, 3000)
            },
            stop () {
                clearInterval(this.interval)
                this.interval = null
            },
        },
        mounted(){
            this.start();
        },
        beforeDestroy () {
            this.stop();
        },
    }
</script>

<style scoped>
</style>