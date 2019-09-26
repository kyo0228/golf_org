<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*******************************************************/
/**
 * 共通データベース処理クラス。DBクラスの機能拡張はこちらへ
 * @name LoginDao (core/MY_DB.php)
*/
class LoginDao extends MY_DB {
	protected $my_var="b";
	
	protected $login_var="1";
	
	/*******************************************************/
	/**
	 * コンストラクタ
	*/
	public function __construct() {
		parent::__construct();
	}
	
}	

?>