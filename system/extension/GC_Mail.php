<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*******************************************************/
/**
 * 共通メール送信クラス
 * @name GC_Mail (extention/GC_Mail.php)
*/
class GC_Mail extends GC_Abstract_Base {
	
	/**
	 * メールの差出人アドレスを設定します
	 * @var string
	*/	
	public $from;
	
	/**
	 * メールの差出人名称を設定します
	 * @var string
	*/	
	public $fromname;
	
	/**
	 * メールの送信先を設定します
	 * @var string
	*/	
	public $to;
	
	/**
	 * メールの追加送信先を設定します
	 * @var string
	*/	
	public $cc;
	
	/**
	 * メールの追加送信先(他受信者に非表示)を設定します
	 * @var string
	*/	
	public $bcc;
	
	/**
	 * メールのタイトルを設定します
	 * @var string
	*/
	public $subject;
	
	/**
	 * メールの本文を設定します
	 * @var string
	*/
	public $message;	
	
	/**
	 * 自動返信メールを送信するかどうか
	 * @var bool
	*/
	public $isremail = false;
	
	/**
	 * 自動返信メールの差出人アドレスを設定します
	 * @var string
	*/
	public $re_from;
	
	/**
	 * 自動返信メールの差出人名称を設定します
	 * @var string
	*/
	public $re_fromname;
	
	/**
	 * 自動返信メールのタイトルを設定します
	 * @var string
	*/
	public $re_subject;
	
	/**
	 * 自動返信メールの本文を設定します
	 * @var string
	*/
	public $re_message;
				
	/**
	 * 半角・全角変換を行う場合、連想配列でキーとオプションを指定します	  
	 * @var array
	*/
	public $convert_array;
		
	/**
	 * リファラチェック用ドメイン
	 * @var string
	*/
	public $referer_domain;		
	
	/**
	 * メール設定情報保持配列
	 * @var array
	 */		
	protected $_mail_conf=array();		
	
	/**
	 * 添付ファイルのパスを保持する配列
	 * @var array
	 */
	protected $_attachfile = array();

	/*******************************************************/
	/**
	 * コンストラクタ
	*/
	public function __construct() {
		$confpath = APLPATH.'config/Mail.php';
		
		if (!file_exists($confpath)) {
			throw new Exception('設定ファイルが見つかりません ['.$confpath.']' );
		}		
		require_once $confpath;
				
		if (count($mail) === 0){
			throw new Exception('メール送信設定が定義されていません。' );
		}		
		
		$this->_mail_conf = array();
		foreach ($mail as $cn_name => $ary) {
			$this->_mail_conf[$cn_name] = $ary;
		}
		
		//タイムゾーンの設定
		if (version_compare(PHP_VERSION, '5.1.0', '>=')) {//PHP5.1.0以上の場合のみタイムゾーンを定義
			date_default_timezone_set($this->_mail_conf['timezone']);//タイムゾーンの設定（日本以外の場合には適宜設定ください）
		}		
	}

	/*******************************************************/
	/**
	 * 添付ファイル配列にパスを追加します(ファイルが見つからない場合は追加されません。)
	 * @name set_attach
	 * @param string $path 添付ファイルパス	  
	*/	
	public function set_attach($path) {
		if (file_exists($path)) {
			array_push($this->_attachfile, $path);			
		}
	}

	/*******************************************************/
	/**
	 * 添付ファイル配列初期化
	*/
	public function clear_attachs() {
		$this->_attachfile = array();
	}

	/*******************************************************/
	/**
	 * メールアドレスのフォーマットチェックを行います
	 * @name check_mailaddress
	 * @param string $mailaddress メールアドレス
	 * @return bool true:問題なし、false:問題あり
	*/	
	public function check_mailaddress($mailaddress){
		$mailaddress_array = explode('@',$mailaddress);
		if(preg_match("/^[\.!#%&\-_0-9a-zA-Z\?\/\+]+\@[!#%&\-_0-9a-z]+(\.[!#%&\-_0-9a-z]+)+$/", $mailaddress) && count($mailaddress_array) ==2){
			return true;
		}else{
			return false;
		}
	}				
	
