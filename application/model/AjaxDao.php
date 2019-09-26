<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*******************************************************/
/**
 * Master用のdao
 * @name AjaxDao (core/MY_DB.php)
*/
class AjaxDao extends MY_DB {
	
	/*******************************************************/
	/**
	 * コンストラクタ
	*/
	public function __construct() {
		parent::__construct();
	}
  
  
	/*******************************************************/				
	/**				
	 * メンバーを新規登録
	 * @name member_insert
   * @param array $data 新規登録用配列
	 * @return true:成功、失敗は例外スロー
	*/				
  public function member_insert($data){
    
    try{
    
      if (!$data || !count($data) || !$data["member_id"]){
        throw new Exception("正しい登録用データではありません");
      }

      if (!$this->trim_utf8($data["member_sei"]) ){
        throw new Exception("氏名(性)は必須です");
      }    

      //まずランダムのmemberIDを作成。バッティングしないかチェック
      $is_check = true;
      $id = null;
      while ($is_check){
        $id = mt_rand(100000000, 999999999);
        $sql = "SELECT *
                FROM M_Member
                where member_id = ?
        ";

        $row = $this->db->select_array($sql,array($id))->row();    

        if (!$row){
          $is_check = false;
        }      
      }      
      
      $num = "";
      if (!$data["member_num"]){
        //空の場合はmax採番
        $sql = "
          SELECT  
            case when max(member_num) is null then 100
            else max(member_num) + 1 end  as max
          FROM M_Member
          where group_id = ?
        ";        
        $row = $this->db->select_array($sql,array($data["group_id"]))->row();
        $num = $row["max"];
        
        
      }else{
        //入っている場合は重複チェック
        $sql = "
          SELECT * 
          FROM M_Member
          where group_id = ?
          and member_num = ?
        ";        
        $row = $this->db->select_array($sql,array($data["group_id"],$data["member_num"]))->row();        
        if (!$row){
          $num = $data["member_num"];
        }else{
          throw new Exception("指定の会員IDは既に使用されています");
        }        
      }
      
      //insert      
      $sql = "
        insert into M_Member
          (member_id,group_id,member_num,member_div,member_sei,member_mei,invalid_flg,insert_date) 
        value
          (?,?,?,?,?,?,0,now()) 
      ";      
      
      
      $div = $data["member_div"];
      if ($data["member_div"] === "false"){$div = "0";}
      else{$div = "1";}
      
      
      
      
      $ary = array($id,$data["group_id"],$num,$div,$data["member_sei"],$data["member_mei"]);

      $this->db->execute($sql,$ary);
      
    }catch(Exception $e){
      throw new Exception("メンバー情報の登録中にエラーが発生しました。".$e->getMessage());
    }

    return true;
  }  
  
	/*******************************************************/				
	/**				
	 * メンバーを新規登録
	 * @name member_insert
   * @param array $data 新規登録用配列
	 * @return true:成功、失敗は例外スロー
	*/				
  public function member_update($data){

    try{
    
      if (!$data || !count($data) || !$data["member_id"]){
        throw new Exception("正しい登録用データではありません");
      }

      if (!$this->trim_utf8($data["member_sei"]) ){
        throw new Exception("氏名(性)は必須です");
      }    
      
      //念のためグループにメンバーが存在しているかチェック
      $sql = "
        SELECT * 
        FROM M_Member
        where group_id = ?
        and member_id = ?
      ";        
      $row = $this->db->select_array($sql,array($data["group_id"],$data["member_id"]))->row();        
      if (!$row){
        throw new Exception("会員情報が正しくありません");
      }
      
      //update
      $sql = "
        update M_Member 
        set
          member_div=?,
          member_sei=?,
          member_mei=?,
          invalid_flg=?,
          update_date=now()
        where member_id = ?
      ";
      $div = $data["member_div"];
      if ($data["member_div"] === "false"){$div = "0";}
      else{$div = "1";}
      
      $invalid = $data["invalid_flg"];
      if ($data["invalid_flg"] === "false"){$invalid = "0";}
      else{$invalid = "1";}      
      
      $ary = array($div,$data["member_sei"],$data["member_mei"],$invalid,$data["member_id"]);

      $this->db->execute($sql,$ary);
      
    }catch(Exception $e){
      throw new Exception("メンバー情報の編集中にエラーが発生しました。".$e->getMessage());
    }

    return true;
  }
  
