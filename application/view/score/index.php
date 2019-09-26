          <div class="row">
            <div class="container-fluid">
              <div class="card">
                <div class="card-header card-header-primary">
                  <h4 class="card-title">トップページ　お知らせ</h4>
                </div>
                <div class="card-body">
                  <div id="typography">
                    <div class="row">
                      <div class="info_row">
                        <span class="info_title">2019/4/6</span>
                        <img class="info_img" src="<?=$this->url_images("faces/ball.jpeg")?>">
                          <span class="info_msg">松毬会のスコア管理ページができました</span>
                      </div>
                      
                      <!--
                      <div class="info_row">
                        <span class="info_title">2019/2/10</span>
                        <img class="info_img" src="<?=$this->url_images("faces/ball.jpeg")?>">
                          <span class="info_msg">相馬までご連絡ください</span>
                      </div>                      
                      -->
                    </div>
                  </div>
                </div>
              </div>
            </div>


            </div>            

<?php 
  if ($this->value("user_auth") === "admin"){
?>                    

            <div class="row">

            <div class="col-md-6 col-sm-12">
              <div class="card card-stats">
                <div class="card-header card-header-info card-header-icon">
                  <div class="card-icon">
                    <i class="fa fa-plus-square"></i>
                  </div>
                  <p class="card-category">開催日登録</p>
                  <h4 class="card-title">日程、参加者を新規登録します</h4>
                  <div style="text-align: right">
                    <a href="<?=$this->url_view("compe_edit","score",array("new"))?>" class="btn btn-info btn-round">登録</a>
                  </div>                  
                  
                </div>
                <div class="card-footer">
                  <div class="stats text-danger">
                    <i class="material-icons">lock</i>管理者のみ表示。登録可能
                  </div>
                </div>
              </div>
            </div>
<?php 
  $compe_list = $this->value("compe_list");
  if (count($compe_list)){
?>              
            <div class="col-md-6 col-sm-12">
              <div class="card card-stats">
                <div class="card-header card-header-success card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">stars</i>
                  </div>
                  <p class="card-category">スコア登録</p>
                  <h4 class="card-title">開催日が過ぎた未完了のコンペ一覧です</h4>
                  <div style="text-align: right">
                    
                  
                  
<?php 
  foreach ($compe_list as $row) {

?>                                
                  <h4 class="card-title"><?=$row["compe_name"]?>のスコアが未完了です　<a href="<?=$this->url_view("score_edit","score",array($row["compe_id"]))?>" class="btn btn-success btn-round">登録</a></h4>
                  
<?php 
    }
?>                                              
                  </div>                  
                </div>
                <div class="card-footer">
                  <div class="stats text-danger" >
                    <i class="material-icons">lock</i>管理者のみ表示。登録可能
                  </div>
                </div>
              </div>
            </div> 
<?php 
  }
?>                            
          </div>

<?php     
  } //end 管理者権限専用
?>                            
