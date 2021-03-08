var fileUploadSuccessName = new Array;
var fileUploadSuccessDir = new Array;
var swfu;
var uploadPicture = 0;
function newSWFUploadFiles(){
	    fileUploadSuccessName = [];
	    fileUploadSuccessDir = [];
		if(typeof(swfu) != "undefined")swfu.destroy;
	    var settings = {
				flash_url : "/modules/upload/swfupload/swfupload.swf",
				flash9_url : "/modules/upload/swfupload/swfupload_fp9.swf",
				upload_url: "/modules/upload/swfupload/upload.php",
				//post_params: {"PHPSESSID" : "<?php echo session_id(); ?>"},
                file_size_limit : "20 MB",
                file_types : "*.*",
                file_types_description : "Filedata",
                file_upload_limit : file_upload_limitVar,
		        debug: false,

				// Button settings
				button_placeholder_id: "uploadButton",
                button_width : 115,
                button_height : 30,
                button_text_left_padding: 10,
                button_text_top_padding: 5,
                button_text : "Выберите файл",
                button_text_style : ".uploadBtn { color:#ffffff; font-size: 18px; font-family: Arial,sans-serif; background-color: #5BC0DE; }",
		        file_dialog_complete_handler : fileDialogComplete,
                upload_success_handler : uploadSuccess,
                file_queue_error_handler : filequeueerror,
                upload_progress_handler : uploadProgress
			};
			swfu = new SWFUpload(settings);
}
function newSWFUploadPicture(type){
	type = type ? 'avatar' : false;

	uploadPicture = 0;
	//if(typeof(swfu) != "undefined")swfu.destroy;
	var settings = {
		flash_url : "/modules/upload/swfupload/swfupload.swf",
		flash9_url : "/modules/upload/swfupload/swfupload_fp9.swf",
		upload_url: "/modules/upload/swfupload/uploadPicture.php",
		file_size_limit : "1024 KB",
		file_types : "*.jpg;*.gif;*.png;",
		file_types_description : "All Files",
		file_upload_limit : 1,
		debug: false,

		// Button settings
		button_placeholder_id: "uploadPictureButton",
		button_width : 115,
		button_height : 30,
		button_text_left_padding: 10,
		button_text_top_padding: 5,
		button_text : "Выберите файл",
		button_text_style : ".uploadBtn { color:#ffffff; font-size: 18px; font-family: Arial,sans-serif; background-color: #5BC0DE; }",	
		
		file_queue_error_handler : fileQueueErrorPicture,
		file_dialog_complete_handler : fileDialogCompletePicture,
		upload_start_handler : uploadStartPicture,
		upload_success_handler : uploadSuccessPicture,

		post_params : {
		    "type" : type
		}
	
	};
	swfu = new SWFUpload(settings);
}
function uploadFileDel(id){
	fileUploadSuccessDir.splice(id,1);
	fileUploadSuccessName.splice(id,1);
	$('#statuss').html($(''));
	for(var i=0;i<fileUploadSuccessName.length;i++){
		$('#statuss').append($(fileUploadSuccessName[i] + ' <a class=\"btn btn-sm btn-danger\" onclick="uploadFileDel(\'' + i + '\');"><i class=\"icon-remove-circle icon-white\"></i>удалить</a>'));
	}
	if(typeof(swfu) == "undefined"){//используется в productProced
		file_upload_limitVar = "1";
		newSWFUploadFiles();
	}
	else{
	   if(file_upload_limitVar > 0){//для возможности замены файла
		  file_upload_limitVar++;
		  swfu.setFileUploadLimit(file_upload_limitVar);
	   }
	}
}
function uploadSuccess(file, serverData) {
	$('#status').html($('<p class="upload_complite">Готово</p>'));
    //console.log(serverData);
	serverData = JSON.parse(serverData);
	
	var len = fileUploadSuccessName.length;
	fileUploadSuccessName[len] = serverData.name;//$('#statuss').append($(serverData));
	fileUploadSuccessDir[len] = serverData.dir;
	//if(urlVar('p', 'get') == 'productredobjsale'){
		var str = '<tr data-productobj="file">\
						<td class="font_size_14 font_weight_700 padding_10 vertical_align">' + serverData.name + '</td>\
						<td style="width: 70px;" class="padding_10 vertical_align"><button class="btn btn-sm btn-danger" onclick="panel.user.myshop.edit.obj.file_del(\'' + serverData.dir + '\', this);">удалить</button></td>\
					';
	
	/*}else{
		var str = '\
			<p>'
				+serverData.name+
				'<a class="btn btn-sm btn-danger" onclick="uploadFileDel('+len+');">\
					удалить\
				</a>\
			</p>';	
	}*/
	document.getElementById("statuss").innerHTML += str;
}
function filequeueerror(file, code, message) {
	$('#status').html($('<p class="upload_complite">Готово</p>'));	
    switch(code){
		case -100:
			$('#statuss').append( '<tr><td colspan="2" class="font_size_14 font_weight_700 padding_10 vertical_align"><p class="upload_error">Если товар универсальный, то можно загрузить только один файл</p></td>');
		   break
		case -110:
			$('#statuss').append( '<tr><td colspan="2" class="font_size_14 font_weight_700 padding_10 vertical_align"><p class="upload_error">' + file.name + ' - Превышен допустимый размер файла</p></td>');
           break
		default:
 			$('#statuss').append( '<tr><td colspan="2" class="font_size_14 font_weight_700 padding_10 vertical_align"><p class="upload_error">Ошибка загрузки</p></td>');
		break
	}		
}
function uploadProgress(file, bytesLoaded, bytesTotal) {
    if(Math.round(bytesLoaded/bytesTotal*100) == 100)$('#status').html($('<p class="upload_status">Загружено ' + Math.round(bytesLoaded/bytesTotal*100) + '% файла ' + file.name + '</p><p class="upload_status">Подождите...</p>'));
	else $('#status').html($('<p class="upload_status">Загружено ' + Math.round(bytesLoaded/bytesTotal*100) + '% файла ' + file.name + '</p>'));
}
 
