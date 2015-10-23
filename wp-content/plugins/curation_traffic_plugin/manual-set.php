<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Install Curate This Bookmarklet</title>
</head>

<body>
<div id="content-wrap">
<!--<img src="http://curationtraffic.com/wp-content/uploads/2012/01/curation-traffic-header51.png" />-->
<?php
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-load.php' );

if($_GET['manual'] == "yes")
{
	$dbVersion = get_option('curate-this-version');
	if($dbVersion != '')
		update_option('curate-this-version',CURATETHIS_VERSION);	
	else
		add_option('curate-this-version',CURATETHIS_VERSION);
		
	echo "manual install complete";
}
?>
</div><!-- content-wrap -->
</body>
</html>