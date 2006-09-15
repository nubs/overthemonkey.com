<!-- INCLUDE header -->
<table id="page_body">
	<tr><td id="page_main">
		<!-- INCLUDE messages -->
		<!-- BEGIN register_form -->
		<form action="register.php" method="post">
		<table class="form">
			<tr class="row1"><td>{register_form.s_email}:</td><td><input type="text" name="email" value="{register_form.f_email}" maxlength="64"></td></tr>
			<tr class="row2"><td>{register_form.s_username}:</td><td><input type="text" name="username" value="{register_form.f_username}" maxlength="16"></td></tr>
			<tr class="row1"><td>{register_form.s_password}:</td><td><input type="password" name="password" maxlength="16"></td></tr>
			<tr class="row2"><td>{register_form.s_password2}:</td><td><input type="password" name="password2" maxlength="16"></td></tr>
			<tr class="row1"><td>Location:</td><td><input type="text" name="location" value="{register_form.f_location}" maxlength="32"></td></tr>
			<tr class="row2"><td>Profile:</td><td><textarea name="profile" value="{register_form.f_profile}"></textarea></td></tr>
			<tr class="row1 submit"><td colspan="2"><input type="submit" name="submit" value="{register_form.s_submit}"></td></tr>
		</table>
		</form>
		<!-- END register_form -->
	</td>
	<td id="page_sidebar">
		<table class="page_conf">
			<tr><th>Greetings</th></tr>
			<tr><td>Hello Hello Hello Hello Hello Hello</td></tr>
		</table>
		<table class="page_conf">
			<tr><th>Queries</th></tr>
			<tr><td>What are you doing? What are you doing?</td></tr>
		</table>
	</td></tr>
</table>
<!-- INCLUDE footer -->
