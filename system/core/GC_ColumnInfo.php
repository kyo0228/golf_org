<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*******************************************************/
/**
 * データクラス用のカラム情報保持クラス。
 * @name GC_ColumnInfo
*/
class GC_ColumnInfo {

	/**
	 * カラムの値
	 * @var any
	*/	
	public $value;	
	
	/**
	 * 自由に使えるプロパティ
	 * @var any
	*/	
	public $tag;		
	
	/**
	 * カラム名
	 * @var string 
	*/	
 public $name;
	
	/**
	 * カラムのデータ型
	 * @var string 
	*/	
	public $type;	

	/**
	 * クラス内で使用するprivate変数名。カラム名に_auto_をつける
	 * @var string 
	*/	
	public $private_name;
	
	/**
	 * カラムのデータ長さ。decimalの場合は整数桁数。text型やオブジェクト型は0をセット
	 * @var int 
	*/	
	public $length;
	
	/**
	 * カラムの有効桁数
	 * @var int 
	*/	
	public $precision;	
	
	/**
	 * カラムの小数点桁数
	 * @var int 
	*/	
	public $scale;	
	
	/**
	 * カラムのnull制約
	 * @var bool 
	*/	
	public $is_null;	
	
	/**
	 * カラムのpk制約
	 * @var string 
	*/	
	public $is_pk;	
	
	/**
	 * カラムの論理名
	 * @var string 
	*/	
	public $text;	
	

}	

?>