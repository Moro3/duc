<?php
  $style='
    	.block_main_service{    		width:100%;
            padding-top:10px;
    	}
    	.block_main_service .size_border{    		border-right-width:1px;
    		border-right-color:#ccaf3a;
    		border-right-color:#bdbdbd;
    		border-right-style: solid;
    	}
    	.block_main_service .top_border{
    		border-top-width:0px;
    		border-top-color:#ccaf3a;
    		border-top-style: dashed;
    	}
    	.service_item{    		vertical-align:top;    		width:30%;
    		padding:0 15px;
            1float:left;
    	}
    	.service_item table{            height:100%;
            vertical-align:top;
    	}
    	.service_item table tr th{
        	font-size:18px;
        	height:46px;
        	color:#666666;
        	text-align:left;
        	vertical-align:top;
    	}
    		.service_item table tr th a{    			color:#666666;
    			text-decoration: none;
    		}
    	.service_item table tr td{
        	padding:3px;
    	}
    	.service_item table tr td.img{
            vertical-align:top;
    	}
    		.service_item table tr td.img img{
            	width:94px;
            	height:132px;
            	border:0;
    		}
    	.service_item table tr td.content{
            vertical-align:top;
    	}
    		.service_item .content .question{
            	color:#378a00;
            	font-weight:bold;
            	vertical-align:top;
    		}
    	.service_item table tr td.content_icon{
            height:30px;
            vertical-align:top;
    	}
    	.service_item table tr td.description{
        	text-align:justify;
        	vertical-align:top;
        	height:110px;
        	overflow:hidden;
    	}
    	.block_main_service .price{
        	text-align:center;
    	}
	    	.block_main_service .price p{
	            padding:10px;
	            background-color:#C1DC96;
	        	border-radius: 0 8px 8px 8px;
	        	height:40px;
	    	}
	    		.block_main_service .price p a{	    			font-size:larger;
	    			color:#1d9e41;
	    		}

    ';

	assets_style($style, false);
?>


<div class="block_main_service">
	<!--<div class="service_item size_border">-->
	<table><tr><td class="service_item size_border top_border">
    	<table >
    		<tr>
    			<th colspan="3">
    			    <a href="<?php echo Modules::run('pages/pages/getFieldUri', 'sidelka'); ?>">
    			    Сиделка
    			    </a>
    			</th>
    		</tr>
    		<tr>
    		    <td rowspan="2" class="img">
    		        <a href="<?php echo Modules::run('pages/pages/getFieldUri', 'sidelka'); ?>">
    		        <img src="<?php echo assets_img('block-foto-2.jpg', false) ?>" alt="Уход за больными">
    		        </a>
    		    </td>
    		    <td class="content_icon">
    		        <img src="<?php echo assets_img('question.gif', false) ?>">
    		    </td>
    		    <td class="content">
    		        <!--<a href="<?php echo Modules::run('pages/pages/getFieldUri', 'advice'); ?>" class="textlink1">Ищите сиделку для больного человека?</a>-->
    		        <span class="question">Ищите сиделку для больного человека?</span>
    		    </td>
    		</tr>
    		<tr>
    		    <td class="content_icon">
    		        <img src="<?php echo assets_img('answer.gif', false) ?>">
    		    </td>
    		    <td class="content">
    		        <a href="<?php echo Modules::run('pages/pages/getFieldUri', 'sidelka'); ?>" class="textlink1">Наш персонал обеспечит квалифицированный уход за больным человеком!</a>
    		    </td>
    		</tr>
    		<tr>
    			<td colspan="3" class="description">
    			    Наши квалифицированные, доброжелательные медицинские сестры, сиделки имеют большой опыт в оказании услуг по уходу за тяжелобольными в различных областях медицины.
    			    <ul>
					<li><a href="<?php echo Modules::run('pages/pages/getFieldUri','sidelka');?>">Сиделка с проживанием</a></li>
					<li><a href="<?php echo Modules::run('pages/pages/getFieldUri','sidelka_prixodyashhaya');?>">Сиделка приходящая</a></li>
					<li><a href="<?php echo Modules::run('pages/pages/getFieldUri','sidelka_v_bolqnicu');?>">Сиделка в больницу</a></li>
					<!--
					<li><a href="<?php echo Modules::run('pages/pages/getFieldUri','nanya');?>">Няня с проживанием</a></li>
					<li><a href="<?php echo Modules::run('pages/pages/getFieldUri','nanny-governess');?>">Няни гувернантки</a></li>
					<li><a href="<?php echo Modules::run('pages/pages/getFieldUri','housemaid');?>">Домработница</a></li>
					-->
					</ul>
