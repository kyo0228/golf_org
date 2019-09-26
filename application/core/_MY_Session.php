<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*******************************************************/
/**
 * セッションの管理クラス。Sessionクラスの機能拡張はこちらへ
 * @name MY_Session (core/MY_Session.php)
*/
class MY_Session extends GC_Session {	

	/*******************************************************/
	/**
	 * コンストラクタ
	*/
	public function __construct() {
		parent::__construct();		
	}
}


?>