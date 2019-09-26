<div class="row">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header card-header-primary">
        <h4 class="card-title">メンバー設定</h4>
      </div>
      <div class="card-body">
          <div id="edit_btn_area" style="margin-bottom: 10px;">
            <a href="<?=$this->url_view("member_list","score")?>" class="btn btn-danger btn-round">キャンセル</a>
            <button type="button" class="btn btn-primary pull-right" onclick="member_save();">登録</button>
          </div>          
          <div class="row">
            <!-- disableの書き方
            <div class="col-md-5">
              <div class="form-group">
                <label class="bmd-label-floating">Company (disabled)</label>
                <input type="text" class="form-control" disabled>
              </div>
            </div>
            -->
            
<?php           
  $is_disabled = "";
  $msg = "※IDは新規登録のみ設定可。未指定の場合は自動採番";
  if ($this->value("mode") === "edit"){
    $is_disabled = "disabled";
    $msg = "※IDは新規登録のみ設定可";
  }
?>                    
            
            
            <div class="col-sm-6">
              <div class="form-group">
                <label class="bmd-label-floating">会員ID(数字のみ)</label>
                <input type="tel" id="member_num" class="form-control <?=$is_disabled?>" maxlength="5" value="<?=$this->value("member_data","member_num")?>">
                
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <span class=""><?=$msg?></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="bmd-label-floating">氏名(性)</label>
                <input type="text" id="member_sei" class="form-control" maxlength="20" value="<?=$this->value("member_data","member_sei")?>">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="bmd-label-floating">氏名(名)</label>
                <input type="text" id="member_mei" class="form-control" maxlength="20" value="<?=$this->value("member_data","member_mei")?>">
              </div>
            </div>
          </div>
        
          <div class="row">
            <div class="col-sm-6">
              <div class="form-check">
<?php            
    $c = "";
    if ($this->value("member_data","member_div")){
      $c = "checked='checked'";
    }
?>                
                  <label class="form-check-label">
                      <input class="form-check-input" type="checkbox" id="member_div" <?=$c?>>
                      管理者(データの登録が可能)
                      <span class="form-check-sign">
                          <span class="check"></span>
                      </span>
                  </label>
              </div>                
            </div>
<?php            
  if ($this->value("mode") === "edit"){
?>                    
            <div class="col-sm-6">
              <div class="form-check">
<?php            
    $c = "";
    if ($this->value("member_data","invalid_flg")){
      $c = "checked='checked'";
    }
?>                                
                  <label class="form-check-label">
                      <input class="form-check-input" type="checkbox" id="invalid_flg" <?=$c?>>
                      無効(コンペの新規登録で表示されなくなります)
                      <span class="form-check-sign">
                          <span class="check"></span>
                      </span>
                  </label>
              </div>                
            </div>
<?php                          
  }
?>
          </div>
          
          
          <div class="clearfix"></div>
      </div>
    </div>
  </div>
<?php            
  if ($this->value("mode") === "edit" && 1===2){
?>                      
  <div class="col-md-6">
    <div class="card card-profile">
      <div class="card-avatar">
        
          <img class="img" src="<?=$this->url_images("faces/ball.jpeg")?>" />
        
      </div>
      <div class="card-body">

        <h4 class="card-title">アイコン画像</h4>
        
        <div class="row">
          <div class="col-sm-12">

            <div class="fileinput fileinput-new text-center" data-provides="fileinput">
              <div>
                <span class="btn btn-raised btn-round btn-rose btn-file">
                   <span class="fileinput-new">画像選択</span>  
                   <span class="fileinput-exists">画像変更</span>
                   <input type="file" id="profile-image" name="photo_1" />
                </span>
                <a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput">
                <i class="fa fa-times"></i>取り消し</a>
                <span class="btn btn-raised btn-round btn-info fileinput-exists" onclick="image_trim2();">
                  <span class="fileinput-exists">トリミング</span>  
                </span>
               
              </div>
              <div class="fileinput-new thumbnail img-raised">
               <!--<img id="image" src="<?=$this->url_images("no-image.jpg");?>" alt="noimage">-->
              </div>
              <div id="member_img" class="fileinput-preview fileinput-exists thumbnail img-raised" style="max-width: 100%;">
                <img src="" alt="noimage">
                 <!-- 切り抜き範囲をhiddenで保持する -->
                 <input type="hidden" id="upload-image-x" name="profileImageX" value="0"/>
                 <input type="hidden" id="upload-image-y" name="profileImageY" value="0"/>
                 <input type="hidden" id="upload-image-w" name="profileImageW" value="0"/>
                 <input type="hidden" id="upload-image-h" name="profileImageH" value="0"/>               

              </div>
              <div>
                <span class="btn btn-raised btn-round btn-primary fileinput-exists" onclick="image_save();">
                  <span class="fileinput-exists">保存</span>  
                </span>                              
              </div>

            </div>
            
          </div>        
        </div>  

      </div>
    </div>
  </div>
<?php                          
  }
?>  
</div>


<script>
  
  
  
  function image_trim2() {
      //alert("aa");


    var options =
    {
        aspectRatio: 1 / 1,
        zoomable:false,
        minCropBoxWidth:100,
        minCropBoxHeight:100

    }

    // 初期設定をセットする
    $('#member_img img').cropper(options);
    
    var id =  $('#member_img img').attr('id', 'selected_img');

  }
  
  function image_save() {

    var cropData = $('#selected_img').cropper("getData");
    $("#upload-image-x").val(Math.floor(cropData.x));
    $("#upload-image-y").val(Math.floor(cropData.y));
    $("#upload-image-w").val(Math.floor(cropData.width));
    $("#upload-image-h").val(Math.floor(cropData.height));    

    //alert(Math.floor(cropData.x));
    //ajax保存サンプルhttps://qiita.com/papillon/items/8d1206a4cb9a589acd16
    
  }
  
  function member_save() {
    var url = '<?=$this->value("edit_url")?>';

    var JSONdata = {
        member_id: '<?=$this->value("member_data","member_id")?>',
        group_id: '<?=$this->value("group_data","group_id")?>',
        member_num: $("#member_num").val(),
        member_sei: $("#member_sei").val(),
        member_mei: $("#member_mei").val(),
        member_div: $("#member_div").prop("checked"),
        invalid_flg: $("#invalid_flg").prop("checked")
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
            window.location.href = "<?=$this->url_view("member_list","score")?>";
          }

        },
        error : function(data) {
          $('#edit_btn_area').append("<p class='error_message text-danger'>通信に失敗しました。少し時間をおいて実行してください</p>");
        }
    });    
  }



  
  
</script>