<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*******************************************************/
/**
 * 共通データベース処理クラス。
 * GC_DB_Connectionのインスタンスを持っているだけの継承元クラス
 * @name GC_DB (core/GC_DB.php)
*/
class GC_DB extends GC_Abstract_Base {
	/**
	 * GC_DBクラスのインスタンス
	 * @var GC_DB_Connection
	*/	
	protected $db;
		
	/*******************************************************/
	/**
	 * コンストラクタ
	*/
	public function __construct() {
		$this->db = &GC_DB_Connection::get_instance();
	}	
}	

/*
 * 更新履歴
 * 2017-04-05 connction→connectionのスペルミス対応
 * 
 */
?>