var eventdate = new Date(countdown_stop);

function toSt(n)
{
	s=''
	if(n<10)
	{
		s+='0'
	}
	return s+n.toString();
}

function countdown()
{
	d=new Date();
	count=Math.floor((eventdate.getTime()-d.getTime())/1000);
	if(count<=0)
	{
		var time_event = document.getElementById('time_event');
		var event_time = document.getElementById('event_time');
		time_event.style.display = 'none';
		event_time.style.display = '';
		return;
	}
	secs_count = toSt(count%60);
	count=Math.floor(count/60);
	mins_count = toSt(count%60);
	count=Math.floor(count/60);
	hours_count = toSt(count%24);
	count=Math.floor(count/24);
	days_count = count;
	document.getElementById('countdown').days.value = days_count;
	document.getElementById('countdown').hours.value = hours_count;
	document.getElementById('countdown').mins.value = mins_count;
	document.getElementById('countdown').secs.value = secs_count;
	window.setTimeout('countdown()',500);
	}

window.onload=function() {
	countdown();
}