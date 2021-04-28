<template>
    <div class='d-flex justify-center align-center primary -h-100vh'>
        <v-row justify='center'>
            <v-col 
                cols='12' 
                sm='6' 
                md='4'
            >
                <v-container fill-height>
                    <v-layout 
                        align-center 
                        justify-center
                    >
                        <v-flex class='text-center'> 
                            <div class='text-h5 text-md-h4 mb-7'>
                                {{ appName }}
                            </div>
                            <v-card 
                                class='pa-sm-4'
                                light 
                            >
                                <v-card-text>
                                    <div class='subtitle-1 mb-5'>
                                        <template>Login</template>
                                    </div>
                                    <form-wrapper :validator="$v.form">
                                        <v-form @submit.prevent="handleLogin">
                                            <v-alert 
                                                v-model="isErrorsShown"
                                                class='text-left' 
                                                type='error' 
                                                dense 
                                                outlined 
                                                dismissible
                                            >
                                                <div 
                                                    v-for="(error, index) in errors" 
                                                    :key="index"
                                                >
                                                    <div>{{ error }}</div>
                                                </div>
                                            </v-alert>
                                            <form-group name='email'>
                                                <v-text-field
                                                    slot-scope="{ attrs }"
                                                    v-bind="attrs"
                                                    v-model="form.email"
                                                    label='Email' 
                                                    prepend-icon='mdi-email' 
                                                />
                                            </form-group>
                                            <form-group name='password'>
                                                <v-text-field
                                                    slot-scope="{ attrs }"
                                                    v-bind="attrs"
                                                    v-model="form.password"
                                                    label='Password'
                                                    prepend-icon='mdi-lock'
                                                    type='password'
                                            />
                                            </form-group>
                                            <v-btn
                                                :loading="isLoadingAuthenticatedUser" 
                                                color='primary'
                                                class='mt-5' 
                                                type='submit'
                                                block
                                            >
                                                Sign in
                                            </v-btn>
                                        </v-form>
                                    </form-wrapper>
                                </v-card-text>
                            </v-card>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-col>
        </v-row>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';
    import { required, email } from 'vuelidate/lib/validators';
    import config from '../../../../config';

    export default {
        name: 'AppLogin',
        data(){
            return {
                appName: config.APP_NAME,
                form: {
                    email: '',
                    password: ''
                },
                errors: [],
                isErrorsShown: false,
            }
        },
        validations: {
            form : {
                email: { required, email },
                password: { required },
            }
        },
        computed: {
            ...mapGetters('base.authentication', ['authenticatedUser', 'isLoadingAuthenticatedUser']),
        },
        methods: {
            ...mapActions('base.authentication', ['login']),
            /**
             * Triggered when form is submited
             * 
             * @event click
             * @type {Event}
             */
            handleLogin(e){
                this.$v.$touch();
                if(this.$v.$invalid){
                    return;
                }

                this.login(this.form)
                    .then((response) => {
                        this.$router.push({ 
                            name: this.$route.params.nextNamedUrl || 'home' 
                        });
                    }).catch((error) => {
                        const {status, data} = error.response;
                        if(status == 403){
                            this.errors = [data.message];
                            this.isErrorsShown = true;
                        }
                        console.log(error);
                    });
            },
        },
        mounted() {
            //
        }
    }
</script>
