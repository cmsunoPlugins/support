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
		$u = strip_tags($_POST['u']);
		if(file_exists('../../data/'.$u.'/support.json'))
			{
			echo file_get_contents('../../data/'.$u.'/support.json');
			}
		else echo false;
		break;
		// ********************************************************************************************
		case 'topic':
		$u = strip_tags($_POST['u']);
		$i = intval($_POST['i']);
		if(file_exists('../../data/'.$u.'/support/support'.$i.'.json') && file_exists('../../data/_sdata-'.$sdata.'/users.json'))
			{
			$q = file_get_contents('../../data/'.$u.'/support/support'.$i.'.json'); $a = json_decode($q,true);
			$q = file_get_contents('../../data/_sdata-'.$sdata.'/users.json'); $b = json_decode($q,true); // add email for gravatar
			$q = file_get_contents('../../data/'.$u.'/support.json'); $c = json_decode($q,true); // check resolved
			foreach($c['list'] as $r)
				{
				if($r['i']==$i)
					{
					$a['r'] = $r['r']; // resolve
					break;
					}
				}
			foreach($a['topic'] as $k=>$v)
				{
				if($v['i']==0) $a['u'] = $v['u']; // first writter
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
		$u = strip_tags($_POST['u']); // ubusy
		$m = strip_tags($_POST['m']); // member
		$to = strip_tags($_POST['t']); // topic (title)
		$e = intval($_POST['e']); // mailme
		$re = intval($_POST['r']); // resolve
		$i = intval($_POST['i']);
		$cont = utf8_encode(urldecode(base64_decode($_POST['c'])));
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
		$q = file_get_contents('../../data/'.$u.'/support.json');
		$a = json_decode($q,true);
		$cont = str_replace('<', '&lt;', $cont);
		$cont = str_replace('>', '&gt;', $cont);
		$cont = str_replace('[br]', '<br>', $cont);
		$cont = bb2html($cont);
		if($i!='' && file_exists('../../data/'.$u.'/support/support'.$i.'.json')) // add in a topic
			{
			// TOPIC
			$q = file_get_contents('../../data/'.$u.'/support/support'.$i.'.json');
			$b = json_decode($q,true);
			$n = 1; $j = 0;
			foreach($b['topic'] as $r)
				{
				++$n;
				if(intval($r['i'])>$j) $j = intval($r['i']);
				}
			++$j;
			$b['topic'][] = array('i'=>$j, 'c'=>$cont, 'u'=>$m, 'd'=>$t);
			$mel = (isset($b['mail'])?$b['mail']:0);
			if(!isset($b['mail'])) $b['mail'] = ',';
			if($e && strpos($b['mail'], ','.$m.',')===false) $b['mail'] .= $m.',';
			usort($b['topic'],'sortDate');
			$out = json_encode($b);
			// LIST
			foreach($a['list'] as $k=>$v)
				{
				if($v['i']==$i)
					{
					$a['list'][$k]['n'] = $n;
					$a['list'][$k]['u'] = $m;
					$a['list'][$k]['d'] = $t;
					$a['list'][$k]['r'] = $re;
					$tit = $a['list'][$k]['t'];
					if(!isset($a['list'][$k]['s'])) $a['list'][$k]['s'] = 0; // 1:staff
					break;
					}
				}
			usort($a['list'],'sortDate');
			$out1 = json_encode($a);
			//
			if(file_put_contents('../../data/'.$u.'/support/support'.$i.'.json', $out) && file_put_contents('../../data/'.$u.'/support.json', $out1))
				{
				echo "OK";
				mailAdmin(T_("Response").' - '.$tit, $cont, $u, $bottom, $top, $sdata);
				if($mel) mailUsers($mel, T_("Response").' - '.$tit, $cont, $u, $bottom, $top, $sdata);
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
			$j = 0;
			if(isset($a['list']) && is_array($a['list'])) foreach($a['list'] as $r)
				{
				if(intval($r['i'])>$j) $j = intval($r['i']);
				}
			++$j;
			$a['list'][] = array('i'=>$j, 'n'=>1, 't'=>$to, 'u'=>$m, 'd'=>$t, 'r'=>$re, 's'=>($j==$a['staf']?1:0));
			usort($a['list'],'sortDate');
			$out1 = json_encode($a);
			//
			$a = array();
			$a['topic'][] = array('i'=>0, 'c'=>$cont, 'u'=>$m, 'd'=>$t);
			$a['mail'] = ',' . ($e?$m.',':'');
			$out = json_encode($a);
			if(file_put_contents('../../data/'.$u.'/support/support'.$j.'.json', $out) && file_put_contents('../../data/'.$u.'/support.json', $out1))
				{
				echo "OK";
				mailAdmin(T_("New Topic").' : '.$to, $cont, $u, $bottom, $top, $sdata);
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
	$q = file_get_contents('../../data/'.$Ubusy.'/site.json'); $a = json_decode($q,true);
	$q = file_get_contents('../../data/_sdata-'.$sdata.'/ssite.json'); $b = json_decode($q,true);
	$rn = "\r\n";
	$url = $a['url'].'/'.$a['nom'].'.html';
	$bottom = str_replace('[[unsubscribe]]','<a href="'.$url.'">'.$url.'</a>',$bottom);
	$body = '<div style="width:100%; max-width:560px">'.T_("New Topic on Support").": <b>".$tit."</b><br />".$rn.$body.'</div>'.$rn;
	$msgT = strip_tags($body).$rn.$url;
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
	$q = file_get_contents('../../data/'.$Ubusy.'/site.json'); $a = json_decode($q,true);
	$q = file_get_contents('../../data/_sdata-'.$sdata.'/ssite.json'); $b = json_decode($q,true);
	$q = file_get_contents('../../data/_sdata-'.$sdata.'/users.json'); $c = json_decode($q,true);
	$rn = "\r\n";
	$url = $a['url'].'/'.$a['nom'].'.html';
	$bottom = str_replace('[[unsubscribe]]','<a href="'.$url.'">'.$url.'</a>',$bottom);
	$body = '<div style="width:100%; max-width:560px">'.T_("Response on Support").": <b>".$tit."</b><br />".$rn.$body.'</div>'.$rn;
	$msgT = strip_tags($body).$rn.$url;
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
		$header = "From: ".$fm."<".$b['mel'].">".$rn."Reply-To:".$fm."<".$b['mel'].">";
		$header .= "MIME-Version: 1.0".$rn;
		foreach($dest as $r)
			{
			$m = '';
			if($r && isset($c['user'][$r])) $m = $c['user'][$r]['e'];
			$boundary = "-----=".md5(rand());
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
			if($m && $m!=$b['mel']) @mail($m, stripslashes($subject), stripslashes($msg), $header);
			}
		}
	}
function bb2html($f)
	{
	$b = array(
        '#\[b\](.*)\[/b\]#Usi',
        '#\[i\](.*)\[/i\]#Usi',
        '#\[u\](.*)\[/u\]#Usi',
        '#\[img\](.*)\[/img\]#Usi',
        '#\[url\](.*)\[/url\]#Usi',
        '#\[url=(.*)\](.*)\[/url\]#Usi',
        '#\[ul\](.*)\[/ul\]#Usi',
        '#\[li\](.*)\[/li\]#Usi',
        '#\[color=(.*)\](.*)\[/color\]#Usi',
        '#\[quote=\](.*)\[/quote\]#Usi',
        '#\[code\](.*)\[/code\]#Usi'
		);
	$h = array(
        '<strong>$1</strong>',
        '<em>$1</em>',
        '<span style="text-decoration:underline;">$1</span>',
        '<img src="$1" alt="Image" />',
        '<a href="$1">$1</a>',
        '<a href="$1">$2</a>',
        '<ul>$1</ul>',
        '<li>$1</li>',
        '<span style="color:#$1">$2</span>',
        '<blockquote>$1</blockquote>',
        '<pre>$1</pre>'
		);
    for($i=0; $i<count($b); ++$i)
		{
		$f = preg_replace($b[$i], $h[$i], $f);
		}
	return $f;
	}
?>
