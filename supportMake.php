<?php
if(!isset($_SESSION['cmsuno'])) exit();
?>
<?php
if(!file_exists('data/'.$Ubusy.'/support.json')) file_put_contents('data/'.$Ubusy.'/support.json', '');
if(!is_dir('data/'.$Ubusy.'/support')) mkdir('data/'.$Ubusy.'/support');
$out = '<div id="support" class="sp"></div>'."\r\n";
$Uhtml = str_replace('[[support]]',$out,$Uhtml);
$Ucontent = str_replace('[[support]]',$out,$Ucontent);
$Uhead .= '<link rel="stylesheet" href="uno/plugins/support/supportInc.css" type="text/css" />'."\r\n";
$Uhead .= '<script type="text/javascript" src="uno/plugins/support/quicktags-min.js"></script>'."\r\n";
$Ufoot .= '<script type="text/javascript" src="uno/plugins/support/supportInc.js"></script>'."\r\n";
$Ustyle .= '.wrapTopicClose{background-image:url("'.$Udep.'includes/img/close.png");}'."\r\n";
$unoUbusy = 1; // insert Ubusy with $script
?>
