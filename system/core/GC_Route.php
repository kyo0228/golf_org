<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*******************************************************/
/**
 * リクエスト、ルーティング情報保持クラス
 * @name GC_Route (core/GC_Route.php)
*/
class GC_Route extends GC_Abstract_Base {

	/**
	 * UserAgentから判断した端末タイプ。pc,sp,tab,fp
	 * @var string
	*/
	protected $_device_type="";

	/**
	 * UserAgentから判断した端末のプラットフォーム。iOSやwindowsなど
	 * @var string
	*/	
	protected $_os_type="";

	/**
	 * UserAgentから判断した端末のブラウザ。ie11,chromeなど
	 * @var string
	*/		
	protected $_browser_type="";
	
	/**
	 * UserAgentからbotと判断したい文字列の配列。googlebot,bingbotなどをセット。
	 * 後から追加するにはua_add_bot()でbotと判断する文字列を追加してください
	 * @var aray
	*/		
	protected $_bot_data=array();	
	
	/**
	 * UserAgentからbotと判断した場合はtrueを返します。
	 * @var string
	*/		
	protected $_is_bot=false;		
	
	/*******************************************************/
	/**
	 * コンストラクタ。
	 * 
	*/
	public function __construct() {
		//parent::__construct();
	}
	
	/*******************************************************/
	/**
	 * 通信のプロトコル(http or https)を取得を取得します
	 * 
	 * @name request_protocol
	 * @return string http or https
	*/
	public function request_protocol(){
		return GC_Static::route("protocol");
	}
	
	
	/*******************************************************/
	/**
	 * 通信のURLを取得します
	 * @name request_url
	 * @param bool $is_real_request true:index.phpがついたままのrequest_urlを返します。false:index.phpを除いたURLを返します[既定値]
	 * @return string URL
	*/
	public function request_url($is_real_request=false){
		if ($is_real_request){
			return GC_Static::request("url_real");
		}else{
			return GC_Static::request("url_sys");
		}		
	}
	
	/*******************************************************/
	/**
	 * リクエストから判定されたコントローラ名を取得します
	 * @name controller
	 * @param bool $is_name true:Controller名(TopとかLoginなど名前)、false:XxxControllerのクラス名で戻す(既定値)
	 * @return string コントローラ名(Top,Loginなど)またはクラス名(TopController,LoginControllerなど)
	*/	
	public function controller($is_name=false){
		if ($is_name){
			return GC_Static::request("controller");
		}else{
			return GC_Static::request("controller_class");
		}
	}		
	
	/*******************************************************/
	/**
	 * リクエストから判定されたページ名を取得します
	 * @name page
	 * @param bool $is_name true:page名(indexとかinfoなどの名前)、false:controller内のxx_actionの名前で戻す(既定値)
	 * @return string ページ名(index,infoなど。空の場合はindexページ)またはコントローラ内関数名(index_action,info_actionなど)
	*/	
	public function page($is_name=false){
		if ($is_name){
			return GC_Static::request("page");
		}else{
			return GC_Static::request("page_function");
		}
	}
	
	/*******************************************************/
	/**
	 * リクエストから判定されたページのパラメータ部を配列で取得します
	 * @name page_args
	 * @return array URLのpage/以降に書かれたパラメータ部
	*/	
	public function page_args(){
		return GC_Static::request("page_args");
	}
	
	/*******************************************************/
	/**
	 * 指定urlが短縮url(//で始まるかどうか)をチェックして短縮なら現在の通信のプロトコルをつけて戻します
	 * @name check_short_url
	 * @param string $url チェックするurl文字列
	 * @return string 通信プロトコルを付与したURL文字列で戻す（URLの最後に/はつきません）
	*/	
	public function check_short_url($url){
		return GC_Static::check_short_url("$url");
	}	
	
	/*******************************************************/
	/**
	 * error_404ページを出力して処理を終了させます
	 * @name output_error_404
	 * @param string $url 見つからないURL
	*/	
	public function output_error_404($url) {
		require GC_Static::localresource("error_404");
		exit;
	}	

