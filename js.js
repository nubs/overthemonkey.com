function check(td, hid)
{
	if(hid.value=='1')
	{
		td.style.backgroundColor='';
		hid.value='0';
	}
	else
	{
		td.style.backgroundColor='#CCCCF9';
		hid.value='1';
	}
}