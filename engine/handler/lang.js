/* Translation Manager */

function t(val)
{

	//If default language is selected (english)
	if(typeof (lang) == 'undefined')
	{
		return val;
	}
	else
	{

	//If another language is selected
	if(lang[val])
	{
		return lang[val];
	}
	else
	{
		return val;
	}  
}
}

