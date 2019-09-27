<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*******************************************************/
/**					
 * Siteコントローラ。システム共通ページなどで利用
 * @name SiteController					
 */
class LoginController extends MY_Controller
{

	/**
	 * $this->db
	 * @var SiteDao
	 */
	protected $db;

	/*******************************************************/
	/**				
	 * コンストラクタ				
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/*******************************************************/
	/**				
	 * index
	 * @name index
	 * @return なし				
	 */
	public function index_action()
	{

		//ログインボタン押下時
		if ($this->page->post("btn_login")) {

			$id = strtoupper($this->page->post("LoginId", true));
			$member = strtoupper($this->page->value("group_data", "member_pw"));
			$admin = strtoupper($this->page->value("group_data", "admin_pw"));

			if ($id == $member) {
				$this->session->set_data("user_auth", "member");
			} elseif ($id == $admin) {
				$this->session->set_data("user_auth", "admin");
			} else {
				//ログインが正しくありません
				$this->page->error("ログインが正しくありません");
				$this->session->delete_all();
			}

			if ($this->session->get_data("user_auth")) {
				$this->redirect($this->page->url_view("index", "score"));
			}
		}

		$this->page->show("login");
	}

	/*******************************************************/
	/**				
	 * ログアウト
	 * @name exit
	 * @return なし				
	 */
	public function exit_action()
	{
		$this->session->delete_all();
		$this->redirect($this->page->url_view("index", "login"));
	}
}
