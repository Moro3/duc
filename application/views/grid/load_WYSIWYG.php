<?php

//-------------- ���������� ������ ���������� �������� (WYSIWYG)

/*
�������� ������
  ������������:
  	$name - string - ��� ��������� (������������ ���� � ������ load_$name)

*/
if(isset($name)){	$this->load->view('grid/load_'.$name);
}else{	echo '�� ������� ��������� ��������, �.�. �� ������� ��� ���';
}
