<?php

//========= Combobox DropDown ===============
/*
* Вывод изображений в виде select формы
*
* Входящие параметры:

*
*/

$script_image_dropdown = '
		$(document).ready(function(e) {
			try {
				$("#select_img select").msDropDown({childwith:650,width:650,
				  visibleRows:4, rowHeight:40
				});
			} catch(e) {
				alert(e.message);
			}
		});
		';
		$script_image_selectmenu = "
		$(function(){
			$('select#peopleA').selectmenu({
				icons: [
					{find: '.avatar'}
				],
				bgImage: function() {
					return 'url(' + $(this).attr(\"title\") + ')';
				}
			});
		});

		//a custom format option callback
		var addressFormatting = function(text){
			var newText = text;
			//array of find replaces
			var findreps = [
				{find:/^([^\-]+) \- /g, rep: '<span class=\"ui-selectmenu-item-header\">$1</span>'},
				{find:/([^\|><]+) \| /g, rep: '<span class=\"ui-selectmenu-item-content\">$1</span>'},
				{find:/([^\|><\(\)]+) (\()/g, rep: '<span class=\"ui-selectmenu-item-content\">$1</span>$2'},
				{find:/([^\|><\(\)]+)$/g, rep: '<span class=\"ui-selectmenu-item-content\">$1</span>'},
				{find:/(\([^\|><]+\))$/g, rep: '<span class=\"ui-selectmenu-item-footer\">$1</span>'}
			];

			for(var i in findreps){
				newText = newText.replace(findreps[i].find, findreps[i].rep);
			}
			return newText;
		}";

         //assets_script($script_image_dropdown, false);

        $style_selectmenu = '
		/* demo styles */
		body {font-size: 62.5%; font-family:"Verdana",sans-serif; }
		fieldset { border:0; }
		label,select,.ui-select-menu { float: left; margin-right: 10px; }
		select { width: 250px; }
		.ui-selectmenu-menu li a, .ui-selectmenu-status { padding: 0.3em 2em; }

		.avatar-big .ui-selectmenu-item-icon, .css-avatar .ui-selectmenu-item-icon , .avatar .ui-selectmenu-item-icon { background-position: 0 0; }

		/* select with custom icons */
		body a.customicons { height: 2.8em;}
		body .customicons li a, body a.customicons span.ui-selectmenu-status { line-height: 2em; padding-left: 30px !important; }
		body .video .ui-selectmenu-item-icon, body .podcast .ui-selectmenu-item-icon, body .rss .ui-selectmenu-item-icon { height: 24px; width: 24px; }
		body a.video { height: 2.7em; }
		body li.video.ui-selectmenu-hasIcon a, body li.podcast.ui-selectmenu-hasIcon a, body li.rss.ui-selectmenu-hasIcon a, body .video .ui-selectmenu-status { padding: 7px 0 7px 30px; }
		body .video .ui-selectmenu-item-icon { background: url(images/24-video-square.png) 0 0 no-repeat; }
		body .podcast .ui-selectmenu-item-icon { background: url(images/24-podcast-square.png) 0 0 no-repeat; }
		body .rss .ui-selectmenu-item-icon { background: url(images/24-rss-square.png) 0 0 no-repeat; }

		/* select with CSS avatar icons */
		option.css-avatar { background-repeat: no-repeat !important; padding-left: 20px; }

		/* select with big avatar icons */
		a.avatar-big { height: 5em; }
		.avatar-big .ui-selectmenu-item-icon { height: 50px; width: 50px; }
		.ui-selectmenu-menu li.avatar-big a, a.avatar-big span.ui-selectmenu-status { padding-left: 5em !important; height: 50px; }
		';

		 $style_image_combobox = '
           .ddcommon .ddTitle .ddTitleText img{height:30px}
           .ddcommon .ddChild li img{height:30px}
		   .dd .ddChild li img{height:30px}
		 ';
		 assets_style($style_image_combobox, false);
         //echo Modules::run('duc/duc_teachers/image_combobox');
         //echo Modules::run('duc/duc_teachers/image_selectmenu');