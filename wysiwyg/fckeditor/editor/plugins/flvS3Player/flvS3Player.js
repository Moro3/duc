//------------------------------------------------------
// This plugin created by Aleksandr Popov
// http://s3blog.org
// 5 september 2009
//------------------------------------------------------

var oEditor = window.parent.InnerDialogLoaded() ;
var FCK		= oEditor.FCK ;

// Set the language direction.
window.document.dir = oEditor.FCKLang.Dir ;

// Set the Skin CSS.
document.write( '<link href="' + oEditor.FCKConfig.SkinPath + 'fck_dialog.css" type="text/css" rel="stylesheet">' ) ;

var sAgent = navigator.userAgent.toLowerCase() ;

var is_ie = (sAgent.indexOf("msie") != -1); // FCKBrowserInfo.IsIE
var is_gecko = !is_ie; // FCKBrowserInfo.IsGecko

var oMedia = null;
var is_new_flvplayer = true;

function window_onload()
{
	// Translate the dialog box texts.
	oEditor.FCKLanguageManager.TranslatePage(document) ;

	// Load the selected element information (if any).
	LoadSelection() ;

	// Show/Hide the "Browse Server" button.
	GetE('tdBrowse').style.display = oEditor.FCKConfig.FlashBrowser ? '' : 'none' ;

	// Activate the "OK" button.
	window.parent.SetOkButton( true ) ;
}


function getSelectedMovie(){
	var oSel = null;
	oMedia = new Media();
	oSel = FCK.Selection.GetParentElement();
	// If in "Get the Flash Player" a href, do it again
	if (oSel.id != null && !oSel.id.match(/^flvS3player[0-9]*$/)) {
		oSel = oSel.parentNode;
	}
	if (oSel.id != null && oSel.id.match(/^flvS3player[0-9]*$/)) {
		for (var i = 0; i < oSel.childNodes.length; i++) {
			if (oSel.childNodes.item(i).nodeName=="DIV") {
				var oC=oSel.childNodes.item(i).innerHTML.split(' ');
				for (var o = 0; o < oC.length ; o++) {
					var tmp=oC[o].split('=');
					var tmp2 = '';
					var tmp3 = '';
					for (var it=1; it<tmp.length; it++) {
						tmp2 += tmp3 + tmp[it];
						tmp3 = '=';
					}
					oMedia.setAttribute(tmp[0],tmp2);
				}
				is_new_flvplayer = false;
			}
		}
	}
	return oMedia;
}

function LoadSelection()
{
	oMedia = new Media();
	oMedia = getSelectedMovie();

	GetE('txtUrl').value    	= oMedia.url;
	GetE('txtImgURL').value    	= oMedia.iurl;
	GetE('txtWidth').value		= oMedia.width;
	GetE('txtHeight').value		= oMedia.height;
	GetE('txtPaddingL').value		= oMedia.paddingL;
	GetE('txtPaddingR').value		= oMedia.paddingR;
	GetE('txtPaddingT').value		= oMedia.paddingT;
	GetE('txtPaddingB').value		= oMedia.paddingB;
	GetE('selAlign').value		= oMedia.align;
	GetE('chkInternalStyle').checked	= oMedia.istyle;
	GetE('btnStyleBrowse').disabled		= oMedia.istyle;
	GetE('txtExternalStyleURL').disabled	= oMedia.istyle;
	GetE('txtExternalStyleURL').value		= oMedia.estyleUrl;
}

//#### The OK button was hit.
function Ok()
{
	if ( GetE('txtUrl').value.length == 0 )
	{
		GetE('txtUrl').focus() ;

		alert( oEditor.FCKLang.DlgFLVS3PlayerAlertUrl ) ;
		return false ;
	}

	if ( GetE('txtWidth').value.length == 0 )
	{
		GetE('txtWidth').focus() ;

		alert( oEditor.FCKLang.DlgFLVS3PlayerAlertWidth ) ;
		return false ;
	}

	if ( GetE('txtHeight').value.length == 0 )
	{
		GetE('txtHeight').focus() ;

		alert( oEditor.FCKLang.DlgFLVS3PlayerAlertHeight ) ;
		return false ;
	}

	if ( GetE('txtPaddingL').value.length == 0 )
	{
		GetE('txtPaddingL').focus() ;

		alert( oEditor.FCKLang.DlgFLVS3PlayerPaddingL ) ;
		return false ;
	}
	if ( GetE('txtPaddingR').value.length == 0 )
	{
		GetE('txtPaddingR').focus() ;

		alert( oEditor.FCKLang.DlgFLVS3PlayerPaddingR ) ;
		return false ;
	}
	if ( GetE('txtPaddingT').value.length == 0 )
	{
		GetE('txtPaddingT').focus() ;

		alert( oEditor.FCKLang.DlgFLVS3PlayerPaddingT ) ;
		return false ;
	}
	if ( GetE('txtPaddingB').value.length == 0 )
	{
		GetE('txtPaddingB').focus() ;

		alert( oEditor.FCKLang.DlgFLVS3PlayerPaddingB ) ;
		return false ;
	}


	var e = (oMedia || new Media()) ;

	updateMovie(e) ;

	// Replace or insert?
	if (!is_new_flvplayer) {
		// Find parent..
	        oSel = FCK.Selection.GetParentElement();
		while (oSel != null && !oSel.id.match(/^flvS3player[0-9]*-parent$/)) {
			oSel=oSel.parentNode;
		}
		// Found - So replace
		if (oSel != null) {
			oSel.parentNode.removeChild(oSel);
			FCK.InsertHtml(e.getInnerHTML());
		}
	} else {
		FCK.InsertHtml(e.getInnerHTML());
	}

	return true ;
}