	/*******************************************************/
	/**
	 * ただいまメンテ中ページを出力して処理を終了させます
	 * @name output_info_maintenance
	 * @param string $title ページタイトル
	 * @param string $mainte_message フリーメッセージ
	*/	
	public function output_info_maintenance($title,$mainte_message) {
		require GC_Static::localresource("info_maintenance");
		exit;
	}	
	
	/*******************************************************/
	/**
	 * コンフィグファイルからメンテナンスモードのチェック処理。許可のないIPの場合はメンテ中ページをだして終了
	 * @name check_auto_maintenance
	 * @return なし。menteページを表示する場合はページを出力しておしまい。別URLへリダイレクトさせる場合もそれでおしまい。
	*/	
	public function check_auto_maintenance() {
		
		if (!GC_Static::route('use_mainte_mode')){return;}

		//通過許可IPのチェック
		$ipary = GC_Static::route('mainte_through_ip');
		
		$is_through = false;
		if (isset($ipary)){
			foreach ($ipary as $ip) {
				if ($this->server('REMOTE_ADDR') === $ip){
					$is_through = true;
					break;
				}				
			}
		}
		
		//通過してはいけない場合
		if (!$is_through){
			if (GC_Static::route('mainte_redirect_url')){
				
				//今回のリクエストが自分のサイトのメンテページ用コントローラ名ならば通過
				if (GC_Static::route('mainte_redirect_controller') !== GC_Static::request("controller")){
					//違ったらリダイレクト
					$this->redirect(GC_Static::check_short_url(GC_Static::route('mainte_redirect_url')));
				}				
			}else{
				$this->output_info_maintenance(GC_Static::sys('apl_name'), GC_Static::route('mainte_message'));
			}
		}		
	}		
	
	/*******************************************************/
	/**
	 * コンフィグファイルからデバイス別のオートリダイレクトをチェックして一致した場合はリダイレクトを行います。
	 * 例えばPCサイトにスマホがアクセスしてきた場合など自動的にスマホサイトへリダイレクトします
	 * @name check_device_redirect
	 * @return なし
	*/
	public function check_device_redirect(){
		if (!GC_Static::route('use_device_redirect')){return;}

		//URLに特定の文字が入っている場合はリダイレクトせず終了
		foreach (GC_Static::route('device_redirect_ignore_word') as $value) {
			if (strpos(GC_Static::request("url_sys"), $value) > 0){
				return;
			}
		}		
		
		$url = "";
		$ctl = "";
		if ($this->ua_is_smartphone()){
			$url = GC_Static::route('device_redirect_url_sp');
			$ctl = GC_Static::route('device_redirect_controller_sp');
		}elseif ($this->ua_is_featurephone()){
			$url = GC_Static::route('device_redirect_url_fp');
			$ctl = GC_Static::route('device_redirect_controller_fp');
		}elseif ($this->ua_is_tablet()){
			if (GC_Static::route('device_redirect_tablet_mode') === "tb" ){
				$url = GC_Static::route('device_redirect_url_tb');
				$ctl = GC_Static::route('device_redirect_controller_tb');
			}elseif (GC_Static::route('device_redirect_tablet_mode') === "sp" ){
				$url = GC_Static::route('device_redirect_url_sp');
				$ctl = GC_Static::route('device_redirect_controller_sp');				
			}
		}
		
		if ($url){
			//短縮URLなら調整
			$url = $this->check_short_url($url);
			
			
			//今回のリクエストが自分のサイトの指定コントローラ名ならば通過
			if ($this->controller(true) !== $ctl){
				//違ったらリダイレクト
				$this->redirect($url);
			}			
		}
	}	
	
	
	/*******************************************************/
	/**
	 * UserAgentよりPCからのアクセスかどうかを判定します。
	 * スマホ、タブレット、ガラケー以外はすべてPCという判定にしています。
	 * 
	 * @name ua_is_pc
	 * @return bool true:PCからのアクセス、false:PC以外からのアクセス
	*/	
	public function ua_is_pc(){
		static $_device_type;
		if (!isset($_device_type)){
			$this->_ua_device_type();
		}
		return ($_device_type === "pc");
	}	
	
