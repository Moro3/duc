<?php
  	$this->load->view('template_1/linkToTop');
?>
<div class='info'>
	<div class='copyright'>
	© 2011-<?php echo date('Y', time()); ?> {cfg name='short_name'}. Все права защищены.
	</div>
	<div class='address'>
	{cfg name='postal_code'}, г.{cfg name='city'}, {cfg name='address'}
	</div>
	<div class='phone'>
	тел. {cfg name='tel1'}, {cfg name='tel2'}
	</div>

</div>

<div class='counter'>

</div>