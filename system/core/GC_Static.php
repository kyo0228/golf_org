<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*******************************************************/
/**
 * Static情報保持クラス
 * 静的に保持したい機能を管理するクラス。
 * でも当クラスの継承は禁止。
 * controllerで直接使わせないようにラッパークラスを用意すること
 * @name GC_Static (core/GC_Static.php)
*/
final class GC_Static {	
	/*******************************************************/
	/**
	 * 初期化処理
	*/
	public static function initialize() {
		static $is_init = null;
		//一度初期化していたら2度と処理させない
		if (isset($is_init)){
			return;
		}
		
		self::_initialize_path();
		self::_initialize_config();
		
		$is_init = true;
	}
	
	/*******************************************************/
	/**
	 * システムの固定パスを初期化します
	 * @name _initialize_config
	 * @return なし
	*/
	private static function _initialize_path(){	
		
		//システムで定義されているpath
		self::localpath(array(
				"apl" => APLPATH,
				"apl_config" => APLPATH.'config/',
				"apl_controller" => APLPATH.'controller/',
				"apl_core" => APLPATH.'core/',
				"apl_model" => APLPATH.'model/',
				"apl_dataclass" => APLPATH.'model/dataclass/',
				"apl_view" => APLPATH.'view/',
				"apl_resource" => APLPATH.'view/_resource/',
				"apl_layout" => APLPATH.'view/_layout/',
				"apl_extension" => APLPATH.'extension/',
				"css" => BASEPATH."css/",
				"images" => BASEPATH."images/",
				"js" => BASEPATH."js/",
				"libraries" => BASEPATH."libraries/",
				"pages" => BASEPATH."pages/",
				"tmp" => BASEPATH."tmp/",
				"log" => BASEPATH."log/",
				"sys" => SYSPATH,
				"sys_core" => SYSPATH.'core/',
				"sys_extension" => SYSPATH.'extension/',
				"sys_resource" => SYSPATH.'_resource/'				
		),true);
	}	
	
