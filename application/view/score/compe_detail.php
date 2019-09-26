<div class="row">
  
  <div class="col-lg-8 col-md-12">
    <div class="card card-stats">
      <div class="card-header card-header-primary card-header-icon">
        <div class="card-icon"><?=$this->value("compe_data","compe_name")?></div>
        <div class="card-header-flex">
          <p class="card-category"><?=$this->value("compe_data","compe_date_val")?></p>
          <a href="<?=$this->url_view("compe_list","score")?>" class="btn btn-danger btn-round">戻る</a>

        </div>
        <h4 class="card-title"><?=$this->value("compe_data","compe_course_val")?>
          

<?php
  if (!$this->value("compe_data","finish_flg") && $this->value("user_auth") === "admin"){
?>          
          <a href="<?=$this->url_view("compe_edit","score",array($this->value("compe_data","compe_id")))?>" class="btn btn-primary btn-round">設定</a>
<?php
  }
?>                             
        </h4>
<?php
  if ($this->value("compe_data","compe_play_val")){
?>    
        <h4 class="card-title text-danger"><?=$this->value("compe_data","compe_play_val")?></h4>
<?php
  }
?>               
        
<?php
  if ($this->value("compe_data","image_01")){
?>    
        <img class="card-image"  src="<?=$this->value("compe_data","image_01_path")?>">
<?php
  }
?>
<?php
  if ($this->value("compe_data","image_02")){
?>    
        <img class="card-image"  src="<?=$this->value("compe_data","image_02_path")?>">
<?php
  }
?>
<?php
  if ($this->value("compe_data","image_03")){
?>    
        <img class="card-image"  src="<?=$this->value("compe_data","image_03_path")?>">
<?php
  }
?>        
      </div>
    </div>
  </div>

<?php 
  if ($this->value("user_auth") === "admin"){
?>                                            
  <div class="col-lg-4 col-md-6 col-sm-12">
    <div class="card card-stats">
      <div class="card-header card-header-success card-header-icon">
        <div class="card-icon">スコア登録</div>
<?php
  if (!$this->value("compe_data","finish_flg")){
?>
        <div class="card-body" style="text-align: left;margin-top:40px;">
          <p class="card-category">ハンデキャップを事前に一括設定できます</p>
          <a href="<?=$this->url_view("score_hande_edit","score",array($this->value("compe_data","compe_id")))?>" class="btn btn-success btn-round">ハンデ一括登録</a>
          <p class="card-category">グロスを一括登録します。ハンデは事前に登録してください</p>
          <a href="<?=$this->url_view("score_gross_edit","score",array($this->value("compe_data","compe_id")))?>" class="btn btn-success btn-round">グロス一括登録</a>
          <p class="card-category">プレー後のスコアを登録、調整します</p>
          <a href="<?=$this->url_view("score_edit","score",array($this->value("compe_data","compe_id")))?>" class="btn btn-success btn-round">スコア登録</a>          
          <p class="card-category">登録完了を行うと編集不可になり、一般権限で結果が表示されます。</p>
          <p class="card-category">当日の写真や感想なども登録完了前に行ってください。</p>
          <button type="button" class="btn btn-warning  btn-round" data-toggle="modal" data-target="#exampleModal">登録完了</button>
          
          <p class="card-category" style='margin-top: 10px;'>開催情報を削除します</p>
          <button type="button" class="btn btn-danger  btn-round" data-toggle="modal" data-target="#deleteModal">削除</button>          
          
          
        </div>
<?php
  } else{
?>
        <div class="card-body" style="text-align: left;margin-top:40px;">
          <p class="card-category">登録完了を解除して再編集可能にします</p>
          <button type="button" class="btn btn-warning  btn-round" data-toggle="modal" data-target="#cancelModal">登録完了の解除</button>
        </div>        
<?php
  } 
?>                                    
      </div>
      <div class="card-footer">
        <div class="stats text-danger" >
          <i class="material-icons">lock</i>管理者のみ表示。登録可能
        </div>
      </div>
    </div>
  </div>
<?php     
  } //end 管理者権限専用
?>                            
</div>

<div class="row">
  
  <div class="col-lg-12 col-md-12">
    <div class="card">
      <div class="card-header card-header-warning">
        
<?php
  $title = "参加者一覧(".$this->value("compe_data","cnt")."人)";
  $order = "会員ID";
  if ($this->value("compe_data","finish_flg")){
    $title.= "(結果確定)";
    $order = "順位";
  }
?>
        <h4 class="card-title"><?=$title?></h4>
      </div>
