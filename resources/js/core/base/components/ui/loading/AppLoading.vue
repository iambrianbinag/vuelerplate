<template>
  <div 
    class="d-flex justify-center align-center"
    :class="{ [heightInVHClass]: true }"
    >
    <v-progress-circular 
      indeterminate 
      color="primary"
    ></v-progress-circular>
  </div>
</template>

<script>
  export default {
    name: 'AppLoading',
    props: {
      /**
       * Height of root element in viewport height
       */
      heightInVH: {
        validator: function(value){
          return [10, 20, 30, 40, 50, 60, 70, 80, 90, 100].includes(value);
        },
      }, 
    },
    computed: {
      heightInVHClass: function(){
        let className = '-h-100vh';
        if(this.heightInVH){
          className = `-h-${this.heightInVH}vh`;
        }

        return className;
      },
    }
  }
</script>