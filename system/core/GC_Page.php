<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*******************************************************/
/**
 * 
 * コントローラ、Viewで使用するページ出力クラス
 * @name GC_Page (core/GC_Page.php)
*/
class GC_Page extends GC_Abstract_Base {
	
	/**
	 * head用リソースを保持します
	 * @var array
	*/	
	protected $_resource_head=array();	
	
	/**
	 * foot用リソースを保持します
	 * @var array
	*/	
	protected $_resource_foot=array();		
	
	/**
	 * エラーメッセージを保持します
	 * @var array
	*/	
	protected $_error=array();			
	
	/**
	 * 現在のコントローラ名(login,mainなど)です。この変数は自動で値が設定されます。
	 * @var string
	*/	
	public $controller="";
	
	/**
	 * ページで使用するキーワードを設定、取得します。
	 * これは＜head＞タグの＜meta name="keywords"＞で使用されます。
	 * @var string
	*/	
	public $keywords="";			
	
	/**
	 * ページで使用する説明を設定、取得します。
	 * これは＜head＞タグの＜meta name="description"＞で使用されます。
	 * @var string
	*/	
	public $description="";	
	
	/**
	 * Pageクラスのitemを保持します
	 * @var array
	*/	
 protected $_item;
	
	/**
	 * ページ名を設定、取得します。設定された名前はhead内titleタグでも使用されます。
	 * @var string
	*/	
	public $name="";
	
	/**
	 * ページのhead内titleタグで使用する文字を設定、取得します。
	 * 初期値はアプリケーション名(Configのapl_name)が設定されています
	 * @var string
	*/	
	public $title="";			

	/**
	 * layout用パーツを読み込んで変数に保持します
	 * @var array
	*/	
	private $_layout;
	
	/**
	 * viewのデータを読み込んで変数に保持します
	 * @var string
	*/	
	private $_layout_view;		
	
	/**
	 * セッションのインスタンスを保持します。privateだけどページ内で$this->sessionと使いたいから_つけない。外からは見えない
	 * @var GC_Session
	*/	
	private $session;			
	
	
	/*******************************************************/
	/**
	 * コンストラクタ
	*/
	public function __construct() {
				
		//これらはコントローラ側で書き換え可能なプロパティ。
		$this->name = $this->_page_name();
		$this->controller = $this->_controller_name();
		$this->title = GC_Static::sys("apl_name");
		
		$this->load_resource_head($this->url_system_resource("system_cmn.css"));
		
		$this->session = GC_Session::get_instance();
	}
	
	/*******************************************************/
	/**
	 * コントローラ名を返します。($this->controllerはpublic変数のため汚染の可能性あり)
	 * 
	 * @name _controller_name
	 * @param int $mode 0:コントローラ名(全部小文字)[既定値]、1:コントローラ名(先頭大文字)、2:コントローラクラス名(XxxxControllerまで)
	 * @return string モードに合わせたコントローラ名

	*/	
 protected function _controller_name($mode=0)
	{
		if ($mode === 1){
			return GC_Static::request("controller");
		}elseif ($mode === 2){
			return GC_Static::request("controller_class");
		}else{
			return strtolower(GC_Static::request("controller"));
		}
	}	
	
	/*******************************************************/
	/**
	 * ページ名を返します。
	 * 
	 * @name _page_name
	 * @param int $mode 0:ページ名(全部小文字)[既定値]、1:ページ名（大文字、小文字加工なし）、2:ページ関数名
	 * @return string モードに合わせたページ名

	*/	
	protected function _page_name($mode=0)
	{		
		if ($mode === 1){
			return GC_Static::request("page");
		}else if($mode === 2){
			return GC_Static::request("page_action");
		}else{
			return strtolower(GC_Static::request("page"));
		}
	}		
	
	
	/*******************************************************/
	/**
	 * POST値を取得します。PHPのグローバル変数$_POSTは当フレームワークで直接使用しません。
	 * @name post
	 * @param string $name post変数名
	 * @param bool $istrim true:文字列前後の空白文字(全角,半角)を取り除いて戻します。false:生のpost値を戻す(既定値)
	 * @return any post値
	*/	
	public function post($name,$istrim = false)
	{
		return $this->_get_multi_value(1,$name,$istrim);
	}
	
	/*******************************************************/
	/**
	 * POST値をすべて取得します。PHPのグローバル変数$_POSTは当フレームワークで直接使用しません。
	 * @name post_array
	 * @return array

	*/	
	public function post_array()
	{
		return $_POST;
	}	

	/*******************************************************/
	/**
	 * POSTに値をセットします。※だたし通常はこの関数を使用しません。なにか理由がある場合にのみ使用してください。
	 * @name set_post
	 * @param string $name POST変数名
	 * @param string $value POSTにセットする値
	 * @return なし

	*/	
	public function set_post($name,$value)
	{
		$_POST[$name] = $value;
	}
	
	/*******************************************************/
	/**
	 * POSTでアップロードされたファイルを取得します。PHPのグローバル変数$_FILESは当フレームワークで直接使用しません。
	 * @name post_files
	 * @param string $name post変数名
	 * @return array アップロードファイルの連想配列($_FILES[$name]と同じ内容)
	*/	
	public function post_files($name)
	{
		return $_FILES[$name];
	}
	
	/*******************************************************/
	/**
	 * POSTでアップロードされたファイルをすべて取得します。PHPのグローバル変数$_FILESは当フレームワークで直接使用しません。
	 * @name post_files_array
	 * @return array アップロードファイルのすべての連想配列($_FILESと同じ内容)

	*/	
	public function post_files_array()
	{
		return $_FILES;
	}	
	
	/*******************************************************/
	/**
	 * POSTでアップロードされたファイルを指定のパスへ移動します。
	 * @name post_files_move
	 * @param string $src_filename アップロードしたファイル名
	 * @param string $dst_filename 移動先のファイル名(フルパス)
	 * @return bool true:成功、false:失敗

	*/	
	public function post_files_move($src_filename,$dst_filename)
	{
		//phpの標準関数呼んでるだけ
		return move_uploaded_file($src_filename,$dst_filename);
	}		
	
	/*******************************************************/
	/**
	 * GET値を取得します。PHPのグローバル変数$_GETは当フレームワークで直接使用しません。
	 * @name get
	 * @param string $name GET変数名
	 * @param bool $istrim true:文字列前後の空白文字(全角,半角)を取り除いて戻します。false:生のGET値を戻す（既定値）
	 * @return any get値

	*/	
	public function get($name,$istrim = false)
	{
		return $this->_get_multi_value(2,$name,$istrim);
	}
	
