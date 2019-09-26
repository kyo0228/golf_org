<div id="main">  

  <div class="groupbox">
    <p><a href="<?=$this->url_view("index","site")?>"><button class="btn_blue">一覧に戻る</button></a></p>
    <div style="">
      <table>
        <tr><th>有報ID</th><td><?=$this->value("ufo_data","ufo_id")?></td><th>取得元</th><td><?=$this->value("ufo_data","access")?></td></tr>
        <tr><th>証券コード</th><td><?=$this->value("ufo_data","s_code")?></td><th>法人コード</th><td><?=$this->value("ufo_data","c_code")?></td></tr>
        <tr><th>会社名</th><td colspan="3"><?=$this->value("ufo_data","corp")?></td></tr>
        <tr><th>タイトル</th><td colspan="3"><?=$this->value("ufo_data","title")?></td></tr>
        <tr><th>XBRLデータ</th><td colspan="3"><?=str_replace("&lt;__&gt;", "<br />", $this->value("ufo_data","xbrl_url"))?></td></tr>
        <tr><th>(参考)全URL</th>
          <td colspan="3">
            <p>
              <input type="checkbox" id="chk_allurl" name="chk_allurl" />
              <label for="chk_allurl">表示する</label>
            </p>
            <div id="txt_allurl" style="display:none;">
              <?=nl2br($this->value("ufo_data","all_url"))?>
            </div>
            
          </td>
        </tr>
        
        <tr><th>有報データ登録日</th><td><?=date('Y-m-d', strtotime($this->value("ufo_data","ufo_date")))?></td><th>ダウンロード日</th><td><?=$this->value("ufo_data","upd_date")?></td></tr>
        
      </table>
    </div>  
    <p>&nbsp;</p>
    
<?php 
  $chk_elm_jp = "";
  if ($this->get("elm_jp")){$chk_elm_jp="checked='checked'";}
  
  $chk_ctxt_jp = "";
  if ($this->get("ctxt_jp")){$chk_ctxt_jp="checked='checked'";}  
  
?>    
    
<?php          
     $list = $this->value("ufo_list");
?>          
      <div>        
        絞り込み：<input type="text" name="txt_search" id="txt_search"  autocomplete="off" value="<?=$this->value("txt_search")?>">
        <input type="checkbox" id="chk_elm_jp" name="chk_elm_jp" <?=$chk_elm_jp?> />
        <label for="chk_elm_jp">表示名(科目)あり</label>
        <input type="checkbox" id="chk_ctxt_jp" name="chk_ctxt_jp" <?=$chk_ctxt_jp?> />
        <label for="chk_ctxt_jp">表示名(コンテキスト)あり</label>

        <button id="btn_search" >検索</button>
        <button id="btn_csv" >CSV出力</button>
      </div>  
      <p>件数：<span id="elm_count"><?=  number_format(count($list))?></span>件</p>
      <table id="tbl_element" style="width:900px;">
        <thead>
        <tr>
          <th style="width:300px">科目名/コンテキスト</th>
          <th style="width:350px">表示名</th>
          <th style="">金額</th>
          <th style="width:70px">精度</th>
        </tr>
        </thead>
        <tbody>        
<?php          
     $list = $this->value("ufo_list");
     foreach ($list as $row) {
?>    
        <tr>
          <td style="word-break: break-all;"><span class="text-bold"><?=$row["elm_name"]?></span><br />[<?=$row["context"]?>]</td>
          <td style="word-break: break-all;"><span class="text-bold"><?=$row["elm_jp_name"]?></span><br />[<?=$row["ctxt_jp_name"]?>]</td>
          <td style="text-align: right"><?=number_format($row["amount"])?></td>
          <td style="text-align: right"><?=$row["decimals"]?></td>
        </tr>
<?php         
     }
?>      
        </tbody>
      </table>
    
  </div>        
</div>          

<script>

$(function(){
	
  var $input = $('#txt_search');
  $input.on('input', function(event) {
    //var value = $input.val();
    search_element($input.val());
  });	
	


	function search_element(search_val) {
		search_count = 0;

		// tbodyのtr数回 処理をする
		$.each($("#tbl_element tbody tr"), function (index, element) {
			$(element).removeHighlight();

				// 検索値が空だったら、全ての行を表示する為の処理
				if (search_val === "") {
						$(element).css("display", "table-row");
						search_count++;
						return true; // 次のtrへ
				}

				var row_text = $(element).text();

				if (row_text.indexOf(search_val) !== -1) {
						// 見つかった場合は表示する
						$(element).css("display", "table-row");
						$(element).highlight(search_val);
						search_count++;
				} else {
						// 見つからなかった場合は非表示に
						$(element).css("display", "none");
				}

		});		
		
		$('#elm_count').text(search_count);	
	}	

  $(document).ready(function(){

    if ($('#txt_search').val() !== ""){
      search_element($('#txt_search').val());
    }
  });
  
  $('#btn_search').click(function(){

    window.location.href = get_url();
    return false;
  });
  
  $('#btn_csv').click(function(){
    var url = get_url();
    
    if ( url.indexOf('?') !== -1) {
      url = url+"&csv=output";
    }else{
      url = url+"?csv=output";
    }
    
    window.location.href = url;
    return false;
  });  
  
  function get_url() {
    var url = '<?=$this->url()."/".$this->value("ufo_data","ufo_id")?>';
    var get = '';
    if ($('#chk_elm_jp').prop("checked")){
      get = get+'elm_jp=1';
    }
    
    if ($('#chk_ctxt_jp').prop("checked")){
      if (get){get= get+"&";}
      get = get+'ctxt_jp=1';
    }
    
    if (get){
      url = url+'?'+get;
    }
    
    return url;
  }
  
  $('#chk_allurl').change(function() {
    if ($('#chk_allurl').prop("checked")){
      $('#txt_allurl').css("display", "inline");
    }else{
      $('#txt_allurl').css("display", "none");
    }

    return false;
  });  

});
</script>