<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//require_once SYSPATH.'core/GC_ColumnInfo.php';

/*************************************************************************/
/**
 * @name クラス：M_CorpStoreテーブル用データクラス[キー: 'StoreID']
*/
class DC_M_CorpStore extends GC_DB{

	//クラス変数
	/**
	 * このテーブルにキーが存在するかどうか。true:存在する。存在しない場合取得、更新は行えません
	 * @var bool 
	*/	
	public $exists_key = true;

	/**
	 * int,decimal型の数字0チェックを行うかどうか。true:行う(既定値),false:チェックしない
	 * @var bool 
	*/	
	public $check_num_zero = true;

	/**
	 * このテーブルにキーが存在する場合にキーカラム名を配列でセットします
	 * @var array 
	*/	
	public $KeyString = array('StoreID');

	/**
	 * 作成日付、作成者等の情報を自動でセットするかどうか。true:自動(既定値),false:手動
	 * @var bool 
	*/	
	public $use_auto_editor = true;

	/**
	 * 作成者、更新者のカラムに自動でセットする更新者情報を設定します。use_auto_editor=TRUEの時にセット
	 * @var string 
	*/	
	public $editor = "";


	//カラム名変数
	/**
	 * PK=true NULL=false
	 * @name StoreID
	 * @var decimal
	*/
	public $StoreID;

	/**
	 * StoreIDのカラム情報保持
	 * @var GC_ColumnInfo
	*/
	private $_StoreID;

	/**
	 * CorpID PK=false NULL=false 説明=
	 * @var decimal
	*/
	public $CorpID;

	/**
	 * CorpIDのカラム情報保持
	 * @var GC_ColumnInfo
	*/
	private $_CorpID;

	/**
	 * StoreCode PK=false NULL=false 説明=
	 * @var varchar
	*/
	public $StoreCode;

	/**
	 * StoreCodeのカラム情報保持
	 * @var GC_ColumnInfo
	*/
	private $_StoreCode;

	/**
	 * StoreName PK=false NULL=false 説明=
	 * @var varchar
	*/
	public $StoreName;

	/**
	 * StoreNameのカラム情報保持
	 * @var GC_ColumnInfo
	*/
	private $_StoreName;

	/**
	 * StoreSName PK=false NULL=true 説明=
	 * @var varchar
	*/
	public $StoreSName;

	/**
	 * StoreSNameのカラム情報保持
	 * @var GC_ColumnInfo
	*/
	private $_StoreSName;

	/**
	 * StoreShortName PK=false NULL=true 説明=
	 * @var varchar
	*/
	public $StoreShortName;

	/**
	 * StoreShortNameのカラム情報保持
	 * @var GC_ColumnInfo
	*/
	private $_StoreShortName;

	/**
	 * ZipCode1 PK=false NULL=true 説明=
	 * @var varchar
	*/
	public $ZipCode1;

	/**
	 * ZipCode1のカラム情報保持
	 * @var GC_ColumnInfo
	*/
	private $_ZipCode1;

	/**
	 * ZipCode2 PK=false NULL=true 説明=
	 * @var varchar
	*/
	public $ZipCode2;

	/**
	 * ZipCode2のカラム情報保持
	 * @var GC_ColumnInfo
	*/
	private $_ZipCode2;

	/**
	 * Prefecture PK=false NULL=true 説明=
	 * @var varchar
	*/
	public $Prefecture;

	/**
	 * Prefectureのカラム情報保持
	 * @var GC_ColumnInfo
	*/
	private $_Prefecture;

	/**
	 * City PK=false NULL=true 説明=
	 * @var varchar
	*/
	public $City;

	/**
	 * Cityのカラム情報保持
	 * @var GC_ColumnInfo
	*/
	private $_City;

	/**
	 * Area PK=false NULL=true 説明=
	 * @var varchar
	*/
	public $Area;

	/**
	 * Areaのカラム情報保持
	 * @var GC_ColumnInfo
	*/
	private $_Area;

	/**
	 * Adrs1 PK=false NULL=true 説明=
	 * @var varchar
	*/
	public $Adrs1;

	/**
	 * Adrs1のカラム情報保持
	 * @var GC_ColumnInfo
	*/
	private $_Adrs1;

	/**
	 * Adrs2 PK=false NULL=true 説明=
	 * @var varchar
	*/
	public $Adrs2;

	/**
	 * Adrs2のカラム情報保持
	 * @var GC_ColumnInfo
	*/
	private $_Adrs2;

	/**
	 * Tel1 PK=false NULL=true 説明=
	 * @var varchar
	*/
	public $Tel1;

	/**
	 * Tel1のカラム情報保持
	 * @var GC_ColumnInfo
	*/
	private $_Tel1;

	/**
	 * Tel2 PK=false NULL=true 説明=
	 * @var varchar
	*/
	public $Tel2;

	/**
	 * Tel2のカラム情報保持
	 * @var GC_ColumnInfo
	*/
	private $_Tel2;

	/**
	 * Fax PK=false NULL=true 説明=
	 * @var varchar
	*/
	public $Fax;

	/**
	 * Faxのカラム情報保持
	 * @var GC_ColumnInfo
	*/
	private $_Fax;

	/**
	 * Web PK=false NULL=true 説明=
	 * @var varchar
	*/
	public $Web;

	/**
	 * Webのカラム情報保持
	 * @var GC_ColumnInfo
	*/
	private $_Web;

	/**
	 * Mail PK=false NULL=true 説明=
	 * @var varchar
	*/
	public $Mail;

	/**
	 * Mailのカラム情報保持
	 * @var GC_ColumnInfo
	*/
	private $_Mail;

	/**
	 * Memo PK=false NULL=true 説明=
	 * @var varchar
	*/
	public $Memo;

	/**
	 * Memoのカラム情報保持
	 * @var GC_ColumnInfo
	*/
	private $_Memo;

	/**
	 * GroupMarkDiv PK=false NULL=true 説明=
	 * @var decimal
	*/
	public $GroupMarkDiv;

	/**
	 * GroupMarkDivのカラム情報保持
	 * @var GC_ColumnInfo
	*/
	private $_GroupMarkDiv;

	/**
	 * AiriaFactoryDiv PK=false NULL=true 説明=
	 * @var decimal
	*/
	public $AiriaFactoryDiv;

	/**
	 * AiriaFactoryDivのカラム情報保持
	 * @var GC_ColumnInfo
	*/
	private $_AiriaFactoryDiv;

	/**
	 * Manager PK=false NULL=true 説明=
	 * @var varchar
	*/
	public $Manager;

	/**
	 * Managerのカラム情報保持
	 * @var GC_ColumnInfo
	*/
	private $_Manager;

	/**
	 * Post PK=false NULL=true 説明=
	 * @var varchar
	*/
	public $Post;

	/**
	 * Postのカラム情報保持
	 * @var GC_ColumnInfo
	*/
	private $_Post;

	/**
	 * SGStartDate PK=false NULL=true 説明=ショットガン使用開始日
	 * @var datetime
	*/
	public $SGStartDate;

	/**
	 * SGStartDateのカラム情報保持
	 * @var GC_ColumnInfo
	*/
	private $_SGStartDate;

	/**
	 * SGEndDate PK=false NULL=true 説明=ショットガン使用終了日
	 * @var datetime
	*/
	public $SGEndDate;

