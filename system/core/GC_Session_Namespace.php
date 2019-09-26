<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*******************************************************/
/**

 * セッションの名前空間用管理クラス。namespaceを提供して同一session内で名前のバッティングを防いだり複数ページにも対応させる
 * イテレータインターフェイスの「getIterator」を実装することで外部にてプロパティがkey-valueの値として参照できる 
 * 
 * 継承禁止（このクラス名を使って判定しているから）
 * 
 * @name GC_Session_Namespace (core/GC_Session_Namespace.php)
*/
final class GC_Session_Namespace implements IteratorAggregate {
	
	private $_namespace;
	private $_session_time;
	
	/*******************************************************/
	/**
	 * コンストラクタ
	*/
	public function __construct($session_namespace) {
		$this->_namespace = $session_namespace;
		$this->set_session_time();
	}
	
	/*******************************************************/
	/**
	 * 現在の時刻をセット。名前空間毎のセッション有効時間で使用
	*/
	public function set_session_time() {
		$this->_session_time = time();
	}
	
	/*******************************************************/
	/**
	 * 現在の時刻をセット。名前空間毎のセッション有効時間で使用
	*/
	public function get_session_time() {
		return $this->_session_time;
	}	
	
	/*******************************************************/
	/**
	 * 外部イテレータを取得する
	 * @name getIterator
	 * @return オブジェクトのインスタンス
	*/
	public function getIterator() {
		return new ArrayIterator($this);
  }
	
	/*******************************************************/
	/**
	 * マジックメソッド。動的なプロパティのセット
	 * @name __set
	*/	
	public function __set($name, $value)
	{
		$this->set_session_time();
		$this->$name = $value;
	}

	/*******************************************************/
	/**
	 * マジックメソッド。動的なプロパティの取得
	 * @name __get
	 * 
	*/		
	public function __get($name)
	{
		$this->set_session_time();
		return $this->$name;		

	}

	/*******************************************************/
	/**
	 * マジックメソッド。動的にセットしたプロパティが存在するか
	 * @name __isset
	*/	
	public function __isset($name)
	{
		$this->set_session_time();
		return isset($this->$name);
	}

	/*******************************************************/
	/**
	 * マジックメソッド。動的なプロパティを削除
	 * @name __unset
	*/	
	public function __unset($name)
	{
		$this->set_session_time();
		unset($this->$name);
	}	
}

?>