<form action="viewusers.php?admin" method="post">
<table class="listing">
	<tr><th>Ban</th><th>Name</th><th>Location</th><th>Email</th></tr>
	<!-- BEGIN users -->
	<tr class="{users.v_rowstyle}"><td><input type="checkbox" name="ban{users.n_id}" value="ban"{users.banned}></td><td><a href="viewuser.php?id={users.n_id}">{users.s_name}</a></td><td>{users.s_location}</td><td>{users.s_email}</td></tr>
	<!-- END users -->
</table>
<input type="submit" name="ban_submit" value="Ban">
</form>