	/*******************************************************/
	/**
	 * GET値をすべて取得します。PHPのグローバル変数$_GETは当フレームワークで直接使用しません。
	 * @name get_array
	 * @return array

	*/	
	public function get_array()
	{
		return $_GET;
	}		

	/*******************************************************/
	/**
	 * GETに値をセットします。※だたし通常はこの関数を使用しません。なにか理由がある場合にのみ使用してください。
	 * @name set_get
	 * @param string $name get変数名
	 * @param string $value get値
	 * @return なし
	*/	
	public function set_get($name,$value)
	{
		$_GET[$name] = $value;
	}	
	
	/*******************************************************/
	/**
	 * ページ表示共通変数itemから値を取得します。
	 * @name item
	 * @param any $name item名
	 * @param bool $istrim true:文字列前後の空白文字(全角,半角)を取り除いて戻します。false:生のitem値を戻す（既定値）
	 * @return any itemにセットされた値
	*/	
	public function item($name,$istrim = false)
	{		
		return $this->_get_multi_value(3,$name,$istrim);	
	}
	
	/*******************************************************/
	/**
	 * ページ表示共通変数itemをすべて取得します。
	 * @name item_array
	 * @return array

	*/	
	public function item_array()
	{
		return $this->_item;
	}		

	/*******************************************************/
	/**
	 * ページ表示共通変数itemに値をセットします。
	 * ※セットした値はViewで使用する前にphpのhtmlspecialcharsが実行されるため特殊文字も変換せずそのままセットできます。
	 * ただしobject型は変換できないのでitemにセットするのは文字型か配列がよいです。
	 * @name set_item
	 * @param string $name 変数名
	 * @param any $value itemにセットする値
	 * @return なし

	*/	
	public function set_item($name,$value)
	{
		$this->_item[$name] = $value;
	}		

	/*******************************************************/
	/**
	 * 当クラスで保持しているpostかitemから配列のキー、クラスのプロパティ名と一致する値を取得します。
	 * 最初にチェックするのはpost。postにない場合はitemを探しにいきます。
	 * 
	 * ※※※※ この関数の引数は可変です。※※※※
	 * 可変と言っても第一引数は必須扱いとなります。引数なし、または存在しない引数が指定された場合はnullが戻ります。
	 * 
	 * この関数を使うとPOSTがない初期表示の時でも事前にitemへPOSTと同じキーで値を設定しておくことでView側ではpostなのかitemなのか意識することなく使う亊ができます。
	 * 
	 * 
	 * 引数がひとつの場合(例:"arg1")
	 * $this->post("arg1");
	 * $this->item("arg1"); と同義です。
	 * 
	 * 引数が２つの場合(例:"arg1",1)
	 * postかitemのargs1が2次元配列で以下のようなデータの場合
	 * args1[0]{"id" => 1 , "name" => "東京" }
	 * args1[1]{"id" => 2 , "name" => "埼玉" }
	 * args1[2]{"id" => 3 , "name" => "千葉" }
	 * 
	 * →"id" => 2 , "name" => "埼玉"　このような配列データを返します
	 * 
	 * 引数が３つの場合(例:"arg1",2,"name")
	 * 上の配列データの場合は　"千葉"　を返します
	 * 
	 * @name value
	 * @return any 値
	*/	
	public function value()
	{
		$args = func_get_args();
		if (count($args) === 0) return null;
		
		//まずポストをチェック
		$ret = $this->_get_multi_value(1,$args);
		
		//なかったらitem
		if (!isset($ret)){
			$ret = $this->_get_multi_value(3,$args);
		}

		return $ret;
	}
	
	
	/*******************************************************/
	/**
	 * post,get,itemからkeyに指定された値を取得します
	 * @name _get_multi_value
	 * @param int $mode 取得するデータタイプ(1:post,2:get,3:item)
	 * @param string or string[] $key 変数名またはキー名の配列
	 * @param bool $istrim true:共通trim処理を実施。false:trimせずそのまま戻す(既定値)
	 * @return any 値
	*/	
	private function _get_multi_value($mode,$key,$istrim = false)
	{
		$data = "";
		if ($mode === 1 || $mode === 2 || $mode === 3){
			
			if ($mode === 1) $data = $this->post_array();
			if ($mode === 2) $data = $this->get_array ();
			if ($mode === 3) $data = $this->item_array();
		}else{
			return null;
		}
		if (!$data || !is_array($data) || count($data) === 0 || !isset($key)) return null;
		
		
		$ret = null;
		if (is_array($key)){	
			if (count($key) > 0){
				$ret = $this->_get_obj_value($data,$key);
			}
		}else{
			if (array_key_exists($key,$data)){
				$ret = $data[$key];
			}
		}
		
		if ($istrim){
			$ret = $this->trim_utf8($ret);
		}
		
		return $ret;
		
	}	

	/*******************************************************/
	/**
	 * 配列やクラスから再帰で指定のキーの値を取得します
	 * @name _get_obj_value
	 * @param array or class $data レイアウトテンプレート名
	 * @param string[] $keyAry 配列やクラスを検索するキー名
	 * @return 一致するキーが存在すれば値を戻す。なければnull
	*/	
	private function _get_obj_value($data,$keyAry){
		
		if (!is_array($keyAry) || count($keyAry) === 0)
		{
			return null;
		}
				
		for ($i = 0; $i < count($keyAry); $i++) {
			//配列
			if (is_array($data) && array_key_exists($keyAry[$i],$data)){
				$data = $data[$keyAry[$i]];
			//クラス
			}elseif (is_object($data) && property_exists($data,$keyAry[$i])){
				//publicでないプロパティ名を指定するとエラー発生
				$data = $data->$keyAry[$i];
			}else{
				//引数あるのに配列じゃないかキーがない時はnullで戻して終了
				$data = null;					
				break;
			}
		}
		
		return $data;
	}	
	
