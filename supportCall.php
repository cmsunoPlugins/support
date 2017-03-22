<?php
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])!='xmlhttprequest') {sleep(2);exit;} // ajax request
?>
<?php
include('../../config.php');
if(isset($_POST['a']))
	{
	switch(strip_tags($_POST['a']))
		{
		// ********************************************************************************************
		case 'list':
		if(file_exists('../../data/'.strip_tags($_POST['u']).'/support.json'))
			{
			echo file_get_contents('../../data/'.strip_tags($_POST['u']).'/support.json');
			}
		else echo false;
		break;
		// ********************************************************************************************
		case 'topic':
		if(file_exists('../../data/'.strip_tags($_POST['u']).'/support/support'.strip_tags($_POST['i']).'.json') && file_exists('../../data/_sdata-'.$sdata.'/users.json'))
			{
			$q = file_get_contents('../../data/'.strip_tags($_POST['u']).'/support/support'.strip_tags($_POST['i']).'.json'); $a = json_decode($q,true);
			$q = file_get_contents('../../data/_sdata-'.$sdata.'/users.json'); $b = json_decode($q,true); // add email for gravatar
			$q = file_get_contents('../../data/'.strip_tags($_POST['u']).'/support.json'); $c = json_decode($q,true); // check resolved
			foreach($c['list'] as $r) { if($r['i']==strip_tags($_POST['i'])) $a['r'] = $r['r']; break;}
			foreach($a['topic'] as $k=>$v)
				{
				if(isset($b['user'][strtolower($v['u'])])) $a['topic'][$k]['e'] = md5($b['user'][strtolower($v['u'])]['e']); // Gravatar works with MD5
				else $a['topic'][$k]['e']='';
				}
			$a['topic'] = array_reverse($a['topic']);
			$a['staf'] = (isset($c['staf'])?$c['staf']:'');
			$out = json_encode($a);
			echo $out;
			}
		else echo false;
		break;
		// ********************************************************************************************
		case 'add':
		if(file_exists('../../data/_sdata-'.$sdata.'/users.json')) // Default language from the USERS plugin
			{
			$q = file_get_contents('../../data/_sdata-'.$sdata.'/users.json');
			$a = json_decode($q,true);
			if(!empty($a['g'])) $lang = $a['g'];
			}
		include('lang/lang.php');
		include '../../template/mailTemplate.php';
		$t = time();
		//
		$q = file_get_contents('../../data/'.strip_tags($_POST['u']).'/support.json');
		$a = json_decode($q,true);
		$cont = str_replace('> yy <', '><', stripslashes($_POST['c'])); // Avoid Ajax 403
		$cont = str_replace('zz <br> zz', '<br>', $cont);
		$cont = filtreTag($cont);
		if(strip_tags($_POST['i'])!='' && file_exists('../../data/'.strip_tags($_POST['u']).'/support/support'.strip_tags($_POST['i']).'.json')) // add in a topic
			{
			// TOPIC
			$q = file_get_contents('../../data/'.strip_tags($_POST['u']).'/support/support'.strip_tags($_POST['i']).'.json');
			$b = json_decode($q,true);
			$n = 1; $i = 0;
			foreach($b['topic'] as $r)
				{
				++$n;
				if(intval($r['i'])>$i) $i = intval($r['i']);
				}
			++$i;
			$b['topic'][] = array('i'=>$i, 'c'=>$cont, 'u'=>strip_tags($_POST['m']), 'd'=>$t);
			$mel = (isset($b['mail'])?$b['mail']:0);
			if(!isset($b['mail'])) $b['mail'] = ',';
			else if(strip_tags($_POST['e']) && strpos($b['mail'], ','.strip_tags($_POST['m']).',')===false) $b['mail'] .= strip_tags($_POST['m']).',';
			usort($b['topic'],'sortDate');
			$out = json_encode($b);
			// LIST
			foreach($a['list'] as $k=>$v)
				{
				if($v['i']==strip_tags($_POST['i']))
					{
					$a['list'][$k]['n'] = $n;
					$a['list'][$k]['u'] = strip_tags($_POST['m']);
					$a['list'][$k]['d'] = $t;
					if(strip_tags($_POST['r'])) $a['list'][$k]['r'] = 'r';
					$tit = $a['list'][$k]['t'];
					if(!isset($a['list'][$k]['s'])) $a['list'][$k]['s'] = 0; // 1:staff
					break;
					}
				}
			usort($a['list'],'sortDate');
			$out1 = json_encode($a);
			//
			if(file_put_contents('../../data/'.strip_tags($_POST['u']).'/support/support'.strip_tags($_POST['i']).'.json', $out) && file_put_contents('../../data/'.strip_tags($_POST['u']).'/support.json', $out1))
				{
				echo "OK";
				mailAdmin(T_("Response").' - '.$tit, $cont, strip_tags($_POST['u']), $bottom, $top, $sdata);
				if($mel) mailUsers($mel, T_("Response").' - '.$tit, $cont, strip_tags($_POST['u']), $bottom, $top, $sdata);
				exit;
				}
			}
		else // add a new topic at the list
			{
			if(!isset($a['staf']))
				{
				$a['staf'] = '';
				$q = file_get_contents('../../data/_sdata-'.$sdata.'/users.json'); $b = json_decode($q,true);
				$q = file_get_contents('../../data/_sdata-'.$sdata.'/ssite.json'); $c = json_decode($q,true);
				foreach($b['user'] as $r)
					{
					if($r['e']==$c['mel']) $a['staf'] = $r['n'];
					}
				}
			$i = 0;
			if(isset($a['list']) && is_array($a['list'])) foreach($a['list'] as $r)
				{
				if(intval($r['i'])>$i) $i = intval($r['i']);
				}
			++$i; 
			$a['list'][] = array('i'=>$i, 'n'=>1, 't'=>strip_tags($_POST['t']), 'u'=>strip_tags($_POST['m']), 'd'=>$t, 'r'=>strip_tags($_POST['m']), 's'=>($i==$a['staf']?1:0));
			usort($a['list'],'sortDate');
			$out1 = json_encode($a);
			//
			$a = array();
			$a['topic'][] = array('i'=>0, 'c'=>$cont, 'u'=>strip_tags($_POST['m']), 'd'=>$t);
			$a['mail'] = ',' . ($_POST['e']?$_POST['m'].',':'');
			$out = json_encode($a);
			if(file_put_contents('../../data/'.strip_tags($_POST['u']).'/support/support'.$i.'.json', $out) && file_put_contents('../../data/'.strip_tags($_POST['u']).'/support.json', $out1))
				{
				echo "OK";
				mailAdmin(T_("New Topic").' : '.strip_tags($_POST['t']), $cont, strip_tags($_POST['u']), $bottom, $top, $sdata);
				exit;
				}
			}
		break;
		// ********************************************************************************************
		}
	}
