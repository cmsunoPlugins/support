var fed=document.createElement('form'),edCanvas;
(function(){var x=new XMLHttpRequest(),o,d,a,p,q,r,s,t,u,params='a=list&u='+Ubusy;
	fed.id='postform';fed.className='postform post-form';
	p=document.createElement('table');p.className='form-table threadreply';
	q=document.createElement('tr');q.style.verticalAlign='top';
	r=document.createElement('th');
	s=document.createElement('div');s.id='threadauthor';
	t=document.createElement('p');
	u=document.createElement('strong');u.id='supportUsersName';t.appendChild(u);
	u=document.createElement('br');t.appendChild(u);
	u=document.createElement('small');u.innerHTML='<span class="authortitle"><a href="">Member</a></span>';t.appendChild(u);
	u=document.createElement('br');t.appendChild(u);
	s.appendChild(t);r.appendChild(s);q.appendChild(r);
	r=document.createElement('td');
	s=document.createElement('p');s.id='titleTopic';
	t=document.createElement('label');t.innerHTML='Topic title';t.style.paddingRight='10px';s.appendChild(t);
	t=document.createElement('input');t.id='topic';t.name='topic';t.style.width='300px';s.appendChild(t);
	r.appendChild(s);
	s=document.createElement('div');s.id='ed_toolbar';
	t=document.createElement('input');t.id='ed_strong';t.className='ed_button';t.onclick=function(){edInsertTag(edCanvas,0)};t.type='button';t.accessKey="b";s.appendChild(t);
	t=document.createElement('input');t.id='ed_em';t.className='ed_button';t.onclick=function(){edInsertTag(edCanvas,1)};t.type='button';t.accessKey="i";s.appendChild(t);
	t=document.createElement('input');t.id='ed_link';t.className='ed_button';t.onclick=function(){edInsertLink(edCanvas,2)};t.type='button';t.accessKey="a";s.appendChild(t);
	t=document.createElement('input');t.id='ed_bloc';t.className='ed_button';t.onclick=function(){edInsertTag(edCanvas,3)};t.type='button';t.accessKey="q";s.appendChild(t);
	t=document.createElement('input');t.id='ed_pre';t.className='ed_button';t.onclick=function(){edInsertTag(edCanvas,4)};t.type='button';t.accessKey="c";s.appendChild(t);
	t=document.createElement('input');t.id='ed_ol';t.className='ed_button';t.onclick=function(){edInsertTag(edCanvas,5)};t.type='button';t.accessKey="o";s.appendChild(t);
	t=document.createElement('input');t.id='ed_ul';t.className='ed_button';t.onclick=function(){edInsertTag(edCanvas,6)};t.type='button';t.accessKey="u";s.appendChild(t);
	t=document.createElement('input');t.id='ed_li';t.className='ed_button';t.onclick=function(){edInsertTag(edCanvas,7)};t.type='button';t.accessKey="l";s.appendChild(t);
	t=document.createElement('input');t.id='idList';t.type='hidden';s.appendChild(t);
	r.appendChild(s);
	s=document.createElement('textarea');s.name='post_content';s.id='post_content';r.appendChild(s);
	s=document.createElement('p');s.innerHTML='Allowed markup: <code>a blockquote code em strong ul ol li</code>.<br>Put code in between <code>`backticks`</code>.';r.appendChild(s);
	q.appendChild(r);
	p.appendChild(q);
	q=document.createElement('tr');
	r=document.createElement('th');q.appendChild(r);
	r=document.createElement('td');
	s=document.createElement('p');s.className='submit';
	t=document.createElement('input');t.id='postformsub';t.className='button';t.name='Submit';t.type='button';t.value='Post';t.onclick=function(){supportAddTopic()};s.appendChild(t);
	r.appendChild(s);
	s=document.createElement('label');s.id='mailMeBloc';t=document.createElement('input');t.id='mailMe';t.type='checkbox';s.appendChild(t);s.innerHTML+='&nbsp;Notify me of follow-up posts via email';r.appendChild(s);
	s=document.createElement('label');s.id='resolveBloc';s.style.display='none';t=document.createElement('input');t.id='resolve';t.type='checkbox';s.appendChild(t);s.innerHTML+='&nbsp;Mark this topic as resolved';r.appendChild(s);
	q.appendChild(r);
	p.appendChild(q);
	fed.appendChild(p);
	x.open('POST','uno/plugins/support/supportCall.php',true);
	x.setRequestHeader('Content-type','application/x-www-form-urlencoded;charset=utf-8');
	x.setRequestHeader('Content-length',params.length);
	x.setRequestHeader('X-Requested-With','XMLHttpRequest');
	x.setRequestHeader('Connection','close');
	x.onreadystatechange=function(){
		if(x.readyState==4&&x.status==200){
			p=document.getElementById('support');
			if(x.responseText){
				d=eval('('+x.responseText+')');
				o='<table class="widefat"><thead><tr><th>Topic</th><th>Posts</th><th>Last Poster</th><th>Freshness</th></tr></thead><tbody id="spT">';
				for(v=0;v<d['list'].length;v++)o+='<tr onClick="supportTopic(this,'+d['list'][v]['i']+')" id="support'+d['list'][v]['i']+'"><td>'+((d['list'][v]['r']=='')?'[resolved] ':'')+d['list'][v]['t'].replace(/\\'/g,"'")+'</td><td>'+d['list'][v]['n']+'</td><td>'+d['list'][v]['u']+'</td><td>'+supportDate(d['list'][v]['d'])+'</td></tr>';
				o+='</tbody></table>';
				p.innerHTML=o;
			}
			var ct=0,isUsers=function(){
				if(Users){
					p.innerHTML+='<h2 class="post-form">Add Topic</h2>';
					a=fed.getElementsByTagName('strong');for(v=0;v<a.length;v++)if(a[v].getAttribute('id')=='supportUsersName')a[v].innerHTML=Users['n'];
					p.appendChild(fed);edValue();
					edCanvas=document.getElementById("post_content");
				}else{setTimeout(function(){++ct;if(ct<400)isUsers();},25);};
			};isUsers();
		}
	};
	x.send(params);
})();
function supportTopic(f,g){
	var x=new XMLHttpRequest(),m=f.parentNode.parentNode,n,o,d,c,e='<ol id="thread">',z,v,a,params='a=topic&i='+g+'&u='+Ubusy;
	x.open('POST','uno/plugins/support/supportCall.php',true);
	x.setRequestHeader('Content-type','application/x-www-form-urlencoded;charset=utf-8');
	x.setRequestHeader('Content-length',params.length);
	x.setRequestHeader('X-Requested-With','XMLHttpRequest');
	x.setRequestHeader('Connection','close');
	x.onreadystatechange=function(){
		if(x.readyState==4&&x.status==200&&x.responseText&&!document.getElementById('thread')){
			if(f.nextElementSibling){
				m.insertAdjacentHTML('afterend','<table class="widefat"><tbody></tbody></table>');
				n=m.nextElementSibling.tBodies[0];
				while(f.nextElementSibling)n.appendChild(f.nextElementSibling);
			}
			d=eval('('+x.responseText+')');
			for(v=0;v<d['topic'].length;v++)e+='<li class="postitem"><div class="threadauthor col-3"><p><img src="'+f_usersGravatar(d['topic'][v]['e'],48)+'" class="avatar" /><strong>'+d['topic'][v]['u']+'</strong><br><small><span class="authortitle"><a href="javascript:void(0)">'+(d['topic'][v]['u']==d['staf']?'Staff':'Member')+'</a></span><br>Posted '+supportDate(d['topic'][v]['d'])+'<br></small></p></div><div class="threadpost col-7"><div class="post">'+d['topic'][v]['c'].replace(/\\'/g,"'")+'</div></div></li>';
			e+='</ol><div style="clear:both;"></div>';
			o=document.createElement('div');o.id='wrapTopic';o.onclick=function(){supportClose(this)};
			c=document.createElement('div');c.className='wrapTopicClose';o.appendChild(c);
			c=document.createElement('div');c.id='topic';c.onclick=function(event){if(event.stopPropagation)event.stopPropagation();else event.returnValue=false;};c.innerHTML=e;
			if(Users){
				c.innerHTML+='<h2 class="post-form">Reply</h2>';
				a=fed.getElementsByTagName('strong');for(v=0;v<a.length;v++)if(a[v].getAttribute('id')=='supportUsersName')a[v].innerHTML=Users['n'];
				a=fed.getElementsByTagName('input');for(v=0;v<a.length;v++)if(a[v].getAttribute('id')=='idList')a[v].value=g;
				a=fed.getElementsByTagName('p');for(v=0;v<a.length;v++)if(a[v].getAttribute('id')=='titleTopic')a[v].style.display='none';
				a=fed.getElementsByTagName('label');for(v=0;v<a.length;v++){
					if(a[v].getAttribute('id')=='resolveBloc'&&d['r']==Users['n'])a[v].style.display='block';
					if(a[v].getAttribute('id')=='mailMeBloc'&&d['mail']&&d['mail'].search(','+Users['n']+',')!=-1)a[v].style.display='none';
				}
				c.appendChild(fed);//edValue();
			}
			else c.innerHTML+='<h2 class="post-form">You must login to reply</h2>';
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
	if(g){h=g.firstChild;if(h.childNodes.length>0){while(h.firstChild.nextElementSibling)i.appendChild(h.firstChild);i.appendChild(h.firstChild);}
		}
	f.parentNode.removeChild(f);
	window.scrollTo(0,r);
	if(Users){p=document.getElementById('support');p.appendChild(fed);document.getElementById('idList').value='';document.getElementById('titleTopic').style.display='block';}
}
function supportAddTopic(){
	var c=document.getElementById("post_content").value,i=document.getElementById('idList').value,t=document.getElementById('topic').value,e=document.getElementById('mailMe').checked,r=document.getElementById('resolve').checked;
	if(c.length>2&&(i!=''||t.length>2)){
		c=c.replace(/(\r\n|\n|\r)/gm,"<br>");
		var x=new XMLHttpRequest(),params='a=add&c='+c+'&i='+i+'&m='+Users['n']+'&t='+t+'&u='+Ubusy+'&e='+e+'&r='+r;
		x.open('POST','uno/plugins/support/supportCall.php',true);
		x.setRequestHeader('Content-type','application/x-www-form-urlencoded;charset=utf-8');
		x.setRequestHeader('Content-length',params.length);
		x.setRequestHeader('X-Requested-With','XMLHttpRequest');
		x.setRequestHeader('Connection','close');
		x.onreadystatechange=function(){if(x.readyState==4&&x.status==200){window.scrollTo(0,0);location.reload();}}
		x.send(params);
		document.getElementById("postformsub").style.display="none";
	}
}
function edValue(){
	document.getElementById('ed_strong').value='b';
	document.getElementById('ed_em').value='i';
	document.getElementById('ed_link').value='link';
	document.getElementById('ed_bloc').value='b-quote';
	document.getElementById('ed_pre').value='code';
	document.getElementById('ed_ol').value='ol';
	document.getElementById('ed_ul').value='ul';
	document.getElementById('ed_li').value='li';
	document.getElementById('idList').value='';
}
function supportDate(f){if(f>Date.now()/1000)f=Date.now()/1000-60;var a=Math.floor((Date.now()/1000)-f);if(a<3600)return Math.floor(a/60)+' minutes';if(a<86400)return Math.floor(a/3600)+' hours';if(a<604800)return Math.floor(a/86400)+' days';if(a<2592000)return Math.floor(a/604800)+' weeks';if(a<31536000)return Math.floor(a/2592000)+' months';return Math.floor(a/31536000)+' years';}
