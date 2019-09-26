<div class="row">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header card-header-primary">
        <h4 class="card-title">設定</h4>
      </div>
      <div class="card-body">
        <div id="edit_btn_area" style="margin-bottom: 10px;">
<?php
  $url = $this->url_view("compe_detail","score",array($this->value("compe_data","compe_id")));
  if ($this->value("compe_data","compe_id") == "new"){
    $url = $this->url_view("index","score");
  }
?>          
          <a href="<?=$url?>" class="btn btn-danger btn-round">キャンセル</a>
          <button type="button" class="btn btn-primary pull-right" onclick="compe_save();">登録</button>
        </div>                  
        
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="bmd-label-floating">開催日</label>
              <input type="text" id="compe_date" class="form-control datepicker" value="<?=$this->value("compe_data","compe_date")?>"/>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="bmd-label-floating">開催名(第10回、xx年1月会など)</label>
              <input type="text" id="compe_name" class="form-control" value="<?=$this->value("compe_data","compe_name")?>"/>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label class="bmd-label-floating">ゴルフ場</label>
              <input type="text" id="course" class="form-control" value="<?=$this->value("compe_data","course")?>"/>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label>参加者</label>
            </div>  
            <div class="form-group">
              <div class="compe_member">

<?php
    $member_list = $this->value("member_list");
    
    foreach ($member_list as $row) {
      $c = "";
      if ($row["is_check"]){
        $c = "checked='checked'";
      }
?>              
                <div class="form-check">
                    <label class="form-check-label">
                        <input class="form-check-input" type="checkbox" id="<?='member_'.$row["member_id"]?>" <?=$c?>>
                        <?=$row["member_name"]?>
                        <span class="form-check-sign">
                            <span class="check"></span>
                        </span>
                    </label>
                </div>                
<?php                
    }
?>
              </div>
            </div>
          </div>
        </div>
        

        <div class="clearfix"></div>
      </div>
    </div>
  </div>
  
<?php if ($this->value("compe_data","compe_id") !== "new"){?>
  
  <div class="col-md-6">
    <form id="edit_form" name="edit_form" method="post" enctype="multipart/form-data">
      <input type="hidden" name="compe_id"  value="<?=$this->value("compe_data","compe_id")?>"/>
      <input type="hidden" name="group_id"  value="<?=$this->value("compe_data","group_id")?>"/>
    <div class="card">
      <div class="card-header card-header-info">
        <h4 class="card-title">当日の情報</h4>
      </div>
      <div class="card-body">
        <div id="edit_btn_area" style="margin-bottom: 10px;">
          <a href="<?=$this->url_view("compe_detail","score",array($this->value("compe_data","compe_id")))?>" class="btn btn-danger btn-round">キャンセル</a>
          <button type="button" class="btn btn-info pull-right" onclick="compe_save_today();">登録</button>
        </div>                  

        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label class="bmd-label-floating">当日の天気</label>
              <input type="text" id="weather" name="weather" class="form-control" value="<?=$this->value("compe_data","weather")?>"/>
            </div>
          </div>            
        </div>        
        
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label>メモ、開催日の感想</label>
              <textarea id="memo" name="memo" class="form-control" rows="3"><?=$this->value("compe_data","memo")?></textarea>
            </div>
          </div>
        </div>  

        
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label>当日の写真 ※3枚まで</label>
            </div>              
            
            <div class="compe_member">
              <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                 <div class="fileinput-new thumbnail img-raised"> 
                  <img name="photo_1" src="<?=$this->value("compe_data","image_01_path")?>" alt="img1">
                 </div>
                 <div id="selected_img1_area" class="fileinput-preview fileinput-exists thumbnail img-raised"></div>
                 <div>
                <span class="btn btn-raised btn-round btn-rose btn-file">
                   <span class="fileinput-new">画像選択</span>
                   <span class="fileinput-exists">画像変更</span>
                   <input type="file" id="selected_img1" name="selected_img1" />
                </span>
                      <a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput">
                      <i class="fa fa-times"></i>取り消し</a>
                 </div>
              </div>

              <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                 <div class="fileinput-new thumbnail img-raised">
                  <img name="photo_2" src="<?=$this->value("compe_data","image_02_path")?>" alt="img2">
                 </div>
                 <div id="selected_img2_area" class="fileinput-preview fileinput-exists thumbnail img-raised"></div>
                 <div>
                <span class="btn btn-raised btn-round btn-rose btn-file">
                   <span class="fileinput-new">画像選択</span>
                   <span class="fileinput-exists">画像変更</span>
                   <input type="file" id="selected_img2" name="selected_img2" />
                </span>
                      <a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput">
                      <i class="fa fa-times"></i>取り消し</a>
                 </div>
              </div>

              <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                 <div class="fileinput-new thumbnail img-raised">
                  <img name="photo_3" src="<?=$this->value("compe_data","image_03_path")?>" alt="img3">
                 </div>
                 <div id="selected_img3_area" class="fileinput-preview fileinput-exists thumbnail img-raised"></div>
                 <div>
                <span class="btn btn-raised btn-round btn-rose btn-file">
                   <span class="fileinput-new">画像選択</span>
                   <span class="fileinput-exists">画像変更</span>
                   <input type="file" id="selected_img3" name="selected_img3" />
                </span>
                      <a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput">
                      <i class="fa fa-times"></i>取り消し</a>
                 </div>
              </div>                

            </div>  
          </div>
        <!--
          <div class="col-md-6">
            <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                <div class="fileinput-new thumbnail img-circle img-raised">
              <img src="https://epicattorneymarketing.com/wp-content/uploads/2016/07/Headshot-Placeholder-1.png" alt="...">
                </div>
                <div class="fileinput-preview fileinput-exists thumbnail img-circle img-raised"></div>
                <div>
                <span class="btn btn-raised btn-round btn-rose btn-file">
                    <span class="fileinput-new">Add Photo</span>
              <span class="fileinput-exists">Change</span>
              <input type="file" name="..." /></span>
                    <br />
                    <a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Remove</a>
                </div>
            </div>
          </div>
        -->
        </div>          
        <div class="clearfix"></div>
      </div>
    </div>
  </form>        
  </div>    
  
<?php }?>  
</div>


