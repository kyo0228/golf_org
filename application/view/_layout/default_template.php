<?php
	/*
	 * デフォルトテンプレート
   * 
   * Material Dashboard
   * https://demos.creative-tim.com/material-dashboard/examples/dashboard.html
	 */
?>
<!doctype html>
<html lang="ja">

<head>
<!-- Global site tag (gtag.js) - Google Analytics 2019/5/4-->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-139535677-2"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-139535677-2');
</script>  
  
  <title><?= $this->title?> | <?= $this->name?></title>
  
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="keywords" content="<?= $this->keywords ?>">
  <meta name="description" content="<?= $this->description ?>">
  <meta name="robots" content="noindex, follow" />
  
  <link rel="icon" type="image/x-icon" href="<?=$this->url_images("favicon/".$this->value("group_data","group_id")."/favicon.ico")?>">
  <link rel="apple-touch-icon" sizes="180x180" href="<?=$this->url_images("favicon/".$this->value("group_data","group_id")."/apple-touch-icon-180x180.png")?>">
  <link rel="icon" type="image/png" sizes="192x192" href="<?=$this->url_images("favicon/".$this->value("group_data","group_id")."/android-chrome-192x192.png")?>">

  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <!-- Material Kit CSS -->
  
  <link href="<?=$this->url_css("material-dashboard.css?v=".$this->value("apl_ver"))?>" rel="stylesheet" />
  <link href="<?=$this->url_css("demo.css?v=".$this->value("apl_ver"))?>" rel="stylesheet" />

  <?=$this->output_resource_head()?>
</head>

<body>
  <div class="wrapper ">
    <div class="sidebar" data-color="purple" data-background-color="white" data-image="<?=$this->url_images("sidebar-1.jpg")?>">
      <!--
      Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

      Tip 2: you can also add an image using data-image tag
  -->
      <div class="logo">
        <a href="<?=$this->url_view("index","score")?>" class="simple-text logo-normal">
          <?= $this->title?>
        </a>
      </div>
  
  
  
      <div class="sidebar-wrapper">
<?php 
  if ($this->value("user_auth") === "admin"){
?>                    
        <div class="text-center text-danger" ><h5>管理者権限</h5></div>
<?php     
  }
