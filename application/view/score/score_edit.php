<div class="row">
  <div class="col-lg-12">
    <div class="card card-stats">
      <div class="card-header card-header-primary card-header-icon">
        <div class="card-icon">スコア登録</div>
          <div style="margin-bottom: 10px;">
            <a href="<?=$this->url_view("compe_detail","score",array($this->value("compe_data","compe_id")))?>" class="btn btn-danger btn-round">キャンセル</a>            
          </div>
          <div class="card-body" style="text-align:left;">
            <p class="card-category">順位が未入力の場合は仮順位(ネット、グロスのスコアが良い順)で順位が決定します。同点などで調整する場合に手入力をしてください</p>
          </div>
          <button type="submit" class="btn btn-primary pull-right" onclick="score_save();">登録</button>

      </div>
      <div class="card-footer">
        <div class="stats text-danger" >
          <i class="material-icons">lock</i>管理者のみ表示。登録可能
        </div>
      </div>
    </div>
  </div>              
  
</div>

<div class="row">
  
  <div class="col-lg-12 col-md-12">
    <div class="card">
      <div class="card-header card-header-warning">
        <h4 class="card-title"><?=$this->value("compe_data","compe_name")?>　参加者一覧</h4>
      </div>
      <!--<form>-->
        
<?php

  $compe_member = $this->value("compe_member");
  foreach ($compe_member as $row) {
    
?>        
        <div class="card-body row">
          <div class="col-lg-2 col-md-12">
          <p><?=$row["member_name"]."(".$row["member_num"].")"?></p>
          </div>
          
          <div class="score_other col-lg-10 col-md-12">
            <div class="form-group x2">
              <input type="hidden" id="<?='dummy_rank_'.$row["member_id"]?>" value="<?=$row["dummy_rank"]?>">
              <label class="bmd-label-floating"><?=$row["dummy_rank"]?>(仮順位)</label>
              <input type="tel" class="form-control" id="<?='rank_'.$row["member_id"]?>" value="<?=$row["rank"]?>">
            </div>            
            <div class="form-group x1">
              <label class="bmd-label-floating">グロス</label>
              <input type="tel" class="form-control" id="<?='gross_'.$row["member_id"]?>" value="<?=$row["gross"]?>" onchange="score_change(<?=$row["member_id"]?>)">
            </div>
            <div class="form-group x1">
              <label class="bmd-label-floating">ハンデ</label>
              <input type="number" class="form-control" id="<?='handicap_'.$row["member_id"]?>" value="<?=$row["handicap"]?>" onchange="score_change(<?=$row["member_id"]?>)">
            </div>
            <div class="form-group x1">
              <label class="bmd-label-floating">ネット</label>
              <input type="tel" class="form-control text-warning" id="<?='net_'.$row["member_id"]?>" value="<?=$row["net"]?>">
            </div>
<?php  if ($this->value("compe_data","view_mode")){?>
            <div class="form-group x1">
              <label class="bmd-label-floating">会費</label>
              <input type="tel" class="form-control" id="<?='fee_'.$row["member_id"]?>" value="<?=$row["fee"]?>">
            </div>
            <div class="form-group x1">
              <label class="bmd-label-floating">罰金</label>
              <input type="tel" class="form-control" id="<?='penalty_'.$row["member_id"]?>" value="<?=$row["penalty"]?>">
            </div>
            <div class="form-group x2">
              <label class="bmd-label-floating">商品</label>
              <input type="text" class="form-control" id="<?='gift_'.$row["member_id"]?>" value="<?=$row["gift"]?>">
            </div>
            <div class="form-group x3">
              <label class="bmd-label-floating">備考</label>
              <input type="text" class="form-control" id="<?='memo_'.$row["member_id"]?>" value="<?=$row["memo"]?>">
            </div>
<?php  }?>            
            <button type="submit" class="btn btn-danger pull-right" onclick="compe_member_del(<?=$row["member_id"]?>,'<?=$row["member_name"]?>');">不参加</button>
          </div>        
        </div>                  
        
<?php
  }
