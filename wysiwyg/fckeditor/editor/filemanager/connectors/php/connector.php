<?php
/*
 * FCKeditor - The text editor for Internet - http://www.fckeditor.net
 * Copyright (C) 2003-2008 Frederico Caldeira Knabben
 *
 * == BEGIN LICENSE ==
 *
 * Licensed under the terms of any of the following licenses at your
 * choice:
 *
 *  - GNU General Public License Version 2 or later (the "GPL")
 *    http://www.gnu.org/licenses/gpl.html
 *
 *  - GNU Lesser General Public License Version 2.1 or later (the "LGPL")
 *    http://www.gnu.org/licenses/lgpl.html
 *
 *  - Mozilla Public License Version 1.1 or later (the "MPL")
 *    http://www.mozilla.org/MPL/MPL-1.1.html
 *
 * == END LICENSE ==
 *
 * This is the File Manager Connector for PHP.
 */

//$_SESSION['test'] = '333';
//print_r($_SESSION);
//print_r($_COOKIE);
//$CI =& get_instance();
//print_r($CI->config);

ob_start() ;

//====== Block code for auth filemanager ========//
//изменяем директорию на корневую framework
chdir('../../../../../../');
//загружаем основной файл
require_once('index.php');

// !!! ставим до очистки буфера иначе не установится параметр в сессию
Modules::run('gate/_auth_wysiwyg');

//очищаем буфер от возвращенной информации
//echo ob_get_contents();
ob_clean();
//меняем путь обратно к filemanager
chdir(dirname(__FILE__));
//возвращаем глобальный объект
$CI =& get_instance();

//устанавливаем сессию для доступа к работе filemanager
//дальнейшая проверка сессии производится в файле config
//$CI->load->module('gate')->_auth_wysiwyg();

//проверяем установилась ли требуемая переменная в сессию
$AU = ($CI->session->userdata('wysiwyg_filemanager') === 'enable')?true:false;
$ci_fck['assets_uploads'] = '/'.$CI->assets->get_assets_uploads() ;

//var_dump($CI->session->userdata('wysiwyg_enable'));
//var_dump(dirname(__FILE__));
//exit;
//========= end Block code auth filemanager ======//

//exit;
require('./config.php') ;
require('./util.php') ;
require('./io.php') ;
require('./basexml.php') ;
require('./commands.php') ;
require('./phpcompat.php') ;

if ( !$Config['Enabled'] )
	SendError( 1, 'Этот коннектор недоступен. Пожалуйста проверьте файл "editor/filemanager/connectors/php/config.php"' ) ;

DoResponse() ;

function DoResponse()
{
	global $Config;
	if (!isset($_GET)) {global $_GET;}
	if ( !isset( $_GET['Command'] ) || !isset( $_GET['Type'] ) || !isset( $_GET['CurrentFolder'] ) )
		return ;

	// Get the main request informaiton.
	$sCommand		= $_GET['Command'] ;
	$sResourceType	= $_GET['Type'] ;
	$sCurrentFolder	= GetCurrentFolder() ;

	// Check if it is an allowed command
	if ( ! IsAllowedCommand( $sCommand ) )
		SendError( 1, 'Команда "' . $sCommand . '" недоступна' ) ;

	// Check if it is an allowed type.
	if ( !IsAllowedType( $sResourceType ) )
		SendError( 1, 'Неверный тип' ) ;

	// File Upload doesn't have to Return XML, so it must be intercepted before anything.
	if ( $sCommand == 'FileUpload' )
	{
		FileUpload( $sResourceType, $sCurrentFolder, $sCommand ) ;
		return ;
	}

	CreateXmlHeader( $sCommand, $sResourceType, $sCurrentFolder ) ;

	// Execute the required command.
	switch ( $sCommand )
	{
		case 'GetFolders' :
			GetFolders( $sResourceType, $sCurrentFolder ) ;
			break ;
		case 'GetFoldersAndFiles' :
			GetFoldersAndFiles( $sResourceType, $sCurrentFolder ) ;
			break ;
		case 'CreateFolder' :
			CreateFolder( $sResourceType, $sCurrentFolder ) ;
			break ;
		case 'FileDelete' :
			if ($Config['Delete']) FileDelete( $sResourceType, $sCurrentFolder, $sCommand );
			break;
		case 'FolderDelete' :
			if ($Config['Delete']) FolderDelete( $sResourceType, $sCurrentFolder, $sCommand );
			break;
	}

	CreateXmlFooter() ;

	exit ;
}
