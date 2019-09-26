<form action="" method ="post">
  <div id="main" style="flex-wrap :nowrap">  
    <div class="groupbox">
<?php 
  $chkjp = "";
  if ($this->post("chk_jp_name")){$chkjp="checked='checked'";}
  
  $chkenable = "";
  if ($this->post("chk_enable")){$chkenable="checked='checked'";}  
  
?>    
      <div>
        絞り込み：<input type="text" name="txt_search" id="txt_search"  autocomplete="off" value="<?=$this->value("txt_search")?>">
        <input type="checkbox" id="chk_jp_name" name="chk_jp_name" <?=$chkjp?> />
        <label for="chk_jp_name">表示名ありのみ</label>
        <input type="checkbox" id="chk_enable" name="chk_enable" <?=$chkenable?> />
        <label for="chk_enable">集計対象科目のみ</label>      

        <input type="submit" name="btn_search_element" value="検索"> 

      </div>
    

<?php
    $list = $this->value("elm_list");
?>    				
      <p>件数：<span id="elm_count"><?=count($list)?></span>件</p>
      <table id="tbl_element">
        <thead>
          <tr>
            <th style="width:300px;">科目名</th>
            <th>表示名</th>
            <th style="width:4.2rem;">集計対象</th>
          </tr>						
        </thead>
        <tbody>
<?php

    foreach ($list as $row) {
      $enable = "-";
      $color = "";
      if ($row["enable_flg"]){
        $enable = "○";
        $color = "bkcolor_pink";
      }
?>    
          <tr>
            <td class="elm_name"><?=$row["elm_name"]?></td>
            <td class="elm_jp_name"><?=$row["elm_jp_name"]?></td>
            <td class="elm_enable <?=$color?>" style="text-align:center;"><?=$enable?></td>
          </tr>
<?php       
    }
?>         
        </tbody>
      </table>  
    </div>

    <!--<div class="groupbox" style="flex-grow:2;">-->
    <div class="sticky" >
    
      <input type="hidden" id="selected_element_name_hidden" name="selected_element_name_hidden" value="">
      <div>
        <p>科目名：<span  class="text-bold"  id="selected_element_name"></span></p>
        <p>表示名：<input type="text" name="selected_element_jp_name" id="selected_element_jp_name"  autocomplete="off" ></p>
        <p><input type="checkbox" id="selected_element_enable" name="selected_element_enable" /><label for="selected_element_enable">この科目を集計対象にする</label></p>
        <input type="submit" name="btn_edit_element" value="登録"> 
      </div>      

    <!--ajaxで取得した科目明細情報をjavascriptでセットするエリア-->
      <div id="selected_element_detail">
      </div>
    </div>
  </div>
</form>



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
	

	$("#tbl_element tbody tr").bind('click', function(){
    var elm_name = $(this).children(".elm_name").text();
    var jp_name = $(this).children(".elm_jp_name").text();
    var elm_enable = $(this).children(".elm_enable").text();
    
    //console.log();
    
    $('#selected_element_name').text(elm_name);
    $('#selected_element_name_hidden').val(elm_name);
    $('#selected_element_jp_name').val(jp_name);
    
    if (elm_enable === '○'){
      $('#selected_element_enable').prop("checked",true);
    }else{
      $('#selected_element_enable').prop("checked",false);
    }
    
    $('#selected_element_detail').empty();
    
		$.get("<?=$this->url_view('ajax_element_detail','master')?>", { name: elm_name },
			function(data){
        
        
        if (!data){  
          $('#selected_element_detail').append("<p>件数：<span class='text-bold'>0</span>件</p>");          
          //alert("aa");
        }else{
          var detailAry =JSON.parse(data);

          if (Array.isArray(detailAry)){
            $('#selected_element_detail').append("<p>件数：<span class='text-bold'>"+detailAry.length+"</span>件</p>");

            $('#selected_element_detail').append("<p style='font-size:0.7rem; text-align:right'>※XBRLのamount(金額)に文字が入っている場合は単位に保存しています</p>");


            $.each(detailAry, function (index, element) {
              
              var ctxt = element["context"];
              if (element["disp_name"]){
                ctxt = ctxt +"@"+ element["disp_name"];
              }
              
              var str = "";
              str+= "<table>";
              str+= "<tr><th style='text-align:left;'>"+element["ufo_date"]+" "+element["title"]+"</td></th>";
              str+= "<tr><td>";
              str+= "<p>プレフィックス：<span class='bkcolor_green'>"+element["elm_kind"]+"</span> コンテキスト：<span class='bkcolor_green'>"+ctxt+"</span></p>";
              //str+= "<p><span class='bkcolor_green'>"+element["elm_kind"]+":</span>"+element["elm_name"]+"<span class='bkcolor_green'>("+element["context"]+")</span></p>";
              str+= "<p>企業名："+element["corp"]+" 有価証券ID："+element["ufo_id"]+"</p>";
              str+= "<p>金額："+(element["amount"]).toLocaleString()+" 精度："+element["decimals"]+" (「-3：千円」「-6：百万円」？</p>";
              str+= "<p>単位："+element["unit"]+"</p>";
              str+= "</td></tr>";
              str+= "</table>";

              $('#selected_element_detail').append(str);

            });	

          }          
        }
				
				//alert("Data Loaded: " + Array.isArray(test) );
				
			}
		);
  });
	
  $(document).ready(function(){

    if ($('#txt_search').val() !== ""){
      search_element($('#txt_search').val());
    }
  });
  
					/*
	$('#tbl_element tbody tr').on('click', function() {
	var td = $(this)[0];
	var tr = $(this).closest('tr')[0];
	
	console.log(JSON.stringify($(this)));
	
	//alert('td:' + $(this));
	
	//console.log('td:' + td.cellIndex);
	//console.log('tr:' + tr.rowIndex);
	//console.log($(this).text());
	});	
*/
});
</script>