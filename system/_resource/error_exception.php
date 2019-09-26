<?php
defined('BASEPATH') OR exit('No direct script access allowed');			
	//こんなところでstatic使いたくなかったけどほかによい方法思いつかなかった。
	if (!GC_Static::is_view_loaded()){
?>
<!DOCTYPE html>
<html lang="jp">
<head>
<meta charset="utf-8">
<title>Exception error</title>
</head>
<body>
<?php
	}
?>	
	<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

	<h4>An uncaught Exception was encountered</h4>
	<p>Type: <?=get_class($e)?></p>
	<p>Message: <?= $e->getMessage() ?></p>
	<p>Filename: <?= $e->getFile(); ?></p>
	<p>Line Number: <?= $e->getLine(); ?></p>

		<p>Backtrace:</p>
		<table style="border: 1px #990000 solid">
			<tr>
				<th style="border: 1px #990000 solid;background-color: #990000;color: #fff;">File</th>
				<th style="border: 1px #990000 solid;background-color: #990000;color: #fff;">Line</th>
				<th style="border: 1px #990000 solid;background-color: #990000;color: #fff;">Function</th>
			</tr>
		<?php foreach ($e->getTrace() as $error): ?>
			<?php if (isset($error['file'])): ?>
			<tr>
				<th style="text-align: left;border: 1px #990000 solid;"><?php echo $error['file']; ?></th>
				<th style="text-align: left;border: 1px #990000 solid;"><?php echo $error['line']; ?></th>
				<th style="text-align: left;border: 1px #990000 solid;"><?php echo $error['function']; ?></th>
			</tr>
			<?php endif ?>

		<?php endforeach ?>
		</table>
	</div>
	
<?php
	if (!GC_Static::is_view_loaded()){
?>	
</body>
</html>
<?php
	}
?>