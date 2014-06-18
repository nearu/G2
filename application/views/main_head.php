<?php
$this->load->helper("url");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
<link href="<?=base_url('css/uikit.gradient.min.css')?>" rel="stylesheet" type="text/css" />
<link href="<?=base_url('css/cms.css')?>" rel="stylesheet" type="text/css" />
<script src="<?=base_url('js/jquery.min.js')?>"></script>
<script src="<?=base_url('js/uikit.min.js')?>"> </script>


<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>后台管理系统</title>
</head>
<p id = "baseurl" style="display:none"><?=base_url('/index.php/admin')?></p>
<div class="uk-grid data-uk-grid-margin">
    <!--begin of adminnavbar -->
    <div class="adminnavbar uk-width-10-10">
        <nav class="uk-navbar">
            <ul class="uk-navbar-nav">            
                <li>
                    <a href="<?=base_url('index.php/admin/main_page')?>"><i class="uk-icon-home uk-icon-large"></i></a>                
                </li>
                
            </ul>
            <div class="uk-navbar-flip">
                <ul class="uk-navbar-nav">
                    <li><a href="<?=base_url('/index.php/admin/logout')?>"><i class="uk-icon-signout"></i>&nbsp;登出</a></li>
                </ul>
            </div>
        </nav>
    </div>
    <div class="adminmenuwrap uk-width-2-10" >
        <ul class="uk-nav uk-nav-side uk-nav-parent-icon " data-uk-nav>
            <li id="register" class="uk-parent">
                <a href="<?=base_url('index.php/admin/confirm_register')?>"><i class="uk-icon-th-list"></i>&nbsp;       确认开户</a>
            </li>
            <li id="lost" class="uk-parent">
                <a href="<?=base_url('index.php/admin/confirm_lost')?>"><i class="uk-icon-file-text-alt"></i>&nbsp;&nbsp;&nbsp;处理挂失</a>
            </li>
            


            <li id="cancel"     class="uk-parent">
                <a href="<?=base_url('index.php/admin/confirm_cancel')?>"><i class="uk-icon-external-link-sign"></i>&nbsp;&nbsp;处理销户</a>
            </li>

            <li id="log"  class="uk-parent">
                <a href="<?=base_url('index.php/admin/log')?>"><i class="uk-icon-user-md"></i>&nbsp; 交易记录</a>
            </li>
            <!--
            <li id="set"     class="uk-parent">
                <a href="#"><i class="uk-icon-user"></i>&nbsp;&nbsp;修改设置</a>
            </li>
        -->
        </ul>
    </div>
    <div class="cmscontent uk-width-8-10" id="content" style="min-height:620px;">
<?php 
echo "<script> document.getElementById('$active').className = 'uk-active';</script>"; 
?>