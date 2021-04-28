<template>
  <v-snackbar
    :value="serverError.isError"
    :timeout="-1"
    color="error"
    :min-width="minAndMaxWidth"
    :max-width="minAndMaxWidth"
    absolute
    outlined
    top
    :centered="isMobile"
    :right="!isMobile"
  >
    <div 
      v-for="(error, index) in serverError.messages" 
      :key="index"
    >
      {{ error }}  
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
  import { mapGetters, mapMutations  } from 'vuex';

  export default {
    name: 'SnackBar',
    data(){
      return {
        //
      }
    },
    computed: {
      ...mapGetters('base.system', ['serverError']),
      minAndMaxWidth: function(){
        return this.$vuetify.breakpoint.xsOnly ? '95%' : '344';
      },
      isMobile: function(){
        return this.$vuetify.breakpoint.xsOnly;
      }
    },
    watch: {
      //
    },
    methods: {
      ...mapMutations('base.system', ['resetServerError']),
      /**
       * Triggered when close button is clicked
       * 
       * @event click
       * @type {Event}
       */
      handleClose(e){
        this.resetServerError();
      }
    },
    mounted(){
      //
    }
  }
</script>