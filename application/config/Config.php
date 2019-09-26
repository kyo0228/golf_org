<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * -globalphp- globalPHP基本フレームワーク php5.3以上～。未検証のphpバージョンは必ずチェックする亊！
 */

/********************************************************
/**
 * アプリ独自の設定を追加する場合は[$user]に項目を追加してください
 * sys    : アプリ全体の設定ファイル
 * db     : DB接続情報
 * route  : アプリケーションのURL情報
 * user		: アプリケーション独自の設定情報
 */

$user = null;


/********************************************************
/**
 * アプリケーションの名前を指定します。headタグのtitleデフォルト値です
 * @name apl_name
 */
$sys['apl_name'] = "GOLF";

/********************************************************
/**
 * アプリケーションのバージョンを指定します。どこかで使うかもしれません
 * @name apl_name
 */
$sys['apl_version'] = "1.2.5";

/********************************************************
/**
 * 
 * trueの場合はできるだけエラー内容をページに表示します。本番環境ではfalseにしてください
 * @name use_debug
 */
$sys['use_debugmode'] = false;

/********************************************************
/**
 * 
 * trueの場合はページの最後に保持しているアイテムをvar_dumpで出力します。本番ではfalseにしてください
 * @name use_develop
 */
$sys['use_devmode'] = true;

/********************************************************
/**
 * 
 * データベースを使わないサイトの場合はfalseに設定。使う場合は$dbに接続情報を記載
 * @name use_database
 */
$sys['use_database'] = true;


/********************************************************
/**
 * 
 * 通常使う接続情報名は[default]にしてください。
 * 接続先が複数ある場合は新しい配列の項目名を追加してください。
 * @name $db
 * 
 * いまのところdriverはpdoとmysqliです
 */

$db['default'] = array(
	'dsn'	=> '',
	'hostname' => 'localhost',
	'username' => 'root',
	'password' => 'megaotowa',
	'database' => 'golf',
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci'
);


/********************************************************
/**
 * 
 * システム標準の言語を指定します。これは将来機能のための予約です
 * @name language
 */
$sys['language'] = 'japanese';

/********************************************************
/**
 * 
 * logフォルダを指定します。未指定の場合はapplicationと同列にlogフォルダが生成されます
 * @name log_path
 */
$sys['log_path'] = '';

/********************************************************
/**
 * 
 * 暗号化用のキー文字列を設定します。32文字のランダム文字を指定してください
 * @name encryption_key
 */
$sys['encryption_key'] = 'bf7fec2f296cfb832ca3b8464ccdc29e';

/********************************************************
/**
 * 
 * セッション名を指定します。クッキーの保存名となります。
 * demoサイトが同じドメイン内の場合は別の文字にした方がよいです。
 * セッションを使用しない場合は空にしてください。
 * @name session_name
 */
$sys['session_name'] = 'golf_sess';

/********************************************************
/**
 * 
 * セッションの有効期限はphpの設定に準拠しています。
 * セッションの名前空間を使用している場合は名前空間毎に有効期限をチェックできるようにしています。
 * 有効期間を設定したい場合はtrueを指定してください。
 * @name use_session_ns_lifetime
 */
$sys['use_session_ns_lifetime'] = true;

/********************************************************
/**
 * 「use_session_ns_lifetime」が有効な場合の有効期限を秒で指定します。
 * 例1440(24分※phpのデフォルト)、14400(4時間)、43200(12時間)、86400(24時間)
 * @name session_ns_lifetime
 * 
 */
$sys['session_ns_lifetime']	= 28800;


/********************************************************
/**
 * 
 * カスタマイズ機能を利用します。 2017/03/16追加
 * globalphpで作成したアプリケーションをカスタマイズして別途提供する場合に利用します。
 * 継承させることで元のアプリケーションの機能や関数はそのまま、必要な関数のみ修正することができます。
 * @name use_customize
 * 
 */
$sys['use_customize'] = false;

