<?php

//------------- Обработчик события для полей multiselect
/*
Входящие данные
  обязательные:
  	$selector - string || array - имя или массив из селекторов для замены
  	$event - string - событие
  			 	default: jqGridAddEditAfterShowForm (выполняется после прорисовки формы при добавлении или редактирования)
  			 	==== AddEdit ====  			 	
  			 	jqGridAddEditBeforeCheckValues
  			 	jqGridAddEditClickSubmit
  			 	jqGridAddEditBeforeSubmit
  			 	jqGridAddEditErrorTextFormat
  			 	jqGridAddEditAfterSubmit
  			 	jqGridAddEditAfterComplete
  			 	jqGridAddEditBeforeInitData
  			 	jqGridAddEditBeforeShowForm
  			 	jqGridAddEditAfterShowForm  			 	
  			 	jqGridAddEditInitializeForm
  			 	jqGridAddEditClickPgButtons
  			 	jqGridAddEditAfterClickPgButtons
  			 	==== Filter ====
  			 	jqGridFilterInitialize
  			 	jqGridFilterBeforeShow
  			 	jqGridFilterAfterShow
  			 	jqGridFilterSearch
  			 	jqGridFilterReset
  			 	==== Toolbar ====
				jqGridToolbarBeforeSearch
				jqGridToolbarAfterSearch
				jqGridToolbarBeforeClear
				jqGridToolbarAfterClear
				==== Grid ====
				jqGridGridComplete
				jqGridAfterGridComplete
				jqGridBeforeRequest
				jqGridLoadComplete
				jqGridAfterLoadComplete
				reloadGrid
				==== Row ====
				jqGridInlineEditRow
				jqGridInlineAfterSaveRow
				jqGridInlineSuccessSaveRow
				jqGridInlineErrorSaveRow
				jqGridInlineAfterRestoreRow
				==== Cell ====
				jqGridBeforeEditCell				
				jqGridAfterEditCell
				jqGridSelectCell
				jqGridBeforeSaveCell
				jqGridBeforeSubmitCell
				jqGridAfterSubmitCell
				jqGridAfterSaveCell
				jqGridErrorCell
				jqGridAfterRestoreCell
				==== SubGrid ====
				jqGridSubGridBeforeExpand
				jqGridSubGridRowExpanded
				jqGridSubGridRowColapsed
				==== Grouping ====
				jqGridGroupingClickGroup
				==== Import ====
				jqGridImportComplete
				
  				all events: http://www.trirand.com/jqgridwiki/doku.php?id=wiki:events 			
    
  опционально:
  	$width - ширина поля select
  	$minimumInputLength 		: int	//	минимально символов для ввода
  	$maximumSelectionSize 		: int	//	максимально значений для мульти селекта
	$maximumInputLength 		: int	//	Maximum number of characters that can be entered for an input.
	$minimumResultsForSearch 	: int	//	The minimum number of results that must be initially (after opening the dropdown for the first time) populated in order to keep the search field.
	$maximumSelectionSize 		: int //	The maximum number of items that can be selected in a multi-select control. If this number is less than 1 selection is not limited. 
	$placeholder 				: string // Initial value that is selected if no other selection is made.
	$placeholderOption 			: function/string	//	When attached to a select resolves the option that should be used as the placeholder.
													// 	Can either be a function which given the select element should return the option element or a string first to indicate that the first option should be used.			
	$separator 					: string //	Separator character or string used to delimit ids in value attribute of the multi-valued selects.
										// The default delimiter is the , character.
	$allowClear					: boolean	// Whether or not a clear button is displayed when the select box has a selection. 
	$multiple					: boolean // Whether or not Select2 allows selection of multiple values.
	$closeOnSelect				: boolean // If set to false the dropdown is not closed after a selection is made, allowing for rapid selection of multiple items. By default this option is set to true.
	$openOnEnter				: boolean // If set to true the dropdown is opened when the user presses the enter key and Select2 is closed. By default this option is enabled. 
	$formatSelection			: function // Function used to render the current selection.
											//	formatSelection(object, container)
											// default formatSelection
	$formatResult				: function // Function used to render a result that the user can select. 
											//	formatResult(object, container, query)
	
*/


//if( ! isset($selector)) return;
if( ! is_array($selector)) $selector = array($selector);
if( ! isset($event)) $event = 'jqGridAddEditAfterShowForm';
//if( ! isset($width)) $width = '200px';
//$selector = '#id_teacher';

$allow_options = array(		'minimumInputLength' => 'int', 'maximumSelectionSize' => 'int', 'maximumInputLength' => 'int', 'minimumResultsForSearch' => 'int', 'maximumSelectionSize' => 'int', 
							'width' => 'string', 'containerCss' => 'string', 'placeholder' => 'string', 'placeholderOption' => 'string', 'separator' => 'string', 
							'allowClear' => 'boolean', 'multiple' => 'boolean',	'closeOnSelect' => 'boolean', 'openOnEnter' => 'boolean',
							'formatSelection' => 'function', 'formatResult' => 'function'	
);



foreach ($allow_options as $option=>$type) {
	if(!empty($$option)){
		if($type === 'int' || $type === 'boolean'){
			$options[$option] = $option.': '.$$option;	
		}elseif($type === 'string'){
			if(is_array($$option)){
				$options[$option] = ''.$option.' : '.json_encode($$option).'';
			}else{
				$options[$option] = ''.$option.' : \''.$$option.'\'';
			}			
		}elseif($type === 'function'){
			$options[$option] = ''.$option.' : '.$$option.'';
		}		
	}
}

//var_dump($options);
//exit;


$script = '
		$grid.bind("'.$event.'", function(event, $form)
		{
			function formatResult(state) {
				//if (!state.id) return state.text; // optgroup
				//return \'<img class="flag" src="/uploads/images/duc/teachers/mini/teacher_1_89e7726ef19016fc27adc89b778ec16f.jpg" style="height:20px" />\' + state.text;
				//return "<img class=\'flag\' src=\'/uploads/images/duc/teachers/mini/" + state.id.toLowerCase() + ".png\'/>" + state.text;
				var originalOption = state.element;

				var elemImg = $(originalOption).data(\'img\');
				if( elemImg.length > 0){
					return "<img class=\'flag\' src=\'/uploads/images/duc/teachers/mini/" + $(originalOption).data(\'img\') + "\' alt=\'" + state.text + "\' style=\'height:20px\' />" + state.text;
				}else{
					return state.text;
				}
			}
			function formatSelection(state) {
				if (!state.id) return state.text; // optgroup
				var originalOption = state.element;

				var elemImg = $(originalOption).data(\'img\');
				if( elemImg.length > 0){
					return "<img class=\'flag\' src=\'/uploads/images/duc/teachers/mini/" + $(originalOption).data(\'img\') + "\' alt=\'" + state.text + "\' style=\'height:15px\' />" + state.text;
				}else{
					return state.text;
				}
				return state.text;
			}
			
';

foreach($selector as $sel){
	$script .= '$("'.$sel.'").select2({';
	if(isset($options) && is_array($options) && count($options) >0){
		//$script += join(',', array_map(function($x){return ''.$x.'';}, array_values($options)));
		$script .= join(",", $options);
	}
	$script .= '});';
}


$script .= '
		});
';

echo '<script>';
	//echo 'alert('.$script.')';
	echo $script;
echo '</script>';
//регистрируем скрипт
//assets_script($script, false);

//очищаем переменные для предотвращения коллизий
unset($script);
//var_dump($options);
if(isset($options)) $options = array();
foreach ($allow_options as $option=>$type) {
	if(isset($$option)){
		
		$$option = '';
	}
}