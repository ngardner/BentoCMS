/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http=//ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example=
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.entities = false;
	config.filebrowserBrowseUrl = '/backend/views/js/ckeditor/filemanager/browser/default/browser.html?Connector='+httpUrl+'backend/views/js/ckeditor/filemanager/connectors/php/connector.php';
  config.filebrowserImageBrowseUrl = '/backend/views/js/ckeditor/filemanager/browser/default/browser.html?Type=Image&Connector='+httpUrl+'backend/views/js/ckeditor/filemanager/connectors/php/connector.php';
  config.filebrowserFlashBrowseUrl = '/backend/views/js/ckeditor/filemanager/browser/default/browser.html??Type=Flash&Connector='+httpUrl+'backend/views/js/ckeditor/filemanager/connectors/php/connector.php';
  //config.filebrowserUploadUrl = '/backend/views/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
  //config.filebrowserImageUploadUrl = '/backend/views/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
  //config.filebrowserFlashUploadUrl = '/backend/views/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';
};
