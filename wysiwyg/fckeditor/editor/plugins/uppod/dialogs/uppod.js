FCKeditor.dialog.add('uppod', function(editor) {
  return {
    title : 'Плеер Uppod',
    minWidth : 400,
    minHeight : 200,
    onShow: function () {
        var A = this;
        var o = A.getSelectedElement();
        if (o && o.data('cke-real-element-type') && o.data('cke-real-element-type') == 'flash') {
        	//alert(o);
        	var p = editor.restoreRealElement(o);
        	if (p.getName() == 'cke:object') {
        		var u = p.getElementsByTag('param', 'cke');
        		var s = {};
        		for (var v = 0, w = u.count(); v < w; v++) {
        		    var x = u.getItem(v),
        		        y = x.getAttribute('name'),
        		        z = x.getAttribute('value');
        		    s[y] = z;
        		}
        		if(s){
        			if(s['flashvars']){
        				var flashvars={};
        				var fvs={};
        				fvs = s['flashvars'].split('&');
        				if(fvs.length>0){
	        				for (var f = 0; f<fvs.length; f++) {
	        					if(fvs[f].indexOf('=')>0){
	        						flashvars[fvs[f].substr(0,fvs[f].indexOf('='))]=fvs[f].substr(fvs[f].indexOf('=')+1);
	        					}
	        				}
	        				if(flashvars['file']){
	        					this.getContentElement('uppod', 'file').getInputElement().setValue(flashvars['file']);
	        				}
	        				if(flashvars['pl']){
	        					this.getContentElement('uppod', 'file').getInputElement().setValue(flashvars['pl']);
	        				}
	        				if(flashvars['comment']){
	        					this.getContentElement('uppod', 'comment').getInputElement().setValue(flashvars['comment']);
	        				}
	        				if(flashvars['poster']){
	        					this.getContentElement('uppod', 'poster').getInputElement().setValue(flashvars['poster']);
	        				}
	        				if(flashvars['st']){
	        					this.getContentElement('uppod', 'style').getInputElement().setValue(flashvars['st'].substr(flashvars['st'].lastIndexOf('/')+1));
	        				}
	        				if(flashvars['m']){
	        					this.getContentElement('uppod', 'media').getInputElement().setValue(flashvars['m']);
	        				}
        				}
        			}
        		}
        	}
        }
    },
    onOk: function() {
      var file = this.getContentElement( 'uppod', 'file').getInputElement().getValue();
      file.indexOf('.txt')==file.length-4?file='pl='+file:file='file='+file;
      var media = this.getContentElement( 'uppod', 'media').getInputElement().getValue();
      var comment = this.getContentElement( 'uppod', 'comment').getInputElement().getValue();
      var poster = this.getContentElement( 'uppod', 'poster').getInputElement().getValue();
      var style = this.getContentElement( 'uppod', 'style').getInputElement().getValue();
      var player_id=Math.floor(Math.random()*101);
      if(file!=''&&UPPOD_FOLDER!=''){
	      this._.editor.insertHtml('<object id="'+media+'player'+player_id+'" type="application/x-shockwave-flash" data="'+UPPOD_FOLDER+'uppod.swf" width="'+UPPOD_SIZES[media]['width']+'" height="'+UPPOD_SIZES[media]['height']+'"><param name="bgcolor" value="'+UPPOD_BGCOLOR+'" />'+(media!='audio'?'<param name="allowFullScreen" value="true" />':'')+'<param name="allowScriptAccess" value="always" /><param name="movie" value="'+UPPOD_FOLDER+'uppod.swf" /><param name="flashvars" value="m='+media+'&amp;' + file + (comment!=''?'&amp;comment='+comment:'') + (poster!=''?'&amp;poster='+poster:'') + (style!=''?'&amp;st='+UPPOD_FOLDER+style:'') +'" /></object>');
      }
      if(file==''){
      	alert('Не указана ссылка на файл');
      }
      if(UPPOD_FOLDER=='http://...'){
      	alert('Не указан путь к папке с плеером (UPPOD_FOLDER)');
      }
    },
    contents : [{
      id : 'uppod',
      label : '',
      title : '',
      elements : [
      {
       	id : 'file',
        type : 'text',
        label : 'Ссылка на файл или плейлист*'
      },{
       	id : 'comment',
        type : 'text',
        label : 'Название'
      },{
       	id : 'poster',
        type : 'text',
        label : 'Заставка (jpg)'
      },{
        type: 'hbox',
        widths: ['', '', ''],
        children: [{
          	id: 'media',
          	type: 'select',
          	label: '',
      	  	items: [
      	    	['Видео', 'video'],
                ['Аудио', 'audio'],
                ['Фото', 'photo']]
           },{
           	id: 'style',
           	type: 'select',
           	label: '',
           	items: UPPOD_STYLES
        	}]
        },{
          type:'html',
          html:'* обязательное поле'
        }]
     }]
  };
});