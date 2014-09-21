<?php


$style = '
     .section{     	padding: 20px;
     	background-color: #e6eeff;
     }
     .section .name{
     	font-size: larger;
     	color: #ffa20f;
     }
     .activity{
     	padding: 20px;
     	margin-left:20px;
     	1background-color: #fff0cc;
     	1background-color: #ffffee;
     }
     .activity .name{
     	font-size: larger;
     	color: #ffa20f;
     }

     .duc_group{     	background-color:#d2ddf4;
     	padding:5px;
     	margin:5px;
     	border-radius: 8px;
     	margin-left:40px;
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

$style_spoiler = '
.spoiler >  input + .box{
    1margin-top: -40px;
}
.spoiler >  input + .box > blockquote{
    display: none;
}
.spoiler >  input:checked + .box > blockquote {
    display: block;
}
.spoiler >  input[type="checkbox"] {
    cursor: pointer;
    border-color:transparent!important;
    border-style:none!important;
    background:transparent none!important;
    position:relative;
    z-index:1;
    margin:-10px 0 -30px -330px;
 }
.spoiler >  input[type="checkbox"]:focus {
    outline:none;	/* Убираем обводку в ИЕ8 при "фокусе" */
}
.spoiler span.close,
.spoiler span.open{
    padding-left:22px;
    1color: #f90!important;
    text-decoration: none;
}
.spoiler >  input +  .box > span.close {
    display: none;
    color: #f90!important;
}
.spoiler >  input:checked +  .box > span.close {
    display: inline;
    color: #30549d!important;
}
.spoiler >  input:checked  + .box > span.open {
    display: none;

}
.spoiler >  input +  .box >  span.open {
    display: inline;
}
.spoiler blockquote,
.spoiler{
    padding:0em;
    1margin-top:-10px;
    vertical-align:top;
    1font-size:12px;
    1line-height: 0.5;
    border-radius:15px;
    -webkit-border-radius:15px;
    -khtml-border-radius:15px;
    -moz-border-radius:15px;
    -o-border-radius:15px;
    -ms-border-radius:15px;
}
.spoiler {
    overflow-x:hidden;
    box-shadow: 0px 3px 8px #808080;
    border:#E5E5E5 solid 0px;
    -webkit-box-shadow:0px 3px 8px #808080;
    -khtml-box-shadow:0px 3px 8px #808080;
    -moz-box-shadow:0px 3px 8px #808080;
    -ms-box-shadow:0px 3px 8px #808080;
}
.spoiler blockquote {
   margin-top:12px;
   min-height: 23px;
   1border:#CDCDCD 0px dashed;
}
';

$style_spoiler_lite = '
      /* CSS */
.spoiler >  input + .box {
    display: none;
}
.spoiler >  input:checked + .box {
    display: block;
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
	echo $style_spoiler;
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
if(isset($groups) && is_array($groups)){        $section_save = '';
        $activity_save = '';
        $count_group = count($groups);
        $i_group = 0;
        echo '<div class="section">';
		foreach($groups as $key=>$item){
            $i_group ++;
            $section = $item['section']->name;
            $activity = $item['activity']->name;

            //закрываем блок для вида деятельности (activity) если он изменился
            if(!empty($activity_save) && $activity_save !== $activity){
            	echo '</blockquote>';
					echo '</div>';
					echo '</div>';

            	echo '</div>';
            }

            // закрываем блок для профиля (section) если он изменился
            // и открываем новый
            if($section_save !== $section){            	echo '</div>';
            	echo '<div class="section">';
            		echo '<div class="name">';
            		echo mb_ucfirst($item['section']->name);
            		echo '</div>';
            }

            // открываем блок для вида деятельности (activity)
            if($activity_save !== $activity){
            	echo '<div class="activity">';
            		//echo '<div class="name">';
            		//echo $item['activity']->name;
            		//echo '</div>';
            		echo '<div class="spoiler">';
					echo '<input style="width:600px;height:45px;" tabindex="-1" type="checkbox" />';
					//echo '<input type="checkbox" >';
					echo '<div class="box">';
					echo '<span class="close name">&#8210; '.mb_ucfirst($item['activity']->name).'</span><span class="open name">&#43; '.mb_ucfirst($item['activity']->name).'</span>';
					//echo '<span class="close">Фотографии коллектива:</span><span class="open">Фотографии коллектива:</span>';
					echo '<blockquote class="Untext">';
            }
            //echo '<h3>'.$item['section']->name.'</h3>';
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
				echo '<p><u>ФИО руководителя (ей)</u>: '.$item['teacher']->surname.' '.$item['teacher']->name.' '.$item['teacher']->name2;
				if(isset($item['dop_teachers']) && is_array($item['dop_teachers'])){
					foreach($item['dop_teachers'] as $dops){
						 echo ', ';
						 echo $dops->teachers->surname.' '.$dops->teachers->name.' '.$dops->teachers->name2;
					}
				}
				echo '</p>';

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

					echo '<div class="spoiler">';
					echo '<input style="width:500px;height:45px;" tabindex="-1" type="checkbox" />';
					//echo '<input type="checkbox" >';
					echo '<div class="box">';
					echo '<span class=close>&#8210; Скрыть</span><span class=open>&#43; Показать</span>';
					//echo '<span class="close">Фотографии коллектива:</span><span class="open">Фотографии коллектива:</span>';
					echo '<blockquote class="Untext">';


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

					echo '</blockquote>';
					echo '</div>';
					echo '</div>';

				echo '</div>';
			}
			echo '</div>';

            //закрываем блок для вида деятельности (activity) если кончились воллективы
            if($i_group >= $count_group){
            	echo '</div>';
            }
            $section_save = $item['section']->name;
            $activity_save = $item['activity']->name;
		}
        echo '</div>';
}
