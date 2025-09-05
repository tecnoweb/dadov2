require.config({
  baseUrl: window.baseurl + '/js/',
  urlArgs: 'bust=' + window.requireJsUnique,
  locale: window.locale,
  templatesPath: 'templates/',
  paths: {
  	'jquery': 'lib/jquery/jquery-1.10.2',
  	'jqueryui': 'lib/jquery/jquery-ui.min',
  	'jqueryte': 'lib/jquery/te/jquery-te-1.4.0.min',
  	'jquerydatetimepicker': 'lib/jquery/jquery.datetimepicker',
  	'text': 'lib/require/plugins/text',
  	'template': 'lib/require/plugins/template',
  	'async': 'lib/require/plugins/async',
  	'underscore': 'lib/underscore/underscore',
  	'backbone': 'lib/backbone/backbone-min',
  	'paginator': 'lib/backbone/backbone-pageable.min',
  	'vcollection': 'lib/backbone/backbone.virtual-collection',
  	'require_plugins': 'lib/require/plugins',
  	'fullcalendar': 'lib/fullcalendar-1.6.4/fullcalendar',
  	'timepicker': 'lib/jquery/jquery-ui-timepicker-addon',
  	'dropdown': 'lib/jquery/dropdown-menu.min',
  	'jquery_iframe': 'lib/jquery/jquery.iframe-transport',
    'addresspicker': 'lib/jquery/jquery.ui.addresspicker',
    'timezonedetect': 'lib/jquery/jstz-1.0.4.min',
    'timezonepicker': 'lib/jquery/jquery.timezone-picker',
    'moment': 'lib/moment/moment.min',
    'marionette': 'lib/backbone/backbone.marionette.min',
    'angular': 'lib/angular/angular.min',
    'angularresource': 'lib/angular/angular-resource.min',
    'angulartree': 'lib/angular/angular-ui-tree',
    'angulartreeview': 'lib/angular/angular-tree-control',
    'angularrouter': 'lib/angular/angular-ui-router',
    'angularbootstrap': 'lib/angular/ui-bootstrap-tpls-0.13.0.min',
    'angulardedlist': 'lib/angular/angular-drag-and-drop-lists.min',
    'angularupload': 'lib/angular/angular-file-upload.min',
    'angularsanitize': 'lib/angular/angular-sanitize.min'
  },
  shim: {
    backbone: {
      deps: ['underscore', 'jquery'],
      exports: 'Backbone'
    },
    paginator: {
      deps: ['backbone'],
      exports: 'Backbone.PageableCollection'
    },
    vcollection: {
      deps: ['backbone'],
      exports: 'Backbone.VirtualCollection'
    },
    marionette: {
    	deps: ['backbone'],
      exports: 'Marionette'
    },
    jqueryui: {
    	deps: ['jquery'],
    	exports: 'jqueryui'
    },
    jquerydatetimepicker: {
    	deps: ['jquery'],
    	exports: 'jqueryui'
    },
    fullcalendar: {
      deps: ['jquery'],
      exports: 'fullCalendar'
    },
    timepicker: {
      deps: ['jqueryui'],
      exports: 'timepicker'
    },
    underscore: {
      exports: '_'
    },
    jqueryte: {
      deps: ['jqueryui'],
      exports: 'jqte'
    },
    dropdown: {
      deps: ['jquery'],
      exports: 'dropdown_menu'
    },
    jquery_iframe: {
    	deps: ['jquery'],
      exports: 'jquery_iframe'
    },
    addresspicker: {
      deps: ['jquery', 'async!http://maps.google.com/maps/api/js?sensor=false'],
      exports: 'addresspicker'
    },
    timezonepicker: {
      deps: ['jquery'],
      exports: 'timezonepicker'
    },
    angular: {
    	deps: ['jquery'],
      exports: 'angular'
    },
    angularresource: {
      deps: ['angular']
    },
    angulartree: {
      deps: ['angular']
    },
    angulartreeview: {
      deps: ['angular']
    },
    angularrouter: {
      deps: ['angular']
    },
    angularbootstrap: {
      deps: ['angular']
    },
    angulardedlist: {
      deps: ['angular']
    },
    angularupload: {
      deps: ['angular']
    },
    angularsanitize: {
      deps: ['angular']
    }
  }
});

if(window.applicationPath) {

  require([window.applicationPath], function() {});
}

var phoneapp = false;

require(['jquery', 'dropdown'], function($) {
	
  jQuery.noConflict();
  
	(function($) {
		/*
		 * Gestione dinamica dei link alla pagina della guida.
		 * Il tag nella pagina deve essere scritto nel formato:
		 * <a class="button helpid" data_pageid="< id del testo nella pagina della guida >"></a>
		 * I link inseriti dinamicamente dopo il caricamento della pagina
		 * non funzionano
		 */
		$('a.button.helpid').click(function(evt) {
			evt.preventDefault();
			var href = $('#page_panel a[target=guida]') && $('#page_panel a[target=guida]').attr('href') || '';
			if(href) {
				var pageid = $(evt.target).attr('data_pageid');
				window.open(href + '#' + pageid, 'guida');
			}
		});
		
	})(jQuery);
  
});