<script>
  
  function compe_save() {
    var url = '<?=$this->value("edit_url")?>';
    
    var member = [];
    $('.form-check-label :checkbox:checked').each(function() {
      member.push($(this).attr('id'));
    });
    
    var JSONdata = {
        compe_id: '<?=$this->value("compe_data","compe_id")?>',
        group_id: '<?=$this->value("group_data","group_id")?>',
        compe_date: $("#compe_date").val(),
        compe_name: $("#compe_name").val(),
        course: $("#course").val(),
        member: member
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
            
<?php
  if ($this->value("compe_data","compe_id") == "new"){    
?>                          
            window.location.href = "<?=$this->url_view("compe_list","score")?>";          
<?php    
  }else{
?>      
            window.location.href = "<?=$this->url_view("compe_detail","score",array($this->value("compe_data","compe_id")))?>";          
<?php    
  }
?>                                

          }

        },
        error : function(data) {
          $('#edit_btn_area').append("<p class='error_message text-danger'>通信に失敗しました。少し時間をおいて実行してください</p>");
        }
    });    
  }
  
  function compe_save_today() {
    var url = '<?=$this->value("edit_url_today")?>';

    var form = document.getElementById("edit_form");
    var post_fd = new FormData(form);	
    
    $.ajax({
      url: url,
      type: 'POST',
      data: post_fd,
      dataType : "json",
      processData: false,
      contentType: false,
      async: false,    
      success : function(data) {
        //渡しかたが違うからかほかとちょっと違う
        var detailAry =data;
        
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
    
/*    
    $('#selected_img1_area img').attr('id', 'selected_img1');
    $('#selected_img2_area img').attr('id', 'selected_img2');
    $('#selected_img3_area img').attr('id', 'selected_img3');
    
    
    
    var JSONdata = {
        compe_id: '<?=$this->value("compe_data","compe_id")?>',
        group_id: '<?=$this->value("group_data","group_id")?>',
        weather: $("#weather").val(),
        memo: $("#memo").val(),
        photo_1: $('#selected_img1').attr('src'),
        photo_2: $('#selected_img2').attr('src'),
        photo_3: $('#selected_img3').attr('src')
    };

    alert(JSON.stringify(JSONdata));
 */   
    
    /*これ書いたらサーバー側でPOSTできなかった
     *  contentType: 'application/JSON',
        dataType : 'JSON',
    
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
    
     */
  }  
  
  
</script>