	/*******************************************************/
	/**
	 * コンフィグ情報を初期化します
	 * @name _initialize_config
	 * @return なし
	*/
	private static function _initialize_config(){	
		$confpath = self::localpath("apl_config").'Config.php';
		
		if (!file_exists($confpath)) {
			throw new Exception('設定ファイルが見つかりません ['.$confpath.']' );
		}		
		require_once $confpath;
		
		self::sys($sys);
		self::db($db);
		
		$route["protocol"] = "http";
		if ($_SERVER["SERVER_PORT"] !== "80"){$route["protocol"] = "https";}		
		
		//URLの最後に/があった場合はここで除かれている
		$route["base_url"] = self::check_short_url($route["base_url"],$route["protocol"]);

		
		self::route($route);
		
		if (isset($user)){
			self::user($user);
		}
		
		//レイアウト定義の設定ファイルを読み込み
		$confpath = self::localpath("apl_config").'Layout.php';
		
		if (file_exists($confpath)){
			require_once $confpath;		
			self::layout($layout);
		}	
		
		//カスタマイズ機能を利用する場合はカスタマイズフォルダのパスをセット。2017/03/16追加
		
		$error_404 = "";
		$error_exception = "";
		$error_phpmsg = "";
		$info_maintenance = "";		
		
		
		if (self::sys("use_customize") && self::sys("customize_prefix")){

			//カスタマイズ用path
			self::localpath(array(
					"cstm_controller" => APLPATH.'customize/'.self::sys("customize_prefix").'/controller/',
					"cstm_core" => APLPATH.'customize/'.self::sys("customize_prefix").'/core/',
					"cstm_model" => APLPATH.'customize/'.self::sys("customize_prefix").'/model/',
					"cstm_dataclass" => APLPATH.'customize/'.self::sys("customize_prefix").'/model/dataclass/',
					"cstm_view" => APLPATH.'customize/'.self::sys("customize_prefix").'/view/',
					"cstm_resource" => APLPATH.'customize/'.self::sys("customize_prefix").'/view/_resource/',
					"cstm_layout" => APLPATH.'customize/'.self::sys("customize_prefix").'/view/_layout/',
					"cstm_extension" => APLPATH.'customize/'.self::sys("customize_prefix").'/extension/',
					"cstm_modify" => APLPATH.'customize/'.self::sys("customize_prefix").'/_modify/',
			),true);

			$cstm_prefix = self::get_custom_prefix(); //先頭大文字と_の文字列
			//リソースファイルがあるかは先にここで確認しておく
			$error_404 = self::localpath("cstm_resource").$cstm_prefix."error_404.php";
			if (!file_exists($error_404)){
				$error_404 = "";
			}
			$error_exception = self::localpath("cstm_resource").$cstm_prefix."error_exception.php";
			if (!file_exists($error_exception)){
				$error_exception = "";
			}
			$error_phpmsg = self::localpath("cstm_resource").$cstm_prefix."error_phpmsg.php";
			if (!file_exists($error_phpmsg)){
				$error_phpmsg = "";
			}			
			
			$info_maintenance = self::localpath("cstm_resource").$cstm_prefix."info_maintenance.php";
			if (!file_exists($info_maintenance)){
				$info_maintenance = "";
			}			
		}
		
		if (!$error_404){
			$error_404 = self::localpath("apl_resource")."error_404.php";
			if (!file_exists($error_404)){
				$error_404 = self::localpath("sys_resource")."error_404.php";
			}			
		}
		
		if (!$error_exception){
			$error_exception = self::localpath("apl_resource")."error_exception.php";
			if (!file_exists($error_exception)){
				$error_exception = self::localpath("sys_resource")."error_exception.php";
			}					
		}

		if (!$error_phpmsg){
			$error_phpmsg = self::localpath("apl_resource")."error_phpmsg.php";
			if (!file_exists($error_phpmsg)){
				$error_phpmsg = self::localpath("sys_resource")."error_phpmsg.php";
			}							
		}

		if (!$info_maintenance){
			$info_maintenance = self::localpath("apl_resource")."info_maintenance.php";
			if (!file_exists($info_maintenance)){
				$info_maintenance = self::localpath("sys_resource")."info_maintenance.php";
			}									
		}
				
		//システム用の共通リソース
		self::localresource(array(
				"error_404" => $error_404,
				"error_exception" => $error_exception,
				"error_phpmsg" => $error_phpmsg,
				"info_maintenance" => $info_maintenance
		));	
	}
	
	
	/*******************************************************/
	/**
	 * リクエストを解析して表示するコントローラやページを判定します。
	 * 元々initialize()で呼び出していたがsession初期化の後に実行する必要があったのでpublicに変更した
	 * @name _initialize_request
	 * @param class $session GC_SESSION
	 * @return なし
	*/
	public static function initialize_request($session){	
		
		$protocol = self::route("protocol");
		$base_url = self::route("base_url");
		
		//realはhttp://domein.jp/index.php/controller/のような生のリクエストURL
		$realurl = "http://".$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"];

		//ロードバランサーなどからんでいると裏の通信がSなしになる亊がある。チェック処理だけsなしで判定する
		$base_url_check = str_replace("https://","http://",$base_url);

		if (strpos($realurl, $base_url_check) !== 0 ){
			throw new Exception("リクエストURLが正しくありません");
		}

		//index.phpまでの記述を除く
		if (strpos($realurl, "index.php") === false ){
			$url = trim(str_replace($base_url_check,"",$realurl),"/");
		}else{
			$url = trim(str_replace($base_url_check."/index.php","",$realurl),"/");
		}
		
		//realに正しいプロトコルをセット
		$realurl = str_replace("http://","",$realurl);
		$realurl = $protocol."://".$realurl;
				
		//demoURLがセットされていたらURLの第一パラメータを除く
		if (DEMOURL){
			$url = trim(ltrim($url,DEMOURL),"/");
		}
		
		$c=""; //コントローラ名（Loginとか）
		$p="index"; //ページ名(indexとか)
		$u=""; //urlでコントローラと判断した文字（loginとかマニュアルならばadmin/logとか）
		$args = array();//ページの引数部
		
		$ary_parts = array();
						
		if (!$url){
			//空の場合は初期値をセット
			$c = self::route("base_controller");
			$u = strtolower($c);
		}else {
			//controller/page/argsなどパーツにばらす
			if (stristr($url, "/") === false){
				$ary_parts = array($url);
			}else{
				$ary_parts = explode("/", $url);
			}
			
			//先頭要素を取り除く処理を追加
			$remove = self::route("remove_route");
			if (count($remove)){
				foreach ($remove as $val) {
					if ($ary_parts[0] === $val){
						array_shift($ary_parts);//配列の先頭を取り除く
						break;
					}
				}
			}
			
			//取り除かれた結果配列が空になった場合は初期値をセットする
			if (!count($ary_parts)){
				$c = self::route("base_controller");
				$u = strtolower($c);				
			}else{
				
				//autoは自動でパラメータセット
				if (self::route("use_automode")){

					//controller取得
					$u =$ary_parts[0];
					$c =ucfirst($u);//先頭大文字に変換

					//page取得なければindex
					if (count($ary_parts) > 1){
						$p = $ary_parts[1];

						//pageのargs取得
						for ($i = 2; $i < count($ary_parts); $i++) {
							array_push($args, $ary_parts[$i]);
						}
					}
				}else{ //手動ルート
					
					//まず最新の$ary_partsで判定用URLを生成
					$url = implode("/",$ary_parts);
					$manual_route_ary = self::route("manual_route");

					$ary_sort = array();

					//urlのパスを文字の長い順に並び替え。
					foreach ($manual_route_ary as $key => $value) {
						$ary_sort[] = $key;
					}
					usort($ary_sort, create_function('$a,$b', 'return mb_strlen($b, "UTF-8") - mb_strlen($a, "UTF-8");'));

					foreach ($ary_sort as $key) {
						if (strpos($url, $key) === 0 ){
							$c = $manual_route_ary[$key];
							$u = $key;
							break;
						}
					}

					//manualなのにこの時点でコントローラが決まらない場合はエラー
					if (!$c){
						throw new Exception("URLからコントローラが判定できません。ルート設定を正しく設定してください");
					}

					//urlから先頭のコントローラ部を除きpageを取得する
					$ary = explode("/", trim(str_replace($u,"",$url),"/"));
					if (count($ary) !== 0 && $ary[0]){
						$p = $ary[0];

						//さらにURLの後ろがある場合はページのパラメータ
						if (count($ary) > 1){
							for ($i = 1; $i < count($ary); $i++) {
								array_push($args, $ary[$i]);
							}
						}
					}
				}
			}
		}
		
		if (!$c || !$p){
			throw new Exception("コントローラ、またはページが指定されていません。URLの構成が正しいか確認が必要です。");
		}
		
		$urlsession = "";
		//セッションのnamespaceがpageと同じ場合はsessionIDがURLに組み込まれている。sessionIDを除きパラメータを作成
		if (count($args) > 0 && $session->check_key_nsclass($p)){
			$urlsession = $p;
			$session->update_ns_lifetime($p);
			
			$p = $args[0];//引数1こ目がpageに昇格
			array_shift($args);//配列の先頭を取り除く
		}

		//ページの拡張子設定
		$suffix = self::route("url_suffix");
		
		//この時点で$pに拡張子ついていたら一旦はずす
		if (strpos($p,".") !== false){
			$tmp = explode(".", $p);
			$p = $tmp[0];
			
			$exists = false;
			for ($i = 1; $i < count($tmp); $i++) {
				if ($tmp[$i] === $suffix){
					$exists = true;
					break;
				}
			}
			
			if (!$exists){
				throw new Exception("ページの拡張子が正しくありません。アプリケーションのURL構成を確認してください。");
			}
		}
		
		if ($suffix){
			$suffix = ".".$suffix;
		}

		//index.phpを除いて改めて見やすいurlをセット。(この変数にはhttp://domein.jp/controller/みたいに見やすくなっている)
		$sysurl = $base_url."/".$url;
		
		if (DEMOURL){
			$base_url .= "/".DEMOURL;
			$sysurl = $base_url."/".$url;
		}
		
		
		$url_page = $base_url."/".$u."/".$p.$suffix;
		//index専用コントローラの場合はpage名とパラメータ書き換え
		if (self::check_index_only_controller($c)){
			$url_page = $base_url."/".$u;
			if ($p !== "index"){
				array_unshift($args,$p);
				$p = "index";
			}
		}
				
		//公開用関数に生成した変数をセット
		self::request(array(
				"url_base" => $base_url,												//config->routeのbase_urlと同じもの。デモサイトの場合は/demoがつく
				"url_controller" => $base_url."/".$u,						//コントローラまでの正しいパス
				"url_page" => $url_page,												//ページまでの正しいパス(ただしindex専用コントローラの場合はindex除く)
				"url_real" => $realurl,													//index.phpを含んだフルパス
				"url_sys" => $sysurl,														//index.phpを除いたフルパス
				"url_session" => $urlsession,										//URL内の文字からsessionIDと判断した文字
				"controller" => $c,															//先頭大文字のコントローラ名
				"controller_real" => $u,												//特にmanual_routeの時に重要。$cと$uは違う可能性あり。autoなら大文字小文字の違いのみ
				"page" => $p,																		//ページ名。大文字小文字変換はしていない
				"page_args" => $args,														//ページ引数部配列で保持
				"page_suffix" => $suffix,												//ページ拡張子。(.html .phpなどドットがついている)
				"controller_class" => $c."Controller",					//XxxxxController
				"page_function" => $p."_action"									//xxxx_action
		));
		
	}
	