function fileDialogComplete(numFilesSelected, numFilesQueued) {
   if(numFilesSelected == 0)$('#status').html($('<p class="upload_status">Выбрано 0 файл(ов)'));
    else $('#status').html($('<p class="upload_status">Выбрано ' + numFilesSelected + ' файл(ов), начинаем загрузку</p>'));
    this.startUpload();
}

/********************/
function fileQueueErrorPicture(file, errorCode, message) {
	switch (errorCode) {
		case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT://Превышен размер файла
		    document.getElementById('SWFuploadPictureMessage').innerHTML = '<p class="upload_error">Превышен размер файла 1мБ</p>';
			break;
		case SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED://Пытается загрузить второй файл
		    document.getElementById('SWFuploadPictureMessage').innerHTML = '<p class="upload_error">Вы можете загрузить только один файл</p>';
			break;
	}
}
function fileDialogCompletePicture(numFilesSelected, numFilesQueued) {
		this.startUpload();
}
function uploadStartPicture(file) {
   uploadPicture = 0;
   document.getElementById('SWFuploadPictureMessage').innerHTML = '<p class="upload_status">Загрузка</p>';
}
function uploadSuccessPicture(file, serverData) {//по заверщению загрузки
		console.log(serverData);
		serverData = serverData.split(':');
		if(serverData[0] != "ok"){alert('Ошибка');return;}
		uploadPicture = serverData[1]; //Передача имени картинки в глобальную переменную
		var uploadDiv = serverData[5] == 'avatar' ? document.getElementById('SWFuploadPictureDiv3') : document.getElementById('SWFuploadPictureDiv');//Создаем кнопку удаления загруженной картинки
		swfu.destroy();

		if(serverData[5] == 'avatar'){
			
			uploadDiv.innerHTML = '<img src="'+serverData[2]+'" alt="Продавец" style="cursor:pointer" onclick="panel.user.cabinet.edit_avatar(this.src);" />';

			$.post('/modules/main/avatarSave.php', {'picture': serverData[1]}, function(){
				popupClose();
			})
		}else{
			uploadDiv.innerHTML = '<img src="'+serverData[2]+'" />';
			var deleteImg = document.createElement('div');
			deleteImg.innerHTML = '<div class="btn btn-sm btn-danger">Удалить</div>';
			deleteImg.onclick = function(){
				uploadDiv.removeChild(deleteImg);
				uploadDiv.innerHTML = '<div id="uploadPictureButton"></div><div id="SWFuploadPictureMessage"></div>';
				newSWFUploadPicture();
			}
			uploadDiv.appendChild(deleteImg);
		}

		
		

		
		

}