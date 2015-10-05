// JS QuickTags for bbPress version 1.0 Copyright (c) 2006 Stefano Aglietti http://www.40annibuttati.it/
function edButton(e,t,n,r,i,s){this.id=e;this.display=t;this.tagStart=n;this.tagEnd=r;this.access=i;this.open=s}function edLink(e,t,n){this.display=e;this.URL=t;if(!n){n=0}this.newWin=n}function edShowButton(e,t){if(e.access){var n=' accesskey = "'+e.access+'"'}else{var n=""}switch(e.id){case"ed_img":document.write('<input type="button" id="'+e.id+'" '+n+' class="ed_button" onclick="edInsertImage(edCanvas);" value="'+e.display+'" />');break;case"ed_link":document.write('<input type="button" id="'+e.id+'" '+n+' class="ed_button" onclick="edInsertLink(edCanvas, '+t+');" value="'+e.display+'" />');break;default:document.write('<input type="button" id="'+e.id+'" '+n+' class="ed_button" onclick="edInsertTag(edCanvas, '+t+');" value="'+e.display+'"  />');break}}function edAddTag(e){if(edButtons[e].tagEnd!=""){edOpenTags[edOpenTags.length]=e;document.getElementById(edButtons[e].id).value="/"+document.getElementById(edButtons[e].id).value}}function edRemoveTag(e){for(i=0;i<edOpenTags.length;i++){if(edOpenTags[i]==e){edOpenTags.splice(i,1);document.getElementById(edButtons[e].id).value=document.getElementById(edButtons[e].id).value.replace("/","")}}}function edCheckOpenTags(e){var t=0;for(i=0;i<edOpenTags.length;i++){if(edOpenTags[i]==e){t++}}if(t>0){return true}else{return false}}function edCloseAllTags(){var e=edOpenTags.length;for(o=0;o<e;o++){edInsertTag(edCanvas,edOpenTags[edOpenTags.length-1])}}function edToolbar(){document.write('<div id="ed_toolbar">');for(i=0;i<extendedStart;i++){edShowButton(edButtons[i],i)}for(i=extendedStart;i<edButtons.length;i++){edShowButton(edButtons[i],i)}document.write("</div>")}function edInsertTag(e,t){if(document.selection){e.focus();sel=document.selection.createRange();if(sel.text.length>0){sel.text=edButtons[t].tagStart+sel.text+edButtons[t].tagEnd}else{if(!edCheckOpenTags(t)||edButtons[t].tagEnd==""){sel.text=edButtons[t].tagStart;edAddTag(t)}else{sel.text=edButtons[t].tagEnd;edRemoveTag(t)}}e.focus()}else if(e.selectionStart||e.selectionStart=="0"){var n=e.selectionStart;var r=e.selectionEnd;var i=r;var s=e.scrollTop;if(n!=r){e.value=e.value.substring(0,n)+edButtons[t].tagStart+e.value.substring(n,r)+edButtons[t].tagEnd+e.value.substring(r,e.value.length);i+=edButtons[t].tagStart.length+edButtons[t].tagEnd.length}else{if(!edCheckOpenTags(t)||edButtons[t].tagEnd==""){e.value=e.value.substring(0,n)+edButtons[t].tagStart+e.value.substring(r,e.value.length);edAddTag(t);i=n+edButtons[t].tagStart.length}else{e.value=e.value.substring(0,n)+edButtons[t].tagEnd+e.value.substring(r,e.value.length);edRemoveTag(t);i=n+edButtons[t].tagEnd.length}}e.focus();e.selectionStart=i;e.selectionEnd=i;e.scrollTop=s}else{if(!edCheckOpenTags(t)||edButtons[t].tagEnd==""){e.value+=edButtons[t].tagStart;edAddTag(t)}else{e.value+=edButtons[t].tagEnd;edRemoveTag(t)}e.focus()}}function edInsertContent(e,t){if(document.selection){e.focus();sel=document.selection.createRange();sel.text=t;e.focus()}else if(e.selectionStart||e.selectionStart=="0"){var n=e.selectionStart;var r=e.selectionEnd;var i=e.scrollTop;e.value=e.value.substring(0,n)+t+e.value.substring(r,e.value.length);e.focus();e.selectionStart=n+t.length;e.selectionEnd=n+t.length;e.scrollTop=i}else{e.value+=t;e.focus()}}function edInsertLink(e,t,n){if(!n){n="http://"}if(!edCheckOpenTags(t)){var r=prompt("Enter the URL",n);if(r){edButtons[t].tagStart='<a href="'+r+'">';edInsertTag(e,t)}}else{edInsertTag(e,t)}}function countInstances(e,t){var n=e.split(t);return n.length-1}var edButtons=new Array,extendedStart=edButtons.length;edButtons.push(new edButton("ed_strong","b","<strong>","</strong>","b"));edButtons.push(new edButton("ed_em","i","<em>","</em>","i"));edButtons.push(new edButton("ed_link","link","","</a>","a"));edButtons.push(new edButton("ed_block","b-quote","<blockquote>","</blockquote>","q"));edButtons.push(new edButton("ed_pre","code","`","`","c"));edButtons.push(new edButton("ed_ol","ol","<ol>\n","</ol>\n\n","o"));edButtons.push(new edButton("ed_ul","ul","<ul>\n","</ul>\n\n","u"));edButtons.push(new edButton("ed_li","li","	<li>","</li>\n","l"));var edOpenTags=new Array