/********************************************************
/**
 * 
 * カスタマイズ機能を利用する場合の接頭語です。
 * 接頭語は半角英の小文字で設定してください。文字数制限はありませんが2～5文字程度としてください。
 * 
 * 接頭語は「customize」フォルダ内のフォルダと継承、追加するクラス名に使用します。
 * ・「customize」フォルダ内の拡張用フォルダ名は小文字(例:demo)
 * ・追加するファイル名やクラス名は先頭大文字(例:Demo_)
 * 接頭語の先頭に[gc]、[my]は使わないでください。
 * 
 * @name customize_prefix
 */
$sys['customize_prefix'] = "";




/********************************************************
/**
 * 
 * アプリケーションの基本URL（ドメイン名）を設定します。アプリトップのindex.phpが配置されているパスを選択してください
 * 先頭が//だとhttpとhttpsは自動で判定します。「//your_domain.co.jp」のように記載してください
 * @name base_url
 */
$route['base_url']	= '//golf.localhost';

/********************************************************
/**
 * URLから自動でcontrollerを指定します。
 * @name use_automode
 * 
 */
$route['use_automode']	= true;


/********************************************************
/**
 * 基本コントローラを指定します。
 * @name base_controller
 * 
 */
$route['base_controller'] = 'Login';


/********************************************************
/**
 * 
 * page名の最後に拡張子を使用します。例：php,html
 * @name url_suffix
 * 
 */
$route['url_suffix'] = '';

/********************************************************
/**
 * indexのみ使用するコントローラを設定します。
 * ここで指定されたコントローラはindexページしか使えない代わりにコントローラの次にパラメータが使用できます。
 * 
 * 例：http://yourdomain.jp/controller/args1/args2 ←ページ名を省略できる
 * 
 * @name index_only_controller
 * 
 */
$route['index_only_controller'] = array(
	'Error'
);

/********************************************************
/**
 * 参照するcontorollerを手動で設定します。base_urlに続くurlのコントローラ部とapl/controller以下のcontrollerパスをペアで記載
 * 
 * 例：url:admin →Admin[Contoroller]
 *     url:system → Sys[Controller]のように手動だとurlと別のコントローラ名もOK
 *     url:system/main → Sysmain[Controller]もあり
 *     urlの最後はスラッシュつけない亊！
 * 
 * @name manual_route
 * 
 */
$route['manual_route'] = array(
	'admin'	=> 'Admin',
	'login' => 'Login',
	'main' => 'Main',
	'system/main' => 'Sysmain'
);

/********************************************************
/**
 * urlのコントローラ部を取り除きurl要素を一段ずらします。
 * (2016/04機能追加)
 * 
 * 通常、URLが http://yourdomain.jp/sp/page/args ならばシステムは
 * コントローラはsp,ページはpageと判断します。
 * しかし「remove_route」に"sp"と設定するとspは取り除かれるので
 * コントローラはpage,ページはargsになります。
 * 
 * この機能を使うとpcサイトとspサイトでURLが一段変わる場合でもコントローラをわけなくてよくなります
 * また、管理サイトでよく使う「admin」もここで除くといままでの管理サイトっぽく使えます。
 * 
 * @name remove_route
 * 
 */
$route['remove_route'] = array(
	"sp",
	"admin"
);

/********************************************************
/**
 * メンテナンスモードを有効にするかどうか
 * trueにするとメンテナンスモードとなります。
 * メンテナンスモードになるとthrough_mainte_ip以外のIPアドレスからのアクセスの場合、mente用ページを表示します
 * @name use_mainte_mode
 * 
 */
$route['use_mainte_mode'] = false;

/********************************************************
/**
 * システムに組み込みのメンテナンスページで表示するフリーメッセージです。
 * 例えば終了予定時刻など記載するとよいかもです
 * @name mainte_message
 * 
 */
$route['mainte_message'] = "終了予定時刻：2:00頃";

/********************************************************
/**
 * メンテナンスモード有効の場合のアクセスを許可するIPアドレスをセットします。
 * 指定されたIP以外のアクセスはすべてメンテナンス用ページが表示されます。
 * $_SERVER["REMOTE_ADDR"]をみて判定しています

 * @name mainte_through_ip
 */
$route['mainte_through_ip'] = array(
	'127.0.0.2',
	'61.206.118.40'
);