	/*******************************************************/
	/**
	 * ページを表示します。コントローラでセットしたitemに対してhtmlspecialcharを実施します。
	 * @name show
	 * @param string $layout レイアウトテンプレート名。未指定の場合はconfigフォルダLayout.phpの[default]に定義されたファイルが読み込まれる
	 * @param string $view ページ名。未指定の場合は現在のページ名に対応するviewファイルが読み込まれる
	 * @param string $controller コントローラ名。別コントローラのviewファイルを読み込む場合に使用する
	 * @return なし
	*/	
	public function show($layout="default",$view="",$controller=""){
			
		//この時点で画面で使う可能性のある全itemをフォーマット
		$this->_item = $this->html_format($this->_item);
		$_GET = $this->html_format($_GET);
		$_POST = $this->html_format($_POST);

		//pageが未指定の場合は現在のページのまま
		if (!$view){
			$view = $this->_page_name();
		}			
		
		if (!$layout){
			throw new Exception("layoutが指定されていません");
		}

		$layout_parts = GC_Static::layout($layout);

		//レイアウトがあるかないかで動きが変わる
		if (isset($layout_parts)){
			//まずレイアウトパーツを読み込み				
			$this->_load_layout($layout, $layout_parts);

			//controllerが未指定の場合は現在のcontroller
			if (!$controller){
				$controller = $this->_controller_name();
			}
			$this->_load_view($controller, $view);

			//viewやテンプレートを読み込んだ亊を通知
			GC_Static::is_view_loaded(true);
			//テンプレートを読み込み
			$this->_load_layout($layout);
		}else{				
			//viewやテンプレートを読み込んだ亊を通知
			GC_Static::is_view_loaded(true);
			//viewをいきなり読み込み
			$this->_load_view($controller, $view,false);
		}
		
		$this->_output_dump();
		

	}	
	
	/*******************************************************/
	/**
	 * View、またはレイアウトのページデータを戻します。※この関数はView側(主にテンプレート内)でコンテンツの配置用に使用します。
	 * @name layout
	 * @param string $layout 未指定の場合：Viewページの情報取得、指定あり：ヘッダーやフッターなどの情報を取得
	 * @return string View、またはレイアウトのページデータ
	*/	
	public function layout($layout=""){
		if (!$layout){
			return $this->_layout_view;
		}else{
			
			if (array_key_exists($layout, $this->_layout)){
				return $this->_layout[$layout];
			}else{
				return null;
			}			
		}
	}	
	
	/*******************************************************/
	/**
	 * layoutファイルを読み込みます
	 * @name _load_layout
	 * @param string $name レイアウト名
	 * @param array $ary コンフィグのレイアウト用配列。配列がない場合はtemplateを読み込み
	 * @return なし
	*/	
	private function _load_layout($name,$ary=null){	
		
		$path = GC_Static::localpath("apl_layout");
		//カスタマイズ機能を追加。2017/04/05@souma
		//最初にテンプレートの存在確認。customizeのレイアウトフォルダに同名のテンプレートファイルがあればカスタムを使う
		$cstm_prefix = GC_Static::get_custom_prefix();
		if ($cstm_prefix && file_exists(GC_Static::localpath("cstm_layout").$cstm_prefix.$name."_template.php")){
			$path = GC_Static::localpath("cstm_layout");
		}else{
			$cstm_prefix = "";
		}
		
		if (isset($ary)){
			//レイアウトパーツの読み込み。変数にセット		
			foreach ($ary as $value) {
				$this->_layout[$value] = "";
				
				$name = $value.".php";
				if ($cstm_prefix){
					$name = $cstm_prefix.$name;
				}
				
				if (file_exists($path.$name)){
					ob_start();
					include $path.$name;
					$this->_layout[$value] = ob_get_contents();
					ob_end_clean();
				}
			}		
		}else{
			//templateの読み込み
			$name = $cstm_prefix.$name."_template.php";
			if (file_exists($path.$name)){
				require_once $path.$name;
			}
		}		
	}	
	
	/*******************************************************/
	/**
	 * viewファイルを読み込みます
	 * @name _load_view
	 * @param string $controller コントローラ名
	 * @param array $page ページ名
	 * @param bool $use_ob true:ならob_関数使う(テンプレートがある場合)、false:ob_関数使わないで即時include
	 * @return なし
	*/	
	private function _load_view($controller,$page,$use_ob=true){	
		
		$path = $this->path_view($page, $controller);
		
		//viewを読み込み
		if (!$path){
			throw new Exception("Viewファイルが存在しません。[".$path."]");
		}
		
		if ($use_ob){
			ob_start();			
		}
		
		include $path;
		
		if ($use_ob){
			$this->_layout_view = ob_get_contents();
			ob_end_clean();		
		}		

	}	
	
	
	/*******************************************************/
	/**
	 * 現在の自分のURLを取得します
	 * 引数argsに値をセットすることで新しいパラメータをセットしたurlを取得することができます。
	 * @name url
	 * @param array $args ページのパラメータ
	 * @return string 現在の自分のurl
	 * 
	*/
	public function url($args = null) {
		
		//パラメータがセットされているときはindexページであってもフルパス+引数で返す
		if (isset($args) && is_array($args)){
			return GC_Static::request("url_page")."/".implode("/",$args);
		}else{
			
			//パラメータがなければ見やすいURLに直す
			if ($this->_check_apl_top_page($this->_controller_name(),$this->_page_name())){
				return $this->url_base();
			}else if ($this->_page_name() === "index"){
				return GC_Static::request("url_controller");
			}else{
				return GC_Static::request("url_page");
			}
		}
	}				
	
	/*******************************************************/
	/**
	 * アプリケーションのルートURLを取得します。
	 * configのbase_urlの値が戻ります。デモの場合はdemoまでが入った値が戻ります。
	 * 
	 * @name url_base
	 * @param string $file アプリrootに配置したファイルのURLを取得する場合にファイル名を指定。例:favicon.ico。でもfaviconだったらimageに入れてもらいたいな
	 * @return string url
	 * 
	*/
	public function url_base($file="") {
		
		if (!$file){			
			return GC_Static::request("url_base");
		}else{
			return GC_Static::request("url_base")."/".$file;
		}		
		
		/*
		//demoの場合
		$demo_str = "";
		if (DEMOURL){
			$demo_str = "/".DEMOURL;
		}
		
		
		if (!$file){
			return GC_Static::route("base_url").$demo_str;
		}else{
			return GC_Static::route("base_url").$demo_str."/".$file;
		}
		*/
	}		
	
	/*******************************************************/
	/**
	 * ページの拡張子を取得します。configのurl_suffixに値が設定されている場合に「.」付きで戻ります。
	 * @name url_suffix
	 * @return string [.]がついたページ用拡張子
	 * 
	*/
	public function url_suffix() {
		return GC_Static::request("page_suffix");		
	}			
	
