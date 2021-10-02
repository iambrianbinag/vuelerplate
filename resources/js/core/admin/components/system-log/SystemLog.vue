<template>
  <v-container>
    <SystemLogTable/>
  </v-container>
</template>

<script>
  import SystemLogTable from './tables/SystemLogTable';

  export default {
    name: 'LogViewDialog',
    components: { SystemLogTable },
  }
</script>

<style scoped>
</style>