	/*******************************************************/				
	/**				
	 * コンペを新規登録
	 * @name compe_insert
   * @param array $data 新規登録用配列
	 * @return true:成功、失敗は例外スロー
	*/				
  public function compe_insert($data){
    
    try{
    
      if (!$data || !count($data) || !$data["compe_id"]){
        throw new Exception("正しい登録用データではありません");
      }

      if (!$this->trim_utf8($data["compe_name"]) ){
        throw new Exception("開催名は必須です");
      }    

      //max採番
      $sql = "
        SELECT  
          case when max(compe_id) is null then 1
          else max(compe_id) + 1 end  as max
        FROM T_Compe
      ";        

      $row = $this->db->select_array($sql,array($data["compe_id"]))->row();
      $id = $row["max"];

      //コンペの期間を取得
      $term= $this->_get_compe_term($data["group_id"], $data["compe_date"]);
      
      //insert      
      $sql = "
        insert into T_Compe
          (compe_id,group_id,compe_date,compe_term,compe_name,course,insert_date) 
        value
          (?,?,?,?,?,?,now()) 
      ";      
      
      $ary = array($id,$data["group_id"],$data["compe_date"],$term,$data["compe_name"],$data["course"]);

      $this->db->execute($sql,$ary);
      
      //参加者      
      foreach ($data["member"] as $value) {
        $sql = "
          insert into T_CompeMember
            (compe_id,member_id) 
          value
            (?,?) 
        ";
        $member = str_replace("member_", "", $value);
        $this->db->execute($sql,array($id,$member));
      }
      
    }catch(Exception $e){
      throw new Exception("コンペ情報の登録中にエラーが発生しました。".$e->getMessage());
    }

    return true;
  }  
  
	/*******************************************************/				
	/**				
	 * コンペを編集
	 * @name compe_update
   * @param array $data 登録用配列
	 * @return true:成功、失敗は例外スロー
	*/				
  public function compe_update($data){

    try{
    
      if (!$data || !count($data) || !$data["compe_id"]){
        throw new Exception("正しい登録用データではありません");
      }

      if (!$this->trim_utf8($data["compe_name"]) ){
        throw new Exception("開催名は必須です");
      }    
      
      //コンペの期間を取得
      $term= $this->_get_compe_term($data["group_id"], $data["compe_date"]);      
      
      //update
      $sql = "
        update T_Compe
        set
          compe_date=?,
          compe_term=?,
          compe_name=?,
          course=?,
          weather=?,
          memo=?
        where compe_id = ?
      ";
      
      $ary = array($data["compe_date"],$term,$data["compe_name"],$data["course"],$data["weather"],$data["memo"],$data["compe_id"]);

      $this->db->execute($sql,$ary);
      
      
      //次にメンバー。一旦いまの参加者持ってくる。いま登録されている人にデータが登録済みかもしれないのでdel→insはしない
      $sql = "
        SELECT  
          *
        FROM T_CompeMember
        where compe_id = ?
      ";        

      $nowData = $this->db->select_array($sql,array($data["compe_id"]))->result();
      
      //参加者      
      foreach ($data["member"] as $value) {
        
        $member = str_replace("member_", "", $value);
        $is_exists = false;
        for ($i = 0; $i < count($nowData); $i++) {
          
          if ($nowData[$i]["member_id"] == $member){
            $nowData[$i]["is_exists"] = true;
            $is_exists = true;
            break;
          }
        }
        
        //現在のデータにいなかったらinsert
        if (!$is_exists){
          $sql = "
            insert into T_CompeMember
              (compe_id,member_id) 
            value
              (?,?) 
          ";

          $this->db->execute($sql,array($data["compe_id"],$member));
        }
      }    
      
      //最後に削除された人をdel
      for ($i = 0; $i < count($nowData); $i++) {

        if (!$nowData[$i]["is_exists"]){
          $sql = "
            delete from T_CompeMember
            where compe_id = ?
            and member_id = ?
          ";

          $this->db->execute($sql,array($data["compe_id"],$nowData[$i]["member_id"]));
        }
      }      
      
    }catch(Exception $e){
      throw new Exception("コンペ情報の編集中にエラーが発生しました。".$e->getMessage());
    }

    return true;
  }  
  