	/*******************************************************/
	/**
	 * 指定のページURLを取得します
	 * 引数argsに値をセットすることでパラメータをセットしたurlを取得することができます。
	 * @name url_view
	 * @param string $page ページ名
	 * @param string $controller コントローラ名。省略すると現在表示中ページのコントローラが自動で設定されます。
	 * @param array $args ページのパラメータ
	 * @return string 指定ページのurl
	 * 
	*/
	public function url_view($page,$controller="",$args = null) {
		
		if (!$controller){
			$controller = GC_Static::request("controller");
			$controller_real = GC_Static::request("controller_real");
		}else{
			$controller_real = $this->_search_controller_url($controller);
		}
		
		$url = $this->url_base();
		
		
		
		//ページ引数がある場合はフルパス+引数
		if (isset($args) && is_array($args)){
			//index専用コントローラならページ部省略
			if (GC_Static::check_index_only_controller($controller)){
				return $url."/".$controller_real."/".implode("/",$args);
			}else{
				return $url."/".$controller_real."/".$page.$this->url_suffix()."/".implode("/",$args);
			}
			
		}else{
			if ($this->_check_apl_top_page($controller, $page)){
				return $url;
			}else if ($page === "index"){
				return $url."/".$controller_real;
			}else{
				return $url."/".$controller_real."/".$page.$this->url_suffix();
			}
		}
	}
	
	/*******************************************************/
	/**
	 * 指定のページURLを取得します。url_view()との違いはURLにセッション名前空間を含めてURLを作成します。
	 * @name url_view_ns
	 * @param string $page ページ名
	 * @param string $controller コントローラ名。省略すると現在表示中ページのコントローラが自動で設定されます。
	 * @param array $args ページのパラメータ
	 * @param string $namespace 名前空間(省略可)。省略した場合はURLに埋め込まれた名前空間IDを使用
	 * @return string 指定ページのurl
	 * 
	*/
	public function url_view_ns($page,$controller="",$args = null, $namespace="") {
				
		if (!$controller){
			$controller = GC_Static::request("controller");
			$controller_real = GC_Static::request("controller_real");
		}else{
			$controller_real = $this->_search_controller_url($controller);
		}
		
		//名前空間を取得
		if (!$namespace){
			$namespace = $this->url_sessionid();
		}
		if ($namespace){
			$controller_real = $controller_real."/".$namespace;
		}
		
		//名前空間埋め込み型の場合はコントローラ、ページの省略不可。なのでここでコントローラセット
		$url = $this->url_base()."/".$controller_real;
		
		if (isset($args) && is_array($args)){
			//index専用コントローラならページ部省略
			if (GC_Static::check_index_only_controller($controller)){
				return $url."/".implode("/",$args);
			}else{
				return $url."/".$page.$this->url_suffix()."/".implode("/",$args);
			}
		}else{
			return $url."/".$page.$this->url_suffix();
		}
	}	
	
	/*******************************************************/
	/**
	 * urlに埋め込まれたセッションID(名前空間ID)を取得します。この関数はセッションを名前空間を用いて運用している場合に使用します。
	 * 
	 * @name url_sessionid
	 * @return string URLに埋め込まれたID、なければ空
	 * 
	*/
	public function url_sessionid() {
		return GC_Static::request("url_session");
	}					
	
	/*******************************************************/
	/**
	 * 指定のコントローラ、ページ名の組み合わせがアプリケーションのトップページかどうかを判定します
	 * 
	 * @name _check_apl_top_page
	 * @param string $ctrl チェックするコントローラ名
	 * @param string $page チェックするページ名
	 * @return bool 指定のコントローラ、ページ名の組み合わせがアプリケーションのトップページだった場合にtrue
	 * 
	*/
	protected function _check_apl_top_page($ctrl,$page) {
		
		$base_controller = mb_strtolower(GC_Static::route("base_controller")); //アプリケーションrootのコントローラ

		//コントローラ & ページ名が一致 = アプリトップ
		return ($base_controller === mb_strtolower($ctrl) && mb_strtolower($page) === "index");
	}	
	
	/*******************************************************/
	/**
	 * コントローラ名からURL用のコントローラ部を取得する。
	 * ほとんどの場合はcontroller名=urlだがmanual_routeだと＝じゃない時があるのでチェックする
	 * 
	 * @name _search_controller_url
	 * @param string $ctrl チェックするコントローラ名
	 * @return string コントローラのURL部。みつからなかったら例外発生
	 * 
	*/
	protected function _search_controller_url($ctrl) {
		
		//autoならコントローラ名=url
		if (GC_Static::route("use_automode")){
			return lcfirst($ctrl); //先頭を小文字に戻す
		}else{
			$ret = "";
			foreach (GC_Static::route("manual_route") as $key => $value) {
				if ($value == $ctrl){
					$ret = $key;
					break;
				}
			}
			if (!$ret){
				throw new Exception("設定ファイルに存在しないコントローラ名です。[".$ctrl."]");
			}
			return $ret;
		}
	}		
	
	/*******************************************************/
	/**
	 * アプリケーションの「css」フォルダへのURLを取得します。
	 * 引数$filenameの例
	 *  default.css  → root_url/css/default.cssとなる
	 *  subdir/default.css  → root_url/css/subdir/default.cssとなる
	 * 
	 * ※カスタマイズ機能利用時
	 * カスタマイズのルールに一致したフォルダやファイルが存在した場合のみカスタマイズのURLを戻す。
	 * みつからなかったら標準ルールのURLを戻す
	 * 
	 * @name url_css
	 * @param string $filename ファイル名(拡張子も必要)
	 * @return string url
	 * 
	*/
	public function url_css($filename) {
		return $this->_get_url("css", $filename);
	}				
	
	/*******************************************************/
	/**
	 * アプリケーションの「images」フォルダへのURLを取得します。
	 * 引数$filenameの例
	 *  top.png  → root_url/images/top.pngとなる
	 *  subdir/border.jpg  → root_url/images/subdir/border.jpgとなる
	 * 
	 * ※カスタマイズ機能利用時
	 * カスタマイズのルールに一致したフォルダやファイルが存在した場合のみカスタマイズのURLを戻す。
	 * みつからなかったら標準ルールのURLを戻す
	 * 
	 * @name url_images
	 * @param string $filename ファイル名(拡張子も必要)
	 * @return string url
	 * 
	*/
	public function url_images($filename) {
		return $this->_get_url("images", $filename);
	}
	
