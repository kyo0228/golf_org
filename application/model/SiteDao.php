<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*******************************************************/
/**
 * Site用のdao
 * @name SiteDao (core/MY_DB.php)
*/
class SiteDao extends MY_DB {
	protected $my_var="b";
	
	protected $login_var="1";
	
	/*******************************************************/
	/**
	 * コンストラクタ
	*/
	public function __construct() {
		parent::__construct();
	}
	
	/*******************************************************/				
	/**				
	 * 有報データを登録
	 * @name insert_ufo_data
   * @param string $ufoid 有報ID
   * @param string $sqlroot $tUFOのinsert文
   * @param string $sqldata $tUFO_DATAのinsert文
	 * @return true:成功、失敗はfalse
	*/				
  public function insert_ufo_data($ufoid,$sqlroot,$sqldata){
    if ($sqldata){
      $this->delete_ufo_data($ufoid);
      
      if ($this->db->execute($sqldata)){
        $this->db->execute($sqlroot);
      }
    }    
  }
  
	/*******************************************************/				
	/**				
	 * 有報データを削除
	 * @name delete_ufo_data
   * @param string $ufoid 有報ID
	 * @return true:成功
	*/				
  public function delete_ufo_data($ufoid){
    $del = "delete from tUFO where ufo_id = '".$ufoid."'";
    $this->db->execute($del);      

    $del = "delete from tUFO_DATA where ufo_id = '".$ufoid."'";
    $this->db->execute($del);
    return true;
  }  
  
	/*******************************************************/				
	/**				
	 * 有報データの集計フラグを更新
	 * @name update_ufo_syukei
   * @param string $ufoid 有報ID
	 * @return true:成功
	*/				
  public function update_ufo_syukei($ufoid,$flg){
    $sql = "update tUFO set syukei = ? where ufo_id = ?";
    $this->db->execute($sql,array($flg,$ufoid));
    
    return true;
  }    
  
	/*******************************************************/				
	/**				
	 * 有報IDから有報親レコード1件を取得
	 * @name get_ufo_info
   * @param string $ufo_id 有報ID
	 * @return 配列
	*/				
  public function get_ufo($ufo_id){
    $sql = "select * from tUFO where ufo_id = '".$ufo_id."'";
    return $this->db->select_array($sql)->row();
  }  
  
	/*******************************************************/				
	/**				
	 * 証券コードから有報親レコード全件を取得
	 * @name get_ufo_info
   * @param string $code 証券コード
	 * @return 配列
	*/				
  public function get_ufo_info($code){
    $sql = "select * from tUFO where s_code = '".$code."'";
    return $this->db->select_array($sql)->result();
  }    
  
	/*******************************************************/				
	/**				
	 * 有報親レコード全件を取得
	 * @name get_ufo_list
	 * @return 配列
	*/				
  public function get_ufo_list(){
    $sql = "select * from tUFO order by corp,ufo_date desc";
    return $this->db->select_array($sql)->result();
  }    
  
