<?php

if(isset($objects) && is_array($objects)){
	$resize_config = Modules::run('duc/duc_settings/get_config_resize', 'groups');
	$resize_param = Modules::run('duc/duc_settings/get_param_resize', 'groups');
		
?>
			<!-- The Bootstrap Image Gallery lightbox, should be a child element of the document body -->
			<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-use-bootstrap-modal="false">
			    <!-- The container for the modal slides -->
			    <div class="slides"></div>
			    <!-- Controls for the borderless lightbox -->
			    
			    <h3 class="title"></h3>
			    <a class="prev">‹</a>
			    <a class="next">›</a>
			    <a class="close">×</a>
			    <a class="play-pause"></a>
			    <ol class="indicator"></ol>
			    
			    <!-- The modal dialog, which will be used to wrap the lightbox content -->
			    
			    <div class="modal fade">
			        <div class="modal-dialog">
			            <div class="modal-content">
			                <div class="modal-header">
			                    <button type="button" class="close" aria-hidden="true">&times;</button>
			                    <h4 class="modal-title"></h4>
			                </div>
			                <div class="modal-body next"></div>
			                <div class="modal-footer">
			                    <button type="button" class="btn btn-default pull-left prev">
			                        <i class="glyphicon glyphicon-chevron-left"></i>
			                        Previous
			                    </button>
			                    <button type="button" class="btn btn-primary next">
			                        Next
			                        <i class="glyphicon glyphicon-chevron-right"></i>
			                    </button>
			                </div>
			            </div>
			        </div>
			    </div>
			    
			</div>
<?php
			echo '<div id="links">';						
				
				foreach($objects as $photos){
					if(!empty($photos->img)){
						$path = $config['path']['root'];
						$dir = $resize_config['path'].'/'.$resize_param['big']['dir'].'/';
						$dir_thumb = $resize_config['path'].'/'.$resize_param['mini']['dir'].'/';
						if(is_file($path.$dir.$photos->img)){
							if($photos->active == 1){
								echo '<a href="/'.$dir.$photos->img.'" title="'.$photos->name.'" data-gallery>';
								echo '<img src="/'.$dir_thumb.$photos->img.'" alt="'.$photos->name.'">';
								echo '</a>';
							}
						}
					}
				}
				
			echo '</div>';
			
}