	/*******************************************************/
	/**
	 * サーバーの基本ディレクトリパスを取得します
	 * 外部で使用する場合は各クラスで関数を作り必要なパスを公開してください
	 * 
	 * @name localpath
	 * @param string $key 配列の要素名。未指定なら全件
	 * @param bool $addkey 配列に要素を追加する場合にtrue。値の取得ならばfalse(既定値) 2017/3/16追加
	 * 
	 * @return any
	*/
	public static function localpath($key,$addkey=false){
		//まだ変数に値がない場合のみ初期化処理を行う。
		static $_localpath = null;
		
		if ($addkey){
			if (!isset($_localpath) && is_array($key)){
				$_localpath = $key;
			}else{
				$_localpath = array_merge($_localpath,$key);
			}				
		}else{
			return self::_get_item($_localpath, $key);
		}
		
		/*
		if (!isset($_localpath) && is_array($key)){
			$_localpath = $key;
		}else{
			return self::_get_item($_localpath, $key);
		}	
		 * 
		 */			
	}		
	
	/*******************************************************/
	/**
	 * システムリソースファイルのファイルパスを取得します
	 * 外部で使用する場合は各クラスで関数を作り必要なパスを公開してください
	 * 
	 * @name localresource
	 * @param string $key 配列の要素名。未指定なら全件
	 * 
	 * @return any
	*/
	public static function localresource($key){
		//まだ変数に値がない場合のみ初期化処理を行う。
		static $_localresource = null;
		if (!isset($_localresource) && is_array($key)){
			$_localresource = $key;
		}else{
			return self::_get_item($_localresource, $key);
		}
	}			
	
