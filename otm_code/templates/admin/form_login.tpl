<!-- BEGIN login_form -->
<form action="login.php" method="post">
<table class="form">
	<tr class="row1"><td>{login_form.s_username}:</td><td><input type="text" name="username" value="{login_form.f_username}" maxlength="16"></td></tr>
	<tr class="row2"><td>{login_form.s_password}:</td><td><input type="password" name="password" maxlength="16"></td></tr>
	<tr class="row1 submit"><td colspan="2"><input type="submit" name="submit" value="{login_form.s_submit}"></td></tr>
</table>
</form>
<!-- END login_form -->
