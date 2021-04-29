const TOKEN_KEY = 'access_token';
const TOKEN_KEY_EXIRATION_DATE = 'access_token_expiration_date';
const EXTENSION_SECONDS_DEFAULT = 31556952; // 1 year is the default extension

import helpers from '../helpers';

/**
 * Token service
 */
const token = {
  /**
   * Get stored token
   * 
   * @returns {string|null}
   */
  getToken() {
    return helpers.getCookie(TOKEN_KEY);
  },
  /**
   * Get stored token's expiration date
   * 
   * @returns {string|null}
   */
  getTokenExpirationDate(){
    return helpers.getCookie(TOKEN_KEY_EXIRATION_DATE);
  },
  /**
   * Store token
   * 
   * @param {string} accessToken token's value 
   * @param {number} extensionSeconds seconds to be added at current date's expiration
   */
  saveToken(accessToken, extensionSeconds = EXTENSION_SECONDS_DEFAULT){ 
    helpers.setCookie(TOKEN_KEY, accessToken, extensionSeconds);
  },
  /**
   * Store token's expiration date
   * 
   * @param {string} expirationDate token expiration date's value 
   * @param {number} extensionSeconds seconds to be added at current date's expiration
   */
  saveTokenExpirationDate(expirationDate, extensionSeconds = EXTENSION_SECONDS_DEFAULT){
    helpers.setCookie(TOKEN_KEY_EXIRATION_DATE, expirationDate, extensionSeconds)
  },
  /**
   * Remove stored token
   */
  removeToken(){
    helpers.deleteCookie(TOKEN_KEY);
  },
  /**
   * Remove stored token's expiration date
   */
  removeTokenExpirationDate(){
    helpers.deleteCookie(TOKEN_KEY_EXIRATION_DATE);
  }
}

export default token;