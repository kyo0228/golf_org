<div class="row">
  <div class="col-lg-12">
    <div class="card card-stats">
      <div class="card-header card-header-primary card-header-icon">
        <div class="card-icon">年間集計</div>
        <p class="card-category">指定期間の平均スコアで集計をおこないます</p>
<?php
  $compe_term = $this->value("compe_term");
  if (count($compe_term)){
?>        
        <div class="row">
          <div class="col-lg-3 col-md-5">
            <div class="form-group">
              
                <label class="form-check-label">
                    期間指定
                    <span class="circle">
                        <span class="check"></span>
                    </span>
                </label>
                  
                <select class="form-control" id="cmb_term" onchange="chenge_condition()">
<?php
    foreach ($compe_term as $row) {
?>                          
                  <option <?php  if ($this->value("term") === $row["compe_term"]){print("selected='selected'");}?> value='<?=$row["compe_term"]?>'><?=$row["display"]?></option>
<?php
    }
?>                          
                  <option <?php  if ($this->value("term") === "all"){print("selected='selected'");}?> value='all'>全期間</option>
                </select>
                  
              
            </div>
          </div>
          <div class="col-lg-2 col-md-5">
            <div class="form-group">
              
                <label class="form-check-label">
                    並び順
                </label>
                
                <select class="form-control" id="cmb_sort" onchange="chenge_condition()">
                  <option <?php  if ($this->value("sort") === 'net'){print("selected='selected'");}?> value="net">ネット</option>
                  <option <?php  if ($this->value("sort") === 'grs'){print("selected='selected'");}?> value="grs">グロス</option>
                  <option <?php  if ($this->value("sort") === 'num'){print("selected='selected'");}?> value="num">会員番号</option>
                </select>
              
            </div>
          </div>          
        </div>
<?php
  }else{
?> 
        <p class='text-danger'>集計データがありません</p>
        <p class='text-danger'>sort:<?=$this->value("sort")?></p>
        <p class='text-danger'>term:<?=$this->value("term")?></p>
        
<?php
  }
?>                
      </div>
      
      
<?php

  $total_year = $this->value("total_year");
  
  $rank=1;
  foreach ($total_year as $row) {
  
    $path = "";
    $name = "";
    $clr_net="";
    $clr_grs="";
    $clr_num="";
    if ($this->value("sort") === 'num'){
      //会員順の時は先頭を会員番号にする
      $rank = $row["member_num"];
      $name = $row["member_name"];
      $clr_num="text-warning";
    }else{
      if ($rank === 1) {$path = $this->url_images("crown1.png");}
      if ($rank === 2) {$path = $this->url_images("crown2.png");}
      if ($rank === 3) {$path = $this->url_images("crown3.png");}
      $name = $row["member_name"]."(".$row["member_num"].")";
      
      if ($this->value("sort") === 'grs'){
        $clr_grs="text-warning";
      }else{
        $clr_net="text-warning";
      }
    }

?>        
      
      <div class="card-body row" style="text-align: left;">
        <div class="col-lg-3 col-md-12">        
<?php      
    if ($path){
?>             
          <p><img src="<?=$path?>" width="50px;" />&nbsp;<?=$name?></p>                
<?php      
    }else{
?>      
          <p><span class="<?=$clr_num?>"><?=$rank?></span>&nbsp;&nbsp;&nbsp;<?=$name?></p>        
<?php      
    }
?>                              
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
          <div class="form-group x1">
            <label class="bmd-label-floating">参加</label>
            <p class=""><?=$row["round"]?></p>
          </div>
          <div class="form-group x1">
            <a href="<?=$this->url_view("total_detail","score",array($row["member_id"],$this->value("sort"),$this->value("term")))?>" class="btn btn-info btn-round">詳細</a>
          </div>
          
        </div>          
          
      </div>

<?php
    $rank++;      
  }    
?>  

    </div>              
  </div>  
</div>

<script>
  
  function chenge_condition() {
    var url = '<?=$this->value("this_url")?>';
    
    var term = $('#cmb_term').val();
    var sort = $('#cmb_sort').val();
    url = url.replace("[term]", term);
    url = url.replace("[sort]", sort);
    
    window.location.href = url;
    return false;
  }
    
</script>