	/**
	 * SGEndDateのカラム情報保持
	 * @var GC_ColumnInfo
	*/
	private $_SGEndDate;

	/**
	 * InvalidFlg PK=false NULL=true 説明=
	 * @var tinyint
	*/
	public $InvalidFlg;

	/**
	 * InvalidFlgのカラム情報保持
	 * @var GC_ColumnInfo
	*/
	private $_InvalidFlg;

	/**
	 * ProcFlg PK=false NULL=true 説明=
	 * @var tinyint
	*/
	public $ProcFlg;

	/**
	 * ProcFlgのカラム情報保持
	 * @var GC_ColumnInfo
	*/
	private $_ProcFlg;

	/**
	 * InsDate PK=false NULL=true 説明=
	 * @var datetime
	*/
	public $InsDate;

	/**
	 * InsDateのカラム情報保持
	 * @var GC_ColumnInfo
	*/
	private $_InsDate;

	/**
	 * InsStaffID PK=false NULL=true 説明=
	 * @var decimal
	*/
	public $InsStaffID;

	/**
	 * InsStaffIDのカラム情報保持
	 * @var GC_ColumnInfo
	*/
	private $_InsStaffID;

	/**
	 * UpdDate PK=false NULL=true 説明=
	 * @var datetime
	*/
	public $UpdDate;

	/**
	 * UpdDateのカラム情報保持
	 * @var GC_ColumnInfo
	*/
	private $_UpdDate;

	/**
	 * UpdStaffID PK=false NULL=true 説明=
	 * @var decimal
	*/
	public $UpdStaffID;

	/**
	 * UpdStaffIDのカラム情報保持
	 * @var GC_ColumnInfo
	*/
	private $_UpdStaffID;


	/*************************************************************************/
	/**
	 * @name コンストラクタ
	*/
	public function __construct() {
		parent::__construct();
		$this->_init_class();
	}


	/*************************************************************************/
	/**
	 * カラムの情報を取得します
	 * @name get_colinfo
	 * @param string $colname カラム名
	 * @return GC_ColumnInfo
	*/
	public function get_colinfo($colname) {
		$colname = "_".$colname;
		return $this->colname;
	}


