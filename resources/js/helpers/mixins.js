/**
 * @mixin 
 */
 export default {
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