	/*******************************************************/
	/**
	 * URLを解析した各種情報を取得します
	 * @name request
	 * @param string $key 配列の要素名。未指定なら全件
	 * 
	 * @return any
	*/
	public static function request($key){
		//まだ変数に値がない場合のみ初期化処理を行う。
		static $_request = null;
		if (!isset($_request) && is_array($key)){
			$_request = $key;
		}else{
			return self::_get_item($_request, $key);
		}		
	}	
		
	/*******************************************************/
	/**
	 * 設定ファイル[sys]の値を取得します。
	 * @name sys
	 * @param string $key 配列の要素名。未指定なら全件
	 * 
	 * @return any
	*/
	public static function sys($key=""){
		//まだ変数に値がない場合のみ初期化処理を行う。
		static $_sys = null;
		if (!isset($_sys) && is_array($key)){
			$_sys = $key;
		}else{
			return self::_get_item($_sys, $key);
		}		
	}
	
	/*******************************************************/
	/**
	 * 設定ファイル[db]の値を取得します
	 * @name db
	 * @param string $key 配列の要素名。未指定なら全件
	 * @return any
	*/
	public static function db($key=""){
		//まだ変数に値がない場合のみ初期化処理を行う。
		static $_db = null;
		if (!isset($_db) && is_array($key)){
			$_db = $key;
		}else{
			return self::_get_item($_db, $key);
		}		
	}	
	
	/*******************************************************/
	/**
	 * 設定ファイル[route]の値を取得します
	 * @name route
	 * @param string $key 配列の要素名。未指定なら全件
	 * @return any
	*/	
	public static function route($key=""){
		//まだ変数に値がない場合のみ初期化処理を行う。
		static $_route = null;
		if (!isset($_route) && is_array($key)){
			$_route = $key;
		}else{
			return self::_get_item($_route, $key);
		}		
	}
	
	/*******************************************************/
	/**
	 * 設定ファイル[user]の値を取得します
	 * @name user
	 * @param string $key 配列の要素名。未指定なら全件
	 * @return any
	*/
	public static function user($key=""){
		//まだ変数に値がない場合のみ初期化処理を行う。
		static $_user = null;
		if (!isset($_user) && is_array($key)){
			$_user = $key;
		}else{
			return self::_get_item($_user, $key);
		}		
	}	
	