/********************************************************
/**
 * メンテナンスモード有効の時にリダイレクトさせたいページがある場合はURLを指定してください
 * (//domain/page)のような短縮を使ってもOK。現在アクセスがあったプロトコルを自動付与します。
 * 自分のドメインにリダイレクトさせる場合はmainte_redirect_controllerに
 * リダイレクトさせたいコントローラ名をセットしないと無限ループになります
 * 
 * 例：
 * $route['base_url']."/error/xxxx"	←自分のerrorコントローラ、xxxxページがリダイレクト先の場合はmainte_redirect_controllerにerrorと書く
 * $route['base_url']."/pages/error.html"	←自分のドメイン内でもcontrollerで管理していないページであればURLだけでOK。
 * http://www.ather_domein.jp/xxxx"	←別サイトにリダイレクトさせる場合もURLだけでOK
 * 
 * @name mainte_redirect_url
 */
$route['mainte_redirect_url'] = "";

/********************************************************
/**
 * メンテナンスモード有効の時にリダイレクトさせたいページが自分のページであればコントローラ名をセット。
 * 別サイトのURLであれば必要ありません
 * 
 * 例：自分のErrorコントローラにリダイレクトさせたい場合は「Error」
 * 
 * @name mainte_redirect_controller
 */
$route['mainte_redirect_controller'] = "";


/********************************************************
/**
 * デバイス別オートリダイレクト機能を使うかどうか。
 * trueにするとURL要素とユーザーエージェントから転送処理を行います
 * 
 * @name use_device_redirect
 * 
 */
$route['use_device_redirect'] = false;

/********************************************************
/**
 * スマホの場合のオートリダイレクトURLを設定します
 * (//domain/page)のような短縮を使ってもOK。現在アクセスがあったプロトコルを自動付与します。
 * 自分のドメインにリダイレクトさせる場合はmainte_redirect_controllerに
 * リダイレクトさせたいコントローラ名をセットしないと無限ループになります
 * 
  * 例：
 * $route['base_url']."/sp"	←自分のspコントローラindexにリダイレクトさせたい場合はdevice_redirect_controller_spにspと書く
 * $route['base_url']."/pages/sp.php"	←自分のドメイン内でもcontrollerで管理していないページであればURLだけでOK。
 * http://www.ather_domein.jp/xxxx"	←別サイトにリダイレクトさせる場合もURLだけでOK
 * 
 * @name device_redirect_url_sp
 * 
 */
$route['device_redirect_url_sp'] = $route['base_url']."/sp";

/********************************************************
/**
 * fpはガラケー,tbはタブレット。設定はスマホと同様
 */
$route['device_redirect_url_fp'] = $route['base_url']."/fb";
$route['device_redirect_url_tb'] = $route['base_url']."/tb";


/********************************************************
/**
 * スマホのオートリダイレクトページが自分のページであればコントローラ名をセット。
 * 別サイトのURLであれば必要ありません
 * 
 * 例：自分のErrorコントローラにリダイレクトさせたい場合は「Error」
 * 
 * @name device_redirect_controller_sp
 */
$route['device_redirect_controller_sp'] = "Sp";

/********************************************************
/**
 * コントローラ名のルールもfp,tbの設定はスマホと同様
 */

$route['device_redirect_controller_fp'] = "Fp";
$route['device_redirect_controller_tb'] = "Tb";

/********************************************************
/**
 * タブレットの場合のみスマホと扱うかPCページと扱うかを選ぶことができます
 * 
 * tbと指定した場合：タブレット設定独立(上記_tbにタブレット専用URLなどをセット)
 * spと指定した場合：スマホと同じ扱い(上記_spの設定を使用)
 * pcと指定した場合：デバイスオートリダイレクトは使われない(pcページと判断)
 * 
 * @name device_redirect_tablet_mode
 */
$route['device_redirect_tablet_mode'] = "sp";

/********************************************************
/**
 * デバイスリダイレクトを無視するワードを設定できます。
 * 
 * デバイスリダイレクトを有効にした場合でもURLに特定のキーワードをセットする亊で
 * リダイレクトを無視することができます。
 * 
 * @name device_redirect_ignore_word
 */
$route['device_redirect_ignore_word'] = array(
		"preview"
);
