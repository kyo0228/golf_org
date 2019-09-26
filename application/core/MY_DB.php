<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*******************************************************/
/**
 * 共通データベース処理クラス。
 * アプリケーションのDB関連共通関数はこのファイル。
 * コントローラ毎の関数はxxDaoでこのクラスを継承させる。
 * @name MY_DB (core/MY_DB.php)
*/
class MY_DB extends GC_DB {
		
	/*******************************************************/
	/**
	 * コンストラクタ
	*/
	public function __construct() {
		parent::__construct();		
	}
	
	/*******************************************************/				
	/**				
	 * グループIDを取得
	 * @name get_group_id
   * @param string $code グループコード
	 * @return 成功:値あり、失敗：値なし
	*/				
  public function get_group_id($code){
    
    $sql = "select * from M_Group where group_code = ?";
    return $this->db->select_array($sql,array($code))->row();
  }
  
	/*******************************************************/				
	/**				
	 * コンペの参加者を取得
	 * @name get_compe_member
   * @param string $compeid コンペID
	 * @return 成功:一覧配列(完了していたら順位順、未完了なら会員番号順)
	*/				
  public function get_compe_member($compeid){
    
    //finish_flgが欲しいから先にselect
    $sql = "
            select * 
            from T_Compe
            where compe_id = ?
            ";    
      
    $row = $this->db->select_array($sql,array($compeid))->row();
    
    $sql = "
            select 
              A.rank, 
              A.compe_id, 
              A.member_id, 
              A.gross,
              CASE 
                WHEN MOD(A.handicap, 1) = 0 THEN TRUNCATE(A.handicap, 0)
                WHEN MOD(A.handicap, 0.1) = 0 THEN TRUNCATE(A.handicap, 1)
                ELSE A.handicap
              END AS handicap,
              CASE 
                WHEN MOD(A.net, 1) = 0 THEN TRUNCATE(A.net, 0)
                WHEN MOD(A.net, 0.1) = 0 THEN TRUNCATE(A.net, 1)
                ELSE A.net
              END AS net,
              A.fee,
              A.penalty,
              A.gift,
              A.memo,
              concat(B.member_sei,' ',B.member_mei) as member_name,
              B.member_num,B.image_path
            from T_CompeMember as A
            left outer join M_Member as B
            on A.member_id = B.member_id
            where A.compe_id = ? 
            ";    
    
    if ($row["finish_flg"]){
      $sql.= "order by rank";
    }else{
      $sql.= "order by case when A.net = 0 then 9999 else A.net end ,A.gross,B.member_num";
    }
    

    //ハンデとネットの少数以下0が取れない。。loopでとるか
    $data = $this->db->select_array($sql,array($compeid))->result();
    
    for ($i = 0; $i < count($data); $i++) {
      $aa = $data[$i];
      
      $h = explode(".", $data[$i]["handicap"]);
      
      if(count($h)===2){
      if ($h[1] == "00"){
        $data[$i]["handicap"] = $h[0];
      }else{
        $data[$i]["handicap"] = $h[0].".".rtrim($h[1], "0");
      }        
      }

      
      $n = explode(".", $data[$i]["net"]);
      
      if(count($n)===2){
      if ($n[1] == "00"){
        $data[$i]["net"] = $n[0];
      }else{
        $data[$i]["net"] = $n[0].".".rtrim($n[1], "0");
      }      
      }

      
      if (!$data[$i]["net"]){
        $data[$i]["net"] = 0;
      }
      
      $data[$i]["dummy_rank"] = $i+1;
    }
    
    return $data;
      
  }        
}	

?>