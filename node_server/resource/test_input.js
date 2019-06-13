//do input check

function test_ans(a1, a2, a3){
	var a_total=0;
	var a_array=[];
	var is_repeat = false;

	if(a1 != ''){
		a_total++;
		a_array.push(a1);
	}
	if(a2 != ''){
		a_total++;
		a_array.forEach(function(item){
			if(item == a2)	is_repeat = true;
		});
		a_array.push(a2);		
	}
	if(a3 != ''){
		a_total++;
		a_array.forEach(function(item){
			if(item == a3)	is_repeat = true;
		});
		a_array.push(a3);
	}
	
	var ans={};
	ans['is_repeat'] = is_repeat;
	ans['a_total'] = a_total;
	ans['a_array'] = a_array;
	
	return ans;
}
