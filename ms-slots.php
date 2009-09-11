<?php 
/*
Plugin Name: MS Slots
Plugin URI: http://shahidmau.blogspot.com
Description: Plugin to display HTML/Javascripts/Text anywhere in your theme in a very easy way. You can also display your contents randomly.
Author: M. Shahid (mshahid85@gmail.com)
Version: 1.0
Author URI: http://shahidmau.blogspot.com
*/


///////////////////////////////////////////////////////////
function ms_total_slots()
{
	if($_POST['ms_total_slots'] <= '100' and $_POST['ms_total_slots']!='' ) 
	{
		update_option('ms_total_slots',$_REQUEST['ms_total_slots']);
	}
}
function ms_slot_up($what)
{
	if($_POST["ms_slot_form"] == '1') 
	{
		update_option($what,$_REQUEST[$what]);
	}
}

function ms_random_slots($exclude='')
{
	for($i=1;$i<=get_option("ms_total_slots");$i++)
	{
		$ms_slots_array[]=get_option("ms_slot$i");
	}
	if($exclude!='')
	{
		$change_arr=explode(",",trim($exclude));
		for($i=0;$i<count($change_arr);$i++)
		{
			unset($ms_slots_array[$change_arr[$i]-1]);
		}
	}		
	return $ms_slots_array[array_rand($ms_slots_array)];
}	

function ms_slots($which)
{
	$c.="<!-- starting of ms_slot$which -->";
	$c.="<!-- ".stripslashes(get_option("ms_slot_remarks".$which))." -->";
	if(get_option("ms_slots_is_debug")=="1")
	{
		$c.="<span style='background-color:#FFFFFF;color#000000;font-weight:bold;font-size:12px;'>(ms_slot$which)</span> ";
		$c.="<span style='background-color:#FFFFFF;color#000000;font-weight:bold;font-size:12px;'>".stripslashes(get_option("ms_slot_remarks".$which))."</span>";
	}
	$c.=stripslashes(get_option("ms_slot".$which));
	$c.="<!-- ending of ms_slot$which -->";
	return $c;
}
function ms_slot_get_box($i,$det)
{
	ms_slot_up("ms_slot$i");
	ms_slot_up("ms_slot_remarks$i");
	return 'ms_slot'.$i.' : '.$det.' <br /><textarea name="ms_slot'.$i.'" cols="80" rows="10">'.stripslashes(get_option("ms_slot$i")).'</textarea><br />Remarks : <input name="ms_slot_remarks'.$i.'" size="85" type="text" value="'.stripslashes(get_option("ms_slot_remarks$i")).'" /><br /><br /><br />';
}
///////////////////////////////////////////////////////////
add_action('admin_menu', 'ms_slot_content');
function ms_slot_content() 
{
  add_options_page('MS Slots', 'MS Slots', 8, 'ms_content', 'ms_slot_content_options');
}
///////////////////////////////////////////////////////////
function ms_slot_content_options() 
{
	ms_slot_up("ms_slots_is_debug");
	ms_total_slots();
	echo '<div class="wrap"><br /><br />';
	echo '<b>Add custom HTML, Javascripts and Text in your template</b><br /><br />';
	echo '<form name="oscimp_form_boxes" method="post" action="'.str_replace( '%7E', '~', $_SERVER['REQUEST_URI']).'" >';
	echo 'No. of Slots : <input type="text" size="10" name="ms_total_slots" value="'.get_option("ms_total_slots").'"><span class="submit"><input type="submit" name="Submit" value="Show" /> (maximum 100 slots)</span></form><br /><br />';
	
	echo '<form name="oscimp_form" method="post" action="'.str_replace( '%7E', '~', $_SERVER['REQUEST_URI']).'" >';
	
	for($i=1;$i<=get_option("ms_total_slots");$i++)
	{
		echo ms_slot_get_box($i,'');
	}
	
	if(get_option("ms_slots_is_debug")=="1"){ $checked="checked='checked'"; }
	
	echo '<input name="ms_slots_is_debug" type="checkbox" value="1" '.$checked.' /> Debug Mode (it will show slot name in slot)<br />';
	echo '<input type="hidden" name="ms_slot_form" value="1"><p class="submit"><input type="submit" name="Submit" value="Update" /></p></form>';
	
	echo '<br /><b>usage:</b><br /><br />';
	echo '1. you can add a single slot anywhere in your template, e.g.<br />';
	echo '<div style="color:#FF0000"><br />&lt;?php if(function_exists("ms_slots")){ echo ms_slots("1"); } ?&gt;</div><br /><br />';

	echo '2. you can also add random slots, e.g.<br />';
	echo '<div style="color:#FF0000"><br />&lt;?php if(function_exists("ms_random_slots")){ echo ms_random_slots(); } ?&gt;</div><br /><br />';
	echo '3. to exclude slots, use';
	echo '<div style="color:#FF0000"><br />&lt;?php if(function_exists("ms_random_slots")){ echo ms_random_slots("1,2"); } ?&gt;</div><br />';


	echo '<br />for more detail visit <a href="http://shahidmau.blogspot.com" target="_blank">http://shahidmau.blogspot.com</a><br /><br />';
}
///////////////////////////////////////////////////////////
?>
