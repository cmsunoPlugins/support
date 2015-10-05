//
// CMSUno
// Plugin Support
//
function f_load_support(){
	jQuery(document).ready(function(){
		jQuery.ajax({type:'POST',url:'uno/plugins/support/support.php',data:{'action':'load','unox':Unox},dataType:'json',async:true,success:function(r){
			if(r.list){
				t=document.createElement('table');
				jQuery.each(r.list,function(k,v){
					d=f_timeConvert(v.d);
					tr=document.createElement('tr');
					td=document.createElement('td');td.innerHTML=v.t.substr(0,60)+(v.t.length>60?'...':'');td.onclick=function(){f_loadT_support(this,v.i)};tr.appendChild(td); // titre
					td=document.createElement('td');td.innerHTML=v.n;tr.appendChild(td); // nb post
					td=document.createElement('td');td.innerHTML=v.u;tr.appendChild(td); // last user
					td=document.createElement('td');td.innerHTML=d;tr.appendChild(td); // date
					td=document.createElement('td');td.onclick=function(){f_del_supportL(v.i)};td.innerHTML='X';tr.appendChild(td);
					t.appendChild(tr);
				});
				jQuery('#supportL').empty();
				document.getElementById('supportL').appendChild(t);
			}
		}});
	});
}
function f_loadT_support(f,l){
	jQuery(document).ready(function(){
		jQuery.ajax({type:'POST',url:'uno/plugins/support/support.php',data:{'action':'loadT','unox':Unox,'topic':l},dataType:'json',async:true,success:function(r){
			if(r.topic){
				t=document.createElement('table');
				jQuery.each(r.topic,function(k,v){
					d=f_timeConvert(v.d);
					tr=document.createElement('tr');
					td=document.createElement('td');td.innerHTML=v.c;tr.appendChild(td); // content
					td=document.createElement('td');td.innerHTML=v.u;tr.appendChild(td); // user
					td=document.createElement('td');td.innerHTML=d;tr.appendChild(td); // date
					td=document.createElement('td');td.onclick=function(){f_del_supportT(l,v.i)};td.innerHTML='X';tr.appendChild(td);
					t.appendChild(tr);
				});
				tr=document.createElement('tr');tr.id='supportT';
				f.parentNode.parentNode.insertBefore(tr, f.parentNode.nextSibling);
				n=f.parentNode.nextElementSibling;
				td=document.createElement('td');td.colSpan='5';td.className='supportTd';
				td.appendChild(t);
				n.appendChild(td);
				f.onclick=function(){f_load_support();};
			}
		}});
	});
}
function f_del_supportL(l){
	jQuery(document).ready(function(){
		jQuery.post('uno/plugins/support/support.php',{'action':'delL','unox':Unox,'del':l},function(r){f_alert(r);f_load_support();});
	});
}
function f_del_supportT(l,i){
	jQuery(document).ready(function(){
		jQuery.post('uno/plugins/support/support.php',{'action':'delT','unox':Unox,'l':l,'i':i},function(r){f_alert(r);f_load_support();});
	});
}
function f_timeConvert(Timestamp){
	var a=new Date(Timestamp*1000); // ms
	var months=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
	var year=a.getFullYear();
	var month=months[a.getMonth()];
	var date=a.getDate();
	var hour=a.getHours();
	var min=a.getMinutes();
	var t=date+' '+month+' '+year+' '+hour+':'+min;
	return t;
}
f_load_support();