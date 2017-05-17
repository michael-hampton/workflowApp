<?php
// Free Ajax-PHP File Manager - from: http://coursesweb.net/

// Data needed to logg in as admin
$admin_name = 'admin';
$admin_pass = 'pass';

// Configuration - See details in the "readme.html" file
$fm_conf = [
 'FILES_ROOT'=> '/FormBuilder/public/uploads',     // Here add the Root directory for File-Manager
 'LOGG_IN'=> 1,      // 0 require to logg in file manager. 1 not require to logg in
 'SESSION_PATH_KEY'=> '',
 'RENAME_ROOT'=> 0,     // 1 allows to rename Root folder, 0 not
 'MOVE_ROOT'=> 0,       // 1 allows to move Root folder, 0 not
 'DEL_ROOT'=> 0,        // 1 allows to delete Root folder, 0 not
 'THUMBS_VIEW_WIDTH'=> 140,
 'THUMBS_VIEW_HEIGHT'=> 120,
 'PREVIEW_THUMB_WIDTH'=> 300,
 'PREVIEW_THUMB_HEIGHT'=> 200,
 'MAX_IMAGE_WIDTH'=> 0,
 'MAX_IMAGE_HEIGHT'=> 0,
 'DEFAULTVIEW'=>'list',
 'FORBIDDEN_UPLOADS'=> 'js jsp jsb mhtml mht xhtml xht php phtml php3 php4 php5 phps shtml jhtml pl sh py cgi exe scr dll msi vbs bat com pif cmd vxd cpl htpasswd htaccess',
 'EDITFILE'=> 'css html htm json txt xml',
 'ALLOWED_UPLOADS'=> '',
 'FILEPERMISSIONS'=> '0644',
 'DIRPERMISSIONS'=> '0755',
 'LANG'=> 'en',
 'DATEFORMAT'=> 'dd/MM/yyyy HH:mm'
];

date_default_timezone_set('UTC');