	/*******************************************************/
	/**
	 * UserAgentよりスマホからのアクセスかどうかを判定します。
	 * 
	 * @name ua_is_smartphone
	 * @return bool true:スマホからのアクセス、false:スマホ以外からのアクセス
	*/	
	public function ua_is_smartphone(){
		if (!$this->_device_type){
			$this->_ua_device_type();
		}
		return ($this->_device_type === "sp");
	}		
	
	/*******************************************************/
	/**
	 * UserAgentよりタブレットからのアクセスかどうかを判定します。
	 * 
	 * @name ua_is_tablet
	 * @return bool true:タブレットからのアクセス、false:タブレット以外からのアクセス
	*/	
	public function ua_is_tablet(){
		if (!$this->_device_type){
			$this->_ua_device_type();
		}
		return ($this->_device_type === "tb");
	}			
	
	/*******************************************************/
	/**
	 * UserAgentよりガラケーからのアクセスかどうかを判定します。
	 * 
	 * @name ua_is_featurephone
	 * @return bool true:ガラケーからのアクセス、false:ガラケー以外からのアクセス
	*/	
	public function ua_is_featurephone(){
		if (!$this->_device_type){
			$this->_ua_device_type();
		}
		return ($this->_device_type === "fp");
	}				
	
	/*******************************************************/
	/**
	 * UserAgentより端末タイプを取得します。
	 * @name _ua_device_type
	 * @return なし
	*/	
	protected function _ua_device_type(){
	 
		$this->_device_type = "pc";
		$ua = $this->server('HTTP_USER_AGENT',2);
		//可能性の高そうなものから順にチェック
		if(strpos($ua,'windows nt') !== false){
			return;
		}elseif((strpos($ua,'android') !== false) && (strpos($ua, 'mobile') !== false)){
			$this->_device_type = "sp";
		}elseif((strpos($ua,'android') !== false) && (strpos($ua, 'mobile') === false)){
			$this->_device_type = 'tb';		
		}elseif(strpos($ua,'iphone') !== false){
			$this->_device_type = "sp";
		}elseif(strpos($ua,'ipad') !== false){
			$this->_device_type = 'tb';			
		}elseif(strpos($ua,'ipod') !== false){
			$this->_device_type = "sp";
		}elseif((strpos($ua,'os x') !== false)){
			return;			
		}elseif((strpos($ua,'windows') !== false) && (strpos($ua, 'phone') !== false)){
			$this->_device_type = "sp";			
		}elseif((strpos($ua,'windows') !== false) && (strpos($ua, 'touch') !== false && (strpos($ua, 'tablet pc') == false))){
			$this->_device_type = 'tb';
		}elseif((strpos($ua,'firefox') !== false) && (strpos($ua, 'mobile') !== false)){
			$this->_device_type = "sp";
		}elseif((strpos($ua,'firefox') !== false) && (strpos($ua, 'tablet') !== false)){
			$this->_device_type = 'tb';			
		}elseif(strpos($ua,'docomo') !== false){
			$this->_device_type = "fp";
		}elseif((strpos($ua,'kddi') !== false) || (strpos($ua, 'up.browser') !== false)|| (strpos($ua, 'pdxgw') !== false)){
			$this->_device_type = "fp";
		}elseif((strpos($ua,'softbank') !== false) || (strpos($ua, 'vodafone') !== false) || (strpos($ua, 'j-phone') !== false) || (strpos($ua,'mot-') !== false)){
			$this->_device_type = "fp";
		}elseif((strpos($ua,'kindle') !== false) || (strpos($ua, 'silk') !== false)){
			$this->_device_type = 'tb';
		}elseif(strpos($ua,'blackberry') !== false){
			$this->_device_type = "sp";			
		}elseif((strpos($ua, 'emobile') !== false) || (strpos($ua, 'huawei') !== false) || (strpos($ua, 'iac') !== false)){
			$this->_device_type = "fp";
		}elseif((strpos($ua, 'willcom') !== false)|| (strpos($ua, 'ddipocket') !== false)){
			$this->_device_type = "fp";
		}elseif((strpos($ua,'playbook') !== false)){
			$this->_device_type = 'tb';
		}
	}		
	
