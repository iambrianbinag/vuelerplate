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
                label="Name *"
                required
              ></v-text-field>
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
            :loading="false"
            color='primary'
            type='submit'
            small
          >
              Save
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
</template>

<script>
  export default {
    name: 'RoleForm',
    props: {
      /**
       * Visible status of dialog
       */
      visible: {
        type: Boolean,
        default: false,
      }
    },
    data(){
      return {
        title: 'Create New Role'
      }
    },
    methods: {
      /**
       * Close dialog
       */
      closeDialog(){
        this.$emit('update:visible', false);
      }
    }
  }
</script>

<style scoped>
</style>