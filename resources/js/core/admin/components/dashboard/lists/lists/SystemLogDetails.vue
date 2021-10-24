<template>
  <v-card
    :color="color"
    dark
  >
    <v-card-title class="text-subtitle-1 pa-2">
      {{ description | capitalize }}
    </v-card-title>
    <v-card-text class="white text--primary pa-2">
      <div class="grey--text ms-4">
        <v-icon
          dense 
          color="grey lighten-1"
        >
          mdi-clock-outline
        </v-icon>
        {{ $moment(data.created_at).format('YYYY-MM-DD hh:mm A') }}
      </div>
      <SystemLogChanges
        :propertiesData="data.changes"
        :isExpansionPanelsOpen="true"
      />
    </v-card-text>
  </v-card>
</template>

<script>
  import SystemLogChanges from '../../../system-log/lists/SystemLogChanges';

  export default {
    name: 'SystemLogDetails',
    components: {
      SystemLogChanges,
    },
    props: {
      /**
       * The system log data
       */
      data: {
        type: Object,
        required: true,
      },
      color: {
        /**
         * The card color
         */
        type: String,
        required: true,
      },
      /**
       * The description of system log
       */
      description: {
        type: String,
        required: true,
      }
    },
  }
</script>

<style scoped>
</style>