	/*******************************************************/
	/**
	 * アプリケーションの「js」フォルダへのURLを取得します。
	 * 引数$filenameの例
	 *  main.js  → root_url/js/main.jsとなる
	 *  subdir/sub.js  → root_url/js/subdir/sub.cssとなる
	 * 
	 * ※カスタマイズ機能利用時
	 * カスタマイズのルールに一致したフォルダやファイルが存在した場合のみカスタマイズのURLを戻す。
	 * みつからなかったら標準ルールのURLを戻す
	 * 
	 * @name url_js
	 * @param string $filename ファイル名(拡張子も必要)
	 * @return string url
	 * 
	*/
	public function url_js($filename) {
		return $this->_get_url("js", $filename);
	}					
	
	/*******************************************************/
	/**
	 * アプリケーションの「libraries」フォルダへのURLを取得します。
	 * 引数$filenameの例
	 *  jquery/jquery1.10.js  → root_url/libraries/jquery/jquery1.10.jsとなる
	 * 
	 * ※カスタマイズ機能利用時
	 * カスタマイズのルールに一致したフォルダやファイルが存在した場合のみカスタマイズのURLを戻す。
	 * みつからなかったら標準ルールのURLを戻す
	 * 
	 * @name url_libraries
	 * @param string $filename ファイル名(拡張子も必要)
	 * @return string url
	 * 
	*/
	public function url_libraries($filename) {
		return $this->_get_url("libraries", $filename);
	}						
	
	/*******************************************************/
	/**
	 * アプリケーションの「pages」フォルダへのURLを取得します。
	 * 引数$filenameの例
	 *  index.html  → root_url/pages/index.htmlとなる
	 *  user/user.php  → root_url/pages/user/user.phpとなる
	 * 
	 * ※カスタマイズ機能利用時
	 * カスタマイズのルールに一致したフォルダやファイルが存在した場合のみカスタマイズのURLを戻す。
	 * みつからなかったら標準ルールのURLを戻す
	 * 
	 * @name url_pages
	 * @param string $filename ファイル名(拡張子も必要)
	 * @return string url
	 * 
	*/
	public function url_pages($filename) {
		return $this->_get_url("pages", $filename);
	}							
	
	/*******************************************************/
	/**
	 * アプリケーションの「tmp」フォルダへのURLを取得します。
	 * 引数$filenameの例
	 *  nannka.pdf  → root_url/tmp/nannka.pdfとなる
	 *  
	 * ※カスタマイズ機能利用時
	 * カスタマイズのルールに一致したフォルダやファイルが存在した場合のみカスタマイズのURLを戻す。
	 * みつからなかったら標準ルールのURLを戻す
	 * 
	 * @name url_tmp
	 * @param string $filename ファイル名(拡張子も必要)
	 * @return string url
	 * 
	*/
	public function url_tmp($filename) {
		return $this->_get_url("tmp", $filename);
	}
	
	/*******************************************************/
	/**
	 * アプリケーションの各フォルダに対応したURLを取得します。
	 *  
	 * ※カスタマイズ機能利用時
	 * カスタマイズのルールに一致したフォルダやファイルが存在した場合のみカスタマイズのURLを戻す。
	 * みつからなかったら標準ルールのURLを戻す
	 * 
	 * @name url_tmp
	 * @param string $dir アプリケーション用ルートフォルダ名(css,jsなど)
	 * @param string $filename ファイルパス
	 * @return string url
	 * 
	*/
	private function _get_url($dir,$filename) {
		$cstm_dir = GC_Static::get_custom_prefix(2);
		if ($cstm_dir && file_exists(GC_Static::localpath($dir).$cstm_dir."/".$filename)){
			return $this->url_base()."/".$dir."/".$cstm_dir."/".$filename;
		}else{
			return $this->url_base()."/".$dir."/".$filename;
		}
	}	
	
	/*******************************************************/
	/**
	 * システムの「url_system_resource」フォルダへのURLを取得します。
	 * この関数は念のためprotectedです。外からsystem/_resourceフォルダはコールしないようにしています。
	 * 
	 * @name url_system_resource
	 * @param string $filename ファイルパス
	 * @return string url
	 * 
	*/
	protected function url_system_resource($filename) {
		$base = $this->url_base();
		
		//demoへのアクセスなのにsyspathにdemoが含まれていない場合はsystemがdemoフォルダにない場合
		if (DEMOURL && strpos(SYSPATH,DEMOURL) === false ){
			$base = str_replace("/".DEMOURL, "", $base);
		}			
		
		return $base."/system/_resource/".$filename;
	}	
	
	/*******************************************************/
	/**
	 * リクエストから判定したviewのファイルパスを取得します。
	 * @name path
	 * @return string ファイルパス
	 * 
	*/
	public function path() {

		//カスタマイズ機能を追加。2017/04/05@souma

		//コントローラ名以下をセット
		$path = $this->_controller_name()."/".$this->_page_name().".php";

		//カスタムViewの存在確認。customizeのViewフォルダに同名のファイルがあれば現在のViewはカスタム
		if (GC_Static::get_custom_prefix() && file_exists(GC_Static::localpath("cstm_view").$path)){
			return GC_Static::localpath("cstm_view").$path;
		}else{
			return GC_Static::localpath("apl_view").$path;
		}
		
	}
	
	/*******************************************************/
	/**
	 * コントローラのファイルパスを取得します。
	 * @name path_controller
	 * @param string $controller コントローラ名(xxxControllerのxxx部だけでOK)。省略すると現在のリクエストで判定されたコントローラファイルパスが戻ります。
	 * @return string ファイルパス
	 * 
	*/
	public function path_controller($controller="") {
		
		//カスタマイズ機能を追加。2017/04/05@souma
		$path = $this->_controller_name(2).".php";
		if ($controller){
			$path = ucfirst($controller)."Controller.php";
		}

		if (GC_Static::get_custom_prefix() && file_exists(GC_Static::localpath("cstm_controller").$path)){
			return GC_Static::localpath("cstm_controller").$path;
		}else{
			return GC_Static::localpath("apl_controller").$path;
		}
	}		
	
