<?php
session_start(); 
if(!isset($_POST['unox']) || $_POST['unox']!=$_SESSION['unox']) {sleep(2);exit;} // appel depuis uno.php
?>
<?php
include('../../config.php');
include('lang/lang.php');
$busy = (isset($_POST['ubusy'])?preg_replace("/[^A-Za-z0-9-_]/",'',$_POST['ubusy']):'index');
// ********************* actions *************************************************************************
if(isset($_POST['action'])) {
	switch($_POST['action']) {
		// ********************************************************************************************
		case 'plugin': ?>
		<link rel="stylesheet" type="text/css" media="screen" href="uno/plugins/support/support.css" />
		<div class="blocForm">
			<h2><?php echo T_("Support");?></h2>
			<p><?php echo T_("This plugin creates a complete forum system. It works with the USERS plugin. It is more targeted to online support.");?></p>
			<p><?php echo T_("Just insert the code");?>&nbsp;<code>[[support]]</code>&nbsp;<?php echo T_("in the template, in the page content or in another plugin.");?></p>
			<p><?php echo T_("The language used will be the one selected in the USERS plugin.");?></p>
			<div id="supportNotif" style="display:none"><?php echo T_("Users plugin must be activated!");?></div>
			<div id="supportList">
				<h3><?php echo T_("Support List");?></h3>
				<div id="supportL"></div>
			</div>
			<div class="clear"></div>
		</div>
		<?php break;
		// ********************************************************************************************
		case 'load':
		if(file_exists('../../data/'.$busy.'/support.json')) {
			$q = file_get_contents('../../data/'.$busy.'/support.json');
			echo $q;
		}
		else echo '{}';
		break;
		// ********************************************************************************************
		case 'loadT':
		$l = intval($_POST['topic']);
		if(file_exists('../../data/'.$busy.'/support/support'.$l.'.json')) {
			$q = file_get_contents('../../data/'.$busy.'/support/support'.$l.'.json');
			echo $q;
		}
		else echo '{}';
		break;
		// ********************************************************************************************
		case 'delL':
		$l = intval($_POST['del']);
		if(file_exists('../../data/'.$busy.'/support.json') && $l) {
			$q = file_get_contents('../../data/'.$busy.'/support.json'); $a = json_decode($q,true);
			$b = 0;
			$c = array();
			if(isset($a['list'])) {
				foreach($a['list'] as $k=>$v) {
					if($v['i']==$l) {unset($a['list'][$k]); $b = 1;}
					else $c[] = $a['list'][$k];
				}
				if($b==0) echo '!'.T_('Error');
				else {
					$a['list'] = $c; // remove key in array
					$out = json_encode($a);
					if(file_put_contents('../../data/'.$busy.'/support.json', $out) && unlink('../../data/'.$busy.'/support/support'.$l.'.json')) echo T_('Topic deleted');
					else echo '!'.T_('Undeletable');
				}
			}
		}
		else echo '!'.T_('No data');
		break;
		// ********************************************************************************************
		case 'delT':
		$l = intval($_POST['l']);
		$i = intval($_POST['i']);
		if(file_exists('../../data/'.$busy.'/support.json')  && $l && file_exists('../../data/'.$busy.'/support/support'.$l.'.json')) {
			$q = file_get_contents('../../data/'.$busy.'/support/support'.$l.'.json'); $b = json_decode($q,true);
			$d = 0; $t = 0;
			$c = array();
			if(isset($b['topic'])) {
				foreach($b['topic'] as $k=>$v) {
					if($v['i']==$i) {
						unset($b['topic'][$k]);
						$d = 1;
						if(!$k && isset($b['topic'][1]['d'])) $t = $b['topic'][1]['d'];
					}
					else $c[] = $b['topic'][$k];
				}
				if($d==0) echo '!'.T_('Error');
				else {
					$b['topic'] = $c; // remove key in array
					$out = json_encode($b);
					$q = file_get_contents('../../data/'.$busy.'/support.json'); $a = json_decode($q,true);
					foreach($a['list'] as $k=>$v) {
						if($v['i']==$l) {
							$a['list'][$k]['n']--;
							if($t) $a['list'][$k]['d'] = $t;
							if(!$a['list'][$k]['n']) unset($a['list'][$k]);
							break;
						}
					}
					if(file_put_contents('../../data/'.$busy.'/support/support'.$l.'.json', $out) && file_put_contents('../../data/'.$busy.'/support.json', json_encode($a))) echo T_('Topic deleted');
					else echo '!'.T_('Undeletable');
				}
			}
			// traiter le cas du plus récent => changement user et date dans LIST !!!
		}
		else echo '!'.T_('No data');
		break;
		// ********************************************************************************************
		case 'saveL':
		$i = intval($_POST['i']);
		$t = strip_tags($_POST['t']);
		if(file_exists('../../data/'.$busy.'/support.json')) {
			$b = array();
			$q = file_get_contents('../../data/'.$busy.'/support.json'); $a = json_decode($q,true);
			if(isset($a['list'])) {
				$c = 0;
				foreach($a['list'] as $k=>$v) {
					if($v['i']==$i) {
						$a['list'][$k]['t'] = $t;
						$c = 1;
					}
					$b[] = $v;
				}
				$a['list'] = $b; // clear key
				if($c && file_put_contents('../../data/'.$busy.'/support.json', json_encode($a))) {
					echo T_('Saved');
					exit;
				}
			}
		}
		echo '!'.T_('Error');
		break;
		// ********************************************************************************************
		case 'saveT':
		$l = intval($_POST['l']);
		$i = intval($_POST['i']);
		$p = utf8_encode(urldecode(base64_decode($_POST['c'])));
		if(file_exists('../../data/'.$busy.'/support.json')  && $l && file_exists('../../data/'.$busy.'/support/support'.$l.'.json')) {
			$q = file_get_contents('../../data/'.$busy.'/support/support'.$l.'.json'); $b = json_decode($q,true);
			if(isset($b['topic'])) {
				$c = 0;
				foreach($b['topic'] as $k=>$v) {
					if($v['i']==$i) {
						$b['topic'][$k]['c'] = $p;
						$c = 1;
					}
				}
				if($c && file_put_contents('../../data/'.$busy.'/support/support'.$l.'.json', json_encode($b, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES))) {
					echo T_('Saved');
					exit;
				}
			}
		}
		echo '!'.T_('Error');
		break;
		// ********************************************************************************************
		case 'saveM':
		$i = intval($_POST['i']);
		$l = intval($_POST['l']); // current
		$m = intval($_POST['m']); // move to
		if(file_exists('../../data/'.$busy.'/support.json')  && $l && file_exists('../../data/'.$busy.'/support/support'.$l.'.json') && $m && ($m==-1 || file_exists('../../data/'.$busy.'/support/support'.$m.'.json'))) {
			$q = file_get_contents('../../data/'.$busy.'/support.json'); $a = json_decode($q,true);
			$q = file_get_contents('../../data/'.$busy.'/support/support'.$l.'.json'); $b = json_decode($q,true);
			if($m!=-1) { // move to new topic
				$q = file_get_contents('../../data/'.$busy.'/support/support'.$m.'.json'); $c = json_decode($q,true);
			}
			if(isset($a['list'])) {
				$d = 0; $t = 0; $t0 = 0; $id = 0; $b1 = array(); $m1 = ',';
				foreach($b['topic'] as $k=>$v) { // support/supportZZ.json : $b & $c
					if($v['i']==$i) {
						unset($b['topic'][$k]);
						if($m!=-1) {
							$c['topic'][] = $v;
							usort($c['topic'],'sortDate');
						}
						$t = $v['d'];
						$d = 1;
						$w = $v;
					}
					else {
						if($t0<$v['d']) $t0 = $v['d']; // last post
						$b['topic'][$k]['i'] = $id; // renum i
						$b1[] = $b['topic'][$k];
						$m1 .= $v['u'].','; // member already in the post ?
						++$id;
					}
				}
				$b['topic'] = $b1;
				if(strpos($c['mail'], ','.$w['u'].',')===false && strpos($b['mail'], ','.$w['u'].',')!==false) $c['mail'] .= $w['u'].','; // Add mail
				if(strpos($b['mail'], ','.$w['u'].',')!==false && strpos($m1, ','.$w['u'].',')===false) $b['mail'] = str_replace($w['u'].',', '', $b['mail']); // Remove mail
				//
				if($d) {
					$id = 0;
					foreach($c['topic'] as $k=>$v) {
						$c['topic'][$k]['i'] = $id; // renum i
						++$id;
					}
					$e = array(); $f = 0;
					foreach($a['list'] as $k=>$v) { // support.json : $a
						if($v['i']>$f) $f = $v['i']; // max (new)
						if($v['i']==$l) {
							if($v['n']>1) {
								$a['list'][$k]['d'] = $t0;
								$a['list'][$k]['n']--;
							}
							else {
								unset($a['list'][$k]);
								$d = 2;
							}
						}
						else if($m!=-1 && $v['i']==$m) {
							if($v['d']<$t) $a['list'][$k]['d'] = $t;
							$a['list'][$k]['n']++;
						}
						if(isset($a['list'][$k])) $e[] = $a['list'][$k];
					}
					if($m==-1) {
						$m = $f + 1;
						$e[] = array('i'=>$m, 'n'=>1, 't'=>'_new_', 'u'=>$w['u'], 'd'=>$t, 'r'=>'r', 's'=>0);
						$c = array();
						$c['topic'][] = array('i'=>0, 'c'=>$w['c'], 'u'=>$w['u'], 'd'=>$t);
						$c['mail'] = ','.$w['u'].',';
					}
					$a['list'] = $e; // remove key in array
					if(file_put_contents('../../data/'.$busy.'/support.json', json_encode($a)) && ($d==2 || file_put_contents('../../data/'.$busy.'/support/support'.$l.'.json', json_encode($b))) && file_put_contents('../../data/'.$busy.'/support/support'.$m.'.json', json_encode($c))) {
						if($d==2) unlink('../../data/'.$busy.'/support/support'.$l.'.json');
						echo T_('Saved');
						exit;
					}
				}
			}
		}
		echo '!'.T_('Error');
		break;
		// ********************************************************************************************
	}
	clearstatcache();
}
function sortDate($i,$j){return strcmp($j['d'], $i['d']);}
?>
