$(document).ready(function() {
						   
	
	//for Caching
	var $content = $('#content');
	
		/*----------------------------------------------------------------------*/
		/* preload images
		/*----------------------------------------------------------------------*/
		
		//$.preload();
		
		/*----------------------------------------------------------------------*/
		/* Widgets
		/*----------------------------------------------------------------------*/
		
		$content.find('div.widgets').wl_Widget();
		/*----------------------------------------------------------------------*/
		/* All Form Plugins
		/*----------------------------------------------------------------------*/
		
		//Integers and decimals
		$content.find('input[type=number].integer').wl_Number();
		$content.find('input[type=number].decimal').wl_Number({decimals:2,step:0.5});
		
		//Date and Time fields
		$content.find('input.date, div.date').wl_Date({dateFormat: 'yy-mm-dd'});
		$content.find('input.time').wl_Time();
		
		//Autocompletes (source is required)
		$content.find('input.autocomplete').wl_Autocomplete({
			source: ["ActionScript","AppleScript","Asp","BASIC","C","C++","Clojure","COBOL","ColdFusion","Erlang","Fortran","Groovy","Haskell","Java","JavaScript","Lisp","Perl","PHP","Python","Ruby","Scala","Scheme"]
		});
		
		//Elastic textareas (autogrow)
		$content.find('textarea[data-autogrow]').elastic();
		//WYSIWYG Editor
//		$content.find('textarea.html').wl_Editor();

                $content.find('textarea.html').elrte({
                    cssClass : 'el-rte',
                    toolbar  : 'ysa_toolbar',
                    fmAllow  : true,
                    height   : 450,
                    cssfiles : ['css/elrte-inner.css'],
                    fmOpen : function(callback) {
                        $('<div id="ysaelfinder" />').elfinder({
                            url : _admin_url + 'images/connector/',
                            dialog : { width : 900, modal : true, title : 'Images' }, // open in dialog window
                            closeOnEditorCallback : true, // close after file select
                            editorCallback : callback     // pass callback to file manager
                        });
                    }
                    
                });
                


		//Validation
		$content.find('input[data-regex]').wl_Valid();
		$content.find('input[type=email]').wl_Mail();
		$content.find('input[type=url]').wl_URL();

		//File Upload
//		$content.find('input[type=file]').wl_File();

		//Password and Color
		$content.find('input[type=password]').wl_Password();
		$content.find('input.color').wl_Color();
		
		//Sliders
		$content.find('div.slider').wl_Slider();
		
		//Multiselects
		$content.find('select[multiple]').wl_Multiselect();
		
		//The Form itself
//		$content.find('form').wl_Form();
		
		/*----------------------------------------------------------------------*/
		/* Alert boxes
		/*----------------------------------------------------------------------*/
		
		$content.find('div.alert').wl_Alert();

		/*----------------------------------------------------------------------*/
		/* uniform plugin && checkbox plugin (since 1.3.2)
		/* uniform plugin causes some issues on checkboxes and radios
		/*----------------------------------------------------------------------*/
		
		$("select, input[type=file]").not('select[multiple]').uniform();
		$('input:checkbox, input:radio').checkbox();
		
		/*----------------------------------------------------------------------*/
		/* Charts
		/*----------------------------------------------------------------------*/

		$content.find('table.chart').wl_Chart({
			onClick:function(value, legend, label, id){
				$.msg("value is "+value+" from "+legend+" at "+label+" ("+id+")",{header:'Custom Callback'});
			}
		});
		
		/*----------------------------------------------------------------------*/
		/* Fileexplorer
		/*----------------------------------------------------------------------*/

		$content.find('div.fileexplorer').wl_Fileexplorer();
		
		
		/*----------------------------------------------------------------------*/
		/* Calendar (read http://arshaw.com/fullcalendar/docs/ for more info!)
		/*----------------------------------------------------------------------*/
		
		$content.find('div.calendar').wl_Calendar({
			eventSources: [
					{
						url: 'http://www.google.com/calendar/feeds/usa__en%40holiday.calendar.google.com/public/basic'
					},{
						events: [ // put the array in the `events` property
							{
								title  : 'Fixed Event',
								start  : '2011-10-01'
							},
							{
								title  : 'long fixed Event',
								start  : '2011-10-06',
								end    : '2011-10-14'
							}
						],
						color: '#f0a8a8',     // an option!
						textColor: '#ffffff' // an option!
					},{
						events: [ // put the array in the `events` property
							{
								title  : 'Editable',
								start  : '2011-10-09 12:30:00'
							}
						],
						editable:true,
						color: '#a2e8a2',     // an option!
						textColor: '#ffffff' // an option!
					}		
					// any other event sources...
			
				]
			});

		
		/*----------------------------------------------------------------------*/
		/* Tipsy Tooltip
		/*----------------------------------------------------------------------*/
		
		
		$content.find('input[title]').tipsy({
			gravity: function(){return ($(this).data('tooltip-gravity') || config.tooltip.gravity);},
			fade: config.tooltip.fade,
			opacity: config.tooltip.opacity,
			color: config.tooltip.color,
			offset: config.tooltip.offset
		});
		
		
		/*----------------------------------------------------------------------*/
		/* Accordions
		/*----------------------------------------------------------------------*/
		
		$content.find('div.accordion').accordion({
                    collapsible:true,
                    autoHeight:false
		});
		
		/*----------------------------------------------------------------------*/
		/* Tabs
		/*----------------------------------------------------------------------*/
		
		$content.find('div.tab').tabs({
                    fx: {
                            opacity: 'toggle',
                            duration: 'fast'
                    }
		});
		
		/*----------------------------------------------------------------------*/
		/* Navigation Stuff
		/*----------------------------------------------------------------------*/
		
		
		//Top Pageoptions
		$('#wl_config').click(function(){
			var $pageoptions = $('#pageoptions');
			if($pageoptions.height() < 200){
				$pageoptions.animate({'height':200});
				$(this).addClass('active');
			}else{
				$pageoptions.animate({'height':20});
				$(this).removeClass('active');
			}
			return false;
		});
		
		
		//Header navigation for smaller screens
		var $headernav = $('ul#headernav');
		
		$headernav.bind('click',function(){
			//if(window.innerWidth > 800) return false;
			var ul = $headernav.find('ul').eq(0);
			(ul.is(':hidden')) ? ul.addClass('shown') : ul.removeClass('shown');
		});
		
		$headernav.find('ul > li').bind('click',function(event){
			event.stopPropagation();
			var children = $(this).children('ul');
			
			if(children.length){
				(children.is(':hidden')) ? children.addClass('shown') : children.removeClass('shown');
				return false;
			}
		});
		
		//Search Field Stuff		
		var $searchform = $('#searchform'),
			$searchfield = $('#search');
		
		$searchfield
			.bind('focus.wl',function(){
		   		$searchfield.parent().animate({width: '150px'},100).select();
			})
			.bind('blur.wl',function(){
	   			$searchfield.parent().animate({width: '90px'},100);
			});
			
		$searchform
			.bind('submit.wl',function(){
				//do something on submit				
				var query = $searchfield.val();
			});
			
		
		//Main Navigation		
		var $nav = $('#nav');
			
		$nav.delegate('li','click.wl', function(event){
			var _this = $(this),
				_parent = _this.parent(),
				a = _parent.find('a');
			_parent.find('ul').slideUp('fast');
			a.removeClass('active');
			_this.find('ul:hidden').slideDown('fast');
			_this.find('a').eq(0).addClass('active');
			event.stopPropagation();
		});
		
		/*----------------------------------------------------------------------*/
		/* Helpers
		/*----------------------------------------------------------------------*/
		
		//placholder in inputs is not implemented well in all browsers, so we need to trick this		
		$("[placeholder]").bind('focus.placeholder',function() {
			var el = $(this);
			if (el.val() == el.attr("placeholder") && !el.data('uservalue')) {
				el.val("");
				el.removeClass("placeholder");
			}
		}).bind('blur.placeholder',function() {
			var el = $(this);
			if (el.val() == "" || el.val() == el.attr("placeholder") && !el.data('uservalue')) {
				el.addClass("placeholder");
				el.val(el.attr("placeholder"));
				el.data("uservalue",false);
			}else{
			
			}
		}).bind('keyup.placeholder',function() {
			var el = $(this);
			if (el.val() == "") {
				el.data("uservalue",false);
			}else{
				el.data("uservalue",true);
			}
		}).trigger('blur.placeholder');
});