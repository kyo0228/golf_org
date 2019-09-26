<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*******************************************************/
/**
 * コンフィグ情報保持クラス。Configクラスの機能拡張はこちらへ
 * @name MY_Config (core/MY_Config.php)
*/
class MY_Config extends GC_Config {
	protected $my_val;
	/*******************************************************/
	/**
	 * コンストラクタ
	*/
 protected function __construct() {
		parent::__construct();
	 
		$this->my_val = "aaa";
		
		$this->_user["aaa"] = $this->my_val;
		
		$this->my_val = "bbb";
	}
}
?>