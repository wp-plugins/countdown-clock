<?php
/*
Plugin Name: Countdown Clock
Description: Display a flash countdown clock on your sidebar set with text and event of your choosing. Choice of clock designs, colors, sizes, background pictures and animations.
Author: enclick
Version: 1.1
Author URI: http://mycountdown.org
Plugin URI: http://mycountdown.org/wordpress-countdown-clock-plugin/
*/



function countdown_clock_init() 
{

     if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )
    	   return; 

    function countdown_clock_control() 
    {

        $newoptions = get_option('countdown_clock');
    	$options = $newoptions;
	$options_flag=0;

	//	$group_list[$group_name]['group_code']

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

    	if ( empty($newoptions) )
	{
	   $options_flag=1;
      	   $newoptions = array(
	   	'title'=>'Birthday Countdown',
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
           	'timezone' => 'GMT'
	   );
	}

	if ( $_POST['countdown-clock-submit'] ) {

	     $options_flag=1;
              $newoptions['title'] = strip_tags(stripslashes($_POST['countdown-clock-title']));
              $newoptions['titleflag'] = strip_tags(stripslashes($_POST['countdown-clock-titleflag']));
              $newoptions['transparentflag'] = strip_tags(stripslashes($_POST['countdown-clock-transparentflag']));
              $newoptions['group'] = strip_tags(stripslashes($_POST['countdown-clock-group']));
              $newoptions['countdown'] = strip_tags(stripslashes($_POST['countdown-clock-countdown']));
              $newoptions['text1'] = strip_tags(stripslashes($_POST['countdown-clock-text1']));
              $newoptions['text2'] = strip_tags(stripslashes($_POST['countdown-clock-text2']));
              $newoptions['background'] = strip_tags(stripslashes($_POST['countdown-clock-background']));
              $newoptions['event_day'] = strip_tags(stripslashes($_POST['countdown-clock-event-day']));
              $newoptions['event_month'] = strip_tags(stripslashes($_POST['countdown-clock-event-month']));
              $newoptions['event_year'] = strip_tags(stripslashes($_POST['countdown-clock-event-year']));
              $newoptions['size'] = strip_tags(stripslashes($_POST['countdown-clock-size']));
              $newoptions['type'] = strip_tags(stripslashes($_POST['countdown-clock-type']));
              $newoptions['typeflag'] = strip_tags(stripslashes($_POST['countdown-clock-typeflag']));
              $newoptions['text_color'] = strip_tags(stripslashes($_POST['countdown-clock-text-color']));
              $newoptions['border_color'] = strip_tags(stripslashes($_POST['countdown-clock-border-color']));
              $newoptions['background_color'] = strip_tags(stripslashes($_POST['countdown-clock-background-color']));
              $newoptions['timezone'] = strip_tags(stripslashes($_POST['countdown-clock-timezone']));
        }


      	if ( $options_flag ==1 ) {
              $options = $newoptions;
              update_option('countdown_clock', $options);
      	}


      	// Extract value from vars
      	$title = htmlspecialchars($options['title'], ENT_QUOTES);
      	$titleflag = htmlspecialchars($options['titleflag'], ENT_QUOTES);
      	$transparent_flag = htmlspecialchars($options['transparentflag'], ENT_QUOTES);
      	$group = htmlspecialchars($options['group'], ENT_QUOTES);
      	$countdown = htmlspecialchars($options['countdown'], ENT_QUOTES);
      	$text1 = htmlspecialchars($options['text1'], ENT_QUOTES);
      	$text2 = htmlspecialchars($options['text2'], ENT_QUOTES);
      	$background = htmlspecialchars($options['background'], ENT_QUOTES);
      	$event_day = htmlspecialchars($options['event_day'], ENT_QUOTES);
      	$event_month = htmlspecialchars($options['event_month'], ENT_QUOTES);
      	$event_year = htmlspecialchars($options['event_year'], ENT_QUOTES);
      	$size = htmlspecialchars($options['size'], ENT_QUOTES);
      	$typeflag = htmlspecialchars($options['typeflag'], ENT_QUOTES);
      	$text_color = htmlspecialchars($options['text_color'], ENT_QUOTES);
      	$border_color = htmlspecialchars($options['border_color'], ENT_QUOTES);
      	$background_color = htmlspecialchars($options['background_color'], ENT_QUOTES);
      	$timezone = htmlspecialchars($options['timezone'], ENT_QUOTES);

      	echo '<ul><li style="text-align:center;list-style: none;"><label for="clock-title">Countdown Clock<br> by <a href="http://mycountdown.org">mycountdown.org</a></label></li>';

       	// Get group

       	echo '<li style="list-style: none;align:center;text-align:center"><label for="countdown-clock-group">Event Type <div style="display:inline;font-size:8px">(Save settings after selecting)</div>'.
               '<select id="countdown-clock-group" name="countdown-clock-group" style="width:100%">';
     	cdc_print_thegroup_list($group, $group_list);
      	echo '</select></label></li>';


       	// Get countdown

       	echo '<li style="list-style: none;align:center;text-align:center"><label for="countdown-clock-countdown">Countdown <div style="display:inline;font-size:8px">(Save settings after selecting)</div>'.
               '<select id="countdown-clock-countdown" name="countdown-clock-countdown" style="width:100%">';
     	cdc_print_thecountdown_list($group,$countdown, $countdown_list);
      	echo '</select></label></li>';

	if(empty($text1))
		$text1 = $countdown;
	
	if(empty($text2))
		$text2 = "Happy " . $countdown;

	// Event Name 
      	echo '<li style="list-style: none;align:center;text-align:center"><label for="countdown-clock-text1">'.'Event<br>';
        echo '<input id="countdown-clock-text1" type="text" name="countdown-clock-text1" style="width: 220px; font-size:13px;align:right;" value="'. $text1 .'">';
      	echo '</label>';
      	echo '</li>';

      	// Event Message
      	echo '<li style="list-style: none;align:center;text-align:center;margin:0px 0px 10px 0px"><label for="countdown-clock-text2">'.'Event Message<br>';
        echo '<input id="countdown-clock-text2" type="text" name="countdown-clock-text2" style="width: 220px; font-size:13px;align:right;" value="'. $text2 .'">';
      	echo '</label>';
      	echo '</li>';

	if($group == "Special Day" || $group == "My Countdown" || $group == "Event")
	{
		// Set Event Date
		echo "\n";
		echo '<li style="list-style: none;text-align:bottom;align:center;text-align:center;margin:0px 0px 20px 0px">';
		echo '<label for="countdown-clock-date">Event Date<br>';

        	echo '<select id="countdown-clock-event-day" name="countdown-clock-event-day"  style="width:45px">';
      		cdc_print_thedays_list($event_day);
      		echo '</select> &nbsp;'; 

        	echo '<select id="countdown-clock-event-month" name="countdown-clock-event-month"  style="width:80px">';
      		cdc_print_themonth_list($event_month);
      		echo '</select>&nbsp;';

        	echo '<select id="countdown-clock-event-year" name="countdown-clock-event-year"  style="width:60px">';
      		cdc_print_theyear_list($event_year);
      		echo '</select>';

		echo '</label></li>';
	}


      	// Set clock type
      	echo '<li style="list-style: none;"><label for="countdown-clock-typeflag">'.'Clock Type:&nbsp;';
       	echo '<select id="countdown-clock-typeflag" name="countdown-clock-typeflag"  style="width:145px" >';
      	cdc_print_type_list($typeflag);
      	echo '</select></label>';
      	echo '</li>';


       	// Get background

 	if ($typeflag > "3011" )
        {
		$inm_background_files = $countdown_list[$group][$countdown]['nm_background_files'];
		echo '<li style="list-style: none;"><label for="countdown-clock-background">Background :';
		echo '<select id="countdown-clock-background" name="countdown-clock-background" style="width:60%">';
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
                  	echo '<option value="'.$kki.'" '.$check_selected.'">' .$vvi.'</option>';
		  }

      		  echo '</select></label></li>';
        }



      	// Set Clock size
	echo "\n";
      	echo '<li style="list-style: none;text-align:bottom"><label for="countdown-clock-size">'.'Clock Size: &nbsp;'.
         '<select id="countdown-clock-size" name="countdown-clock-size"  style="width:75px">';
      	cdc_print_thesize_list($size);
      	echo '</select></label></li>';


      	// Set Text Clock color
      	echo '<li style="list-style: none;"><label for="countdown-clock-text-color">'.'Text Color: &nbsp;';
       	echo '<select id="countdown-clock-text-color" name="countdown-clock-text-color"  style="width:75px" >';
      	cdc_print_textcolor_list($text_color);
      	echo '</select></label>';
      	echo '</li>';


      	// Set Background Clock color
      	echo '<li style="list-style: none;"><label for="countdown-clock-background-color">'.'Background Color:&nbsp;';
       	echo '<select id="countdown-clock-background-color" name="countdown-clock-background-color"  style="width:75px" >';
      	cdc_print_backgroundcolor_list($background_color);
      	echo '</select></label>';
      	echo '</li>';

      	// Set TIMEZONE
      	echo '<li style="list-style: none;"><label for="countdown-clock-timezone">'.'Timezone:&nbsp;';
       	echo '<select id="countdown-clock-timezone" name="countdown-clock-timezone"  style="width:150px" >';
      	cdc_print_timezone($timezone);
      	echo '</select></label>';
      	echo '</li>';


	//   Transparent option

	$transparent_checked = "";
	if ($transparent_flag =="1")
	   $transparent_checked = "CHECKED";

	echo "\n";
        echo '<li style="list-style: none;"><label for="countdown-clock-transparentflag"> Transparent: 
	<input type="checkbox" id="countdown-clock-transparentflag" name="countdown-clock-transparentflag" value=1 '.$transparent_checked.' /> 
	</label></li>';

      	// Hidden "OK" button
      	echo '<label for="countdown-clock-submit">';
      	echo '<input id="countdown-clock-submit" name="countdown-clock-submit" type="hidden" value="Ok" />';
      	echo '</label>';


	//	Title header option	
	if($countdown)
		$title = UCWords($countdown) . " Countdown";
	elseif($countdown_name)
		$title = $countdown_name . " Countdown";
	elseif($group_name)
		$title = $group_name . " Countdown";

        echo '<label for="countdown-clock-title"> <input type="hidden" id="countdown-clock-title" name="countdown-clock-title" value="'.$title.'" /> </label>';



	$title_checked = "";
	if ($titleflag =="1")
	   $title_checked = "CHECKED";

	echo "\n";
        echo '<li style="list-style: none;"><label for="countdown-clock-titleflag"> Countdown Title: 
	<input type="checkbox" id="countdown-clock-titleflag" name="countdown-clock-titleflag" value=1 '.$title_checked.' /> 
	</label></li>';

	echo '</ul>';


    }


    /////////////////////////////////////////////////////////////////////////////////////////////////////
    //
    //	OUTPUT CLOCK WIDGET
    //
    /////////////////////////////////////////////////////////////////////////////////////////////////////

     function countdown_clock($args) 
     {

	// Get values 
      	extract($args);

      	$options = get_option('countdown_clock');

	// Get Title,Location,Size,

      	$title = htmlspecialchars($options['title'], ENT_QUOTES);
      	$titleflag = htmlspecialchars($options['titleflag'], ENT_QUOTES);
      	$transparentflag = htmlspecialchars($options['transparentflag'], ENT_QUOTES);
      	$group = htmlspecialchars($options['group'], ENT_QUOTES);
      	$countdown = htmlspecialchars($options['countdown'], ENT_QUOTES);
      	$text1 = htmlspecialchars($options['text1'], ENT_QUOTES);
      	$text2 = htmlspecialchars($options['text2'], ENT_QUOTES);
      	$background = htmlspecialchars($options['background'], ENT_QUOTES);
      	$event_day = htmlspecialchars($options['event_day'], ENT_QUOTES);
      	$event_month = htmlspecialchars($options['event_month'], ENT_QUOTES);
      	$event_year = htmlspecialchars($options['event_year'], ENT_QUOTES);
      	$size = htmlspecialchars($options['size'], ENT_QUOTES);
      	$type = htmlspecialchars($options['type'], ENT_QUOTES);
      	$typeflag = htmlspecialchars($options['typeflag'], ENT_QUOTES);
      	$text_color = htmlspecialchars($options['text_color'], ENT_QUOTES);
      	$border_color = htmlspecialchars($options['border_color'], ENT_QUOTES);
      	$background_color = htmlspecialchars($options['background_color'], ENT_QUOTES);
      	$timezone = htmlspecialchars($options['timezone'], ENT_QUOTES);

	$new_countdown_date = $event_year ."-" . $event_month . "-" . $event_day;

	if(empty($event_day) || empty($event_month) || empty($event_year) )
		$event_time = date('U',time()+3600*24*300);
	else{
		$dateTimeZoneUTC = new DateTimeZone("UTC");
        	$new_dateTimeUTC = new DateTime($new_countdown_date, $dateTimeZoneUTC);
 		$event_time =   $new_dateTimeUTC->format('U') ;
	}


	echo $before_widget; 




	// Output title
	echo $before_title . $title . $after_title; 


	// Output Clock


	$target_url= "http://mycountdown.org/$group_name/";
	if ($countdown_name)
   	   $target_url .= $countdown_name ."/";

	$target_url .= $countdown ."/";
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


	echo'<!-Countdown Clock widget - HTML code - mycountdown.org --><div align="center" style="margin:15px 0px 0px 0px">';

	echo $noscript_start . '<div align="center" style="width:140px;border:1px solid #ccc;background:'.$background_color.' ;color:'.$text_color.' ;font-weight:bold">';
	echo '<a style="padding:2px 1px;margin:2px 1px;font-size:12px;line-height:16px;font-family:arial;text-decoration:none;color:'.$text_color. ' ;" href="'.$target_url.'">';
	echo '<img src="http://mycountdown.org/images/countries/'.$group_code.'.png" border=0 style="border:0;margin:0;padding:0">&nbsp;&nbsp;'.$title.'</a></div>' . $noscript_end;

	$text_color = str_replace("#","",$text_color);
	$background_color = str_replace("#","",$background_color);
	$border_color = str_replace("#","",$border_color);

	$widget_call_string .= '&cp3_Hex='.$border_color.'&cp2_Hex='.$background_color.'&cp1_Hex='.$text_color. $transparent_string . $ampm_string. '&fwdt='.$size;


	echo '<script type="text/javascript" src="'.$widget_call_string . '"></script></div><!-end of code-->';




	echo $after_widget;


    }
  
    register_sidebar_widget('Countdown Clock', 'countdown_clock');
    register_widget_control('Countdown Clock', 'countdown_clock_control', 245, 300);


}


add_action('plugins_loaded', 'countdown_clock_init');


// This function print for selector clock color list

include("functions.php");


?>