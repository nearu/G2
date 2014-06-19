        <h2>后台管理系统</h2>
        <hr class="uk-article-divider"/>

        <div>
       		<h3>您好<?=$user['realName']?>，欢迎使用后台管理系统</h3>	

    
    	</div>
    	<div class="uk-panel-box uk-width-2-10" style="margin:10px;">
			<h3 class="uk-panel-title">基本设置</h3>
			<hr/>
			<ul>
			<li><a href="<?=base_url('index.php/admin/')?>">修改个人资料</a></li>
			</ul>
		</div>	
		        <hr class="uk-article-divider"/>
	
			<div class="uk-panel-box uk-width-3-10" style="margin:10px;height:500px">
				<h3 class="uk-panel-title">概况</h3>
				<hr/>
				<table class="uk-table">
					<thead>
						<th>内容</th>
						<th>数量</th>
					</thead>
					<tr>
						<td><a href="<?=base_url('index.php/admin/confirm_register')?>">待处理开户</a></td>
						<td>共<?=$user['registerNum']?>个</td>
					</tr>
					<tr>
						<td><a href="<?=base_url('index.php/admin/confirm_cancel')?>">待处理销户</td></a>
						<td>共<?=$user['cancelNum']?>个</td>
					</tr>
					<tr>
						<td><a href="<?=base_url('index.php/admin/confirm_lost')?>">待处理挂失</td></a>
						<td>共<?=$user['lostNum']?>个</td>
					</tr>
					
				</table>
			</div>

		</div>
		



	</div>
</div>