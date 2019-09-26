<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*******************************************************/
/**
 * DB結果保持クラス
 * @name GC_DB_Result (core/GC_DB_Result.php)
 * 
*/

class GC_DB_Result extends GC_Abstract_Base {
	
	protected $_result_type = "";
	protected $_result_data = array();

	/*******************************************************/
	/**
	 * コンストラクタ
	*/
	public function __construct() {
		//parent::__construct();		
	}
	
	/*******************************************************/
	/**
	 * selectの結果の戻りをクラスで戻すための処理を実施。コントローラから使うことはありません
	 * @name set_object
	 * @param class $obj クラス
	*/
	public function set_object($obj) {
		$this->_result_type = "object";
		$this->_result_data = $obj;
	}	
	
	/*******************************************************/
	/**
	 * @name selectの結果の戻りを配列で戻すための処理を実施。コントローラから使うことはありません
	 * @param class $ary 配列
	*/
	public function set_array($ary) {
		$this->_result_type = "array";
		$this->_result_data = $ary;
				
	}		
	
	/*******************************************************/
	/**
	 * selectしたデータを全件返します。
	 * @name result
	 * @return any 配列 or クラスの配列
	*/
	public function result() {
		return $this->_result_data;
	}			
	
	/*******************************************************/
	/**
	 * selectしたデータを1レコード分だけ返します。
	 * @name row
	 * @param int $rowidx 行インデックスを指定すると指定行を戻します。（デフォルトは先頭0）
	 * @return any 配列 or クラス
	 * 
	*/
	public function row($rowidx=0) {
		if (count($this->_result_data) < $rowidx + 1 ){
			return null;
		}else{
			return $this->_result_data[$rowidx];
		}
	}				
	
	/*******************************************************/
	/**
	 * クエリで返されたレコード数を取得します。
	 * @name num_rows
	 * @param int $rowidx 行インデックスを指定すると指定行を戻します。（デフォルトは先頭0）
	 * @return int レコード数
	 * 
	*/
	public function num_rows() {
		return count($this->_result_data);
	}					
	
	/*******************************************************/
	/**
	 * 問い合わせ結果のフィールド数を取得します。
	 * @name num_fields
	 * @return int フィールド数。結果が0の場合はフィールド数も0となります
	 * 
	*/
	public function num_fields() {
		
		if (!$this->num_rows()){
			return 0;
		}
		if ($this->_result_type == "array"){
			return count($this->_result_data[0]);
		}else{
			return count((array)$this->_result_data[0]);
		}
	}	
}	

/*
 * 更新履歴
 * 2017-11-06 row()関数でselect結果が0の場合にphpの注意文がでないよう調整
 * 
 */

?>