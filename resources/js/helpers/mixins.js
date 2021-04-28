/**
 * @mixin 
 */
 export default {
  filters: {
    /**
     * Capitalize text
     * 
     * @param {String} value to be capitalized
     * @returns {String}
     */
    capitalize: function(value){
      if(!value){
        return;
      }
      value = value.toString();
      
      return value.charAt(0).toUpperCase() + value.slice(1);
    },
  },
  methods: {
    /**
     * Add seconds at current date
     * 
     * @param {Number} seconds seconds to be added at current date
     * @returns {Date}
     */
    addSecondsAtCurrentDate(seconds = 0){
      let currentDate = new Date();
      currentDate.setSeconds(currentDate.getSeconds() + seconds);
      
      return currentDate;
    }
  },
}