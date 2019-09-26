<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*******************************************************/
/**
 * 共通データベース処理クラス。このクラスはsingletonで実装。
 * 継承させるとアプリ側の関数やインスタンスが大量に入ってくる可能性があるので継承不可とする
 * @name GC_DB_Connection (core/GC_DB_Connection.php)
*/
final class GC_DB_Connection extends GC_Abstract_Sgtn {

	/**
	 * 接続情報保持配列
	 * @var array
	 */		
	protected $_connection_conf=array();
	
	/**
	 * 接続オブジェクト保持配列
	 * @var array
	 */			
	protected $_connection_obj=array();
	
	/*******************************************************/
	/**
	 * コンストラクタ。privateにして外からnewさせない
	*/
	protected function __construct() {
	}
		
	/*******************************************************/
	/**
	 * コンフィグよりDB接続情報を取得
	 * @name _load_db_conf
	 * @return 接続情報の配列がない場合は例外発生
	 * 
	*/	
	private function _load_db_conf() {
		
		if (count(GC_Static::db()) === 0){
			throw new Exception('データベースの接続設定が定義されていません。' );
		}		
		
		$this->_connection_conf = array();
		foreach (GC_Static::db() as $cn_name => $ary) {
			$this->_connection_conf[$cn_name] = $ary;
		}
	}
			
	
	/*******************************************************/
	/**
	 * 設定ファイルのDB接続情報からDBに接続できるか確認。
	 * @name check_connection
	 * @return true:接続成功、接続できない場合は例外エラー発生
	 * 
	*/	
	public function check_connection() {
		
		if (count($this->_connection_conf) === 0){
			$this->_load_db_conf();
		}
		
		if (count($this->_connection_obj) > 0){return true;}
		
		/*
		'dsn'	=> '',
		'hostname' => 'localhost',
		'username' => 'root',
		'password' => 'megaotowa',
		'database' => 'cblink',
		'dbdriver' => 'mysql',
		'dbprefix' => '',
		'char_set' => 'utf8',
		'dbcollat' => 'utf8_general_ci'	

		 * 
		 */		
		
		$obj_ary = array();
		foreach ($this->_connection_conf as $cn_name => $db_conf) {

			$obj=null;
			if ($db_conf["dbdriver"] == "pdo"){
				try {
					$dsn = 'mysql:host='.$db_conf["hostname"].';dbname='.$db_conf["database"].';charset='.$db_conf["char_set"].';';
					$user = $db_conf["username"];
					$password = $db_conf["password"];			
					$option = array(
							PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
							PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
							PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
							PDO::ATTR_EMULATE_PREPARES => false,
							PDO::ATTR_STRINGIFY_FETCHES => false
					);				

					$obj = new PDO($dsn,$user,$password,$option);
				} catch (PDOException $e) {
					throw new Exception("データベースに接続できません。[pdo DatabaseName=".$db_conf["database"]."]");
				}		
			}else if ($db_conf["dbdriver"] == "mysqli"){

				$driver = new mysqli_driver();
				$driver->report_mode = MYSQLI_REPORT_STRICT;	//MYSQLI_REPORT_STRICTで例外発生。キャッチして別メッセージ表示

				$host = $db_conf["hostname"];
				$user = $db_conf["username"];
				$password = $db_conf["password"];
				$dbname = $db_conf["database"];
				
				try{
					
					$obj = new mysqli($host, $user, $password, $dbname);
					
					if (mysqli_connect_errno()) {
								throw new Exception("mysqli not connected");
					}					
					
				}catch(Exception $e){
					throw new Exception("データベースに接続できません。[mysqli DatabaseName=".$db_conf["database"]."]");
				}
				$obj->set_charset($db_conf["char_set"]);
				
			}else{

				throw new Exception($db_conf["database"]." はまだ当フレームワークに対応していません");
			}
			
			$obj_ary[$cn_name] = $obj;
		}
		
		//エラーなく全部のDBが接続できたら共通変数にセット
		$this->_connection_obj = $obj_ary;
		return true; 
	}	
	