	/*******************************************************/
	/**
	 * UserAgentよりアクセスしてきた端末のOSを判定します。
	 * あまり馴染みのないものはunknownにしちゃってます
	 * 
	 * @name ua_os
	 * @return string OS名(windows 10,android,ios,unknownなど)
	*/	
	public function ua_os(){
		if (!$this->_os_type){
			$this->_ua_os_type();
		}
		return $this->_os_type;
	}				
	
	/*******************************************************/
	/**
	 * UserAgentよりアクセスしてきた端末のOSを判定します。
	 * あまり馴染みのないものはunknownにしちゃってます
	 * @name _ua_os_type
	 * @return なし
	*/	
	protected function _ua_os_type(){
	 
		$this->_os_type = "unknown";
		$ua = $this->server('HTTP_USER_AGENT',2);
		
		//判定しやすいものから順にチェック
		if((strpos($ua,'android') !== false)){
			$this->_os_type = "android";
		}elseif((strpos($ua,'iphone') !== false) || (strpos($ua,'ipad') !== false) || (strpos($ua,'ipod') !== false)){
			$this->_os_type = "ios";		
		}elseif(strpos($ua,'windows nt 10.0') !== false){
			$this->_os_type = "windows 10";
		}elseif((strpos($ua,'windows nt 6.3') !== false)){
			$this->_os_type = "windows 8.1";
		}elseif((strpos($ua,'windows nt 6.2') !== false)){
			$this->_os_type = "windows 8";
		}elseif((strpos($ua,'windows nt 6.1') !== false)){
			$this->_os_type = "windows 7";
		}elseif((strpos($ua,'windows nt 6.0') !== false)){
			$this->_os_type = "windows vista";
		}elseif((strpos($ua,'windows nt 5.2') !== false)){
			$this->_os_type = "windows 2003";
		}elseif((strpos($ua,'windows nt 5.1') !== false)){
			$this->_os_type = "windows XP";
		}elseif((strpos($ua,'windows nt 5.0') !== false)){
			$this->_os_type = "windows 2000";
		}elseif((strpos($ua,'windows') !== false) && (strpos($ua, 'pリクエスト、ルーティング情報保持クラスhone') !== false)){
			$this->_os_type = "windows phone";
		}elseif((strpos($ua,'windows') !== false) && (strpos($ua, 'touch') !== false && (strpos($ua, 'tablet pc') == false))){
			$this->_os_type = "windows tablet";	
		}elseif((strpos($ua,'windows nt') !== false) || (strpos($ua, 'winnt') !== false) || (strpos($ua, 'win9') !== false) || (strpos($ua, 'windows') !== false)){
			$this->_os_type = "other windows";	
		}elseif((strpos($ua,'os x') !== false)){
			$this->_os_type = "mac os x";
		}elseif((strpos($ua,'ppc') !== false)){
			$this->_os_type = "mac";
		}elseif((strpos($ua,'firefox') !== false) && (strpos($ua, 'mobile') !== false)){
			$this->_os_type = "firefox os";
		}elseif((strpos($ua,'firefox') !== false) && (strpos($ua, 'tablet') !== false)){
			$this->_os_type = "firefox os";
		}elseif(strpos($ua,'docomo') !== false){
			$this->_os_type = "docomo"; //osじゃないけど・・
		}elseif((strpos($ua,'kddi') !== false) || (strpos($ua, 'up.browser') !== false)|| (strpos($ua, 'pdxgw') !== false)){
			$this->_os_type = "au";
		}elseif((strpos($ua,'softbank') !== false) || (strpos($ua, 'vodafone') !== false) || (strpos($ua, 'j-phone') !== false) || (strpos($ua,'mot-') !== false)){
			$this->_os_type = "softbank";
		}elseif(strpos($ua,'blackberry') !== false){
			$this->_os_type = "blackberry";			
		}elseif((strpos($ua, 'emobile') !== false) || (strpos($ua, 'huawei') !== false) || (strpos($ua, 'iac') !== false)){
			$this->_os_type = "emobile";
		}elseif((strpos($ua, 'willcom') !== false)|| (strpos($ua, 'ddipocket') !== false)){
			$this->_os_type = "willcom";
		}
	}
	
