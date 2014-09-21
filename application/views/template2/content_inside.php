<?php

  //$p_id = $this->bufer->pages['id'];
  //echo Modules::run('pages/pages/breadcrumbs', $p_id, 'side_left');
  echo $breadcrumbs;
?>
<table width="100%" cellpadding="0">
<tr>
<td width="100%" align="right" valign="top">
<?

	include ('content/h1.php');
?>
</td>
</tr>
<tr>
<td height="4" colspan="2"><table width="100%" cellpadding="10">
<tr>
<td class="textosn">

<?php

  if(isset($contents['page']['content'])){
  	echo $contents['page']['content'][0]->description;
  }
?>
</td>
</tr>
</table></td>
</tr>
</table>