<?php
  $style='
    	.block_main_service{    		width:100%;
            padding:10px;
    	}
    	.block_main_service .size_border{
    		1height:350px;
    		border-right-width:4px;
    		border-color:#ccaf3a;
    		border-right-style: solid;
    		overflow: hidden;
    		zoom: 1;
    	}
    	.service_item{    		width:30%;
    		padding:1%;
    		1overflow: hidden;
    		1zoom: 1;
    		float:left;

    	}
    	.service_item .zag1{        	font-size:x-large;
        	height:50px;
        	text-align:center;
    	}
    	.service_item .service_img{
        	float:left;
        	padding:10px;
    	}
    		.service_item .service_img img{

    		}
    	.service_item .question{
        	1width:180px;
        	height:70px;
        	overflow: hidden;
    		zoom: 1;
    	}
    	.service_item .answer{
           height:80px;
           overflow: hidden;
    		zoom: 1;
    	}
    	.service_item .cont_icon{
           float:left;
           width:40px;
           display: inline-block;
    	}
    	.service_item .cont{
           1width: 140px;
           1float:left;
           1display: inline-block;
           overflow: hidden;
    	}
    	.service_item .description{
           float:left;
           display: inline-block;
           overflow: hidden;
           text-align:justify;
           padding:5px;
    	}


  ';

	assets_style($style, false);
?>


<div class="block_main_service">
	<div class="service_item">
		<div class="size_border">
    	<div class="zag1">
    		Сиделка
    	</div>
    	<div class="service_img">
    		<img src="<?php echo assets_img('foto2.jpg', false) ?>" width="94" height="132" alt="Уход за больными">
    	</div>
    	<div class="question">
    		<div class="cont_icon">
    		<img src="<?php echo assets_img('question.gif', false) ?>" width="25" height="24">
    		</div>
    		<div class="cont">
    		<a href="<?php echo Modules::run('pages/pages/getFieldUri', 'advice'); ?>" class="textlink1">Ищите сиделку для больного человека?</a>
    		</div>

    	</div>
    	<div class="answer">
    		<div class="cont_icon">
    		<img src="<?php echo assets_img('answer.gif', false) ?>" width="25" height="24">
    		</div>
    		<div class="cont">
    		<a href="<?php echo Modules::run('pages/pages/getFieldUri', 'advice'); ?>" class="textlink1">Наш персонал обеспечит квалифицированный уход за больным человеком!</a>
    		</div>

    	</div>
    	<div class="description">
    		Наши квалифицированные, доброжелательные медицинские сестры, сиделки имеют большой опыт в оказании услуг по уходу за тяжелобольными в различных областях медицины (неврология, онкология, терапия, хирургия, гематология, травматология и др.)
			<br />Выполняем все виды медицинских манипуляций: внутримышечные и внутривенные инъекции, капельницы, перевязки, обработка пролежней, клизмы, ЛФК, мануальная терапия, массаж.
  		</div>
  		</div>
	</div>

	<div class="service_item">
    	<div class="size_border">
    	<div class="zag1">
    		Перевозка больных автомобилем
    	</div>
    	<div class="service_img">
    		<img src="<?php echo assets_img('foto2.jpg', false) ?>" width="94" height="132" alt="Уход за больными">
    	</div>
    	<div class="question">
    		<div class="cont_icon">
    		<img src="<?php echo assets_img('question.gif', false) ?>" width="25" height="24">
    		</div>
    		<div class="cont">
    		<a href="<?php echo Modules::run('pages/pages/getFieldUri', 'advice'); ?>" class="textlink1">Ищите сиделку для больного человека?</a>
    		</div>

    	</div>
    	<div class="answer">
    		<div class="cont_icon">
    		<img src="<?php echo assets_img('answer.gif', false) ?>" width="25" height="24">
    		</div>
    		<div class="cont">
    		<a href="<?php echo Modules::run('pages/pages/getFieldUri', 'advice'); ?>" class="textlink1">Наш персонал обеспечит квалифицированный уход за больным человеком!</a>
    		</div>

    	</div>
    	<div class="description">
    		В качестве автомобиля используются отечественные и зарубежные модели, которые имеют необходимый размер и возможности для транспортировки всего необходимого: оборудования и медикаментов.
    	</div>
    	</div>
	</div>

	<div class="service_item">
    	<div class="zag1">
    		Перевозка больных самолетом
    	</div>
    	<div class="service_img">
    		<img src="<?php echo assets_img('foto2.jpg', false) ?>" width="94" height="132" alt="Уход за больными">
    	</div>
    	<div class="question">
    		<div class="cont_icon">
    		<img src="<?php echo assets_img('question.gif', false) ?>" width="25" height="24">
    		</div>
    		<div class="cont">
    		<a href="<?php echo Modules::run('pages/pages/getFieldUri', 'advice'); ?>" class="textlink1">Ищите сиделку для больного человека?</a>
    		</div>

    	</div>
    	<div class="answer">
    		<div class="cont_icon">
    		<img src="<?php echo assets_img('answer.gif', false) ?>" width="25" height="24">
    		</div>
    		<div class="cont">
    		<a href="<?php echo Modules::run('pages/pages/getFieldUri', 'advice'); ?>" class="textlink1">Наш персонал обеспечит квалифицированный уход за больным человеком!</a>
    		</div>

    	</div>
    	<div class="description">
    		 При перелете на самолете больному нужно обеспечить дополнительный уход:
		    <ul>
			<li>Подготовить пациента к перевозке, учитывая сложность его состояния.</li>
			<li>Осуществить посадку в автотранспорт.</li>
			<li>Следить за состоянием больного в процессе перевозки, обеспечивать безопасность.</li>
			<li>Осуществлять высадку к месту назначения.</li>
			</ul>
		</div>
	</div>
</div>