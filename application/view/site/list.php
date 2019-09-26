<div id="main">  
  <div class="groupbox">
    <button id="btn_csv" >CSV出力</button>
<?php          
  $totalAry = $this->value(ufo_total);
  
  if (count($totalAry)){
    
    $header = $totalAry[0];
    $nondisp = array("有報ID","證券コード","取得元","企業コード","企業名");
    $noncalc = array("タイトル","登録日");
?> 
      <div style="height:70vh; overflow: auto;">
      <table _fixedhead="rows:1; cols:2">
        <thead>  
          <tr>
<?php     
          foreach ($header as $key => $value) {
            $style="";
            if (array_search($key, $nondisp) === false){
              $col = str_replace("-", "<br />", $key);
              if ($key === "タイトル"){$style="style='min-width:600px;'";}
?>        
            <th <?=$style?>><?=$col?></th>
<?php                    
            }
          }
?> 
          </tr>
      </thead>  
      <tbody>

<?php     
          $cnt=1;
          foreach ($totalAry as $row) {
            $color="";
            if($cnt%2==0){$color="background-color: #f0eace;";}
            $cnt++;
?>        
        <tr> 
<?php     
            
            foreach ($row as $key => $value) {
              $align="";

              if (array_search($key, $nondisp) === false){
                if (array_search($key, $noncalc) === false){
                  $align="text-align:right;";
                  $value = number_format($value);
                }    
                if ($key === "登録日"){$value = date('Y-m-d', strtotime($value));}
                
?>                  
            <td style="<?=$align?><?=$color?>"><?=$value?></td>
<?php     
              }
            }
?>                              
        </tr>            
<?php       
            
          }
?>           

      </tbody>  
        
        
        
      </table>  
      </div>    
    
<?php          
  }
?>                  
  </div>
  
</div>          

<script>

$(function(){

  $(document).ready(function(){
    FixedMidashi.create();
  });

  $('#btn_csv').click(function(){
    var url = '<?=$this->url()."/?csv=output"?>';
    
    window.location.href = url;
    return false;
  });  
});
</script>

