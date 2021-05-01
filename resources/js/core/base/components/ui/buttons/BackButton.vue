<template>
  <v-btn
    icon
    small
    color="secondary"
    @click="handleGoBack"
  >
    <v-icon>mdi-arrow-left</v-icon>
  </v-btn>
</template>

<script>
  export default {
    name: 'BackButton',
    methods: {
      /**
       * Go back to previous route
       * 
       * @event click
       * @type {event}
       */
      handleGoBack(){
        this.$router.go(-1);
      }
    },
  }
</script>

<style scoped>
</style>