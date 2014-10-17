$.ajax({
	type: "POST",
	url: "/",
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
