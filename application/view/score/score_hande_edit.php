<div class="row">
  <div class="col-lg-12">
    <div class="card card-stats">
      <div class="card-header card-header-primary card-header-icon">
        <div class="card-icon">ハンデキャップ登録</div>
          <div style="margin-bottom: 10px;">
            <a href="<?=$this->url_view("compe_detail","score",array($this->value("compe_data","compe_id")))?>" class="btn btn-danger btn-round">キャンセル</a>
            <button type="submit" class="btn btn-primary pull-right" onclick="score_save();">登録</button>
          </div>        

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
        <h4 class="card-title"><?=$this->value("compe_data","compe_name")?>　参加者一覧　ハンデ一括登録</h4>
      </div>
      <!--<form>-->
        
<?php

  $compe_member = $this->value("compe_member");
  foreach ($compe_member as $row) {
    
?>        
        <div class="card-body">
          <div class="score_other">
            <div class="form-group x2">
              <label class="bmd-label-floating"><?=$row["member_name"]?></label>
              <input type="number" class="form-control" id="<?='handicap_'.$row["member_id"]?>" value="<?=$row["handicap"]?>" onchange="score_change(<?=$row["member_id"]?>)">
            </div>
          </div>        
        </div>                  
        
<?php
  }
?>        
      <!--</form>-->
    </div>
  </div>  
</div>

<script>
  function score_save() {
    var url = '<?=$this->value("edit_url")?>';
    
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
        id:id, 
        handicap:$("#handicap_"+id).val()
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
    
  
</script>