<?php					
defined('BASEPATH') OR exit('No direct script access allowed');					

/*******************************************************/					
/**					
 * ajax関連のコントローラ。このコントローラはMy_Controllerを継承しない
 * @name AjaxController
*/					
class AjaxController extends MY_Controller {

	/**
	 * $this->db
	 * @var AjaxDao
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
	 * controllerのindexページ				
	 * @name index_action				
	*/				
	public function index_action() {
		$this->output_error_404();
	}

	/*******************************************************/				
	/**				
	 * メンバー登録
	 * @name member_insert
	 * @return json
	*/				
	public function member_insert_action() {
    
    if (count($this->page->post_array())){
      
      try{
        $this->db->member_insert($this->page->post_array());
        echo json_encode(array('success'=> true,'message'=>'メンバー登録をおこないました'));
      }catch(Exception $e){
        echo json_encode(array('success'=> false,'message'=>$e->getMessage()));
      }

    }else{
      echo json_encode(array('success'=> false,'message'=>'正しいリクエストではありません'));
    }
    

	}
  
	/*******************************************************/				
	/**				
	 * メンバー更新
	 * @name member_update
	 * @return json
	*/				
	public function member_update_action() {
    
    if (count($this->page->post_array())){
      
      try{
        $this->db->member_update($this->page->post_array());
        echo json_encode(array('success'=> true,'message'=>'メンバー情報の変更をおこないました'));
      }catch(Exception $e){
        echo json_encode(array('success'=> false,'message'=>$e->getMessage()));
      }

    }else{
      echo json_encode(array('success'=> false,'message'=>'正しいリクエストではありません'));
    }
    

	}  
  
	/*******************************************************/				
	/**				
	 * コンペ新規登録
	 * @name compe_insert
	 * @return json
	*/				
	public function compe_insert_action() {
    
    if (count($this->page->post_array())){
      
      try{
        $this->db->compe_insert($this->page->post_array());
        echo json_encode(array('success'=> true,'message'=>'コンペ登録をおこないました'));
      }catch(Exception $e){
        echo json_encode(array('success'=> false,'message'=>$e->getMessage()));
      }

    }else{
      echo json_encode(array('success'=> false,'message'=>'正しいリクエストではありません'));
    }
    

	}
  
  /*******************************************************/				
	/**				
	 * コンペ編集
	 * @name compe_update
	 * @return json
	*/				
	public function compe_finish_action() {
    
    if (count($this->page->post_array())){
      
      try{
        $this->db->compe_finish($this->page->post_array());
        echo json_encode(array('success'=> true,'message'=>'コンペ完了登録をおこないました'));
      }catch(Exception $e){
        echo json_encode(array('success'=> false,'message'=>$e->getMessage()));
      }

    }else{
      echo json_encode(array('success'=> false,'message'=>'正しいリクエストではありません'));
    }
    

	}
  
  /*******************************************************/				
	/**				
	 * コンペ編集
	 * @name compe_update
	 * @return json
	*/				
	public function compe_update_action() {
    
    if (count($this->page->post_array())){
      
      try{
        $this->db->compe_update($this->page->post_array());
        echo json_encode(array('success'=> true,'message'=>'コンペ登録をおこないました'));
      }catch(Exception $e){
        echo json_encode(array('success'=> false,'message'=>$e->getMessage()));
      }

    }else{
      echo json_encode(array('success'=> false,'message'=>'正しいリクエストではありません'));
    }
    

	}
  
