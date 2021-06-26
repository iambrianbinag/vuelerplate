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
            <LogViewDialog
              title="Permission Log" 
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
              <v-col
                cols="12"
              >
                <v-text-field
                  v-model="form.order"
                  label="Order"
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
  import LogViewDialog from '../../log/dialogs/LogViewDialog';

  export default {
    name: 'PermissionViewDialog',
    components: {
      LogViewDialog
    },
    props: {
      /**
       * The permission details
       */
      permission: {
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
        title: 'View permission',
        form: {
          id: null,
          name: '',
          order: '',
        },
        logData: {
          'log_name': 'permission',
          'subject_id': null,
        }
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
       * Set permission details in form data
       */
      setPermissionPropToForm(){
        if(this.permission){
          this.form = { ...this.permission };
          this.logData.subject_id = this.form.id;
        }
      },
    },
    mounted(){
      this.setPermissionPropToForm();
    }
  }
</script>

<style scoped>
</style>