function sortDate($i,$j){return strcmp($j['d'], $i['d']);}
function mailAdmin($tit, $body, $Ubusy, $bottom, $top, $sdata)
	{
	$bottom = str_replace('[[unsubscribe]]','&nbsp;',$bottom);
	$q = file_get_contents('../../data/'.$Ubusy.'/site.json'); $a = json_decode($q,true);
	$q = file_get_contents('../../data/_sdata-'.$sdata.'/ssite.json'); $b = json_decode($q,true);
	$body = T_("New Topic on Support").": <b>".$tit."</b><br />\r\n".$body."\r\n";
	$msgT = strip_tags($body);
	$msgH = $top . $body . $bottom;
	$subject = $a['tit'].' - '. $tit;
	$fm = preg_replace("/[^a-zA-Z ]+/", "", $a['tit']);
	if(file_exists(dirname(__FILE__).'/../newsletter/PHPMailer/PHPMailerAutoload.php'))
		{
		// PHPMailer
		require_once(dirname(__FILE__).'/../newsletter/PHPMailer/PHPMailerAutoload.php');
		$phm = new PHPMailer();
		$phm->CharSet = "UTF-8";
		$phm->setFrom($b['mel'], $fm);
		$phm->addReplyTo($b['mel'], $fm);
		$phm->AddAddress($b['mel']);
		$phm->isHTML(true);
		$phm->Subject = stripslashes($subject);
		$phm->Body = stripslashes($msgH);		
		$phm->AltBody = stripslashes($msgT);
		if($phm->Send()) return true;
		else return false;
		}
	else
		{
		$rn = "\r\n";
		$boundary = "-----=".md5(rand());
		$header = "From: ".$fm."<".$b['mel'].">".$rn."Reply-To:".$fm."<".$b['mel'].">";
		$header .= "MIME-Version: 1.0".$rn;
		$header .= "Content-Type: multipart/alternative;".$rn." boundary=\"$boundary\"".$rn;
		$msg = $rn."--".$boundary.$rn;
		$msg .= "Content-Type: text/plain; charset=\"utf-8\"".$rn;
		$msg .= "Content-Transfer-Encoding: 8bit".$rn;
		$msg .= $rn.$msgT.$rn;
		$msg .= $rn."--".$boundary.$rn;
		$msg .= "Content-Type: text/html; charset=\"utf-8\"".$rn;
		$msg .= "Content-Transfer-Encoding: 8bit".$rn;
		$msg .= $rn.$msgH.$rn;
		$msg .= $rn."--".$boundary."--".$rn;
		$msg .= $rn."--".$boundary."--".$rn;
		if(mail($b['mel'], stripslashes($subject), stripslashes($msg), $header)) return true;
		else return false;
		}
	}
