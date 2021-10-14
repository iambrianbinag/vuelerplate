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
    /**
     * Set string to uppercase
     * 
     * @param {string} value to be set to uppercase
     * @returns {string}
     */
    upperCase: function(value){
      return value.toUpperCase();
    }
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
    },

    /**
     * Sort array by object's property
     * 
     * @param {array} array to be sorted
     * @param {string} key to be used in sorting
     * @param {string} orderBy - order by ascending or descending
     * @returns {array}
     */
     sortArrayByKey(array = [], key = null, orderBy = 'asc'){
      const sortedArray = array.sort((a, b) => {
        return orderBy == 'asc' ? a[key] - b[key] : b[key] - a[key];
      });

      return sortedArray;
    },
    /**
     *  Pluck array by object key
     * 
     * @param {array} array to be plucked 
     * @param {string} key to be used in pluck
     * @returns {array}
     */
    pluckArrayByKey(array = [], key = null){
      const pluckedArrayByKey = array.map((objectValue) => {
        return objectValue[key];
      });

      return pluckedArrayByKey;
    }
  },
}