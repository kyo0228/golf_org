<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*******************************************************/
/**
 * バリデーション情報保持クラス
 * @name MY_Validator
 * @category Common
 * @package Model
 */
class MY_Validator {

  /* --- ▼▼▼ --- クラス変数 start --- ▼▼▼ --- */
	
  //バリデート情報配列
  private $validate_args;

  /* --- ▼▼▼ --- アクション start --- ▼▼▼ --- */
  
	/*******************************************************/
	/**
	 * コンストラクタ
   * @name __construct
   * @access public
   * @see ArrayObject::__construct()
   * @return none
	 */
	public function __construct() {
		$this->validate_args = new ArrayObject();
	}
	
	public function test() {
		return "MY_Validator";
	}	
	
	
	
	/**
	 * バリデーションルールセット
   * @name set_rule
   * @access public
   * @param string $fieldname 項目名
   * @param string $fieldname_display 日本語表示項目名
   * @param string $value 項目値
   * @param string $rule バリデーションルール
   *                      text：テキスト項目（何でもOK）
   *                      zenkaku：全角文字
   *                      hankaku：半角文字
   *                      kana_h：ひらがな
   *                      kana_k：カタカナ
   *                      number：半角数字
   *                      decimal：半角小数
   *                      alpha：半角英字
   *                      alphanum：半角英数字
   *                      date：年月日（YYYY/MM/DD）
   *                      time：時分秒（HH24:mm:ss）
   *                      datetime：年月日時分秒（YYYY/MM/DD HH24:mm:ss）
   *                      tel：電話番号（-ありなし両方対応）
   *                      email：メールアドレス（RFC準拠＋docomoやsoftbankのRFC非準拠メールアドレス、×国際化ドメイン）
   *                      uri：URL
   * @param boolean $required 未入力不可フラグ（0：未入力可、1：未入力不可）
   * @param number $min_length 入力可能文字数(最小)
	 * @param number $max_length 入力可能文字数(最大)
   * @param string $err_message エラーメッセージ（ここにエラーメッセージを入れるとエラー時に優先的に使用される）
   * @return none
	 */
  public function set_rule($fieldname = 0, $fieldname_display = NULL, $value = NULL, $rule = "text", $required = FALSE, $min_length = 0, $max_length = 0, $err_message = NULL) {
    if (is_null($fieldname_display)) :
      $fieldname_display = $fieldname;
    endif;
    $this->validate_args->offsetSet(
                              $fieldname
                            , array(
                                  "fieldname_display" => $fieldname_display
                                , "value" => $value
                                , "rule" => $rule
                                , "required" => $required
																, "min_length" => $min_length
																, "max_length" => $max_length
                                , "result" => NULL
                                , "err_message" => $err_message
                              )
                          );
  }

  /*******************************************************/
	/**
	 * セット済ルールの初期化
   * @name init_rule
   * @access public
   * @return none
	 */
  public function init_rule() {
    $this->validate_args = new ArrayObject();
  }
  