	/*******************************************************/
	/**
	 * UserAgentよりアクセスしてきた端末のブラウザを簡易判定します。
	 * メジャーではないものはotherにしています
	 * 
	 * @name ua_browser
	 * @return string ブラウザ名(ie11,edge,firefox,chrome,otherなど)
	*/	
	public function ua_browser(){
		if (!$this->_browser_type){
			$this->_ua_browser_type();
		}
		return $this->_browser_type;
	}				
	
	/*******************************************************/
	/**
	 * UserAgentよりアクセスしてきた端末のブラウザを簡易判定します。
	 * メジャーではないものはotherにしています
	 * @name _ua_browser_type
	 * @return なし
	*/	
	protected function _ua_browser_type(){
	 
		$this->_browser_type = "other";
		$ua = $this->server('HTTP_USER_AGENT',2);
		
		//edge
		if((strpos($ua,'appleWebkit') !== false) && (strpos($ua, 'edge') !== false)){
			$this->_browser_type = "edge";
		//ie11
		}elseif((strpos($ua,'trident/7.0') !== false)){
			if(strpos($ua,'msie 10.0') !== false){
				$this->_browser_type = "ie10";
			}elseif(strpos($ua,'msie 9.0') !== false){
				$this->_browser_type = "ie9";
			}elseif(strpos($ua,'msie 8.0') !== false){
				$this->_browser_type = "ie8";
			}elseif(strpos($ua,'msie 7.0') !== false){
				$this->_browser_type = "ie7";
			}else{
				$this->_browser_type = "ie11";
			}
		//～ie10
		}elseif(strpos($ua,'msie') !== false){
			if(strpos($ua,'msie 10.0') !== false){
				$this->_browser_type = "ie10";
			}elseif(strpos($ua,'msie 9.0') !== false){
				$this->_browser_type = "ie9";
			}elseif(strpos($ua,'msie 8.0') !== false){
				$this->_browser_type = "ie8";
			}elseif(strpos($ua,'msie 7.0') !== false){
				$this->_browser_type = "ie7";
			}elseif(strpos($ua,'msie 6.0') !== false){
				$this->_browser_type = "ie6";
			}else{
				$this->_browser_type = "old ie";
			}			
		//android標準（あやしい）
		}elseif(strpos($ua,'android') !== false && strpos($ua,'chrome') === false && strpos($ua,'firefox') === false){
			$this->_browser_type = "android";			
		}elseif((strpos($ua,'firefox') !== false)){
			$this->_browser_type = "firefox";
		}elseif(strpos($ua,'chrome') !== false){
			$this->_browser_type = "chrome";			
		}elseif(strpos($ua,'safari') !== false){
			$this->_browser_type = "safari";
		}elseif(strpos($ua,'opera') !== false){
			$this->_browser_type = "opera";
		//以下ガラケーは扱いに困る
		}elseif(strpos($ua,'docomo') !== false){
			$this->_browser_type = "docomo";
		}elseif((strpos($ua,'kddi') !== false) || (strpos($ua, 'up.browser') !== false)|| (strpos($ua, 'pdxgw') !== false)){
			$this->_browser_type = "au";
		}elseif((strpos($ua,'softbank') !== false) || (strpos($ua, 'vodafone') !== false) || (strpos($ua, 'j-phone') !== false) || (strpos($ua,'mot-') !== false)){
			$this->_browser_type = "softbank";
		}elseif(strpos($ua,'blackberry') !== false){
			$this->_browser_type = "blackberry";			
		}
	}	
	
