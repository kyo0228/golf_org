<!DOCTYPE html>
<html lang="ja">

<head>
  <!-- Global site tag (gtag.js) - Google Analytics 2019/5/4-->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-139535677-2"></script>
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'UA-139535677-2');
  </script>


  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta charset="utf-8">
  <meta name="robots" content="noindex, follow" />
  <title><?= $this->title ?></title>
  <meta name="keywords" content="">
  <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="keywords" content="<?= $this->keywords ?>">
  <meta name="description" content="<?= $this->description ?>">
  <meta name="robots" content="noindex, follow" />

  <link rel="icon" type="image/x-icon" href="<?= $this->url_images("favicon/" . $this->value("group_data", "group_id") . "/favicon.ico") . "?t=" . time() ?>">
  <link rel="apple-touch-icon" sizes="180x180" href="<?= $this->url_images("favicon/" . $this->value("group_data", "group_id") . "/apple-touch-icon-180x180.png") . "?t=" . time() ?>">
  <link rel="icon" type="image/png" sizes="192x192" href="<?= $this->url_images("favicon/" . $this->value("group_data", "group_id") . "/android-chrome-192x192.png") . "?t=" . time() ?>">

  <link rel='stylesheet' type='text/css' href='<?= $this->url_css("nx_flatstyle.css?v=" . $this->value("apl_ver")) ?>' />

</head>

<body>

  <div class="fb fb-center login_contents">
    <?= $this->layout() ?>
  </div>
</body>

</html>