	/*******************************************************/				
	/**				
	 * 有報IDから有報子レコード全件を取得
	 * @name get_ufo_detail
   * @param string $ufo_id 有報ID
   * @param string $is_elm_jp 1:科目表示名なしを除く
   * @param string $is_ctxt_jp 1:コンテキスト表示名なしを除く
	 * @return 配列
	*/				
  public function get_ufo_detail($ufo_id,$is_elm_jp,$is_ctxt_jp){
    $sql = "
      SELECT 
        A.*,
        case when B.elm_jp_name is null then '-' else B.elm_jp_name end as elm_jp_name,
        case when C.disp_name is null then '-' else C.disp_name end as ctxt_jp_name 
      FROM tUFO_DATA as A
      left outer join mELEMENT as B
      on A.elm_name = B.elm_name
      left outer join mELEMENT_COND as C
      on A.context = C.cond_value and C.cond_type=3      
      where ufo_id = '".$ufo_id."'";
    
    if ($is_elm_jp){
      $sql.= " and B.elm_jp_name is not null ";
    }
    if ($is_ctxt_jp){
      $sql.= " and C.disp_name is not null ";
    }    
      
    $sql.= "order by idx";

    return $this->db->select_array($sql)->result();
  }      
  
  
	/*******************************************************/				
	/**				
	 * 複数の有価証券情報を串刺し集計
	 * @name get_ufo_analyzer
	 * @return 配列
	*/				
  public function get_ufo_analyzer(){
    
    //横軸用項目名
    $sql = "
      SELECT
        concat(B.elm_jp_name,'-',C.disp_name )as disp_name 
      FROM (
        select * from tUFO_DATA
        WHERE elm_name in (
          SELECT elm_name  FROM mELEMENT WHERE enable_flg = 1
        )
      ) as A
      inner join mELEMENT as B
      on A.elm_name = B.elm_name
      inner join mELEMENT_COND as C
      on A.context = C.cond_value and C.cond_type=3      
      inner join tUFO as D
      on A.ufo_id = D.ufo_id and D.syukei=1
      group by 
        B.elm_jp_name,
        C.disp_name
      order by 
        B.elm_jp_name,
        C.cond_idx

    ";
    
    $aryYoko = $this->db->select_array($sql)->result();
    
    //横軸の項目名を取得
    $col = "";
    foreach ($aryYoko as $row) {
      $col.= " 0 as '".$row["disp_name"]."',";
    }
    $col=  rtrim($col,',');
    
    
    //縦軸先頭カラム
    $sql = "
      SELECT
        A.ufo_id as 有報ID,
        D.s_code as 證券コード,
        case when D.access = 'edinetx' then '有報' else '短信' end as 取得元 ,
        D.c_code as 企業コード,
        D.corp as 企業名,
        D.title as タイトル,
        D.ufo_date as 登録日,        
      ";
    $sql.= $col;
    
    $sql.= "
      FROM (
        select * from tUFO_DATA
        WHERE elm_name in (
          SELECT elm_name  FROM mELEMENT WHERE enable_flg = 1
        )
      ) as A
      inner join mELEMENT as B
      on A.elm_name = B.elm_name
      inner join mELEMENT_COND as C
      on A.context = C.cond_value and C.cond_type=3
      inner join tUFO as D
      on A.ufo_id = D.ufo_id and D.syukei=1
      group by 
        A.ufo_id,
        D.s_code,
        D.access,
        D.c_code,
        D.corp,
        D.title,
        D.ufo_date
      order by 
        D.corp,
        D.ufo_date desc

    ";    
    
    $aryTotal = $this->db->select_array($sql)->result();
    
    
    //全項目取得
    $sql = "
      SELECT
        A.*,
        B.elm_jp_name as elm_jp_name,
        C.disp_name as ctxt_jp_name 
      FROM (
        select * from tUFO_DATA
        WHERE elm_name in (
          SELECT elm_name  FROM mELEMENT WHERE enable_flg = 1
        )
      ) as A
      inner join mELEMENT as B
      on A.elm_name = B.elm_name
      inner join mELEMENT_COND as C
      on A.context = C.cond_value and C.cond_type=3      
      inner join tUFO as D
      on A.ufo_id = D.ufo_id and D.syukei=1
    ";    
    
    $aryData = $this->db->select_array($sql)->result();    
    
    foreach ($aryData as $row) {
      
      for ($i = 0; $i < count($aryTotal); $i++) {
        if ($aryTotal[$i]["有報ID"] === $row["ufo_id"]){
          $colname = $row["elm_jp_name"]."-".$row["ctxt_jp_name"];
          
          $aryTotal[$i][$colname] = $row["amount"];
          break;
        }
      }
      
    }
    

    return $aryTotal;
  }        
  
		
}	

?>