	/*******************************************************/
	/**
	 * 設定ファイル[layout]の値を取得します
	 * @name layout
	 * @param string $key 配列の要素名。未指定なら全件
	 * @return any
	*/	
	public static function layout($key=""){
		//まだ変数に値がない場合のみ初期化処理を行う。
		static $_layout = null;
		if (!isset($_layout) && is_array($key)){
			$_layout = $key;
		}else{
			return self::_get_item($_layout, $key);
		}
	}
	
	/*******************************************************/
	/**
	 * 配列から指定の値を取得します
	 * @name _get_item
	 * @param string $ary 値を取得する変数
	 * @param string $key 配列の要素名。未指定なら全件
	 * @return any
	*/	
	private static function _get_item($ary ,$key=""){
		
		if (!isset($ary)){
			return null;
		}
		if (!$key){
			return $ary;
		}else{
			if (isset($ary[$key])){
				return $ary[$key];
			}else{
				return null;
			}
			
		}
	}			
	
	/*******************************************************/
	/**
	 * viewやテンプレートファイルを詠み込んだかどうかを取得します。
	 * この関数はerror_exception.phpでheadタグを書くかどうかを判定するためにつかっています
	 * @name is_view_loaded
	 * @param bool $loaded :templateを詠み込んだらtrueを入れる
	 * @return bool
	*/
	public static function is_view_loaded($loaded=""){
		
		static $_loaded = false;
		if ($loaded){
			$_loaded = true;
		}
		return $_loaded;
	}		
	
	/*******************************************************/
	/**
	 * urlが短縮url(//で始まるかどうか)をチェックして短縮なら現在の通信のプロトコルをつけて戻します
	 * この関数のラッパーはrouteにあります
	 * @name check_short_url
	 * @param string $url チェックするurl文字列
	 * @param string $pcl configの一番最初の時だけセットされていないので引数で渡す。通常は使わない
	 * @return 通信プロトコルを付与したURL文字列で戻す（URLの最後に/はつきません）
	*/
	public static function check_short_url($url,$pcl=""){
		if (!$pcl){
			$pcl = self::route("protocol");
		}
		
		if (strpos($url, "//") === 0 ){
			$url = $pcl.":".$url;
		}
		return rtrim($url,"/");
		
	}			
	
	/*******************************************************/
	/**
	 * indexページ専用のコントローラかどうかをチェックします。専用の場合はtrueが戻ります
	 * @name check_index_only_controller
	 * @param string $ctrl チェックするコントローラ名
	 * @return true:indexページ専用コントローラ、false:専用じゃない
	*/
	public static function check_index_only_controller($ctrl){
		
		$ret = false;
		if (is_array(self::route("index_only_controller"))){
			foreach (self::route("index_only_controller") as $value) {
				if ($value === $ctrl){
					$ret = true;
					break;
				}
			}					
		}

		return $ret;
		
	}				
	
	/*******************************************************/
	/**
	 * カスタマイズ機能の接頭語を取得
	 * @name get_custom_prefix
	 * @param bool $returntype 1:カスタム用接頭語(先頭大文字、最後に_)が戻る[デフォルト]。2:cssやjsフォルダ用の接頭語(先頭に_)が戻る
	 * @return カスタマイズ機能が無効や未設定の場合は空、有効の場合は$returntypeに準じた接頭語が戻る
	 * 
	*/		
	public static function get_custom_prefix($returntype=1)
	{
		//カスタマイズ機能を使う場合は接頭語を取得
		$cstm_prefix="";

		if (GC_Static::sys("use_customize") && GC_Static::sys("customize_prefix")){
			$cstm_prefix=ucfirst(GC_Static::sys("customize_prefix"))."_";//先頭大文字、最後に_をつける
			
			//2:cssなどの内部フォルダ用
			if ($returntype === 2){
				$cstm_prefix="_".GC_Static::sys("customize_prefix");//先頭に_をつける
			}
		}

		return $cstm_prefix;
	}		
	
}


/*
 * 更新履歴
 * 2017-04-05 
 * ・extensionやconnectionなどスペルミスの修正にともなうローカルパスの変更
 * ・カスタマイズ機能追加のため新しいlocalpathの追加。_initialize_config、_initialize_pathの処理修正
 * 
 * 2017-04-14 カスタマイズ機能にcssやjsのフォルダ構成ルールを追加。get_custom_prefixを拡張
 */

?>
