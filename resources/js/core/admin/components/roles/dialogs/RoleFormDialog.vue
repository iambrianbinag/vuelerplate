<template>
  <v-row justify="center">
    <v-dialog
      v-model="visible"
      persistent
      max-width="300px"
    >
      <form-wrapper :validator="$v.form">
        <v-form @submit.prevent="handleFormSubmit">
          <v-card>
            <v-card-title>
              <span class="text-h6">{{ title }}</span>
            </v-card-title>
            <v-card-text>
              <v-row>
                <v-col
                  cols="12"
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
                </v-row>
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn
                :loading="false"
                color='secondary'
                @click="closeDialog"
                small
              >
                  Cancel
              </v-btn>
              <v-btn
                :loading="isLoadingCreateRole || isLoadingUpdateRole"
                color='primary'
                type='submit'
                small
              >
                  Save
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-form>
      </form-wrapper>
    </v-dialog>
  </v-row>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex'; 
  import { required } from 'vuelidate/lib/validators';

  export default {
    name: 'RoleForm',
    props: {
      /**
       * The role details
       */
      role: {
        type: Object,
        default: null,
      },
      /**
       * Visible status of dialog
       */
      visible: {
        type: Boolean,
        default: false,
      },
      /**
       * Get called when action is successful
       */
      successCallback: {
        type: Function,
        default: () => {},
      }
    },
    data(){
      return {
        title: 'Create New Role',
        form: {
          id: null,
          name: '',
        }
      }
    },
    computed: {
      ...mapGetters('admin.roles', [
        'isLoadingCreateRole',
        'isLoadingUpdateRole'
      ]),
      isUpdateAction: function(){
        return this.form.id ? true : false;
      },
    },
    validations: {
      form: {
        name: { 
          required 
        },
      }
    },
    methods: {
      ...mapActions('base.system', [
        'showSnackbar'
      ]),
      ...mapActions('admin.roles', [
        'createRole',
        'updateRole',
      ]),
      /**
       * Close dialog
       */
      closeDialog(){
        this.$emit('update:visible', false);
      },
      /**
       * Set role details in form data
       */
      setRolePropToForm(){
        if(this.role){
          this.form = { ...this.role };
        }
      },
      /**
       * Set form to empty
       */
      resetForm(){
        this.form = {
          id: null,
          name: '',
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

        if(this.isUpdateAction){
          this.updateRole(params)
            .then((response) => {
              this.showSnackbar({
                message: 'Role updated successfully'
              });
              this.$v.$reset();
              this.successCallback();
              this.closeDialog();
            });
        } else {
          delete params.id;
          this.createRole(params)
            .then((response) => {
              this.showSnackbar({
                message: 'Role created successfully'
              });
              this.$v.$reset();
              this.resetForm();
              this.successCallback();
              this.closeDialog();
            });
        }
      },
    },
    mounted(){
      this.setRolePropToForm();
    }
  }
</script>

<style scoped>
</style>