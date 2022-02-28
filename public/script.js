(function webpackUniversalModuleDefinition(root, factory) {
	if(typeof exports === 'object' && typeof module === 'object')
		module.exports = factory();
	else if(typeof define === 'function' && define.amd)
		define([], factory);
	else if(typeof exports === 'object')
		exports["blackjack-sdc-digrev-header"] = factory();
	else
		root["blackjack-sdc-digrev-header"] = factory();
})(this, function() {
return /******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};

/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {

/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;

/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			exports: {},
/******/ 			id: moduleId,
/******/ 			loaded: false
/******/ 		};

/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);

/******/ 		// Flag the module as loaded
/******/ 		module.loaded = true;

/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}


/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;

/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;

/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";

/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports) {

	// BLACKJACK-DIGREV-HEADER
	// ===================
	// INITIALISE COMPONENT
	// @param rootElement {htmlNode} root dom element of component
	'use strict';

	Object.defineProperty(exports, '__esModule', {
	  value: true
	});

	exports['default'] = function (rootElement) {
		console.log('root')
	  var menus = Array.prototype.slice.call(rootElement.querySelectorAll('.site-nav-desktop__item-link--more'));
	  var lastLinks = Array.prototype.slice.call(rootElement.querySelectorAll('.site-nav-desktop__menu-links li:last-of-type > a'));

	  var mainNav = rootElement.querySelector('[data-role="main-nav"]');
	  var mainNavOpen = rootElement.querySelector('[data-role="open-left-hand-nav"]');

	  menus.forEach(function (item) {
	    item.onclick = function (e) {
	      e.preventDefault();

	      if (this.classList.contains('site-nav-desktop__item--open')) {
	        this.classList.remove('site-nav-desktop__item--open');
	      } else {
	        closeSubMenus();
	        this.classList.add('site-nav-desktop__item--open');
	      }
	    };
	  });

	  lastLinks.forEach(function (item) {
	    item.onblur = function () {
	      closeSubMenus();
	    };
	  });

	  mainNavOpen.onclick = function (e) {
	    e.preventDefault();
	    var expanded = mainNavOpen.getAttribute('aria-expanded');
	    closeMainMenu(expanded);
	  };

	  function closeSubMenus() {
	    menus.forEach(function (item) {
	      item.classList.remove('site-nav-desktop__item--open');
	    });
	  }

	  function closeMainMenu(close) {
	    if (close === 'true') {
	      mainNav.setAttribute('aria-hidden', 'true');
	      mainNavOpen.setAttribute('aria-expanded', false);
	    } else {
	      mainNav.setAttribute('aria-hidden', 'false');
	      mainNavOpen.setAttribute('aria-expanded', true);
	    }
	  }

	  // close sub menus if clicked off
	  document.body.addEventListener('click', function (e) {
	    if (!document.querySelector('.site-header').contains(e.target)) {
	      closeSubMenus();
	    }
	  });

	  // close main menu if clicked off
	  document.body.addEventListener('click', function (e) {
	    if (!mainNav.contains(e.target) && !mainNavOpen.contains(e.target)) {
	      closeMainMenu('true');
	    }
	  });

	  // SITE LOGIN - take from - https://git.bskyb.com/ssdm-design/core/edit/develop/js/modules/skyid-login.js
	  // globals - SKY_SPORTS
	  (function () {
	    var skyid = {

	      init: function init(element) {
	        this.rootElement = element;

	        if (!window.SKY_SPORTS || !window.SKY_SPORTS.user || !this.rootElement) {
	          // console.log('The SKY_SPORTS or SKY_SPORTS.user global is undefined');
	          return;
	        }

	        this.setLoginBoxContent(window.SKY_SPORTS.user);
	      },

	      /**
	       * Set login box content
	       * @param  {HTMLElement}  element
	       * @param  {Object}  [options]
	       */
	      setLoginBoxContent: function setLoginBoxContent(userObj) {
	        var rootElement = this.rootElement; // eslint-disable-line no-shadow
	        var mobileEl = rootElement.querySelector('.login-text[data-role="isMobile"]');
	        var wholesaleEl = rootElement.querySelector('.login-text[data-role="isWholesale"]');
	        var skySportsEl = rootElement.querySelector('.login-text[data-role="isSkySports"]');
	        var skyEl = rootElement.querySelector('.login-text[data-role="isSky"]');
	        var nowTvEl = rootElement.querySelector('.login-text[data-role="isNowTv"]');
	        var loggedInEl = rootElement.querySelector('.login-text[data-role="isLoggedIn"]');
	        var defaultEl = rootElement.querySelector('.login-text[data-role="default"]');
	        var html = '';

	        if ((window.SKY_SPORTS.device.ios || window.SKY_SPORTS.device.android) && mobileEl) {
	          html = mobileEl.textContent;
	        } else if (userObj.isWholesale) {
	          if (wholesaleEl) {
	            html = wholesaleEl.textContent;
	          }
	        } else if (userObj.isSkySports) {
	          if (skySportsEl) {
	            html = skySportsEl.textContent;
	          }
	        } else if (userObj.isSky) {
	          if (skyEl) {
	            html = skyEl.textContent;
	          }
	        } else if (userObj.isNowTV) {
	          if (nowTvEl) {
	            html = nowTvEl.textContent;
	          }
	        } else if (userObj.isLoggedIn) {
	          if (loggedInEl) {
	            html = loggedInEl.textContent;
	          }
	        } else if (defaultEl) {
	          if (defaultEl) {
	            html = defaultEl.textContent;
	          }
	        }

	        rootElement.innerHTML = html.replace(/\#\{name\}/, userObj.name); // eslint-disable-line no-useless-escape
	      }

	    };

	    var loginBoxes = document.querySelectorAll('[data-role="site-login"]');
	    for (var i = -1; ++i < loginBoxes.length;) {
	      skyid.init(loginBoxes[i]);
	    }
	  })();
	};

	module.exports = exports['default'];
	// @param options {object} object of optional parameters for component

/***/ })
/******/ ])
});
;