	/**
	 * チェック実行
   * 値に数字以外が含まれていたらエラー
   * @name do_valid
   * @access public
   * @return none
	 */
  public function do_valid() {
    try {
      $rtn = TRUE;
      foreach ($this->validate_args as $key => $row) :
        // 必須入力チェック
        if ($row["required"]) :
          if (is_null($row["value"]) || (empty($row["value"]) && (string)$row["value"] !== "0")) :
            $rtn = FALSE;
            $row["result"] = FALSE;
            $row["err_message"] = $row["fieldname_display"]."は入力必須の項目です。";
            $this->validate_args->offsetSet($key, $row);
            continue;
          endif;
        endif;
        // 文字数チェック(最小)
        if ((int)$row["min_length"] > 0) :
          if (mb_strlen($row["value"], "utf-8") < (int)$row["min_length"]) :
            $rtn = FALSE;
            $row["result"] = FALSE;
            $row["err_message"] = $row["fieldname_display"]."は".(int)$row["min_length"]."文字以上で入力してください。";
            $this->validate_args->offsetSet($key, $row);
            continue;
          endif;
        endif;
        // 文字数チェック(最大)
        if ((int)$row["max_length"] > 0) :
          if (mb_strlen($row["value"], "utf-8") > (int)$row["max_length"]) :
            $rtn = FALSE;
            $row["result"] = FALSE;
            $row["err_message"] = $row["fieldname_display"]."は".(int)$row["max_length"]."文字以内で入力してください。";
            $this->validate_args->offsetSet($key, $row);
            continue;
          endif;
        endif;
        // その他形式チェック
        switch ($row["rule"]) :
          case "text" :
            // 文字列（なんでもよい）のため判定しない
            $row["result"] = TRUE;
            break;
          case "zenkaku" :
            $row["result"] = $this->valid_zenkaku($row["value"], $row["err_message"], $row["fieldname_display"]);
            break;
          case "hankaku" :
            $row["result"] = $this->valid_hankaku($row["value"], $row["err_message"], $row["fieldname_display"]);
            break;
          case "kana_h" :
            $row["result"] = $this->valid_hiragana($row["value"], $row["err_message"], $row["fieldname_display"]);
            break;
          case "kana_k" :
            $row["result"] = $this->valid_katakana($row["value"], $row["err_message"], $row["fieldname_display"]);
            break;
          case "number" :
            $row["result"] = $this->valid_number($row["value"], $row["err_message"], $row["fieldname_display"]);
            break;
          case "decimal" :
            $row["result"] = $this->valid_decimal($row["value"], $row["err_message"], $row["fieldname_display"]);
            break;
          case "alpha" :
            $row["result"] = $this->valid_alpha($row["value"], $row["err_message"], $row["fieldname_display"]);
            break;
          case "alphanum" :
            $row["result"] = $this->valid_alphanum($row["value"], $row["err_message"], $row["fieldname_display"]);
            break;
          case "date" :
            $row["result"] = $this->valid_date($row["value"], $row["err_message"], $row["fieldname_display"]);
            break;
          case "time" :
            $row["result"] = TRUE;
            break;
          case "datetime" :
            $row["result"] = TRUE;
            break;
          case "tel" :
            $row["result"] = $this->valid_tel($row["value"], $row["err_message"], $row["fieldname_display"]);
            break;
          case "email" :
            $row["result"] = $this->valid_mail($row["value"], $row["err_message"], $row["fieldname_display"]);
            break;
          case "uri" :
            $row["result"] = $this->valid_uri($row["value"], $row["err_message"], $row["fieldname_display"]);
            break;
          case "zip" :
            $row["result"] = $this->valid_zip($row["value"], $row["err_message"], $row["fieldname_display"]);
            break;
          default :
            break;
        endswitch;
        if (!$row["result"]) :
          $rtn = FALSE;
        endif;
        $this->validate_args->offsetSet($key, $row);
      endforeach;
      return $rtn;
    } catch (Exception $e) {
      exit($e->getMessage());
    }
  }

  /*******************************************************/
	/**
	 * バリデーション結果の取得
   * @name get_result
   * @access public
   * @return none
	 */
  public function get_result() {
    return $this->validate_args;
  }
  
  /*******************************************************/
	/**
	 * バリデーション結果エラーメッセージのみ取得（全件）
   * @name get_err_message_args
   * @access public
   * @return array $err_message_args エラーメッセージ配列
	 */
  public function get_err_message_args() {
    $err_message_args = array();
    foreach ($this->validate_args as $key => $row) :
      if (!is_null($row["err_message"])) :
        $err_message_args[$key] = $row["err_message"];
      endif;
    endforeach;
    return $err_message_args;
  }
  
  /*******************************************************/
	/**
	 * バリデーション結果エラーメッセージのみ取得（fieldname指定）
   * @name get_err_message
   * @access public
   * @param $fieldname
   * @return string エラーメッセージありの時、エラーメッセージ内容、なしの時、FALSE
	 */
  public function get_err_message($fieldname = NULL) {
    if (!is_null($this->validate_args[$fieldname]["err_message"])) :
      return $this->validate_args[$fieldname]["err_message"];
    else :
      return FALSE;
    endif;
  }
  
