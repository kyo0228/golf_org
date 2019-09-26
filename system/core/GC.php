<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// エラーを画面に表示(1を0にすると画面上にはエラーは出ない)
ini_set('display_errors',0);

/*******************************************************/
/**
 * @name core/GC.php
 * 
*/

define('CORE_VERSION', '1.0.0');

require_core_class("Abstract_Base",true);
require_core_class("Abstract_Sgtn",true);
require_core_class("Static",true);


//----Logクラス。フレームワーク共通のログ処理を実装
$LOG = &load_core_class("Log");
//----例外クラス
$ERR = &load_core_class("Exception");
$ERR->get_log_instance($LOG);


//----スクリプト終了、phpの内部エラー発生時にコールされる関数をハンドリング
register_shutdown_function(array($ERR,'shutdown_handler'));
//----スクリプトは終了しないがphp内部から出力されるメッセージをハンドリング
set_error_handler(array($ERR,"warning_handler"));
//----catchできていない例外をハンドリング
set_exception_handler(array($ERR,'exception_handler'));

//----Staticクラス	。設定ファイルを読み込み。
GC_Static::initialize();

//----Sessionクラス。$_SESSIONを直接使わないようなセッション関数を用意
//セッション複数タブ対応のためinitialize_requestの前にもってきた。
require_core_class("Session_Namespace");
$SESS = load_core_class("Session");

GC_Static::initialize_request($SESS);


//----Configクラス	。GC_Staticのコンフィグ関連ラッパークラス
load_core_class("Config");

//----Routeクラス。GC_Staticのリクエスト関連ラッパー関数とその他ルーティング関連機能
$RTE = &load_core_class("Route");

//config,routeを読んだあと、自動メンテ中の確認を実施
$RTE->check_auto_maintenance();

//device別のauto_redirectをチェック
$RTE->check_device_redirect();


//DB関連クラスのロード。Controllerと同名のDaoがあれば生成。なければMY_DB,GC_DBの順にクラスを生成する
load_model_class(GC_Static::request("controller"),GC_Static::sys("use_database")); 

load_core_class("Page");

//----コントローラ読み込み
$ctrl_name = require_controller_class(GC_Static::request("controller_class"));
if (!$ctrl_name){
	//コントローラがなければエラーページ
	$RTE->output_error_404(GC_Static::request("url_sys"));
}	else{
	$CTR = &$ctrl_name::get_instance();
}

//コントローラに対応するAction関数がなければページなし
if ( !in_array(strtolower(GC_Static::request("page_function")), array_map('strtolower', get_class_methods($ctrl_name)), TRUE)){
	//コントローラの中に対応する関数がなければ404エラー
	$RTE->output_error_404(GC_Static::request("url_sys"));
}	else{
	//コントローラの呼び出し用関数をパラメータ付きでコールする
	call_user_func_array(array(&$CTR, GC_Static::request("page_function")), GC_Static::request("page_args"));
}
exit();





