<div class="row">

<?php

  $compe_list = $this->value("compe_list");
  foreach ($compe_list as $row) {
    
?>  
  <div class="col-lg-6">
    <div class="card card-stats">
      <div class="card-header card-header-info card-header-icon">
        <div class="card-icon"><?=$row["compe_name"]?></div>
        <div class="card-header-flex">
          <p class="card-category"><?=$row["compe_date_val"]?></p>
          <a href="<?=$this->url_view("compe_detail","score",array($row["compe_id"]))?>" class="btn btn-info btn-round">詳細</a>
        </div>
        <h4 class="card-title"><?=$row["compe_course_val"]?></h4>
<?php
  if ($row["compe_play_val"]){
?>    
        <h4 class="card-title text-danger"><?=$row["compe_play_val"]?></h4>
<?php
  }
?>      
      </div>

      <div class="card-body" style="text-align: left;" >
        <div class="table-responsive">
          <table class="table">
            <thead class=" text-primary">
              <tr>
                <th>名前</th>
                <th>グロス</th>
                <th>ネット</th>
              </tr>

            </thead>
            <tbody>
              
<?php
  if (!$row["finish_flg"]){
?>    
              <tr>
                <td colspan="3" class="text-danger">スコア確定待ち！</td>
              </tr>              
        
<?php
  }else{
?>            
              <tr>
                <td><img src="<?=$this->url_images("crown1.png")?>" width="50px;" />&nbsp;<?=$row["finish_1_name"]?></td>
                <td><?=$row["finish_1_gross"]?></td>
                <td class="text-warning"><?=$row["finish_1_net"]?></td>
              </tr>
              <tr>
                <td><img src="<?=$this->url_images("crown2.png")?>" width="50px;" />&nbsp;<?=$row["finish_2_name"]?></td>
                <td><?=$row["finish_2_gross"]?></td>
                <td class="text-warning"><?=$row["finish_2_net"]?></td>
              </tr>
              <tr>
                <td><img src="<?=$this->url_images("crown3.png")?>" width="50px;" />&nbsp;<?=$row["finish_3_name"]?></td>
                <td><?=$row["finish_3_gross"]?></td>
                <td class="text-warning"><?=$row["finish_3_net"]?></td>
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
  
<?php
  }
?>    
  
</div>

