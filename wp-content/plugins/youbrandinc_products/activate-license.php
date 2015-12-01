<?php include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); ?>
<div class="wrap">
	<?php screen_icon('options-general'); ?> <h2><?php echo _e('You Brand, Inc. Plugin Licensing'); ?></h2><br />
<?php
	$allErrors = '';

	foreach($AllProductsArr as $product)
	{
		if(isYBIPluginActive($product->active_name)) 
		{
			$spbasObj = $product->spbas_obj;


			if($spbasObj->errors)
				$allErrors .= '<li><strong>'. $product->name.'</strong> '.$spbasObj->errors.'</li>';
		}
	}

	if($allErrors <> ''): 
?>
	<div id="message" class="error">
		<p><ul>
		  <?php echo _e($allErrors); ?>
		</ul></p>
	</div>
	<?php endif; // if($allErrors <> '')  ?>
<?php 
	if ($license_updated): 
		foreach($AllProductsArr as $product)
		{
			if(isYBIPluginActive($product->active_name)) 
			{
				$spbasObj = $product->spbas_obj;


				if(!$spbasObj->errors && $spbasObj->license_key):
					  $updateMessages .= '<p><b>'. $product->name . ' license activated successfully!</b></p>';
				endif;            
			}
		}

		if ($updateMessages <> ''): 
?>
        <div id="message" class="updated">
            <?php echo $updateMessages; ?>
        </div>
<?php 	
		endif; // if ($updateMessages <> ''):
	endif; //	if ($license_updated):  
?>    

<form method="post">
<?php wp_nonce_field('youbrandinc_license', 'youbrandinc_license'); ?>
	<p class="howto">
	<?php 
		$from = $_GET['fromCheck'];
		echo _e('To activate <strong>'. $from .'</strong> please enter the license key that was e-mailed to you. You can also <a href="http://members.youbrandinc.com/" target="_blank">login here</a> 
		(licensing information can be found by visiting the licensing menu).');
	?>
	</p>

<?php 
foreach($AllProductsArr as $product)
{
	if(isYBIPluginActive($product->active_name)) 
	{
		$spbasObj = $product->spbas_obj;
		//$spbasObj->clear_cache_local_key(true);
		//var_dump($spbasObj);

?>
		<p>	
        	<b><?php echo _e($product->name); ?>: </b> 
            <input name="<?php echo $product->prefix; ?>_license_key" type="text" value="<?php echo $spbasObj->license_key; ?>" style="width: 300px;" /> 
            <?php if($spbasObj->license_key == '') { ?><a href="<?php echo $product->info_url; ?>" target="_blank"><?php echo _e('Learn More'); ?></a><?php } ?>
		</p>
<?php		
	}
	else
	{
?>
      <p>
      <strong><?php echo _e($product->name); ?></strong> - <a href="<?php echo $product->info_url; ?>" target="_blank"><?php echo _e('Learn More'); ?></a> 
      | <a href="<?php echo(self_admin_url('plugin-install.php?tab=upload')); ?>" target=""><?php echo _e('Install Plugin'); ?></a>
      | <a href="<?php echo(self_admin_url('plugins.php?plugin_status=inactive')); ?>" target=""><?php echo _e('Activate Plugin'); ?></a>
      </p>
<?php	
	}
}

 ?>
    <div style="width: 440px; text-align:right;">
        <p><input type="submit" class="button-primary" value="<?php echo _e('Activate Products'); ?>" /> </p>
    </div>
	</form>
</div><!--wrap yo-->