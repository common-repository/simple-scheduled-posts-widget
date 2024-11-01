<?php
/*
Plugin Name: Simple scheduled posts widget
Plugin URI: http://ageno.pl/wordpress/simple-scheduled-posts-widget
Description: This widget shows scheduled (future) posts in sidebar. Its really simple!
Author: Maksymilian Sleziak
Tags: scheduled, preview, plugin, post, posts, future, schenduled, upcoming, posts, upcoming post, upcoming, draft, Post, widget, sidebar, simple, ageno, maksymilian, sleziak, maxbmx 
Author URI: http://maksymilian.sleziak.com/
Version: 1.0
License: GPL2

	Check: http://ageno.pl/ - Good websites based on Wordpress and more...
	
	____________________________________________________________________________
	
	USAGE:

	Copyright (C) 2010  Maksymilian Sleziak  maksymilian.sleziak.com, ageno.pl
	
	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.	    


	SSPW - Simple Scheduled posts widget - free free free FREE, but it will be super cool if you put link to us in footer - ageno.pl
*/

function SSPW($args) 
{ 	

?>
	<style type="text/css">
	.SSPW li{position:relative; display:block; border-bottom:1px solid #eee; padding:2px 0 2px 0; overflow:hidden;}
	.SSPW li a,
	.SSPW li div{white-space: nowrap;}
	.SSPW span.date{display:block; position:absolute; top:50%; margin-top:-11px; right:0; color:#555; background:#f1f1f1; padding:0px 5px; font-size:10px; height:22px; line-height:22px;}	
	</style>
<?php
		extract($args);
		$options = get_option("SSPW");
		if (!is_array( $options ))
		{
			$options = array('title' => 'Scheduled posts','DisplayDate' => '','DisplayUrl' => '');
		}	
	echo $before_widget;
	echo $before_title.$options['title'].$after_title;
	echo '<!-- start of simple scheduled posts widget -->';
	echo '<ul> ';
	
	global $wpdb;  
	$posts = $wpdb->get_results("SELECT ID, post_title, post_title, post_date FROM $wpdb->posts WHERE post_type = 'post' AND post_status = 'future' ORDER BY post_date ASC"); //need limit?  ad LIMIT 50
		
	foreach ($posts as $post) 
	{  
	    setup_postdata($post);  
	    $id = $post->ID;  
	    $title = $post->post_title;
	    
	    if($options['DisplayDate'] == true) //!display date
	    {        
	    	$post_date = $post->post_date;
	   		$post_date = date_parse($post_date);
	    	$post_date = $post_date[year].'&ndash;'.$post_date[month].'&ndash;'.$post_date[day];
	    	$post_date = '<span class="date">'.$post_date.'</span>';
	    }
	    else
	    {
	    	$post_date = '';
	    }
	    
	    if($options['DisplayUrl'] == true)//!display url
	    {
	    	$post_url_before = '<a href="' . get_permalink($id) . '" title="' . $title . '">';
	    	$post_url_after = '</a>';
	    }
	    else
	    {
	   		$post_url_before = '<div>';
	    	$post_url_after = '</div>';
	    }
	    
	echo '<li class="post-'.$id.'">'.$post_url_before.$title.$post_date.$post_url_after.'</li> '; 		
	}

	echo '</ul>';
	echo '<!-- !end of simple scheduled posts widget, check http://ageno.pl -->';  
	echo $after_widget;
}



function SSPW_control()
{
	$options = get_option("SSPW");
	if (!is_array( $options ))
	$options = array('title' => 'Scheduled posts','DisplayDate' => '','DisplayUrl' => '');

	if ($_POST['SSPW-Submit'])
	{
		$options['title'] = htmlspecialchars($_POST['SSPW-WidgetTitle']);
		$options['DisplayDate'] = $_POST['SSPW-WidgetDate'];
		$options['DisplayUrl'] = $_POST['SSPW-WidgetUrl'];	
		update_option("SSPW", $options);
	}
?>	
	<p>
	    <label for="SSPW-WidgetTitle">Title: 
	    	<input type="text" id="SSPW-WidgetTitle" name="SSPW-WidgetTitle" value="<?php echo $options['title'];?>"/>
	    </label>
	</p>
	<p>
	    <label for="SSPW-WidgetDate">Show date:
	    	<input type="checkbox" id="SSPW-WidgetDate" name="SSPW-WidgetDate" <?php if($options['DisplayDate']==true){ echo 'checked=checked';} ?> value="true" />
		</label>
	</p>
	<p>
		<label for="SSPW-WidgetUrl">Show url:
	    	<input type="checkbox" id="SSPW-WidgetUrl" name="SSPW-WidgetUrl" <?php if($options['DisplayUrl']==true){ echo 'checked=checked';} ?> value="true" />
		</label>
    </p>
    <p>
    	<input type="hidden" id="SSPW-Submit" name="SSPW-Submit" value="1" />
	</p>
<?php
}


function SSPW_init()
{
	register_sidebar_widget('Simple scheduled posts widget', 'SSPW');
	register_widget_control(   'Simple scheduled posts widget', 'SSPW_control');
}
add_action("plugins_loaded", "SSPW_init");
?>