<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*******************************************************/
/**
 * コントローラの共通継承元スーパークラス。singleton(インスタンスを1つしか作らない)クラス
 * @name GC_Controller (controller/GC_Controller.php)
 * 
*/
class GC_Controller extends GC_Abstract_Sgtn {

	/**
	 * $this->route
	 * @var GC_Route
	 */	
	protected $route;
	
	/**
	 * $this->db
	 * @var GC_DB
	 */	
	protected $db;	
	
	/**
	 * $this->config
	 * @var GC_Config
	 */	
	protected $config;		
	
	/**
	 * $this->session
	 * @var GC_Session
	 */	
	protected $session;			
	
	/**
	 * $this->log
	 * @var GC_Log
	 */	
	protected $log;				
	
	/**
	 * $this->page
	 * @var GC_Page
	 */	
	protected $page;					
	
	/*******************************************************/
	/**
	 * コンストラクタ
	*/
 protected function __construct()
	{
		//Mainで読み込み済みのクラスをthisにセット。$this->log->writeのような使い方ができるようにする
		foreach (loaded_class() as $var => $class)
		{	
			$this->$var =& load_core_class($class);			
		}		
	}
	
	/*******************************************************/
	/**
	 * データクラスファイルを読み込みます。IDEを使っている場合、newはコントローラ側で行うと視認性が上がります。
	 * コントローラでnewする場合は new DC_[テーブル名]();としてください
	 * @name require_dataclass
	 * @param string $classname データクラスファイル名。拡張子不要
	 * @param bool $isnew true:newをしたインスタンスを戻します。false:requireだけ（既定値）
	 * @return $isnewがtrueの時にクラスのインスタンスを戻す
	*/	
	public function require_dataclass($classname,$isnew=false){
				
		//カスタムの存在確認を追加。2017/04/05@souma
		if (GC_Static::get_custom_prefix() && file_exists(GC_Static::localpath("cstm_dataclass").$classname.".php")){
			require_once GC_Static::localpath("cstm_dataclass").$classname.".php";
		}else{
			require_once GC_Static::localpath("apl_dataclass").$classname.".php";
		}
		
		if ($isnew){
			$classname = "DC_".$classname;
			return new $classname();			
		}				
	}
	
	/*******************************************************/
	/**
	 * モデルを読み込みます。
	 * Controllerと対になるモデル(MY_DBやXxxDao)は自動で読み込み込んでいます。(this->dbで使用可能)
	 * その他、読み込みが必要なモデルがある場合に使用してください。
	 * IDEを使っている場合、newはコントローラ側で行うと視認性が上がります。
	 * @name require_model
	 * @param string $classname モデルクラスファイル名。拡張子不要
	 * @param bool $isnew true:newをしたインスタンスを戻します。false:requireだけ（既定値）
	 * @return $isnewがtrueの時にクラスのインスタンスを戻す
	*/	
	public function require_model($classname,$isnew=false){
		
		//カスタムの存在確認を追加。2017/04/05@souma
		if (GC_Static::get_custom_prefix() && file_exists(GC_Static::localpath("cstm_model").$classname.".php")){
			require_once GC_Static::localpath("cstm_model").$classname.".php";
		}else{
			require_once GC_Static::localpath("apl_model").$classname.".php";
		}		
		
		if ($isnew){
			return new $classname();
		}
	}	
	
	/*******************************************************/
	/**
	 * 拡張クラスファイルを読み込みます。IDEを使っている場合、newはコントローラ側で行うと視認性が上がります。
	 * コントローラでnewする場合は new GC_[クラス名]();
	 * または拡張クラスを新規追加した場合や独自継承している場合 new MY_[クラス名]();としてください
	 * 
	 * @name require_extensionclass
	 * @param string $classname 拡張クラス名(例えばGC_MailならばMail)。拡張子不要
	 * @param bool $isnew true:newをしたインスタンスを戻します。false:requireだけ（既定値）
	 * @return $isnewがtrueの時にクラスのインスタンスを戻す
	*/	
	public function require_extensionclass($classname,$isnew=false){
		
		//拡張クラスはGCになく、MYのみある亊も考慮する
		$path = GC_Static::localpath("sys_extension")."GC_".$classname.".php";
		$is_sys_exists = false;
		if (file_exists($path)){
			require_once $path;
			$is_sys_exists=true;
		}
		
		$path = GC_Static::localpath("apl_extension")."MY_".$classname.".php";
		
		$is_apl_exists = false;
		if (file_exists($path)){
			require_once $path;
			$is_apl_exists=true;
		}
		
		//custom追加
		$path = GC_Static::localpath("cstm_extension").GC_Static::get_custom_prefix()."MY_".$classname.".php";
		
		$is_cstm_exists = false;
		if (file_exists($path)){
			require_once $path;
			$is_cstm_exists=true;
		}		
		
		if ($isnew){
			if ($is_cstm_exists){
				$classname = GC_Static::get_custom_prefix()."MY_".$classname;			
			}elseif ($is_apl_exists){
				$classname = "MY_".$classname;
			}elseif ($is_sys_exists){
				$classname = "GC_".$classname;
			}
			
			//newする場合はいずれかあれば
			if ($is_sys_exists || $is_apl_exists || $is_cstm_exists){
				return new $classname();
			}else{
				return null;
			}			
		}
	}	
	
	
	/*******************************************************/
	/**
	 * 「お探しのページは見つかりません」のページを出力します
	 * ※本当にエラーページへリダイレクトさせたい場合はアプリ側でリダイレクト用エラーページを作成してください。
	 * 
	 * デザインを変えたいだけの場合はview/_error/にerror_404.phpというページを用意してください
	 * 
	 * @name output_error_404
	 * @param string $url 見つからないページのurl。未指定ならば現在のurl
	 * @return notfoundページを出力して終了
	*/	
	public function output_error_404($url=""){
		
		if (!$url){
			$url = $this->route->request_url();
		}
		$this->route->output_error_404($url);		
	}	
	
	/*******************************************************/
	/**
	 * 「ただいまメンテ中」のページを出力します
	 * ※本当にメンテページへリダイレクトさせたい場合はアプリ側でリダイレクト用のページを作成してください。
	 * 
	 * デザインを変えたいだけの場合はview/_error/にinfo_maintenance.phpというページを用意してください
	 * 
	 * @name output_info_maintenance
	 * @param string $title ページタイトル。未指定ならコンフィグの[apl_name]
	 * @param string $message フリーメッセージ。未指定ならコンフィグの[mainte_message]
	 * @return メンテページを出力して終了
	*/	
	public function output_info_maintenance($title="",$message=""){
		if (!$title){
			$title = $this->config->sys("apl_name");
		}
		if (!$message){
			$message = $this->config->route("mainte_message");
		}		
		$this->route->output_info_maintenance($title,$message);
	}		
}

/*
 * 更新履歴
 * 2016-10-26 require_extensionclassを拡張。system_coreに同名のクラスがなくてもロードできるようにした。
 * 2017-04-05 カスタマイズ機能追加に伴いrequireしている関数でもカスタマイズフォルダを参照するよう改修
 */