<template>
  <v-row justify="center">
    <v-dialog
      v-model="visible"
      persistent
      max-width="300px"
    >
      <v-card>
        <v-card-title>
          <span class="text-h6">{{ title }}</span>
        </v-card-title>
        <v-card-text>
          <v-row>
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
    </v-dialog>
  </v-row>
</template>

<script>
  export default {
    name: 'PermissionViewDialog',
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