?>                            
        <ul class="nav">
          <?php 
            if ($this->value("func") === "top"){$active = "active";}else{$active="";}
          ?>            
          <li class="nav-item <?=$active?>">
            <a class="nav-link" href="<?=$this->url_view("index","score")?>">
              <i class="material-icons">dashboard</i>
              <p>トップ</p>
            </a>
          </li>
          <?php 
            if ($this->value("func") === "member"){$active = "active";}else{$active="";}
          ?>                      
          <li class="nav-item <?=$active?>">
            <a class="nav-link" href="<?=$this->url_view("member_list","score")?>">
              <i class="material-icons">person</i>
              <p>メンバー</p>
            </a>
          </li>
          <?php 
            if ($this->value("func") === "compe"){$active = "active";}else{$active="";}
          ?>                                
          <li class="nav-item <?=$active?>">
            <a class="nav-link" href="<?=$this->url_view("compe_list","score")?>">
              <i class="material-icons">content_paste</i>
              <p>スコア履歴</p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="<?=$this->url_view("total_list","score")?>">
              <i class="material-icons">assessment</i>
              <p>スコア集計</p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="<?=$this->url_view("exit","login")?>">
              <i class="material-icons">exit_to_app</i>
              <p>ログアウト</p>
            </a>
          </li>          
        </ul>
      </div>
    </div>
    <div class="main-panel">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <span style="width:200px;"><img class="" style="width: 100%;margin-left: 10px;" src="<?=$this->url_images("shokyukai.png")?>"></span>
            <!--<h1><i class="material-icons">golf_course</i><?= $this->value("group_data","group_name")."&nbsp;".$this->name?></h1>-->
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end">

          </div>
        </div>
      </nav>
      <!-- End Navbar -->
      <div class="content">
        <div class="container-fluid">
        <?=$this->output_error()?>	
        <?=$this->layout()?>
        </div>
      </div>
      <footer class="footer">
        <div class="container-fluid">
          <nav class="float-left">
            <ul>
              <li>
                <a href="<?=$this->url_view("index","score")?>">
                  <?= $this->title?>
                </a>
              </li>
            </ul>
          </nav>
          <div class="copyright float-right">
            &copy;2019, kyo souma
          </div>
          <!-- your footer here -->
        </div>
      </footer>
    </div>
  </div>
  
  
  <!--   Core JS Files   -->
  <script src="<?=$this->url_js("core/jquery.min.js")?>"></script>
  <script src="<?=$this->url_js("core/popper.min.js")?>"></script>
  <script src="<?=$this->url_js("core/bootstrap-material-design.min.js")?>"></script>
  <script src="<?=$this->url_js("plugins/perfect-scrollbar.jquery.min.js")?>"></script>
  <!-- Plugin for the momentJs  -->
  <script src="<?=$this->url_js("plugins/moment.min.js")?>"></script>
  <!--  Plugin for Sweet Alert -->
  <script src="<?=$this->url_js("plugins/sweetalert2.js")?>"></script>
  <!-- Forms Validations Plugin -->
  <script src="<?=$this->url_js("plugins/jquery.validate.min.js")?>"></script>
  <!-- Plugin for the Wizard, full documentation here: https://github.com/VinceG/twitter-bootstrap-wizard -->
  <script src="<?=$this->url_js("plugins/jquery.bootstrap-wizard.js")?>"></script>
  <!--	Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->
  <script src="<?=$this->url_js("plugins/bootstrap-selectpicker.js")?>"></script>
  <!--  Plugin for the DateTimePicker, full documentation here: https://eonasdan.github.io/bootstrap-datetimepicker/ -->
  <script src="<?=$this->url_js("plugins/bootstrap-datetimepicker.min.js")?>"></script>
  <script src="<?=$this->url_js("plugins/bootstrap-datetimepicker.ja.js")?>"></script>
  
  
  <!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
  <script src="<?=$this->url_js("plugins/jquery.dataTables.min.js")?>"></script>
  <!--	Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinputs  -->
  <script src="<?=$this->url_js("plugins/bootstrap-tagsinput.js")?>"></script>
  <!-- Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
  <script src="<?=$this->url_js("plugins/jasny-bootstrap.min.js")?>"></script>
  <!--  Full Calendar Plugin, full documentation here: https://github.com/fullcalendar/fullcalendar    -->
  <script src="<?=$this->url_js("plugins/fullcalendar.min.js")?>"></script>
  <!-- Vector Map plugin, full documentation here: http://jvectormap.com/documentation/ -->
  <script src="<?=$this->url_js("plugins/jquery-jvectormap.js")?>"></script>
  <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
  <script src="<?=$this->url_js("plugins/nouislider.min.js")?>"></script>
  <!-- Include a polyfill for ES6 Promises (optional) for IE11, UC Browser and Android browser support SweetAlert -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
  <!-- Library for adding dinamically elements -->
  <script src="<?=$this->url_js("plugins/arrive.min.js")?>"></script>
  <!--  Google Maps Plugin    -->
  <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
  <!-- Chartist JS -->
  <script src="<?=$this->url_js("plugins/chartist.min.js")?>"></script>
  <!--  Notifications Plugin    -->
  <script src="<?=$this->url_js("plugins/bootstrap-notify.js")?>"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="<?=$this->url_js("material-dashboard.js?v=2.1.1")?>" type="text/javascript"></script>
  <!-- Material Dashboard DEMO methods, don't include it in your project! -->
  <script src="<?=$this->url_js("demo.js")?>"></script>
  
  
