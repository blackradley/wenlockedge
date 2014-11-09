<!DOCTYPE html>
<!--[if IE 7 ]>					<html class="ie lt-ie8 ie7" lang="en"> <![endif]-->
<!--[if gt IE 7]>  				<html class="ie gt-ie7"> <![endif]-->
<!--[if !IE]><!--> 				<html lang="en"><!--<![endif]-->
<head>
	<link href="/public/css/customization.css" rel="stylesheet" />
</head>
<body>
	<h1>File Browser</h1>
	<h2><?php echo $virtual_root.'/'.$path_in_url ?></h2>
<?php
    $prefix = $controller.'/'.$method.'/'.$path_in_url;
    if (!empty($dirs)) foreach( $dirs as $dir )
        echo '/'.anchor($prefix.$dir['name'], $dir['name']).'<br>';
 
    if (!empty($files)) foreach( $files as $file )
        echo anchor($prefix.$file['name'], $file['name']).'<br>';
?>
</body>