  /*******************************************************/				
	/**				
	 * コンペの当日情報編集
	 * @name compe_update_todat
	 * @return json
	*/				
	public function compe_update_today_action() {
    
    if (count($this->page->post_array())){
      
      try{
        
        
        //upload画像がある場合
        $photo_name=array();
        for ($i = 1; $i <= 3; $i++) {
          $upfile = $this->page->post_files("selected_img".$i);
          
          if ($upfile["name"] != "") { //アップロードファイルあり
            $photo_name["image_0".$i] = $this->_set_compe_img($this->page->post("compe_id"), $upfile); //画像配置
            //$this->db->update_estimate_img($ary["est_id"], $change_no, $photo_name); //DB更新
            //$photo_path = $this->page->url_images("estimate/est".$ary["est_id"]."/photo".$change_no."/".$photo_name); //更新後に表示する画像のパス

            //元画像削除
            /*
            if ($this->page->post("photo".$change_no."_org")) {
              if (file_exists($this->page->path_images("estimate/est".$ary["est_id"]."/photo".$change_no."/".$this->page->post("photo".$change_no."_org")))) {
                unlink($this->page->path_images("estimate/est".$ary["est_id"]."/photo".$change_no."/".$this->page->post("photo".$change_no."_org")));
              }
            }
             * 
             */
          }                  
        }
        /*
        
		if ($change_mode == "upload") { //アップロード
			$upfile = $this->page->post_files("photo".$change_no."_path");		
			if ($upfile["name"] != "") { //アップロードファイルあり
				$photo_name = $this->_set_estimate_img($ary["est_id"], $change_no); //画像配置
				$this->db->update_estimate_img($ary["est_id"], $change_no, $photo_name); //DB更新
				$photo_path = $this->page->url_images("estimate/est".$ary["est_id"]."/photo".$change_no."/".$photo_name); //更新後に表示する画像のパス
				
				//元画像削除
				if ($this->page->post("photo".$change_no."_org")) {
					if (file_exists($this->page->path_images("estimate/est".$ary["est_id"]."/photo".$change_no."/".$this->page->post("photo".$change_no."_org")))) {
						unlink($this->page->path_images("estimate/est".$ary["est_id"]."/photo".$change_no."/".$this->page->post("photo".$change_no."_org")));
					}
				}
			}
		} elseif ($change_mode == "delete") { //取消
			$this->db->update_estimate_img($ary["est_id"], $change_no, ""); //DB更新
			//元画像削除
			if ($this->page->post("photo".$change_no."_org")) {
				if (file_exists($this->page->path_images("estimate/est".$ary["est_id"]."/photo".$change_no."/".$this->page->post("photo".$change_no."_org")))) {
					unlink($this->page->path_images("estimate/est".$ary["est_id"]."/photo".$change_no."/".$this->page->post("photo".$change_no."_org")));
				}
			}	
			$photo_path = $this->page->url_images("no-image.jpg"); //更新後に表示する画像のパス
		}			
         * 
         */	        
        
        

        $compe = $this->db->compe_update_today($this->page->post_array(),$photo_name);
        if ($compe["image_01"]){$img01 = $this->page->path_images("compe/".$compe["compe_id"]."/".$compe["image_01"]);}
        if ($compe["image_02"]){$img02 = $this->page->path_images("compe/".$compe["compe_id"]."/".$compe["image_02"]);}
        if ($compe["image_03"]){$img03 = $this->page->path_images("compe/".$compe["compe_id"]."/".$compe["image_03"]);}
          
        //最新のコンペデータが戻るので不要な画像ファイルを削除
        foreach(glob($this->page->path_images("compe/".$compe["compe_id"]."/*")) as $file){
          
          $is_enable = false;
          if($file === $img01 || $file === $img02 || $file === $img03){
            $is_enable = true;
          }
          
          if (!$is_enable){
            unlink($file);
          }
        }        
        
        
        
        
        echo json_encode(array('success'=> true,'message'=>'コンペ登録をおこないました'));
      }catch(Exception $e){
        echo json_encode(array('success'=> false,'message'=>$e->getMessage()));
      }

    }else{
      echo json_encode(array('success'=> false,'message'=>'正しいリクエストではありません'));
    }
    

	}
  
  
	/*******************************************************/
	/**
	 * 見積画像の配置
	 * @name _set_estimate_img
	 * @param string $compe_id コンペID
	 * @param string $file アップロード画像情報
	 * @return string 保存パス
	 */
	private function _set_compe_img($compe_id, $file){			
						
		//見積画像のフォルダ作成
		$uDir = $this->_create_compe_dir($compe_id);
		
			
		//$file = $this->page->post_files("photo".$change_no."_path");
		
		return $this->set_img($uDir, $file);
	}	

	/*******************************************************/
	/**
	 * コンペ画像のフォルダ作成
	 * @name _create_compe_dir
	 * @param string $compe_id コンペID
	 * @return 画像フォルダパス
	 */
	private function _create_compe_dir($compe_id) {
		$uDir = $this->page->path_images("compe/");
    if(!is_dir($uDir)) {mkdir($uDir, 0777);}
		chmod($uDir, 0777);
		
		
		$uDir .= $compe_id."/";
    if(!is_dir($uDir)) {mkdir($uDir, 0777);}
		chmod($uDir, 0777);
		
		return $uDir;
	}  
  