	/*************************************************************************/
	/**
	 * クラス内変数の初期化
	 * @name _init_class
	*/
	private function _init_class() {
		//StoreID
		$this->_StoreID = new GC_ColumnInfo();
		$this->_StoreID->tag = null;
		$this->_StoreID->name = "StoreID";
		$this->_StoreID->type = "decimal";
		$this->_StoreID->private_name;
		$this->_StoreID->length = "10";
		$this->_StoreID->precision = "10";
		$this->_StoreID->scale = "0";
		$this->_StoreID->is_null = false;
		$this->_StoreID->is_pk = true;
		$this->_StoreID->text = "";

		//CorpID
		$this->_CorpID = new GC_ColumnInfo();
		$this->_CorpID->tag = null;
		$this->_CorpID->name = "CorpID";
		$this->_CorpID->type = "decimal";
		$this->_CorpID->private_name;
		$this->_CorpID->length = "10";
		$this->_CorpID->precision = "10";
		$this->_CorpID->scale = "0";
		$this->_CorpID->is_null = false;
		$this->_CorpID->is_pk = false;
		$this->_CorpID->text = "";

		//StoreCode
		$this->_StoreCode = new GC_ColumnInfo();
		$this->_StoreCode->tag = null;
		$this->_StoreCode->name = "StoreCode";
		$this->_StoreCode->type = "varchar";
		$this->_StoreCode->private_name;
		$this->_StoreCode->length = "3";
		$this->_StoreCode->precision = "3";
		$this->_StoreCode->scale = "0";
		$this->_StoreCode->is_null = false;
		$this->_StoreCode->is_pk = false;
		$this->_StoreCode->text = "";

		//StoreName
		$this->_StoreName = new GC_ColumnInfo();
		$this->_StoreName->tag = null;
		$this->_StoreName->name = "StoreName";
		$this->_StoreName->type = "varchar";
		$this->_StoreName->private_name;
		$this->_StoreName->length = "30";
		$this->_StoreName->precision = "30";
		$this->_StoreName->scale = "0";
		$this->_StoreName->is_null = false;
		$this->_StoreName->is_pk = false;
		$this->_StoreName->text = "";

		//StoreSName
		$this->_StoreSName = new GC_ColumnInfo();
		$this->_StoreSName->tag = null;
		$this->_StoreSName->name = "StoreSName";
		$this->_StoreSName->type = "varchar";
		$this->_StoreSName->private_name;
		$this->_StoreSName->length = "30";
		$this->_StoreSName->precision = "30";
		$this->_StoreSName->scale = "0";
		$this->_StoreSName->is_null = true;
		$this->_StoreSName->is_pk = false;
		$this->_StoreSName->text = "";

		//StoreShortName
		$this->_StoreShortName = new GC_ColumnInfo();
		$this->_StoreShortName->tag = null;
		$this->_StoreShortName->name = "StoreShortName";
		$this->_StoreShortName->type = "varchar";
		$this->_StoreShortName->private_name;
		$this->_StoreShortName->length = "10";
		$this->_StoreShortName->precision = "10";
		$this->_StoreShortName->scale = "0";
		$this->_StoreShortName->is_null = true;
		$this->_StoreShortName->is_pk = false;
		$this->_StoreShortName->text = "";

		//ZipCode1
		$this->_ZipCode1 = new GC_ColumnInfo();
		$this->_ZipCode1->tag = null;
		$this->_ZipCode1->name = "ZipCode1";
		$this->_ZipCode1->type = "varchar";
		$this->_ZipCode1->private_name;
		$this->_ZipCode1->length = "3";
		$this->_ZipCode1->precision = "3";
		$this->_ZipCode1->scale = "0";
		$this->_ZipCode1->is_null = true;
		$this->_ZipCode1->is_pk = false;
		$this->_ZipCode1->text = "";

		//ZipCode2
		$this->_ZipCode2 = new GC_ColumnInfo();
		$this->_ZipCode2->tag = null;
		$this->_ZipCode2->name = "ZipCode2";
		$this->_ZipCode2->type = "varchar";
		$this->_ZipCode2->private_name;
		$this->_ZipCode2->length = "4";
		$this->_ZipCode2->precision = "4";
		$this->_ZipCode2->scale = "0";
		$this->_ZipCode2->is_null = true;
		$this->_ZipCode2->is_pk = false;
		$this->_ZipCode2->text = "";

		//Prefecture
		$this->_Prefecture = new GC_ColumnInfo();
		$this->_Prefecture->tag = null;
		$this->_Prefecture->name = "Prefecture";
		$this->_Prefecture->type = "varchar";
		$this->_Prefecture->private_name;
		$this->_Prefecture->length = "5";
		$this->_Prefecture->precision = "5";
		$this->_Prefecture->scale = "0";
		$this->_Prefecture->is_null = true;
		$this->_Prefecture->is_pk = false;
		$this->_Prefecture->text = "";

		//City
		$this->_City = new GC_ColumnInfo();
		$this->_City->tag = null;
		$this->_City->name = "City";
		$this->_City->type = "varchar";
		$this->_City->private_name;
		$this->_City->length = "50";
		$this->_City->precision = "50";
		$this->_City->scale = "0";
		$this->_City->is_null = true;
		$this->_City->is_pk = false;
		$this->_City->text = "";

		//Area
		$this->_Area = new GC_ColumnInfo();
		$this->_Area->tag = null;
		$this->_Area->name = "Area";
		$this->_Area->type = "varchar";
		$this->_Area->private_name;
		$this->_Area->length = "50";
		$this->_Area->precision = "50";
		$this->_Area->scale = "0";
		$this->_Area->is_null = true;
		$this->_Area->is_pk = false;
		$this->_Area->text = "";

		//Adrs1
		$this->_Adrs1 = new GC_ColumnInfo();
		$this->_Adrs1->tag = null;
		$this->_Adrs1->name = "Adrs1";
		$this->_Adrs1->type = "varchar";
		$this->_Adrs1->private_name;
		$this->_Adrs1->length = "50";
		$this->_Adrs1->precision = "50";
		$this->_Adrs1->scale = "0";
		$this->_Adrs1->is_null = true;
		$this->_Adrs1->is_pk = false;
		$this->_Adrs1->text = "";

		//Adrs2
		$this->_Adrs2 = new GC_ColumnInfo();
		$this->_Adrs2->tag = null;
		$this->_Adrs2->name = "Adrs2";
		$this->_Adrs2->type = "varchar";
		$this->_Adrs2->private_name;
		$this->_Adrs2->length = "50";
		$this->_Adrs2->precision = "50";
		$this->_Adrs2->scale = "0";
		$this->_Adrs2->is_null = true;
		$this->_Adrs2->is_pk = false;
		$this->_Adrs2->text = "";

		//Tel1
		$this->_Tel1 = new GC_ColumnInfo();
		$this->_Tel1->tag = null;
		$this->_Tel1->name = "Tel1";
		$this->_Tel1->type = "varchar";
		$this->_Tel1->private_name;
		$this->_Tel1->length = "15";
		$this->_Tel1->precision = "15";
		$this->_Tel1->scale = "0";
		$this->_Tel1->is_null = true;
		$this->_Tel1->is_pk = false;
		$this->_Tel1->text = "";

		//Tel2
		$this->_Tel2 = new GC_ColumnInfo();
		$this->_Tel2->tag = null;
		$this->_Tel2->name = "Tel2";
		$this->_Tel2->type = "varchar";
		$this->_Tel2->private_name;
		$this->_Tel2->length = "15";
		$this->_Tel2->precision = "15";
		$this->_Tel2->scale = "0";
		$this->_Tel2->is_null = true;
		$this->_Tel2->is_pk = false;
		$this->_Tel2->text = "";

		//Fax
		$this->_Fax = new GC_ColumnInfo();
		$this->_Fax->tag = null;
		$this->_Fax->name = "Fax";
		$this->_Fax->type = "varchar";
		$this->_Fax->private_name;
		$this->_Fax->length = "15";
		$this->_Fax->precision = "15";
		$this->_Fax->scale = "0";
		$this->_Fax->is_null = true;
		$this->_Fax->is_pk = false;
		$this->_Fax->text = "";

		//Web
		$this->_Web = new GC_ColumnInfo();
		$this->_Web->tag = null;
		$this->_Web->name = "Web";
		$this->_Web->type = "varchar";
		$this->_Web->private_name;
		$this->_Web->length = "100";
		$this->_Web->precision = "100";
		$this->_Web->scale = "0";
		$this->_Web->is_null = true;
		$this->_Web->is_pk = false;
		$this->_Web->text = "";

		//Mail
		$this->_Mail = new GC_ColumnInfo();
		$this->_Mail->tag = null;
		$this->_Mail->name = "Mail";
		$this->_Mail->type = "varchar";
		$this->_Mail->private_name;
		$this->_Mail->length = "100";
		$this->_Mail->precision = "100";
		$this->_Mail->scale = "0";
		$this->_Mail->is_null = true;
		$this->_Mail->is_pk = false;
		$this->_Mail->text = "";

		//Memo
		$this->_Memo = new GC_ColumnInfo();
		$this->_Memo->tag = null;
		$this->_Memo->name = "Memo";
		$this->_Memo->type = "varchar";
		$this->_Memo->private_name;
		$this->_Memo->length = "300";
		$this->_Memo->precision = "300";
		$this->_Memo->scale = "0";
		$this->_Memo->is_null = true;
		$this->_Memo->is_pk = false;
		$this->_Memo->text = "";

		//GroupMarkDiv
		$this->_GroupMarkDiv = new GC_ColumnInfo();
		$this->_GroupMarkDiv->tag = null;
		$this->_GroupMarkDiv->name = "GroupMarkDiv";
		$this->_GroupMarkDiv->type = "decimal";
		$this->_GroupMarkDiv->private_name;
		$this->_GroupMarkDiv->length = "3";
		$this->_GroupMarkDiv->precision = "3";
		$this->_GroupMarkDiv->scale = "0";
		$this->_GroupMarkDiv->is_null = true;
		$this->_GroupMarkDiv->is_pk = false;
		$this->_GroupMarkDiv->text = "";

		//AiriaFactoryDiv
		$this->_AiriaFactoryDiv = new GC_ColumnInfo();
		$this->_AiriaFactoryDiv->tag = null;
		$this->_AiriaFactoryDiv->name = "AiriaFactoryDiv";
		$this->_AiriaFactoryDiv->type = "decimal";
		$this->_AiriaFactoryDiv->private_name;
		$this->_AiriaFactoryDiv->length = "3";
		$this->_AiriaFactoryDiv->precision = "3";
		$this->_AiriaFactoryDiv->scale = "0";
		$this->_AiriaFactoryDiv->is_null = true;
		$this->_AiriaFactoryDiv->is_pk = false;
		$this->_AiriaFactoryDiv->text = "";

		//Manager
		$this->_Manager = new GC_ColumnInfo();
		$this->_Manager->tag = null;
		$this->_Manager->name = "Manager";
		$this->_Manager->type = "varchar";
		$this->_Manager->private_name;
		$this->_Manager->length = "20";
		$this->_Manager->precision = "20";
		$this->_Manager->scale = "0";
		$this->_Manager->is_null = true;
		$this->_Manager->is_pk = false;
		$this->_Manager->text = "";

		//Post
		$this->_Post = new GC_ColumnInfo();
		$this->_Post->tag = null;
		$this->_Post->name = "Post";
		$this->_Post->type = "varchar";
		$this->_Post->private_name;
		$this->_Post->length = "20";
		$this->_Post->precision = "20";
		$this->_Post->scale = "0";
		$this->_Post->is_null = true;
		$this->_Post->is_pk = false;
		$this->_Post->text = "";

		//SGStartDate
		$this->_SGStartDate = new GC_ColumnInfo();
		$this->_SGStartDate->tag = null;
		$this->_SGStartDate->name = "SGStartDate";
		$this->_SGStartDate->type = "datetime";
		$this->_SGStartDate->private_name;
		$this->_SGStartDate->length = "";
		$this->_SGStartDate->precision = "";
		$this->_SGStartDate->scale = "0";
		$this->_SGStartDate->is_null = true;
		$this->_SGStartDate->is_pk = false;
		$this->_SGStartDate->text = "ショットガン使用開始日";

		//SGEndDate
		$this->_SGEndDate = new GC_ColumnInfo();
		$this->_SGEndDate->tag = null;
		$this->_SGEndDate->name = "SGEndDate";
		$this->_SGEndDate->type = "datetime";
		$this->_SGEndDate->private_name;
		$this->_SGEndDate->length = "";
		$this->_SGEndDate->precision = "";
		$this->_SGEndDate->scale = "0";
		$this->_SGEndDate->is_null = true;
		$this->_SGEndDate->is_pk = false;
		$this->_SGEndDate->text = "ショットガン使用終了日";

		//InvalidFlg
		$this->_InvalidFlg = new GC_ColumnInfo();
		$this->_InvalidFlg->tag = null;
		$this->_InvalidFlg->name = "InvalidFlg";
		$this->_InvalidFlg->type = "tinyint";
		$this->_InvalidFlg->private_name;
		$this->_InvalidFlg->length = "";
		$this->_InvalidFlg->precision = "";
		$this->_InvalidFlg->scale = "0";
		$this->_InvalidFlg->is_null = true;
		$this->_InvalidFlg->is_pk = false;
		$this->_InvalidFlg->text = "";

		//ProcFlg
		$this->_ProcFlg = new GC_ColumnInfo();
		$this->_ProcFlg->tag = null;
		$this->_ProcFlg->name = "ProcFlg";
		$this->_ProcFlg->type = "tinyint";
		$this->_ProcFlg->private_name;
		$this->_ProcFlg->length = "";
		$this->_ProcFlg->precision = "";
		$this->_ProcFlg->scale = "0";
		$this->_ProcFlg->is_null = true;
		$this->_ProcFlg->is_pk = false;
		$this->_ProcFlg->text = "";

		//InsDate
		$this->_InsDate = new GC_ColumnInfo();
		$this->_InsDate->tag = null;
		$this->_InsDate->name = "InsDate";
		$this->_InsDate->type = "datetime";
		$this->_InsDate->private_name;
		$this->_InsDate->length = "";
		$this->_InsDate->precision = "";
		$this->_InsDate->scale = "0";
		$this->_InsDate->is_null = true;
		$this->_InsDate->is_pk = false;
		$this->_InsDate->text = "";

		//InsStaffID
		$this->_InsStaffID = new GC_ColumnInfo();
		$this->_InsStaffID->tag = null;
		$this->_InsStaffID->name = "InsStaffID";
		$this->_InsStaffID->type = "decimal";
		$this->_InsStaffID->private_name;
		$this->_InsStaffID->length = "5";
		$this->_InsStaffID->precision = "5";
		$this->_InsStaffID->scale = "0";
		$this->_InsStaffID->is_null = true;
		$this->_InsStaffID->is_pk = false;
		$this->_InsStaffID->text = "";

		//UpdDate
		$this->_UpdDate = new GC_ColumnInfo();
		$this->_UpdDate->tag = null;
		$this->_UpdDate->name = "UpdDate";
		$this->_UpdDate->type = "datetime";
		$this->_UpdDate->private_name;
		$this->_UpdDate->length = "";
		$this->_UpdDate->precision = "";
		$this->_UpdDate->scale = "0";
		$this->_UpdDate->is_null = true;
		$this->_UpdDate->is_pk = false;
		$this->_UpdDate->text = "";

		//UpdStaffID
		$this->_UpdStaffID = new GC_ColumnInfo();
		$this->_UpdStaffID->tag = null;
		$this->_UpdStaffID->name = "UpdStaffID";
		$this->_UpdStaffID->type = "decimal";
		$this->_UpdStaffID->private_name;
		$this->_UpdStaffID->length = "5";
		$this->_UpdStaffID->precision = "5";
		$this->_UpdStaffID->scale = "0";
		$this->_UpdStaffID->is_null = true;
		$this->_UpdStaffID->is_pk = false;
		$this->_UpdStaffID->text = "";

    }


