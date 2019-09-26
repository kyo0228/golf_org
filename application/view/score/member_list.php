<?php
  $member_list = $this->value("member_list");
?>

<div class="row">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header card-header-primary">

        <h4 class="card-title ">メンバー　
<?php 
  if ($this->value("user_auth") === "admin"){
?>                                      
          <a href="<?=$this->url_view("member_edit","score",array("new"))?>" class="btn btn-info btn-round">新規登録<div class="ripple-container"></div></a>
<?php 
  }
?>                                      
        </h4>
        
        <p class="card-category"><?=count($member_list)?>名</p>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table">
            <thead class=" text-primary">
<?php 
  if ($this->value("user_auth") === "admin"){
?>                                          
              <th style="width: 50px;">編集</th>
<?php 
  }
?>                                          
              <th style="width: 80px;">ID</th>
              <th>名前</th>
              <!--<th style="width: 200px;">画像</th>-->
              <th style="width: 80px;">管理者</th>
            </thead>
            <tbody>
<?php
    foreach ($member_list as $row) {
      $disable = "";
      if ($row["invalid_flg"]){
        $disable = "bgcolor_disabled";
      }
?>
              <tr class="<?=$disable?>">
<?php 
  if ($this->value("user_auth") === "admin"){
?>                                            
                <td><a href="<?=$this->url_view("member_edit","score",array($row["member_id"]))?>" class="btn btn-info btn-round" style="padding: 7px;"><i class="material-icons">edit</i><div class="ripple-container"></div></a></td>
<?php 
  }
?>                                            
                <td><?=$row["member_num"]?></td>
                <td><?=$row["member_sei"]." ".$row["member_mei"]?></td>
                <!--<td><img class="info_img" src="<?=$this->url_images("faces/ball.jpeg")?>"></td>-->
                <td><?php if ($row["member_div"]) {echo "●"; } ?></td>
              </tr>
<?php                
    }
?>              
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