<!--<br />Выполняем все виды медицинских манипуляций: внутримышечные и внутривенные инъекции, капельницы, перевязки, обработка пролежней, клизмы, ЛФК, мануальная терапия, массаж.
-->
    			</td>
    		</tr>

    	</table>

	<!--
	</div>
	<div class="service_item size_border">
	-->
	</td>
	<td class="service_item size_border top_border">
    	<table>
    		<tr>
    			<th colspan="3">
    			    <a href="<?php echo Modules::run('pages/pages/getFieldUri', 'transport'); ?>">
    			    Перевозка больных автомобилем
    			    </a>
    			</th>
    		</tr>
    		<tr>
    		    <td rowspan="2" class="img">
    		        <a href="<?php echo Modules::run('pages/pages/getFieldUri', 'transport'); ?>">
    		        <img src="<?php echo assets_img('block-foto-1.jpg', false) ?>" alt="Перевозка больных автомобилем">
    		        </a>
    		    </td>
    		    <td class="content_icon">
    		        <img src="<?php echo assets_img('question.gif', false) ?>">
    		    </td>
    		    <td class="content">
    		        <!--<a href="<?php echo Modules::run('pages/pages/getFieldUri', 'advice'); ?>" class="textlink1">Нужно перевезти больного?</a>-->
    		        <span class="question">Нужно перевезти больного?</span>
    		    </td>
    		</tr>
    		<tr>
    		    <td class="content_icon">
    		        <img src="<?php echo assets_img('answer.gif', false) ?>">
    		    </td>
    		    <td class="content">
    		        <a href="<?php echo Modules::run('pages/pages/getFieldUri', 'transport'); ?>" class="textlink1">Перевозка больных с травмами, инвалидов или людей с проблемами опорно-двигательного аппарата!</a>
    		    </td>
    		</tr>
    		<tr>
    			<td colspan="3" class="description">
    			    В качестве автомобиля используются отечественные и зарубежные модели, которые имеют необходимый размер и возможности для транспортировки всего необходимого: оборудования и медикаментов.

    			    <ul>
					<li><a href="<?php echo Modules::run('pages/pages/getFieldUri','perevozka_bolqnyx_avtomobilem_skoroy_pomoschi');?>">Автомобиль скорой помощи.</a></li>
					<li><a href="<?php echo Modules::run('pages/pages/getFieldUri','perevozka_bolqnyx_reanimobilem');?>">Реанимобиль.</a></li>
					</ul>
    			    <!--
    			    <br />
    			    Для переездов по городу используется комфортабельный транспорт, снабженный всем необходимым для того, чтобы обеспечить удобство больному.
    			    -->
    			</td>
    		</tr>
    	</table>

	<!--
	</div>
	<div class="service_item">
	-->
	</td>
	<td class="service_item top_border">
    	<table>
    		<tr>
    			<th colspan="3">
    			    <a href="<?php echo Modules::run('pages/pages/getFieldUri', 'sposoby_aviaperevozki'); ?>">
    			    Перевозка больных самолетом
    			    </a>
    			</th>
    		</tr>
    		<tr>
    		    <td rowspan="2" class="img">
    		        <a href="<?php echo Modules::run('pages/pages/getFieldUri', 'sposoby_aviaperevozki'); ?>">
    		        <img src="<?php echo assets_img('block-foto-5.jpg', false) ?>" alt="Авиаперевозка больных - санавиация">
    		        </a>
    		    </td>
    		    <td class="content_icon">
    		        <img src="<?php echo assets_img('question.gif', false) ?>">
    		    </td>
    		    <td class="content">
    		        <!--<a href="<?php echo Modules::run('pages/pages/getFieldUri', 'advice'); ?>" class="textlink1">Больной нуждается в авиаперелёте?</a>-->
    		        <span class="question">Больной нуждается в авиаперелёте?</span>
    		    </td>
    		</tr>
    		<tr>
    		    <td class="content_icon">
    		        <img src="<?php echo assets_img('answer.gif', false) ?>">
    		    </td>
    		    <td class="content">
    		        <a href="<?php echo Modules::run('pages/pages/getFieldUri', 'sposoby_aviaperevozki'); ?>" class="textlink1">Транспортировка при госпитализации больного в профильный стационар или организации лечения за рубежом!</a>
    		    </td>
    		</tr>
    		<tr>
    			<td colspan="3" class="description">
    			    Осуществляем перелет больного на дальние расстояния.
    			    К вашим услугам авиаперевозка больных:
				    <ul>
					<li><a href="<?php echo Modules::run('pages/pages/getFieldUri','perevozka_bolqnyx_rejsovym_samoletom');?>">Рейсовым самолетом.</a></li>
					<li><a href="<?php echo Modules::run('pages/pages/getFieldUri','perevozka_bolqnyx_chastnym_samoletom');?>">Частным самолетом.</a></li>
					</ul>
				</td>
    		</tr>
    	</table>


	</td></tr>

	<tr>
    			<td  class="price service_item size_border">
    				<p>
    				<a href="<?php echo Modules::run('pages/pages/getFieldUri','tariff_sidelka');?>">Цены на услуги сиделки</a>
    				</p>
    			</td>
    			<td  class="price service_item size_border">
    				<p>
    				<a href="<?php echo Modules::run('pages/pages/getFieldUri','transport');?>">Цены на услуги перевозка больных автомобилем</a>
    				</p>
    			</td>
    			<td  class="price service_item">
    				<p>
    				<a href="<?php echo Modules::run('pages/pages/getFieldUri','transport');?>">Цены на услуги перевозка больных самолетом</a>
    				</p>
    			</td>
    </tr>
	</table>
	<!--</div>-->
</div>