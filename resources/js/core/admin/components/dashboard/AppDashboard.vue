<template>
    <v-container>
        <AppLoading 
            v-if="false"
            :heightInVH="70" 
        />
        <div v-else>
            <v-row dense>
                <v-col 
                    cols="12"
                    md="6"
                >
                    <v-card 
                        class="text-xs-center" 
                        height="100%"
                    >
                        <v-card-text>
                            <div class="display-1 mb-2">20</div>Total users
                        </v-card-text>
                    </v-card>
                </v-col>
                <v-col 
                    cols="12"
                    md="6"
                >
                    <v-card 
                        class="text-xs-center" 
                        height="100%"
                    >
                        <v-card-text>
                            <div class="display-1 mb-2">26</div>Total roles
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
            <v-row dense>
                <v-col
                    cols="12"
                >
                    <v-card 
                        class="text-xs-center" 
                        height="100%"
                    >
                        <v-card-title class="primary">Activity Log</v-card-title>
                        <v-timeline dense>
                            <v-slide-x-reverse-transition
                                group
                                hide-on-leave
                            >
                            <v-timeline-item
                                v-for="item in items"
                                :key="item.id"
                                :color="item.color"
                                small
                                fill-dot
                            >
                                <v-alert
                                    :value="true"
                                    :color="item.color"
                                    :icon="item.icon"
                                    class="white--text"
                                >
                                Lorem ipsum dolor sit amet, no nam oblique veritus. Commune scaevola imperdiet nec ut, sed euismod convenire principes at. Est et nobis iisque percipit, an vim zril disputando voluptatibus, vix an salutandi sententiae.
                                </v-alert>
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
        components: { AppLoading },
        data(){
            return {
                stats: [
                    {
                        number: '42',
                        label: 'New leads this week',
                    },
                    {
                        number: '$8,312',
                        label: 'Sales this week',
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

        mounted(){
            this.start();
        },

        beforeDestroy () {
            this.stop();
        },

        methods: {
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
    }
</script>

<style scoped>
</style>