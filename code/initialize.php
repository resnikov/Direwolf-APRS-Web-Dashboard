<?php
// This file is part of the Direwolf APRS Web Dashboard as available at https://github.com/PC7MM/Direwolf-APRS-Web-Dashboard
// Developed by Michael PC7MM and Richard PD3RFR as an extension of https://github.com/IZ7BOJ/direwolf_webstat and https://github.com/IZ7BOJ/APRS_dashboard as developed by Alfredo IZ7BOJ
// See config.php for adjustable parameters and see https://www.youtube.com/watch?v=7bMf7rWCfnE for more information

session_start();
include 'config.php';
include 'functions.php';

if (str_contains($_SERVER['PHP_SELF'],"traffic.php")) $_SESSION['daysback']=0; // go to logfile of today if traffic monitor is to be loaded

if (!isset($_SESSION['daysback'])) $_SESSION['daysback']=0; else $_SESSION['daysback']=intval($_SESSION['daysback']); // older sessions may hold a non-numeric value

if (!isset($_SESSION['showedbufferwarning'])) $_SESSION['showedbufferwarning']=0;

if(!isset($_SESSION['if'])) {
	if ($static_if==1) {
 		$_SESSION['if']=$static_if_index;
	} else {
		$_SESSION['if']=0;
		header('Refresh: 0; url=chgif.php'); // show change interface page if static interface is disabled
		die();
	}
}
$if = $_SESSION['if'];

// substr and strip_tags are used to get rid of possible malicious input

// only accept the values offered on the pages: anything else would end up in arithmetic and stop every page with a TypeError for the rest of the session
if (isset($_GET['time']) and in_array($_GET['time'], array("1","2","4","6","12","e"), true)) $_SESSION['timevalue'] = $_GET['time'];

if (isset($_GET['daysback']) and ($_GET['daysback'] !== "")) $_SESSION['daysback'] = min(max(intval($_GET['daysback']),0),99); // else if (!isset($_GET['ajax'])) $_SESSION['daysback']=0;

if (isset($_GET['getcall'])) $_SESSION['callsign'] = strip_tags(substr($_GET['getcall'],0,9));

if ($fixedlogname!="") {
		$newlogname = $fixedlogname." (fixed)";
                $log=$logpath.$fixedlogname;
        } else {
                $newlogname=gmdate("Y-m-d",time()-$_SESSION['daysback']*86400).'.log'; // Direwolf names its daily logfiles by UTC date
                $log=$logpath.$newlogname;
	}

if (is_file($log)) $logfile = file($log); else $logfile = [];

foreach($ajaxupdatehtml as $file) { if (str_contains($_SERVER['SCRIPT_NAME'],$file)) { $ajaxupdatetype="html"; } } // for replacing dynamic content on page

foreach($ajaxupdateappend as $file) { if (str_contains($_SERVER['SCRIPT_NAME'],$file)) { $ajaxupdatetype="append"; } } // for appending dynamic content on page

if (!isset($_GET['ajax']) and !isset($_GET['if'])) {
	include 'header.php';
	include 'ajaxupdate.php';
}
?>