/*******************************************************/
/**
 * コントローラ用クラスファイルを読み込みます。
 * @name require_controller_class
 * @param string $class application/controller/のコントローラ名（.phpはいらない）
 * @return loadするControllerのクラス名。Controllerがなければfalse
 * 
 *				 カスタマイズ設定有効時:2017/04追加
 *					[customize]フォルダのXxx_ControllerやXxx_○○Controllerなどが戻る
 *					applicationの○○ControllerがMY_Controllerを継承していた場合、○○Controllerの継承先を
 *					「Xxx_MY_Controller」に書き換えて[customize]-[_modify]に配置しrequire
 * 
*/	
function require_controller_class($class) {
	
	//まずsystem/coreのGC_Controllerは必須
	require_once GC_Static::localpath("sys_core")."GC_Controller.php";

	//apl/coreにMY_Controllerがあったらrequire
	$is_apl_my = false;
	$my_path = GC_Static::localpath("apl_core")."MY_Controller.php";
	if (file_exists($my_path)){
		$is_apl_my = true;
		require_once $my_path;
	}
	
	//コントローラのクラス名
	$ctrlname = ucfirst($class); 
	
	//カスタマイズ機能を使う場合は接頭語を取得
	$cstm_prefix = GC_Static::get_custom_prefix();

	$is_cstm_my = false;
	//もしカスタムフォルダのcoreにMY_Controllerがあった場合はrequire
	if ($cstm_prefix){
		$my_path = GC_Static::localpath("cstm_core").$cstm_prefix."MY_Controller.php";
		if (file_exists($my_path)){
			$is_cstm_my = true;
			require_once $my_path;
		}		
	}
	
	//aplのControllerの存在確認。まだrequireしない
	$is_apl_ctrl = false;
	$path_apl_ctrl=GC_Static::localpath("apl_controller").$ctrlname.".php";
	if (file_exists($path_apl_ctrl)){
		$is_apl_ctrl = true;		
	}
	
	//customのControllerの存在確認。まだrequireしない
	$is_cstm_ctrl = false;
	$path_cstm_ctrl = GC_Static::localpath("cstm_controller").$cstm_prefix.$ctrlname.".php";
	if (file_exists($path_cstm_ctrl)){
		$is_cstm_ctrl = true;		
	}	
	
	//aplのコントローラとcustomのMyがあった場合、aplのコントローラ継承先をCustomのMyへファイルを書き換える
	$path_mod_ctrl="";
	if ($is_apl_ctrl && $is_cstm_my){
		//aplコントローラの更新日付を取得
		$t = filemtime($path_apl_ctrl);
		$path_mod_ctrl = GC_Static::localpath("cstm_modify").$ctrlname."_".$t.".php";
		//customの_modifyに書き換えたファイルがあるかチェック。
		if (file_exists($path_mod_ctrl)){
			//最新更新日付のファイルがあった場合は見つかったファイルをrequire
			require_once $path_mod_ctrl;
		}else{
			//最新更新日付のファイルがない場合は継承元をcustomのMY_Controllerに書き換えてファイル新規作成
			$file_data = file_get_contents($path_apl_ctrl);
			if(strpos($file_data,$ctrlname.' extends MY_Controller') !== false){ 
				$file_data = str_replace('extends MY_Controller','extends '.$cstm_prefix.'MY_Controller',$file_data);
				file_put_contents($path_mod_ctrl,$file_data);
				
				//同名の別ファイルがあった場合は削除
				foreach(glob(GC_Static::localpath("cstm_modify").'{'.$ctrlname.'*.php}',GLOB_BRACE) as $file){
					if(is_file($file) && $path_mod_ctrl !== $file){
						//コントローラ名で切り取った残りの文字がすべて数字なら同名ファイル。削除実行
						$filename = str_replace($ctrlname.'_','',basename($file, ".php"));
						
						if (is_numeric($filename)){
							unlink($file);
						}
					}
				}
				
				//requireするのはカスタムにコピーしたファイル
				require_once $path_mod_ctrl;
				
			}else{
				//「extends MY_Controller」の文字がみつからなかったら別のファイルを継承しているからアプリのファイルをrequire
				require_once $path_apl_ctrl;
			}	
		}
				
	}elseif ($is_apl_ctrl && !$is_cstm_my){
		//customにXxx_MY_Controllerがない場合はアプリのファイルをrequire
		require_once $path_apl_ctrl;
	}
	
	//↑↑ここまででMY,CustomのMY,アプリのコントローラのrequireが完了↑↑
	
	//最後にcustomのコントローラがある場合はrequire
	if ($is_cstm_ctrl){
		$ctrlname = $cstm_prefix.$ctrlname;
		require_once $path_cstm_ctrl;
	}	

	
	//アプリ、カスタムともにコントローラがなければエラーにさせる
	if (!$is_apl_ctrl && !$is_cstm_ctrl){
		return false;
	}else{
		return $ctrlname;
	}
}


