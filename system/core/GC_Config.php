<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*******************************************************/
/**
 * コンフィグ情報保持クラス
 * とはいってもコンフィグデータ本体はGC_Static。
 * Controllerで$this->configと使わせるためのラッパークラス
 * @name GC_Config (core/GC_Config.php)
*/
class GC_Config {
	/*******************************************************/
	/**
	 * コンストラクタ
	*/
	public function __construct() {
		
	}	
	/*******************************************************/
	/**
	 * 設定ファイル[sys]の値を取得します
	 * @name sys
	 * @param string $key 配列の要素名。未指定なら全件
	 * @return any
	*/
	public function sys($key=""){
		return GC_Static::sys($key);
	}
	
	/*******************************************************/
	/**
	 * 設定ファイル[db]の値を取得します
	 * @name db
	 * @param string $key 配列の要素名。未指定なら全件
	 * @return any
	*/
	public function db($key=""){
		return GC_Static::db($key);
	}
		
	/*******************************************************/
	/**
	 * 設定ファイル[route]の値を取得します
	 * @name route
	 * @param string $key 配列の要素名。未指定なら全件
	 * @return any
	*/	
	public function route($key=""){
		return GC_Static::route($key);
	}	
	
	/*******************************************************/
	/**
	 * 設定ファイル[user]の値を取得します
	 * @name user
	 * @param string $key 配列の要素名。未指定なら全件
	 * @return any
	*/	
	public function user($key=""){
		return GC_Static::user($key);
	}		

	/*******************************************************/
	/**
	 * 設定ファイル[layout]の値を取得します
	 * @name layout
	 * @param string $key 配列の要素名。未指定なら全件
	 * @return any
	*/	
	public function layout($key=""){
		return GC_Static::layout($key);
	}
}