<!-- CSS -->
<link  href="//cdnjs.cloudflare.com/ajax/libs/cropper/3.1.6/cropper.min.css" rel="stylesheet">
<!-- JS -->
<!--<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.1.6/jquery.min.js"></script>-->
<script src="//cdnjs.cloudflare.com/ajax/libs/cropper/3.1.6/cropper.min.js"></script>

  <script>
    $(document).ready(function() {
//      $().ready(function() {
        $sidebar = $('.sidebar');

        $sidebar_img_container = $sidebar.find('.sidebar-background');

        $full_page = $('.full-page');

        $sidebar_responsive = $('body > .navbar-collapse');

        window_width = $(window).width();

        fixed_plugin_open = $('.sidebar .sidebar-wrapper .nav li.active a p').html();

        if (window_width > 767 && fixed_plugin_open == 'Dashboard') {
          if ($('.fixed-plugin .dropdown').hasClass('show-dropdown')) {
            $('.fixed-plugin .dropdown').addClass('open');
          }

        }

        $('.fixed-plugin a').click(function(event) {
          // Alex if we click on switch, stop propagation of the event, so the dropdown will not be hide, otherwise we set the  section active
          if ($(this).hasClass('switch-trigger')) {
            if (event.stopPropagation) {
              event.stopPropagation();
            } else if (window.event) {
              window.event.cancelBubble = true;
            }
          }
        });

        $('.fixed-plugin .active-color span').click(function() {
          $full_page_background = $('.full-page-background');

          $(this).siblings().removeClass('active');
          $(this).addClass('active');

          var new_color = $(this).data('color');

          if ($sidebar.length != 0) {
            $sidebar.attr('data-color', new_color);
          }

          if ($full_page.length != 0) {
            $full_page.attr('filter-color', new_color);
          }

          if ($sidebar_responsive.length != 0) {
            $sidebar_responsive.attr('data-color', new_color);
          }
        });

        $('.fixed-plugin .background-color .badge').click(function() {
          $(this).siblings().removeClass('active');
          $(this).addClass('active');

          var new_color = $(this).data('background-color');

          if ($sidebar.length != 0) {
            $sidebar.attr('data-background-color', new_color);
          }
        });

        $('.fixed-plugin .img-holder').click(function() {
          $full_page_background = $('.full-page-background');

          $(this).parent('li').siblings().removeClass('active');
          $(this).parent('li').addClass('active');


          var new_image = $(this).find("img").attr('src');

          if ($sidebar_img_container.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
            $sidebar_img_container.fadeOut('fast', function() {
              $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
              $sidebar_img_container.fadeIn('fast');
            });
          }

          if ($full_page_background.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
            var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

            $full_page_background.fadeOut('fast', function() {
              $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
              $full_page_background.fadeIn('fast');
            });
          }

          if ($('.switch-sidebar-image input:checked').length == 0) {
            var new_image = $('.fixed-plugin li.active .img-holder').find("img").attr('src');
            var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

            $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
            $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
          }

          if ($sidebar_responsive.length != 0) {
            $sidebar_responsive.css('background-image', 'url("' + new_image + '")');
          }
        });

        $('.switch-sidebar-image input').change(function() {
          $full_page_background = $('.full-page-background');

          $input = $(this);

          if ($input.is(':checked')) {
            if ($sidebar_img_container.length != 0) {
              $sidebar_img_container.fadeIn('fast');
              $sidebar.attr('data-image', '#');
            }

            if ($full_page_background.length != 0) {
              $full_page_background.fadeIn('fast');
              $full_page.attr('data-image', '#');
            }

            background_image = true;
          } else {
            if ($sidebar_img_container.length != 0) {
              $sidebar.removeAttr('data-image');
              $sidebar_img_container.fadeOut('fast');
            }

            if ($full_page_background.length != 0) {
              $full_page.removeAttr('data-image', '#');
              $full_page_background.fadeOut('fast');
            }

            background_image = false;
          }
        });

        $('.switch-sidebar-mini input').change(function() {
          $body = $('body');

          $input = $(this);

          if (md.misc.sidebar_mini_active == true) {
            $('body').removeClass('sidebar-mini');
            md.misc.sidebar_mini_active = false;

            $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar();

          } else {

            $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar('destroy');

            setTimeout(function() {
              $('body').addClass('sidebar-mini');

              md.misc.sidebar_mini_active = true;
            }, 300);
          }

          // we simulate the window Resize so the charts will get updated in realtime.
          var simulateWindowResize = setInterval(function() {
            window.dispatchEvent(new Event('resize'));
          }, 180);

          // we stop the simulation of Window Resize after the animations are completed
          setTimeout(function() {
            clearInterval(simulateWindowResize);
          }, 1000);

        });
//      });
    });
  </script>
  <script>
    $(document).ready(function() {
      // Javascript method's body can be found in assets/js/demos.js
      md.initDashboardPageCharts();
      
      
      if ($('.datepicker').length != 0) {
        md.initFormExtendedDatetimepickers();
      }
      if ($('#fullCalendar').length != 0) {
        md.initFullCalendar();
      }


    });
    
    
$(document).on('keydown', '.input_number_only', function(e){
  let k = e.keyCode;
  let str = String.fromCharCode(k);
  if(!(str.match(/[0-9]/) || (37 <= k && k <= 40) || k === 8 || k === 46)){
    return false;
  }
});
 
$(document).on('keyup', '.input_number_only', function(e){
  this.value = this.value.replace(/[^0-9]+/i,'');
});
 
$(document).on('blur', '.input_number_only',function(){
  this.value = this.value.replace(/[^0-9]+/i,'');
});    
    
  </script>  
</body>

</html>
