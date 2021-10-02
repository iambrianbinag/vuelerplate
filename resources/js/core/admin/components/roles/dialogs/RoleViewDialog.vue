<template>
  <v-row justify="center">
    <v-dialog
      v-model="visible"
      persistent
      max-width="300px"
    >
      <v-form>
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
              title="Role Log" 
              :logData="logData"
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
      </v-form>
    </v-dialog>
  </v-row>
</template>

<script>
  import SystemLogDialog from '../../system-log/dialogs/SystemLogDialog';

  export default {
    name: 'RoleViewDialog',
    components: { SystemLogDialog },
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
    },
    data(){
      return {
        title: 'View role',
        form: {
          id: null,
          name: '',
        },
        logData: {
          'log_name': 'role',
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
       * Set role details in form data
       */
      setRolePropToForm(){
        if(this.role){
          this.form = { ...this.role };
          this.logData.subject_id = this.form.id;
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