/*******************************************************/
/**
 * コアクラスファイルを読み込みます。クラスの固定接頭語(GC_やMY_)は書かなくてOK
 * @name require_core_class
 * @param string $class クラス名
 * @param bool   $no_extend アプリ側で継承させないクラスはtrue,通常のクラスはfalse(既定値)
 * @return coreクラス(sys)か拡張クラス(apl)どちらかのクラス名
 * 
*/	
function require_core_class($class ,$no_extend=false) {
	$isexists = false;
	//まずsystem/coreクラスは必ずある
	$gc_name = "GC_".$class;
	require_once SYSPATH."core/".$gc_name.".php";

	if (!$no_extend){
		//apl/coreクラスはあったらrequire
		$my_name = "MY_".$class;
		$my_path = APLPATH."core/".$my_name.".php";
		if (file_exists($my_path)){
			$isexists = true;
			require_once $my_path;
		}

		//カスタマイズフォルダに同名クラスがあったらrequire
		$cstm_prefix = GC_Static::get_custom_prefix();
		if ($cstm_prefix){
			$my_path = GC_Static::localpath("cstm_core").$cstm_prefix.$my_name.".php";
			if (file_exists($my_path)){
				$isexists = true;
				require_once $my_path;
				$my_name = $cstm_prefix.$my_name;
			}		
		}
	}

	if ($isexists){
		return $my_name;
	}else{
		return $gc_name;
	}		
}


/*******************************************************/
/**
 * コアクラスを生成します。インスタンスの初回生成時にクラスの固定接頭語(GC_やMY_)は書かなくてOK
 * @name load_core_class
 * @param string $class クラス名
 * @param string $key クラス名とは別のkey名をControllerで使用したい場合にセット
 * @return 生成したクラス
 * 
*/	
function &load_core_class($class,$key="") {
	//生成されたクラス名を保持
	static $_classes = array();

	//一度でも生成されていたら生成済みのクラスを戻す
	if (isset($_classes[$class])){
		return $_classes[$class];
	}else if (isset($_classes[$key])){
		return $_classes[$key];
	}

	//いまのところkeyはmodelだけなのでこっちは事前にrequire済み
	if (!$key){
		//requireしてクラス名を取得
		$name = require_core_class($class);
		
	}else{
		$name = $class;
	}

	//contorollerで使う変数名とクラス名のペアを保持しておく
	loaded_class($name,$key);

	//get_instanceの関数があればsingleton実装している。newしない
	if (method_exists($name,'get_instance')) {
		$_classes[$name] = $name::get_instance();
	}else{
		//インスタンス生成
		$_classes[$name] = new $name();
	}
	return $_classes[$name];

}	

