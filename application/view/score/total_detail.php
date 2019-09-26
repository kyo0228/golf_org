<div class="row">
  <div class="col-lg-12">
    <div class="card card-stats">
      <div class="card-header card-header-primary card-header-icon">
        <div class="card-icon">個人集計詳細</div>
        
        <div class="card-header-flex">
          <a href="<?=$this->url_view("total_list","score",array($this->value("sort"),$this->value("term")))?>" class="btn btn-danger btn-round">戻る</a>
        </div>        
        <p class="card-category">集計期間：<?php if($this->value("term") == "all"){print("全期間");}else{print($this->value("term")."年度");}?></p>
        
        <h4 class="card-title" style='margin-top: 10px;'><?=$this->value("member_detail",0,"member_name")."さんのスコア詳細"?></h4>
        
        <h5 class="card-title">グロス:</h5>        
        <h5 class="card-title">
          <i class="fa fa-thumbs-o-up text-warning"  style="font-size: 1rem"></i><?=$this->value("member_detail",0,"gross_min")?>
          <i class="fa fa-thumbs-o-down text-danger" style="font-size: 1rem"></i><?=$this->value("member_detail",0,"gross_max")?>          
        </h5>
        
        <h5 class="card-title">ネット</h5>        
        <h5 class="card-title">
          <i class="fa fa-thumbs-o-up text-warning"  style="font-size: 1rem"></i><?=$this->value("member_detail",0,"net_min")?>
          <i class="fa fa-thumbs-o-down text-danger" style="font-size: 1rem"></i><?=$this->value("member_detail",0,"net_max")?>
        </h5>
        
      
      </div>
<?php

  $data = $this->value("member_data");
  
  foreach ($data as $row) {
  
    $clr_net="";
    $clr_grs="";
    
    if ($row["gross"] == $this->value("member_detail",0,"gross_min")){
      $clr_grs="text-warning";
    }elseif ($row["gross"] == $this->value("member_detail",0,"gross_max")){
      $clr_grs="text-danger";
    }
    if ($row["net"] == $this->value("member_detail",0,"net_min")){
      $clr_net="text-warning";
    }elseif ($row["net"] == $this->value("member_detail",0,"net_max")){
      $clr_net="text-danger";
    }
    
    $compe_date= date('Y/m/d',  strtotime($row["compe_date"]));


?>        
      
      <div class="card-body row" style="text-align: left;">
        <div class="col-lg-3 col-md-12">
          <p class="card-title"><?=$row["compe_name"]?><br />(<?=$compe_date?>)</p>
        </div>

        <div class="score_other col-lg-9 col-md-12">
          <div class="form-group x1">
            <label class="bmd-label-floating">グロス</label>
            <p class="<?=$clr_grs?>"><?=$row["gross"]?></p>
          </div>
          <div class="form-group x1">
            <label class="bmd-label-floating">ネット</label>
            <p class="<?=$clr_net?>"><?=$row["net"]?></p>
          </div>          
        </div>          
          
      </div>

<?php
  }    
?>  

    </div>              
  </div>  
</div>

<script>
  
    
</script>