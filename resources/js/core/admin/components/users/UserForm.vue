<template>
  <v-container>
    <AppHeader :title="headerTitle" />
    <AppLoading 
      v-if="isLoadingGetUser || isLoadingGetRoles"
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
              <form-group name="name">
                <v-text-field
                  slot-scope="{ attrs }"
                  v-bind="attrs"
                  v-model="form.name"
                  label="Name *"
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
              <form-group name="role">
                <v-select
                  slot-scope="{ attrs }"
                  v-bind="attrs"
                  v-model="form.role"
                  label="Role *"
                  :items="roles || []"
                  item-text="name"
                  item-value="id"
                  hide-details="auto"
                  persistent-hint
                  return-object
                  outlined
                  dense
                ></v-select>
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
          </v-row>
          <div class="d-flex justify-end mt-2">
            <v-btn
              :loading="isLoadingCreateUser || isLoadingUpdateUser"
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
  import AppLoading from '../../../base/components/ui/loading/AppLoading';
  import { required, email, minLength, requiredUnless } from 'vuelidate/lib/validators';

  export default {
    name: 'UserForm',
    components: { AppHeader, AppLoading },
    data(){
      return {
        isPasswordShown: false,
        form: {
          id: null,
          name: '',
          email: '',
          role: '',
          password: '',
        }
      }
    },
    computed: {
      ...mapGetters('admin.users', [
        'isLoadingCreateUser',
        'isLoadingUpdateUser',
        'isLoadingGetUser',
      ]),
      ...mapGetters('admin.roles', [
        'roles',
        'isLoadingGetRoles',
      ]),
      headerTitle: function(){
        return this.isUpdateAction ? 'Update user' : 'Add new user';
      },
      isUpdateAction: function(){
        return this.getIdParam() ? true : false;
      },
    },
    validations: {
      form: {
        name: { 
          required 
        },
        role: {
          required
        },
        email: { 
          required, 
          email 
        },
        password: { 
          required: requiredUnless(function(form){
            return this.isUpdateAction;
          }),
          minLengthString: minLength(8),
        },
      }
    },
    methods: {
      ...mapActions('base.system', [
        'showSnackbar'
      ]),
      ...mapActions('admin.users', [
        'createUser',
        'updateUser',
        'getUser',
      ]),
      ...mapActions('admin.roles', [
        'getRoles',
      ]),
      /**
       * Get id in route's params
       */
      getIdParam(){
        return this.$route.params.id;
      },
      /**
       * Get user by id
       */
      fetchUser(){
        this.getUser({ id: this.getIdParam() })
          .then((data) => {
            this.form = {...this.form, ...data};
          });
      },
      /**
       * Set form to empty
       */
      resetForm(){
        this.form = {
          id: null,
          name: '',
          email: '',
          role: '',
          password: '',
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
        params.role_id = params.role.id;
        delete params.role;

        if(this.isUpdateAction){
          if(!params.password){
            delete params.password;
          }
          this.updateUser(params)
            .then((response) => {
              this.showSnackbar({
                message: 'User updated successfully'
              });
              this.$v.$reset();
            });
        } else {
          delete params.id;
          this.createUser(params)
            .then((response) => {
              this.showSnackbar({
                message: 'User created successfully'
              });
              this.$v.$reset();
              this.resetForm();
            });
        }
      },
    },
    mounted(){
      this.getRoles({ not_paginated: true});
      if(this.isUpdateAction){
        this.fetchUser();
      }
    }
  }
</script>

<style scoped>
</style>