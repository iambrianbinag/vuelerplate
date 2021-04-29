/**
 * @mixin 
 */
 export default {
  filters: {
    /**
     * Capitalize text
     * 
     * @param {string} value to be capitalized
     * @returns {string}
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
     * @param {number} seconds seconds to be added at current date
     * @returns {date}
     */
    addSecondsAtCurrentDate(seconds = 0){
      let currentDate = new Date();
      currentDate.setSeconds(currentDate.getSeconds() + seconds);
      
      return currentDate;
    }
  },
}