	/*******************************************************/
	/**
	 * メールを送信します
	 * @name send
	 * @return bool $ret true:送信成功、false:送信失敗(実際に届いたかではないので注意)
	*/
	public function send(&$error="") {
		$ret = false;			
		
		try {												
			switch ($this->_mail_conf['protocol']) {
				case 'mail': 						
					mb_language('ja');
					mb_internal_encoding('UTF-8');
					
					$_subject = "=?".$this->_mail_conf['charset']."?B?".base64_encode(mb_convert_encoding($this->subject,"JIS","UTF-8"))."?=";
					$_message = mb_convert_encoding($this->message,"JIS","UTF-8");	
								
					
					if (count($this->_attachfile) == 0) {
						//添付ファイルなし
						$boundary = null;
						
						$body = $_message;
					} else {
						//添付ファイルあり
						$boundary = md5(uniqid(rand(), true));
						
						$body  = "--$boundary\n";
						$body .= "Content-Type: ".$this->_mail_conf['contenttype']."; charset=\"".$this->_mail_conf['charset']."\"\n";
						$body .= "Content-Transfer-Encoding: 7bit\n";
						$body .= "\n";
						$body .= "$_message\n";

						foreach($this->_attachfile as $file) {
							if (!file_exists($file)) {
								continue;
							}

							$info    = pathinfo($file);
							$content = $this->_mail_conf['mime_content_types'][$info['extension']];

							$filename = mb_convert_encoding($this->_get_basename($file),"JIS","UTF-8");				

							$body .= "\n";
							$body .= "--$boundary\n";
							$body .= "Content-Type: $content; name=\"$filename\"\n";
							$body .= "Content-Disposition: attachment; filename=\"$filename\"\n";
							$body .= "Content-Transfer-Encoding: base64\n";
							$body .= "\n";
							$body .= chunk_split(base64_encode(file_get_contents($file))) . "\n";
						}
							
						$body .= '--' . $boundary . '--';
					}															
										
					$ret = mail($this->to, $_subject, $body, $this->_create_mail_header("1", $boundary)); 
					
					//自動返信メールの送信
					if ($ret && $this->isremail) {
						$_re_subject = "=?".$this->_mail_conf['charset']."?B?".base64_encode(mb_convert_encoding($this->re_subject,"JIS","UTF-8"))."?=";
						$_re_message = mb_convert_encoding($this->re_message,"JIS","UTF-8");
						$ret = mail($this->from, $_re_subject, $_re_message, $this->_create_mail_header("2")); 
					}
					break;
				case 'sendmail':	
				case 'smtp':					
					require_once('Mail.php');										
										
					if ($this->_mail_conf['protocol'] == "smtp") $mailObject = Mail::factory("smtp", $this->_mail_conf['pear_params']);
					else $mailObject = Mail::factory("sendmail");
															
					mb_language('ja');
					mb_internal_encoding('UTF-8');
					$headerAr = array();
					$headerAr['MIME-Version'] = $this->_mail_conf['mineversion'];
					$headerAr['Content-Type'] = $this->_mail_conf['contenttype'].'; charset="'.$this->_mail_conf['charset'].'"';
					$headerAr['From'] = mb_encode_mimeheader($this->fromname, "JIS"). "<".$this->from.">";
					$headerAr['Subject'] = mb_encode_mimeheader($this->subject, "JIS");
					$headerAr['Return-Path'] = $this->from;
					$headerAr['To'] = $this->to;
					$headerAr['Cc'] = $this->cc;
					$headerAr['Bcc'] = $this->bcc;
					//$headerAr['Sender'] = $this->to;
					
					$_message = mb_convert_encoding($this->message,"JIS","UTF-8");						
											
					if (count($this->_attachfile) == 0) {
						//添付ファイルなし
						$body = $_message;
					} else {
						require_once("Mail/mime.php");
						
						//添付ファイルあり
						$mime = new Mail_Mime();
						$mime->setParam('text_charset', $this->_mail_conf['charset']);
						$mime->setParam('text_encoding', '8bit');
						$mime->setTxtBody($_message);
						
						foreach($this->_attachfile as $file) {
							if (!file_exists($file)) {
								continue;
							}
							
							$info    = pathinfo($file);
							$content = $this->_mail_conf['mime_content_types'][$info['extension']];
														
							$filename = mb_convert_encoding($this->_get_basename($file),"JIS","UTF-8");							
							$mime->addAttachment($file, $content, $filename, true, 'base64','attachment','ISO-2022-JP','','','base64','base64','','ISO-2022-JP');
						}																		
						
						$body = $mime->get();						
						$headerAr = $mime->headers($headerAr);
					}	
					
					$pearret = $mailObject->send($this->to, $headerAr, $body);																				
					if ($pearret == '1') {
						if ($this->isremail) {
							$re_headerAr = array();
							$re_headerAr['MIME-Version'] = $this->_mail_conf['mineversion'];
							$re_headerAr['Content-Type'] = $this->_mail_conf['contenttype'].'; charset="'.$this->_mail_conf['charset'].'"';
							$re_headerAr['From'] = mb_encode_mimeheader($this->re_fromname, "JIS"). "<".$this->re_from.">";
							$re_headerAr['Subject'] = mb_encode_mimeheader($this->re_subject, "JIS");
							$re_headerAr['Return-Path'] = $this->re_from;
							$re_headerAr['To'] = $this->from;
							//$re_headerAr['Sender'] = $this->to;	
							$_re_message = mb_convert_encoding($this->re_message,"JIS","UTF-8");
							$pearret = $mailObject->send($this->from, $re_headerAr, $_re_message);
							
							if ($pearret == '1') {
								$ret = true;
							} else {
								$error = $pearret;
							}													
						} else {
							$ret = true;
						}
					} else {
						$error = $pearret;
					}
					break;
			}	
		} catch (Exception $e) {
		}		
		return $ret;
	}
	
