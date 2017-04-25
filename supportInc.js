var edCanvas,bbe=document.createElement('form');bb=true;
(function(){var x=new XMLHttpRequest(),o,d,a,p,q,r,s,t,u,params='a=list&u='+Ubusy;
	bbe.id='postform';bbe.className='postform post-form';
	p=document.createElement('table');p.className='form-table threadreply';
	q=document.createElement('tr');q.style.verticalAlign='top';
	r=document.createElement('th');
	s=document.createElement('div');s.id='threadauthor';
	t=document.createElement('p');
	u=document.createElement('strong');u.id='supportUsersName';t.appendChild(u);
	u=document.createElement('br');t.appendChild(u);
	u=document.createElement('small');u.innerHTML='<span class="authortitle"><a href="">'+supTr.mem+'</a></span>';t.appendChild(u);
	u=document.createElement('br');t.appendChild(u);
	s.appendChild(t);r.appendChild(s);q.appendChild(r);
	r=document.createElement('td');
	s=document.createElement('p');s.id='titleTopic';
	t=document.createElement('label');t.innerHTML=supTr.tot;t.style.paddingRight='10px';s.appendChild(t);
	t=document.createElement('input');t.id='topic';t.name='topic';t.style.width='300px';s.appendChild(t);
	r.appendChild(s);
	s=document.createElement('div');s.className='richeditor';bbe.id='supportEd';
	t=document.createElement('div');t.className='editbar';
	u=document.createElement('button');u.title=supTr.b;u.type='button';u.onclick=function(){doClick('bold');};u.innerHTML='<b>B</b>';t.appendChild(u);
	u=document.createElement('button');u.title=supTr.i;u.type='button';u.onclick=function(){doClick('italic');};u.innerHTML='<i>I</i>';t.appendChild(u);
	u=document.createElement('button');u.title=supTr.und;u.type='button';u.onclick=function(){doClick('underline');};u.innerHTML='<u>U</u>';t.appendChild(u);
	u=document.createElement('button');u.title=supTr.lin;u.type='button';u.onclick=function(){doLink();};u.style.backgroundImage= "url('uno/plugins/support/bbcode/images/url.gif')";t.appendChild(u);
	u=document.createElement('button');u.title=supTr.ima;u.type='button';u.onclick=function(){doImage();};u.style.backgroundImage= "url('uno/plugins/support/bbcode/images/img.gif')";t.appendChild(u);
	u=document.createElement('button');u.title=supTr.lis;u.type='button';u.onclick=function(){doClick('InsertUnorderedList');};u.style.backgroundImage= "url('uno/plugins/support/bbcode/images/icon_list.gif')";t.appendChild(u);
	u=document.createElement('button');u.title=supTr.col;u.type='button';u.onclick=function(){showColorGrid2('none')};u.style.backgroundImage= "url('uno/plugins/support/bbcode/images/colors.gif')";t.appendChild(u);u=document.createElement('span');u.id='colorpicker201';u.className='colorpicker201';t.appendChild(u);
	u=document.createElement('button');u.title=supTr.quo;u.type='button';u.onclick=function(){doQuote();};u.style.backgroundImage= "url('uno/plugins/support/bbcode/images/icon_quote.png')";t.appendChild(u);
	u=document.createElement('button');u.title=supTr.cod;u.type='button';u.onclick=function(){doCode();};u.style.backgroundImage= "url('uno/plugins/support/bbcode/images/icon_code.png')";t.appendChild(u);
	u=document.createElement('button');u.title=supTr.sou;u.type='button';u.onclick=function(){javascript:SwitchEditor()};u.style.backgroundImage= "url('uno/plugins/support/bbcode/images/icon_html.gif')";t.appendChild(u);
	s.appendChild(t);
	t=document.createElement('div');t.className='container';
	u=document.createElement('textarea');u.id='textareaEd';u.style.width='100%';u.style.height='150px';t.appendChild(u);
	s.appendChild(t);
	r.appendChild(s);
	q.appendChild(r);
	p.appendChild(q);
	q=document.createElement('tr');
	r=document.createElement('th');q.appendChild(r);
	r=document.createElement('td');
	s=document.createElement('p');s.className='submit';
	t=document.createElement('input');t.id='idList';t.type='hidden';s.appendChild(t);
	t=document.createElement('input');t.className='button';t.type='button';t.value=supTr.pot;t.onclick=function(){doCheck();supportAddTopic();};s.appendChild(t);
	r.appendChild(s);
	s=document.createElement('label');s.id='mailMeBloc';t=document.createElement('input');t.id='mailMe';t.type='checkbox';t.checked=true;s.appendChild(t);s.innerHTML+='&nbsp;'+supTr.ntm;r.appendChild(s);
	s=document.createElement('label');s.id='resolveBloc';t=document.createElement('input');t.id='resolve';t.type='checkbox';t.checked=false;s.appendChild(t);s.innerHTML+='&nbsp;'+supTr.mar;r.appendChild(s);
	q.appendChild(r);
	p.appendChild(q);
	bbe.appendChild(p);
	x.open('POST','uno/plugins/support/supportCall.php',true);
	x.setRequestHeader('Content-type','application/x-www-form-urlencoded;charset=utf-8');
	x.setRequestHeader('X-Requested-With','XMLHttpRequest');
	x.onreadystatechange=function(){
		if(x.readyState==4&&x.status==200){
			p=document.getElementById('support');
			if(x.responseText){
				d=eval('('+x.responseText+')');
				o='<table class="widefat"><thead><tr><th>'+supTr.toc+'</th><th>'+supTr.pts+'</th><th>'+supTr.lap+'</th><th>'+supTr.fre+'</th></tr></thead><tbody id="spT">';
				for(v=0;v<d['list'].length;v++)if(d['list'][v]['s']==1)o+='<tr class="staff" onClick="supportTopic(this,'+d['list'][v]['i']+',1)" id="support'+d['list'][v]['i']+'"><td>'+((d['list'][v]['r']==1)?'['+supTr.res+'] ':'')+d['list'][v]['t'].replace(/\\'/g,"'")+'</td><td>'+d['list'][v]['n']+'</td><td>'+d['list'][v]['u']+'</td><td>'+supportDate(d['list'][v]['d'])+'</td></tr>';
				for(v=0;v<d['list'].length;v++)if(d['list'][v]['s']!=1)o+='<tr onClick="supportTopic(this,'+d['list'][v]['i']+',0)" id="support'+d['list'][v]['i']+'"><td>'+((d['list'][v]['r']==1)?'['+supTr.res+'] ':'')+d['list'][v]['t'].replace(/\\'/g,"'")+'</td><td>'+d['list'][v]['n']+'</td><td>'+d['list'][v]['u']+'</td><td>'+supportDate(d['list'][v]['d'])+'</td></tr>';
				o+='</tbody></table>';
				p.innerHTML=o;
			}
			var ct=0,isUsers=function(){
				if(Users){
					p.innerHTML+='<h2 class="post-form">'+supTr.ato+'</h2>';
					a=bbe.getElementsByTagName('strong');for(v=0;v<a.length;v++)if(a[v].getAttribute('id')=='supportUsersName')a[v].innerHTML=Users['n'];
					p.appendChild(bbe);
					initEditor('textareaEd', true);
					setTimeout('ShowEditor()',100);
					edCanvas=document.getElementById("textareaEd");
				}else{setTimeout(function(){++ct;if(ct<400)isUsers();},25);};
			};isUsers();
		}
	};
	x.send(params);
})();
function supportTopic(f,g,h){
	var x=new XMLHttpRequest(),m=f.parentNode.parentNode,n,o,d,c,e='<ol id="thread">',z,v,a,params='a=topic&i='+g+'&u='+Ubusy;
	x.open('POST','uno/plugins/support/supportCall.php',true);
	x.setRequestHeader('Content-type','application/x-www-form-urlencoded;charset=utf-8');
	x.setRequestHeader('X-Requested-With','XMLHttpRequest');
	x.onreadystatechange=function(){
		if(x.readyState==4&&x.status==200&&x.responseText&&!document.getElementById('thread')){
			if(f.nextElementSibling){
				m.insertAdjacentHTML('afterend','<table class="widefat"><tbody></tbody></table>');
				n=m.nextElementSibling.tBodies[0];
				while(f.nextElementSibling)n.appendChild(f.nextElementSibling);
			}
			d=eval('('+x.responseText+')');
			for(v=0;v<d['topic'].length;v++)e+='<li class="postitem"><div class="threadauthor col-3"><p><img src="'+f_usersGravatar(d['topic'][v]['e'],48)+'" class="avatar" /><strong>'+d['topic'][v]['u']+'</strong><br><small><span class="authortitle"><a href="javascript:void(0)">'+(d['topic'][v]['u']==d['staf']?supTr.stf:supTr.mem)+'</a></span><br>'+supportDate(d['topic'][v]['d'])+'<br></small></p></div><div class="threadpost col-7"><div class="post">'+d['topic'][v]['c'].replace(/\\'/g,"'")+'</div></div></li>';
			e+='</ol><div style="clear:both;"></div>';
			o=document.createElement('div');o.id='wrapTopic';o.onclick=function(){supportClose(this)};
			c=document.createElement('div');c.className='wrapTopicClose';o.appendChild(c);
			c=document.createElement('div');c.id='topic';c.onclick=function(event){if(event.stopPropagation)event.stopPropagation();else event.returnValue=false;};c.innerHTML=e;
			if(Users&&h==0){
				c.innerHTML+='<h2 class="post-form">'+supTr.rep+'</h2>';
				a=bbe.getElementsByTagName('strong');for(v=0;v<a.length;v++)if(a[v].getAttribute('id')=='supportUsersName')a[v].innerHTML=Users['n'];
				a=bbe.getElementsByTagName('input');for(v=0;v<a.length;v++)if(a[v].getAttribute('id')=='idList')a[v].value=g;
				a=bbe.getElementsByTagName('p');for(v=0;v<a.length;v++)if(a[v].getAttribute('id')=='titleTopic')a[v].style.display='none';
				a=bbe.getElementsByTagName('label');for(v=0;v<a.length;v++){
					if(a[v].getAttribute('id')=='resolveBloc'&&(d['u']!=Users['n']||d['r']==1))a[v].style.display='none';
					if(a[v].getAttribute('id')=='mailMeBloc'&&d['mail']&&d['mail'].search(','+Users['n']+',')!=-1)a[v].style.display='none';
				}
				c.appendChild(document.getElementById('supportEd'));setTimeout('ShowEditor()',100);
			}
			else if(h==0)c.innerHTML+='<h2 class="post-form">'+supTr.ytr+'</h2>';
			o.appendChild(c);
			if(f.nextElementSibling)n.parentNode.parentNode.insertBefore(o,n.parentNode);
			else m.parentNode.insertBefore(o,m.nextSibling);
			edCanvas=document.getElementById("post_content");
			if(document.getElementById("titleTopic")!==null)document.getElementById("titleTopic").style.display='none';
		}
	};
	x.send(params);
}
function supportClose(f){
	var g=f.nextElementSibling,h,i=document.getElementById('spT'),r=f.offsetTop-150;
	if(Users){
		document.getElementById('mailMeBloc').style.display='block';
		document.getElementById('resolveBloc').style.display='block';
	}
	if(g){
		h=g.firstChild;
		if(h.childNodes.length>0){
			while(h.firstChild.nextElementSibling)i.appendChild(h.firstChild);
			i.appendChild(h.firstChild);
		}
	}
	f.parentNode.removeChild(f);
	window.scrollTo(0,r);
	setTimeout('ShowEditor()',100);
	if(Users){
		p=document.getElementById('support');p.appendChild(bbe);
		document.getElementById('idList').value='';
		document.getElementById('titleTopic').style.display='block';
	}
}
function supportAddTopic(){
	var c=document.getElementById("textareaEd").value,i=document.getElementById('idList').value,t=document.getElementById('topic').value,e=(document.getElementById('mailMe').checked?1:0),r=(document.getElementById('resolve').checked?1:0);
	if(c.length>2&&(i!=''||t.length>2)){
		c=c.replace(/(\r\n|\n|\r)/gm,"[br]");
		var x=new XMLHttpRequest(),params='a=add&c='+f_encode64(c)+'&i='+i+'&m='+Users['n']+'&t='+t+'&u='+Ubusy+'&e='+e+'&r='+r;
		x.open('POST','uno/plugins/support/supportCall.php',true);
		x.setRequestHeader('Content-type','application/x-www-form-urlencoded;charset=utf-8');
		x.setRequestHeader('X-Requested-With','XMLHttpRequest');
		x.onreadystatechange=function(){
			if(x.readyState==4&&x.status==200){
				window.scrollTo(0,0);
				location.reload();
			}
		}
		x.send(params);
		document.getElementById("postformsub").style.display="none";
	}
}
function supportDate(f){if(f>Date.now()/1000)f=Date.now()/1000-60;var a=Math.floor((Date.now()/1000)-f);if(a<3600)return Math.floor(a/60)+' minutes';if(a<86400)return Math.floor(a/3600)+' hours';if(a<604800)return Math.floor(a/86400)+' days';if(a<2592000)return Math.floor(a/604800)+' weeks';if(a<31536000)return Math.floor(a/2592000)+' months';return Math.floor(a/31536000)+' years';}
// BASE64 ENCODE - http://ntt.cc/2008/01/19/base64-encoder-decoder-with-javascript.html
var keyStr="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
function f_encode64(f){
	f=escape(f);
	var out='',chr1,chr2,chr3,enc1,enc2,enc3,enc4,i=0;
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
