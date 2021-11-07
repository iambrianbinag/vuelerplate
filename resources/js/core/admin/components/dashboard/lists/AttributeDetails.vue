<template>
  <v-card 
    class="text-xs-center" 
    height="100%"
  >
    <v-card-text class="d-flex justify-space-between">
        <div>
            <div class="text-h4 mb-2">{{ attributeDetails.value }}</div>{{ attributeDetails.label }}
        </div>
        <div>
            <v-icon size="70">
              {{`mdi-${attributeDetails.icon}`}}
            </v-icon>
        </div>
    </v-card-text>
    <v-card-actions class="primary">
        <v-spacer></v-spacer>
        <div class="subtitle-2">
              <v-btn
                color="white"
                @click="handleMoreInfo" 
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
  export default {
    name: 'AttributeDetails',
    props: {
      attributeDetails: {
        type: Object,
        required: true,
      }
    },
    methods: {
      /**
       * Triggered when more info is clicked
       * 
       * @event click
       * @type {event}
       */
      handleMoreInfo(){
        const pathName = this.attributeDetails.pathName;
        if(pathName){
          this.$router.push({ name: pathName });
        }
      },
    },
  }
</script>

<style scoped>
</style>