/*******************************************************/
/**
 * Model用クラスファイルを読み込みます。
 * @name load_model_class
 * @param string $class コントローラ名
 * @param bool $use_database true:dbを使う場合はこの関数ないでDB接続チェックを実施
 * @return コントローラと同名のDaoがある場合はapl/model/xxDao
 *         Daoがなくapl/core/My_DBがあればMy_DB
 *         My_DBがなければGC_DB
 * 
 *				 カスタマイズ設定有効時:2017/04追加
 *					[customize]フォルダのXxx_MY_DBやXxx_○○daoなどが戻る
 *					applicationの○○daoがMY_DBを継承していた場合、○○daoの継承先を
 *					「Xxx_MY_DB」に書き換えて[customize]-[_modify]に配置しrequire
 * 
*/
function &load_model_class($class,$use_database) {
	
	require_core_class("ColumnInfo");//dataclass用
	require_core_class("DB_Result");
	require_core_class("DB_Connection"); //Singleton
		
	//まずsystem/coreのGC_DB。
	$dao_name = "GC_DB";
	require_once GC_Static::localpath("sys_core")."GC_DB.php";
	//コンフィグにDB使用するとあった場合は接続チェック
	if ($use_database){
		$con = &GC_DB_Connection::get_instance();
		$con->check_connection();
	}
	
	//apl/coreにMY_DBがあったらrequire
	$is_apl_my = false;
	$my_path = GC_Static::localpath("apl_core")."MY_DB.php";
	if (file_exists($my_path)){
		$is_apl_my = true;
		require_once $my_path;
		$dao_name = "MY_DB";
	}
		
	//カスタマイズ機能を使う場合は接頭語を取得
	$cstm_prefix = GC_Static::get_custom_prefix();
	
	$is_cstm_my = false;
	//もしカスタムフォルダのcoreにMY_DBがあった場合はrequire
	if ($cstm_prefix){
		$my_path = GC_Static::localpath("cstm_core").$cstm_prefix."MY_DB.php";
		if (file_exists($my_path)){
			$is_cstm_my = true;
			require_once $my_path;
			$dao_name = $cstm_prefix."MY_DB";
		}		
	}
	
	//aplのDaoの存在確認。まだrequireしない
	$class = $class."Dao";
	$is_apl_model = false;
	$path_apl_model=GC_Static::localpath("apl_model").$class.".php";
	if (file_exists($path_apl_model)){
		$is_apl_model = true;
		$dao_name = $class;
	}
	
	//customのDaoの存在確認。まだrequireしない
	$is_cstm_model = false;
	$path_cstm_model = GC_Static::localpath("cstm_model").$cstm_prefix.$class.".php";
	if (file_exists($path_cstm_model)){
		$is_cstm_model = true;		
	}	
	
	//aplのDaoとcustomのMyがあった場合、aplのDao継承先をCustomのMyへファイルを書き換える
	$path_mod_model="";
	if ($is_apl_model && $is_cstm_my){
		//aplDaoの更新日付を取得
		$t = filemtime($path_apl_model);
		$path_mod_model = GC_Static::localpath("cstm_modify").$class."_".$t.".php";
		//customの_modifyに書き換えたファイルがあるかチェック。
		if (file_exists($path_mod_model)){
			//最新更新日付のファイルがあった場合は見つかったファイルをrequire
			require_once $path_mod_model;
		}else{
			//最新更新日付のファイルがない場合は継承元をcustomのMY_DBに書き換えてファイル新規作成
			$file_data = file_get_contents($path_apl_model);
			if(strpos($file_data,$class.' extends MY_DB') !== false){ 
				$file_data = str_replace('extends MY_DB','extends '.$cstm_prefix.'MY_DB',$file_data);
				file_put_contents($path_mod_model,$file_data);
				
				//同名の別ファイルがあった場合は削除
				foreach(glob(GC_Static::localpath("cstm_modify").'{'.$class.'*.php}',GLOB_BRACE) as $file){
					if(is_file($file) && $path_mod_model !== $file){
						//Dao名で切り取った残りの文字がすべて数字なら同名ファイル。削除実行
						$filename = str_replace($class.'_','',basename($file, ".php"));
						
						if (is_numeric($filename)){
							unlink($file);
						}
					}
				}
				
				//requireするのはカスタムにコピーしたファイル
				require_once $path_mod_model;
				
			}else{
				//「extends MY_DB」の文字がみつからなかったら別のファイルを継承しているからアプリのファイルをrequire
				require_once $path_apl_model;
			}	
		}
				
	}elseif ($is_apl_model && !$is_cstm_my){
		//customにXxx_MY_DBがない場合はアプリのファイルをrequire
		require_once $path_apl_model;
	}
	
	//↑↑ここまででMY,CustomのMY,アプリのコントローラのrequireが完了↑↑
	
	//最後にcustomのDaoがある場合はrequire
	if ($is_cstm_model){
		$class = $cstm_prefix.$class;
		require_once $path_cstm_model;
		$dao_name = $class;
	}	
	
	return load_core_class($dao_name,"db");

}



/*******************************************************/
/**
 * 読み込み済みのクラスをcontrollerで使用する変数名とクラス名のペアの形で保存。
 * @name loaded_class
 * @param string $class 呼び出すクラス名(GC_やMY_がついた名前)。modelなどの独自クラス名の場合はkeynameに「db」などとセット
 * @param string $key クラス名とは別のkey名をControllerで使用したい場合にセット
 * @return 保存済みのクラス名の連想配列
 * 
*/		
function &loaded_class($class = '' , $key = '')
{
	static $_loaded_class = array();

	if ($class !== '')
	{
		if ($key){
			$_loaded_class[$key] = $class;
		}else{
			$name = strtolower($class);
			if (strpos($name,'gc_') === 0 || strpos($name,'my_') === 0){
				$name = substr( $name , 3 , strlen($name)- 3 );
			}else{
				///xxx_my_classに対応
				if (strpos($name,'_my_') > 0){
					$ary = explode("_", $name,3);
					if ($ary[2] && strlen($ary[2]) > 1){//一文字だった場合は正式なクラス名ではない。
						$name = $ary[2];
					}
				}
				
			}
			$_loaded_class[$name] = $class;
		}
	}

	return $_loaded_class;
}	




/*
 * 更新履歴
 * 2017-04-05 
 * ・フォルダやクラス名のスペルミスを対応
 * ・カスタマイズ機能追加に伴い各コアファイル読み込み関連の改修
 */