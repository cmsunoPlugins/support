//
// CMSUno
// Plugin Support
//
var supportL=[];
function f_load_support(){
	let x=new FormData();
	x.set('action','load');
	x.set('unox',Unox);
	x.set('ubusy',Ubusy);
	fetch('uno/plugins/support/support.php',{method:'post',body:x})
	.then(r=>r.json())
	.then(function(r){
		if(r.list){
			supportL=r.list;
			let t=document.createElement('table'),tr,td,dv;
			for(let [k,v] of Object.entries(r.list)){
				d=f_timeConvert(v.d);
				tr=document.createElement('tr');tr.className="supportTrL";
				td=document.createElement('td');td.innerHTML=v.t.substr(0,60)+(v.t.length>60?'...':'');td.onclick=function(){f_loadT_support(this,v.i)};if(v.r==1)td.className='resolved';tr.appendChild(td); // titre
				td=document.createElement('td');td.innerHTML=v.n;tr.appendChild(td); // nb post
				td=document.createElement('td');td.innerHTML=v.u;tr.appendChild(td); // last user
				td=document.createElement('td');td.innerHTML=d;tr.appendChild(td); // date
				td=document.createElement('td');
				dv=document.createElement('div');dv.className='delPost';dv.onclick=function(){f_del_supportL(v.i)};
				dv.style.backgroundImage='url('+Udep+'includes/img/ui-icons_444444_256x240.png)';
				dv.style.backgroundPosition='-176px -96px';
				dv.style.backgroundRepeat='no-repeat';
				dv.style.display='inline-block';
				td.appendChild(dv);
				dv=document.createElement('div');dv.className='editPost';dv.onclick=function(){f_edit_supportL(v,this)};
				dv.style.backgroundImage='url('+Udep+'includes/img/ui-icons_444444_256x240.png)';
				dv.style.backgroundPosition='-64px -112px';
				dv.style.backgroundRepeat='no-repeat';
				dv.style.display='inline-block';
				td.appendChild(dv);
				tr.appendChild(td);
				t.appendChild(tr);
			}
			document.getElementById('supportL').innerHTML='';
			document.getElementById('supportL').appendChild(t);
		}
	});
	if(!Upluglist.includes('1users')&&!Upluglist.includes('2users'))document.getElementById('supportNotif').style.display='block';
}
function f_loadT_support(f,l){
	let x=new FormData();
	x.set('action','loadT');
	x.set('unox',Unox);
	x.set('ubusy',Ubusy);
	x.set('topic',l);
	fetch('uno/plugins/support/support.php',{method:'post',body:x})
	.then(r=>r.json())
	.then(function(r){
		if(r.topic){
			var t=document.createElement('table'),d,tr,td,dv,c=0;
			for(let [k,v] of Object.entries(r.topic)){
				d=f_timeConvert(v.d);
				tr=document.createElement('tr');
				td=document.createElement('td');td.innerHTML=v.c.normalize();tr.appendChild(td); // content
				td=document.createElement('td');td.innerHTML=v.u;tr.appendChild(td); // user
				td=document.createElement('td');td.innerHTML=d;tr.appendChild(td); // date
				td=document.createElement('td');
				if(c){
					dv=document.createElement('div');dv.className='delPost';dv.onclick=function(){f_del_supportT(l,v.i)};
					dv.style.backgroundImage='url('+Udep+'includes/img/ui-icons_444444_256x240.png)';
					dv.style.backgroundPosition='-176px -96px';
					dv.style.backgroundRepeat='no-repeat';
					td.appendChild(dv);
				}
				dv=document.createElement('div');dv.className='editPost';dv.onclick=function(){f_edit_supportT(l,v,this)};
				dv.style.backgroundImage='url('+Udep+'includes/img/ui-icons_444444_256x240.png)';
				dv.style.backgroundPosition='-64px -112px';
				dv.style.backgroundRepeat='no-repeat';
				td.appendChild(dv);
				dv=document.createElement('div');dv.className='movePost';dv.onclick=function(){f_supportM(l,v.i,this)};
				dv.style.backgroundImage='url('+Udep+'includes/img/ui-icons_444444_256x240.png)';
				dv.style.backgroundPosition='-32px -80px';
				dv.style.backgroundRepeat='no-repeat';
				td.appendChild(dv);
				tr.appendChild(td);
				t.appendChild(tr);
				++c;
			}
			tr=document.createElement('tr');tr.className='supportT';
			f.parentNode.parentNode.insertBefore(tr, f.parentNode.nextSibling);
			n=f.parentNode.nextElementSibling;
			td=document.createElement('td');td.colSpan='5';td.className='supportTd';
			td.appendChild(t);
			n.appendChild(td);
			f.onclick=function(){f_load_support();};
		}
	});
}
function f_del_supportL(l){
	if(confirm("Delete ?")){
		let x=new FormData();
		x.set('action','delL');
		x.set('unox',Unox);
		x.set('ubusy',Ubusy);
		x.set('del',l);
		fetch('uno/plugins/support/support.php',{method:'post',body:x})
		.then(r=>r.text())
		.then(function(r){
			f_alert(r);
			f_load_support();
		});
	}
}
function f_del_supportT(l,i){
	if(confirm("Delete ?")){
		let x=new FormData();
		x.set('action','delT');
		x.set('unox',Unox);
		x.set('ubusy',Ubusy);
		x.set('l',l);
		x.set('i',i);
		fetch('uno/plugins/support/support.php',{method:'post',body:x})
		.then(r=>r.text())
		.then(function(r){
			f_alert(r);
			f_load_support();
		});
	}
}
function f_edit_supportL(f,t){
	let td=t.parentNode.parentNode.firstChild,te,bu;
	td.innerHTML='';td.onclick='';
	te=document.createElement('input');te.type='text';te.value=f.t;te.style.width='120px';td.appendChild(te);
	bu=document.createElement('a');bu.className='bouton';bu.href='javascript:void(0)';bu.innerHTML='Save';
	bu.onclick=function(){f_save_supportL(f,this.parentNode.firstChild.value);};td.appendChild(bu);
}
function f_edit_supportT(l,f,t){
	let td=t.parentNode.parentNode.firstChild,te,bu,w=document.body.clientWidth;
	td.innerHTML='';
	te=document.createElement('textarea');te.value=f.c;te.style.width=Math.min(w/2.1,620)+'px';td.appendChild(te);
	bu=document.createElement('a');bu.className='bouton';bu.href='javascript:void(0)';bu.innerHTML='Save';
	bu.onclick=function(){f_save_supportT(l,f,this.parentNode.firstChild.value);};td.appendChild(bu);
}
function f_supportM(l,i,t){
	let td=t.parentNode.parentNode.firstChild,se,bu;
	td.innerHTML='';
	se=document.createElement('select');
	bu=document.createElement('option');bu.value=-1;bu.innerHTML='_New_';se.appendChild(bu);
	supportL.forEach(function(v,k){
		bu=document.createElement('option');bu.value=v.i;bu.innerHTML=v.t.substr(0,20)+(v.t.length>20?'...':'');se.appendChild(bu);
	});
	td.appendChild(se);
	bu=document.createElement('a');bu.className='bouton';bu.href='javascript:void(0)';bu.innerHTML='Move';
	bu.onclick=function(){f_save_supportM(l,i,this.parentNode.firstChild.value);};td.appendChild(bu);
}
function f_save_supportL(f,t){
	let x=new FormData();
	x.set('action','saveL');
	x.set('unox',Unox);
	x.set('ubusy',Ubusy);
	x.set('i',f.i);
	x.set('t',t);
	fetch('uno/plugins/support/support.php',{method:'post',body:x})
	.then(r=>r.text())
	.then(function(r){
		f_alert(r);
		f_load_support();
	});
}
function f_save_supportT(l,f,t){;
	let x=new FormData();
	x.set('action','saveT');
	x.set('unox',Unox);
	x.set('ubusy',Ubusy);
	x.set('l',l);
	x.set('i',f.i);
	x.set('c',f_encode64(t));
	fetch('uno/plugins/support/support.php',{method:'post',body:x})
	.then(r=>r.text())
	.then(function(r){
		f_alert(r);
		f_load_support();
	});
}
function f_save_supportM(l,i,t){;
	let x=new FormData();
	x.set('action','saveM');
	x.set('unox',Unox);
	x.set('ubusy',Ubusy);
	x.set('l',l);
	x.set('i',i);
	x.set('m',t);
	fetch('uno/plugins/support/support.php',{method:'post',body:x})
	.then(r=>r.text())
	.then(function(r){
		f_alert(r);
		f_load_support();
	});
}
function f_timeConvert(Timestamp){
	let a=new Date(Timestamp*1000); // ms
	let months=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
	let year=a.getFullYear();
	let month=months[a.getMonth()];
	let date=a.getDate();
	let hour=a.getHours();
	let min=a.getMinutes();
	let t=date+' '+month+' '+year+' '+hour+':'+min;
	return t;
}
f_load_support();
// BASE64 ENCODE - http://ntt.cc/2008/01/19/base64-encoder-decoder-with-javascript.html
var keyStr="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
function f_encode64(f){
	f=escape(f);
	let out='',chr1,chr2,chr3,enc1,enc2,enc3,enc4,i=0;
	do{
		chr1=f.charCodeAt(i++);
		chr2=f.charCodeAt(i++);
		chr3=f.charCodeAt(i++);
		enc1=chr1>>2;
		enc2=((chr1&3)<<4)|(chr2>>4);
		enc3=((chr2&15)<<2)|(chr3>>6);
		enc4=chr3&63;
		if(isNaN(chr2))enc3=enc4=64;
		else if(isNaN(chr3))enc4=64;
		out=out+keyStr.charAt(enc1)+keyStr.charAt(enc2)+keyStr.charAt(enc3)+keyStr.charAt(enc4);
		chr1=chr2=chr3='';
		enc1=enc2=enc3=enc4='';
	}
	while(i<f.length);
	return out;
}