	/*************************************************************************/
	/**
	 * Select文の作成
	 * @name select_string
	 * @return string select文
	*/
	public function select_string(){

		$this->_check_primary_key();

		$ret = "SELECT * FROM M_CorpStore ";
		$ret .= " WHERE StoreID = :StoreID ";

		return $ret;
	}

	/*************************************************************************/
	/**
	 * Selectの実行。キー値を事前に設定しておいてください
	 * @name select
	 * @return true:レコード1件をクラスプロパティに適用。false:取得レコードなし
	*/
	public function select($cn_name="default"){

		$ret = false;

		$qry = $this->select_string();
		$ary = array();
		$ary['StoreID'] = $this->StoreID;
		$result = $this->db->select($qry, $ary ,$cn_name);

		if ($result->num_rows() > 1){
			throw new Exception("設定されたキー値でデータが複数存在しました。当機能では対応できません");
		} elseif ($result->num_rows() === 1) {

			//各プロパティに取得データを設定
			$data = $result->row();
			$this->StoreID = $data->StoreID;
			$this->CorpID = $data->CorpID;
			$this->StoreCode = $data->StoreCode;
			$this->StoreName = $data->StoreName;
			$this->StoreSName = $data->StoreSName;
			$this->StoreShortName = $data->StoreShortName;
			$this->ZipCode1 = $data->ZipCode1;
			$this->ZipCode2 = $data->ZipCode2;
			$this->Prefecture = $data->Prefecture;
			$this->City = $data->City;
			$this->Area = $data->Area;
			$this->Adrs1 = $data->Adrs1;
			$this->Adrs2 = $data->Adrs2;
			$this->Tel1 = $data->Tel1;
			$this->Tel2 = $data->Tel2;
			$this->Fax = $data->Fax;
			$this->Web = $data->Web;
			$this->Mail = $data->Mail;
			$this->Memo = $data->Memo;
			$this->GroupMarkDiv = $data->GroupMarkDiv;
			$this->AiriaFactoryDiv = $data->AiriaFactoryDiv;
			$this->Manager = $data->Manager;
			$this->Post = $data->Post;
			$this->SGStartDate = $data->SGStartDate;
			$this->SGEndDate = $data->SGEndDate;
			$this->InvalidFlg = $data->InvalidFlg;
			$this->ProcFlg = $data->ProcFlg;
			$this->InsDate = $data->InsDate;
			$this->InsStaffID = $data->InsStaffID;
			$this->UpdDate = $data->UpdDate;
			$this->UpdStaffID = $data->UpdStaffID;

			$ret = true;
		}

		return $ret;
	}



	/*************************************************************************/
	/**
	 * insert文の作成
	 * @name insert_string
	 * @return string insert文
	*/
	public function insert_string(){
		$this->_check_primary_key();

		$ret = "INSERT INTO M_CorpStore ";
		$ret .= "(StoreID,CorpID,StoreCode,StoreName,StoreSName,StoreShortName,ZipCode1,ZipCode2,Prefecture,City,Area,Adrs1,Adrs2,Tel1,Tel2,Fax,Web,Mail,Memo,GroupMarkDiv,AiriaFactoryDiv,Manager,Post,SGStartDate,SGEndDate,InvalidFlg,ProcFlg,InsDate,InsStaffID) ";
		$ret .= "VALUES ";
		$ret .= "(:StoreID,:CorpID,:StoreCode,:StoreName,:StoreSName,:StoreShortName,:ZipCode1,:ZipCode2,:Prefecture,:City,:Area,:Adrs1,:Adrs2,:Tel1,:Tel2,:Fax,:Web,:Mail,:Memo,:GroupMarkDiv,:AiriaFactoryDiv,:Manager,:Post,:SGStartDate,:SGEndDate,:InvalidFlg,:ProcFlg,NOW(),:InsStaffID) ";

		return $ret;
	}

