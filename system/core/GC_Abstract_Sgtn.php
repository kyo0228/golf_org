<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*******************************************************/
/**
 * Singleton実装用の共通継承元スーパークラス。
 * @name GC_Abstract_Sgtn (core/GC_Abstract_Sgtn.php)
*/
abstract class GC_Abstract_Sgtn extends GC_Abstract_Base
{
	/**
	* singleton自クラスのインスタンス
	* @var	this
	*/	
	protected static $_instance = array();

	/*******************************************************/
	/**
	 * コンストラクタ。protectedにして外からnewさせない
	*/	
	protected function __construct()
	{
	}

	/*******************************************************/
	/**
	 * クラスインスタンスを返します。
	*/		
	public static function &get_instance()
	{
		$key = get_called_class();
		if(!isset(self::$_instance[$key]))
		{
				self::$_instance[$key] = new static(); //new selfだと継承元をロードしてくれない
		}
		return self::$_instance[$key];
	}

	/*******************************************************/
	/**
	 * マジックメソッドの実行を阻止
	*/		
	final public function __clone()
	{
		throw new Exception('このクラスは複製できません');
	}
}
?>