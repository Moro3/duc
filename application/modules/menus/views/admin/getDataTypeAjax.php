<?php
	if( ! isset($uri)) return;
?>

$(document).ready(function(){	
	$("#sorter").on("click", function(){
		alert("Кнопка type_id");
	});
});

var id = 1;
$.ajax({
	type: "POST",
	url: "<?=$uri?>",
	data: { id : id},
	beforeSend: function(){
		/* что-то сделать перед */
	},
	success: function(data){
		/* обработать результат */
	},
	error: function(){
		/* обработать ошибку */
	}
});