	/*******************************************************/				
	/**				
	 * コンペの集計年を計算
	 * @name _get_compe_term
   * @param string $group_id グループID
   * @param string $compe_date 開催日
	 * @return 集計年
	*/				
  private function _get_compe_term($group_id,$compe_date){
    
    //コンペの期間を取得
    $term= date('Y',  strtotime($compe_date)); //1月スタートなど通常はterm=年
    $sql = "
      SELECT  *
      FROM M_Group
      where group_id = ?
    ";
    $row = $this->db->select_array($sql,array($group_id))->row();
    if ($row["month_start"] !== "1"){

      $month = date('n',  strtotime($compe_date));
      //11月スタートより前は1～10月を前の年度に
      if ($row["month_start"] < 11){
        if ($month < $row["month_start"]){
          $term--;
        }          
      }else{
        //11月スタートより後は11～12月を次の年度に
        if ($month >= $row["month_start"]){
          $term++;
        }          
      }
    }
    return $term;

  }  
  
	/*******************************************************/				
	/**				
	 * ハンデのみを編集
	 * @name hande_update
   * @param array $data 登録用配列
	 * @return true:成功、失敗は例外スロー
	*/				
  public function hande_update($data){

    try{
    
      if (!$data || !count($data) || !$data["compe_id"]){
        throw new Exception("正しい登録用データではありません");
      }
      
      $ary =  $data["data"];
      
      foreach ($ary as $row) {
        
        foreach ($row as $key => $value) {
          if (!$row[$key]){$row[$key] = null;}
        }
        
        $sql = "
          update T_CompeMember
          set
            handicap=?
          where compe_id = ?
          and member_id = ?
        ";
        
        $ary = array($row["handicap"],$data["compe_id"],$row["id"]);

        $this->db->execute($sql,$ary);        
      }

    }catch(Exception $e){
      throw new Exception("ハンデ情報の編集中にエラーが発生しました。".$e->getMessage());
    }

    return true;
  }    
  
	/*******************************************************/				
	/**				
	 * グロスのみを編集
	 * @name gross_update
   * @param array $data 登録用配列
	 * @return true:成功、失敗は例外スロー
	*/				
  public function gross_update($data){

    try{
    
      if (!$data || !count($data) || !$data["compe_id"]){
        throw new Exception("正しい登録用データではありません");
      }
      
      $ary =  $data["data"];
      
      foreach ($ary as $row) {
        
        foreach ($row as $key => $value) {
          if (!$row[$key]){$row[$key] = null;}
        }
        
        
        $g = $row["gross"];
        $h = $row["handicap"];
        
        //念のため値なしなら0を入れておく
        if (!$g) {
          $g = 0;
        }
        if (!$h) {
          $h = 0;
        }
        
        $n = $g - $h;
        
        
        
        
        $sql = "
          update T_CompeMember
          set
            gross=?,
            net=?
          where compe_id = ?
          and member_id = ?
        ";
        
        $ary = array($g,$n,$data["compe_id"],$row["id"]);

        $this->db->execute($sql,$ary);        
      }

    }catch(Exception $e){
      throw new Exception("グロス情報の編集中にエラーが発生しました。".$e->getMessage());
    }

    return true;
  }      
  
