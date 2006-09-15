function expand(id)
{
	style = document.getElementById(id).style;
	style.display = ((style.display == '') ? 'none' : '');
}
