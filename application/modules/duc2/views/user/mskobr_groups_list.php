<?php


$style = '
     .duc_group{     	background-color:#ffffee;
     	padding:5px;
     	margin:5px;
     	overflow:hidden;
     	zoom:1;
     }
     .duc_group .header{     	padding:5px;
     }
     .duc_group .img_group{     	width: 300px;
     	float: left;
     }
     .duc_group .img_group img{
     	width: 280px;
     	margin: 5px;
     	text-align: left;
     	height: 187px;
     }
     .duc_group .content{
     }
     .duc_group .photos{
         padding: 20px 0;
     }
     .duc_group .photos .button{
     	color:#4d81d0;

     }
     .duc_group .photos img{
     	height:160px;
     	margin:5px;
     	border:2px solid #aaa;
     	border-radius: 3px;
     }
     table.photos {
     	border:0;
     }
     table tbody.photos {     	background-color:#fff;
     }
     table tbody.photos td{
     	border:0;
     }
';

$style2 = '
#zoom {
 list-style:none;
 margin-left:-20px; /* Компенсируем отступ слева */
}
#zoom li {
 width:252px; /* Ширина размера изображения с учетом рамки в 1px */
 height:158px;
 float:left;
 margin-left:20px;
 margin-top:20px;
 margin-right:20px;
 overflow:visible;
 display: block;

}
.zoom_it {
 position:relative;
 1width:250px;
 height:126px;
 top:0;
 left:0;
 border:1px solid #ccc;
}
     .zoom_it:hover {
 1width:500px; /* Увеличиваем ширину изображения */
 height:288px; /* Увеличиваем высоту изображения */
 top:-16px; /* Сдвигаем изображение вверх на значение (новая ширина - старая)/2 */
 left:-25px; /* Сдвигаем изображение влево на значение (новая высота - старая)/2 */
 z-index:9999; /* Располагаем изображение над всеми элементами */
 box-shadow: 0 20px 30px rgba(0,0,0,0.5); /* Добавляем тень */
 -webkit-box-shadow: 0 20px 30px rgba(0,0,0,0.5); /* Для Safari и Chrome */
 -moz-box-shadow: 0 20px 30px rgba(0,0,0,0.5); /* Для Firefox */
 transition: all 0.3s ease; /* Изменяем значения всех свойств плавно в течение 0,3 сек */
 -webkit-transition: all 0.3s ease; /* Для Safari и Chrome */
 -o-transition: all 0.3s ease; /* Для Opera */
 -moz-transition: all 0.3s ease; /* Для Firefox */
}

.to-be-changed {
    color: black;
}



	#toggle[type=checkbox] {
    position: absolute;
    top: -9999px;
    left: -9999px;
}

label {
    display: block;
    background: #08C;
    padding: 5px;
    border: 1px solid rgba(0,0,0,.1);
    border-radius: 2px;
    color: white;
    font-weight: bold;
}

#toggle[type=checkbox]:checked ~ .to-be-changed {
    color: red;
}


';
echo '<style>';
	echo $style;
	//echo $style2;
echo '</style>';

$script = '
    $(document).ready(function(e) {
       $(".duc_group .photos table").hide();

       $(".duc_group .photos .button").click(function(){
       		$(this).parent().find("table").toggle();
       });
	});
';
echo '<script>';
	//echo $script;
echo '</script>';

