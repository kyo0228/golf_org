<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*******************************************************/
/**
 * 例外処理クラス。Exceptionクラスの機能拡張はこちらへ
 * @name MY_Exception (core/MY_Exception.php)
*/
class MY_Exception extends GC_Exception {

	/*******************************************************/
	/**
	 * コンストラクタ
	*/
	public function __construct() {
		parent::__construct();			
	}
}	
				

?>