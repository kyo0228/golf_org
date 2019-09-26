<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*******************************************************/
/**
 * アプリ用の共通コントローラ。コントローラの共通処理はここに記載。（共通処理が今後も使える関数の場合はcoreに組み込む）
 * @name MY_Controller (controller/MY_Controller.php)
*/
class MY_Controller extends GC_Controller {

	/**
	 * $this->db
	 * @var MY_DB
	 */	
	protected $db;
	
	/**
	 * $this->page
	 * @var MY_Page
	 */		
	protected $page;
	

	/*******************************************************/
	/**
	 * コンストラクタ
	*/
	public function __construct() {
		parent::__construct();
		
    $group_code = $this->page->get("group");
    if (!$group_code){
      $this->output_error_404();
      exit();
    }
    
    $group_data = $this->db->get_group_id($group_code);
    if (!$group_data){
      $this->output_error_404();
      exit();      
    }
    
    if ($this->page->controller != "login" &&  !$this->session->get_data("user_auth")){
      $this->redirect($this->page->url_view("index", "login"));
    }else{
      $this->page->set_item("user_auth", $this->session->get_data("user_auth"));
    }
    
    //メンバー権限で編集ページに入ろうとした時はログアウト
    $pos = strpos($this->page->name, "_edit");
    if ($this->session->get_data("user_auth") === "member" && $pos > 0){
      $this->redirect($this->page->url_view("exit", "login"));
    }
    
    //CSSなどのファイルキャッシュ対策でバージョン番号を使用
    $this->page->set_item("apl_ver", $this->config->sys("apl_version"));
    
    $this->page->set_item("group_data", $group_data);
    
    $this->page->title = $group_data["group_name"]."スコア管理";
    
		$this->_set_html_resource_header();
		

	}
	
	/*******************************************************/
	/**
	 * htmlのheaderにセットする
	 * 
	 * @name _controller_name
	 * @param int $mode 0:コントローラ名(全部小文字)[既定値]、1:コントローラ名(先頭大文字)、2:コントローラクラス名(XxxxControllerまで)
	 * @return string モードに合わせたコントローラ名

	*/	
	private function _set_html_resource_header(){
		#$this->page->load_resource_head($this->page->url_css("style.css"));
		
    /*
		$this->page->load_resource_head($this->page->url_css("style.css?".  time()));
		$this->page->load_resource_head($this->page->url_css("font-awesome-4.7.0/css/font-awesome.min.css"));
		
		$this->page->load_resource_head($this->page->url_js("jquery3.3.1.js"));
		$this->page->load_resource_head($this->page->url_js("jquery-highlight-5.js")); //科目検索の強調表示で使用
    
    if ($this->page->controller === "site" && $this->page->name === "list"){
      $this->page->load_resource_head($this->page->url_libraries("fixed_midashi_1.10/fixed_midashi.js"));
    }
		*/
		
	}
  