function updateMovie(e){
	e.url = GetE('txtUrl').value;
	e.iurl = GetE('txtImgURL').value;
	e.width = (isNaN(GetE('txtWidth').value)) ? 0 : parseInt(GetE('txtWidth').value);
	e.height = (isNaN(GetE('txtHeight').value)) ? 0 : parseInt(GetE('txtHeight').value);
	e.paddingL = (isNaN(GetE('txtPaddingL').value)) ? 0 : parseInt(GetE('txtPaddingL').value);
	e.paddingR = (isNaN(GetE('txtPaddingR').value)) ? 0 : parseInt(GetE('txtPaddingR').value);
	e.paddingT = (isNaN(GetE('txtPaddingT').value)) ? 0 : parseInt(GetE('txtPaddingT').value);
	e.paddingB = (isNaN(GetE('txtPaddingB').value)) ? 0 : parseInt(GetE('txtPaddingB').value);
	e.align =	GetE('selAlign').value;
	e.istyle = (GetE('chkInternalStyle').checked) ? 'true' : 'false';
	e.estyleUrl = GetE('txtExternalStyleURL').value;

}


function BrowseServer()
{
	OpenServerBrowser(
		'flv',
		oEditor.FCKConfig.FlashBrowserURL,
		oEditor.FCKConfig.FlashBrowserWindowWidth,
		oEditor.FCKConfig.FlashBrowserWindowHeight )
;}

function imgBrowseServer()
{
	OpenServerBrowser(
		'img',
		oEditor.FCKConfig.ImageBrowserURL,
		oEditor.FCKConfig.ImageBrowserWindowWidth,
		oEditor.FCKConfig.ImageBrowserWindowHeight ) ;
}

function styleBrowseServer()
{
	OpenServerBrowser(
		'style',
		oEditor.FCKConfig.LinkBrowserURL,
		oEditor.FCKConfig.LinkBrowserWindowWidth,
		oEditor.FCKConfig.LinkBrowserWindowHeight ) ;
}

function OpenServerBrowser( type, url, width, height )
{
	sActualBrowser = type ;

	OpenFileBrowser( url, width, height ) ;
}

var sActualBrowser ;


function SetUrl( url ) {
	if ( sActualBrowser == 'flv' ) {
		document.getElementById('txtUrl').value = url ;
//		GetE('txtHeight').value = GetE('txtWidth').value = '' ;
	} else if ( sActualBrowser == 'img' ) {
		document.getElementById('txtImgURL').value = url ;
	} else if ( sActualBrowser == 'style' ) {
		document.getElementById('txtExternalStyleURL').value = url ;
	}
}


var Media = function (o){
	this.url = '';
	this.iurl = '';
	this.width = '400';
	this.height = '300';
	this.paddingL = '';
	this.paddingR = '';
	this.paddingT = '';
	this.paddingB = '';
	this.align = '';
	this.istyle = 'true';
	this.estyleUrl = '';

	if (o)
		this.setObjectElement(o);
};

Media.prototype.setObjectElement = function (e){
	if (!e) return ;
	this.width = GetAttribute( e, 'width', this.width );
	this.height = GetAttribute( e, 'height', this.height );
	this.paddingL = GetAttribute( e, 'padding-left', this.paddingL );
	this.paddingR = GetAttribute( e, 'padding-right', this.paddingR );
	this.paddingT = GetAttribute( e, 'padding-top', this.paddingT );
	this.paddingB = GetAttribute( e, 'padding-bottom', this.paddingB );
};

