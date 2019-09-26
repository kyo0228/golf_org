<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*******************************************************/
/**
 * 共通ログ出力クラス
 * @name GC_Log (core/GC_Log.php)
*/
class GC_Log extends GC_Abstract_Base {
	
	/*******************************************************/
	/**
	 * コンストラクタ
	*/
	public function __construct() {
		//parent::__construct();		
	}
	
	/*******************************************************/
	/**
	 * logファイルはアプリケーションルートパスの[log]フォルダに出力されます。
	 * [log]ディレクトリに書き込み権限を設定してください。
	 * @name write
	 * @param string/array $message 出力メッセージ。配列の場合は配列の値を出力
	 * @param string $level info,error,debugなどlogのレベルを自由に設定。infoなら「infolog_yyyymm.log」が生成されます。空の場合は「log_yyyymm.log」
	 * @param string $filename 指定された場合はlogフォルダに[$filename.log]のファイルが出力されます。
	 * @param string $subdir 指定された場合はlogフォルダにサブフォルダを作成します
	 * 
	*/	
	public function write($message,$level="info",$filename="",$subdir="") {
		
		$logpath = $this->_get_log_path($subdir);
		
		if ($filename){
			$logpath .= $filename.".log";
		}else{
			$logpath .= $level."log_".date("Ym").".log";
		}
		
		$str = date("Y-m-d H:i:s")." ";
		
		if (is_array($message)){
			$str .="array(".implode(", ", $message).")\n";
		}else{
			$str .=$message."\n";
		}

		file_put_contents($logpath, $str, FILE_APPEND | LOCK_EX);
		chmod($logpath,0777);
		
	}	
	
	
	/*******************************************************/
	/**
	 * 例外エラーをログに出力します。
	 * [log]ディレクトリに書き込み権限を設定してください。
	 * @name exception
	 * @param class $e 例外エラークラス
	 * @param string $level info,error,debugなどlogのレベルを自由に設定。infoなら「infolog_yyyymm.log」が生成されます。空の場合は「log_yyyymm.log」
	 * @param string $filename 指定された場合はlogフォルダに[$filename.log]のファイルが出力されます。
	 * @param string $subdir 指定された場合はlogフォルダにサブフォルダを作成します
	 * 
	*/		
	public function exception($e,$level="exception",$filename="",$subdir="") {
		$msg = "type:".get_class($e).",file:".$e->getFile().",line:".$e->getLine()."\n";
		$msg.= "msg:".$e->getMessage();
		
		$this->write($msg,$level,$filename,$subdir);
		
	}
	
	/*******************************************************/
	/**
	 * logフォルダのパスを取得します。
	 * @name _get_child_class_name
	 * @param string $subdir 指定された場合はlogフォルダにサブフォルダを作成します
	 * @return string パス
	*/		
	private function _get_log_path($subdir="")
	{
		$logpath = BASEPATH."log/";
		
		if(!file_exists($logpath)){
				throw new Exception($logpath."が存在しません");
		}	
		
		if (!is_writable($logpath)){
			throw new Exception($logpath."に書き込み権限がありません");
		}
		
		if ($subdir){
			$logpath .= trim($subdir, "/")."/";
			
			if(!file_exists($logpath)){
				mkdir($logpath,0777);
				chmod($logpath, 0777);//mkdirで権限かわらないときあるからもう一度
			}
		}
		return $logpath;
	}		
	
	/*******************************************************/
	/**
	 * 呼び出し元クラスのファイル名を取得
	 * @name _get_child_class_name
	 * @return string ファイル名
	 * 
	*/		
	private function _get_child_class_name()
	{
			$ref = new ReflectionObject($this);
			return basename($ref->getFilename());
	}	
}	

/*
 * 更新履歴
 * 2017-11-27 write関数内で書き込み日付とメッセージの間に空白一文字を追加。ファイルのアクセス権限をフルアクセスに変更

 * 
 */

?>