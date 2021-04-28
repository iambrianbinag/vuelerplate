/**
 * Helper functions
 */
 export default {
  /**
   * Get cookie by name
   * 
   * @param {String} cookieName name to search
   * @returns {String|null}
   */
  getCookie(cookieName) {
    let name = cookieName + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let cookieAttributes = decodedCookie.split(';');
    for(let i = 0; i < cookieAttributes.length; i++) {
      let cookie = cookieAttributes[i];
      while (cookie.charAt(0) == ' ') {
        cookie = cookie.substring(1);
      }
      if (cookie.indexOf(name) == 0) {
        return cookie.substring(name.length, cookie.length);
      }
    }
    return null;
  },
  /**
   * Set cookie by name
   * 
   * @param {String} cookieName name of cookie
   * @param {Mixed} cookieValue value of cookie
   * @param {Number} extensionSeconds seconds to be added in expiration date
   */
  setCookie(cookieName, cookieValue, extensionSeconds) {
    let currentDate = new Date();
    currentDate.setSeconds(currentDate.getSeconds() + extensionSeconds);
    let expires = "expires="+ currentDate.toUTCString();
    document.cookie = cookieName + "=" + cookieValue + ";" + expires + ";path=/";
  },
  /**
   * Delete cookie by name
   * 
   * @param {String} cookieName cookie name to be deleted
   */
  deleteCookie(cookieName) {
    document.cookie = cookieName + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;path=/';
  },
}