	/*******************************************************/
	/**
	 * 指定のviewファイルパスを取得します。
	 * @name path_view
	 * @param string $page ページ名
	 * @param string $controller コントローラ名(xxxControllerのxxx部だけでOK)。省略すると現在のリクエストで判定されたコントローラファイルを使用します。
	 * @return string 指定viewのファイルパス。ファイルが存在しなければブランク
	 * 
	*/
	public function path_view($page,$controller="") {
		
		if ($controller){
			$c = mb_strtolower($controller)."/";
		}else{
			$c = $this->_controller_name()."/";
		}
		
		$path = GC_Static::localpath("apl_view").$c.$page.".php";
		
		//カスタムViewの存在確認。customizeのViewフォルダに同名のファイルがあれば現在のViewはカスタム
		$cstm_prefix = GC_Static::get_custom_prefix();
		if ($cstm_prefix && file_exists(GC_Static::localpath("cstm_view").$c.$cstm_prefix.$page.".php")){
			return GC_Static::localpath("cstm_view").$c.$cstm_prefix.$page.".php";
		}elseif (file_exists($path)){
			return $path;
		}else{
			return "";
		}
	}				
	
	/*******************************************************/
	/**
	 * アプリケーションの「css」フォルダのパスを取得します。
	 * 引数「$filename」を省略した場合はフォルダのパスを返します
	 *  引数あり：xxx/css/$filename
	 *  引数なし：xxx/css/　←スラッシュまで
	 *  
	 * ※カスタマイズ機能利用時
	 * カスタマイズのルールに一致したフォルダやファイルが存在した場合のみカスタマイズのパスを戻す。
	 * みつからなかったら標準ルールのパスを戻す
	 * 
	 * @name path_css
	 * @param string $filename ファイル名(拡張子も必要)。。省略した場合はフォルダのパスを返します
	 * @return string ファイルパス
	 * 
	*/
	public function path_css($filename="") {
		return $this->_get_path("css", $filename);
	}				
		
	/*******************************************************/
	/**
	 * アプリケーションの「images」フォルダのパスを取得します。
	 * 引数「$filename」を省略した場合はフォルダのパスを返します
	 *  引数あり：xxx/images/$filename
	 *  引数なし：xxx/images/　←スラッシュまで
	 *  
	 * ※カスタマイズ機能利用時
	 * カスタマイズのルールに一致したフォルダやファイルが存在した場合のみカスタマイズのパスを戻す。
	 * みつからなかったら標準ルールのパスを戻す
	 * 
	 * @name path_images
	 * @param string $filename ファイル名(拡張子も必要)。省略した場合はフォルダのパスを返します
	 * @return string ファイルパス
	 * 
	*/	
	public function path_images($filename="") {
		return $this->_get_path("images", $filename);
	}
	
	/*******************************************************/
	/**
	 * アプリケーションの「js」フォルダのパスを取得します。
	 * 引数「$filename」を省略した場合はフォルダのパスを返します
	 *  引数あり：xxx/js/$filename
	 *  引数なし：xxx/js/　←スラッシュまで
	 *  
	 * ※カスタマイズ機能利用時
	 * カスタマイズのルールに一致したフォルダやファイルが存在した場合のみカスタマイズのパスを戻す。
	 * みつからなかったら標準ルールのパスを戻す
	 * 
	 * @name path_js
	 * @param string $filename ファイル名(拡張子も必要)。省略した場合はフォルダのパスを返します
	 * @return string ファイルパス
	 * 
	*/	
	public function path_js($filename="") {
		return $this->_get_path("js", $filename);
	}	
	
	/*******************************************************/
	/**
	 * アプリケーションの「libraries」フォルダのパスを取得します。
	 * 引数「$filename」を省略した場合はフォルダのパスを返します
	 *  引数あり：xxx/libraries/$filename
	 *  引数なし：xxx/libraries/　←スラッシュまで
	 *  
	 * ※カスタマイズ機能利用時
	 * カスタマイズのルールに一致したフォルダやファイルが存在した場合のみカスタマイズのパスを戻す。
	 * みつからなかったら標準ルールのパスを戻す
	 * 
	 * @name path_libraries
	 * @param string $filename ファイル名(拡張子も必要)。省略した場合はフォルダのパスを返します
	 * @return string ファイルパス
	 * 
	*/	
	public function path_libraries($filename="") {
		return $this->_get_path("libraries", $filename);
	}		
	
	/*******************************************************/
	/**
	 * アプリケーションの「pages」フォルダのパスを取得します。
	 * 引数「$filename」を省略した場合はフォルダのパスを返します
	 *  引数あり：xxx/pages/$filename
	 *  引数なし：xxx/pages/　←スラッシュまで
	 *  
	 * ※カスタマイズ機能利用時
	 * カスタマイズのルールに一致したフォルダやファイルが存在した場合のみカスタマイズのパスを戻す。
	 * みつからなかったら標準ルールのパスを戻す
	 * 
	 * @name path_pages
	 * @param string $filename ファイル名(拡張子も必要)。省略した場合はフォルダのパスを返します
	 * @return string ファイルパス
	 * 
	*/	
	public function path_pages($filename="") {
		return $this->_get_path("pages", $filename);
	}
	
	/*******************************************************/
	/**
	 * アプリケーションの「tmp」フォルダのパスを取得します。
	 * 引数「$filename」を省略した場合はフォルダのパスを返します
	 *  引数あり：xxx/tmp/$filename
	 *  引数なし：xxx/tmp/　←スラッシュまで
	 *  
	 * ※カスタマイズ機能利用時
	 * カスタマイズのルールに一致したフォルダやファイルが存在した場合のみカスタマイズのパスを戻す。
	 * みつからなかったら標準ルールのパスを戻す
	 * 
	 * @name path_tmp
	 * @param string $filename ファイル名(拡張子も必要)。省略した場合はフォルダのパスを返します
	 * @return string ファイルパス
	 * 
	*/	
	public function path_tmp($filename="") {
		return $this->_get_path("tmp", $filename);
	}	

	/*******************************************************/
	/**
	 * アプリケーションフォルダのパスを取得します。
	 * 引数「$filename」を省略した場合はフォルダのパスを返します
	 *  引数あり：xxx/application/$filename
	 *  引数なし：xxx/application/　←スラッシュまで
	 * 
	 * @name path_application
	 * @param string $filename ファイル名(拡張子も必要)。省略した場合はフォルダのパスを返します
	 * @return string ファイルパス
	 * 
	*/	
	public function path_application($filename="") {
		if (!$filename){
			return GC_Static::localpath("apl");
		}else{
			return GC_Static::localpath("apl").$filename;
		}
	}		
	
