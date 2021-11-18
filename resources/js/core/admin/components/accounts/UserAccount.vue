<template>
  <v-container>
    <AppHeader :title="headerTitle" />
    <AppLoading 
      v-if="isLoadingGetUserAccount = false"
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
              :loading="isLoadingUpdateUser"
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

  export default {
    name: 'UserAccount',
    components: { AppHeader, AppLoading },
    data(){
      return {
        headerTitle: 'My account',
        isPasswordShown: false,
        isPasswordConfirmationShown: false,
        form: {
          id: null,
          email: '',
          password: '',
          password_confirmaton: '',
        }
      }
    },
    computed: {
      ...mapGetters('admin.users', [
        'isLoadingUpdateUser',
      ]),
      ...mapGetters('base.authentication', [
          'authenticatedUser', 
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
      ...mapActions('admin.users', [
        'updateUser',
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

        this.updateUser(params)
          .then((response) => {
            this.showSnackbar({
              message: 'Account has updated successfully'
            });
            this.$v.$reset();
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