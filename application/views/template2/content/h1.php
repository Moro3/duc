<table width="100%" cellpadding="0">
<tr>
<td height="15"><img src="<?php echo assets_img('00.gif', false) ?>" width="1" height="1"></td>
</tr>
<tr>
<td align="left" class="textosn">
	<table width="100%" height="40" cellpadding="0" >
	<tr>
	  <td width="3%" align="left" background="<?php echo assets_img('pl2_centr.gif', false) ?>"><img src="<?php echo assets_img('pl2_left.gif', false) ?>" width="6" height="40"></td>
	  <td width="96%" align="left" background="<?php echo assets_img('pl2_centr.gif', false) ?>">
	  <?php
	  if(isset($contents['page']['active']) && $contents['page']['active'] == 1){
		  if(isset($contents['page']['content'][0]->seo[0]->h1)){
		  		echo '<h1>'.$contents['page']['content'][0]->seo[0]->h1.'</h1>';
		  }elseif(isset($contents['page']['content'][0]->seo[0]->title)){		  	    echo '<h1>'.$contents['page']['content'][0]->seo[0]->title.'</h1>';
		  }elseif(isset($contents['page']['content'][0]->name)){		  	    echo '<h1>'.$contents['page']['content'][0]->name.'</h1>';
		  }else{		  	   echo '<h1></h1>';
		  }
	  }
	  ?>
	  </td>
	  <td width="1%" align="right" background="<?php echo assets_img('pl2_centr.gif', false) ?>"><img src="<?php echo assets_img('pl2_right.gif', false) ?>" width="6" height="40"></td>
	</tr>
  	</table></td>
</tr>
<tr>
	<td>

    </td>
</tr>
</table>