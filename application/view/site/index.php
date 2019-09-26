<div id="main">  
  <div class="groupbox">
    <h3>【ダウンロード・登録】<span>証券コードを入力してデータを検索します。必要な情報を登録してください。</span></h3>
    <form action="" method ="post">
      
<?php
  $condition_01 ="";
  $condition_02 ="";
  if ($this->value("rdo_condition") == "2"){$condition_02 = "checked='checked'";}
  else{$condition_01 = "checked='checked'";}  
?>  
      
      <div>
        証券コード：
        <input type="text" name = "syokencode" style="width: 6.0rem;" autocomplete="off" value="<?=$this->value("syokencode")?>">
        <input type="submit" value="検索">
        <input type="hidden" name="form_syoken_search" value="1">
        &nbsp;
        <input type="radio" id="rdo_condition_01" name="rdo_condition" value="1" <?=$condition_01?> />
        <label for="rdo_condition_01">有報</label>
        <input type="radio" id="rdo_condition_02" name="rdo_condition" value="2" <?=$condition_02?> />
        <label for="rdo_condition_02">短信</label>        
        
        
        
      </div>
    
<?php
  if ($this->value("search_error")){
    print("<p class='red'>".$this->value("search_error")."<p>");
  }
?>        
    </form>  
  
  
  
<?php
    //登録実行した後の成功、失敗メッセージを表示
    if ($this->value("is_insert")){
      $errorary = $this->value("insert_error");
      if (count($errorary)){
        foreach ($errorary as $value) {
          print("<p class='red'>".$value."</p>") ;
        }
      }else{
        print("<p class='red'>登録が成功しました</p>") ;
      }      
    }
?>      
    <form action="" method ="post">
      <input type="hidden" name="form_ufo_insert" value="1">
      <input type="hidden" name="syokencode" value="<?=$this->value("syokencode")?>">
      <input type="hidden" name="rdo_condition" value="<?=$this->value("rdo_condition")?>">
      <table>
        <tr>
          <th style="width:100px">有価証券ID</th>
          <th style="min-width:500px">名称</th>
          <th style="width:120px">登録日</th>
          <th style="width:80px">状態</th>
          <th style="width:80px">登録</th>
        </tr>
<?php
  if (!$this->value("dl_list")){
?>
        <tr>
          <td colspan="5">未実施</td>
        </tr>                    
<?php          
  }else{
     $list = $this->value("dl_list");
     foreach ($list as $row) {
?>    
        <tr>
          <td><?=$row["id"]?></td>
<?php          
        //もし仕様変更などでダウンロードURLが取れなくなった場合はメッセージを出す
        if (!$row["link"]){
?>        <td colspan="4">
            <p><?=$row["title"]?></p>
            XBRLデータが見つかりません。(サマリしかない、仕様変更があった？短信は分析がまだ不十分かも・・)<br />
            ----(参考)関連全URL----<br />
            <div>
              <?=nl2br($row["alllink"])?>
            </div>
          </td>        
          
<?php          
        }else{
?>        <td><?=$row["title"]?></td>    
          <td><?=$row["updated"]?></td>
<?php          
          //もし仕様変更などでダウンロードURLが取れなくなった場合はメッセージを出す
          if (!$row["is_dl"]){
?>                
          <td class='bg_pink'>未登録</td>
          <td><input type="checkbox" name="chk_<?=$row["id"]?>" checked="checked" ></td>
<?php          
          }else{
?>                        
          <td>登録済</td>
          <td><input type="checkbox" name="chk_<?=$row["id"]?>"  ></td>
<?php                 
          }
?>                                           
<?php                 
        }
?>                               
        </tr>          

<?php                 
     }
?>         
<?php          
  }
?>          
      </table>
<?php
  if ($this->value("dl_list")){
?>        
      <input type="submit" value="データ登録"> 
<?php
  }
?>                  
    </form>    
  </div>        
  
  
  
  <div class="groupbox">
    <h3>【登録済みデータ一覧】</h3>
      <table>
        <tr>
          <th style="">詳細</th>
          <th style="">法人</th>
          <th style="">タイトル</th>
          <th style="">登録日</th>
          <th style="">集計</th>
          <th style="">削除</th>
        </tr>
<?php
  if (!$this->value("ufo_list")){
?>
        <tr>
          <td colspan="6">未登録</td>
        </tr>                    
<?php          
  }else{
     $list = $this->value("ufo_list");
     foreach ($list as $row) {
      $chk = "";
      if ($row["syukei"]){$chk="checked='checked'";}       
?>    
        <tr>
          <td><a href="<?=$this->url_view("detail","site",array($row["ufo_id"]))?>"><button class="btn_blue">詳細</button></a></td>
          <td><?=$row["corp"]?></td>
          <td><?=$row["title"]?></td>
          <td><?=date('Y-m-d', strtotime($row["ufo_date"]))?></td>
          <td>
            <div class="switch">
              <input id="toggle_<?=$row["ufo_id"]?>" class="cmn-toggle cmn-toggle-round" type="checkbox" <?=$chk?>>
              <label for="toggle_<?=$row["ufo_id"]?>"></label>
            </div>            
          </td>
          <td><button class="btn_red" name="del_<?=$row["ufo_id"]?>" item="<?=$row["title"]?>">削除</button></td>
        </tr>
<?php         
     }
?>         
<?php          
  }
?>          
      </table>
    
  </div>        
  
  
</div>          

<script>

$(function(){

  //削除ボタンクリックイベント
  $('.btn_red').click(function(){
    
      var result = window.confirm($(this).attr("item")+'を削除しますか？');

      if( result ) {

        $.get("<?=$this->url_view('ajax_delete_ufo','site')?>", { name: $(this).attr("name") },
          function(data){
              //alert(data);

              window.location.href = '<?=$this->url()?>';
              return false;
          }
        );    

      }
    
    //alert($(this).attr("name"));
    
  });
  
  
  //集計トグルボタン選択変更時のイベント
  $('.cmn-toggle').click(function(){
    //alert($(this).attr("id"));
      $.get("<?=$this->url_view('ajax_update_ufo_syukei','site')?>", { name: $(this).attr("id"),flg:$(this).prop("checked") },
        function(data){
            //alert(data);

            //window.location.href = '<?=$this->url()?>';
            //return false;
        }
      );    
    
    //alert($(this).attr("name"));
    
  });  
  
});
</script>
