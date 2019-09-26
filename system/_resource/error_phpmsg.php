<?php
defined('BASEPATH') OR exit('No direct script access allowed');			
	//こんなところでstatic使いたくなかったけどほかによい方法思いつかなかった。
	if (!GC_Static::is_view_loaded()){
?>
<!DOCTYPE html>
<html lang="jp">
<head>
<meta charset="utf-8">
<title>PHP error</title>
</head>
<body>
<?php
	}
?>	

<div style="border:1px solid <?=$clr?>;padding-left:20px;margin:0 0 10px 0;">

<h4 style="color: <?=$clr?>;">A PHP Error was encountered</h4>
<p>Type: <?=$e["kind"]?></p>
<p>Message: <?= $e["message"] ?></p>
<p>Filename: <?= $e["file"] ?></p>
<p>Line Number: <?= $e["line"] ?></p>
</div>
<?php
	if (!GC_Static::is_view_loaded()){
?>	
</body>
</html>
<?php
	}
?>