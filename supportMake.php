<?php
if(!isset($_SESSION['cmsuno'])) exit();
?>
<?php
if(!file_exists('data/'.$Ubusy.'/support.json')) file_put_contents('data/'.$Ubusy.'/support.json', '');
if(!is_dir('data/'.$Ubusy.'/support')) mkdir('data/'.$Ubusy.'/support');
if(strpos($Uhtml.$Ucontent,'[[support]]')!==false && file_exists('data/_sdata-'.$sdata.'/users.json'))
	{
	$lang0 = $lang;
	$q = file_get_contents('data/_sdata-'.$sdata.'/users.json'); $a = json_decode($q,true);
	if(!empty($a['g'])) $lang = $a['g'];
	include('plugins/support/lang/lang.php'); // lang from User plugin
	//
	$out = '<div id="support" class="sp"></div>'."\r\n";
	$Uhtml = str_replace('[[support]]',$out,$Uhtml);
	$Ucontent = str_replace('[[support]]',$out,$Ucontent);
	$Uhead .= '<link rel="stylesheet" href="uno/plugins/support/supportInc.css" type="text/css" />'."\r\n";
	$Uhead .= '<link rel="Stylesheet" type="text/css" href="uno/plugins/support/bbcode/editor.css" />'."\r\n";
	$Uhead .= '<script type="text/javascript" src="uno/plugins/support/bbcode/editor-min.js"></script>'."\r\n";
	$Ufoot .= '<script type="text/javascript">var supTr={'.
		'"mem":"'.T_("Member").'"'.
		',"tot":"'.T_("Topic title").'"'.
		',"b":"'.T_("Bold").'"'.
		',"i":"'.T_("Italic").'"'.
		',"und":"'.T_("Underline").'"'.
		',"lin":"'.T_("Hyperlink").'"'.
		',"ima":"'.T_("Image").'"'.
		',"lis":"'.T_("List").'"'.
		',"col":"'.T_("Color").'"'.
		',"quo":"'.T_("Quote").'"'.
		',"cod":"'.T_("Code").'"'.
		',"sou":"'.T_("switch to source").'"'.
		',"pot":"'.T_("Post").'"'.
		',"pts":"'.T_("Posts").'"'.
		',"ntm":"'.T_("Notify me of follow-up posts via email").'"'.
		',"mar":"'.T_("Mark this topic as resolved").'"'.
		',"toc":"'.T_("Topic").'"'.
		',"lap":"'.T_("Last Poster").'"'.
		',"fre":"'.T_("Freshness").'"'.
		',"res":"'.T_("resolved").'"'.
		',"ato":"'.T_("Add Topic").'"'.
		',"stf":"'.T_("Staff").'"'.
		',"psd":"'.T_("Add Topic").'"'.
		',"rep":"'.T_("Reply").'"'.
		',"ytr":"'.T_("You must login to reply").'"'.
		'};</script>'."\r\n";
	$Ufoot .= '<script type="text/javascript" src="uno/plugins/support/supportInc.js"></script>'."\r\n";
	$Ustyle .= '.wrapTopicClose{background-image:url("'.$Udep.'includes/img/close.png");}'."\r\n";
	$unoUbusy = 1; // insert Ubusy with $script
	//
	$lang = $lang0;
	}
?>
