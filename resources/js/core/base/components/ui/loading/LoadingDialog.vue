<template>
  <v-dialog
    :value="true"
    hide-overlay
    persistent
    width="300"
  >
    <v-card
      color="primary"
      dark
    >
      <v-card-text>
       {{ description }}
        <v-progress-linear
          indeterminate
          color="white"
          class="mb-0"
        ></v-progress-linear>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>

<script>
  export default {
    name: 'LoadingDialog',
    props: {
      /**
       * Desciption of loading text
       */
      description: {
        type: String,
        default: 'Loading...',
      }
    }
  }
</script>

<style scoped>
</style>