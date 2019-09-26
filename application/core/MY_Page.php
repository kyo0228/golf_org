<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*******************************************************/
/**
 * GC_Page拡張
 * @name MY_Page
*/
class MY_Page extends GC_Page {

	/**
	 * 左メニューを表示する場合はtrue
   * @name show_leftmenu
	 */
	public $show_leftmenu = true;
	
	/**
	 * ページタイトルを表示する場合はtrue
   * @name show_title
	 */
	public $show_title = true;	
	
	/**
	 * ページヘッダーのhomeとログアウトアイコンを表示する場合はtrue
   * @name show_header_icon
	 */
	public $show_header_icon = true;
	
	/*******************************************************/
	/**
	 * コンストラクタ
   * @name __construct
   * @access public
   * @see GC_Page::__construct()
   * @return なし
	 */
	public function __construct() {
		parent::__construct();
	}
	
	/*******************************************************/
	/**
	 * test
	 */
	public function url_view($page,$controller="",$args = null) {
    $url = parent::url_view($page,$controller,$args);
		return $url."?group=".$this->get("group");
	}	
	
}
