<template>
  <v-sheet
    color="white"
    :elevation="elevationValue"
    class="d-flex justify-left align-center pa-1"
    :class="{ 'mb-2': gutter, }"
  >
    <BackButton v-if="backButton"/>
    <div class="d-inline-block text-h6 mx-1">
      {{ title }}
    </div>
  </v-sheet>
</template>

<script>
  import BackButton from '../buttons/BackButton';

  export default {
    name: 'AppHeader',
    components: { BackButton },
    props: {
      title: String,
      gutter: {
        type: Boolean,
        default: true,
      },
      backButton: {
        type: Boolean,
        default: true,
      },
      elevation: {
        type: Boolean,
        default: true,
      },
    },
    computed: {
      elevationValue: function(){
        let value = 1;
        if(!this.elevation){
          value = 0;
        }

        return value;
      }
    },
  }
</script>

<style scoped>
</style>