//echo '<pre>';
//print_r($groups);
//echo '</pre>';
if(isset($groups) && is_array($groups)){
		foreach($groups as $key=>$item){

			echo '<div class="duc_group">';
			echo '<div class="header">';
				echo '<h2>';
					echo $item['name'];
				echo '</h2>';
			echo '</div>';
			//echo '<div class="img_group">';
				//echo '<img alt="'.htmlspecialchars($item['name']).'"  />';
					//echo  'src="http://duc.dop.mskobr.ru/images/ck/pics/kol__hor_kolokolchik-2.jpg"';
			//echo '</div>';
			echo '<div class="content">';
				echo '<p><u>Вид деятельности</u>: '.$item['activity']->name.'</p>';
				echo '<p><u>Образовательные программы</u>: '.$item['programm'].'</p>';
				echo '<p><u>Возраст детей</u>:&nbsp; '.$item['age_from'].'-'.$item['age_to'].' лет</p>';
				if(!empty($item['year_create'])){
					echo '<p><u>Год создания коллектива</u>: '.$item['year_create'].'</p>';
				}
				if(!empty($item['period'])){
					echo '<p><u>Срок реализации</u>: '.$item['period'].' года/лет</p>';
				}
				echo '<p><u>ФИО руководителя (ей)</u>: '.$item['teacher']->surname.' '.$item['teacher']->name.' '.$item['teacher']->name2.'</p>';
				if(isset($item['concertmaster']) && is_array($item['concertmaster'])){					echo '<p><u>Концертмейстер(ы)</u>:';
					foreach($item['concertmaster'] as $items){						 echo $items->teachers->surname.' '.$items->teachers->name.' '.$items->teachers->name2;
						 echo '<br />';
					}
					echo '</p>';
				}

				if(isset($item['teacher']->qualification)){
					echo '<p><u>Квалификационная категория</u>: '.$item['teacher']->qualification->name.'</p>';
                }
				echo '<p><u>Описание</u>:</p>';
				if(!empty($item['description'])){
					echo '<p>'.$item['description'].'</p>';
                }elseif(!empty($item['short_description'])){                	echo '<p>'.$item['short_description'].'</p>';
                }
			echo '</div>';
			if(!empty($item['photos'])){
				//echo '<input type="checkbox" id="toggle">';
				//echo '<label for="toggle">Нажми меня!</label>';
				//echo '<p class="to-be-changed">Здесь будет красный цвет. Или уже есть...</p>';
				echo '<div class="photos">';
					echo '<span class="button">';
					echo 'Фотографии коллектива:';


					echo '</span>';
				echo '<table class="photos">';
				echo '<tbody class="photos">';
				echo '<tr>';
					echo '<td>';

					//echo '</td>';

					//echo '<td>';
					//echo '<pre>';
					//print_r($item['photos']);
					//echo '</pre>';
					$resize_config = Modules::run('duc/duc_settings/get_config_resize', 'groups');
					$resize_param = Modules::run('duc/duc_settings/get_param_resize', 'groups');
					//echo '<div class="jg_row sortable_gallery SORTABLE">';
					//echo '<ul id="zoom">';
					foreach($item['photos'] as $photos){
						if(!empty($photos->img)){
							$path = $config['path']['root'];
							$dir = $resize_config['path'].'/'.$resize_param['small']['dir'].'/';
							if(is_file($path.$dir.$photos->img)){
								if($photos->active == 1){
									//echo '<img src="/'.$dir.$photos->img.'" />';
									echo  '<img src="http://duc.dop.mskobr.ru/images/ck/pics/groups/'.$photos->img.'" />';

                                    /*
                                    echo '<li>';
									echo '<a href="http://duc.dop.mskobr.ru/images/ck/pics/kol__hor_kolokolchik-2.jpg"><img class="zoom_it" src="http://duc.dop.mskobr.ru/images/ck/pics/kol__hor_kolokolchik-2.jpg" /></a>';
									echo '</li>';
									echo '<li>';
									echo '<a href="http://duc.dop.mskobr.ru/images/ck/pics/kol__evridika-2.jpg"><img class="zoom_it" src="http://duc.dop.mskobr.ru/images/ck/pics/kol__evridika-2.jpg" /></a>';
									echo '</li>';
									echo '<li>';
									echo '<a href="http://duc.dop.mskobr.ru/images/ck/pics/kol__fortepiano-6.jpg"><img class="zoom_it" src="http://duc.dop.mskobr.ru/images/ck/pics/kol__fortepiano-6.jpg" /></a>';
									echo '</li>';
									*/
									/*
									echo '<div id="'.$item['id'].'" class="jg_element_cat kris_task" style="background: none repeat scroll 0% 0% transparent; cursor: pointer;">';
										echo '<div class="jg_imgalign_catimgs">';
											echo '<a class="jg_catelem_photo jg_catelem_photo_align" rel="lightbox[joomgallery]" href="http://duc.dop.mskobr.ru/images/ck/pics/kol__hor_kolokolchik-2.jpg" title="">';
											echo '<img class="jg_photo" width="150" height="113" alt="" src="http://duc.dop.mskobr.ru/images/joomgallery/thumbnails/_2013_26/20130908_-_________53/________1_20130909_1600252634.jpg">';
											echo '</a>';
										echo '</div>';
									echo '</div>';
									*/
								}
							}
						}
					}
					//echo '</ul>';
                    //echo '</div>';
					echo '</td>';
				echo '</tr>';
				echo '</tbody>';
				echo '</table>';
				echo '</div>';
			}
			echo '</div>';
		}

}
