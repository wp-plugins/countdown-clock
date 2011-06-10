<?php
/*
Plugin Name: Countdown Clock
Description: Display a flash countdown clock on your sidebar, with text and event date of your choosing. Choose from several clock designs, colors, sizes, background pictures and animations.
Author: enclick
Version: 1.4
Author URI: http://mycountdown.org
Plugin URI: http://mycountdown.org/wordpress-countdown-clock-plugin/
*/

/**
 * Add function to widgets_init that'll load our widget.
 */

add_action( 'widgets_init', 'load_countdown_clock' );

/**
 * Register our widget.
 * 'countdown-clock' is the widget class used below.
 *
 */
function load_countdown_clock() {
        register_widget( 'countdown_clock' );
}


/*******************************************************************************************
*
*	Countdown Clock  class.
* 	This class handles everything that needs to be handled with the widget:
* 	the settings, form, display, and update. 
*
*********************************************************************************************/
class countdown_clock extends WP_Widget 
{

      /*******************************************************************************************
      *
      *
      * Widget setup.
      *
      *
      ********************************************************************************************/
      function countdown_clock() {
                #Widget settings
                $widget_ops = array( 'description' => __('A widget that displays a countdown clock', 'countdown_clock') );

                #Widget control settings
                $control_ops = array( 'width' => 200, 'height' => 550, 'id_base' => 'countdown_clock' );

                #Create the widget
                $this->WP_Widget( 'countdown_clock', __('Countdown Clock', 'countdown_clock'), $widget_ops, $control_ops );
        }


    	/*******************************************************************************************
	*    	
	*
        * Update the widget settings.
	*
	*
        *******************************************************************************************/
        function update( $new_instance, $old_instance ) 
	{
		$instance = $old_instance;

              $instance['clock-title'] = strip_tags(stripslashes($new_instance['clock-title']));
              $instance['titleflag'] = strip_tags(stripslashes($new_instance['titleflag']));
              $instance['transparentflag'] = strip_tags(stripslashes($new_instance['transparentflag']));
              $instance['group'] = strip_tags(stripslashes($new_instance['group']));
              $instance['countdown'] = strip_tags(stripslashes($new_instance['countdown']));
              $instance['text1'] = strip_tags(stripslashes($new_instance['text1']));
              $instance['text2'] = strip_tags(stripslashes($new_instance['text2']));
              $instance['background'] = strip_tags(stripslashes($new_instance['background']));
              $instance['event_day'] = strip_tags(stripslashes($new_instance['event_day']));
              $instance['event_month'] = strip_tags(stripslashes($new_instance['event_month']));
              $instance['event_year'] = strip_tags(stripslashes($new_instance['event_year']));
              $instance['size'] = strip_tags(stripslashes($new_instance['size']));
              $instance['typeflag'] = strip_tags(stripslashes($new_instance['typeflag']));
              $instance['text_color'] = strip_tags(stripslashes($new_instance['text_color']));
              $instance['background_color'] = strip_tags(stripslashes($new_instance['background_color']));
              $instance['timezone'] = strip_tags(stripslashes($new_instance['timezone']));
              $instance['time_of_day'] = strip_tags(stripslashes($new_instance['time_of_day']));

              $instance['options_flag'] = 1;
	      $instance['new_group_flag'] = 0;	      				
	      $instance['new_countdown_flag'] = 0;	      				

	      if($instance['countdown'] != $old_instance['countdown'])
              		$instance['new_countdown_flag'] = 1;	      				

	      if($instance['group'] != $old_instance['group']){
              		$instance['new_group_flag'] = 1;	     
			$instance['new_countdown_flag'] = 1;		
	      }

              return $instance;
        }


