<?php
/*
Plugin Name: No Update
Plugin URI: http://www.allancollins.net/168/wordpress-plugin-no-update/
Description: Disable plugin upgrade options for selected plugins.
Version: 1.1.7
Author: Allan Collins
Author URI: http://www.allancollins.net/
*/



/*



Copyright (C) 2009 Allan Collins







This program is free software; you can redistribute it and/or modify



it under the terms of the GNU General Public License as published by



the Free Software Foundation; either version 3 of the License, or



(at your option) any later version.







This program is distributed in the hope that it will be useful,



but WITHOUT ANY WARRANTY; without even the implied warranty of



MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the



GNU General Public License for more details.







You should have received a copy of the GNU General Public License



along with this program.  If not, see <http://www.gnu.org/licenses/>.



*/







function noUpdate_start() {







	add_options_page('Ignore Plugin Upgrades', 'Ignore Plugin Upgrades', 7, 'ignorepluginupgrades', 'noUpdate_options');







}





function noUpdate_activate() {


$pluginName[]="No Update";

	update_option('upgradeIgnore', serialize($pluginName));
	}









function upgrade_checker() {



$pluginName=unserialize(get_option('upgradeIgnore'));



$showCount=get_option('show_plugin_count');



if ($showCount == true) {







	echo "<style type=\"text/css\">span.update-plugins { display:none; }</style>";







}



	echo "



	<script type=\"text/javascript\">



	



		jQuery(document).ready(function() {



			



			jQuery(\".plugin-update\").prepend(\"<a href='javascript: ' class='update-genie'>Ignore Upgrade Option</a>  |  \");



		";



		$i=0;



		if (count($pluginName) != 0) {



		



	foreach ($pluginName as $k=>$v) {



	if ($v != '') {



			echo "	jQuery(\"a[title='$v']\").parent().fadeOut(\"slow\");";



	$i++;



			}







		}



		}



	echo "	



		



		var x = jQuery('.plugin-count').html();



		var y=x-" . $i . ";



		if (y == 0) {



		jQuery('.plugin-count').fadeOut(1);



		}else{



		jQuery('.plugin-count').html(' ' + y );



		}



		jQuery('a.update-genie').click(function() {



		var theParent=jQuery(this).parent();



		var parentID=jQuery(this).parent().children('a').eq(1).attr('title');



	



		jQuery.get('options-general.php?page=ignorepluginupgrades&add=";



		echo "' + parentID);



		jQuery(theParent).fadeOut('slow');



		});



		



		



		});



	



	</script>



	



	



	";











}











function noUpdate_options() {







if (isset($_POST['Submit'])) {







	



	update_option('upgradeIgnore', serialize($_POST['plugin']));



	update_option('show_plugin_count',$_POST['plugincount']);



	



	echo "<script type=\"text/javascript\">window.location='options-general.php?page=ignorepluginupgrades';</script>";



}elseif (isset($_GET['add'])) {







	$pluginName=get_option('upgradeIgnore');



	$pluginName=unserialize($pluginName);



	



	$pluginName[]=$_GET['add'];



	



	update_option('upgradeIgnore', serialize($pluginName));



	



	



	die();











}



?>











<div class="wrap">



<h2>Upgrade Ignore List</h2>







<form method="post" action="options-general.php?page=ignorepluginupgrades">











<table class="form-table">



<tr valign="top">



<th scope="row">Hide Plugin Upgrade Count:</th>



<td>



<?php



$showCount=get_option('show_plugin_count');



if ($showCount == true) {



$checked="checked=\"checked\"";



}else{



$checked="";



}



?>



<input type="checkbox" name="plugincount" value="true" <?php echo $checked; ?> /> Yes</td>



</tr>







<tr valign="top">



<th scope="row">Plugin Name:</th>



<?php



$pluginName=get_option('upgradeIgnore');



$pluginName=unserialize($pluginName);



?>



<td><?php







if (count($pluginName) != 0) {



foreach ($pluginName as $k=>$v) {?>











<input type="checkbox" name="plugin[]" value="<?php echo $v; ?>" checked="checked" /> <?php echo $v; ?><br />



<?php }







}else{



echo "No plugin upgrade alerts are being ignored.";



}



?>



<br />







</td>



</tr>



 







</table>











<p class="submit">



<input type="submit" name="Submit" value="<?php _e('Save Changes') ?>" />



</p>







</form>



</div>



<?php







}



















add_action('admin_menu', 'noUpdate_start');



add_action('admin_head', 'upgrade_checker');


register_activation_hook( __FILE__, 'noUpdate_activate' );





?>