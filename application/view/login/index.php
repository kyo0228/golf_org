		<form name="LoginForm" action="<?=$this->url_view("index","login")?>" method="post">
			<input type="hidden" id="function" name="function" value="login" />

				<div class="login_area" >
					<div class="login_area_header" style=""><img src="<?=$this->url_images("shokyukai.png")?>" alt="" style="" ></div>
					<div class="login_area_main" >
						<p>ログイン</p>
						<input type="text" autocomplete="off" id="LoginId" name="LoginId" maxlength="10" style="ime-mode:disabled" value="" />
            <div style="color: red;">
            <?=$this->output_error()?>	
            </div>
						<!--<p><a class="button navyblue" href="http://nxcloud.localhost/site/menu">ログイン</a></p>-->
            <input class="button navyblue" type="submit" name="btn_login" value="ログイン" style="">
					</div>
				</div>
		</form>			