    	 /*******************************************************************************************
	 *
         *	Displays the widget settings controls on the widget panel.
         * 	Make use of the get_field_id() and get_field_name() function
         * 	when creating your form elements. This handles the confusing stuff.
	 *
	 *
     	 ********************************************************************************************/
        function form( $instance ) 
	{
		#
                #	Set up some default widget settings
		#

		$defaults = array(
	     	'clock-title'=>'Birthday Countdown',
           	'transparentflag'=>'0', 
           	'group' => 'Special Day',
           	'countdown' => 'Birthday',
           	'text1' => 'My Birthday',
           	'text2' => 'Happy Birthday!',
           	'background' => '-4',
           	'event_day' => '12',
           	'event_month' => '3',
           	'event_year' => '2011',
           	'size' => '150',
           	'typeflag' => '3015',
           	'text_color' => '#000000',
           	'border_color' => '#963939',
           	'background_color' => '#FFFFFF',
           	'timezone' => 'GMT',
           	'options_flag' => '1',
           	'new_group_flag' => '0',
           	'new_countdown_flag' => '0',
           	'timezone' => 'GMT',
           	'time_of_day' => '0'
		);


		if(!isset($instance['group']))
			$instance = $defaults;


		#
		#	PREDEFINED CONSTANTS
		#

		$file_location = dirname(__FILE__)."/group_list.ser"; 
		if ($fd = fopen($file_location,'r')){
		   $group_list_ser = fread($fd,filesize($file_location));
		   fclose($fd);
		}
		$group_list = array();
		$group_list = unserialize($group_list_ser);


		$file_location = dirname(__FILE__)."/countdown_list.ser"; 
		if ($fd = fopen($file_location,'r')){
		   $countdown_list_ser = fread($fd,filesize($file_location));
		   fclose($fd);
		}
		$countdown_list = unserialize($countdown_list_ser);

		##extract( $instance, EXTR_SKIP );

      		// Extract value from $instance

      		$title = htmlspecialchars($instance['clock-title'], ENT_QUOTES);
		$titleflag = htmlspecialchars($instance['titleflag'], ENT_QUOTES);
		$transparent_flag = htmlspecialchars($instance['transparentflag'], ENT_QUOTES);
		$group = htmlspecialchars($instance['group'], ENT_QUOTES);
		$countdown = htmlspecialchars($instance['countdown'], ENT_QUOTES);
		$text1 = htmlspecialchars($instance['text1'], ENT_QUOTES);
		$text2 = htmlspecialchars($instance['text2'], ENT_QUOTES);
		$background = htmlspecialchars($instance['background'], ENT_QUOTES);
		$event_day = htmlspecialchars($instance['event_day'], ENT_QUOTES);
		$event_month = htmlspecialchars($instance['event_month'], ENT_QUOTES);
		$event_year = htmlspecialchars($instance['event_year'], ENT_QUOTES);
		$size = htmlspecialchars($instance['size'], ENT_QUOTES);
		$typeflag = htmlspecialchars($instance['typeflag'], ENT_QUOTES);
		$text_color = htmlspecialchars($instance['text_color'], ENT_QUOTES);
		$border_color = htmlspecialchars($instance['border_color'], ENT_QUOTES);
		$background_color = htmlspecialchars($instance['background_color'], ENT_QUOTES);
		$timezone = htmlspecialchars($instance['timezone'], ENT_QUOTES);
		$time_of_day = htmlspecialchars($instance['time_of_day'], ENT_QUOTES);
		$new_countdown_flag = htmlspecialchars($instance['new_countdown_flag'], ENT_QUOTES);
		$new_group_flag = htmlspecialchars($instance['new_group_flag'], ENT_QUOTES);

		#
		#
		#		START FORM OUTPUT
		#
		#

		echo '<div style="align:center;text-align:center;margin-bottom:10px">';
		echo 'Countdown Clock<br> by <a href="http://mycountdown.org">mycountdown.org</a></div>';

       		// Get group
		echo "\n";
       		echo '<p><label for="' .$this->get_field_id( 'group' ). '">Type of Event <span style="display:inline;font-size:9px"> (Save after selection)</span>'.
               '<select id="' .$this->get_field_id( 'group' ). '" name="' .$this->get_field_name( 'group' ). '" style="width:100%">';
     	       cdc_print_thegroup_list($group, $group_list);
      	       echo '</select></label></p>';


       	       // Get countdown
               if($instance['new_group_flag'] == 1 ){
			$countdown_array = array_keys($countdown_list[$group]);
			$countdown = trim($countdown_array[0]);
		}

       	       echo '<p><label for="' .$this->get_field_id( 'countdown' ). '">List of Events <span style="display:inline;font-size:9px"> (Save after selection)</span>'.
               '<select id="' .$this->get_field_id( 'countdown' ). '" name="' .$this->get_field_name( 'countdown' ). '" style="width:100%">';
     	       cdc_print_thecountdown_list($group,$countdown, $countdown_list);
      	       echo '</select></label></p>';


	       if(empty($text1) || $new_countdown_flag == 1)
			$text1 = $countdown;

		if(empty($text2) ||  $new_countdown_flag == 1)
        		 $text2 = "Happy " . $countdown;

		// Event Name 
      		echo '<p><label for="' .$this->get_field_id( 'text1' ). '">'.'Event<br>';
        	echo '<input id="' .$this->get_field_id( 'text1' ). '" type="text" name="' .$this->get_field_name( 'text1' ). '" 
		     	  style="width: 220px; font-size:13px;align:right;" value="'. $text1 .'">';
      		echo '</label></p>';


      		// Event Message
      		echo '<p><label for="' .$this->get_field_id( 'text2' ). '">'.'Event Message<br>';
        	echo '<input id="' .$this->get_field_id( 'text2' ). '" type="text" name="' .$this->get_field_name( 'text2' ). '" 
		     	  style="width: 220px; font-size:13px;align:right;" value="'. $text2 .'">';
      		echo '</label></p>';


		if($group == "Special Day" || $group == "My Countdown" || $group == "Event")
		{
			// Set Event Date
			echo "\n";
			echo '<p><label for="' .$this->get_field_id( 'date' ). '">Event Date<br>';

        		echo '<select id="' .$this->get_field_id( 'event_day' ). '" name="' .$this->get_field_name( 'event_day' ). '"  style="width:45px">';
      			cdc_print_thedays_list($event_day);
      			echo '</select> &nbsp;'; 

        		echo '<select id="' .$this->get_field_id( 'event_month' ). '" name="' .$this->get_field_name( 'event_month' ). '"  style="width:80px">';
      			cdc_print_themonth_list($event_month);
      			echo '</select>&nbsp;';

        		echo '<select id="' .$this->get_field_id( 'event_year' ). '" name="' .$this->get_field_name( 'event_year' ). '"  style="width:60px">';
			if(empty($event_year))	$event_year = date('Y',time());
      			cdc_print_theyear_list($event_year);
      			echo '</select>';

			echo '</label></p>';
		}


      		// Set clock type
      		echo '<p><label for="' .$this->get_field_id( 'typeflag' ). '">'.'Clock Type:&nbsp;';
       		echo '<select id="' .$this->get_field_id( 'typeflag' ). '" name="' .$this->get_field_name( 'typeflag' ). '"  style="width:145px" >';
      		cdc_print_type_list($typeflag);
      		echo '</select></label></p>';

       		// Get background

 		if ($typeflag > "3011" )
        	{
			$inm_background_files = $countdown_list[$group][$countdown]['nm_background_files'];
			echo '<p><label for="' .$this->get_field_id( 'background' ). '">Background :';
			echo '<select id="' .$this->get_field_id( 'background' ). '" name="' .$this->get_field_name( 'background' ). '" style="width:60%">';
		  	if($background == 1) $check_selected = " SELECTED ";
                	echo '<option value="1" '. $check_selected .'>default</option>';

                	$inmj=1;
              		if  ($typeflag < "3014")
              	  	while ($inmj <= $inm_background_files){
                              $jnmj = $inmj+2;
			      $check_selected = "";
			      if($jnmj == $background) $check_selected = " SELECTED ";
                           	   echo "\n<option value=\"$jnmj\" $check_selected >Picture $inmj</option>";
                              $inmj++;
                 	}

		  	$flash_files = array( -1 => "stars", -2 => "balloons", -3 => "hearts", -4 => "confetti", -5=>"star trails", -6 => "sun-rays");
		  	foreach ($flash_files as $kki => $vvi)
		  	{		
	  			$check_selected = "";
			 	if($kki == $background) $check_selected = " SELECTED ";
			 	echo "\n";
                  		echo '<option value="'.$kki.'" '.$check_selected.'>' .$vvi.'</option>';
		  	}

      		  	echo '</select></label></p>';
        	}



      		// Set Clock size
		echo "\n";
      		echo '<p><label for="' .$this->get_field_id( 'size' ). '">'.'Clock Size: &nbsp;'.
         	     '<select id="' .$this->get_field_id( 'size' ). '" name="' .$this->get_field_name( 'size' ). '"  style="width:75px">';
      		cdc_print_thesize_list($size);
      		echo '</select></label></p>';


      		// Set Text Clock color
      		echo '<p><label for="' .$this->get_field_id( 'text_color' ). '">'.'Text Color: &nbsp;';
       		echo '<select id="' .$this->get_field_id( 'text_color' ). '" name="' .$this->get_field_name( 'text_color' ). '"  style="width:75px" >';
      		cdc_print_textcolor_list($text_color);
      		echo '</select></label></p>';

      		// Set Background Clock color
      		echo '<p><label for="' .$this->get_field_id( 'background_color' ). '">'.'Background Color:&nbsp;';
       		echo '<select id="' .$this->get_field_id( 'background_color' ). '" name="' .$this->get_field_name( 'background_color' ). '"  style="width:75px" >';
      		cdc_print_backgroundcolor_list($background_color);
      		echo '</select></label></p>';

      		// Set TIMEZONE
      		echo '<p><label for="' .$this->get_field_id( 'timezone' ). '">'.'Timezone:&nbsp;';
       		echo '<select id="' .$this->get_field_id( 'timezone' ). '" name="' .$this->get_field_name( 'timezone' ). '"  style="width:auto;overflow:hidden" >';
      		cdc_print_timezone($timezone);
      		echo '</select></label></p>';


		if($group == "Special Day" || $group == "My Countdown" || $group == "Event")
		{
			// Set TIME OF DAY
      			echo '<p><label for="' .$this->get_field_id( 'time_of_day' ). '">'.'Time of Day:&nbsp;';
       			echo '<select id="' .$this->get_field_id( 'time_of_day' ). '" name="' .$this->get_field_name( 'time_of_day' ). '"  style="width:80px" >';
      			cdc_print_time_of_day($time_of_day);
      			echo '</select></label></p>';
		}


		//   Transparent option

		$transparent_checked = "";
		if ($transparent_flag =="1")
	   	   $transparent_checked = "CHECKED";

		echo "\n";
        	 echo '<p><label for="' .$this->get_field_id( 'transparentflag' ). '"> Transparent: 
		<input type="checkbox" id="' .$this->get_field_id( 'transparentflag' ). '" name="' .$this->get_field_name( 'transparentflag' ). '" value=1 '.$transparent_checked.' /> 
		</label></p>';


	     //	Title header option	
	     if($countdown)
			$title = UCWords($countdown) . " Countdown";
	     elseif($group)
			$title = $group . " Countdown";

             echo '<p><label for="' .$this->get_field_id( 'clock-title' ). '">';
	     echo '<input type="hidden" id="' .$this->get_field_id( 'clock-title' ). '" name="' .$this->get_field_name( 'clock-title' ). '" value="'.$title.'" /> </label></p>';


	     $title_checked = "";
	     if ($titleflag =="1")
	     	$title_checked = "CHECKED";

	     echo "\n";
             echo '<p><label for="' .$this->get_field_id( 'titleflag' ). '"> Countdown Title: 
	     <input type="checkbox" id="' .$this->get_field_id( 'titleflag' ). '" name="' .$this->get_field_name( 'titleflag' ). '" value=1 '.$title_checked.' /> 
	     </label></p>';


	}


