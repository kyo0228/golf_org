<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*******************************************************/
/**
 * 共通継承元スーパークラス。当クラスを継承するすべてのクラスで共通的に使いたい関数を記載
 * 共通利用するためコンストラクタは設けていない。
 * @name GC_Abstract_Base (core/GC_Abstract_Base.php)
*/
abstract class GC_Abstract_Base {
	
	/*******************************************************/
	/**
	 * マルチバイト対応のtrim。先頭、最後の全角空白文字も除去
	 * @name trim_utf8
	 * @param string $str trimする文字列
	 * @param string $charlist 削除する文字
	 * @return 空白処理済みの文字列
	 * 
	*/	
	public function trim_utf8($str, $charlist = " \t\n\r\0\x0B　") {
			$charlist = str_replace('..', '-', addcslashes($charlist, "^-:]\0\\/"));
			return preg_replace("/\\A[{$charlist}]++|[{$charlist}]++\\z/u", '', $str);
	}	
	
	
	/*******************************************************/
	/**
	 * HTML表示文字列をフォーマット
	 * 以前は一つの関数でまとめていたけどdecodeはほとんど使わないから関数を分離
	* @name html_format
	* @param mixed	$target	フォーマットしたい文字列or配列
	* @return 調整した値or配列
	*/
	public function html_format($target) {
		return $this->_html_format($target,1);
	}		
	
	/*******************************************************/
	/**
	 * フォーマットしたHTML表示文字列を元に戻す
	* @name html_format_decode
	* @param mixed	$target	フォーマットしたい文字列or配列
	* @return 調整した値or配列
	*/
	public function html_format_decode($target) {
		return $this->_html_format($target,2);
	}			
	
	/*******************************************************/
	/**
	 * HTML表示文字列フォーマット関数(内部用)
	* @name _html_format
	* @param mixed	$target	フォーマットしたい文字列or配列
	* @param int		$mode		1:htmlの表示用に変換、2:変換された値を戻す
	* @return 調整した値or配列
	*/
	private function _html_format($target,$mode) {
		if (is_array($target)) {
			foreach ($target as $key => $val) {
				//再帰
				$target[$key] = $this->_html_format($val,$mode);
			}
		} else {
			if (!is_object($target)){
				if ($mode === 1)
					$target = htmlspecialchars($target, ENT_QUOTES);
				else if ($mode === 2)
					$target = htmlspecialchars_decode($target, ENT_QUOTES);
				
			}
			

			//echo $target."</br>";
		}

		return $target;
	}			
	
	/*******************************************************/
	/**
	 * 連想配列から配列の要素名を再帰で探します。
	 * @name search_key_array
	 * @param string $name 変数名
	 * @param array $ary 検索する配列
	 * @return any 値 みつからなかったらnull
	*/	
	public function search_key_array($name,$ary){
		$ret=null;
		if (!is_array($ary)){
			return null;
		}
		
		$keys = array_keys($ary);
		//普通の配列ならばチェックしないで終了
		if (array_values($ary) === $ary){
			return null;
		}

		// 配列数分ループして、キーを取り出して表示する。
		foreach ($keys as $value) {
			if ((string)$value === $name){
				$ret = $ary[$value];
			}else{
				if (is_array($ary[$value])){
					$ret = $this->search_key_array($name, $ary[$value]);
					if (isset($ret)){
						break;
					}					
				}
			}
		} 
		
		return $ret;
	}
	
	/*******************************************************/
	/**
	 * 文字列から拡張子を取得します
	 * @name get_extension
	 * @param string $str URLやパスの文字列（文字列最後が.xxxなもの）
	 * @return string 拡張子。引数が大文字、小文字関係なく小文字で戻ります。
	*/	
	public function get_extension($str){
		return mb_strtolower(substr($str, strrpos($str, '.') + 1));
	}	
	
	/*******************************************************/
	/**
	 * 指定のURLへリダイレクトします
	 * @name redirect
	 * @param string $url 遷移先url
	 * @param string $method location or refresh
	 * @param string $code locationの時のリダイレクトコード
	 * @return なし
	*/	
	public function redirect($url,$method="location",$code=""){
		
		// IISはrefreshじゃないと正常に動作しないらしい。うちにiisないので未検証
		if (isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS') !== FALSE)
		{
			$method = 'refresh';
		}
		
		if ($method !== 'refresh' && (empty($code) OR ! is_numeric($code)))
		{
			//codeigniterからパクリ。postをリダイレクトするとコードによっては遷移先でgetに変わってしまうのでそのあたりの対策だと思われる
			if (isset($_SERVER['SERVER_PROTOCOL'], $_SERVER['REQUEST_METHOD']) && $_SERVER['SERVER_PROTOCOL'] === 'HTTP/1.1')
			{
				$code = ($_SERVER['REQUEST_METHOD'] !== 'GET')
					? 303	// reference: http://en.wikipedia.org/wiki/Post/Redirect/Get
					: 307;
			}
			else
			{
				$code = 302;
			}
		}
		
		if ($method === "refresh"){
			header('Refresh:0;url='.$url);
		}else{
			header('Location: '.$url, TRUE, $code);
		}
		exit;
	}	
	
}	

?>