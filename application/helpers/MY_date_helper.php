<?php 
function format_common_date($date)
{
	if(empty($date) || $date == '0000-00-00' || $date == '0000-00-00 00:00:00')
	{
		return NULL;
	}
	else
	{
		return date(config_item('date_format'), strtotime($date));
	}
}
function format_common_date_time($date)
{
	if(empty($date) || $date == '0000-00-00 00:00:00')
	{
		return NULL;
	}
	else
	{
		return date(config_item('date_time_format'), strtotime($date));
	}
}