  /*******************************************************/				
	/**				
	 * スコア登録
	 * @name score_update
	 * @return json
	*/				
	public function score_update_action() {
    
    if (count($this->page->post_array())){
      
      try{
        $this->db->score_update($this->page->post_array());
        echo json_encode(array('success'=> true,'message'=>'スコア登録をおこないました'));
      }catch(Exception $e){
        echo json_encode(array('success'=> false,'message'=>$e->getMessage()));
      }

    }else{
      echo json_encode(array('success'=> false,'message'=>'正しいリクエストではありません'));
    }

	}  
  
  /*******************************************************/				
	/**				
	 * ハンデ登録
	 * @name hande_update
	 * @return json
	*/				
	public function hande_update_action() {
    
    if (count($this->page->post_array())){
      
      try{
        $this->db->hande_update($this->page->post_array());
        echo json_encode(array('success'=> true,'message'=>'ハンデ登録をおこないました'));
      }catch(Exception $e){
        echo json_encode(array('success'=> false,'message'=>$e->getMessage()));
      }

    }else{
      echo json_encode(array('success'=> false,'message'=>'正しいリクエストではありません'));
    }

	}    
  
  /*******************************************************/				
	/**				
	 * グロス登録
	 * @name gross_update
	 * @return json
	*/				
	public function gross_update_action() {
    
    if (count($this->page->post_array())){
      
      try{
        $this->db->gross_update($this->page->post_array());
        echo json_encode(array('success'=> true,'message'=>'グロス登録をおこないました'));
      }catch(Exception $e){
        echo json_encode(array('success'=> false,'message'=>$e->getMessage()));
      }

    }else{
      echo json_encode(array('success'=> false,'message'=>'正しいリクエストではありません'));
    }

	}      
  
  /*******************************************************/				
	/**				
	 * スコア登録画面からの個別不参加
	 * @name compe_member_del
	 * @return json
	*/				
	public function compe_member_del_action() {
    
    if (count($this->page->post_array())){
      
      try{
        $this->db->compe_member_del($this->page->post_array());
        echo json_encode(array('success'=> true,'message'=>'不参加登録をおこないました'));
      }catch(Exception $e){
        echo json_encode(array('success'=> false,'message'=>$e->getMessage()));
      }

    }else{
      echo json_encode(array('success'=> false,'message'=>'正しいリクエストではありません'));
    }

	}    
  
	
	/*******************************************************/				
	/**				
	 * 画面で選択された科目名の詳細を取得
	 * @name ajax_element_detail_action
	 * @return json形式の配列
	*/
	public function ajax_element_detail_action(){
		$elm_name = $this->page->get('name',true);
		if ($elm_name){
			$data = $this->db->get_element_detail($elm_name);
		}
		
		if (count($data)){
			$json = json_encode($data);
			echo $json;
		}else{
			echo false;
		}
		
	}
  
	/*******************************************************/				
	/**				
	 * 設定画面
	 * @name setting_action
	*/				
	public function setting_action() {
    $type="";
    $value="";
    $name="";
    
    
    if ($this->page->post("btn_condition")){
      $type="1";
      $value=$this->page->post("rdo_condition");
      $name="集計方法";
    }
    elseif ($this->page->post("btn_context_cond_del")){
      $type="2";
      $value=$this->page->post("txt_context_cond_del");
      $name="除外条件";      
    }elseif ($this->page->post("btn_context_cond_add")){
      $type="3";
      $value=$this->page->post("txt_context_cond_add");
      $name="指定条件";
    }
    
    //ボタンおされていたら更新
    if ($type){
      try{
        $this->db->edit_element_cond($type,$value);
        $this->page->error($name."の登録をおこないました。");
      } catch (Exception $e) {
        $this->page->error($e->getMessage());
        $this->log->exception($e);
      }
    }
    
    //方法取得
    $this->page->set_item(
            "rdo_condition",
            $this->db->get_element_cond("1")
    );
    
    //全件取得
    $this->page->set_item(
            "txt_context_cond_del",
            $this->db->get_element_cond("2","1")
    );            
    $this->page->set_item(
            "txt_context_cond_add",
            $this->db->get_element_cond("3","1")
    );                  
    
    $this->page->name = "設定";
		$this->page->show();
	}
}
