<form action="" method ="post">
  <div id="main">  
    
    <div class="groupbox">
<?php
  $condition_01 ="";
  $condition_02 ="";
  $condition_03 ="";
    
    if ($this->value("rdo_condition") == "2"){$condition_02 = "checked='checked'";}
    elseif ($this->value("rdo_condition") == "3"){$condition_03 = "checked='checked'";}
    else{$condition_01 = "checked='checked'";}
?>            
      <p>「科目一覧」のコンテキスト検索方法</p>
      <div>
        <input type="radio" id="rdo_condition_01" name="rdo_condition" value="1" <?=$condition_01?> />
        <label for="rdo_condition_01">条件指定しない</label>
      </div>  
      <div>
        <input type="radio" id="rdo_condition_02" name="rdo_condition" value="2" <?=$condition_02?> />
        <label for="rdo_condition_02">除外項目を除いて検索</label>
      </div>      
      <div>
        <input type="radio" id="rdo_condition_03" name="rdo_condition" value="3" <?=$condition_03?> />
        <label for="rdo_condition_03">集計項目で検索</label>        
      </div>      
      
      <input type="submit" name="btn_condition" value="登録">     
    </div>    
    
    <div class="groupbox">
      <p class="text-bold">除外項目</p>
      <p>確認用に除外するコンテキスト(先頭一致で判定)</p>
      <div>
        <textarea style="width: 20rem; height: 10rem;" name="txt_context_cond_del"><?=$this->value("txt_context_cond_del")?></textarea>  
      </div>
      <input type="submit" name="btn_context_cond_del" value="登録">

    
    </div>
    <div class="groupbox">
      <p class="text-bold">コンテキスト表示名</p>
      <p>集計で使用するコンテキスト(完全一致) ※出力用に表示名を設定できます。項目名@表示名で登録してください</p>
      <div>
        <textarea style="width: 50rem; height: 10rem;" name="txt_context_cond_add"><?=$this->value("txt_context_cond_add")?></textarea>  
      </div>
      <input type="submit" name="btn_context_cond_add" value="登録">

    
    </div>    
    

    
    <div class="groupbox">
      <p>コンテキストIDについて</p>
      
      <strong>命名規約</strong><br />
      コンテキストIDは、以下の３項目を組み合わせたものです。<br />
      コン</p>
      <blockquote><p>テキストID：　｛相対年度｝｛連結・個別｝｛期間・時点｝</p></blockquote>
      <table style="color: #333333;" border="1">
      <tbody>
      <tr>
      <td><strong>No.</strong></td>
      <td><strong>項目</strong></td>
      <td><strong>設定値</strong></td>
      <td><strong>説明</strong></td>
      </tr>
      <tr>
      <td>1</td>
      <td rowspan="12">｛相対年度｝</td>
      <td>CurrentYear</td>
      <td>当年度</td>
      </tr>
      <tr>
      <td>2</td>
      <td>Interim</td>
      <td>中間期</td>
      </tr>
      <tr>
      <td>3</td>
      <td>Prior1Year</td>
      <td>前年度</td>
      </tr>
      <tr>
      <td>4</td>
      <td>Prior1Interim</td>
      <td>前中間期</td>
      </tr>
      <tr>
      <td>5</td>
      <td>Prior2Year</td>
      <td>前々年度</td>
      </tr>
      <tr>
      <td>6</td>
      <td>Prior{数値}Year</td>
      <td>{数値}年度前</td>
      </tr>
      <tr>
      <td>7</td>
      <td>CurrentYTD</td>
      <td>当四半期累計期間</td>
      </tr>
      <tr>
      <td>8</td>
      <td>CurrentQuarter</td>
      <td>当四半期会計期間</td>
      </tr>
      <tr>
      <td>9</td>
      <td>Prior{数値}YTD</td>
      <td>{数値}年度前同四半期累計期間</td>
      </tr>
      <tr>
      <td>10</td>
      <td>Prior{数値}Quarter</td>
      <td>{数値}年度前同四半期会計期間</td>
      </tr>
      <tr>
      <td>11</td>
      <td>LastQuarter</td>
      <td>前四半期会計期間</td>
      </tr>
      <tr>
      <td>12</td>
      <td>Prior{数値}LastQuarter</td>
      <td>{数値}年度前の前四半期会計期間</td>
      </tr>
      <tr>
      <td>13</td>
      <td rowspan="2">｛連結・個別｝</td>
      <td>Consolidated</td>
      <td>連結</td>
      </tr>
      <tr>
      <td>14</td>
      <td>NonConsolidated</td>
      <td>個別</td>
      </tr>
      <tr>
      <td>15</td>
      <td rowspan="2">｛期間・時点｝</td>
      <td>Instant</td>
      <td>時点</td>
      </tr>
      <tr>
      <td>16</td>
      <td>Duration</td>
      <td>期間</td>
      </tr>
      </tbody>
      </table>
      <h3>設定例</h3>
      <p><strong>当期連結時点</strong></p>
      <table style="color: #333333;" border="1">
      <tbody>
      <tr>
      <td>コンテキストID</td>
      <td>CurrentYearConsolidatedInstant</td>
      </tr>
      <tr>
      <td>シナリオ(scenario)</td>
      <td>設定無し</td>
      </tr>
      <tr>
      <td>説明</td>
      <td>報告対象となる会計年度(CurrentYear)の期末日時点(Instant)の<br />
      連結(Consolidated)の財務情報を報告するために利用します。</td>
      </tr>
      </tbody>
      </table>
      <h3 style="color: #666666;">前期個別期間</h3>
      <table style="color: #333333;" border="1">
      <tbody>
      <tr>
      <td>コンテキストID</td>
      <td>Prior1YearNonConsolidatedDuration</td>
      </tr>
      <tr>
      <td>シナリオ(scenario)</td>
      <td>&lt;jpfr-oe:NonConsolidated/&gt;</td>
      </tr>
      <tr>
      <td>説明</td>
      <td>報告対象となる会計年度の前期(Prior1Year)の期間(Duration)の<br />
      個別(NonConsolidated)の財務情報を報告するために利用します。</td>
      </tr>
      </tbody>
      </table>
      <h3>例外：提出日時点のコンテキスト</h3>
      <p>提出日時点を表すコンテキストは、上記の命名規約にかかわらず「DocumentInfo」固定です。</p>      
    
    </div>    
    
    <div class="groupbox">
      <p>「米国基準(US-GAAP)」と「国際基準(IFRS)」のXBRL</p>
      
日本基準のXBRLは、サマリーと財務諸表の両方に対応している点と、上場企業の９割が採用している点で、まずはこちらから着手するのが良いでしょう。この対応だけで完成といってもいいくらいのシェアです。サマリー(summary)とは、有報や決算短信のはじめの方に載ってる表のことです。売上・利益・資産などが載ってるやつです。
「米国基準(US-GAAP)」と「国際基準(IFRS)」のXBRLは、2014年以降、サマリーのみの対応になりました。財務諸表のデータは取得が困難になっています。（金融庁の説明を見るに、2018年から再び対応する動きはあるようです。）      
      
      <p>https://srbrnote.work/archives/1148</p>
      
    </div>    
    
  </div>          
</form>  
