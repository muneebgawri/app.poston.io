/*=========================================================================================
    File Name: nav.js
    Description: Navigation available in Bootstrap share general markup and styles, from the base .nav class to the active and disabled states. Swap modifier classes to switch between each style.
    ----------------------------------------------------------------------------------------

==========================================================================================*/
(function (window, document, $) {
  'use strict';

  // add class pill-cotainer with pill componet for styling
  if ($('.nav.nav-pills').length > 0) {
    $('.nav-pills').addClass('pill-container');
  }

})(window, document, jQuery);
