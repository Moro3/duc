<?php
	if( ! isset($uri)) return;
?>

//$grid.bind("jqGridAddEditAfterShowForm", function(event, $form){
	var id = $("<?=$selector_event?>").val();
	var node = $("<?=$selector_node?>").val();
	//alert("Кнопка <?=$selector_event?> по умолчанию: " + id);
	getDataType();
//});
$("body").on("change", "<?=$selector_event?>", function(){	
	id = $(this).val();
	//alert("Кнопка <?=$selector_event?>: " + id);
	getDataType();
});

function getDataType()  
    {
	$.ajax({
		type: "POST",
		url: "<?=$uri?>",
		data: { id : id, node : node},
		beforeSend: function(){
			/* что-то сделать перед */

		},
		success: function(data){
			/* обработать результат */			
			//alert(data);
			$("<?=$selector_replace?>").empty().html(data);
		},
		error: function(){
			/* обработать ошибку */
		}
	});
}
