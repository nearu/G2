<?php
$this->load->helper("url");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><head>

<link href="<?=base_url('static/index.css')?>" rel="stylesheet" type="text/css" />
<link href="<?=base_url('css/uikit.gradient.min.css')?>" rel="stylesheet" type="text/css" />
<link href="<?=base_url('css/cms.css')?>" rel="stylesheet" type="text/css" />
<script src="<?=base_url('static/jquery-1.8.2.min.js')?>"></script>
<script src="<?=base_url('static/richard.js')?>"></script>
<script src="<?=base_url('static/editor/kindeditor-min.js')?>"></script>
<script src="<?=base_url('static/editor/lang/zh_CN.js')?>"></script>
<script src="<?=base_url('js/uikit.min.js')?>"> </script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>后台管理系统</title>
</head>

<body>

<div class="uk-panel-box" style="
margin-right: auto;
margin-left: auto;
margin-top:100px;
height:200px;
width:300px;
box-shadow:rgba(200,200,200,0.7) 0 4px 10px -1px;">
        <form id="login_form" method="post" action="" class="uk-form" style="padding:20px;margin-left:10px"> 
            <h1 class="uk-panel-title">后台管理系统</h1>
            <b class="login_tag">用户名</b>
            <input class="login_input uk-form-width-medium" type="text" name="login_username" />
            <br /><br />
            <b class="login_tag">密　码</b>
            <input class="login_input uk-form-width-medium" type="password" name="login_password" />
            <br /><br />
            <input id="login_submit" type="submit" value="登录"  class="uk-button"/>
        </form>
</div>        
</body>
