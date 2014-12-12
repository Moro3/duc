<?php
  //$this->assets->out_config();
  assets_script_out();
?>

<!-- JQGrid script -->
<script>
	$.extend($.jgrid.defaults,
	{
		altRows: false,
		altclass: 'altrow',

		hidegrid: false,
		hoverrows: false,

		viewrecords: true,
		scrollOffset: 21,

		width: 776,
		height: 400,
	});

	$.jgrid.defaults.height = '600';
	$.jgrid.nav.refreshtext = 'Refresh';
	$.jgrid.formatter.date.newformat = 'ISO8601Short';

	$.jgrid.edit.closeAfterEdit = true;
	$.jgrid.edit.closeAfterAdd = true;

	$(function()
	{
		$('#tabs-info').html($('#descr_rus').html());

		$('#accordion').accordion({
			'animated' : false,
			'navigation' : true
		});

		$('#tabs').tabs();

		//hljs.tabReplace = '    ';
		//hljs.initHighlightingOnLoad();
	});
</script>
<script>
function myelem (value, options) {
	console.log('смотрим myelem');
    console.log(value);
    console.log(options);
    
  var el = document.createElement("input");
  el.type="text";
  el.value = value;
  return el;
}

function myvalue(elem, operation, value) {
	console.log('смотрим myvalue');
    console.log(elem);
    console.log(operation);
    console.log(value);
    var get_val = $(elem).val();
    //var set_val = $('input',elem).val(value);
    console.log(get_val);
    //console.log(set_val);
    if(operation === 'get') {
       //return $(elem).find("input").val();
       return $(elem).val();
    } else if(operation === 'set') {
       $('input',elem).val('!-'+value);
       //return $(elem).find("input").val();
       var select = $("input", $(elem)[0]);
    	var first = select[0].value;
    	return first;
    }
}

function myselect (value, options) {
  	//alert(value);
  	//alertObj(options);
  	//strObj(options,'',10);
  	//alert(strObj(options,"",10));
  	//print_r(options);
  	var elemStr = '<select id="'+options.id +'" class="FormElement" role="select" name="'+options.name +'">';
  	if(options.dataTeachers && typeof(options.dataTeachers) == 'object'){
  		
  		var teacher = false;
  		var flagSelect = '';
  		for(k in options.dataTeachers){
  			if(options.teachers && typeof(options.teachers) == 'object'){
	  			if(options.teachers[options.dataTeachers[k].id]){
	  				teacher = options.teachers[options.dataTeachers[k].id];	
	  			}else{
	  				teacher = false;
	  			}	  			 
	  		}
	  		if(value == teacher){
	  			flagSelect = 'selected';
	  		}else{
	  			flagSelect = '';
	  		} 
  			//alert(value);		  	
		  	elemStr += '<option role="option" value="' + options.dataTeachers[k].id +
		  	'" data-img="'+options.dataTeachers[k].foto +'" '+ flagSelect +'>'+ options.dataTeachers[k].surname +
			'</option>';
			// return DOM element from jQuery object
		}
		
	}
	elemStr += '</select>';
	return $(elemStr)[0];
}

function myselectvalue(elem, operation, value) {
    
    var select = $("option", $(elem)[0]);
    var first = select[0].value;
    //alertObj(select);
    console.log('смотрим myselectvalue');
    console.log(elem);
    console.log(operation);
    console.log(value);                                                    
    return first;
}

function alertObj(obj) { 
    var str = ""; 
    for(k in obj) { 
        str += k+": "+ obj[k]+"\r\n"; 
    } 
    alert(str); 
}

function strObj(obj,prefix,depth) {
	var str = "\r\n\r\n\r\n\r\n\r\n\r\n";
	for(k in obj) {
		str += prefix+" "+k+": "+ obj[k]+"\r\n";
		if(obj[k] && 'object' === typeof obj[k] && prefix.length < depth-1) {
			str += strObj(obj[k],prefix+"-",depth)
		}	
	}
	return str;
} 

function print_r(arr, level) {
    var print_red_text = "";
    if(!level) level = 0;
    var level_padding = "";
    for(var j=0; j<level+1; j++) level_padding += "    ";
    if(typeof(arr) == 'object') {
        for(var item in arr) {
            var value = arr[item];
            if(typeof(value) == 'object') {
                print_red_text += level_padding + "'" + item + "' :\n";
                print_red_text += print_r(value,level+1);
    }
            else
                print_red_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
        }
    }

    else  print_red_text = "===>"+arr+"<===("+typeof(arr)+")";
    return print_red_text;
}
</script>