  /*******************************************************/
	/**
	 * 数字チェック
   * 値に数字以外が含まれていたらエラー
   * @name valid_number
   * @access private
   * @param string $value 値
   * @param string $err_message エラーメッセージ
   * @param string $fieldname_display 表示名 out
   * @return boolean OK：TRUE、NG：FALSE
	 */
  private function valid_number($value, &$err_message, $fieldname_display = NULL) {
    $rtn = TRUE;
    try {
      if (is_null($fieldname_display)) :
        $fieldname_display = "数値";
      endif;
      if (!(is_null($value) || (empty($value) && (string)$value !== "0"))) :
        if (!ctype_digit((string)$value)) :
          if (empty($err_message)) :
            $err_message = $fieldname_display."は半角数字のみ使用可能です。";
          endif;
          $rtn = FALSE;
        else :
          $err_message = NULL;
        endif;
      else :
        $err_message = NULL;
      endif;
    } catch (Exception $e) {
      $err_message = $fieldname_display."：".$e->getMessage();
      $rtn = FALSE;
    }
    return $rtn;
  }

  /*******************************************************/
	/**
	 * 日付チェック
   * 日付形式が正しいかチェック
   * @name valid_date
   * @access private
   * @param string $value 値
   * @param string $err_message エラーメッセージ out
   * @param string $fieldname_display 表示名
   * @return boolean OK：TRUE、NG：FALSE
	 */
  private function valid_date($value, &$err_message, $fieldname_display = NULL) {
    $rtn = TRUE;
    try {
      if (is_null($fieldname_display)) :
        $fieldname_display = "日付";
      endif;
      if (!(is_null($value) || (empty($value) && (string)$value !== "0"))) :
        $date = str_replace("-", "/", $value);
        $exp_date_args = explode("/", $date);
        if (count($exp_date_args) !== 3) :
          $rtn = FALSE;
        else :
          if (!checkdate($exp_date_args[1], $exp_date_args[2], $exp_date_args[0])) :
            $rtn = FALSE;
          endif;
        endif;
        if (!$rtn) :
          if (empty($err_message)) :
            $err_message = $fieldname_display."は不正な日付です。";
          endif;
        else :
          $err_message = NULL;
        endif;
      else :
        $err_message = NULL;
      endif;
    } catch (Exception $e) {
      $err_message = $fieldname_display."：".$e->getMessage();
      $rtn = FALSE;
    }
    return $rtn;
  }
  
  /*******************************************************/
	/**
	 * 全角文字チェック
   * 値に全角文字以外が含まれていたらエラー
   * @name valid_zenkaku
   * @access private
   * @param string $value 値
   * @param string $err_message エラーメッセージ
   * @param string $fieldname_display 表示名 out
   * @return boolean OK：TRUE、NG：FALSE
	 */
  private function valid_zenkaku($value, &$err_message, $fieldname_display = NULL) {
    $rtn = TRUE;
    try {
      if (is_null($fieldname_display)) :
        $fieldname_display = "全角文字";
      endif;
      if (!(is_null($value) || (empty($value) && (string)$value !== "0"))) :
        if (preg_match("/(?:\xEF\xBD[\xA1-\xBF]|\xEF\xBE[\x80-\x9F])|[\x20-\x7E]/", $value)) :
          if (empty($err_message)) :
            $err_message = $fieldname_display."は全角文字で入力してください。";
          endif;
          $rtn = FALSE;
        else :
          $err_message = NULL;
        endif;
      else :
        $err_message = NULL;
      endif;
    } catch (Exception $e) {
      $err_message = $fieldname_display."：".$e->getMessage();
      $rtn = FALSE;
    }
    return $rtn;
  }
  
