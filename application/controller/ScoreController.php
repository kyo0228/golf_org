<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*******************************************************/
/**
 * ログイン関連
 * @name LoginController
*/
class ScoreController extends MY_Controller {

	/**
	 * $this->db
	 * @var ScoreDao
	 */	
	protected $db;  
	
	/*******************************************************/
	/**
	 * コンストラクタ
	*/
 public function __construct() {
		parent::__construct();
	}
	
	/*******************************************************/
	/**
	 * トップページ
	 * @name index_action
	 * @return なし
	*/
	public function index_action() {
    
    $ary_compe = array();
    $compe_list = $this->db->get_compelist($this->page->value("group_data","group_id"));
    
    //トップのスコア未登録に表示するのは未完了で開催日がtoday以前
    foreach ($compe_list as $row) {
      
      $t1 = strtotime($row["compe_date"]);
      $t2 = time();
      
      if (!$row["finish_flg"] && $t1 < $t2){
        $ary_compe[] = $row;
      }      
    }    
    $this->page->set_item("compe_list", $ary_compe);   
    
    //トップの最新ラウンドを取得
    $new = $this->db->get_new_compe($this->page->value("group_data","group_id"));
    $this->page->set_item("compe_data", array());
    if ($new){
      $compe = $this->db->get_compe_member($new["compe_id"]);
      $this->page->set_item("compe_data", $compe);
    }
    
    $this->page->set_item("func", "top");
    $this->page->name = "トップ";
    $this->page->show();
		
	}	
	
	/*******************************************************/
	/**
	 * メンバー一覧
	 * @name member_list_action
	 * @return なし
	*/
	public function member_list_action() {
    
    $member_list = $this->db->get_memberlist($this->page->value("group_data","group_id"));
    $this->page->set_item("member_list", $member_list);    
    
    
    $this->page->set_item("func", "member");
    $this->page->name = "メンバー";
    $this->page->show();
		
	}		
  
	/*******************************************************/
	/**
	 * メンバー編集
	 * @name member_edit_action
   * @param string $id 新規=new,編集=member_id
	 * @return なし
	*/
	public function member_edit_action($id) {
    
    if ($id === "new"){
      $this->page->set_item("mode", "new");
      $this->page->set_item("member_data", array("member_id"=>"new"));
      $this->page->set_item("edit_url", $this->page->url_view("member_insert", "ajax"));
    }else{
      $member_data = $this->db->get_member($id, $this->page->value("group_data","group_id"));
      
      if (!$member_data){
        $this->output_error_404();
        exit();        
      }      
      $this->page->set_item("mode", "edit");
      $this->page->set_item("member_data", $member_data);
      $this->page->set_item("edit_url", $this->page->url_view("member_update", "ajax", array($id)));
    }
    
    $this->page->set_item("func", "member");
    $this->page->name = "メンバー設定";
    $this->page->show();
		
	}		  
  
	/*******************************************************/
	/**
	 * コンペ履歴
	 * @name compe_list_action
	 * @return なし
	*/
	public function compe_list_action() {
    
    $compe_list = $this->db->get_compelist($this->page->value("group_data","group_id"));
    
    for ($i = 0; $i < count($compe_list); $i++) {
      $compe_list[$i] = $this->_compe_display_data($compe_list[$i]);
      if ($compe_list[$i]["finish_flg"]){
        $member = $this->db->get_compe_member($compe_list[$i]["compe_id"]);
        $cnt = 1;
        foreach ($member as $row) {
          $compe_list[$i]["finish_".$cnt."_name"] = $row["member_name"];
          $compe_list[$i]["finish_".$cnt."_gross"] = $row["gross"];
          $compe_list[$i]["finish_".$cnt."_net"] = $row["net"];
          $cnt++;
          
          if ($cnt === 4){break;}
        }
      }
      
    }
    
    $this->page->set_item("compe_list",$compe_list);
    
    $this->page->set_item("func", "compe");
    $this->page->name = "コンペ履歴";
    $this->page->show();
		
	}		  
  
