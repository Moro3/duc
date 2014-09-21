var InsertFilelist=function(){
//создаем новую команду
};
InsertFilelist.GetState=function() {
return FCK_TRISTATE_OFF; //не надо делать кнопку переключаемой
}
InsertFilelist.Execute=function() {
    FCK.Focus();
    FCK.InsertHtml(''); //вставляем произвольный html
}
FCKCommands.RegisterCommand( 'Filelist' , InsertFilelist ) ; // регистрируем команду
var oFindItem = new FCKToolbarButton( 'Filelist', 'Файлы' ) ; // создаем кнопку
oFindItem.IconPath = FCKConfig.PluginsPath + 'separator/separator.png' ; // иконку к ней
FCKToolbarItems.RegisterItem( 'Filelist', oFindItem ) ; // 'связываем команду и кнопку