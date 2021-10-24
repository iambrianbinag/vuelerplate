<template>
  <v-card 
      class="text-xs-center" 
      height="100%"
    >
      <v-card-title class="primary white--text">Activity Log</v-card-title>
      <v-timeline dense>
        <v-slide-x-reverse-transition
          group
          hide-on-leave
        >
          <v-timeline-item
            v-for="systemLog in systemLogData"
            :key="systemLog.id"
            :color="getSystemLogAttributeByActionType(systemLog.description).color"
            :icon="getSystemLogAttributeByActionType(systemLog.description).icon"
            small
            fill-dot
          >
            <SystemLogDetails
              :data="systemLog"
              :color="getSystemLogAttributeByActionType(systemLog.description).color"
              :description="formatActivityLogDescription(systemLog.causer ? systemLog.causer.name : null, systemLog.description, systemLog.log_name)" 
            />
          </v-timeline-item>
        </v-slide-x-reverse-transition>
      </v-timeline>
    <v-card-actions class="primary">
        <v-spacer></v-spacer>
        <div class="subtitle-2">
          <v-btn
            color="white" 
            text 
            small
          >
            More Info
            <v-icon large>
              mdi-chevron-right
            </v-icon>
          </v-btn>
        </div> 
        <v-spacer></v-spacer>
    </v-card-actions>
  </v-card>
</template>

<script>
  import SystemLogDetails from './lists/SystemLogDetails';

  const SYSTEM_LOG_ATTRIBUTES = {
    created: {
      color: 'success',
      icon: 'mdi-check-circle',
    },
    updated: {
      color: 'info',
      icon: 'mdi-information',
    },
    deleted: {
      color: 'error',
      icon: 'mdi-alert',
    },
    viewed: {
      color: 'warning',
      icon: 'mdi-eye',
    }
  };

  export default {
    name: 'SystemLog',
    components: {
      SystemLogDetails,
    },
    props: {
      systemLogData: {
        type: Array,
        default: [],
      }
    },
    methods: {
      /**
       * Get the attributes of system log based on given action type
       */
      getSystemLogAttributeByActionType(actionTypeName){
        return SYSTEM_LOG_ATTRIBUTES[actionTypeName];
      },
      /**
       * Format description of activity log based on given values
       */
      formatActivityLogDescription(causerName, description, logName){
        const defaultCauserName = 'Admin';
        return `${causerName || defaultCauserName} ${description} a ${logName}`;
      },
    }
  }
</script>

<style scoped>
</style>