	/*******************************************************/
	/**
	 * csv作成処理
	 * @name output_csv
	 * @param string $name	出力ファイル名(指定値の後ろに「-yyyy-mm-dd」をつけて出力)
	 * @param array	$data	csvに出力するデータ配列
	 * @param array	$colList(optional) 出力するカラム名(配列のキーにカラム名,値に表示名)
	 * @param string $head 先頭行に出力するヘッダ
	 * @return データがない場合はfalse
	*/
	public function output_csv($name, $data, $colList=null, $head=null) {
		//if (!is_array($data) || !count($data)) return false;

		$name = mb_convert_encoding($name, 'SJIS-WIN', 'UTF-8');
		$filename = uniqid(mt_rand()).".csv";//一時ファイル名(ユニーク文字取得)
		$outputFile = $this->page->path_tmp($filename);//一時保存先フルパス
		touch($outputFile);//ファイル作成
		$old = umask(0);
		chmod($outputFile, 0777);
		umask($old);
		$fp = fopen($outputFile, "w");//ファイルオープン

		//カラム名が設定されていない場合は初期化,設定済みの場合は文字コードのみ調整
		if ($colList == null){
			foreach($data[0] as $key => $value) {
				$colList[$key] = "\"".$this->mb_csv_encoding('"','""',$key, 'SJIS-WIN', 'UTF-8')."\"";
			}
		}else{
			foreach($colList as $key => $value) {
				$colList[$key] = "\"".$this->mb_csv_encoding('"','""',$value, 'SJIS-WIN', 'UTF-8')."\"";
			}
		}

		//headerを作成
		//fputcsv($fp,$colList);
		$line = "";
		$cnt = 1;

		//1行目にヘッダ挿し込み
		$colListcnt = count($colList);
		if($head) {
			$headline = mb_convert_encoding($head, 'SJIS-WIN', 'UTF-8');
			
			while($colListcnt >= $cnt) {
				if($colListcnt != $cnt) {
					$headline .= ',""';
				} else {
					$headline .= ',';
				}
				$cnt++;
			}
			
			fwrite($fp, $headline);
			fwrite($fp, "\r\n");
		} 

		foreach($colList as $key => $value) {
			$line .= $value;
			if (count($colList) !== $cnt) {
				$line .= ',';
				$cnt++;
			}
		}
		fwrite($fp, $line);
		fwrite($fp, "\r\n");

		//detail
		foreach($data as $row){
			//カラムに合わせる
			$line = "";
			$cnt = 1;
			foreach($colList as $key => $value) {
				if ($row[$key] != ""){
					$val = $this->mb_csv_encoding('"','""',$row[$key], 'SJIS-WIN', 'UTF-8');
				} else {
					$val = "";
				}
				$line .= '"'.$val.'"';

				if (count($colList) !== $cnt) {
					$line .= ',';
					$cnt++;
				}
			}

			//fputcsv関数から自前のcsv処理に変更。2011/12/19@souma
			fwrite($fp, $line);
			fwrite($fp, "\r\n");
		}
		fclose($fp);//保存

		//ファイルサイズを取得し、ファイルをメモリに読み込み。元ファイルを削除(データが大きいとサーバーがやばいかも)
		$len = filesize($outputFile);
		$contents = fread(fopen($outputFile, "r"), $len);
		unlink($outputFile);

		//ダウンロード処理
		$filename = $name."-".date("Y-m-d_His").".csv";
		//header("Content-Type: text/octet-stream");
		header("Content-Type: application/csv");
		header("Content-Disposition: attachment; filename=".$filename);
		header("Content-Length:".$len);
		print($contents);
		return true;
	}

	/*******************************************************/
	/**
	* csv文字列のエンコード
	 * @name mb_csv_encoding
	 * @param mixed $search 検索文字列（またはその配列）
	 * @param mixed $replace 置換文字列（またはその配列）
	 * @param mixed $subject 対象文字列（またはその配列）
	 * @param string $toencoding 文字列のエンコーディング
	 * @param string $fromencoding 文字列のエンコーディング
	 * @return mixed subject 内の search を replace で置き換えた文字列
	*/
	public function mb_csv_encoding($search,$replace,$subject,$toencoding,$fromencoding){
			$subject = mb_convert_encoding($subject, $toencoding, $fromencoding);
			$subject = $this->mb_str_replace($search, $replace, $subject, $toencoding);
			return $subject;
	}

