<template>
  <v-snackbar
    :value="snackbar.show"
    :timeout="-1"
    :color="snackbar.color"
    :min-width="minAndMaxWidth"
    :max-width="minAndMaxWidth"
    class="snackBar"
    absolute
    outlined
    top
    :centered="isMobile"
    :right="!isMobile"
  >
    <div v-if="snackbar.message">
      {{ snackbar.message }}  
    </div>
    <div 
      v-for="(message, index) in snackbar.messages" 
      :key="index"
    >
      {{ message }}  
    </div>
    <template v-slot:action="{ attrs }">
      <v-btn
        icon
        v-bind="attrs"
        @click="handleClose"
        color="primary"
      >
        <v-icon>mdi-close-circle-outline</v-icon>
      </v-btn>
    </template>
  </v-snackbar>
</template>

<script>
  import { mapGetters, mapActions  } from 'vuex';

  export default {
    name: 'SnackBar',
    computed: {
      ...mapGetters('base.system', [
        'snackbar'
      ]),
      minAndMaxWidth: function(){
        return this.$vuetify.breakpoint.xsOnly ? '95%' : '344';
      },
      isMobile: function(){
        return this.$vuetify.breakpoint.xsOnly;
      }
    },
    watch: {
      snackbar: {
        handler(value){
          if(value.show == true){
            setTimeout(() => this.resetSnackbar(), value.timeout || 5000);
          }
        },
        deep: true,
      }
    },
    methods: {
      ...mapActions('base.system', [
        'resetSnackbar'
      ]),
      /**
       * Triggered when close button is clicked
       * 
       * @event click
       * @type {Event}
       */
      handleClose(){
        this.resetSnackbar();
      }
    },
  }
</script>

<style lang="scss" scoped>
  .snackBar {
    z-index: 10000;
  }
</style>