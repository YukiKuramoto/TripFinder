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
/******/ 	return __webpack_require__(__webpack_require__.s = 5);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/jquery.tagsinput.js":
/*!******************************************!*\
  !*** ./resources/js/jquery.tagsinput.js ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/*

	jQuery Tags Input Plugin 1.3.3

	Copyright (c) 2011 XOXCO, Inc

	Documentation for this plugin lives here:
	http://xoxco.com/clickable/jquery-tags-input

	Licensed under the MIT license:
	http://www.opensource.org/licenses/mit-license.php

	ben@xoxco.com

*/
(function ($) {
  var delimiter = new Array();
  var tags_callbacks = new Array();

  $.fn.doAutosize = function (o) {
    var minWidth = $(this).data('minwidth'),
        maxWidth = $(this).data('maxwidth'),
        val = '',
        input = $(this),
        testSubject = $('#' + $(this).data('tester_id'));

    if (val === (val = input.val())) {
      return;
    } // Enter new content into testSubject


    var escaped = val.replace(/&/g, '&amp;').replace(/\s/g, ' ').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    testSubject.html(escaped); // Calculate new width + whether to change

    var testerWidth = testSubject.width(),
        newWidth = testerWidth + o.comfortZone >= minWidth ? testerWidth + o.comfortZone : minWidth,
        currentWidth = input.width(),
        isValidWidthChange = newWidth < currentWidth && newWidth >= minWidth || newWidth > minWidth && newWidth < maxWidth; // Animate width

    if (isValidWidthChange) {
      input.width(newWidth);
    }
  };

  $.fn.resetAutosize = function (options) {
    // alert(JSON.stringify(options));
    var minWidth = $(this).data('minwidth') || options.minInputWidth || $(this).width(),
        maxWidth = $(this).data('maxwidth') || options.maxInputWidth || $(this).closest('.tagsinput').width() - options.inputPadding,
        val = '',
        input = $(this),
        testSubject = $('<tester/>').css({
      position: 'absolute',
      top: -9999,
      left: -9999,
      width: 'auto',
      fontSize: input.css('fontSize'),
      fontFamily: input.css('fontFamily'),
      fontWeight: input.css('fontWeight'),
      letterSpacing: input.css('letterSpacing'),
      whiteSpace: 'nowrap'
    }),
        testerId = $(this).attr('id') + '_autosize_tester';

    if (!$('#' + testerId).length > 0) {
      testSubject.attr('id', testerId);
      testSubject.appendTo('body');
    }

    input.data('minwidth', minWidth);
    input.data('maxwidth', maxWidth);
    input.data('tester_id', testerId);
    input.css('width', minWidth);
  };

  $.fn.addTag = function (value, options) {
    options = jQuery.extend({
      focus: false,
      callback: true
    }, options);
    this.each(function () {
      var id = $(this).attr('id');
      var tagslist = $(this).val().split(delimiter[id]);

      if (tagslist[0] == '') {
        tagslist = new Array();
      }

      value = jQuery.trim(value);

      if (options.unique) {
        var skipTag = $(this).tagExist(value);

        if (skipTag == true) {
          //Marks fake input as not_valid to let styling it
          $('#' + id + '_tag').addClass('not_valid');
        }
      } else {
        var skipTag = false;
      }

      if (value != '' && skipTag != true) {
        $('<span>').addClass('tag').append($('<span>').text(value).append('&nbsp;&nbsp;'), $('<a>', {
          href: '#',
          title: 'Removing tag',
          text: 'x'
        }).click(function () {
          return $('#' + id).removeTag(escape(value));
        })).insertBefore('#' + id + '_addTag');
        tagslist.push(value);
        $('#' + id + '_tag').val('');

        if (options.focus) {
          $('#' + id + '_tag').focus();
        } else {
          $('#' + id + '_tag').blur();
        }

        $.fn.tagsInput.updateTagsField(this, tagslist);

        if (options.callback && tags_callbacks[id] && tags_callbacks[id]['onAddTag']) {
          var f = tags_callbacks[id]['onAddTag'];
          f.call(this, value);
        }

        if (tags_callbacks[id] && tags_callbacks[id]['onChange']) {
          var i = tagslist.length;
          var f = tags_callbacks[id]['onChange'];
          f.call(this, $(this), tagslist[i - 1]);
        }
      }
    });
    return false;
  };

  $.fn.removeTag = function (value) {
    value = unescape(value);
    this.each(function () {
      var id = $(this).attr('id');
      var old = $(this).val().split(delimiter[id]);
      $('#' + id + '_tagsinput .tag').remove();
      str = '';

      for (i = 0; i < old.length; i++) {
        if (old[i] != value) {
          str = str + delimiter[id] + old[i];
        }
      }

      $.fn.tagsInput.importTags(this, str);

      if (tags_callbacks[id] && tags_callbacks[id]['onRemoveTag']) {
        var f = tags_callbacks[id]['onRemoveTag'];
        f.call(this, value);
      }
    });
    return false;
  };

  $.fn.tagExist = function (val) {
    var id = $(this).attr('id');
    var tagslist = $(this).val().split(delimiter[id]);
    return jQuery.inArray(val, tagslist) >= 0; //true when tag exists, false when not
  }; // clear all existing tags and import new ones from a string


  $.fn.importTags = function (str) {
    var id = $(this).attr('id');
    $('#' + id + '_tagsinput .tag').remove();
    $.fn.tagsInput.importTags(this, str);
  };

  $.fn.tagsInput = function (options) {
    var settings = jQuery.extend({
      interactive: true,
      defaultText: '投稿にタグをつけよう！',
      minChars: 0,
      width: '300px',
      height: '100px',
      autocomplete: {
        selectFirst: false
      },
      hide: true,
      delimiter: ',',
      unique: true,
      removeWithBackspace: true,
      placeholderColor: '#666666',
      autosize: true,
      comfortZone: 20,
      inputPadding: 6 * 2
    }, options);
    var uniqueIdCounter = 0;
    this.each(function () {
      // If we have already initialized the field, do not do it again
      if (typeof $(this).attr('data-tagsinput-init') !== 'undefined') {
        return;
      } // Mark the field as having been initialized


      $(this).attr('data-tagsinput-init', true);

      if (settings.hide) {
        $(this).hide();
      }

      var id = $(this).attr('id');

      if (!id || delimiter[$(this).attr('id')]) {
        id = $(this).attr('id', 'tags' + new Date().getTime() + uniqueIdCounter++).attr('id');
      }

      var data = jQuery.extend({
        pid: id,
        real_input: '#' + id,
        holder: '#' + id + '_tagsinput',
        input_wrapper: '#' + id + '_addTag',
        fake_input: '#' + id + '_tag'
      }, settings);
      delimiter[id] = data.delimiter;

      if (settings.onAddTag || settings.onRemoveTag || settings.onChange) {
        tags_callbacks[id] = new Array();
        tags_callbacks[id]['onAddTag'] = settings.onAddTag;
        tags_callbacks[id]['onRemoveTag'] = settings.onRemoveTag;
        tags_callbacks[id]['onChange'] = settings.onChange;
      } // 自作エリア


      var $div = $(this).parent().find(".tagsinput");
      $div.attr("id", id + "_tagsinput");
      $div.find(".tagsinput-child").attr("id", id + "_addTag");

      if (settings.interactive) {
        $div.find(".tagsinput-form").attr("id", id + "_tag");
        $div.find(".tagsinput-form").attr("data-default", settings.defaultText);
      } // 自作エリアここまで


      $(data.holder).css('width', settings.width);
      $(data.holder).css('min-height', settings.height);
      $(data.holder).css('height', settings.height);

      if ($(data.real_input).val() != '') {
        $.fn.tagsInput.importTags($(data.real_input), $(data.real_input).val());
      }

      if (settings.interactive) {
        $(data.fake_input).val($(data.fake_input).attr('data-default'));
        $(data.fake_input).css('color', settings.placeholderColor);
        $(data.fake_input).resetAutosize(settings);
        $(data.holder).bind('click', data, function (event) {
          $(event.data.fake_input).focus();
        });
        $(data.fake_input).bind('focus', data, function (event) {
          if ($(event.data.fake_input).val() == $(event.data.fake_input).attr('data-default')) {
            $(event.data.fake_input).val('');
          }

          $(event.data.fake_input).css('color', '#000000');
        });

        if (settings.autocomplete_url != undefined) {
          autocomplete_options = {
            source: settings.autocomplete_url
          };

          for (attrname in settings.autocomplete) {
            autocomplete_options[attrname] = settings.autocomplete[attrname];
          }

          if (jQuery.Autocompleter !== undefined) {
            $(data.fake_input).autocomplete(settings.autocomplete_url, settings.autocomplete);
            $(data.fake_input).bind('result', data, function (event, data, formatted) {
              if (data) {
                $('#' + id).addTag(data[0] + "", {
                  focus: true,
                  unique: settings.unique
                });
              }
            });
          } else if (jQuery.ui.autocomplete !== undefined) {
            $(data.fake_input).autocomplete(autocomplete_options);
            $(data.fake_input).bind('autocompleteselect', data, function (event, ui) {
              $(event.data.real_input).addTag(ui.item.value, {
                focus: true,
                unique: settings.unique
              });
              return false;
            });
          }
        } else {
          // if a user tabs out of the field, create a new tag
          // this is only available if autocomplete is not used.
          $(data.fake_input).bind('blur', data, function (event) {
            var d = $(this).attr('data-default');

            if ($(event.data.fake_input).val() != '' && $(event.data.fake_input).val() != d) {
              if (event.data.minChars <= $(event.data.fake_input).val().length && (!event.data.maxChars || event.data.maxChars >= $(event.data.fake_input).val().length)) $(event.data.real_input).addTag($(event.data.fake_input).val(), {
                focus: true,
                unique: settings.unique
              });
            } else {
              $(event.data.fake_input).val($(event.data.fake_input).attr('data-default'));
              $(event.data.fake_input).css('color', settings.placeholderColor);
            }

            return false;
          });
        } // if user types a default delimiter like comma,semicolon and then create a new tag


        $(data.fake_input).bind('keypress', data, function (event) {
          if (_checkDelimiter(event)) {
            event.preventDefault();
            if (event.data.minChars <= $(event.data.fake_input).val().length && (!event.data.maxChars || event.data.maxChars >= $(event.data.fake_input).val().length)) $(event.data.real_input).addTag($(event.data.fake_input).val(), {
              focus: true,
              unique: settings.unique
            });
            $(event.data.fake_input).resetAutosize(settings);
            return false;
          } else if (event.data.autosize) {
            $(event.data.fake_input).doAutosize(settings);
          }
        }); //Delete last tag on backspace

        data.removeWithBackspace && $(data.fake_input).bind('keydown', function (event) {
          if (event.keyCode == 8 && $(this).val() == '') {
            event.preventDefault();
            var last_tag = $(this).closest('.tagsinput').find('.tag:last').text();
            var id = $(this).attr('id').replace(/_tag$/, '');
            last_tag = last_tag.replace(/[\s]+x$/, '');
            $('#' + id).removeTag(escape(last_tag));
            $(this).trigger('focus');
          }
        });
        $(data.fake_input).blur(); //Removes the not_valid class when user changes the value of the fake input

        if (data.unique) {
          $(data.fake_input).keydown(function (event) {
            if (event.keyCode == 8 || String.fromCharCode(event.which).match(/\w+|[áéíóúÁÉÍÓÚñÑ,/]+/)) {
              $(this).removeClass('not_valid');
            }
          });
        }
      } // if settings.interactive

    });
    return this;
  };

  $.fn.tagsInput.updateTagsField = function (obj, tagslist) {
    var id = $(obj).attr('id');
    $(obj).val(tagslist.join(delimiter[id]));
  };

  $.fn.tagsInput.importTags = function (obj, val) {
    $(obj).val('');
    var id = $(obj).attr('id');
    var tags = val.split(delimiter[id]);

    for (i = 0; i < tags.length; i++) {
      $(obj).addTag(tags[i], {
        focus: false,
        callback: false
      });
    }

    if (tags_callbacks[id] && tags_callbacks[id]['onChange']) {
      var f = tags_callbacks[id]['onChange'];
      f.call(obj, obj, tags[i]);
    }
  };
  /**
    * check delimiter Array
    * @param event
    * @returns {boolean}
    * @private
    */


  var _checkDelimiter = function _checkDelimiter(event) {
    var found = false;

    if (event.which == 13) {
      return true;
    }

    if (typeof event.data.delimiter === 'string') {
      if (event.which == event.data.delimiter.charCodeAt(0)) {
        found = true;
      }
    } else {
      $.each(event.data.delimiter, function (index, delimiter) {
        if (event.which == delimiter.charCodeAt(0)) {
          found = true;
        }
      });
    }

    return found;
  };
})(jQuery);

$(function () {
  $('.hash-tag').tagsInput({
    width: '100%'
  });
});

/***/ }),

/***/ 5:
/*!************************************************!*\
  !*** multi ./resources/js/jquery.tagsinput.js ***!
  \************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Users/kuramotoyuuki/workspace/TechBoost/TripFinder/resources/js/jquery.tagsinput.js */"./resources/js/jquery.tagsinput.js");


/***/ })

/******/ });