	/*******************************************************/
	/**
	* マルチバイト対応 str_replace()
	 * @name mb_str_replace
	 * @param mixed $search 検索文字列（またはその配列）
	 * @param mixed $replace 置換文字列（またはその配列）
	 * @param mixed $subject 対象文字列（またはその配列）
	 * @param string $encoding 文字列のエンコーディング(省略: 内部エンコーディング)
	 * @return mixed subject 内の search を replace で置き換えた文字列
 	 * この関数の $search, $replace, $subject は配列に対応していますが、
	 * $search, $replace が配列の場合の挙動が PHP 標準の str_replace() と異なります。
 	 * http://fetus.k-hsu.net/document/programming/php/mb_str_replace.html　からいただきました。
	*/
	function mb_str_replace($search, $replace, $subject, $encoding = 'auto') {
		if(!is_array($search)) {
			$search = array($search);
		}
		if(!is_array($replace)) {
			$replace = array($replace);
		}
		if(strtolower($encoding) === 'auto') {
			$encoding = mb_internal_encoding();
		}

		// $subject が複数ならば各要素に繰り返し適用する
		if(is_array($subject) || $subject instanceof Traversable) {
			$result = array();
			foreach($subject as $key => $val) {
				$result[$key] = mb_str_replace($search, $replace, $val, $encoding);
			}
			return $result;
		}

		$currentpos = 0; // 現在の検索開始位置
		while(true) {
			// $currentpos 以降で $search のいずれかが現れる位置を検索する
			$index = -1; // 見つけた文字列（最も前にあるもの）の $search の index
			$minpos = -1; // 見つけた文字列（最も前にあるもの）の位置
			foreach($search as $key => $find) {
				if($find == '') {
					continue;
				}
				$findpos = mb_strpos($subject, $find, $currentpos, $encoding);
				if($findpos !== false) {
					if($minpos < 0 || $findpos < $minpos) {
						$minpos = $findpos;
						$index = $key;
					}
				}
			}

			// $search のいずれも見つからなければ終了
			if($minpos < 0) {
				break;
			}

			// 置換実行
			$r = array_key_exists($index, $replace) ? $replace[$index] : '';
			$subject =
				mb_substr($subject, 0, $minpos, $encoding) . // 置換開始位置より前
				$r . // 置換後文字列
				mb_substr( // 置換終了位置より後ろ
					$subject,
					$minpos + mb_strlen($search[$index], $encoding),
					mb_strlen($subject, $encoding),
					$encoding);

			// 「現在位置」を $r の直後に設定
			$currentpos = $minpos + mb_strlen($r, $encoding);
		}
		return $subject;
	}

