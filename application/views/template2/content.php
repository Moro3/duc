<?php
  //if(isset($breadcrumbs))  echo $breadcrumbs;
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
<td height="4" colspan="2">


<?php
	include('content/block4.php');
	//include('content/block1.php');
?>


	<table width="100%" cellpadding="10">
	<tr>
	<td class="textosn">

	<?php

	  if(isset($contents['page']['content'])){	  	echo $contents['page']['content'][0]->description;
	  }
	?>

	</td>
	</tr>
	</table>
</td>
</tr>
</table>
