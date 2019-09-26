<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*******************************************************/
/**
 * Main用のdao
 * @name MainDao (core/MY_DB.php)
*/
class ScoreDao extends MY_DB {
	
	/*******************************************************/
	/**
	 * コンストラクタ
	*/
	public function __construct() {
		parent::__construct();
	}

	/*******************************************************/				
	/**				
	 * 会員IDとグループIDからメンバー情報を取得
	 * @name get_member
   * @param string $id メンバーID
   * @param string $groupid グループID
	 * @return 成功:1レコード配列
	*/				
  public function get_member($id,$groupid){
    //一応ねんのためIDとgroupが一致したレコードを戻す
    $sql = "select * from M_Member where member_id = ? and group_id = ?";
      
    return $this->db->select_array($sql,array($id,$groupid))->row();
      
  }
  
	/*******************************************************/				
	/**				
	 * グループIDからメンバー一覧を取得
	 * @name get_memberlist
   * @param string $groupid グループID
   * @param bool $is_all true:グループ全員、false:無効除く全員
	 * @return 成功:一覧配列
	*/				
  public function get_memberlist($groupid,$is_all=true){
    $sql = "select * from M_Member where group_id = ?  ";    
    if (!$is_all){
      $sql.= "and invalid_flg = 0 ";
    }
    
    $sql.= "order by member_num";
      
    return $this->db->select_array($sql,array($groupid))->result();
      
  }  
  
	/*******************************************************/				
	/**				
	 * 完了している最新のコンペを取得
	 * @name get_new_compe
	 * @return 成功:1レコード配列
	*/				
  public function get_new_compe($groupid){
    
    $sql = "
            select * from T_Compe 
            where group_id = ?
            and finish_flg = 1
            order by compe_date desc
           ";
      
    return $this->db->select_array($sql,array($groupid))->row();
      
  }  
  
	/*******************************************************/				
	/**				
	 * コンペ情報を取得
	 * @name get_compe
   * @param string $compeid コンペID
   * @param string $groupid グループID
	 * @return 成功:一致した配列
	*/				
  public function get_compe($compeid,$groupid){
    $sql = "
            select 
              A.*,B.cnt,
              C.view_mode
            from T_Compe as A
            left outer join (
              select compe_id, count(*) as cnt from T_CompeMember
              group by compe_id
            ) as B
            on A.compe_id = B.compe_id
            left outer join M_Group as C
            on A.group_id = C.group_id
            where A.group_id = ?
            and A.compe_id = ?            
            order by compe_date desc
          ";
      
    return $this->db->select_array($sql,array($groupid,$compeid))->row();
  }    
  
	/*******************************************************/				
	/**				
	 * コンペの集計期間を取得
	 * @name get_compe_term
   * @param string $groupid グループID
	 * @return 成功:一致した配列
	*/				
  public function get_compe_term($groupid){

    $sql = "
            select
              A.compe_term,
              B.month_start,
              B.month_end
            from (
                        SELECT compe_term
                        FROM  T_Compe
                        WHERE group_id =?
                        GROUP BY group_id
            ) as A
            cross join M_Group as B
            where B.group_id = ?
            ORDER BY A.compe_term desc
          ";
      
    $compe_term = $this->db->select_array($sql,array($groupid,$groupid))->result();

    if (count($compe_term)){
      for ($i= 0; $i < count($compe_term); $i++) {
        $compe_term[$i]["display"] = $compe_term[$i]["compe_term"]."年度(".$compe_term[$i]["month_start"]."～".$compe_term[$i]["month_end"]."月)";
      }      
    }  

    return $compe_term;
  }      
  
	/*******************************************************/				
	/**				
	 * コンペ一覧を取得
	 * @name get_compelist
   * @param string $groupid グループID
	 * @return 成功:一覧配列
	*/				
  public function get_compelist($groupid){
    $sql = "
            select 
              A.*,B.cnt
            from T_Compe as A
            left outer join (
              select compe_id, count(*) as cnt from T_CompeMember
              group by compe_id
            ) as B
            on A.compe_id = B.compe_id
            where A.group_id = ?
            order by compe_date desc
          ";
      
    return $this->db->select_array($sql,array($groupid))->result();
      
  }      