	/*******************************************************/
	/**
	 * bmp用の調整
	 * @name _imagecreatefrombmp
	 * @param $filename 対象ファイル
	 */
	public function imagecreatefrombmp($filename) {
		//Ouverture du fichier en mode binaire
		if (! $f1 = fopen($filename,"rb")) return FALSE;

		//1 : Chargement des ent�tes FICHIER
		$FILE = unpack("vfile_type/Vfile_size/Vreserved/Vbitmap_offset", fread($f1,14));
		if ($FILE['file_type'] != 19778) return FALSE;

		//2 : Chargement des ent�tes BMP
		$BMP = unpack('Vheader_size/Vwidth/Vheight/vplanes/vbits_per_pixel'.
				'/Vcompression/Vsize_bitmap/Vhoriz_resolution'.
				'/Vvert_resolution/Vcolors_used/Vcolors_important', fread($f1,40));
		$BMP['colors'] = pow(2,$BMP['bits_per_pixel']);
		if ($BMP['size_bitmap'] == 0) $BMP['size_bitmap'] = $FILE['file_size'] - $FILE['bitmap_offset'];
		$BMP['bytes_per_pixel'] = $BMP['bits_per_pixel']/8;
		$BMP['bytes_per_pixel2'] = ceil($BMP['bytes_per_pixel']);
		$BMP['decal'] = ($BMP['width']*$BMP['bytes_per_pixel']/4);
		$BMP['decal'] -= floor($BMP['width']*$BMP['bytes_per_pixel']/4);
		$BMP['decal'] = 4-(4*$BMP['decal']);
		if ($BMP['decal'] == 4) $BMP['decal'] = 0;

		//3 : Chargement des couleurs de la palette
		$PALETTE = array();
		if ($BMP['colors'] < 16777216)
		{
			$PALETTE = unpack('V'.$BMP['colors'], fread($f1,$BMP['colors']*4));
		}

		//4 : Cr�ation de l'image
		$IMG = fread($f1,$BMP['size_bitmap']);
		$VIDE = chr(0);

		$res = imagecreatetruecolor($BMP['width'],$BMP['height']);
		$P = 0;
		$Y = $BMP['height']-1;
		while ($Y >= 0)
		{
			$X=0;
			while ($X < $BMP['width'])
			{
				if ($BMP['bits_per_pixel'] == 24)
					$COLOR = unpack("V",substr($IMG,$P,3).$VIDE);
				elseif ($BMP['bits_per_pixel'] == 16)
				{
					$COLOR = unpack("n",substr($IMG,$P,2));
					$COLOR[1] = $PALETTE[$COLOR[1]+1];
				}
				elseif ($BMP['bits_per_pixel'] == 8)
				{
					$COLOR = unpack("n",$VIDE.substr($IMG,$P,1));
					$COLOR[1] = $PALETTE[$COLOR[1]+1];
				}
				elseif ($BMP['bits_per_pixel'] == 4)
				{
					$COLOR = unpack("n",$VIDE.substr($IMG,floor($P),1));
					if (($P*2)%2 == 0) $COLOR[1] = ($COLOR[1] >> 4) ; else $COLOR[1] = ($COLOR[1] & 0x0F);
					$COLOR[1] = $PALETTE[$COLOR[1]+1];
				}
				elseif ($BMP['bits_per_pixel'] == 1)
				{
					$COLOR = unpack("n",$VIDE.substr($IMG,floor($P),1));
					if     (($P*8)%8 == 0) $COLOR[1] =  $COLOR[1]        >>7;
					elseif (($P*8)%8 == 1) $COLOR[1] = ($COLOR[1] & 0x40)>>6;
					elseif (($P*8)%8 == 2) $COLOR[1] = ($COLOR[1] & 0x20)>>5;
					elseif (($P*8)%8 == 3) $COLOR[1] = ($COLOR[1] & 0x10)>>4;
					elseif (($P*8)%8 == 4) $COLOR[1] = ($COLOR[1] & 0x8)>>3;
					elseif (($P*8)%8 == 5) $COLOR[1] = ($COLOR[1] & 0x4)>>2;
					elseif (($P*8)%8 == 6) $COLOR[1] = ($COLOR[1] & 0x2)>>1;
					elseif (($P*8)%8 == 7) $COLOR[1] = ($COLOR[1] & 0x1);
					$COLOR[1] = $PALETTE[$COLOR[1]+1];
				}
				else
					return FALSE;
				imagesetpixel($res,$X,$Y,$COLOR[1]);
				$X++;
				$P += $BMP['bytes_per_pixel'];
			}
			$Y--;
			$P+=$BMP['decal'];
		}

		//Fermeture du fichier
		fclose($f1);

		return $res;
	}

