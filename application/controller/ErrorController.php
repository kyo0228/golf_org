<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*******************************************************/
/**
 * ログイン関連
 * @name LoginController
*/
class ErrorController extends GC_Controller {

	
	/*******************************************************/
	/**
	 * コンストラクタ
	*/
 protected function __construct() {
		parent::__construct();
		
		$this->page->load_resource_head($this->page->url_libraries("jquery-1.10.2/jquery-1.10.2.min.js"));
		$this->page->load_resource_head($this->page->url_libraries("lightbox/jquery.js"));
		$this->page->load_resource_head($this->page->url_libraries("lightbox/common.js"));
		$this->page->load_resource_head($this->page->url_libraries("lightbox/lightbox-plus.js"));
		$this->page->load_resource_head($this->page->url_js("scrollTop.js"));
		$this->page->load_resource_head($this->page->url_libraries("jquery.flexslider/jquery.flexslider-min.js"));
		$this->page->load_resource_head($this->page->url_js("news_tiker.js"));
		$this->page->load_resource_head($this->page->url_js("searchModel.js"));
		$this->page->load_resource_head($this->page->url_libraries("lightbox/lightbox.css"),"screen,tv");
		$this->page->load_resource_head($this->page->url_css("reset.css"));
		$this->page->load_resource_head($this->page->url_css("common.css"));
		$this->page->load_resource_head($this->page->url_css("news_tiker.css"));
		$this->page->load_resource_head($this->page->url_libraries("jquery.flexslider/flexslider.css"));
		$this->page->load_resource_head($this->page->url_libraries("font-awesome/font-awesome.min.css"));
		$this->page->load_resource_head($this->page->url_css("search.css"));
		
	}
	
	/*******************************************************/
	/**
	 * controllerのindexページ
	 * @name index_action
	 * @return なし
	*/
	public function index_action($args) {
		print("args:".$args."<br />");
		
		print("page->url():".$this->page->url()."<br />");
		print("page->url(array(fit)):".$this->page->url(array("fit"))."<br />");
		print("page->url_view(index):".$this->page->url_view("index")."<br />");
		print("page->url_view(index,array(fit)):".$this->page->url_view("index","",array("fit"))."<br />");
		print("page->url_view(index,Login,array(fit)):".$this->page->url_view("index","Login",array("fit"))."<br />");
		
	}	
	
	/*******************************************************/
	/**
	 * aaaページ
	 * @name aaa_action
	 * @return なし
	*/
	public function aaa_action() {
		$a = $this->page->my_url(array("11","222"));
		
	}	
	
	
	
	

}
