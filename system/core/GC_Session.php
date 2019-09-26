<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*******************************************************/
/**
 * phpグローバルセッションの管理クラス。基本的にはグローバルセッション$_SESSIONは使わせないローカルルールとしたい
 * @name GC_Session (core/GC_Session.php)
*/

class GC_Session extends GC_Abstract_Sgtn {	
	protected $_is_start = false;

	/*******************************************************/
	/**
	 * コンストラクタ
	*/
	protected function __construct() {
		
		//parent::__construct();
		$this->start();		
	}
	
	/*******************************************************/
	/**
	 * セッションを開始します。セッション名はConfig.phpに設定されたsession_nameを使用します。
	 * @name start
	 * @return なし
	*/
	public function start() {
		
		if (!GC_Static::sys("session_name")){
			throw new Exception("session_nameが設定されていません。");
		}

		$name = session_name();
		$id = session_id();
		
		
		if (!$this->_is_start){
			session_name(GC_Static::sys("session_name"));
			session_start();

		$name = session_name();
		$id = session_id();

			
			$this->_is_start = true;
			
			//名前空間のsessionタイムアウトをチェックします
			if (GC_Static::sys("use_session_ns_lifetime")){
				$this->_check_ns_lifetime();							
			}

		}
		
  }
	
	/*******************************************************/
	/**
	 * セッションを明示的に終了したい場合に使用します。closeした後は自分でstartしてください。
	 * @name close
	 * @return なし
	*/
	public function close() {
		
		if ($this->_is_start)
		{
			$this->delete_all();
		}		
  }	
	
	/*******************************************************/
	/**
	 * 名前空間のsessionタイムアウトをチェックし、時間が切れていたら名前空間を削除します
	 * @name _check_ns_lifetime
	 * @return なし
	*/
	private function _check_ns_lifetime() {
		$lifetime = GC_Static::sys("session_ns_lifetime");//ini_get("session.gc_maxlifetime");

		//数値で0以上ならチェック。
		if (is_int($lifetime) && $lifetime > 0)
		{
			foreach ($_SESSION as $key => $value) {
				//名前空間の場合は経過時間チェック
				if ($this->check_key_nsclass($key)){

					$last = $value->get_session_time();

					if ( ($last + $lifetime) < time() ){
						$this->delete_ns_all($key);
					}				
				}
			}					
		}
	}	
	
	/*******************************************************/
	/**
	 * 指定のセッションIDを設定します。
	 * 通常は自動でセッションIDが振られますが特定のセッションIDを使用する場合はstartをする前に設定してください。
	 * @name set_sessionid
	 * @param string $id セッションID
	 * @return なし
	*/
	public function set_sessionid($id) {
		session_id($id);
  }	
	
	/*******************************************************/
	/**
	 * セッションIDを取得します。
	 * @name get_sessionid
	 * @return string セッションID
	*/
	public function get_sessionid() {
		return session_id();
  }		
	
	/*******************************************************/
	/**
	 * まだセッションの名前空間で使用されていないユニークなIDを取得します。
	 * @name get_uniqueid
	 * @param int $length 文字の長さ(デフォルト10)
	 * @return string 現在のセッション名前空間で重複しないID
	*/
	public function get_uniqueid($length = 10) {
		
    static $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJLKMNOPQRSTUVWXYZ0123456789';
    $str = '';
    for ($i = 0; $i < $length; ++$i) {
        $str .= $chars[mt_rand(0, 61)];
    }
		
		//同じIDが見つかってしまったら再帰
		if ($this->check_key_nsclass($str)){
			usleep(100000);//0.1秒待機
			$str = $this->get_uniqueid($length);
		}
		
    return $str;
  }			
	
	/*******************************************************/
	/**
	 * セッションに値をセットします。
	 * @name set_data
	 * @param string $key セッション変数名
	 * @param any $value セットする値
	 * @param string $namespace 名前空間(省略可)
	 * @param bool $auto_ns 名前空間をURLから自動で取得する場合はtrue(既定値)。手動はfalse
	 * @return なし
	*/
	public function set_data($key,$value,$namespace="",$auto_ns = true){
		
		if (!$this->_is_start)
		{
			throw new Exception("Sessionが開始していません");
		}
		
		//未指定の場合にURL埋め込み名前空間を使う 
		$namespace = $this->_get_auto_ns($namespace,$auto_ns);
		
		if (!$namespace){
			$_SESSION[$key] = $value;
		}else{
			//名前空間の指定がある場合
			if (!isset($_SESSION[$namespace])){
				$_SESSION[$namespace] = new GC_Session_Namespace($namespace);	
			}else{
				if (!$this->check_key_nsclass($namespace)){
					throw new Exception("GC_Session_Namespaceを使用していないセッションキーが上書きされます。このような実装は行わないでください。");
				}
			}
			$_SESSION[$namespace]->$key = $value;
			
		}
  }
		
	/*******************************************************/
	/**
	 * セッションの値を取得します
	 * @name get_data
	 * @param string $key セッション変数名
	 * @param string $namespace 名前空間
	 * @param bool $auto_ns 名前空間をURLから自動で取得する場合はtrue(既定値)。手動はfalse
	 * @return any セッション値
	*/	
	public function get_data($key, $namespace="",$auto_ns = true){		
		
		//未指定の場合にURL埋め込み名前空間を使う
		$namespace = $this->_get_auto_ns($namespace,$auto_ns);
		
		if (!$namespace){
			return $_SESSION[$key];
		}else{
			//名前空間の指定がある場合
			if (!isset($_SESSION[$namespace])){
				return null;
			}else{
				return $_SESSION[$namespace]->$key;
			}			
		}
	}
	
