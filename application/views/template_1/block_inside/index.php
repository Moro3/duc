<?php

//echo "Сегмент: ".$this->uri->segment(1);
//echo "<br>";
//echo "RСегмент: ".$this->uri->rsegment(1);



if($this->uri->segment(1)){
	switch($this->uri->segment(1)){
		case 'news':
                    echo Modules::run('news/news_user_news/user_index');
		break;
		case 'duc':
                    echo Modules::run('duc/duc/user_index');
		break;
		case 'gallery':
                    echo Modules::run('gallery/gallery/index');
		break;
		default:
            if($contents['content'] !== false){
        		echo $contents['content'];
    		}else{
                echo Modules::run('pages/pages/page_404');
      		}
	}
}