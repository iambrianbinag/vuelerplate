<template>
  <v-row justify="center">
    <v-dialog
      v-model="visible"
      persistent
      max-width="300px"
    >
      <form>
        <v-card>
          <v-toolbar
            color="primary"
            dark
            flat
            dense
          >
            <v-toolbar-title>{{ title }}</v-toolbar-title>
            <v-spacer></v-spacer>
            <SystemLogDialog
              title="Setting Log" 
              :systemLogData="systemLogData"
            />
          </v-toolbar>
          <v-card-text>
            <v-row dense>
              <v-col
                cols="12"
              >
                <v-text-field
                  v-model="form.name"
                  label="Name"
                  hide-details
                  disabled
                  outlined
                  dense
                />
              </v-col>
              <v-col
                cols="12"
              >
                <v-text-field
                  v-model="form.value"
                  label="Value"
                  hide-details
                  disabled
                  outlined
                  dense
                />
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
                Close
            </v-btn>
          </v-card-actions>
        </v-card>
      </form>
    </v-dialog>
  </v-row>
</template>

<script>
  import SystemLogDialog from '../../system-log/dialogs/SystemLogDialog';

  export default {
    name: 'SettingViewDialog',
    components: { SystemLogDialog },
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
    },
    data(){
      return {
        title: 'View setting',
        form: {
          id: null,
          name: '',
          value: '',
        },
        systemLogData: {
          'log_name': 'setting',
          'subject_id': null,
        },
      }
    },
    methods: {
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
          this.systemLogData.subject_id = this.form.id;
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