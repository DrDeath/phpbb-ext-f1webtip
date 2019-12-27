function drivers() {
	//reset all 4 redisplay
	for (var i = 1; i <= places; i++) {
		var select4redisplay = document.getElementById('place' + i);
		if (select4redisplay) {
			for (var j = 0; j < select4redisplay.options.length; j++) {
				select4redisplay.options[j].disabled = false;
			}
		}
	}
	//get the ids from selected drivers
	var val2remove = new Array();
	for (i = 1; i <= places; i++) {
		var select4hide = document.getElementById('place' + i);
		if (select4hide) {
			for (j = 0; j < select4hide.options.length; j++) {
				if (select4hide.options[j].selected == true) {
					val2remove[i] = select4hide.options[j].value;
					break;
				}
			}
		}
	}
	//now lets go and hide
	for (i = 1; i <= places; i++) {
		for (j = 1; j <= places; j++) {
			if (j != i) {
				select4hide = document.getElementById('place' + i);
				if (select4hide) {
					for (var k = 0; k < select4hide.options.length; k++) {
						if (select4hide.options[k].value == val2remove[j] && val2remove[j] != 0) {
							select4hide.options[k].disabled = true;
						}
					}
				}
			}
		}
	}
}

function showtipps(showtipps)
{
	window.open(showtipps, "_showtipps", "width=400, height=630, resizable=yes");
}

window.onload=function() {
	drivers();
}