	/*************************************************************************/
	/**
	 * Insertの実行
	 * @name insert
	 * @return true:更新成功。失敗はDBドライバによってことなる。なるべく例外出すよう継承元で処理を作る
	*/
	public function insert(){

		$ret = false;

		$qry = $this->insert_string();
		$ary = array();
		$this->_check_value($this->_StoreID, $this->StoreID);
		$ary['StoreID'] = $this->_convert_edit_value($this->_StoreID, $this->StoreID);
		$this->_check_value($this->_CorpID, $this->CorpID);
		$ary['CorpID'] = $this->_convert_edit_value($this->_CorpID, $this->CorpID);
		$this->_check_value($this->_StoreCode, $this->StoreCode);
		$ary['StoreCode'] = $this->_convert_edit_value($this->_StoreCode, $this->StoreCode);
		$this->_check_value($this->_StoreName, $this->StoreName);
		$ary['StoreName'] = $this->_convert_edit_value($this->_StoreName, $this->StoreName);
		$this->_check_value($this->_StoreSName, $this->StoreSName);
		$ary['StoreSName'] = $this->_convert_edit_value($this->_StoreSName, $this->StoreSName);
		$this->_check_value($this->_StoreShortName, $this->StoreShortName);
		$ary['StoreShortName'] = $this->_convert_edit_value($this->_StoreShortName, $this->StoreShortName);
		$this->_check_value($this->_ZipCode1, $this->ZipCode1);
		$ary['ZipCode1'] = $this->_convert_edit_value($this->_ZipCode1, $this->ZipCode1);
		$this->_check_value($this->_ZipCode2, $this->ZipCode2);
		$ary['ZipCode2'] = $this->_convert_edit_value($this->_ZipCode2, $this->ZipCode2);
		$this->_check_value($this->_Prefecture, $this->Prefecture);
		$ary['Prefecture'] = $this->_convert_edit_value($this->_Prefecture, $this->Prefecture);
		$this->_check_value($this->_City, $this->City);
		$ary['City'] = $this->_convert_edit_value($this->_City, $this->City);
		$this->_check_value($this->_Area, $this->Area);
		$ary['Area'] = $this->_convert_edit_value($this->_Area, $this->Area);
		$this->_check_value($this->_Adrs1, $this->Adrs1);
		$ary['Adrs1'] = $this->_convert_edit_value($this->_Adrs1, $this->Adrs1);
		$this->_check_value($this->_Adrs2, $this->Adrs2);
		$ary['Adrs2'] = $this->_convert_edit_value($this->_Adrs2, $this->Adrs2);
		$this->_check_value($this->_Tel1, $this->Tel1);
		$ary['Tel1'] = $this->_convert_edit_value($this->_Tel1, $this->Tel1);
		$this->_check_value($this->_Tel2, $this->Tel2);
		$ary['Tel2'] = $this->_convert_edit_value($this->_Tel2, $this->Tel2);
		$this->_check_value($this->_Fax, $this->Fax);
		$ary['Fax'] = $this->_convert_edit_value($this->_Fax, $this->Fax);
		$this->_check_value($this->_Web, $this->Web);
		$ary['Web'] = $this->_convert_edit_value($this->_Web, $this->Web);
		$this->_check_value($this->_Mail, $this->Mail);
		$ary['Mail'] = $this->_convert_edit_value($this->_Mail, $this->Mail);
		$this->_check_value($this->_Memo, $this->Memo);
		$ary['Memo'] = $this->_convert_edit_value($this->_Memo, $this->Memo);
		$this->_check_value($this->_GroupMarkDiv, $this->GroupMarkDiv);
		$ary['GroupMarkDiv'] = $this->_convert_edit_value($this->_GroupMarkDiv, $this->GroupMarkDiv);
		$this->_check_value($this->_AiriaFactoryDiv, $this->AiriaFactoryDiv);
		$ary['AiriaFactoryDiv'] = $this->_convert_edit_value($this->_AiriaFactoryDiv, $this->AiriaFactoryDiv);
		$this->_check_value($this->_Manager, $this->Manager);
		$ary['Manager'] = $this->_convert_edit_value($this->_Manager, $this->Manager);
		$this->_check_value($this->_Post, $this->Post);
		$ary['Post'] = $this->_convert_edit_value($this->_Post, $this->Post);
		$this->_check_value($this->_SGStartDate, $this->SGStartDate);
		$ary['SGStartDate'] = $this->_convert_edit_value($this->_SGStartDate, $this->SGStartDate);
		$this->_check_value($this->_SGEndDate, $this->SGEndDate);
		$ary['SGEndDate'] = $this->_convert_edit_value($this->_SGEndDate, $this->SGEndDate);
		$this->_check_value($this->_InvalidFlg, $this->InvalidFlg);
		$ary['InvalidFlg'] = $this->_convert_edit_value($this->_InvalidFlg, $this->InvalidFlg);
		$this->_check_value($this->_ProcFlg, $this->ProcFlg);
		$ary['ProcFlg'] = $this->_convert_edit_value($this->_ProcFlg, $this->ProcFlg);
		$ary['InsStaffID'] = $this->_convert_edit_value($this->_InsStaffID, $this->InsStaffID);
		$ret = $this->db->execute($qry, $ary);

		return $ret;
	}



	/*************************************************************************/
	/**
	 * Update文の作成
	 * update_string
	 * @return Update文
	*/
	public function update_string(){
		$this->_check_primary_key();

		$ret = "UPDATE M_CorpStore SET ";
		$ret .= "   CorpID = :CorpID";
		$ret .= " , StoreCode = :StoreCode";
		$ret .= " , StoreName = :StoreName";
		$ret .= " , StoreSName = :StoreSName";
		$ret .= " , StoreShortName = :StoreShortName";
		$ret .= " , ZipCode1 = :ZipCode1";
		$ret .= " , ZipCode2 = :ZipCode2";
		$ret .= " , Prefecture = :Prefecture";
		$ret .= " , City = :City";
		$ret .= " , Area = :Area";
		$ret .= " , Adrs1 = :Adrs1";
		$ret .= " , Adrs2 = :Adrs2";
		$ret .= " , Tel1 = :Tel1";
		$ret .= " , Tel2 = :Tel2";
		$ret .= " , Fax = :Fax";
		$ret .= " , Web = :Web";
		$ret .= " , Mail = :Mail";
		$ret .= " , Memo = :Memo";
		$ret .= " , GroupMarkDiv = :GroupMarkDiv";
		$ret .= " , AiriaFactoryDiv = :AiriaFactoryDiv";
		$ret .= " , Manager = :Manager";
		$ret .= " , Post = :Post";
		$ret .= " , SGStartDate = :SGStartDate";
		$ret .= " , SGEndDate = :SGEndDate";
		$ret .= " , InvalidFlg = :InvalidFlg";
		$ret .= " , ProcFlg = :ProcFlg";
		$ret .= " , UpdDate = NOW()";
		$ret .= " , UpdStaffID = :UpdStaffID";
		$ret .= " WHERE StoreID = :StoreID ";

		return $ret;
	}