Media.prototype.setAttribute = function(attr, val) {
	if (val=="true") {
		this[attr]=true;
	} else if (val=="false") {
		this[attr]=false;
	} else {
		this[attr]=val;
	}
};

Media.prototype.getInnerHTML = function (objectId){
	var randomnumber = Math.floor(Math.random()*1000001);
	var thisWidth = this.width;
	var thisHeight = this.height;
	var thisPaddingL = this.paddingL;
	var thisPaddingR = this.paddingR;
	var thisPaddingT = this.paddingT;
	var thisPaddingB = this.paddingB;

	// Align
	var cssalign='';
	var cssfloat='';

 	if (this.align=="center") {
		cssalign='margin-left: auto;margin-right: auto;';
	} else if (this.align=="right") {
		cssfloat='float: right;';
	} else if (this.align=="left") {
		cssfloat='float: left;';
	}
	csspadding = 'padding-left: '. + thisPaddingL +'px;' + 'padding-right: '. + thisPaddingR +'px;' + 'padding-top: '. + thisPaddingT +'px;' +'padding-bottom: '. + thisPaddingB +'px;';

	var s = "";
	//s+= '<p>\n';
	s+= '<div id="flvS3player' + randomnumber + '-parent" style="text-align: center;' + csspadding + cssfloat + '">\n';
	s+= '<div style="border-style: none; height: ' + thisHeight + 'px; width: ' + thisWidth + 'px; overflow: hidden; background-color: rgb(220, 220, 220); background-image: url(' + oEditor.FCKConfig.PluginsPath + 'flvS3Player/flvS3Player.gif); background-repeat:no-repeat; background-position:center;' + cssalign + '">';
	s+= '<script src="' + oEditor.FCKConfig.PluginsPath + 'flvS3Player/swfobject.js" type="text/javascript"></script>\n';
	s+= '<div id="flvS3player' + randomnumber + '">';
	s+= '<a href="http://www.macromedia.com/go/getflashplayer">Get the Flash Player</a> to see this player.';
	// Moved after info - Added width,height,overflow for MSIE7


	s+= '<div id="flvS3player' + randomnumber + '-config" style="display: none;visibility: hidden;width: 0px;height:0px;overflow: hidden;">';
	// Save settings
	for (var i in this) {
		if (!i || !this[i]) continue;
	        if (!i.match(/(set|get)/)) {
        	        s+=i+"="+this[i]+" ";
        	}
	}
	s+= '</div>';

	s+= '</div>';

	s+= '<script type="text/javascript">\n';

	var videoStyle = oEditor.FCKConfig.PluginsPath + 'flvS3Player/videoStyle.txt';

	if ((this.istyle == 'false') && this.estyleUrl) {
		videoStyle = this.estyleUrl;
	}

	var poster = (this.iurl) ? ',"poster":"' + this.iurl + '"' : '';
	var poster2 = (this.iurl) ? '&amp;poster=' + this.iurl : '';

	s+= 'var flashvars' + randomnumber + ' = {"st":"' + videoStyle + '","file":"' + this.url + '"' + poster + '};';
	s+= 'var params' + randomnumber + ' = {wmode:"transparent", allowFullScreen:"true", allowScriptAccess:"always"}; ';

	s+= ' new swfobject.embedSWF("' + oEditor.FCKConfig.PluginsPath + 'flvS3Player/uppod.swf", "flvS3player' + randomnumber + '", "' + thisWidth +'", "' + thisHeight + '", "9.0.0", false, flashvars' + randomnumber + ', params' + randomnumber + ');';

	s+= '</script>\n';

/*
	s+= '<noscript>\n';
	s+= '<object id="flvS3player' + randomnumber + '" width="' + thisWidth +'" height="' + thisHeight + '">';
	s+= '<param name="allowFullScreen" value="true" />';
	s+= '<param name="allowScriptAccess" value="always" />';
	s+= '<param name="wmode" value="transparent" />';
	s+= '<param name="movie" value="' + oEditor.FCKConfig.PluginsPath + 'flvS3Player/uppod.swf" />';
	s+= '<param name="flashvars" value="st=' + videoStyle + '&amp;file=' + this.url + poster2 + '" />';
	s+= '<embed src="' + oEditor.FCKConfig.PluginsPath + 'flvS3Player/uppod.swf" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" wmode="transparent" flashvars="st=' + videoStyle + '&amp;file=' + this.url + poster2 + '" width="' + thisWidth +'" height="' + thisHeight + '"></embed>';
	s+= '</object>';
	s+= '</noscript>';
*/

	s+= '</div>\n';
	s+= '</div>\n';
	//s+= '</p>\n';

	return s;
};
