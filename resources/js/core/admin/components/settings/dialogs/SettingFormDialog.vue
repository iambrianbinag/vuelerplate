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
            <v-toolbar
              color="primary"
              dark
              flat
              dense
            >
              <v-toolbar-title>{{ title }}</v-toolbar-title>
            </v-toolbar>
            <v-card-text>
              <v-row dense>
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
                <v-col
                  cols="12"
                >
                  <form-group name="value">
                    <v-text-field
                      slot-scope="{ attrs }"
                      v-bind="attrs"
                      v-model="form.value"
                      label="Value *"
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
                color='secondary'
                @click="closeDialog"
                small
              >
                Cancel
              </v-btn>
              <v-btn
                :loading="isLoadingCreateSetting || isLoadingUpdateSetting"
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
    name: 'SettingFormDialog',
    props: {
      /**
       * The setting details
       */
      setting: {
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
        form: {
          id: null,
          name: '',
          value: '',
        }
      }
    },
    computed: {
      ...mapGetters('admin.settings', [
        'isLoadingCreateSetting',
        'isLoadingUpdateSetting',
      ]),
      title: function(){
        return this.isUpdateAction ? 'Update setting' : 'Add new setting';
      },
      isUpdateAction: function(){
        return this.form.id ? true : false;
      },
    },
    validations: {
      form: {
        name: { 
          required 
        },
        value: {
          required
        },
      }
    },
    methods: {
      ...mapActions('base.system', [
        'showSnackbar'
      ]),
      ...mapActions('admin.settings', [
        'createSetting',
        'updateSetting',
      ]),
      /** 
       * Reload page 
       */
      reloadPage(){
        window.location.reload();
      },
      /**
       * Close dialog
       */
      closeDialog(){
        this.$emit('update:visible', false);
      },
      /**
       * Set setting details in form data
       */
      setSettingPropToForm(){
        if(this.setting){
          this.form = { ...this.setting };
        }
      },
      /**
       * Set form to empty
       */
      resetForm(){
        this.form = {
          id: null,
          name: '',
          value: '',
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
          this.updateSetting(params)
            .then((data) => {
              this.showSnackbar({
                message: 'Setting updated successfully'
              });
              this.successCallback();
              this.$v.$reset();
              this.closeDialog();
              this.reloadPage();
            });
        } else {
          this.createSetting(params)
            .then((data) => {
              this.showSnackbar({
                message: 'Setting created successfully'
              });
              this.successCallback();
              this.$v.$reset();
              this.resetForm();
              this.closeDialog();
              this.reloadPage();
            });
        }
      },
    },
    mounted(){
      this.setSettingPropToForm();
    }
  }
</script>

<style scoped>
</style>