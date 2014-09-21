/*
	Официальный плагин для плеера Uppod (http://uppod.ru)
	v. 1.0, 2012
	Описание - http://uppod.ru/help/q=ckeditor
*/

//------------------
// НАЧАЛО НАСТРОЕК
//------------------

// UPPOD_FOLDER - путь к папке с файлами плеера, например http://site.ru/player/

var UPPOD_FOLDER = '/uploads/flash/pleer/uppod.swf';

// UPPOD_SIZES - размеры плеера в режиме видео и аудио

var UPPOD_SIZES=[];UPPOD_SIZES['video']=[];UPPOD_SIZES['audio']=[];UPPOD_SIZES['photo']=[];

UPPOD_SIZES['video']['width']=500;
UPPOD_SIZES['video']['height']=375;

UPPOD_SIZES['audio']['width']=300;
UPPOD_SIZES['audio']['height']=90;

UPPOD_SIZES['photo']['width']=500;
UPPOD_SIZES['photo']['height']=375;

// UPPOD_STYLES - стили плеера, которые лежат в UPPOD_FOLDER, например [['Стили',''],['Видео','video.txt'],['Аудио','audio.txt']]

var UPPOD_STYLES=[['Стили','']];

// UPPOD_BGCOLOR - цвет фона плеера в коде (по-умолчанию белый)

var UPPOD_BGCOLOR='#ffffff';

//------------------
// КОНЕЦ НАСТРОЕК
//------------------

FCKeditor.plugins.add('uppod', {
  init : function(editor) {
    var command = editor.addCommand('uppod', new FCKeditor.dialogCommand('uppod'));
    command.modes = {wysiwyg:1, source:1};
    command.canUndo = true;

    editor.ui.addButton('Uppod', {
      label : 'Создать Uppod',
      command : 'uppod',
      icon: this.path + 'images/logo.jpg'
    });

    FCKeditor.dialog.add('uppod', this.path + 'dialogs/uppod.js');
  }
});