	/*******************************************************/
	/**
	 * 画像保存共通処理
	 * @name set_img
	 * @param string $dir 保存ディレクトリ
	 * @param array $file $_FILES情報
	 * @param string $name_add 保存ファイル名の先頭に結合する文字列(省略可)
	 */
	public function set_img($dir, $file, $name_add="") {
		//アップロードされたファイルを保存する////////////////////	
		$ext = pathinfo($file["name"], PATHINFO_EXTENSION); //jpg,png

		//保存するファイル名(同じファイル名だと表示が変わらないからランダム名を設定) ・・　保存名は日分秒.拡張子
		$d = uniqid(mt_rand()); //date("YmdHis")

		//bmpはgifに変換
		$saveName =""; //保存ファイル名	
		if (strtolower($ext) == "bmp") $saveName = $name_add.$d.".gif"; // || strtolower($ext) == "png"
		else $saveName = $name_add.$d.".".$ext;

		//作ったディレクトリにアップロードされたファイルを保存する///
		if (is_uploaded_file($file["tmp_name"])) {			

			//ファイル移動ではなくサイズ調整後、保存パスに出力//
			// ファイル名から、画像インスタンスを生成(warningを出さないように関数の頭に@をつける
			switch (strtolower($ext)) {
				case "jpg":
				case "jpeg":
					$img = @imagecreatefromjpeg($file["tmp_name"]); break;
				case "png": $img = @imagecreatefrompng($file["tmp_name"]); break;
				case "gif": $img = @imagecreatefromgif($file["tmp_name"]); break;
				case "bmp": $img = @$this->imagecreatefrombmp($file["tmp_name"]); break; //bmpは独自関数
			}
			list($image_w, $image_h) = getimagesize($file["tmp_name"]); // コピー元画像のファイルサイズを取得

			//背景用画像のサイズ設定
			$cimage_w = $image_w;
			$cimage_h = $image_h;
			if ($image_w > 1024) {
				$cimage_w = 1024;
				$cimage_h = round($image_h / ($image_w / 1024));
			}

			// 背景画像に、画像をコピーする(背景画像, コピー元画像, 背景画像の x 座標, 背景画像の y 座標, コピー元の x 座標, コピー元の y 座標, 背景画像の幅, 背景画像の高さ, コピー元画像ファイルの幅, コピー元画像ファイルの高さ)
			$canvas = imagecreatetruecolor($cimage_w, $cimage_h); // サイズを指定して、背景用画像を生成
			imagealphablending($canvas,false); //背景画像を透過させる
			imagesavealpha($canvas,true);
			$fillcolor = imagecolorallocatealpha($canvas,0,0,0,127);
			imagefill($canvas,0,0,$fillcolor);
			imagecopyresampled($canvas, $img, 0, 0, 0, 0, $cimage_w, $cimage_h, $image_w, $image_h);

			// 画像を出力する(背景画像, 出力するファイル名（省略すると画面に表示する）, 画像精度（この例だと100%で作成 jpgの場合のみ）)

			switch (strtolower($ext)) {
				case "jpg":
				case "jpeg":
					//case "png": //bmpはjpgに変換
					$ret = imagejpeg($canvas, $dir.$saveName, 100);
					break;
				case "png": $ret = imagepng($canvas, $dir. $saveName); break;
				case "bmp":
					//case "png":
				case "gif":
					$ret = imagegif($canvas, $dir.$saveName);
					break;
			}
			imagedestroy($canvas); // メモリを開放する
      chmod($dir.$saveName, 0777);

			if (!$ret){
				throw new Exception("画像アップロードに失敗しました");
			}
			//オリジナル画像の移動
			//if (!move_uploaded_file($tname, $uDir.$fDir.$aDir."/".$orgName)){
			//	throw new Exception($msg."を登録できませんでした。");
			//}
		}

		//パスをリターン
		return $saveName;
	}
	
	/*******************************************************/
	/**
	 * 古いファイル削除処理
	 * @name delete_old_file
	 * @param string $tmpPath 削除対象ディレクトリ
	 * @param string $paradate 指定日付と更新日の差が1日以上のファイルを削除(未指定時は無条件で削除)
	 * @return なし
	 */
	public function delete_old_file($tmpPath, $paradate="") {	
		try {
			//$nowdate = date("Y/m/d");
			//$tmpPath = $this->page->path_tmp();

			$res_dir = opendir($tmpPath);					
			if ($res_dir) {
				//ディレクトリ内のファイル名を１つずつを取得
				while(false !== ($file_name = readdir($res_dir))){
					//ファイルかどうか(flashonlinereportフォルダは無視)
					if (is_file($tmpPath.$file_name)) {
						if ($file_name != "." && $file_name != ".." && substr($file_name, -4) != ".svn") {
							$filemdate_unix = filemtime($tmpPath.$file_name);
							$filemdate = date("Y/m/d",$filemdate_unix);

							if ($paradate != "") {
								$daydiff = (strtotime($paradate)-strtotime($filemdate))/(3600*24);
							} else {
								$daydiff = 1;
							}						

							if ($daydiff >= 1) {
								try {
									unlink($tmpPath.$file_name);
								} catch (Exception $e) {
								}
							}
						}
					}
				}				

				//ディレクトリ・ハンドルをクローズ
				closedir($res_dir);
			}			
		} catch (Exception $e) {
		}
	}
}