	/*******************************************************/				
	/**				
	 * コンペ完了登録を編集
	 * @name compe_finish
   * @param array $data 登録用配列
	 * @return true:成功、失敗は例外スロー
	*/				
  public function compe_finish($data){
    
    try{
    
      if (!$data || !count($data) || !$data["compe_id"]){
        throw new Exception("正しい登録用データではありません");
      }
      
      if ($data["mode"] == "finish"){
        

        //finishで順位確定
        $member_data = $this->get_compe_member($data["compe_id"]);
        for ($i = 0; $i < count($member_data); $i++) {
          if (!$member_data[$i]["rank"]){
            $member_data[$i]["rank"] = $member_data[$i]["dummy_rank"];
          }
          
          $sql = "
            update T_CompeMember
            set
              rank=?
            where compe_id = ?
            and member_id = ?
          ";

          $ary = array($member_data[$i]["rank"],$data["compe_id"],$member_data[$i]["member_id"]);
          $this->db->execute($sql,$ary);
        }

        $sql = "
          update T_Compe
          set
            finish_flg=1,
            finish_date=now()
          where compe_id = ?
        ";        
        $this->db->execute($sql,array($data["compe_id"]));        
        
      }elseif ($data["mode"] == "cancel"){
        //update
        $sql = "
          update T_Compe
          set
            finish_flg=0,
            finish_date=null
          where compe_id = ?
        ";                
        $this->db->execute($sql,array($data["compe_id"]));
      }elseif ($data["mode"] == "delete"){
        
        //delete
        $sql = "
          delete from T_Compe
          where compe_id = ?
        ";                
        $this->db->execute($sql,array($data["compe_id"]));        
        
        //delete
        $sql = "
          delete from T_CompeMember
          where compe_id = ?
        ";                
        $this->db->execute($sql,array($data["compe_id"]));                
        
      }

    }catch(Exception $e){
      throw new Exception("コンペ情報の編集中にエラーが発生しました。".$e->getMessage());
    }

    return true;
  }    
  
	/*******************************************************/				
	/**				
	 * コンペ当日情報を編集
	 * @name compe_update_today
   * @param array $data 登録用配列
	 * @return true:成功、失敗は例外スロー
	*/				
  public function compe_update_today($data,$file_name){

    try{
    
      if (!$data || !count($data) || !$data["compe_id"]){
        throw new Exception("正しい登録用データではありません");
      }
      
      
      $path_str ="";
      foreach ($file_name as $key => $value) {
        $path_str.= $key."='".$value."',";
      }
      
      //update
      $sql = "
        update T_Compe
        set ".$path_str ;
      
      $sql.= "
          weather=?,
          memo=?
        where compe_id = ?
      ";
      
      $ary = array($data["weather"],$data["memo"],$data["compe_id"]);

      $this->db->execute($sql,$ary);
      
      //コントローラ側で不要な画像ファイルを削除するから最新情報を戻す
      $sql = "
        select * from T_Compe
        where compe_id = ?
        ";
      
      return $this->db->select_array($sql,array($data["compe_id"]))->row();

    }catch(Exception $e){
      throw new Exception("コンペ情報の編集中にエラーが発生しました。".$e->getMessage());
    }

    return false;
  }    
  
	/*******************************************************/				
	/**				
	 * スコアを登録
	 * @name score_update
   * @param array $data 登録用配列
	 * @return true:成功、失敗は例外スロー
	*/				
  public function score_update($data){

    try{
    
      if (!$data || !count($data) || !$data["compe_id"]){
        throw new Exception("正しい登録用データではありません");
      }
      
      $ary =  $data["data"];
      
      foreach ($ary as $row) {
        
        foreach ($row as $key => $value) {
          if (!$row[$key]){$row[$key] = null;}
        }
        
        if ($row["viewmode"]){
          $sql = "
            update T_CompeMember
            set
              rank=?,          
              gross=?,
              handicap=?,
              net=?,
              fee=?,
              penalty=?,
              gift=?,
              memo=?
            where compe_id = ?
            and member_id = ?
          ";

          $ary = array($row["rank"],$row["gross"],$row["handicap"],$row["net"],$row["fee"],$row["penalty"],$row["gift"],$row["memo"],$data["compe_id"],$row["id"]);          
        }else{
          $sql = "
            update T_CompeMember
            set
              rank=?,          
              gross=?,
              handicap=?,
              net=?
            where compe_id = ?
            and member_id = ?
          ";

          $ary = array($row["rank"],$row["gross"],$row["handicap"],$row["net"],$data["compe_id"],$row["id"]);
        }


        $this->db->execute($sql,$ary);        
      }

      

      

    }catch(Exception $e){
      throw new Exception("コンペ情報の編集中にエラーが発生しました。".$e->getMessage());
    }

    return true;
  }      
  
