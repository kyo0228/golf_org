<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*******************************************************/
/**
 * 共通ログ出力クラス。Logクラスの機能拡張はこちらへ
 * @name MY_Log (core/MY_Log.php)
*/
class MY_Log extends GC_Log {
	
	/*******************************************************/
	/**
	 * コンストラクタ
	*/
	public function __construct() {
		parent::__construct();		
	}
}	

?>