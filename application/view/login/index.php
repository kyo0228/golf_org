		<form name="LoginForm" action="<?= $this->url_view("index", "login") ?>" method="post">
			<input type="hidden" id="function" name="function" value="login" />

			<div class="login_area">
				<div class="login_area_header" style=""><img src="<?= $this->url_images("shokyukai.png") ?>" alt="" style=""></div>
				<div class="login_area_main">
					<p>ログイン</p>
					<input type="text" autocomplete="off" id="LoginId" name="LoginId" maxlength="10" style="ime-mode:disabled" value="" />
					<div style="color: red;">
						<?= $this->output_error() ?>
					</div>
					<!--<p><a class="button navyblue" href="http://nxcloud.localhost/site/menu">ログイン</a></p>-->
					<input class="button navyblue" type="submit" name="btn_login" value="ログイン" style="">
				</div>
			</div>

			<?php
			if ($this->value("demo_user")) {
				?>
				<div>
					<p>ゴルフコンペスコア管理webサービス</p>
					<p>
						ゴルフ場で発行される紙のコンペ結果を保管するwebサービスです。用途に特化しているため簡単な操作とシンプルな機能をご用意しています。
						個人やグループ毎のスコア登録はGDOやゴルフネットワークでリリースされているアプリがとても便利なのでそちらを使ってください。
					</p>

					<p>主な機能</p>
					<ul>
						<li>参加者の登録、管理</li>
						<li>コンペ日程、コースの設定</li>
						<li>ハンデキャップの事前設定</li>
						<li>ラウンド当日のメモ、写真の保管</li>
						<li>ラウンド終了後のスコア登録</li>
						<li>過去のコンペ結果表示、集計</li>
					</ul>

				</div>
			<?php
			}
			?>

		</form>