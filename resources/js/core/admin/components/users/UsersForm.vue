<template>
  <v-container>
    <AppHeader title="Add New User" />
    <v-sheet
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
              <form-group name="name">
                <v-text-field
                  slot-scope="{ attrs }"
                  v-bind="attrs"
                  v-model="form.name"
                  label="Name"
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
              <form-group name="email">
                <v-text-field
                  slot-scope="{ attrs }"
                  v-bind="attrs"
                  v-model="form.email"
                  label="Email"
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
          </v-row>
          <div class="d-flex justify-end mt-2">
            <v-btn
              :loading="isLoadingCreateUser"
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
  import AppHeader from '../../../base/components/ui/headers/AppHeader';
  import { required, email, minLength } from 'vuelidate/lib/validators';

  export default {
    name: 'UsersForm',
    components: { AppHeader },
    data(){
      return {
        isPasswordShown: false,
        form: {
          name: '',
          email: '',
          password: '',
        }
      }
    },
    computed: {
      ...mapGetters('admin.users', [
        'isLoadingCreateUser'
      ]),
    },
    validations: {
      form: {
        name: { 
          required 
        },
        email: { 
          required, 
          email 
        },
        password: { 
          required, 
          minLengthString: minLength(8),
        },
      }
    },
    methods: {
      ...mapActions('base.system', [
        'showSnackbar'
      ]),
      ...mapActions('admin.users', [
        'createUser'
      ]),
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

        this.createUser(this.form)
          .then((response) => {
            this.showSnackbar({
              message: 'User created successfully'
            });
            this.resetForm();
          });
      },
      /**
       * Reset form to empty
       */
      resetForm(){
        this.form = {
          name: '',
          email: '',
          password: '',
        }
      }
    }
  }
</script>

<style scoped>
</style>