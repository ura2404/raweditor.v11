<?php
header("Content-type: application/json");

require_once ('../../common.php');
require_once '../../utils.php';

$id = isset($_POST['id']) ? $_POST['id'] : null;
$Root = isset($_POST['root']) ? $_POST['root'] : '';

// --- --- --- --- --- --- ---
$fun_root = function() use($Root){
    $Text = $Root!=='' ? strRAfter($Root,'/') :  null;
    return [
		[
			'id' => $Root ? $Root : '/',
			'text' => $Text ? $Text : 'Файловая система',
			
			//'text' => $Root ? strRAfter($Root,'/') : '/',
			'state' => 'closed',
			'type' => 'root',
			'top' => true,
			'iconCls' => 'fa fa-fw fa-folder-o',
		]
	];
};

// --- --- --- --- --- --- ---
$fun_node = function() use($id){
    
	$id = $id==='/' ? null : $id;
	
	$root_path = realpath(RAW_ROOT. $id);
	//dump($root_path);

	$files = scandir($root_path);

	$dirs = [];
	$fils = [];
	foreach($files as $file){
        if($file === '.' || $file === '..') continue;
        $path = $root_path .'/'. $file;
        
        if(is_dir($path)) $dirs[] = [
			'id' => $id . '/'. $file,
			'text' => $file,
			'state' => 'closed',
			'type' => 'folder',
			'iconCls' => 'fa fa-fw fa-folder-o',
		];
		else $fils[] = [
			'id' => $id . '/' . $file,
			'text' => $file,
			'type' => 'file',
			'iconCls' => 'fa fa-fw fa-file-o',
		];	    
	}
	
	foreach($fils as $f) $dirs[] = $f;
	return $dirs;
};

// --- --- --- --- --- --- ---
if($id === null) $tree = $fun_root();
else $tree = $fun_node();

echo json_encode($tree);
?>