  /*******************************************************/
	/**
	 * 全角文字チェック
   * 値に全角文字以外が含まれていたらエラー
   * @name valid_hankaku
   * @access private
   * @param string $value 値
   * @param string $err_message エラーメッセージ
   * @param string $fieldname_display 表示名 out
   * @return boolean OK：TRUE、NG：FALSE
	 */
  private function valid_hankaku($value, &$err_message, $fieldname_display = NULL) {
    $rtn = TRUE;
    try {
      if (is_null($fieldname_display)) :
        $fieldname_display = "半角文字";
      endif;
      if (!(is_null($value) || (empty($value) && (string)$value !== "0"))) :
        if (strlen($value) !== mb_strlen($value)) :
          if (empty($err_message)) :
            $err_message = $fieldname_display."は半角文字で入力してください。";
          endif;
          $rtn = FALSE;
        else :
          $err_message = NULL;
        endif;
      else :
        $err_message = NULL;
      endif;
    } catch (Exception $e) {
      $err_message = $fieldname_display."：".$e->getMessage();
      $rtn = FALSE;
    }
    return $rtn;
  }
  
  /*******************************************************/
	/**
	 * ひらがなチェック
   * 値にひらがな以外が含まれていたらエラー
   * @name valid_hiragana
   * @access private
   * @param string $value 値
   * @param string $err_message エラーメッセージ
   * @param string $fieldname_display 表示名 out
   * @return boolean OK：TRUE、NG：FALSE
	 */
  private function valid_hiragana($value, &$err_message, $fieldname_display = NULL) {
    $rtn = TRUE;
    try {
      if (is_null($fieldname_display)) :
        $fieldname_display = "ひらがな（全角）";
      endif;
      if (!(is_null($value) || (empty($value) && (string)$value !== "0"))) :
        if (!preg_match("/^[ぁ-ん]+$/u", $value)) :
          if (empty($err_message)) :
            $err_message = $fieldname_display."は全角ひらがなで入力してください。";
          endif;
          $rtn = FALSE;
        else :
          $err_message = NULL;
        endif;
      else :
        $err_message = NULL;
      endif;
    } catch (Exception $e) {
      $err_message = $fieldname_display."：".$e->getMessage();
      $rtn = FALSE;
    }
    return $rtn;
  }
  
  /*******************************************************/
	/**
	 * カタカナチェック
   * 値にカタカナ以外が含まれていたらエラー
   * @name valid_katakana
   * @access private
   * @param string $value 値
   * @param string $err_message エラーメッセージ
   * @param string $fieldname_display 表示名 out
   * @return boolean OK：TRUE、NG：FALSE
	 */
  private function valid_katakana($value, &$err_message, $fieldname_display = NULL) {
    $rtn = TRUE;
    try {
      if (is_null($fieldname_display)) :
        $fieldname_display = "カタカナ（全角）";
      endif;
      if (!(is_null($value) || (empty($value) && (string)$value !== "0"))) :
        if (!preg_match("/^[ァ-ヶー]+$/u", $value)) :
          if (empty($err_message)) :
            $err_message = $fieldname_display."は全角カタカナで入力してください。";
          endif;
          $rtn = FALSE;
        else :
          $err_message = NULL;
        endif;
      else :
        $err_message = NULL;
      endif;
    } catch (Exception $e) {
      $err_message = $fieldname_display."：".$e->getMessage();
      $rtn = FALSE;
    }
    return $rtn;
  }

  /*******************************************************/
	/**
	 * 小数チェック
   * 値に小数（0〜9と.）以外が含まれていたらエラー（先頭.始まりは不可）
   * @name valid_decimal
   * @access private
   * @param string $value 値
   * @param string $err_message エラーメッセージ
   * @param string $fieldname_display 表示名 out
   * @return boolean OK：TRUE、NG：FALSE
	 */
  private function valid_decimal($value, &$err_message, $fieldname_display = NULL) {
    $rtn = TRUE;
    try {
      if (is_null($fieldname_display)) :
        $fieldname_display = "数値（小数）";
      endif;
      if (!(is_null($value) || (empty($value) && (string)$value !== "0"))) :
        if (!preg_match('/^([1-9]\d*|0)\.(\d+)?$/', $value)) :
          if (empty($err_message)) :
            $err_message = $fieldname_display."は半角数字もしくは「.」のみ使用可能です（ただし先頭「.」は不可です）。";
          endif;
          $rtn = FALSE;
        else :
          $err_message = NULL;
        endif;
      else :
        $err_message = NULL;
      endif;
    } catch (Exception $e) {
      $err_message = $fieldname_display."：".$e->getMessage();
      $rtn = FALSE;
    }
    return $rtn;
  }
  
