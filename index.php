<?php
/********************************************************
/**
 * @name index.php
 */


/********************************************************
/**
 * デモサイトモードを使うか設定します
 * (2016/4機能追加)
 * 
 * いままで本番サーバーでのdemo,testサイトは別のサブドメインが必要でした。
 * この機能を使うと[application]フォルダと同列に[demo]フォルダが配置できます。
 * 
 * この機能はConfig.phpを読む前に設定が必要なためindex.phpに機能を追加しました。
 * 
 * ※htaccess使っていろいろやるアプリの場合はちゃんと動かない使えないかも・・・
 * 
 * @name use_demosite
 */
$use_demosite = true;
$demosite_url = "demo";




/**************************↓処理開始↓******************************/

//demoサイトへのアクセスかチェック。demoの場合は$demosite_urlに値あり
if ($use_demosite){
	$php_self = trim($_SERVER["PHP_SELF"],"/"); //index.php/demo
	
	if (strpos($php_self,"index.php/".$demosite_url) !== 0){
		$demosite_url = "";
	}	
}else{
	$demosite_url = "";
}


//定数作成
$path = realpath("").'/';

$base_path = $path;
$apl_path = $path.'application/';
$sys_path = $path.'system/';
$demo_dir = '';


if ($demosite_url){
	$demopath = $path.$demosite_url."/";
	
	$base_path = $demopath;
	$apl_path = $demopath.'application/';
	$demo_dir = $demosite_url;	
	
	if (file_exists($demopath."system/")) {
		$sys_path = $demopath.'system/';
	}
}

define('BASEPATH', $base_path);
define('APLPATH', $apl_path);	
define('SYSPATH', $sys_path);
define('DEMOURL', $demo_dir);

$corepath = SYSPATH.'core/';

require_once $corepath."GC.php";


?>