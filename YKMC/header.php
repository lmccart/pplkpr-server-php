<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="./css/style.css" rel="stylesheet" type="text/css" />


<title>YKMC</title>

<!--
<script type="text/javascript" src="http://b-experience.net/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="http://b-experience.net/js/jquery-ui-1.8rc3.custom.min.js"></script>
<script type="text/javascript" src="http://b-experience.net/js/jquery.scrollTo.js"></script>
<script type="text/javascript" src="http://b-experience.net/js/b.js"></script>
-->

</head>

<?php require_once('functions.php');
echo 'login:'.$_SESSION['LOGIN'].' email:'.$_SESSION['EMAIL'].' number:'.$_SESSION['NUMBER'].' gameid:'.$_SESSION['GAMEID'].' turn:'.$_SESSION['TURN'].' invited:'.$_SESSION['INVITED'].' invitedby:'.$_SESSION['INVITEDBY'];