	/*******************************************************/				
	/**				
	 * コンペ編集画面用の参加者を取得
	 * @name get_compe_member_edit
   * @param string $groupid グループID
   * @param string $compeid コンペID　新規の時はnew
	 * @return 成功:一覧配列
	*/				
  public function get_compe_member_edit($groupid,$compeid){
    
    if ($compeid === "new"){
      $sql = "
        select 
          member_id,
          member_num,
          concat(member_sei,' ',member_mei) as member_name,
          1 as is_check
        from M_Member 
        where group_id = ?
        and invalid_flg = 0
        order by member_num
        ";    
      return $this->db->select_array($sql,array($groupid))->result();
    }else{
      $sql = "
        select 
          A.member_id,
          A.member_num,
          concat(A.member_sei,' ',A.member_mei) as member_name,
          case when B.member_id is null then 0 else 1 end as is_check
        from M_Member as A
        left outer join (
          select * from T_CompeMember
          where compe_id = ?
        ) as B
        on A.member_id = B.member_id
        where A.group_id = ?
        order by invalid_flg,A.member_num
        ";
      return $this->db->select_array($sql,array($compeid,$groupid))->result();
    }
      
  }      
  
  
	/*******************************************************/				
	/**				
	 * 集計　期間平均
	 * @name get_total
   * @param string $groupid グループID
   * @param string $sort 並び順
   * @param string $term 集計期間
   * @param string $memberid メンバーID
	 * @return 成功:一覧配列
	*/				
  public function get_total($groupid,$sort,$term,$memberid=""){
    
    $sql = "
        select 
         B.member_num,
         concat(B.member_sei,' ',B.member_mei) as member_name,
         A.*
        from (
            SELECT
             member_id,
             count(*) as round,
             round(AVG(gross),2) as gross,
             round(AVG(net),2) as net,
             round(max(gross),2) as gross_max,
             round(max(net),2) as net_max,
             round(min(gross),2) as gross_min,
             round(min(net),2) as net_min           
            FROM T_CompeMember as A
            inner join T_Compe as B
            on A.compe_id = B.compe_id
            where B.group_id = ?
    ";
    
    $param = array($groupid);
    if ($term !== 'all'){
      $sql.= " and B.compe_term = ? ";
      $param = array($groupid,$term);
    }
    
    if ($memberid){
      $sql.= " and A.member_id = ? ";
      $param[] = $memberid;      
    }
    
    $sql.= "                 
            and A.net > 0
            and B.finish_flg = 1
            GROUP by member_id
        ) as A
        inner join M_Member as B
        on A.member_id = B.member_id
    ";
    if ($sort === 'num'){
      $sql.= " order by B.member_num ";
    }elseif ($sort === 'grs'){ 
      $sql.= " order by A.gross,A.net ";
    }else{
      $sql.= " order by A.net,A.gross ";
    }

    return $this->db->select_array($sql,$param)->result();
      
  }  
  
	/*******************************************************/				
	/**				
	 * 集計　個人
	 * @name get_total_year
   * @param string $memberid メンバーID
   * @param string $term 集計期間
	 * @return 成功:一覧配列
	*/				
  public function get_total_member($memberid,$term){
    
    $sql = "
            SELECT * 
            FROM T_CompeMember AS A
            INNER JOIN T_Compe AS B ON A.compe_id = B.compe_id
            WHERE A.member_id = ?
           ";
    
    $param = array($memberid);
    if ($term !== 'all'){
      $sql.= " and B.compe_term = ? ";
      $param[] = $term;
    }
    
    $sql.= "
            AND A.net > 0
            AND B.finish_flg =1
            ORDER BY B.compe_date DESC             
          ";
    
    return $this->db->select_array($sql,$param)->result();
  }  
  
}	

?>