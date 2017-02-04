<?php
session_start(); 
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])!='xmlhttprequest') {sleep(2);exit;} // ajax request
if(!isset($_POST['unox']) || $_POST['unox']!=$_SESSION['unox']) {sleep(2);exit;} // appel depuis uno.php
?>
<?php
include('../../config.php');
include('lang/lang.php');
$q = file_get_contents('../../data/busy.json'); $a = json_decode($q,true); $Ubusy = $a['nom'];
// ********************* actions *************************************************************************
if (isset($_POST['action']))
	{
	switch ($_POST['action'])
		{
		// ********************************************************************************************
		case 'plugin': ?>
		<link rel="stylesheet" type="text/css" media="screen" href="uno/plugins/support/support.css" />
		<div class="blocForm">
			<h2><?php echo T_("Support");?></h2>
			<p><?php echo T_("This plugin creates a complete forum system. It works with the USERS plugin. It is more targeted to online support.");?></p>
			<p><?php echo T_("Just insert the code");?>&nbsp;<code>[[support]]</code>&nbsp;<?php echo T_("in the template, in the page content or in another plugin.");?></p>
			<p><?php echo T_("The language used in the emails will be the one selected in the USERS plugin.");?></p>
			<div id="usersList">
				<h3><?php echo T_("Support List");?></h3>
				<div id="supportL"></div>
			</div>
			<div class="clear"></div>
		</div>
		<?php break;
		// ********************************************************************************************
		case 'load':
		if(file_exists('../../data/'.$Ubusy.'/support.json'))
			{
			$q = file_get_contents('../../data/'.$Ubusy.'/support.json');
			echo $q;
			}
		else echo false;
		exit;
		break;
		// ********************************************************************************************
		case 'loadT':
		$l = $_POST['topic'];
		if(file_exists('../../data/'.$Ubusy.'/support/support'.$l.'.json'))
			{
			$q = file_get_contents('../../data/'.$Ubusy.'/support/support'.$l.'.json');
			echo $q;
			}
		else echo false;
		exit;
		break;
		// ********************************************************************************************
		case 'delL':
		$l = $_POST['del'];
		if(file_exists('../../data/'.$Ubusy.'/support.json') && $l)
			{
			$q = file_get_contents('../../data/'.$Ubusy.'/support.json'); $a = json_decode($q,true);
			$b = 0;
			$c = array();
			if(isset($a['list']))
				{
				foreach($a['list'] as $k=>$v)
					{
					if($v['i']==$l) {unset($a['list'][$k]); $b = 1;}
					else $c[] = $a['list'][$k];
					}
				if($b==0) echo '!'.T_('Error');
				else
					{
					$a['list'] = $c; // remove key in array
					$out = json_encode($a);
					if(file_put_contents('../../data/'.$Ubusy.'/support.json', $out) && unlink('../../data/'.$Ubusy.'/support/support'.$l.'.json')) echo T_('Topic deleted');
					else echo '!'.T_('Undeletable');
					}
				}
			}
		else echo '!'.T_('No data');
		break;
		// ********************************************************************************************
		case 'delT':
		$l = $_POST['l'];
		$i= $_POST['i'];
		if(file_exists('../../data/'.$Ubusy.'/support.json')  && $l && file_exists('../../data/'.$Ubusy.'/support/support'.$l.'.json'))
			{
			$q = file_get_contents('../../data/'.$Ubusy.'/support/support'.$l.'.json'); $b = json_decode($q,true);
			$d = 0; $t = 0;
			$c = array();
			if(isset($b['topic']))
				{
				foreach($b['topic'] as $k=>$v)
					{
					if($v['i']==$i)
						{
						unset($b['topic'][$k]);
						$d = 1;
						if(!$k && isset($b['topic'][1]['d'])) $t = $b['topic'][1]['d'];
						}
					else $c[] = $b['topic'][$k];
					}
				if($d==0) echo '!'.T_('Error');
				else
					{
					$b['topic'] = $c; // remove key in array
					$out = json_encode($b);
					$q = file_get_contents('../../data/'.$Ubusy.'/support.json'); $a = json_decode($q,true);
					foreach($a['list'] as $k=>$v)
						{
						if($v['i']==$l)
							{
							$a['list'][$k]['n']--;
							if($t) $a['list'][$k]['d'] = $t;
							if(!$a['list'][$k]['n']) unset($a['list'][$k]);
							break;
							}
						}
					if(file_put_contents('../../data/'.$Ubusy.'/support/support'.$l.'.json', $out) && file_put_contents('../../data/'.$Ubusy.'/support.json', json_encode($a))) echo T_('Topic deleted');
					else echo '!'.T_('Undeletable');
					}
				}
			// traiter le cas du plus récent => changement user et date dans LIST !!!
			}
		else echo '!'.T_('No data');
		break;
		// ********************************************************************************************
		}
	clearstatcache();
	exit;
	}
?>