?>        
      <!--</form>-->
    </div>
  </div>  
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">不参加</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>コンペの写真やスコアの登録は終わりましたか？<br />完了にすると一般メンバーに結果を公開することができます。</p>
        <p>※内容は変更できなくなります</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">まだ終わっていない</button>
        <button type="button" class="btn btn-primary">完了</button>
      </div>
    </div>
  </div>
</div>

<script>
  
  function score_save() {
    
    var url = '<?=$this->value("edit_url")?>';
    var viewmode = '<?=$this->value("compe_data","view_mode")?>';
    
    var memberlist = [
<?php
  foreach ($compe_member as $row) {
    print($row["member_id"].",\n");
  }
?>
    ];


    var data = [];
    for ( var i = 0; i<memberlist.length; i++ ) {
      var id = memberlist[i];
      var member = { 
        rank:$("#rank_"+id).val(),  
        dummy_rank:$("#dummy_rank_"+id).val(),  
        gross:$("#gross_"+id).val(), 
        net:$("#net_"+id).val(), 
        handicap:$("#handicap_"+id).val(), 
<?php  if ($this->value("compe_data","view_mode")){?>        
        fee:$("#fee_"+id).val(), 
        penalty:$("#penalty_"+id).val(),
        gift:$("#gift_"+id).val(),         
        memo:$("#memo_"+id).val(),
<?php  }?>
        id:id,
        viewmode:viewmode
      };
      data.push(member);
    }    

    var JSONdata = {
        compe_id: '<?=$this->value("compe_data","compe_id")?>',
        group_id: '<?=$this->value("group_data","group_id")?>',
        data: data
    };
    
    
    /*これ書いたらサーバー側でPOSTできなかった
     *  contentType: 'application/JSON',
        dataType : 'JSON',
     */
    $.ajax({
        type : 'post',
        url : url,
        data : JSONdata,
        scriptCharset: 'utf-8',
        success : function(data) {
          var detailAry =JSON.parse(data);
          
          $('.error_message').remove();
          if (detailAry["success"] === false){  
            $('#edit_btn_area').append("<p class='error_message text-danger'>"+detailAry["message"]+"</p>");
          }else{
            window.location.href = "<?=$this->url_view("compe_detail","score",array($this->value("compe_data","compe_id")))?>";
          }

        },
        error : function(data) {
          $('#edit_btn_area').append("<p class='error_message text-danger'>通信に失敗しました。少し時間をおいて実行してください</p>");
        }
    });    
  }
  
  

  function compe_member_del(id,nm) {
    var url = '<?=$this->value("del_url")?>';
    
    if (window.confirm(nm+"さんを不参加にします。よろしいですか？")) {
      


      var JSONdata = {
          compe_id: '<?=$this->value("compe_data","compe_id")?>',
          group_id: '<?=$this->value("group_data","group_id")?>',
          member_id: id
      };


      /*これ書いたらサーバー側でPOSTできなかった
       *  contentType: 'application/JSON',
          dataType : 'JSON',
       */
      $.ajax({
          type : 'post',
          url : url,
          data : JSONdata,
          scriptCharset: 'utf-8',
          success : function(data) {
            var detailAry =JSON.parse(data);

            $('.error_message').remove();
            if (detailAry["success"] === false){  
              $('#edit_btn_area').append("<p class='error_message text-danger'>"+detailAry["message"]+"</p>");
            }else{
              window.location.href = "<?=$this->url_view("score_edit","score",array($this->value("compe_data","compe_id")))?>";
            }

          },
          error : function(data) {
            $('#edit_btn_area').append("<p class='error_message text-danger'>通信に失敗しました。少し時間をおいて実行してください</p>");
          }
      });    
      
    }
    return false;
  }  
  
  function score_change(id) {
  
    var g = $("#gross_"+id).val();
    var h = $("#handicap_"+id).val();
    
    if (g && h){
      if ((g - h) >= 0){
        $("#net_"+id).val(g - h); 
      }
    }
    
    
  }
  
    
  
</script>