    /////////////////////////////////////////////////////////////////////////////////////////////////////
    //
    //	OUTPUT CLOCK WIDGET
    //
    /////////////////////////////////////////////////////////////////////////////////////////////////////
     function widget( $args, $instance ) {

	// Get values 
      	extract($args);

        $title = apply_filters('widget_title', $instance['clock-title'] );
      	$titleflag = htmlspecialchars($instance['titleflag'], ENT_QUOTES);
      	$transparentflag = htmlspecialchars($instance['transparentflag'], ENT_QUOTES);
      	$group = htmlspecialchars($instance['group'], ENT_QUOTES);
      	$countdown = htmlspecialchars($instance['countdown'], ENT_QUOTES);
      	$text1 = htmlspecialchars($instance['text1'], ENT_QUOTES);
      	$text2 = htmlspecialchars($instance['text2'], ENT_QUOTES);
      	$background = htmlspecialchars($instance['background'], ENT_QUOTES);
      	$event_day = htmlspecialchars($instance['event_day'], ENT_QUOTES);
      	$event_month = htmlspecialchars($instance['event_month'], ENT_QUOTES);
      	$event_year = htmlspecialchars($instance['event_year'], ENT_QUOTES);
      	$size = htmlspecialchars($instance['size'], ENT_QUOTES);
      	$type = htmlspecialchars($instance['type'], ENT_QUOTES);
      	$typeflag = htmlspecialchars($instance['typeflag'], ENT_QUOTES);
      	$text_color = htmlspecialchars($instance['text_color'], ENT_QUOTES);
      	$border_color = htmlspecialchars($instance['border_color'], ENT_QUOTES);
      	$background_color = htmlspecialchars($instance['background_color'], ENT_QUOTES);
      	$timezone = htmlspecialchars($instance['timezone'], ENT_QUOTES);
      	$time_of_day = htmlspecialchars($instance['time_of_day'], ENT_QUOTES);

	$new_countdown_date = $event_year ."-" . $event_month . "-" . $event_day;

	if(empty($event_day) || empty($event_month) || empty($event_year) )
		$event_time = date('U',time()+3600*24*300);
	else{
		$dateTimeZoneUTC = new DateTimeZone("UTC");
        	$new_dateTimeUTC = new DateTime($new_countdown_date, $dateTimeZoneUTC);
 		$event_time =   $new_dateTimeUTC->format('U') ;
	}

	if(isset($time_of_day))
		$event_time = $event_time + 3600 * $time_of_day;



	echo $before_widget; 




	// Output title
	echo "\n" . $before_title . $title . $after_title; 


	// Output Clock

	$target_url= "http://mycountdown.org/$group/";
	$target_url .= $countdown ."/";
	$target_url = str_replace(" ", "_", $target_url);

	$group = str_replace(" ", "+", $group);
	$countdown= str_replace(" ", "+", $countdown);
	$group_code = strtolower($group);

	$widget_call_string = 'http://mycountdown.org/wp_countdown-clock.php?group='.$group_code;
	$widget_call_string .= '&countdown='.$countdown;
	$widget_call_string .= '&widget_number='.$typeflag;
	$widget_call_string .= '&text1='.$text1;
	$widget_call_string .= '&text2='.$text2;

	if(empty($timezone))
		$timezone= "UTC";

	$widget_call_string .= '&timezone='.$timezone;

	$lgroup = strtolower($group);
	if($lgroup == "special+day" || $lgroup == "my+countdown" || $lgroup == "event")
		  $widget_call_string .= '&event_time='.$event_time;

	$widget_call_string .= '&img='.$background;

	#	IMG

	$transparent_string = "&hbg=0";
	if($transparentflag == 1){
     	     $transparent_string = "&hbg=1";
     	     $background_color="";
	}



	if($titleflag != 1){
	      $noscript_start = "<noscript>";
	      $noscript_end = "</noscript>";
	}


	echo '<!--Countdown Clock widget - HTML code - mycountdown.org -->';
	echo  "\n <div align=\"center\" style=\"margin:15px 0px 0px 0px\">\n";

	echo $noscript_start . "\n" . '<div align="center" style="width:140px;border:1px solid #ccc;background:'.$background_color.' ;color:'.$text_color.' ;font-weight:bold">';
	echo '<a style="padding:2px 1px;margin:2px 1px;font-size:12px;line-height:16px;font-family:arial;text-decoration:none;color:'.$text_color. ' ;" href="'.$target_url.'">';
	echo $title.'</a></div>' . "\n" .  $noscript_end ."\n";

	$text_color = str_replace("#","",$text_color);
	$background_color = str_replace("#","",$background_color);
	$border_color = str_replace("#","",$border_color);

	$widget_call_string .= '&cp3_Hex='.$border_color.'&cp2_Hex='.$background_color.'&cp1_Hex='.$text_color. $transparent_string . $ampm_string. '&fwdt='.$size;


	echo '<script type="text/javascript" src="'.$widget_call_string . '"></script>';
	echo "\n</div><!-end of code-->";



	echo $after_widget;


    }

}


#add_action('plugins_loaded', 'countdown_clock_init');


// This function print for selector clock color list
require_once("functions.php");


?>