  /*******************************************************/
	/**
	 * 英字チェック
   * 値に半角英字（a-z、A-Z）以外が含まれていたらエラー
   * @name valid_alpha
   * @access private
   * @param string $value 値
   * @param string $err_message エラーメッセージ
   * @param string $fieldname_display 表示名 out
   * @return boolean OK：TRUE、NG：FALSE
	 */
  private function valid_alpha($value, &$err_message, $fieldname_display = NULL) {
    $rtn = TRUE;
    try {
      if (is_null($fieldname_display)) :
        $fieldname_display = "英字";
      endif;
      if (!(is_null($value) || (empty($value) && (string)$value !== "0"))) :
        if (!ctype_alpha((string)$value)) :
          if (empty($err_message)) :
            $err_message = $fieldname_display."は半角英字のみ使用可能です。";
          endif;
          $rtn = FALSE;
        else :
          $err_message = NULL;
        endif;
      else :
        $err_message = NULL;
      endif;
    } catch (Exception $e) {
      $err_message = $fieldname_display."：".$e->getMessage();
      $rtn = FALSE;
    }
    return $rtn;
  }
  
  /*******************************************************/
	/**
	 * 英数字チェック
   * 値に半角英数字（a-z、A-Z、0-9）以外が含まれていたらエラー
   * @name valid_alphanum
   * @access private
   * @param string $value 値
   * @param string $err_message エラーメッセージ
   * @param string $fieldname_display 表示名 out
   * @return boolean OK：TRUE、NG：FALSE
	 */
  private function valid_alphanum($value, &$err_message, $fieldname_display = NULL) {
    $rtn = TRUE;
    try {
      if (is_null($fieldname_display)) :
        $fieldname_display = "英数字";
      endif;
      if (!(is_null($value) || (empty($value) && (string)$value !== "0"))) :
        if (!ctype_alnum((string)$value)) :
          if (empty($err_message)) :
            $err_message = $fieldname_display."は半角英数字のみ使用可能です。";
          endif;
          $rtn = FALSE;
        else :
          $err_message = NULL;
        endif;
      else :
        $err_message = NULL;
      endif;
    } catch (Exception $e) {
      $err_message = $fieldname_display."：".$e->getMessage();
      $rtn = FALSE;
    }
    return $rtn;
  }
  
  /*******************************************************/
	/**
	 * 電話番号チェック
   * 電話番号が正しい形式化チェック
   * @name valid_tel
   * @access private
   * @param string $value 値
   * @param string $err_message エラーメッセージ
   * @param string $fieldname_display 表示名 out
   * @return boolean OK：TRUE、NG：FALSE
	 */
  private function valid_tel($value, &$err_message, $fieldname_display = NULL) {
    $rtn = TRUE;
    try {
      if (is_null($fieldname_display)) :
        $fieldname_display = "電話番号";
      endif;
      if (!(is_null($value) || (empty($value) && (string)$value !== "0"))) :
        if (!strpos($value, "-")) :
          if (!preg_match("/(^(?<!090|080|070)\d{10}$)|(^(090|080|070)\d{8}$)|(^0120\d{6}$)|(^0080\d{7}$)/", $value)) :
            if (empty($err_message)) :
              $err_message = $fieldname_display."は不正な電話番号です。";
            endif;
            $rtn = FALSE;
          else :
            $err_message = NULL;
          endif;
        else :
          if (!preg_match("/(^(?<!090|080|070)(^\d{2,5}?\-\d{1,4}?\-\d{4}$|^&#91;\d\-&#93;{12}$))|(^(090|080|070)(\-\d{4}\-\d{4}|&#91;\\d-&#93;{13})$)|(^0120(\-\d{2,3}\-\d{3,4}|&#91;\d\-&#93;{12})$)|(^0080\-\d{3}\-\d{4})/", $value)) :
            if (empty($err_message)) :
              $err_message = $fieldname_display."は不正な電話番号です。";
            endif;
            $rtn = FALSE;
          else :
            $err_message = NULL;
          endif;
        endif;
      else :
        $err_message = NULL;
      endif;
    } catch (Exception $e) {
      $err_message = $fieldname_display."：".$e->getMessage();
      $rtn = FALSE;
    }
    return $rtn;
  }
  