	/*******************************************************/
	/**
	 * データベースのコネクションオブジェクトを取得します。
	 * @name get_connection
	 * @param string $cn_name db接続情報名
	 * @return ドライバに対応したコネクションオブジェクト、接続できない場合は例外エラー発生
	 * 
	*/	
	public function &get_connection($cn_name="default") {
		
		if (!$this->_connection_obj[$cn_name]){
			throw new Exception("データベース接続が初期化されていません。先に接続処理を行ってください");
		}
		
		return $this->_connection_obj[$cn_name]; 
	}		
	
	
	/*******************************************************/
	/**
	 * select文を実行し結果をクラスで戻します。
	 * @name select
	 * @param string $sql 実行するクエリ
	 * @param array $aryParams バインド用の配列(連想配列でもOK)
	 * @param string $cn_name DB接続情報名(default以外を使う場合に名前を指定する)
	 * @return GC_DB_Resultクラス(オブジェクトで戻る)
	 * 
	*/	
	public function select($sql,$aryParams=array(),$cn_name="default") {
		
		return $this->_query(true, $sql, $aryParams,$cn_name, true);
	}
	
	/*******************************************************/
	/**
	 * select文を実行し結果を配列で戻します。
	 * @name select_array
	 * @param string $sql 実行するクエリ
	 * @param array $aryParams バインド用の配列(連想配列でもOK)
	 * @param string $cn_name DB接続情報名(default以外を使う場合に名前を指定する)
	 * @return GC_DB_Resultクラス(連想配列で戻る)
	 * 
	*/	
	public function select_array($sql,$aryParams=array(),$cn_name="default") {
		
		return $this->_query(true, $sql, $aryParams,$cn_name, false);
	}	
	
	/*******************************************************/
	/**
	 * insert,update,deleteなどを実行します。
	 * @name execute
	 * @param string $sql 実行するクエリ
	 * @param array $aryParams バインド用の配列(連想配列でもOK)
	 * @param string $cn_name DB接続情報名(default以外を使う場合に名前を指定する)
	 * @return true:成功、失敗の場合は例外発生
	 * 
	*/	
	public function execute($sql,$aryParams=array(),$cn_name="default") {
		
		return $this->_query(false, $sql, $aryParams,$cn_name);
	}	
	
	/*******************************************************/
	/**
	 * クエリを実行します。
	 * @name _query
	 * @param bool $isSelect trueの場合はselect文、falseは編集系
	 * @param string $sql 実行するクエリ
	 * @param array $aryParams バインド用の配列(連想配列でもOK)
	 * @param string $cn_name DB接続情報名(default以外を使う場合に名前を指定する)
	 * @param bool $isReturnObj trueの場合、selectの戻りがクラス。false:selectの戻りが連想配列
	 * @return selectの場合はGC_DB_Resultクラス。更新系はtrue or 例外発生
	 * 
	*/	
 protected function _query($isSelect ,$sql,$aryParams=array(),$cn_name="default",$isReturnObj= true) {
		$cn = &$this->get_connection($cn_name);
		
		$result = false;
				
		if ($this->_connection_conf[$cn_name]["dbdriver"] === "pdo"){
			$stmt = $cn->prepare(trim($sql));
			if ($stmt->execute($aryParams)){
				if ($isSelect){
					$result = new GC_DB_Result();
					if ($isReturnObj){
						$result->set_object($stmt->fetchAll(PDO::FETCH_OBJ));
					}else{
						$result->set_array($stmt->fetchAll(PDO::FETCH_ASSOC));
					}										
				}else{
					$result = true;
				}
			}
			
			$stmt->closeCursor();
			
		}elseif ($this->_connection_conf[$cn_name]["dbdriver"] === "mysqli"){

			//mysqliのバインドは使い辛いので?の数だけ置き換える
			if (substr_count($sql,"?") > 0){
				if (substr_count($sql,"?") !== count($aryParams)){
					throw new Exception("mysqliのバインド変数の数が一致しません。処理を確認してください。");
				}
				
				foreach ($aryParams as $key => $value) {
					//nullのみ判定、その他はmysqlの自動型変換に任せてしまう
					if (is_null($value)){
						$escape_val = "null";
					}else{
						$escape_val = "'".$cn->real_escape_string($value)."'";
					}
					
					$start = strpos($sql,"?");
					if ($start !== false){
						$sql = substr_replace($sql, $escape_val, $start, 1);
					}
				}				
			}
			
			$result_mysqli = $cn->query($sql);
			
			if ($result_mysqli === false) {
				throw new Exception("エラーが発生しました。構文をチェックしてください。");
			}elseif ($result_mysqli === true) {
				//insert,update,deleteのはず
				$result = true;
			}else{
				$result = new GC_DB_Result();
				$result_data = array();
				if ($isReturnObj){
					// オブジェクトを取得
					while ($row = $result_mysqli->fetch_object()) {
						$result_data[] = $row;
					}
					$result->set_object($result_data);
				}else{
					// 連想配列を取得
					while ($row = $result_mysqli->fetch_assoc()) {
						$result_data[] = $row;
					}									
					$result->set_array($result_data);
				}
				
				mysqli_free_result($result_mysqli);
			}
			
		}else{
				throw new Exception($this->_connection_conf[$cn_name]["dbdriver"]." はまだ当フレームワークに対応していません");
		}		
		return $result; 
	}			
	
	
	/*******************************************************/
	/**
	 * トランザクションを開始します
	 * @name trans_start
	 * @param string $cn_name DB接続情報名(default以外を使う場合に名前を指定する)
	 * @return true:開始成功、false:開始失敗。※ MyISAMのようにトランザクションに対応していない場合はtrueになってしまいます
	 * 
	*/	
	public function trans_start($cn_name="default") {
		$cn = &$this->get_connection($cn_name);
		
		if ($this->_connection_conf[$cn_name]["dbdriver"] === "pdo"){
			return $cn->beginTransaction();
		}elseif ($this->_connection_conf[$cn_name]["dbdriver"] === "mysqli"){
			$this->cn->autocommit(false);
			return $cn->begin_transaction();
		}else{
			throw new Exception($this->_connection_conf[$cn_name]["dbdriver"]." はまだ当フレームワークに対応していません");
		}
		
	}		
	
