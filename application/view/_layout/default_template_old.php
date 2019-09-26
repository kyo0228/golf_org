<?php
	/*
	 * デフォルトテンプレート
	 */
?>
<!doctype html>
<html class="no-js" lang="">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?= $this->output_title()?>
    <meta name="keywords" content="<?= $this->keywords ?>">
    <meta name="description" content="<?= $this->description ?>">
    <meta name="robots" content="noindex, follow" />
    
    <?=$this->output_resource_head()?>

<!--
    <link rel="manifest" href="site.webmanifest">
    <link rel="apple-touch-icon" href="icon.png">
favicon.icoはルートディレクトに配置 -->

   <!-- 
    
    <link rel="stylesheet" href="css/main.css">
    -->
  </head>
  <body>
    <header id="site-header">
      <div>
        <p id="header_title">有価証券報告書</p>
        <p id="header_sub">集計ツール</p>
      </div>

      <ul id="nav">
          <li><a href="<?=$this->url_view("index","site")?>">登録・参照</a></li>
          <li><a href="<?=$this->url_view("list","site")?>">集計</a></li>
          <li><a href="<?=$this->url_view("view_element","master")?>">科目</a></li>
          <li><a href="<?=$this->url_view("setting","master")?>">設定</a></li>
      </ul>

    </header>

    <div class="content">
      
      <div id ="page_title" style="">
        <h1 ><?=$this->name?></h1>
      </div>
      
      <?=$this->output_error()?>	
      <?=$this->layout()?>

    </div>
    <footer class="site-footer">
        <p class="copyright">@2018 ks</p>
    </footer>

    <!-- Google Analytics: UA-XXXXX-YをあなたのIDに変更 
    <script>
        window.ga=function(){ga.q.push(arguments)};ga.q=[];ga.l=+new Date;
        ga('create','UA-XXXXX-Y','auto');ga('send','pageview')
    </script>
    <script src="https://www.google-analytics.com/analytics.js" async defer></script>
    -->
  </body>
</html>
