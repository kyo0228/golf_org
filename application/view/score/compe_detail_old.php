<div class="row">
  
  <div class="col-lg-6 col-md-12">
    <div class="card card-stats">
      <div class="card-header card-header-primary card-header-icon">
        <div class="card-icon">第４回</div>
        <div class="card-header-flex">
          <p class="card-category">2019.02.10(日)　雪</p>
          <a href="<?=$this->url_view("compe_edit","score")?>" class="btn btn-primary btn-round">コンペ設定</a>
        </div>
        <h4 class="card-title">リバーサイドフェニックスCC　参加数：18人</h4>
        <img class="card-image"  src="<?=$this->url_images("compe/DSC_1108.JPG")?>">        
      </div>
    </div>
  </div>


  <div class="col-lg-4 col-md-6 col-sm-12">
    <div class="card card-stats">
      <div class="card-header card-header-success card-header-icon">
        <div class="card-icon">スコア登録</div>
        <div class="card-header-flex">
          <a href="<?=$this->url_view("score_edit","score")?>" class="btn btn-success btn-round">登録</a>
        </div>                                    
        <p class="card-category">登録完了を行うと編集不可になり、一般権限で結果が表示されます。</p>
        <p class="card-category">当日の写真や感想なども登録完了前に行ってください。</p>
        <button type="button" class="btn btn-warning  btn-round" data-toggle="modal" data-target="#exampleModal">登録完了</button>          

      </div>
      <div class="card-footer">
        <div class="stats text-danger" >
          <i class="material-icons">lock</i>管理者のみ表示。登録可能
        </div>
      </div>
    </div>
  </div>              
  
</div>

<div class="row">
  
  <div class="col-lg-12 col-md-12">
    <div class="card">
      <div class="card-header card-header-warning">
        <h4 class="card-title">結果</h4>

      </div>
      <div class="card-body table-responsive">
        <table class="table table-hover">
          <thead class="text-warning">
            <th>順位</th>
            <th>氏名</th>
            <th>グロス</th>
            <th>ネット</th>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td>稲川俊之</td>
              <td>79</td>
              <td>68.7</td>
            </tr>
            <tr>
              <td>2</td>
              <td>稗田眞久</td>
              <td>86</td>
              <td>71.1</td>
            </tr>
            <tr>
              <td>3</td>
              <td>井上勝年</td>
              <td>93</td>
              <td>72.6</td>
            </tr>
            <tr>
              <td>4</td>
              <td>増田茂</td>
              <td>82</td>
              <td>73.1</td>
            </tr>
            <tr>
              <td>5</td>
              <td>稲川俊之</td>
              <td>79</td>
              <td>68.7</td>
            </tr>
            <tr>
              <td>6</td>
              <td>稗田眞久</td>
              <td>86</td>
              <td>71.1</td>
            </tr>
            <tr>
              <td>7</td>
              <td>井上勝年</td>
              <td>93</td>
              <td>72.6</td>
            </tr>
            <tr>
              <td>8</td>
              <td>増田茂</td>
              <td>82</td>
              <td>73.1</td>
            </tr>
            <tr>
              <td>9</td>
              <td>稲川俊之</td>
              <td>79</td>
              <td>68.7</td>
            </tr>
            <tr>
              <td>10</td>
              <td>稗田眞久</td>
              <td>86</td>
              <td>71.1</td>
            </tr>
            <tr>
              <td>11</td>
              <td>井上勝年</td>
              <td>93</td>
              <td>72.6</td>
            </tr>
            <tr>
              <td>12</td>
              <td>増田茂</td>
              <td>82</td>
              <td>73.1</td>
            </tr>            
          </tbody>
        </table>
      </div>
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
        <button type="button" class="btn btn-primary">完了</button>
      </div>
    </div>
  </div>
</div>
