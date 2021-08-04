/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/main.js":
/*!******************************!*\
  !*** ./resources/js/main.js ***!
  \******************************/
/*! no static exports found */
/***/ (function(module, exports) {

/* jshint curly:true, debug:true */

/* globals $ */
// ログイン、新規登録関連操作
$('#sign-in').on('click', function (e) {
  e.preventDefault();
  $('.modal-title').text('新規登録');
  $('#button-signin').show();
  $('#button-login').hide();
});
$('#log-in').on('click', function (e) {
  e.preventDefault();
  $('.modal-title').text('ログイン');
  $('#button-login').show();
  $('#button-signin').hide();
});
$('.btn-submit').click(function () {
  $(this).parents('form').attr('action', $(this).data('action'));
  $(this).parents('form').submit();
}); // プラン投稿画面

new Vue({
  el: '#post-page',
  data: function data() {
    return {
      dayInfo: [{
        dayKey: 0,
        spotInfo: [{
          spotKey: 0,
          spotDisplay: ''
        }]
      }],
      contentsInfo: [{
        contentsStatus: 'IS_NEW',
        contentsKey: 0,
        daykey: 0,
        spotkey: 0,
        spotImage: []
      }],
      currentNum: 0,
      planOutline: {}
    };
  },
  computed: {
    _listStyle: function _listStyle() {
      return {
        transition: '',
        transform: "translatex(".concat(-100 * this.currentNum, "%)")
      };
    },
    IS_NEW: function IS_NEW(Status) {
      return Status === 'IS_NEW';
    },
    IS_SELECTED: function IS_SELECTED(Status) {
      return Status === 'IS_SELECTED';
    }
  },
  methods: {
    carouselMove: function carouselMove(spotKey) {
      this.currentNum = spotKey + 1;
    },
    addDay: function addDay() {
      var newDaykey = this.dayInfo.length;
      this.dayInfo.push({
        dayKey: newDaykey,
        spotInfo: [{}]
      });
      this.contentsInfo.push({});
      this.assignKey();
    },
    deleteDay: function deleteDay() {
      var targetIndex_day = this.dayInfo.length - 1;
      var targetIndex_content = this.dayInfo[targetIndex_day].spotInfo[0].spotKey;
      var targetCount = this.dayInfo[targetIndex_day].spotInfo.length;

      if (targetIndex_day == 0) {
        return;
      }

      this.contentsInfo.splice(targetIndex_content, targetCount);
      this.dayInfo.splice(targetIndex_day, 1);
      var spotArray = this.dayInfo[this.dayInfo.length - 1].spotInfo;
      this.currentNum = spotArray[spotArray.length - 1].spotKey + 1;
    },
    addSpot: function addSpot(dayIndex, spotIndex) {
      this.dayInfo[dayIndex].spotInfo.splice(spotIndex + 1, 0, {
        spotDisplay: 'display: block;'
      });
      this.contentsInfo.splice(spotIndex + 1, 0, {
        daykey: dayIndex,
        contentsTitle: "AddedPage".concat(spotIndex + 1),
        contentsStatus: 'IS_NEW'
      });
      this.assignKey();
    },
    deleteSpot: function deleteSpot(dayKey, spotKey) {
      var spotArray = this.dayInfo[dayKey].spotInfo;
      var contentsArray = this.contentsInfo;
      var target;

      if (spotArray.length !== 1) {
        for (var i = 0; i < spotArray.length; i++) {
          if (spotArray[i].spotKey == spotKey) {
            target = i;
          }
        }

        spotArray.splice(target, 1);

        for (var _i = 0; _i < contentsArray.length; _i++) {
          if (contentsArray[_i].daykey == dayKey && contentsArray[_i].spotkey == spotKey) {
            contentsArray.splice(_i, 1);
          }
        }
      } else {
        return;
      }

      this.assignKey();
    },
    assignKey: function assignKey() {
      var dayArray = this.dayInfo;
      var keyCount = 0;

      for (var i = 0; i < dayArray.length; i++) {
        for (var j = 0; j < dayArray[i].spotInfo.length; j++) {
          dayArray[i].spotInfo[j].spotKey = keyCount;
          this.contentsInfo[keyCount].contentsKey = keyCount;
          this.contentsInfo[keyCount].spotkey = keyCount;
          this.contentsInfo[keyCount].daykey = i;
          keyCount++;
        }
      }
    },
    SelectImage: function SelectImage(Image, Target, Status) {
      Image = Target;
      this.contentsInfo[0].spotImage = Target;
      console.log(Target);
      console.log(this.contentsInfo[0].spotImage);
    },
    showSpot: function showSpot(e) {
      e.preventDefault();
      var content = $(e.target).closest('section').find('.accordion-content');

      if (!content.is(':visible')) {
        $('.accordion-content:visible').slideUp();
        content.slideDown();
      }
    }
  },
  template: "\n    <div id=\"post-body\">\n        <div id=\"post-menu\">\n            <div>\n                <div id=\"button-area\">\n                    <button type=\"submit\" id=\"dayadd-button\" class=\"btn btn-secondary btn-submit\" v-on:click=\"addDay\">Add Day</button>\n                    <button type=\"submit\" id=\"daydelete-button\" class=\"btn btn-secondary btn-submit\" v-on:click=\"deleteDay\">Delete Day</button>\n                </div>\n                <section>\n                    <h2 class=\"accordion-title\"><a href=\"#\" v-on:click=\"showSpot\">PlanOutline</a></h2>\n                    <div class=\"accordion-content accordion-content-active\" v-on:click=\"carouselMove(-1)\">PlanOutline</div>\n                </section>\n                <section v-for=\"day in dayInfo\">\n                    <h2 class=\"accordion-title\"><a href=\"#!\" v-on:click=\"showSpot\">Day{{ day.dayKey + 1 }}</a></h2>\n                    <div v-for=\"spot in day.spotInfo\">\n                      <div class=\"accordion-content\" v-bind:style=\"spot.spotDisplay\" v-on:click=\"carouselMove(spot.spotKey)\">\n                        <div class=\"accordion-content-area\">\n                          <div class=\"spot-area\">\n                            Spot{{ spot.spotKey + 1 }}\n                          </div>\n                          <div class=\"icon-area\">\n                            <i class=\"bi bi-plus-square plus-button\" v-on:click=\"addSpot(day.dayKey, spot.spotKey)\"></i>\n                            <i class=\"bi bi-dash-square minus-button\" v-on:click=\"deleteSpot(day.dayKey, spot.spotKey)\"></i>\n                          </div>\n                        </div>\n                      </div>\n                    </div>\n                </section>\n            </div>\n        </div>\n        <form id=\"post-form\">\n            <div>\n              <div class=\"carousel\">\n                <div class=\"list\" v-bind:style=\"_listStyle\">\n                  <div class=\"list__item\">\n                    <div class=\"item\">\n                      <div class=\"content-title\">PlanOutline</div>\n                        <div class=\"title-area\">\n                          \u30D7\u30E9\u30F3\u30BF\u30A4\u30C8\u30EB\uFF1A\n                          <input type=\"text\"\n                          v-on:input=\"planOutline.planTitle = $event.target.value\"\n                          v-bind:value=\"planOutline.planTitle\">\n                        </div>\n                        <div class=\"plan-tag-area\">\n                          <div class=\"plan-textarea-title\">\n                            \u30CF\u30C3\u30B7\u30E5\u30BF\u30B0\n                          </div>\n                          <textarea\n                          v-on:input=\"planOutline.hashTag = $event.target.value\"\n                          v-bind:value=\"planOutline.hashTag\"\n                          placeholder=\" #\u65E5\u5E30\u308A  #\u304A\u3057\u3083\u308C\u30AB\u30D5\u30A7  #\u30C7\u30FC\u30C8\">\n                          </textarea>\n                        </div>\n                        <div class=\"plan-information-area\">\n                          <div class=\"plan-textarea-title\">\n                            PLAN INFORMATION\n                          </div>\n                          <textarea class=\"plan-information-textarea\"\n                          v-on:input=\"planOutline.information = $event.target.value\"\n                          v-bind:value=\"planOutline.information\"\n                          placeholder=\"\u30D7\u30E9\u30F3\u306E\u898B\u6240\u3092\u6295\u7A3F\u3057\u3088\u3046\uFF01\">\n                          </textarea>\n                        </div>\n                    </div>\n                  </div>\n                  <template class=\"spot-content\" v-for=\"content in contentsInfo\">\n                    <div class=\"list__item\">\n                      <div class=\"item\">\n                        <div class=\"content-title\">Spot{{ content.contentsKey + 1 }}</div>\n                          <div class=\"title-area\">\n                            \u30B9\u30DD\u30C3\u30C8\u30BF\u30A4\u30C8\u30EB\uFF1A \n                            <input type=\"text\"\n                            v-on:input=\"content.contentsTitle = $event.target.value\" \n                            v-bind:value=\"content.contentsTitle\">\n                          </div>\n                          <div class=\"spot-detail-wrapper\">\n                            <div class=\"spot-detail-area\">\n                              <div class=\"spot-stay-area\">\n                                <div class=\"spot-area-title\">\n                                  \u6EDE\u5728\u6642\u9593\uFF1A\n                                </div>\n                                <input type=\"text\"\n                                v-on:input=\"content.contentsStay = $event.target.value\" \n                                v-bind:value=\"content.contentsStay\">\n                              </div>\n                              <div class=\"spot-address-area\">\n                                <div class=\"spot-area-title\">\n                                  \u6240\u5728\u5730\uFF1A\n                                </div>\n                                <input type=\"text\"\n                                v-on:input=\"content.contentsAddress = $event.target.value\" \n                                v-bind:value=\"content.contentsAddress\">\n                              </div>\n                              <div class=\"spot-tag-area\">\n                                <div>\n                                  \u30B9\u30DD\u30C3\u30C8\u30BF\u30B0\n                                </div>\n                                <textarea\n                                v-on:input=\"content.contentsTag = $event.target.value\" \n                                v-bind:value=\"content.contentsTag\"\n                                placeholder=\"\u30BF\u30B0\u3092\u3064\u3051\u3088\u3046\uFF01\">\n                                </textarea>\n                              </div>\n                            </div>\n                            <div class=\"spot-image-area\">\n                              <div class=\"spot-image-select\">\n                                \u5199\u771F\u3092\u6295\u7A3F:\n                                <input type=\"file\"\n                                class=\"file-select\"\n                                v-on:input=\"SelectImage(content.spotImage, $event.target.value, content.contentsStatus)\"\n                                v-bind:value=\"content.spotImage\"\n                                multiple=\"multiple\">\n                              </div>\n                              <div class=\"spot-image-view\">\n                                <img v-if=\"content.contentsStatus === 'IS_NEW'\" src=\"../../image/sample.png\">\n                                <img v-else-if=\"content.contentsStatus === 'IS_NEW'\" v-bind:src=\"content.SpotImage[0]\">\n                              </div>\n                            </div>\n                          </div>\n                          <div class=\"spot-information-area\">\n                            <div>\n                              SPOT INFORMATION\n                            </div>\n                            <textarea class=\"spot-information-textarea\"\n                            v-on:input=\"content.contentsInfo = $event.target.value\"\n                            v-bind:value=\"content.contentsInfo\"\n                            placeholder=\"\u30B9\u30DD\u30C3\u30C8\u306E\u60C5\u5831\u3092\u6295\u7A3F\u3057\u3088\u3046\uFF01\" >\n                            </textarea>\n                          </div>\n                      </div>\n                    </div>\n                  </template>\n                </div>\n                <div class=\"submit-button-area\">\n                  <a href=\"#\" class=\"btn btn-success post-submit-button\">\u6295\u7A3F\u3059\u308B</a>\n                </div>\n              </div>\n            </div>\n        </form>\n    </div>\n  "
});

/***/ }),

/***/ 1:
/*!************************************!*\
  !*** multi ./resources/js/main.js ***!
  \************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /home/ec2-user/environment/TripFinder/resources/js/main.js */"./resources/js/main.js");


/***/ })

/******/ });