	/*************************************************************************/
	/**
	 * Updateの実行
	 * @name update
	 * @return true:更新成功。失敗はDBドライバによってことなる。なるべく例外出すよう継承元で処理を作る
	*/
	public function update(){

		$ret = false;

		$qry = $this->update_string();
		$ary = array();
		$this->_check_value($this->_StoreID, $this->StoreID);
		$ary['StoreID'] = $this->_convert_edit_value($this->_StoreID, $this->StoreID);
		$this->_check_value($this->_CorpID, $this->CorpID);
		$ary['CorpID'] = $this->_convert_edit_value($this->_CorpID, $this->CorpID);
		$this->_check_value($this->_StoreCode, $this->StoreCode);
		$ary['StoreCode'] = $this->_convert_edit_value($this->_StoreCode, $this->StoreCode);
		$this->_check_value($this->_StoreName, $this->StoreName);
		$ary['StoreName'] = $this->_convert_edit_value($this->_StoreName, $this->StoreName);
		$this->_check_value($this->_StoreSName, $this->StoreSName);
		$ary['StoreSName'] = $this->_convert_edit_value($this->_StoreSName, $this->StoreSName);
		$this->_check_value($this->_StoreShortName, $this->StoreShortName);
		$ary['StoreShortName'] = $this->_convert_edit_value($this->_StoreShortName, $this->StoreShortName);
		$this->_check_value($this->_ZipCode1, $this->ZipCode1);
		$ary['ZipCode1'] = $this->_convert_edit_value($this->_ZipCode1, $this->ZipCode1);
		$this->_check_value($this->_ZipCode2, $this->ZipCode2);
		$ary['ZipCode2'] = $this->_convert_edit_value($this->_ZipCode2, $this->ZipCode2);
		$this->_check_value($this->_Prefecture, $this->Prefecture);
		$ary['Prefecture'] = $this->_convert_edit_value($this->_Prefecture, $this->Prefecture);
		$this->_check_value($this->_City, $this->City);
		$ary['City'] = $this->_convert_edit_value($this->_City, $this->City);
		$this->_check_value($this->_Area, $this->Area);
		$ary['Area'] = $this->_convert_edit_value($this->_Area, $this->Area);
		$this->_check_value($this->_Adrs1, $this->Adrs1);
		$ary['Adrs1'] = $this->_convert_edit_value($this->_Adrs1, $this->Adrs1);
		$this->_check_value($this->_Adrs2, $this->Adrs2);
		$ary['Adrs2'] = $this->_convert_edit_value($this->_Adrs2, $this->Adrs2);
		$this->_check_value($this->_Tel1, $this->Tel1);
		$ary['Tel1'] = $this->_convert_edit_value($this->_Tel1, $this->Tel1);
		$this->_check_value($this->_Tel2, $this->Tel2);
		$ary['Tel2'] = $this->_convert_edit_value($this->_Tel2, $this->Tel2);
		$this->_check_value($this->_Fax, $this->Fax);
		$ary['Fax'] = $this->_convert_edit_value($this->_Fax, $this->Fax);
		$this->_check_value($this->_Web, $this->Web);
		$ary['Web'] = $this->_convert_edit_value($this->_Web, $this->Web);
		$this->_check_value($this->_Mail, $this->Mail);
		$ary['Mail'] = $this->_convert_edit_value($this->_Mail, $this->Mail);
		$this->_check_value($this->_Memo, $this->Memo);
		$ary['Memo'] = $this->_convert_edit_value($this->_Memo, $this->Memo);
		$this->_check_value($this->_GroupMarkDiv, $this->GroupMarkDiv);
		$ary['GroupMarkDiv'] = $this->_convert_edit_value($this->_GroupMarkDiv, $this->GroupMarkDiv);
		$this->_check_value($this->_AiriaFactoryDiv, $this->AiriaFactoryDiv);
		$ary['AiriaFactoryDiv'] = $this->_convert_edit_value($this->_AiriaFactoryDiv, $this->AiriaFactoryDiv);
		$this->_check_value($this->_Manager, $this->Manager);
		$ary['Manager'] = $this->_convert_edit_value($this->_Manager, $this->Manager);
		$this->_check_value($this->_Post, $this->Post);
		$ary['Post'] = $this->_convert_edit_value($this->_Post, $this->Post);
		$this->_check_value($this->_SGStartDate, $this->SGStartDate);
		$ary['SGStartDate'] = $this->_convert_edit_value($this->_SGStartDate, $this->SGStartDate);
		$this->_check_value($this->_SGEndDate, $this->SGEndDate);
		$ary['SGEndDate'] = $this->_convert_edit_value($this->_SGEndDate, $this->SGEndDate);
		$this->_check_value($this->_InvalidFlg, $this->InvalidFlg);
		$ary['InvalidFlg'] = $this->_convert_edit_value($this->_InvalidFlg, $this->InvalidFlg);
		$this->_check_value($this->_ProcFlg, $this->ProcFlg);
		$ary['ProcFlg'] = $this->_convert_edit_value($this->_ProcFlg, $this->ProcFlg);
		$ary['UpdStaffID'] = $this->_convert_edit_value($this->_UpdStaffID, $this->UpdStaffID);
		$ret = $this->db->execute($qry, $ary);

		return $ret;
	}



	/*************************************************************************/
	/**
	 * 特定カラムUpdate文の作成
	 * @name update_part_string
	 * @param array $colary アップデートを行う特定カラム名の配列
	 * @return Update文
	*/
	public function update_part_string($colary){
		$this->_check_primary_key();

		$ret = "UPDATE M_CorpStore SET ";
		if (array_key_exists("CorpID", $colary))
			$ret .= " CorpID = :CorpID,";
		if (array_key_exists("StoreCode", $colary))
			$ret .= " StoreCode = :StoreCode,";
		if (array_key_exists("StoreName", $colary))
			$ret .= " StoreName = :StoreName,";
		if (array_key_exists("StoreSName", $colary))
			$ret .= " StoreSName = :StoreSName,";
		if (array_key_exists("StoreShortName", $colary))
			$ret .= " StoreShortName = :StoreShortName,";
		if (array_key_exists("ZipCode1", $colary))
			$ret .= " ZipCode1 = :ZipCode1,";
		if (array_key_exists("ZipCode2", $colary))
			$ret .= " ZipCode2 = :ZipCode2,";
		if (array_key_exists("Prefecture", $colary))
			$ret .= " Prefecture = :Prefecture,";
		if (array_key_exists("City", $colary))
			$ret .= " City = :City,";
		if (array_key_exists("Area", $colary))
			$ret .= " Area = :Area,";
		if (array_key_exists("Adrs1", $colary))
			$ret .= " Adrs1 = :Adrs1,";
		if (array_key_exists("Adrs2", $colary))
			$ret .= " Adrs2 = :Adrs2,";
		if (array_key_exists("Tel1", $colary))
			$ret .= " Tel1 = :Tel1,";
		if (array_key_exists("Tel2", $colary))
			$ret .= " Tel2 = :Tel2,";
		if (array_key_exists("Fax", $colary))
			$ret .= " Fax = :Fax,";
		if (array_key_exists("Web", $colary))
			$ret .= " Web = :Web,";
		if (array_key_exists("Mail", $colary))
			$ret .= " Mail = :Mail,";
		if (array_key_exists("Memo", $colary))
			$ret .= " Memo = :Memo,";
		if (array_key_exists("GroupMarkDiv", $colary))
			$ret .= " GroupMarkDiv = :GroupMarkDiv,";
		if (array_key_exists("AiriaFactoryDiv", $colary))
			$ret .= " AiriaFactoryDiv = :AiriaFactoryDiv,";
		if (array_key_exists("Manager", $colary))
			$ret .= " Manager = :Manager,";
		if (array_key_exists("Post", $colary))
			$ret .= " Post = :Post,";
		if (array_key_exists("SGStartDate", $colary))
			$ret .= " SGStartDate = :SGStartDate,";
		if (array_key_exists("SGEndDate", $colary))
			$ret .= " SGEndDate = :SGEndDate,";
		if (array_key_exists("InvalidFlg", $colary))
			$ret .= " InvalidFlg = :InvalidFlg,";
		if (array_key_exists("ProcFlg", $colary))
			$ret .= " ProcFlg = :ProcFlg,";
		//[UpdDate]はカラム指定の有無関係なし
			$ret .= " UpdDate = NOW(),";
		//[UpdStaffID]はカラム指定の有無関係なし
			$ret .= " UpdStaffID = :UpdStaffID";
		if (mb_substr($ret, -1) == ',') $ret = mb_substr($ret, 0, -1);
		$ret .= " WHERE StoreID = :StoreID ";

		return $ret;
	}

