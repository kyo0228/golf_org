<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="jp">
<head>
<meta charset="utf-8">
<title><?=$title?></title>
</head>
<body>
	<div style="border:1px solid #cccccc;padding-left:20px;margin:0 0 10px 0;">
		<h4><?=$title?>はメンテナンスを実施しています</h4>
		<div>
			<p>ただいまwebサイトのメンテナンスを実施しています。</p>
			<p>恐れ入りますが時間を空けて再度ご利用ください。</p>
<?php
		if ($mainte_message){
?>
			<p style="margin-top: 40px"><?=$mainte_message?></p>
<?php			
		}
?>						
		</div>
	</div>
</body>
</html>