	/*******************************************************/
	/**
	 * 名前空間のすべてのセッションを取得します
	 * @name get_ns_all
	 * @param string $namespace 名前空間
	 * @return 連想配列
	*/	
	public function get_ns_all($namespace){
		
		if ($this->check_key_nsclass($namespace)){
			return (array)$_SESSION[$namespace];
		}else{
			return null;
		}
	}	
	
	/*******************************************************/
	/**
	 * すべてのセッションを取得します
	 * @name get_all
	 * @return 連想配列
	*/	
	public function get_all(){		
		$ret = array();
		
		foreach ($_SESSION as $key => $value) {
			if ($this->check_key_nsclass($key)){
				$ret[$key] = $this->get_ns_all($key) ;
			}else{
				$ret[$key] = $value ;
			}			
		}			

		return $ret;
	}		

	/*******************************************************/
	/**
	 * 指定のセッション変数に値があるか確認します
	 * @name exist_data
	 * @param string $key セッション変数名
	 * @param string $namespace 名前空間
	 * @param bool $auto_ns 名前空間をURLから自動で取得する場合はtrue(既定値)。手動はfalse
	 * @return bool true:値あり、false:値なし
	*/	
	public function exist_data($key, $namespace="",$auto_ns = true){
		
		//未指定の場合にURL埋め込み名前空間を使う
		$namespace = $this->_get_auto_ns($namespace,$auto_ns);
		
		if (!$namespace){
			return isset($_SESSION[$key]);
		}else{
			//名前空間の指定がある場合
			if (!isset($_SESSION[$namespace])){
				return false;
			}else{
				return isset($_SESSION[$namespace]->$key);
			}
		}
	}

	/*******************************************************/
	/**
	 * 指定のセッション変数を削除します
	 * @name delete_data
	 * @param string $key セッション変数名
	 * @param string $namespace 名前空間
	 * @param bool $auto_ns 名前空間をURLから自動で取得する場合はtrue(既定値)。手動はfalse
	 * @return なし
	*/		
	public function delete_data($key, $namespace="",$auto_ns = true){
		//未指定の場合にURL埋め込み名前空間を使う
		$namespace = $this->_get_auto_ns($namespace,$auto_ns);
		
		if (!$namespace){
			if ($this->check_key_nsclass($namespace)){
				throw new Exception("名前空間を削除しようとしています。名前空間を一括削除する場合はdelete_ns_allを使用してください");
			}else{
				unset($_SESSION[$key]);
			}
		}else{
			//名前空間の指定がある場合
			if (isset($_SESSION[$namespace])){
				unset($_SESSION[$namespace]->$key);
			}
		}
	}	
	
	/*******************************************************/
	/**
	 * 指定の名前空間のセッションデータをすべて削除します
	 * @name delete_ns_all
	 * @param string $namespace 名前空間
	 * @return なし
	*/		
	public function delete_ns_all($namespace)
	{
		if ($this->check_key_nsclass($namespace)){
			unset($_SESSION[$namespace]);
		}
	}		
	
	/*******************************************************/
	/**
	 * セッションデータをすべて削除します
	 * @name delete_all
	 * @return なし
	*/
	public function delete_all()
	{
		
		$_SESSION = array();
		
		$name = session_name();
		$id = session_id();
		
		if (isset($_COOKIE[session_name()])) {
				setcookie(session_name(), '', time()-42000, '/');
		}		
		session_destroy();
		$this->_is_start = false;
		//unset($_SESSION);
	}			
	
	/*******************************************************/
	/**
	 * セッションが開始しているかどうかをチェックします
	 * @name is_start
	 * @return bool true:開始済み、false:開始してない
	*/		
	public function is_start()
	{
		return $this->_is_start;
	}
		
	/*******************************************************/
	/**
	 * 指定のセッションキーが名前空間クラス(GC_Session_Namespace)かどうかをチェックします
	 * @name check_key_nsclass
	 * @param string $key セッション変数名
	 * @return bool true:GC_Session_Namespace、false:その他のオブジェクト
	*/		
	public function check_key_nsclass($key)
	{
		$ret = false;
		
		if (isset($_SESSION[$key]) && is_object($_SESSION[$key]) && get_class($_SESSION[$key]) === "GC_Session_Namespace"){
			$ret = true;
		}
		return $ret;
	}
	
	/*******************************************************/
	/**
	 * 名前空間クラスのセッション有効時間を更新(アクセス毎gc_staticから呼び出され自動的に更新)
	 * @name update_ns_lifetime
	 * @param string $key セッション変数名
	 * @return なし
	*/		
	public function update_ns_lifetime($key)
	{		
		if (isset($_SESSION[$key]) && is_object($_SESSION[$key]) && get_class($_SESSION[$key]) === "GC_Session_Namespace"){
			$_SESSION[$key]->set_session_time();
		}
	}	
	
	/*******************************************************/
	/**
	 * 各関数で名前空間が未指定の時にURLに埋め込まれていたセッションを自動で取得する
	 * @name _get_auto_ns
	 * @param string $namespace 名前空間名
	 * @param bool $auto_ns 自動でセットする場合はtrue。勝手にセットしてほしくない場合はfalse
	 * @return 名前空間名
	*/		
	private function _get_auto_ns($namespace,$auto_ns)
	{
		if (!$namespace && $auto_ns){
			$val = GC_Static::request("url_session");
			
			if ($val) {
				$namespace = $val;
			}
		}
		return $namespace;
	}		
	
/*
 * 更新履歴リクエスト、ルーティング情報保持クラス
 * 2017-12-04 phpDocの説明文を調整。処理に変更はない
 * 
 */
	
}

?>