	/*************************************************************************/
	/**
	 * 特定カラムUpdateの実行
	 * @name update_part
	 * @param array $colary アップデートを行う特定カラム名の配列
	 * @return true:更新成功。失敗はDBドライバによってことなる。なるべく例外出すよう継承元で処理を作る
	*/
	public function update_part($colary){

		$ret = false;

		$qry = $this->update_part_string($colary);
		$ary = array();
		if (array_key_exists("StoreID", $colary)) {
			$this->_check_value($this->_StoreID, $this->StoreID);
			$ary['StoreID'] = $this->_convert_edit_value($this->_StoreID, $this->StoreID);
		}
		if (array_key_exists("CorpID", $colary)) {
			$this->_check_value($this->_CorpID, $this->CorpID);
			$ary['CorpID'] = $this->_convert_edit_value($this->_CorpID, $this->CorpID);
		}
		if (array_key_exists("StoreCode", $colary)) {
			$this->_check_value($this->_StoreCode, $this->StoreCode);
			$ary['StoreCode'] = $this->_convert_edit_value($this->_StoreCode, $this->StoreCode);
		}
		if (array_key_exists("StoreName", $colary)) {
			$this->_check_value($this->_StoreName, $this->StoreName);
			$ary['StoreName'] = $this->_convert_edit_value($this->_StoreName, $this->StoreName);
		}
		if (array_key_exists("StoreSName", $colary)) {
			$this->_check_value($this->_StoreSName, $this->StoreSName);
			$ary['StoreSName'] = $this->_convert_edit_value($this->_StoreSName, $this->StoreSName);
		}
		if (array_key_exists("StoreShortName", $colary)) {
			$this->_check_value($this->_StoreShortName, $this->StoreShortName);
			$ary['StoreShortName'] = $this->_convert_edit_value($this->_StoreShortName, $this->StoreShortName);
		}
		if (array_key_exists("ZipCode1", $colary)) {
			$this->_check_value($this->_ZipCode1, $this->ZipCode1);
			$ary['ZipCode1'] = $this->_convert_edit_value($this->_ZipCode1, $this->ZipCode1);
		}
		if (array_key_exists("ZipCode2", $colary)) {
			$this->_check_value($this->_ZipCode2, $this->ZipCode2);
			$ary['ZipCode2'] = $this->_convert_edit_value($this->_ZipCode2, $this->ZipCode2);
		}
		if (array_key_exists("Prefecture", $colary)) {
			$this->_check_value($this->_Prefecture, $this->Prefecture);
			$ary['Prefecture'] = $this->_convert_edit_value($this->_Prefecture, $this->Prefecture);
		}
		if (array_key_exists("City", $colary)) {
			$this->_check_value($this->_City, $this->City);
			$ary['City'] = $this->_convert_edit_value($this->_City, $this->City);
		}
		if (array_key_exists("Area", $colary)) {
			$this->_check_value($this->_Area, $this->Area);
			$ary['Area'] = $this->_convert_edit_value($this->_Area, $this->Area);
		}
		if (array_key_exists("Adrs1", $colary)) {
			$this->_check_value($this->_Adrs1, $this->Adrs1);
			$ary['Adrs1'] = $this->_convert_edit_value($this->_Adrs1, $this->Adrs1);
		}
		if (array_key_exists("Adrs2", $colary)) {
			$this->_check_value($this->_Adrs2, $this->Adrs2);
			$ary['Adrs2'] = $this->_convert_edit_value($this->_Adrs2, $this->Adrs2);
		}
		if (array_key_exists("Tel1", $colary)) {
			$this->_check_value($this->_Tel1, $this->Tel1);
			$ary['Tel1'] = $this->_convert_edit_value($this->_Tel1, $this->Tel1);
		}
		if (array_key_exists("Tel2", $colary)) {
			$this->_check_value($this->_Tel2, $this->Tel2);
			$ary['Tel2'] = $this->_convert_edit_value($this->_Tel2, $this->Tel2);
		}
		if (array_key_exists("Fax", $colary)) {
			$this->_check_value($this->_Fax, $this->Fax);
			$ary['Fax'] = $this->_convert_edit_value($this->_Fax, $this->Fax);
		}
		if (array_key_exists("Web", $colary)) {
			$this->_check_value($this->_Web, $this->Web);
			$ary['Web'] = $this->_convert_edit_value($this->_Web, $this->Web);
		}
		if (array_key_exists("Mail", $colary)) {
			$this->_check_value($this->_Mail, $this->Mail);
			$ary['Mail'] = $this->_convert_edit_value($this->_Mail, $this->Mail);
		}
		if (array_key_exists("Memo", $colary)) {
			$this->_check_value($this->_Memo, $this->Memo);
			$ary['Memo'] = $this->_convert_edit_value($this->_Memo, $this->Memo);
		}
		if (array_key_exists("GroupMarkDiv", $colary)) {
			$this->_check_value($this->_GroupMarkDiv, $this->GroupMarkDiv);
			$ary['GroupMarkDiv'] = $this->_convert_edit_value($this->_GroupMarkDiv, $this->GroupMarkDiv);
		}
		if (array_key_exists("AiriaFactoryDiv", $colary)) {
			$this->_check_value($this->_AiriaFactoryDiv, $this->AiriaFactoryDiv);
			$ary['AiriaFactoryDiv'] = $this->_convert_edit_value($this->_AiriaFactoryDiv, $this->AiriaFactoryDiv);
		}
		if (array_key_exists("Manager", $colary)) {
			$this->_check_value($this->_Manager, $this->Manager);
			$ary['Manager'] = $this->_convert_edit_value($this->_Manager, $this->Manager);
		}
		if (array_key_exists("Post", $colary)) {
			$this->_check_value($this->_Post, $this->Post);
			$ary['Post'] = $this->_convert_edit_value($this->_Post, $this->Post);
		}
		if (array_key_exists("SGStartDate", $colary)) {
			$this->_check_value($this->_SGStartDate, $this->SGStartDate);
			$ary['SGStartDate'] = $this->_convert_edit_value($this->_SGStartDate, $this->SGStartDate);
		}
		if (array_key_exists("SGEndDate", $colary)) {
			$this->_check_value($this->_SGEndDate, $this->SGEndDate);
			$ary['SGEndDate'] = $this->_convert_edit_value($this->_SGEndDate, $this->SGEndDate);
		}
		if (array_key_exists("InvalidFlg", $colary)) {
			$this->_check_value($this->_InvalidFlg, $this->InvalidFlg);
			$ary['InvalidFlg'] = $this->_convert_edit_value($this->_InvalidFlg, $this->InvalidFlg);
		}
		if (array_key_exists("ProcFlg", $colary)) {
			$this->_check_value($this->_ProcFlg, $this->ProcFlg);
			$ary['ProcFlg'] = $this->_convert_edit_value($this->_ProcFlg, $this->ProcFlg);
		}
		$ary['UpdStaffID'] = $this->_convert_edit_value($this->_UpdStaffID, $this->UpdStaffID);
		$ret = $this->db->execute($qry, $ary);

		return $ret;
	}



