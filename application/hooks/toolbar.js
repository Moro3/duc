var debugToolbar = {

	// current toolbar section thats open
	current: null,

	// current vars and config section open
	currentvar: null,

	// current config section open
	currentli: null,

	// toggle a toolbar section
	show : function(obj) {
		if (obj == debugToolbar.current) {
			debugToolbar.off(obj);
			debugToolbar.current = null;
		} else {
			debugToolbar.off(debugToolbar.current);
			debugToolbar.on(obj);
			debugToolbar.current = obj;
		}
	},

	// toggle a vars and configs section
	showvar : function(li, obj) {
		if (obj == debugToolbar.currentvar) {
			debugToolbar.off(obj);
			debugToolbar.currentli = null;
			debugToolbar.currentli.className = '';
			debugToolbar.currentvar = null;
		} else {
			debugToolbar.off(debugToolbar.currentvar);
			if (debugToolbar.currentli)
				debugToolbar.currentli.className = '';
			debugToolbar.on(obj);
			debugToolbar.currentvar = obj;
			debugToolbar.currentli = li;
			debugToolbar.currentli.className = 'active';
		}
	},

	// turn an element on
	on : function(obj) {
		if (document.getElementById(obj) != null)
			document.getElementById(obj).style.display = '';
	},

	// turn an element off
	off : function(obj) {
		if (document.getElementById(obj) != null)
			document.getElementById(obj).style.display = 'none';
	},

	// toggle an element
	toggle : function(obj) {
		if (typeof obj == 'string')
			obj = document.getElementById(obj);

		if (obj)
			obj.style.display = obj.style.display == 'none' ? '' : 'none';
	},

	// close the toolbar
	close : function() {
		document.getElementById('codeigniter-debug-toolbar').style.display = 'none';
	},

	swap: function() {
		var toolbar = document.getElementById('debug-toolbar');
		if (toolbar.style.right) {
			toolbar.style.left = '0px';
			toolbar.style.right = null;
		} else if (toolbar.style.left) {
			toolbar.style.left = null;
			toolbar.style.right = null;
		} else {
			toolbar.style.left = null;
			toolbar.style.right = '0px';
		}
	}

};

/*
 * Test for javascript libraries
 * (only supports jQuery at the moment
 */
if (typeof jQuery != 'undefined') {

	$(document).ready(function(){

		// display ajax button in toolbar
		$('#toggle-ajax').css({display: 'inline'});

		// bind ajax event
		$('#debug-ajax').bind("ajaxComplete", function(event, xmlrequest, ajaxOptions){

			// add a new row to ajax table
			$('#debug-ajax table').append(
				'<tr class="even">' +
					'<td>' + $('#debug-ajax table tr').size() +'<\/td>' +
					'<td>jQuery ' + jQuery.fn.jquery + '<\/td>' +
					'<td>' + xmlrequest.statusText + ' (' + xmlrequest.status + ')<\/td>' +
					'<td>' + ajaxOptions.url + '<\/td>' +
					'<td>' + ajaxOptions.type + '<\/td>' +
					'<td>' + ajaxOptions.data + '<\/td>' +
					//'<td>' + print_r(ajaxOptions) + '<\/td>' +
				'<\/tr>'
			);

			// stripe table
			$('#debug-ajax table tbody tr:nth-child(even)').attr('class', 'odd');

			// update count in toolbar
			$('#toggle-ajax span').text($('#debug-ajax table tr').size()-1);

		});

	});
}

if (typeof Prototype != 'undefined') {

}

/**
 * аналог PHP-шной
 * @param {Array/HTMLElement/Object} taV
 */
function print_r(taV)
{
  alert(getProps(taV));
}

/**
 * возвращает список атрибутов объекта и значения
 * @param {Element/Object} toObj - ссылка на объект
 * @param {String} tcSplit - строка разделитель строк
 * @return {String} - строку со списком атрибутов объекта
 * и значениями атрибутов
 */
function getProps(toObj, tcSplit)
{
  if (!tcSplit) tcSplit = '\n';
  var lcRet = '';
  var lcTab = '    ';

    for (var i in toObj) // обращение к свойствам объекта по индексу
      lcRet += lcTab + i + " : " + toObj[i] + tcSplit;

    lcRet = '{' + tcSplit + lcRet + '}';

  return lcRet;
}