	/*******************************************************/				
	/**				
	 * コンペ参加者を削除
	 * @name compe_member_del
   * @param array $data 登録用配列
	 * @return true:成功、失敗は例外スロー
	*/				
  public function compe_member_del($data){

    try{
    
      if (!$data || !count($data) || !$data["compe_id"]){
        throw new Exception("正しい登録用データではありません");
      }

      $sql = "
        delete from T_CompeMember
        where compe_id = ?
        and member_id = ?
      ";

      $this->db->execute($sql,array($data["compe_id"],$data["member_id"]));


    }catch(Exception $e){
      throw new Exception("コンペ参加者削除処理中にエラーが発生しました。".$e->getMessage());
    }

    return true;
  }      
  
	/*******************************************************/				
	/**				
	 * 検索、集計条件を取得
	 * @name get_element_cond
   * @param string $type 種類1:集計方法、2:除外条件、3:指定条件
   * @param string $retmode 戻りの形 未指定:集計方法、1:textarea用文字、2:sql用and条件文字
	 * @return $retmodeにより変動
	*/				
  public function get_element_cond($type,$retmode=null){
    $sql = "SELECT *
						FROM mELEMENT_COND
						where cond_type = ?
						order by cond_idx
		";
    
    $ary = $this->db->select_array($sql,array($type))->result();
    
    if ($type == "1"){
      //type=1 集計方法
      if (count($ary)){
        return $ary[0]["cond_value"];
      }else{
        return "";
      }
      
    }else{
      $str = "";
      if ($retmode === "1"){
        foreach ($ary as $row) {
          if ($row["disp_name"]){
            $str.= $row["cond_value"]."@".$row["disp_name"]."\n";
          }else{
            $str.= $row["cond_value"]."\n";
          }
        }
        return $str;
      }if ($retmode === "2"){
        if (!count($ary)){
          return "";
        }else{
          if ($type == "2"){
            foreach ($ary as $row) {
              $str.= " and context not like '".$row["cond_value"]."%' \n";
            }            
          }else{
            $str.= " and (\n";
            for ($i = 0; $i < count($ary); $i++) {
              $str.= " context = '".$ary[$i]["cond_value"]."' \n";
              if ((count($ary)-1) !== $i){
                $str.= " or\n";
              }
            }
            $str.= " )\n";
          }
        }
        return $str;      
      }
    }
  }    
  
	/*******************************************************/				
	/**				
	 * 検索、集計条件を登録
	 * @name edit_element_cond
   * @param string $type 種類1:集計方法、2:除外条件、3:指定条件
   * @param string $text textareaで設定された文字列
	 * @return 成功:true、失敗:例外発生
	*/				
  public function edit_element_cond($type,$text){
    
    try{
      //del→ins
      $sql = "
        delete FROM mELEMENT_COND
        where cond_type = ?
      ";
      $this->db->execute($sql, array($type));      
      
      if ($type == "1"){
      $sql = "insert into mELEMENT_COND(cond_type,cond_idx,cond_value,disp_name) VALUES ";
      $sql.= "(1,1,'".$text."','')";

      $this->db->execute($sql);              
        
      }else{
        $ary = explode("\n", $text); // とりあえず行に分割
        $ary = array_map('trim', $ary); // 各行にtrim()をかける
        $ary = array_filter($ary, 'strlen'); // 文字数が0の行を取り除く
        $ary = array_values($ary); // これはキーを連番に振りなおしてるだけ            
        
        if (count($ary)){
          $sql = "insert into mELEMENT_COND(cond_type,cond_idx,cond_value,disp_name) VALUES ";
          for ($i = 0; $i < count($ary); $i++) {
            
            $val = "";
            $name = "";
            if (strpos($ary[$i],"@") !== false){
              $item = explode("@", $ary[$i]);
              $val = $item[0];
              $name = $item[1];
            }else{
              $val = $ary[$i];
            }
            
            $sql.= "(".$type.",".($i+1).",'".$val."','".$name."'),";
          }
          $sql = rtrim($sql,",");

          $this->db->execute($sql);
        }        
      }
      
    }catch(Exception $e){

      throw new Exception("登録中にエラーが発生しました。".$e->getMessage());
    }
    
    return true;
  } 		
}	

?>