	/*******************************************************/
	/**
	 * パスの最後にある名前の部分を返す(PHPのbasename関数の代用-日本語ファイル文字化け対策)
	 * @name _get_basename
	 * @param string $path 
	 * @return string $ret パスの最後にある名前の部分
	*/
	private function _get_basename($path) {
		$ret = mb_substr(mb_strrchr($path, "/"), 1);
		return $ret;		
	}
	
	/*******************************************************/
	/**
	 * メールヘッダーを生成します
	 * @name _create_mail_header
	 * @param string $mode 1:通常メール、2:自動返信メール
	 * @return string $header ヘッダー文字列
	*/
	private function _create_mail_header($mode, $boundary=""){		
		$header  = "X-Mailer: PHP".phpversion()."\n";					
		
		switch ($mode) {
			case "1":
				if ($this->fromname != "") $header.="From: ".mb_encode_mimeheader($this->fromname)." <".$this->from.">\n";
				else $header.="From: ".$this->from."\n";	
				if($this->cc != '') $header.="Cc: ".$this->cc."\n";
				if($this->bcc != '') $header.="Bcc: ".$this->bcc."\n";
				$header.="Reply-To: ".$this->from."\n";
				break;
			case "2": //自動返信メール
				if ($this->re_fromname != "") $header.="From: ".mb_encode_mimeheader($this->re_fromname)." <".$this->re_from.">\n";
				else $header.="From: ".$this->re_from."\n";
				$header.="Reply-To: ".$this->re_from."\n";
				break;
		}						
		
		$header .= "MIME-Version: ".$this->_mail_conf['mineversion']."\n";
		if ($mode == '2' || count($this->_attachfile) == 0) {
			$header .= "Content-Type: ".$this->_mail_conf['contenttype']."; charset=\"".$this->_mail_conf['charset']."\"\n";						
		} else {
			$header .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\n";						
		}
		$header .= "Content-Transfer-Encoding: 7bit";
		
		//$header.="Content-Type:".$this->_mail_conf['contenttype'].";charset=".$this->_mail_conf['charset']."\nX-Mailer: PHP/".phpversion();			
		
		return $header;
	}
		
}	

?>