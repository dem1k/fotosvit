<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title> </title>
		<link type="text/css" href="/assets/admin/themes/smoothness/jquery-ui-1.7.2.custom.css" rel="Stylesheet" />
		<link type="text/css" href="/assets/admin/css/admin.css" rel="Stylesheet" />
		<script type="text/javascript" src="/assets/js/jquery.js"></script>
		<script type="text/javascript" src="/assets/admin/js/jquery-ui-1.7.2.custom.min.js"></script>
		<script type="text/javascript" src="/assets/admin/js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" src="/assets/admin/js/admin.js"></script>
	</head>
	<body>

<form id="auth" name="login" method="post" action="/admin/auth/login">

<?php
//header('Content-Type: text/html; charset=UTF-8');
//echo show_errors();
?>

<label for="login">Логин</label>
<div class="field">
<input type="text" name="login" id="login" value="<?=set_value('login');?>" />
<?=form_error('login', '<div class="error">', '</div>')?>   
</div>
<div class="clear"></div>

<label for="password">Пароль</label>
<div class="field">
<input type="password" name="password" id="password" value=""/>     
<?=form_error('password', '<div class="error">', '</div>')?>
</div>
<div class="clear" style="margin-bottom:10px"></div>

<div style="float:left;"><input type="checkbox" value="1" name="remember_me"><span class="">Запомнить меня</span></div>
<input type="submit" style="float:right" name="submit_login" value="Вход" />     
<div class="clear"></div>

</form>


</body>
</html>