	/*******************************************************/
	/**
	 * コンペ詳細
	 * @name compe_detail_action
	 * @return なし
	*/
	public function compe_detail_action($id) {
    
    $compe_data = $this->db->get_compe($id, $this->page->value("group_data","group_id"));

    //レコードがない場合はURL直指定。別グループや実レコードなし
    if (!$compe_data){
      $this->output_error_404();
      exit();        
    }
    
    
    $this->page->set_item("finish_url", $this->page->url_view("compe_finish", "ajax"));
    $this->page->set_item("compe_data",$this->_compe_display_data($compe_data));
    $this->page->set_item("compe_member",$this->db->get_compe_member($id));
    
    
    $this->page->set_item("func", "compe");
    $this->page->name = "コンペ詳細";
    $this->page->show();
		
	}		    
  
	/*******************************************************/
	/**
	 * コンペ詳細の表示情報を作成
	 * @name _compe_display_data
   * @param array $data コンペ詳細1レコードの配列
	 * @return 結果を追加した配列
	*/  
  private function _compe_display_data($data){
    $week_name = array("日", "月", "火", "水", "木", "金", "土");
    
    $date = new DateTime($data["compe_date"]);
    $str= $date->format('Y/m/d')."(".$week_name[$date->format('w')].")";
    
    if ($data["weather"]){
      $str.="　".$data["weather"];
    }
    
    $data["compe_date_val"] = $str;
    
    $course = "";
    if ($data["course"]){
      $course = $data["course"];
    }
    
    if (!$data["cnt"]){
      $data["cnt"] = 0;
    }
    $course.="　参加者".$data["cnt"]."人";
    $data["compe_course_val"] = $course;
    
    
    $now = new DateTime();
    $now->setTime(0, 0, 0);
    
    $playdate ="";
    if ($date->format('Y/m/d') == $now->format('Y/m/d') ){
      $playdate="本日開催日!";
    }else if($date->format('Y/m/d') > $now->format('Y/m/d') ){
      $diff= $date->diff($now);
      if ($diff->days === 1){
        $playdate="明日開催！";
      }else{
        $playdate="開催まで後".$diff->days."日！";
      }
    } 
    
    $data["compe_play_val"] = $playdate;
    
    $data["image_01_path"] = $this->page->url_images("no-image.jpg");
    $data["image_02_path"] = $this->page->url_images("no-image.jpg");
    $data["image_03_path"] = $this->page->url_images("no-image.jpg");

    if ($data["image_01"]){$data["image_01_path"] = $this->page->url_images("compe/".$data["compe_id"]."/".$data["image_01"]);}
    if ($data["image_02"]){$data["image_02_path"] = $this->page->url_images("compe/".$data["compe_id"]."/".$data["image_02"]);}
    if ($data["image_03"]){$data["image_03_path"] = $this->page->url_images("compe/".$data["compe_id"]."/".$data["image_03"]);}
    
    return $data;
        
  }
  
	/*******************************************************/
	/**
	 * コンペ登録
	 * @name compe_edit_action
   * @param string $id コンペID
	 * @return なし
	*/
	public function compe_edit_action($id) {
    
    if ($id === "new"){
      $this->page->set_item("mode", "new");
      $this->page->set_item("compe_data", array("compe_id"=>"new","compe_date"=>date("Y-m-d")));
      $this->page->set_item("edit_url", $this->page->url_view("compe_insert", "ajax"));
    }else{
      $compe_data = $this->db->get_compe($id, $this->page->value("group_data","group_id"));
      
      if (!$compe_data){
        $this->output_error_404();
        exit();        
      }      
      $this->page->set_item("mode", "edit");
      $this->page->set_item("compe_data",$this->_compe_display_data($compe_data));
      $this->page->set_item("edit_url", $this->page->url_view("compe_update", "ajax", array($id)));
      $this->page->set_item("edit_url_today", $this->page->url_view("compe_update_today", "ajax", array($id)));

    }    
    
    $this->page->set_item("member_list", $this->db->get_compe_member_edit($this->page->value("group_data","group_id"),$id));
    
    $this->page->set_item("func", "compe");
    $this->page->name = "コンペ登録";
    $this->page->show();
		
	}		      
  
