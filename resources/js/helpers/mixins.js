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
    },
    /**
     * Check if object is empty
     * 
     * @param {object} object to be check
     * @returns {boolean}
     */
    isObjectEmpty(object = {}){
      if(!object){
        return true;
      }

      return Object.keys(object).length === 0 ? true : false;
    },
    /**
     * Generate URL parameters based on given object
     * 
     * @param {object} params to iterate and to generate url parameter 
     * @returns {string}
     */
    generateUrlParams(params = {}){
      let urlParams = '';

      for(let [key, value] of Object.entries(params)){
        urlParams += urlParams === '' ? `?${key}=${value}` : `&${key}=${value}`;
      }

      return urlParams;
    }
  },
}