function mailUsers($dest, $tit, $body, $Ubusy, $bottom, $top, $sdata)
	{
	$bottom = str_replace('[[unsubscribe]]','&nbsp;',$bottom);
	$q = file_get_contents('../../data/'.$Ubusy.'/site.json'); $a = json_decode($q,true);
	$q = file_get_contents('../../data/_sdata-'.$sdata.'/ssite.json'); $b = json_decode($q,true);
	$q = file_get_contents('../../data/_sdata-'.$sdata.'/users.json'); $c = json_decode($q,true);
	$body = T_("Response on Support").": <b>".$tit."</b><br />".$rn.$body.$rn;
	$msgT = strip_tags($body);
	$msgH = $top . $body . $bottom;
	$subject = $a['tit'].' - '. $tit;
	$fm = preg_replace("/[^a-zA-Z ]+/", "", $a['tit']);
	$dest = explode(',', $dest);
	if(file_exists(dirname(__FILE__).'/../newsletter/PHPMailer/PHPMailerAutoload.php'))
		{
		// PHPMailer
		require_once(dirname(__FILE__).'/../newsletter/PHPMailer/PHPMailerAutoload.php');
		$phm = new PHPMailer();
		$phm->CharSet = "UTF-8";
		$phm->setFrom($b['mel'], $fm);
		$phm->addReplyTo($b['mel'], $fm);
		$phm->isHTML(true);
		$phm->Subject = stripslashes($subject);
		$phm->Body = stripslashes($msgH);		
		$phm->AltBody = stripslashes($msgT);
		foreach($dest as $r)
			{
			$phm->clearAllRecipients();
			$m = '';
			if($r && isset($c['user'][$r])) $m = $c['user'][$r]['e'];
			$phm->AddAddress($m);
			if($m && $m!=$b['mel']) $phm->Send();
			}
		}
	else
		{
		$rn = "\r\n";
		$header  = "From: ".$fm."<".$b['mel'].">".$rn."Reply-To:".$fm."<".$b['mel'].">";
		$header.= "MIME-Version: 1.0".$rn;
		foreach($dest as $r)
			{
			$m = '';
			if($r && isset($c['user'][$r])) $m = $c['user'][$r]['e'];
			$boundary = "-----=".md5(rand());
			$header.= "Content-Type: multipart/alternative;".$rn." boundary=\"$boundary\"".$rn;
			$msg= $rn."--".$boundary.$rn;
			$msg.= "Content-Type: text/plain; charset=\"utf-8\"".$rn;
			$msg.= "Content-Transfer-Encoding: 8bit".$rn;
			$msg.= $rn.$msgT.$rn;
			$msg.= $rn."--".$boundary.$rn;
			$msg.= "Content-Type: text/html; charset=\"utf-8\"".$rn;
			$msg.= "Content-Transfer-Encoding: 8bit".$rn;
			$msg.= $rn.$msgH.$rn;
			$msg.= $rn."--".$boundary."--".$rn;
			$msg.= $rn."--".$boundary."--".$rn;
			if($m && $m!=$b['mel']) @mail($m, stripslashes($subject), stripslashes($msg), $header);
			}
		}
	}
function filtreTag($f)
	{
	// <strong></strong><em></em><a href></a><blockquote></blockquote><ol></ol><ul></ul><li></li>
	$b = 0;
	$out = "";
	for($v=0;$v<strlen($f);++$v)
		{
		if(substr($f,$v,1)=="`" && $b==0)
			{
			$b = 1;
			$out .= '<pre><code>';
			}
		else if(substr($f,$v,1)=="`" && $b==1)
			{
			$b = 0;
			$out .= '</code></pre>';
			}
		else if(substr($f,$v,1)=='<' && substr($f,$v,8)!='<strong>' && substr($f,$v,9)!='</strong>' && substr($f,$v,4)!='<em>' && substr($f,$v,5)!='</em>' && substr($f,$v,7)!='<a href' && substr($f,$v,4)!='</a>' && substr($f,$v,12)!='<blockquote>' && substr($f,$v,13)!='</blockquote>' && substr($f,$v,4)!='<ol>' && substr($f,$v,5)!='</ol>' && substr($f,$v,4)!='<ul>' && substr($f,$v,5)!='</ul>' && substr($f,$v,4)!='<li>' && substr($f,$v,5)!='</li>' && substr($f,$v,3)!='<br') $out .= '&lt;';
		else $out .= substr($f,$v,1);
		}
	return $out;
	}
?>