	/*******************************************************/
	/**
	 * アプリケーション用各フォルダのパスを取得します。
	 * 引数「$filename」を省略した場合はフォルダのパスを返します
	 *  引数あり：xxx/tmp/$filename
	 *  引数なし：xxx/tmp/　←スラッシュまで
	 *  
	 * ※カスタマイズ機能利用時
	 * カスタマイズのルールに一致したフォルダやファイルが存在した場合のみカスタマイズのパスを戻す。
	 * みつからなかったら標準ルールのパスを戻す
	 * 
	 * @name _get_path
	 * @param string $dir アプリケーション用ルートフォルダ名(css,jsなど)
	 * @param string $filename ファイル名
	 * @return string ファイルパス
	 * 
	*/	
	private function _get_path($dir,$filename="") {
		$cstm_dir = GC_Static::get_custom_prefix(2);
				
		if (!$filename){
			if ($cstm_dir && file_exists(GC_Static::localpath($dir).$cstm_dir."/")){
				return GC_Static::localpath($dir).$cstm_dir."/";
			}else{
				return GC_Static::localpath($dir);
			}	
		}else{
			if ($cstm_dir && file_exists(GC_Static::localpath($dir).$cstm_dir."/".$filename)){
				return GC_Static::localpath($dir).$cstm_dir."/".$filename;
			}else{
				return GC_Static::localpath($dir).$filename;
			}
		}
	}		
	
	
	/*******************************************************/
	/**
	 * ＜head＞タグ内に記載する外部css,jsの記述ヘルパー関数です。
	 * この関数はアプリ全体で使うcssやjs,コントローラ毎やページ毎で使うcssやjsなどを
	 * コントローラ側で管理しやすくするための機能です。
	 * 
	 * 特に特定のページでのみ呼び出す外部リソースがある場合では無駄なロードが減り有効です。
	 * ・アプリ全体で使うcssなど：MY_Controllerで追加
	 * ・特定のコントローラで使う場合：XxxxControllerのコンストラクタで追加
	 * ・特定のページのみで使う場合：xxxx_action()内で追加
	 * 
	 * 第一引数の$urlは拡張子からjsかcssを判断します。
	 * layoutやviewでは記述したい場所に[$this->output_resource_head();]を呼び出してください
	 * 
	 * @name load_resource_head
	 * @param string $url js,またはcssのURL
	 * @param string $css_media ファイルタイプがcssの時、media属性を追加します（例："screen,tv"など）。省略可
	 * @return なし
	 * 
	*/
	public function load_resource_head($url,$css_media="") {
		$this->_create_resource_array(true, $url, $css_media);
	}	
	
	/*******************************************************/
	/**
	 * ページ下部に記載する外部css,jsの記述ヘルパー関数です。
	 * ページ下部にリソースを記述する場合は＜/body＞直前が多いようですが各アプリケーションにあった使い方をしてください。
	 * 
	 * ※使い方はload_resource_headと同様です。
	 * layoutやviewでは記述したい場所に[$this->output_resource_foot();]を呼び出してください
	 * 
	 * @name load_resource_foot
	 * @param string $url js,またはcssのURL
	 * @param string $css_media ファイルタイプがcssの時、media属性を追加します（例："screen,tv"など）
	 * @return なし
	 * 
	*/
	public function load_resource_foot($url,$css_media="") {
		$this->_create_resource_array(false, $url, $css_media);
	}		
	
	/*******************************************************/
	/**
	 * 外部resource用の配列を作成し共通変数にセットします
	 * 
	 * @name _create_resource_array
	 * @param bool $is_head true:head,false:foot
	 * @param string $url URL
	 * @param string $media media属性の文字
	 * @return なし
	 * 
	*/	
	private function _create_resource_array($is_head,$url,$media){
		
		if (strpos($url, ".js") !== false){
			$ext = "js";
			$media = "";
		}elseif (strpos($url, ".css") !== false){
			$ext = "css";
		}else{
			throw new Exception("未対応の外部リソースファイルです。");
		}
		
		if ($is_head){
			$this->_resource_head[] = array("type"=>$ext,"url"=>$url,"media"=>$media);
		}else{
			$this->_resource_foot[] = array("type"=>$ext,"url"=>$url,"media"=>$media);
		}	
	}
	
	/*******************************************************/
	/**
	 * ＜head＞タグに記載する外部css,js用文字列を出力します。
	 * 
	 * @name output_resource_head
	 * @return ＜script＞や＜link＞タグを出力
	 * 
	*/
	public function output_resource_head() {
		$this->_output_resource($this->_resource_head);
	}		
	
	/*******************************************************/
	/**
	 * ページの最後の方に記載する外部css,js用文字列を出力します。
	 * 
	 * @name output_resource_foot
	 * @return ＜script＞や＜link＞タグを出力
	 * 
	*/
	public function output_resource_foot() {
		$this->_output_resource($this->_resource_foot);
	}			
	
	/*******************************************************/
	/**
	 * css,js用文字列を出力します。
	 * 
	 * @name _output_resource
	 * @param array $ary_resource head or foot用配列
	 * @return ＜script＞や＜link＞タグを出力
	 * 
	*/
	private function _output_resource(&$ary_resource) {
		
		foreach ($ary_resource as $ary) {
			if ($ary["type"] == "js"){
				print("<script type='text/javascript' src='".$ary["url"]."'></script>\n");
			}else{
				$media = "";
				if ($ary["media"]){
					$media = "media='".$ary["media"]."'";
				}
				print("<link rel='stylesheet' type='text/css' href='".$ary["url"]."' ".$media." />\n");
			}			
		}
	}
	
	/*******************************************************/
	/**
	 * エラーメッセージをセットします。
	 * 配列で保持しているので１ページ内の複数のエラーを設定し一括で出力することができます
	 * viewでは記述したい場所に[$this->output_error();]を呼び出してください
	 * 
	 * @name error
	 * @param any $message 文字列、または配列でメッセージをセット
	 * @return なし
	 * 
	*/
	public function error($message) {
		if (is_array($message)){
			foreach ($message as $value) {
				$this->_error[] = $value; 
			}
		}else{
			$this->_error[] = $message; 
		}		
	}		
	
	
	/*******************************************************/
	/**
	 * セットされたエラーメッセージ数を取得します。
	 * 
	 * @name error_count
	 * @return エラーメッセージ数
	 * 
	*/
	public function error_count() {
		return count($this->_error);
	}			
	