	/*******************************************************/
	/**
	 * スコア登録
	 * @name score_edit_action
   * @param string $id コンペID
	 * @return なし
	*/
	public function score_edit_action($id) {
    
    $compe_data = $this->db->get_compe($id, $this->page->value("group_data","group_id"));

    //レコードがない場合はURL直指定。別グループや実レコードなし
    if (!$compe_data){
      $this->output_error_404();
      exit();        
    }
    $this->page->set_item("compe_data",$this->_compe_display_data($compe_data));
    $this->page->set_item("compe_member",$this->db->get_compe_member($id));    
    
    
    $this->page->set_item("edit_url", $this->page->url_view("score_update", "ajax", array($id)));
    $this->page->set_item("del_url", $this->page->url_view("compe_member_del", "ajax", array($id)));
    
    $this->page->set_item("func", "compe");
    $this->page->name = "スコア登録";
    $this->page->show();
		
	}		        
  
	/*******************************************************/
	/**
	 * ハンデ一括登録
	 * @name score_hande_edit_action
   * @param string $id コンペID
	 * @return なし
	*/
	public function score_hande_edit_action($id) {
    
    $compe_data = $this->db->get_compe($id, $this->page->value("group_data","group_id"));

    //レコードがない場合はURL直指定。別グループや実レコードなし
    if (!$compe_data){
      $this->output_error_404();
      exit();        
    }
    $this->page->set_item("compe_data",$this->_compe_display_data($compe_data));
    $this->page->set_item("compe_member",$this->db->get_compe_member($id));    
    
    
    $this->page->set_item("edit_url", $this->page->url_view("hande_update", "ajax", array($id)));
    
    $this->page->set_item("func", "compe");
    $this->page->name = "ハンデ登録";
    $this->page->show();
		
	}		          
  
	/*******************************************************/
	/**
	 * gross一括登録
	 * @name score_gross_edit_action
   * @param string $id コンペID
	 * @return なし
	*/
	public function score_gross_edit_action($id) {
    
    $compe_data = $this->db->get_compe($id, $this->page->value("group_data","group_id"));

    //レコードがない場合はURL直指定。別グループや実レコードなし
    if (!$compe_data){
      $this->output_error_404();
      exit();        
    }
    $this->page->set_item("compe_data",$this->_compe_display_data($compe_data));
    $this->page->set_item("compe_member",$this->db->get_compe_member($id));    
    
    
    $this->page->set_item("edit_url", $this->page->url_view("gross_update", "ajax", array($id)));
    
    $this->page->set_item("func", "compe");
    $this->page->name = "ハンデ登録";
    $this->page->show();
		
	}		            
  
	/*******************************************************/
	/**
	 * スコア集計
	 * @name total_list_action
   * @param string $sort 未指定：ネット値、指定ある場合：num,gross,netを指定
   * @param string $term 未指定：最新年度、その他指定年度、all:全年度
	 * @return なし
	*/
	public function total_list_action($sort="",$term="") {
    
    $this->page->set_item("this_url", $this->page->url_view("total_list", "score", array("[sort]","[term]")));
    
    //並び順
    if (!$sort){$sort = "net";}
    $this->page->set_item("sort", $sort);
    
    //期間コンボ用のデータ
    $compe_term = $this->db->get_compe_term($this->page->value("group_data","group_id"));
    
    $data=array();
    if (count($compe_term)){
      if (!$term){$term = $compe_term[0]["compe_term"];}
      
      //スコア集計
      $data = $this->db->get_total($this->page->value("group_data","group_id"), $sort,$term);
      
    }
    
    $this->page->set_item("term", $term);
    $this->page->set_item("compe_term", $compe_term);
    $this->page->set_item("total_year", $data);
    
    
    $this->page->set_item("func", "total");
    $this->page->name = "スコア集計";
    $this->page->show();
		
	}		         
  
	/*******************************************************/
	/**
	 * スコア集計の個人詳細
	 * @name total_list_action
   * @param string $memberid メンバーID
   * @param string $sort 並び順(前画面戻り用)
   * @param string $term 集計期間
	 * @return なし
	*/
	public function total_detail_action($memberid,$sort,$term) {
    $this->page->set_item("sort", $sort);
    $this->page->set_item("term", $term);
    
    //スコア集計
    $this->page->set_item("member_detail", $this->db->get_total($this->page->value("group_data","group_id"), "",$term,$memberid));
    
    //スコア一覧
    $this->page->set_item("member_data", $this->db->get_total_member($memberid,$term));
    
    $this->page->set_item("func", "total");
    $this->page->name = "集計詳細";
    $this->page->show();
  }  
	

}
