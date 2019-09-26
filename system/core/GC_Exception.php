<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*******************************************************/
/**
 * 例外処理クラス
 * @name GC_Exception (core/GC_Exception.php)
*/
class GC_Exception extends GC_Abstract_Base {

	/**
	 * GC_logのインスタンスを保持
	 * @var GC_Log
	 */						
	protected  $_log = "";	
	
	/*******************************************************/
	/**
	 * コンストラクタ
	*/
	public function __construct() {
		//parent::__construct();		
	}
	
	/*******************************************************/
	/**
	 * GC_Logのインスタンスをクラスにセット
	 * 
	 * ※備忘録:引数を参照渡しで用意していたらphp7でコンパイルエラー。別のところが原因？
	 * @name exception_handler
	 * @param GC_Log $instance インスタンス
	 * @return なし
	*/	
	public function get_log_instance($instance) {
		$this->_log = $instance;
	}			
	/*******************************************************/
	/**
	 * キャッチされていない例外をハンドリングする関数。
	 * try-catchで漏れた例外を取得
	 * @name exception_handler
	 * @param Exception $e 例外エラー
	 * @return なし
	*/	
	public function exception_handler($e) {
		
		$this->_log->exception($e);
		
		if (GC_Static::sys("use_debugmode")){
			require GC_Static::localresource("error_exception");
		}
		
	}

	/*******************************************************/
	/**
	 * phpのwarningなどをハンドリングする関数。
	 * shutdown_handlerとは違いスクリプトは終了せず、続行します
	 * @name warning_handler
	 * @return なし
	*/		
	public function warning_handler($severity, $message, $file, $line) {	
		
		$this->_log->write(array($file,$line,$message), "php_warning");
		
		if (GC_Static::sys("use_debugmode")){
			$e["kind"] = $this->_get_error_type($severity);
			$e["message"] = $message;
			$e["file"] = $file;
			$e["line"] = $line;
			$clr = "gold";

			require GC_Static::localresource("error_phpmsg");
		}
	}		

	/*******************************************************/
	/**
	 * phpのスクリプト終了をハンドリングする関数。
	 * phpの内部エラーが発生した場合に画面への表示とログへの書き込み処理を行う
	 * @name shutdown_handler
	 * @return なし
	*/	
	public function shutdown_handler(){
		$e = error_get_last();
		$clr = "red";
		$e["kind"] = $this->_get_error_type($e['type']);
		 
		if ($e["kind"] && stripos($e["kind"], "Error") > 0){
			$this->_log->write($e, "php_error");
			
			if (GC_Static::sys("use_debugmode")){
				require GC_Static::localresource("error_phpmsg");
			}
		}
	}

	/*******************************************************/
	/**
	 * phpのエラー番号からエラー種類を取得
	 * @name get_error_type
	 * @param エラー番号
	 * @return エラー種類
	*/	
	protected function _get_error_type($type){
		if (!isset($type)) return false;

		switch($type)
		{
				case E_ERROR: // 1 //重大な実行時エラー
						return 'Fatal Error';
				case E_WARNING: // 2 //実行時の警告 (致命的なエラーではない)
						return 'Warning';
				case E_PARSE: // 4 //コンパイル時のパースエラー
						return 'Parse Error';
				case E_NOTICE: // 8 //実行時の警告
						return 'Notice';
				case E_CORE_ERROR: // 16 //PHPの初期始動時点での致命的なエラー
						return 'Core Error';
				case E_CORE_WARNING: // 32 //（致命的ではない）警告
						return 'Core Warning';
				case E_COMPILE_ERROR: // 64 //コンパイル時の致命的なエラー
						return 'Compile Error';
				case E_COMPILE_WARNING: // 128 //コンパイル時の警告（致命的ではない）
						return 'Compile Warning';
				case E_USER_ERROR: // 256 //ユーザーによって発行されるエラーメッセージ
						return 'User Error';
				case E_USER_WARNING: // 512 //ユーザーによって発行される警告メッセージ
						return 'User Warning';
				case E_USER_NOTICE: // 1024 //ユーザーによって発行される注意メッセージ
						return 'User Notice';
				case E_STRICT: // 2048 //コードの相互運用性や互換性を維持するために PHP がコードの変更を提案する
						return 'Strict';
				case E_RECOVERABLE_ERROR: // 4096 //キャッチできる致命的なエラー
						return 'Recoverable Error';
				case E_DEPRECATED: // 8192 //実行時の注意
						return 'Deprecated';
				case E_USER_DEPRECATED: // 16384 //ユーザー定義の警告メッセージ
						return 'User Deprecated';
				default:
					return 'Unknown';
		}
	}	
}	
				

?>