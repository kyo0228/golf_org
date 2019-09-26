<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * Layout.php ページテンプレートを定義します。
 * ページテンプレート(headerやメニューなど)は当フレームワークの場合パーツを用意しておきます。
 * 各パーツはapplication/view/_layoutに配置します
 * 
 * 例：
 * default_template.php ・・・ページの枠を定義するファイル。このファイルのみ[_template]という名前にしてください
 * page_header.php
 * left_menu.php
 * right_contents.php   ・・・その他各パーツです。こちらに命名ルールはありません。
 * footer.php
 * 
 * この組み合わせの場合は配列の名前を「default」にします。
 * このlayoutの機能を使う場合は一番メインのレイアウトグループ名を「default」にしてください
 * 
 * テンプレートファイルに各パーツを配置したい場所へ 
 *   <?= $this->layout("page_header"); ?>
 * 
 * のように記載するとテンプレートに各パーツが配置されます
 * 
 * コンテンツ部(view本体)は以下のように空にします
 * <?= $this->layout(); ?>
 * 
 */

$layout['default'] = array(
);

$layout['login'] = array(
);

/*
 * ページを表示する場合は各コントローラのxx_action関数から以下のように呼び出します
 * $this->page->show(); 省略の場合はxxxx_actionに対応するviewとdefault_templateが自動でセットされます
 * $this->page->show("other"); 第1引数にレイアウトグループ名をセットするとdefault以外のテンプレートを呼びます
 * $this->page->show("other","other_view"); 第2引数は同一コントローラの別ページを表示することもできます（viewの作りや変数が対応していれば・・）
 * 
 * 
 */