<?php
  if ($this->value("compe_data","finish_flg")=="aaa"){
?>    

      <div class="card-body">
        <div class="score_other">
          <div class="form-group x1">
            <label class="text-warning">グロス</label>
          </div>
          <div class="form-group x1">
            <label class="text-warning">ハンデ</label>
          </div>
          <div class="form-group x1">
            <label class="text-warning">ネット</label>
          </div>
<?php  if ($this->value("compe_data","view_mode")){?>          
          <div class="form-group x1">
            <label class="text-warning">会費</label>
          </div>
          <div class="form-group x1">
            <label class="text-warning">罰金</label>
          </div>
          <div class="form-group x2">
            <label class="text-warning">景品</label>
          </div>
          <div class="form-group x3">
            <label class="text-warning">備考</label>
          </div>
<?php  }?>
        </div>        
      </div> 
<?php      
  }
?>      
      
<?php

  $compe_member = $this->value("compe_member");
  foreach ($compe_member as $row) {
    
    $rank = $row["rank"];
    $path = "";
    if ($this->value("compe_data","finish_flg")){
      if ($rank == "1") {$path = $this->url_images("crown1.png");}
      if ($rank == "2") {$path = $this->url_images("crown2.png");}
      if ($rank == "3") {$path = $this->url_images("crown3.png");}      
    }else{
      if (!$rank){
        $rank = $row["dummy_rank"];
      }
    }

?>        
      
      <div class="card-body row">
        <div class="col-lg-3 col-md-12">
          
        
<?php      
    if ($path){
?>             
          <p><img src="<?=$path?>" width="50px;" />&nbsp;<?=$row["member_name"]."(".$row["member_num"].")"?></p>                
<?php      
    }else{
?>      
          <p><span class="text-danger"><?=$rank?></span>&nbsp;&nbsp;&nbsp;<?=$row["member_name"]."(".$row["member_num"].")"?></p>        
<?php      
    }
?>                              
        </div>
        
<?php
    //if ($this->value("compe_data","finish_flg")){
?>        
        <div class="score_other col-lg-9 col-md-12">
          <div class="form-group x1">
            <label class="bmd-label-floating">グロス</label>
            <p class=""><?=$row["gross"]?></p>
          </div>
          <div class="form-group x1">
            <label class="bmd-label-floating">ハンデ</label>
            <p class=""><?=$row["handicap"]?></p>
          </div>
          <div class="form-group x1">
            <label class="bmd-label-floating">ネット</label>
            <p class="text-warning"><?=$row["net"]?></p>
          </div>
<?php  if ($this->value("compe_data","view_mode")){?>          
          <div class="form-group x1">
            <label class="bmd-label-floating">会費</label>
            <p class=""><?=number_format($row["fee"])?></p>
          </div>
          <div class="form-group x1">
            <label class="bmd-label-floating">罰金</label>
            <p class=""><?=number_format($row["penalty"])?></p>
          </div>
          <div class="form-group x2">
            <label class="bmd-label-floating">景品</label>
            <p class=""><?= is_numeric($row["gift"]) ? number_format($row["gift"]) : $row["gift"]; ?></p>
          </div>
          <div class="form-group x3">
            <label class="bmd-label-floating">備考</label>
            <p class=""><?=$row["memo"]?></p>
          </div>
<?php  }?>          
        </div>          
<?php      
    //}
?>                      
          
      </div>

<?php

  }    
?>  
      
      
    </div>
  </div>  
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">スコア登録を完了します</h5>
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
        <button type="button" class="btn btn-primary" onclick="compe_finish('finish');">完了</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">スコア登録の完了を解除します</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>スコア登録の完了を解除してスコアを再登録可能な状態に変更します</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
        <button type="button" class="btn btn-primary" onclick="compe_finish('cancel');">解除</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">開催情報の削除</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>開催情報や登録済みの参加者、スコアなどすべて削除します</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
        <button type="button" class="btn btn-primary" onclick="compe_finish('delete');">削除</button>
      </div>
    </div>
  </div>
</div>



<script>
  function compe_finish(mode) {
    var url = '<?=$this->value("finish_url")?>';
    
    
    

    var JSONdata = {
        compe_id: '<?=$this->value("compe_data","compe_id")?>',
        group_id: '<?=$this->value("group_data","group_id")?>',
        mode: mode
    };
    
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
            if (mode === 'delete'){
              window.location.href = "<?=$this->url_view("compe_list","score")?>";
            }else{
              window.location.href = "<?=$this->url_view("compe_detail","score",array($this->value("compe_data","compe_id")))?>";
            }
            
          }

        },
        error : function(data) {
          $('#edit_btn_area').append("<p class='error_message text-danger'>通信に失敗しました。少し時間をおいて実行してください</p>");
        }
    });    
  }
</script>  