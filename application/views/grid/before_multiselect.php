<?php
//подключение функции для Multiselect
/*
Входящие данные
  обязательные:

  опционально:
  	$sortable - boolean - сортировка
  	$searchable - boolean - поиск


*/
$sortable = (isset($sortable)) ? 'true' : 'false';
$searchable = (isset($searchable)) ? 'true' : 'false';
?>

<script>
	function gridEditMultiselect(field)
	{
              $(".ui-"+field).remove();
              $("."+field).show().multiselect(
              {
              	sortable: <?php echo $sortable; ?>,
              	searchable: <?php echo $searchable; ?>
              });

	}
</script>