	/*******************************************************/
	/**
	 * トランザクションを開始します
	 * @name trans_begin
	 * @param string $cn_name DB接続情報名(default以外を使う場合に名前を指定する)
	 * @return true:成功、false:失敗。※ MyISAMのようにトランザクションに対応していない場合はtrueになってしまいます
	 * 
	*/	
	public function trans_begin($cn_name="default") {
		$cn = &$this->get_connection($cn_name);
		
		if ($this->_connection_conf[$cn_name]["dbdriver"] === "pdo"){
			return $cn->beginTransaction();
		}elseif ($this->_connection_conf[$cn_name]["dbdriver"] === "mysqli"){
			$this->cn->autocommit(false);
			return $cn->begin_transaction();
		}else{
			throw new Exception($this->_connection_conf[$cn_name]["dbdriver"]." はまだ当フレームワークに対応していません");
		}

	}			
	
	/*******************************************************/
	/**
	 * トランザクションをcommitします
	 * @name trans_commit
	 * @param string $cn_name DB接続情報名(default以外を使う場合に名前を指定する)
	 * @return true:成功、false:失敗。
	 * 
	*/	
	public function trans_commit($cn_name="default") {
		$cn = &$this->get_connection($cn_name);
		
		if ($this->_connection_conf[$cn_name]["dbdriver"] === "pdo"){
			return $cn->commit();
		}elseif ($this->_connection_conf[$cn_name]["dbdriver"] === "mysqli"){
			if ($this->cn->commit())
			{
				$this->cn->autocommit(true);
				return true;
			}else{
				return false;
			}
		}else{
			throw new Exception($this->_connection_conf[$cn_name]["dbdriver"]." はまだ当フレームワークに対応していません");
		}
		
		
	}				
	
	/*******************************************************/
	/**
	 * トランザクションをrollBackします
	 * @name trans_rollBack
	 * @param string $cn_name DB接続情報名(default以外を使う場合に名前を指定する)
	 * @return true:成功、false:失敗。
	 * 
	*/	
	public function trans_rollBack($cn_name="default") {
		$cn = &$this->get_connection($cn_name);
		
		if ($this->_connection_conf[$cn_name]["dbdriver"] === "pdo"){
			return $cn->rollBack();
		}elseif ($this->_connection_conf[$cn_name]["dbdriver"] === "mysqli"){
			if ($this->cn->rollback())
			{
				$this->cn->autocommit(true);
				return true;
			}else{
				return false;			
			}
		}else{
			throw new Exception($this->_connection_conf[$cn_name]["dbdriver"]." はまだ当フレームワークに対応していません");
		}
	}
	
}	

/*
 * 更新履歴
 * 2017-04-05 スペルミス。ファイル名とクラス名をconnction→connectionに変更
 * 2017-12-05 mysqliの_query関数で中途半端な実装だったところを作成。(2017/12現在mysqliでの運用実績なし)
 * 
 */
?>