	/*************************************************************************/
	/**
	 * delete文の作成
	 * @name delete_string
	 * @return delete文
	*/
	public function delete_string(){
		$this->_check_primary_key();

		$ret = "DELETE FROM M_CorpStore ";
		$ret .= " WHERE StoreID = :StoreID ";

		return $ret;
	}

	/*************************************************************************/
	/**
	 * deleteの実行
	 * @name delete
	 * @return true:更新成功。失敗はDBドライバによってことなる。なるべく例外出すよう継承元で処理を作る
	*/
	public function delete(){

		$ret = false;

		$qry = $this->delete_string();
		$ary = array();
		$ary['StoreID'] = $this->StoreID;
		$ret = $this->db->execute($qry, $ary);

		return $ret;
	}


	/*************************************************************************/
	/**
	 * 主キー値のチェック
	 * @name _check_primary_key
	 * @return なし
	*/
	private function _check_primary_key(){

		if (!$this->exists_key) throw new Exception("テーブルにキーが存在しないためクエリは作成できません");
		//StoreID:decimal
		if ($this->StoreID == "") throw new Exception("キー値が入力されていません[StoreID]");
		if ($this->check_num_zero && $this->StoreID == "0") throw new Exception("キー値が入力されていません[StoreID]");
		$StoreID_ary = explode(",", $this->StoreID);
		if (mb_strlen($StoreID_ary[0],"utf8") > 10) throw new Exception("桁数がオーバーしています[StoreID]");
		if (count($StoreID_ary) == 2 && intval($StoreID_ary[1]) != 0) {
			if (mb_strlen($StoreID_ary[1],"utf8") > 0) throw new Exception("小数点以下の桁数がオーバーしています[StoreID]");
		}
	}


	/*************************************************************************/
	/**
	 * 各カラムのvalueをinsert,edit用の文字列に変換
	 * @name _convert_edit_value
	 * @param any $args GC_ColumnInfo
	 * @return 変換した文字列
	*/
	private function _convert_edit_value($args, $value){

		$ret = null;

		if ($args) {
			if ($args->type == "datetime") {
				if (date("H:i:s", strtotime($value)) == "00:00:00") $ret = date("Y/m/d", strtotime($value));
				else $ret = date("Y/m/d H:i:s", strtotime($value));
			} elseif ($args->type == "bit" || $args->type == "bool") {
				if ($value) $ret = "1";
				else $ret = "0";
			} else {
				$ret = $value;
			}
		}

		$this->editor = LOGINID;
		if ($this->use_auto_editor) {
			if ($args->name == "InsStaffID") $ret = $this->editor;
			if ($args->name == "UpdStaffID") $ret = $this->editor;
		}

		return $ret;
	}


	/*************************************************************************/
	/**
	 * 各カラムのvalueチェック
	 * @name _check_value
	 * @param string $col GC_ColumnInfo
	 * @return 値チェックで問題があった場合は例外発生
	*/
	private function _check_value($col, $value){

		$s = "";

		if ($s == "" && !$col->is_null && $value == "")
			$s = "空の値は許可されません。";

		if ($s == "" && ($col->type == "varchar" || $col->type == "char" || $col->type == "text")) {
			if (mb_strlen($value,"utf8") > $col->length)
				$s = "文字数は".$col->length."文字までです。";
		}

		if ($s == "" && $col->type == "decimal" && $value != "") {
			if (is_numeric($value)) {
				$ary = explode(".", $value);

				//小数点あり
				if (count($ary) == 2) {
					$sclVal = intval($ary[1]);

					if ($col->scale == "0" && sclVal > 0) {
						$s = "小数の値は登録できません。";
					} elseif ($col->scale != "0" && $col->scale < mb_strlen($sclVal,"utf8")) {
						$s = "小数の最大桁数は".$col->scale."桁までです。";
					}
				}

				//整数部(-は文字数としてカウントしない)
				if ($s == "" && mb_strlen(trim($ary[0], "-"),"utf8") > $col->length) $s = "整数値の最大桁数は".$col->length."桁までです。";
			} else {
				$s = "正しい数値ではありません";
			}
		}

		if ($s == "" && ($col->type == "date" || $col->type == "datetime") && $value != "") {
			$chk = true;
			try{
				$str = str_replace("/","-", $col-value);
				//まず日付変換してみる
				$date = new DateTime($str);
				//エラーにならずに日付が自動変換されてしまう場合があるのでチェック(例:2015-02-29⇒2015-03-01)
				if ($date->format('Y-m-d') <> mb_substr($str, 0, 10, 'UTF-8')) $chk = false;
				//0000年もエラーにならないのでチェック
				if ($chk && $date->format('Y') == '0000') $chk = false;
			}catch (Exception $e) {
				$chk = false;
			}

			//1つ目のチェックOKの場合
			if ($chk) {
				//最後に念のためcheckdate
				$ary = explode("-", $date->format('Y-m-d'));
				if (!@checkdate($ary[1],$ary[2],$ary[0])) $chk = false;
			}

			if (!$chk) {
				$s = "正しい日付ではありません";
			}
		}

		if ($s == "" && ($col->type == "int" || $col->type == "smallint" || $col->type == "bigint" || $col->type == "tinyint") && $value != "") {
			$str = $col-value;
			if (mb_substr($str, 0, 1, 'UTF-8') == "-") $str = mb_substr($str, 1);
			if(!preg_match("/^[0-9]+$/", $str)) {
				$s = "正しい数値ではありません";
			} elseif (mb_strlen($str) > $col->length) {
				$s = "最大桁数は".$col->length."桁までです。";
			}
		}

		if ($s == "" && $col->type == "bit" && $value != "") {
			if ($value != "0" && $value != "1")
				$s = "正しい値ではありません";
		}

		if ($s != "") {
			if ($col->text == "") {
				throw new Exception($s." [".$col->name."]");
			} else {
				throw new Exception($s." [".$col->text."]");
			}
		}

	}

}

?>
