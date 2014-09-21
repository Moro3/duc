<?php
$path = $config['path']['icons'].$config['image_config']['dir'].'/';

$uri_del_foto = '/ajax/?resource=pages_foto_delete/ajax/pages';

$script_foto_delete = "
		function foto_delete()
		{
			//Получаем параметры
			var id = $('#id').val();
			//alert('запущено удаление файла ' + id);
			if (confirm('Вы действительно хотите удалить изображение с сервера? Если Вы желаете оставить изображение, то выберете нет, а в списке ниже выберете нужное поле!')) {

			  // Отсылаем паметры
			       $.ajax({
			             type: \"POST\",
			                url: \"".$uri_del_foto."\",
			                data: \"id=\"+id,
			                // Выводим то что вернул PHP
			                success: function(html) {
			 					//alert(html);
			 					//предварительно очищаем нужный элемент страницы
			                        $(\"#foto\").empty();
								//и выводим ответ php скрипта
			                        $(\"#foto\").append(html);
			                }
			        });
			} else {
			    // Do nothing!
			}
		}
";

$script_image_dropdown = "
$(document).ready(function(e) {
	try {
		$('#select_img select').msDropDown({childwith:650,width:650,
				  visibleRows:4, rowHeight:40
		});
	} catch(e) {
		alert(e.message);
	}
	});
";

assets_script($script_foto_delete, false);
//assets_script($script_image_dropdown, false);
?>

<script>
$grid.bind("jqGridAddEditAfterShowForm", function(event, $form)
{
	var row_id = $(this).getGridParam("selrow");
	var imghtml = $(this).getCell(row_id, "foto_upload");
	var select_img = $(this).getCell(row_id, "select.select_img");

	$("#foto_upload").replaceWith(imghtml);
    $('#select_img select').msDropDown({childwith:650,width:650,
				  visibleRows:4, rowHeight:40
		});
});
$grid.bind("jqGridAddEditBeforeShowForm", function(event, $form)
{
    var mygrid=jQuery(”#grid_id”)[0];
	mygrid.updateColumns();


});
</script>
