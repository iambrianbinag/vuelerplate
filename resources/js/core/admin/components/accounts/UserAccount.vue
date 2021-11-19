<template>
  <v-container>
    <AppHeader :title="headerTitle" />
    <AppLoading 
      v-if="isLoadingAuthenticatedUser"
      :heightInVH="70" 
    />
    <v-sheet
      v-else
      color="white"
      elevation="1"
      class="pa-2"
    >
      <form-wrapper :validator="$v.form">
        <v-form @submit.prevent="handleFormSubmit">
          <v-row dense>
            <v-col
              cols="12"
              sm="6"
            >
              <form-group name="email">
                <v-text-field
                  slot-scope="{ attrs }"
                  v-bind="attrs"
                  v-model="form.email"
                  label="Email *"
                  hide-details="auto"
                  outlined
                  dense
                />
              </form-group>
            </v-col>
            <v-col
              cols="12"
              sm="6"
            >
              <form-group name="password">
                <v-text-field
                  slot-scope="{ attrs }"
                  v-bind="attrs"
                  v-model="form.password"
                  label="Password"
                  hide-details="auto"
                  :type="isPasswordShown ? 'text' : 'password'"
                  :append-icon="isPasswordShown ? 'mdi-eye' : 'mdi-eye-off'"
                   @click:append="isPasswordShown = !isPasswordShown"
                  outlined
                  dense
                />
              </form-group>
            </v-col>
            <v-col
              cols="12"
              sm="6"
            >
              <form-group name="password_confirmaton">
                <v-text-field
                  slot-scope="{ attrs }"
                  v-bind="attrs"
                  v-model="form.password_confirmaton"
                  label="Password"
                  hide-details="auto"
                  :type="isPasswordConfirmationShown ? 'text' : 'password'"
                  :append-icon="isPasswordConfirmationShown ? 'mdi-eye' : 'mdi-eye-off'"
                   @click:append="isPasswordConfirmationShown = !isPasswordConfirmationShown"
                  outlined
                  dense
                />
              </form-group>
            </v-col>
          </v-row>
          <div class="d-flex justify-end mt-2">
            <v-btn
              :loading="isLoadingUpdateUserAccount"
              color='primary'
              type='submit'
              small
            >
              Save
            </v-btn>
          </div>
        </v-form>
      </form-wrapper>
    </v-sheet>
  </v-container>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex';
  import AppHeader from 'base/components/ui/headers/AppHeader';
  import AppLoading from 'base/components/ui/loading/AppLoading';
  import { required, email, minLength, requiredUnless, sameAs } from 'vuelidate/lib/validators';

  const DEFAULT_FORM_DATA = {
    id: null,
    email: '',
    password: '',
    password_confirmaton: '',
  };

  export default {
    name: 'UserAccount',
    components: { AppHeader, AppLoading },
    data(){
      return {
        headerTitle: 'My account',
        isPasswordShown: false,
        isPasswordConfirmationShown: false,
        form: {...DEFAULT_FORM_DATA},
      }
    },
    computed: {
      ...mapGetters('base.authentication', [
          'authenticatedUser',
          'isLoadingAuthenticatedUser',
          'isLoadingUpdateUserAccount', 
      ]),
      isPasswordEmpty: function(){
        return this.form.password == '' || this.form.password == null;
      }
    },
    watch: {
      authenticatedUser: {
        handler(){
          this.setFormData();
        },
        deep: true,
      }
    },
    validations: {
      form: {
        email: { 
          required, 
          email 
        },
        password: { 
          required: requiredUnless(function(form){
            return this.isPasswordEmpty;
          }),
          minLengthString: minLength(8),
        },
        password_confirmaton: {
          sameAsPassword: sameAs('password'),
        },
      }
    },
    methods: {
      ...mapActions('base.system', [
        'showSnackbar'
      ]),
      ...mapActions('base.authentication', [
        'updateUserAccount',
      ]),
      /**
       * Set form data from authenticated user
       */
      setFormData(){
        if(this.authenticatedUser.information){
          this.form.email = this.authenticatedUser.information.email;
        }
      },
      /**
       * Set form to empty
       */
      resetForm(){
        const retainedFormData  = { email: this.form.email };
        this.form = {...DEFAULT_FORM_DATA, ...retainedFormData};
      },
      /**
       *  Triggered when form is submitted
       * 
       * @event click
       * @type {event}
       */
      handleFormSubmit(){
        this.$v.$touch();
        if(this.$v.$invalid){
          return;
        }

        const params = {...this.form};
        if(params.password == '' || params.password == null){
          delete params.password;
          delete params.password_confirmaton;
        }

        this.updateUserAccount(params)
          .then((response) => {
            this.showSnackbar({
              message: 'Account has updated successfully'
            });
            this.$v.$reset();
            this.resetForm();
          });
      },
    },
    mounted(){
      this.setFormData();
    },
  }
</script>

<style scoped>
</style>