	/*******************************************************/
	/**
	 * UserAgentよりbotを判断します。
	 * 判定文字列は強制遷移用や処理除外用でも使えそうなので配列にしておきます。
	 * もしもbot扱いしたい文字が増えた場合はua_add_bot()するか継承してください
	 * 
	 * @name ua_is_bot
	 * @return bool true:botと判定、false:botじゃない
	*/	
	public function ua_is_bot(){
		if (count($this->_bot_data) === 0){
			$this->_ua_init_bot();
			$this->_ua_check_bot();
		}
		return $this->_is_bot;
	}				
	
	/*******************************************************/
	/**
	 * botと判定する文字を追加します。
	 * 
	 * @name ua_is_bot
	 * @param string $botstr 判定文字
	 * @return なし
	*/	
	public function ua_add_bot($botstr){
		if (count($this->_bot_data) === 0){
			$this->_ua_init_bot();
		}
		$this->_bot_data[] = $botstr;
		$this->_ua_check_bot();
		
		return $this->_is_bot;
	}					
	
	/*******************************************************/
	/**
	 * bot判定文字の配列を初期化します
	 * @name _ua_init_bot
	 * @return なし
	*/	
	protected function _ua_init_bot(){
		$this->_bot_data = array(
			'googlebot',
			'msnbot',
			'baiduspider',
			'bingbot',
			'slurp',
			'yahoo',
			'askjeeves',
			'fastcrawler',
			'infoseek',
			'lycos',
			'yandex',
			'mediapartners-google',
			'CRAZYWEBCRAWLER',
			'adsbot-google',
			'feedfetcher-google',
			'curious george'
		);
	}	
	
	/*******************************************************/
	/**
	 * botかどうか判定します
	 * @name _ua_init_bot
	 * @return なし
	*/	
	protected function _ua_check_bot(){
		$this->_is_bot = false;
		$ua = $this->server('HTTP_USER_AGENT',2);
		foreach ($this->_bot_data as $value) {
			if(strpos($ua,$value) !== false){
				$this->_is_bot = true;
				break;
			}
		}
	}
	
	/*******************************************************/
	/**
	 *  サーバー情報および実行時の環境情報を取得します。($_SERVERのラッパー関数。ただのラッパーじつまらないから戻りモードを追加)
	 * @name server
	 * @param string $name 項目名
	 * @param int $return_mode 戻りモード 1:大文字変換、2:小文字変換、1,2以外はそのまま戻す(デフォルト)
	 * @return 値があれば文字列、なければnull
	 * @todo filter_inputはoptionで動きかわるのでいまのところは直接。そのうち変えるかも
	*/	
	public function server($name,$return_mode=0){
		if (!$_SERVER || !isset($_SERVER[$name])){
			return null;	
		}else{
			
			if ($return_mode === 1){
				return mb_strtoupper($_SERVER[$name]);
			}else if ($return_mode === 2){
				return mb_strtolower($_SERVER[$name]);
			}else{
				return $_SERVER[$name];
			}
		}
	}	
	
	/*******************************************************/
	/**
	 *  いまのリクエストがPOSTかどうか？
	 * @name is_post
	 * @return bool true:post,false:post以外
	 * @todo REQUEST_METHODの戻りは'GET', 'HEAD', 'POST', 'PUT'なのでfalseはPOST以外とした
	*/	
	public function is_post(){
		return ($this->server("REQUEST_METHOD",1) === "POST");
	}		
	
	/*******************************************************/
	/**
	 *  いまのリクエストがGETかどうか？
	 * @name is_get
	 * @return true:get,false:get以外
	 * @todo REQUEST_METHODの戻りは'GET', 'HEAD', 'POST', 'PUT'なのでfalseはGET以外とした
	*/	
	public function is_get(){
		return ($this->server("REQUEST_METHOD",1) === "GET");
	}
	
	
/*
 * 更新履歴リクエスト、ルーティング情報保持クラス
 * 2016-09-16 server,is_get,is_postの関数を追加。クラス内で$_SERVER使っているところを関数使うようにした
 * 2017-11-29 phpDocの説明文を調整。処理に変更はない
 * 
 */
	
	
	
}

?>