<template>
  <div class="pa-2">
    <v-expansion-panels
      v-for="(propertyGroupValue, propertyGroupName, index) in propertiesData"
      :key="index"
      class="mb-2" 
      accordion 
      focusable
    >
      <v-expansion-panel>
        <v-expansion-panel-header>
          {{ getPropertyGroupLabel(propertyGroupName) | upperCase }}
        </v-expansion-panel-header>
        <v-expansion-panel-content>
          <v-row
            class="mt-2" 
            dense
          >
            <v-col
              v-for="(propertyValue, propertyName, index) in propertyGroupValue"
              :key="index"
              cols="12"
            >
              <div class="d-flex">
                <div class="mr-1">
                  {{ `${propertyName}: ` | capitalize }}
                </div>
                <div>
                  <template v-if="Array.isArray(propertyValue)" >
                    <v-chip
                      v-for="(propertyArrayValue, index) in propertyValue"
                      :key="index"
                      class="ma-1"
                      small
                    >
                      {{ propertyArrayValue }}
                    </v-chip>
                  </template>
                  <template v-else>
                    {{ propertyValue }}
                  </template>
                </div>
              </div>
            </v-col>
          </v-row>
        </v-expansion-panel-content>
      </v-expansion-panel>
    </v-expansion-panels>
  </div>
</template>

<script>
  const PROPERTIES = {
    attributes: { label: 'New'},
    old: { label: 'Old'},
  };

  const PROPERTIES_REQUIRED = Object.keys(PROPERTIES);

  export default {
    name: 'SystemLogChanges',
    props: {
      /**
       * The changes properties data
       */
      propertiesData: {
        type: Object,
        required: true,
        validator: function(value){
          for(const property in value){
            if(value.hasOwnProperty(property)){
              return PROPERTIES_REQUIRED.indexOf(property) !== -1;
            }
          }
        },
      }
    },
    methods: {
      /**
       * Get group label based on given group name
       */
      getPropertyGroupLabel(groupName){
        return PROPERTIES[groupName].label;
      }
    }
  }
</script>

<style scoped>
</style>