	/*******************************************************/
	/**
	 * エラーメッセージを出力します。
	 * アプリケーション独自のレイアウト、例えばdivではなくtableで出力したい場合などは
	 * MY_Pageクラスで継承して関数をオーバーライドしてください
	 * 
	 * divのままstyleだけ変えたい場合はアプリ側のcssで[_system_error_area]と[_system_error_line]をオーバーライドするだけで大丈夫です
	 * 
	 * @name output_error
	 * @return divで囲んだメッセージを出力。エラーがセットされてなければなにも出力しません。
	 * 
	*/
	public function output_error() {
		if ($this->error_count() === 0 ) return;

		print("<div class='system_error_area'>\n");
		
		foreach ($this->_error as $value) {
			if (is_string($value)){
				print("  <p class='system_error_line'>".$value."</p>\n");
			}else{
				print("  <p class='system_error_line'>".var_dump($value)."</p>\n");
			}
			
		}		
		print("</div>\n");
	}

	/*******************************************************/
	/**
	 * 開発モードのvar_dumpを出力します。
	 * 
	 * @name output_error
	 * @return divで囲んだメッセージを出力。エラーがセットされてなければなにも出力しません。
	 * 
	*/
	protected function _output_dump() {

		//開発モードならitem変数を全部ver_dump
		if (GC_Static::sys("use_devmode")){
			print("<div class='system_dump_area'>\n");
			print("  <h4>開発モード：「item」のver_dump</h4>\n");
			print("  <p>※この表示を消したい場合はコンフィグの[use_devmode]をfalseにしてください</p>\n");
			var_dump($this->item_array());
			print("</div>\n");		
			
			print("<div class='system_dump_area'>\n");
			print("  <h4>開発モード：「session」のver_dump</h4>\n");
			if ($this->session->is_start()){
				var_dump($this->session->get_all());
			}else{
				print("  <p>セッションが開始されていません</p>\n");
			}
			
			print("</div>\n");					

			print("<div class='system_dump_area'>\n");
			print("  <h4>開発モード：「post」のver_dump</h4>\n");
			var_dump($this->post_array());
			print("</div>\n");		

			print("<div class='system_dump_area'>\n");
			print("  <h4>開発モード：「get」のver_dump</h4>\n");
			var_dump($this->get_array());
			print("</div>\n");							
			

			print("<div class='system_dump_area'>\n");
			print("  <h4>開発モード：「define」のver_dump</h4>\n");
			$defined_all = get_defined_constants(TRUE);
			var_dump( $defined_all['user'] );			
			print("</div>\n");													
			
			print("<div class='system_dump_area'>\n");
			print("  <h4>開発モード：「Static:request」のver_dump</h4>\n");
			var_dump(GC_Static::request(""));
			print("</div>\n");										
			
			print("<div class='system_dump_area'>\n");
			print("  <h4>開発モード：「Static:localpath」のver_dump</h4>\n");
			var_dump(GC_Static::localpath(""));
			print("</div>\n");													
			
			print("<div class='system_dump_area'>\n");
			print("  <h4>開発モード：「Static:localresource」のver_dump</h4>\n");
			var_dump(GC_Static::localresource(""));
			print("</div>\n");																
		}	 		
	}
	
	/*******************************************************/
	/**
	 * ＜title＞タグに記載するページタイトルを出力します。
	 * この関数を使用するとコントローラで設定したpage->nameとpage->titleを自動でつなげます。
	 * page->nameを設定しなければtitleだけ表示されます
	 * 
	 * 例：メイン | グローバル共通フレームワーク
	 * 
	 * @name output_title
	 * @return ＜title＞タグを自動生成
	 * 
	*/
	public function output_title() {
		if ($this->name){
			return "<title>".$this->name." | ".$this->title."</title>";
		}else{
			return "<title>".$this->title."</title>";
		}
	}			
	
	/*******************************************************/
	/**
	 * 現在のアクセスがdemoサイトへのアクセスかどうかを判定します
	 * 
	 * @name is_demo_site
	 * @return true:デモサイト、false:デモサイトではない
	 * 
	*/
	public function is_demo_site() {
		if (DEMOURL){
			return true;
		}else{
			return false;
		}
	}			
	
	/*******************************************************/
	/**
	 * postのデータを指定の配列にセットします。同名のキーがある場合は上書きします。
	 * 
	 * @name set_post_to_array
	 * @param array $ary postの値をセットしたい配列。postと同名のキーがあれば上書き
	 * @param bool $istrim true:配列にpost値をセットするときに前後空白文字(全角、半角)を除く(既定値)、false:空白は除かず元のpostのまま
	 * @param bool $isadd true:配列に同名のキーがない場合は追加(既定値)、false:配列に同名のキーがない場合は追加しない
	 * @return 配列
	 * 
	*/
	public function set_post_to_array($ary,$istrim=true,$isadd=true) {
		
		if (count($_POST)){
			
			foreach ($_POST as $key => $value) {
				$check = true;
				if (!array_key_exists($key , $ary) && $isadd == false){
					$check = false;
				}
				
				if ($check){
					if ($istrim){
						$ary[$key] = $this->trim_utf8($value);
					}else{
						$ary[$key] = $value;
					}
				}
			}
		}
		
		return $ary;
	}				
		
}	


/*
 * 更新履歴
 * 2016-10-05 value関数を拡張。配列のキーやクラスのプロパティ名を指定して取ってこれるように変更。
 * 2016-10-20 value関数と同じような機能をpost(),get(),item()にも追加。
 * 2016-10-26 path_controllerに引数追加。path_application関数を追加。path_css,path_js,path_images,path_libraries,path_pagesの引数指定してもファイル名が戻らないバグを修正
 * 2017-04-05 カスタマイズ機能追加に伴いshow()や_load_view、path_viewなどカスタマイズフォルダを参照する必要がある関数を改修
 * 2017-04-14 カスタマイズ機能にcssやjsのフォルダ構成ルールを追加。url_cssやpath_cssなどを変更
 * 2017-07-12 _get_multi_value内の書き方のせいでwarningerrorが大量に出力される部分を修正
 * 2017-11-29 phpDocの説明文を調整。処理に変更はない
 * 2018-01-25 set_post_to_arrayを追加
 * 
 */


?>