  /*******************************************************/
	/**
	 * メールアドレスのバリデーションチェック
   * メールアドレスがRFC準拠のメールアドレスかをチェック
   * docomo、softbankなどのRFC非準拠メールアドレスにも対応
   * ただし国際化ドメインには未対応
   * @name valid_mail
   * @access private
   * @param string $value メールアドレス
   * @param string $err_message エラーメッセージ
   * @param string $fieldname_display 表示名 out
   * @return boolean OK：TRUE、NG：FALSE
	 */
  private function valid_mail($value, &$err_message, $fieldname_display = NULL) {
    $rtn = TRUE;
    try {
      if (is_null($fieldname_display)) :
        $fieldname_display = "メールアドレス";
      endif;
      if (!(is_null($value) || (empty($value) && (string)$value !== "0"))) :
        if (!preg_match('/^[-+.\\w]+@[-a-z0-9]+(\\.[-a-z0-9]+)*\\.[a-z]{2,6}$/i', $value)) :
          if (empty($err_message)) :
            $err_message = $fieldname_display."は不正なメールアドレスです。";
          endif;
          $rtn = FALSE;
        else :
          $err_message = NULL;
        endif;
      else :
        $err_message = NULL;
      endif;
    } catch (Exception $e) {
      $err_message = $fieldname_display."：".$e->getMessage();
      $rtn = FALSE;
    }
    return $rtn;
  }
  
  /*******************************************************/
	/**
	 * URLのバリデーションチェック
   * @name valid_uri
   * @access private
   * @param string $value メールアドレス
   * @param string $err_message エラーメッセージ
   * @param string $fieldname_display 表示名 out
   * @return boolean OK：TRUE、NG：FALSE
	 */
  private function valid_uri($value, &$err_message, $fieldname_display = NULL) {
    $rtn = TRUE;
    try {
      if (is_null($fieldname_display)) :
        $fieldname_display = "URI";
      endif;
      if (!(is_null($value) || (empty($value) && (string)$value !== "0"))) :
        if (!(filter_var($value, FILTER_VALIDATE_URL) && preg_match('@^https?+://@i', $value))) :
          if (empty($err_message)) :
            $err_message = $fieldname_display."は不正なURLです。";
          endif;
          $rtn = FALSE;
        else :
          $err_message = NULL;
        endif;
      else :
        $err_message = NULL;
      endif;
    } catch (Exception $e) {
      $err_message = $fieldname_display."：".$e->getMessage();
      $rtn = FALSE;
    }
    return $rtn;
  }
  
  /*******************************************************/
	/**
	 * 郵便番号のバリデーションチェック
   * @name valid_zip
   * @access private
   * @param string $value 郵便番号（半角数字7桁もしくはXXX-XXXX）
   * @param string $err_message エラーメッセージ
   * @param string $fieldname_display 表示名 out
   * @return boolean OK：TRUE、NG：FALSE
	 */
  private function valid_zip($value, &$err_message, $fieldname_display = NULL) {
    $rtn = TRUE;
    try {
      if (is_null($fieldname_display)) :
        $fieldname_display = "郵便番号";
      endif;
      if (!(is_null($value) || (empty($value) && (string)$value !== "0"))) :
        if (!preg_match("/^\d{3}\-\d{4}$/", $value) && !preg_match("/^\d{7}$/", $value)) :
          if (empty($err_message)) :
            $err_message = $fieldname_display."は不正な郵便番号です。";
          endif;
          $rtn = FALSE;
        else :
          $err_message = NULL;
        endif;
      else :
        $err_message = NULL;
      endif;
    } catch (Exception $e) {
      $err_message = $fieldname_display."：".$e->getMessage();
      $rtn = FALSE;
    }
    return $rtn;
  }
  
}	

?>