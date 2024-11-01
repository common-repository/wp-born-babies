<?php
/*
Plugin Name: Wp Born Babies
Plugin URI: http://www.webcodeman.com/
Description: Use this plugin to create a baby list & publish the baby list in your WordPress website. It has a built-in single page template for baby details.
Version: 1.0
Author: Jain Deen
Author URI: http://www.webcodeman.com/
Tags:Best Plugin for Born Babies, Nursery, Hospitals, Custom Post Type Plugin
License: GPLv2 or later
Copyright: 
*/

/* add shortcode */
add_shortcode( 'born-babies', 'babies_shortcode' );

/* draw a calendar */
function babies_shortcode( $atts='' ) {

	/* set defaults & extract attributes */
	
   ?>  
    <style type="text/css">
.baby_list{ width:45%; float:left; padding:5px 10px 5px 10px;}
.baby_search{ border:0px; padding:2px 2px 2px 2px; line-height:1;}
.baby_search1{width:45%; float:left; padding:5px 5px 5px 15px;}
.baby_search2{width:45%; float:left; padding:5px 5px 5px 15px; text-align:right;}
.baby_list a{text-decoration:none; color:#8d8b00;}
.baby_list a:visited{ color:#8d8b00;}
.baby_layout fieldset{border:1px solid #572700; border-radius:10px 10px 10px 10px;}
.baby_layout legend{padding-left:5px; padding-right:5px;}
.bigtext{font-size:24px; font-weight:bold; line-height:2; color: #572700;}
</style>
	
 
 <div class="baby_layout">   
 <div class="baby_search1">
       <form role="search" action="<?php echo get_permalink(); ?>" method="get" id="searchform">
    <input type="text" name="baby_name"  style="width:150px;  height:20px;" placeholder="Search Baby" value="<?php echo  $_GET['baby_name']; ?>"/>
    <input type="submit" alt="Search" value="Find" class="baby_search" />
  </form>
</div>
<div class="baby_search2">
<form action="<?php echo get_permalink(); ?>" method="get">

  <select name="mnth" style="width:100px;  height:22px; ">
    <option value="01" <?php if($_GET['mnth'] == '01'){ echo 'selected'; } ?>>January</option>
    <option value="02" <?php if($_GET['mnth'] == '02'){ echo 'selected'; } ?>>February</option>
    <option value="03" <?php if($_GET['mnth'] == '03'){ echo 'selected'; } ?>>March</option>
    <option value="04" <?php if($_GET['mnth'] == '04'){ echo 'selected'; } ?>>April</option>
    <option value="05" <?php if($_GET['mnth'] == '05'){ echo 'selected'; } ?>>May</option>
    <option value="06" <?php if($_GET['mnth'] == '06'){ echo 'selected'; } ?>>June</option>
    <option value="07" <?php if($_GET['mnth'] == '07'){ echo 'selected'; } ?>>July</option>
    <option value="08" <?php if($_GET['mnth'] == '08'){ echo 'selected'; } ?>>August</option>
    <option value="09" <?php if($_GET['mnth'] == '09'){ echo 'selected'; } ?>>September</option>
    <option value="10" <?php if($_GET['mnth'] == '10'){ echo 'selected'; } ?>>October</option>
    <option value="11" <?php if($_GET['mnth'] == '11'){ echo 'selected'; } ?>>November</option>
    <option value="12" <?php if($_GET['mnth'] == '12'){ echo 'selected'; } ?>>December</option>
  </select>
  <select name="yr" style="width:60px; height:22px; ">
		<option value="2014" <?php if($_GET['yr'] == '2014'){ echo 'selected'; } ?>>2014</option> 
		<option value="2013" <?php if($_GET['yr'] == '2013'){ echo 'selected'; } ?>>2013</option> 
		<option value="2012" <?php if($_GET['yr'] == '2012'){ echo 'selected'; } ?>>2012</option> 
  </select>
  <input value="Find" type="submit" class="baby_search">

</form>
</div>
 <div style="clear:both;"></div>
 
<?php 
 $current_year = date('Y');
 $current_month = date('m');
 
 $selected_month = $_GET['mnth'];
 $selected_year = $_GET['yr'];

if( isset($selected_year))
{
	 $year = $selected_year;
	 $month = $selected_month;
}else{
	 $year = $current_year;
	 $month = $current_month;
}

 $baby_searchname = $_GET['baby_name'];


$args = array( 
'post_type' => 'baby', 
'post_status'  => 'publish',
'posts_per_page' => -1,
 'order_by' => array('_baby_birthday'),
 'order' => 'ASC',

'meta_query' => array( // WordPress has all the results, now, return only the events after today's date
            array(
                'key' => '_baby_birthyear', 
                'value' => $year, 
                'compare' => '=', 
               // 'type' => 'NUMERIC,'
				),
			 array(
                'key' => '_baby_birthmonth', 
                'value' => $month, 
                'compare' => '=', 
               //'type' => 'NUMERIC,'
				) 

)
);


///////////////// Block for Search Baby Name //////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
add_filter( 'posts_where', 'title_like_posts_where', 10, 2 );
function title_like_posts_where( $where, &$wp_query ) {
    global $wpdb;
    if ( $post_title_like = $wp_query->get( 'post_title_like' ) ) {
        $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'' . esc_sql( like_escape( $post_title_like ) ) . '%\'';
    }
    return $where;
}


$args2 = array( 
'post_type' => 'baby', 
'post_status'  => 'publish',
'posts_per_page' => -1,
 'order_by' => array('_baby_birthday'),
 'order' => 'ASC',
'post_title_like' => $baby_searchname
);

if(isset($baby_searchname))
{
	$loop = new WP_Query( $args2 );
}else{
	$loop = new WP_Query( $args );
}
?>

<?php

$monthName = date("F", mktime(0, 0, 0, $month, 10));
//echo $monthName; //output: May
?>
 <fieldset>
	<legend> <div class="bigtext"><?php echo strtoupper($monthName); ?> <?php echo $year; ?></div></legend>
 

<?php
while ( $loop->have_posts() ) : $loop->the_post();
global $post;
$size = array(210,150);
$post_type = get_post_type( $post->ID ); 
?>
	<div class="baby_list">
	
	<?php $birth_date = get_post_meta($post->ID, '_baby_birthmonth', true).'/'.get_post_meta($post->ID, '_baby_birthday', true).'/'.get_post_meta($post->ID, '_baby_birthyear', true) ?>
	
	<a href="<?php echo get_permalink(); ?>"><?php echo $birth_date; ?> @ <?php echo get_post_meta($post->ID, '_baby_time', true); ?> - <?php echo get_the_title($post->ID); ?></a><br>
 
	</div>
<?php	
endwhile;
?>
</fieldset></div>
<?php  
}
/* Custom Function Code for post type baby */



add_action('init', 'baby_register');

 

function baby_register() {

 

    $labels = array(

        'name' => _x('Babies', 'post type general name'),

        'singular_name' => _x('Baby Item', 'post type singular name'),

        'add_new' => _x('Add New', 'Baby item'),

        'add_new_item' => __('Add New Baby Item'),

        'edit_item' => __('Edit Baby Item'),

        'new_item' => __('New Baby Item'),

        'view_item' => __('View Baby Item'),

        'search_items' => __('Search Babies'),

        'not_found' =>  __('Nothing found'),

        'not_found_in_trash' => __('Nothing found in Trash'),

        'parent_item_colon' => ''

    );

 

    $args_job = array(

        'labels' => $labels,

        'public' => true,

        'publicly_queryable' => true,

        'show_ui' => true,

        'query_var' => true,

        'menu_icon' => '',

        'rewrite' => true,

        'capability_type' => 'post',

        'hierarchical' => false,

        'menu_position' => null,

        'supports' => array('title','thumbnail','comments')

      );

 

    register_post_type( 'baby' , $args_job );

}

// Add Featured Box

/* Custom For Baby Name */

add_action( 'add_meta_boxes', 'add_baby_metaboxes' );
// Add the job Meta Boxes
function add_baby_metaboxes() {
   // add_meta_box('wpt_baby_baby', 'Baby Name', 'wpt_baby_baby', 'baby', 'normal', 'high');
}
/* Custom For baby Gender*/

add_action( 'add_meta_boxes', 'add_baby_metaboxes10' );
// Add the job Meta Boxes
function add_baby_metaboxes10() {
    add_meta_box('wpt_baby_baby10', 'Gender', 'wpt_baby_baby10', 'baby', 'normal', 'high');
}
// The Event job Metabox
function wpt_baby_baby10() {
    global $post;
    // Noncename needed to verify where the data originated
    echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' .
    wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
    // Get the job data if its already been entered
    $baby_babygender = get_post_meta($post->ID, '_baby_babygender', true); ?>
  
 <select name="_baby_babygender">
<option value="male" <?php if($baby_babygender == 'male') { echo 'selected'; } ?>>Male</option>
<option value="female" <?php if($baby_babygender == 'female') { echo 'selected'; } ?>>Female</option></select>
<?php }

// Save the Metabox Data
function wpt_save_babygender_meta($post_id, $post) {
    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if ( !wp_verify_nonce( $_POST['eventmeta_noncename'], plugin_basename(__FILE__) )) {
    return $post->ID;
    }
    // Is the user allowed to edit the post or page?
    if ( !current_user_can( 'edit_post', $post->ID ))
        return $post->ID;
    // OK, we're authenticated: we need to find and save the data
    // We'll put it into an array to make it easier to loop though.
    $baby_meta['_baby_babygender'] = $_POST['_baby_babygender'];
    // Add values of $baby_meta as custom fields
    foreach ($baby_meta as $key => $value) { // Cycle through the $baby_meta array!
        if( $post->post_type == 'revision' ) return; // Don't store custom data twice
        $value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
        if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
            update_post_meta($post->ID, $key, $value);
        } else { // If the custom field doesn't have a value
            add_post_meta($post->ID, $key, $value);
        }
        if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
    }
}
add_action('save_post', 'wpt_save_babygender_meta', 1, 2); // save the custom fields
/* Custom For Baby Photo url Ends */

/* Custom For Baby Name url Ends */

/* Custom For Baby date*/

add_action( 'add_meta_boxes', 'add_baby_metaboxes1' );
// Add the job Meta Boxes
function add_baby_metaboxes1() {
    add_meta_box('wpt_baby_baby1', 'Birth Date', 'wpt_baby_baby1', 'baby', 'normal', 'high');
}
// The Event job Metabox
function wpt_baby_baby1() {
    global $post;
    // Noncename needed to verify where the data originated
    echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' .
    wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
    // Get the job data if its already been entered
   // $baby_birthdate = get_post_meta($post->ID, '_baby_birthdate', true);
	
	$baby_birthday = get_post_meta($post->ID, '_baby_birthday', true);
	$baby_birthmonth = get_post_meta($post->ID, '_baby_birthmonth', true);
	$baby_birthyear = get_post_meta($post->ID, '_baby_birthyear', true);
	
    // Echo out the field
    echo 'Month <input type="text" name="_baby_birthmonth" value="' . $baby_birthmonth  . '" class="widefat" style="width: 45px; height: 25px"  />  <small>Eg: 01</small> &nbsp;&nbsp;';
	echo 'Day <input type="text" name="_baby_birthday" value="' . $baby_birthday  . '" class="widefat" style="width: 45px; height: 25px"  />  <small>Eg: 01</small> &nbsp;&nbsp;';
	echo 'Year <input type="text" name="_baby_birthyear" value="' . $baby_birthyear  . '" class="widefat" style="width: 80px; height: 25px"  /> <small>Eg: 2014</small> <br><br>';
	
	//echo 'Date of Birth <input type="text" name="_baby_birthdate" value="'. $baby_birthdate.'" class="widefat" style="width: 100px; height: 25px" readonly="" />';
}

// Save the Metabox Data
function wpt_save_babydate_meta($post_id, $post) {
    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if ( !wp_verify_nonce( $_POST['eventmeta_noncename'], plugin_basename(__FILE__) )) {
    return $post->ID;
    }
    // Is the user allowed to edit the post or page?
    if ( !current_user_can( 'edit_post', $post->ID ))
        return $post->ID;
    // OK, we're authenticated: we need to find and save the data
    // We'll put it into an array to make it easier to loop though.
   $baby_meta['_baby_birthdate'] = $_POST['_baby_birthday']. '/' . $_POST['_baby_birthmonth']. '/' . $_POST['_baby_birthyear'];
    // Add values of $baby_meta as custom fields
    foreach ($baby_meta as $key => $value) { // Cycle through the $baby_meta array!
        if( $post->post_type == 'revision' ) return; // Don't store custom data twice
        $value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
        if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
            update_post_meta($post->ID, $key, $value);
        } else { // If the custom field doesn't have a value
            add_post_meta($post->ID, $key, $value);
        }
        if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
    }
}
add_action('save_post', 'wpt_save_babydate_meta', 1, 2); // save the custom fields


/* Custom For Baby date url Ends */

/* Custom For Baby Day*/

add_action( 'add_meta_boxes', 'add_baby_metaboxes13' );
// Add the job Meta Boxes
function add_baby_metaboxes13() {
    //add_meta_box('wpt_baby_baby13', 'Day', 'wpt_baby_baby13', 'baby', 'normal', 'high');
}
// The Event job Metabox
function wpt_baby_baby13() {
    global $post;
    // Noncename needed to verify where the data originated
    echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' .
    wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
    // Get the job data if its already been entered
    $baby_birthday = get_post_meta($post->ID, '_baby_birthday', true);
    // Echo out the field
   
}

// Save the Metabox Data
function wpt_save_babyday_meta($post_id, $post) {
    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if ( !wp_verify_nonce( $_POST['eventmeta_noncename'], plugin_basename(__FILE__) )) {
    return $post->ID;
    }
    // Is the user allowed to edit the post or page?
    if ( !current_user_can( 'edit_post', $post->ID ))
        return $post->ID;
    // OK, we're authenticated: we need to find and save the data
    // We'll put it into an array to make it easier to loop though.
    $baby_meta['_baby_birthday'] = $_POST['_baby_birthday'];
    // Add values of $baby_meta as custom fields
    foreach ($baby_meta as $key => $value) { // Cycle through the $baby_meta array!
        if( $post->post_type == 'revision' ) return; // Don't store custom data twice
        $value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
        if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
            update_post_meta($post->ID, $key, $value);
        } else { // If the custom field doesn't have a value
            add_post_meta($post->ID, $key, $value);
        }
        if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
    }
}
add_action('save_post', 'wpt_save_babyday_meta', 1, 2); // save the custom fields


/* Custom For Baby Day url Ends */




/* Custom For Baby Month*/

add_action( 'add_meta_boxes', 'add_baby_metaboxes11' );
// Add the job Meta Boxes
function add_baby_metaboxes11() {
    //add_meta_box('wpt_baby_baby11', 'Birth Month', 'wpt_baby_baby11', 'baby', 'normal', 'high');
}
// The Event job Metabox
function wpt_baby_baby11() {
    global $post;
    // Noncename needed to verify where the data originated
    echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' .
    wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
    // Get the job data if its already been entered
    $baby_birthmonth = get_post_meta($post->ID, '_baby_birthmonth', true);
    // Echo out the field
}

// Save the Metabox Data
function wpt_save_babymonth_meta($post_id, $post) {
    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if ( !wp_verify_nonce( $_POST['eventmeta_noncename'], plugin_basename(__FILE__) )) {
    return $post->ID;
    }
    // Is the user allowed to edit the post or page?
    if ( !current_user_can( 'edit_post', $post->ID ))
        return $post->ID;
    // OK, we're authenticated: we need to find and save the data
    // We'll put it into an array to make it easier to loop though.
    $baby_meta['_baby_birthmonth'] = $_POST['_baby_birthmonth'];
    // Add values of $baby_meta as custom fields
    foreach ($baby_meta as $key => $value) { // Cycle through the $baby_meta array!
        if( $post->post_type == 'revision' ) return; // Don't store custom data twice
        $value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
        if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
            update_post_meta($post->ID, $key, $value);
        } else { // If the custom field doesn't have a value
            add_post_meta($post->ID, $key, $value);
        }
        if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
    }
}
add_action('save_post', 'wpt_save_babymonth_meta', 1, 2); // save the custom fields


/* Custom For Baby Month url Ends */


/* Custom For Baby Year*/

add_action( 'add_meta_boxes', 'add_baby_metaboxes12' );
// Add the job Meta Boxes
function add_baby_metaboxes12() {
    //add_meta_box('wpt_baby_baby12', 'Birth Year', 'wpt_baby_baby12', 'baby', 'normal', 'high');
}
// The Event job Metabox
function wpt_baby_baby12() {
    global $post;
    // Noncename needed to verify where the data originated
    echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' .
    wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
    // Get the job data if its already been entered
    $baby_birthyear = get_post_meta($post->ID, '_baby_birthyear', true);
    // Echo out the field
	
}

// Save the Metabox Data
function wpt_save_babyyear_meta($post_id, $post) {
    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if ( !wp_verify_nonce( $_POST['eventmeta_noncename'], plugin_basename(__FILE__) )) {
    return $post->ID;
    }
    // Is the user allowed to edit the post or page?
    if ( !current_user_can( 'edit_post', $post->ID ))
        return $post->ID;
    // OK, we're authenticated: we need to find and save the data
    // We'll put it into an array to make it easier to loop though.
    $baby_meta['_baby_birthyear'] = $_POST['_baby_birthyear'];
    // Add values of $baby_meta as custom fields
    foreach ($baby_meta as $key => $value) { // Cycle through the $baby_meta array!
        if( $post->post_type == 'revision' ) return; // Don't store custom data twice
        $value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
        if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
            update_post_meta($post->ID, $key, $value);
        } else { // If the custom field doesn't have a value
            add_post_meta($post->ID, $key, $value);
        }
        if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
    }
}
add_action('save_post', 'wpt_save_babyyear_meta', 1, 2); // save the custom fields


/* Custom For Baby Year url Ends */




/* Custom For Time of Birth*/

add_action( 'add_meta_boxes', 'add_baby_metaboxes2' );
// Add the job Meta Boxes
function add_baby_metaboxes2() {
    add_meta_box('wpt_baby_baby2', 'Time of Birth', 'wpt_baby_baby2', 'baby', 'normal', 'high');
}
// The Event job Metabox
function wpt_baby_baby2() {
    global $post;
    // Noncename needed to verify where the data originated
    echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' .
    wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
    // Get the job data if its already been entered
    $baby_time = get_post_meta($post->ID, '_baby_time', true);
    // Echo out the field
    echo '<input type="text" name="_baby_time" value="' . $baby_time  . '" class="widefat" />';
}

// Save the Metabox Data
function wpt_save_babytime_meta($post_id, $post) {
    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if ( !wp_verify_nonce( $_POST['eventmeta_noncename'], plugin_basename(__FILE__) )) {
    return $post->ID;
    }
    // Is the user allowed to edit the post or page?
    if ( !current_user_can( 'edit_post', $post->ID ))
        return $post->ID;
    // OK, we're authenticated: we need to find and save the data
    // We'll put it into an array to make it easier to loop though.
    $baby_meta['_baby_time'] = $_POST['_baby_time'];
    // Add values of $baby_meta as custom fields
    foreach ($baby_meta as $key => $value) { // Cycle through the $baby_meta array!
        if( $post->post_type == 'revision' ) return; // Don't store custom data twice
        $value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
        if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
            update_post_meta($post->ID, $key, $value);
        } else { // If the custom field doesn't have a value
            add_post_meta($post->ID, $key, $value);
        }
        if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
    }
}
add_action('save_post', 'wpt_save_babytime_meta', 1, 2); // save the custom fields


/* Custom For Baby Time of Birth url Ends */



/* Custom For Weight/length*/

add_action( 'add_meta_boxes', 'add_baby_metaboxes3' );
// Add the job Meta Boxes
function add_baby_metaboxes3() {
    add_meta_box('wpt_baby_baby3', 'Weight/length', 'wpt_baby_baby3', 'baby', 'normal', 'high');
}
// The Event job Metabox
function wpt_baby_baby3() {
    global $post;
    // Noncename needed to verify where the data originated
    echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' .
    wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
    // Get the job data if its already been entered
    $baby_weight = get_post_meta($post->ID, '_baby_weight', true);
    // Echo out the field
    echo '<input type="text" name="_baby_weight" value="' . $baby_weight  . '" class="widefat" />';
}

// Save the Metabox Data
function wpt_save_babyweight_meta($post_id, $post) {
    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if ( !wp_verify_nonce( $_POST['eventmeta_noncename'], plugin_basename(__FILE__) )) {
    return $post->ID;
    }
    // Is the user allowed to edit the post or page?
    if ( !current_user_can( 'edit_post', $post->ID ))
        return $post->ID;
    // OK, we're authenticated: we need to find and save the data
    // We'll put it into an array to make it easier to loop though.
    $baby_meta['_baby_weight'] = $_POST['_baby_weight'];
    // Add values of $baby_meta as custom fields
    foreach ($baby_meta as $key => $value) { // Cycle through the $baby_meta array!
        if( $post->post_type == 'revision' ) return; // Don't store custom data twice
        $value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
        if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
            update_post_meta($post->ID, $key, $value);
        } else { // If the custom field doesn't have a value
            add_post_meta($post->ID, $key, $value);

        }
        if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
    }
}
add_action('save_post', 'wpt_save_babyweight_meta', 1, 2); // save the custom fields


/* Custom For Baby Weight/length url Ends */

/* Custom For Parents*/

add_action( 'add_meta_boxes', 'add_baby_metaboxes4' );
// Add the job Meta Boxes
function add_baby_metaboxes4() {
    add_meta_box('wpt_baby_baby4', 'Parents', 'wpt_baby_baby4', 'baby', 'normal', 'high');
}
// The Event job Metabox
function wpt_baby_baby4() {
    global $post;
    // Noncename needed to verify where the data originated
    echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' .
    wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
    // Get the job data if its already been entered
    $baby_parents = get_post_meta($post->ID, '_baby_parents', true);
    // Echo out the field
    echo '<input type="text" name="_baby_parents" value="' . $baby_parents  . '" class="widefat" />';
}

// Save the Metabox Data
function wpt_save_babyparents_meta($post_id, $post) {
    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if ( !wp_verify_nonce( $_POST['eventmeta_noncename'], plugin_basename(__FILE__) )) {
    return $post->ID;
    }
    // Is the user allowed to edit the post or page?
    if ( !current_user_can( 'edit_post', $post->ID ))
        return $post->ID;
    // OK, we're authenticated: we need to find and save the data
    // We'll put it into an array to make it easier to loop though.
    $baby_meta['_baby_parents'] = $_POST['_baby_parents'];
    // Add values of $baby_meta as custom fields
    foreach ($baby_meta as $key => $value) { // Cycle through the $baby_meta array!
        if( $post->post_type == 'revision' ) return; // Don't store custom data twice
        $value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
        if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
            update_post_meta($post->ID, $key, $value);
        } else { // If the custom field doesn't have a value
            add_post_meta($post->ID, $key, $value);
        }
        if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
    }
}
add_action('save_post', 'wpt_save_babyparents_meta', 1, 2); // save the custom fields


/* Custom For Baby parents url Ends */

/* Custom For Delivered*/

add_action( 'add_meta_boxes', 'add_baby_metaboxes5' );
// Add the job Meta Boxes
function add_baby_metaboxes5() {
    add_meta_box('wpt_baby_baby5', 'Delivered by', 'wpt_baby_baby5', 'baby', 'normal', 'high');
}
// The Event job Metabox
function wpt_baby_baby5() {
    global $post;
    // Noncename needed to verify where the data originated
    echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' .
    wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
    // Get the job data if its already been entered
    $baby_delivered = get_post_meta($post->ID, '_baby_delivered', true);
    // Echo out the field
    echo '<input type="text" name="_baby_delivered" value="' . $baby_delivered  . '" class="widefat" />';
}

// Save the Metabox Data
function wpt_save_babydelivered_meta($post_id, $post) {
    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if ( !wp_verify_nonce( $_POST['eventmeta_noncename'], plugin_basename(__FILE__) )) {
    return $post->ID;
    }
    // Is the user allowed to edit the post or page?
    if ( !current_user_can( 'edit_post', $post->ID ))
        return $post->ID;
    // OK, we're authenticated: we need to find and save the data
    // We'll put it into an array to make it easier to loop though.
    $baby_meta['_baby_delivered'] = $_POST['_baby_delivered'];
    // Add values of $baby_meta as custom fields
    foreach ($baby_meta as $key => $value) { // Cycle through the $baby_meta array!
        if( $post->post_type == 'revision' ) return; // Don't store custom data twice
        $value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
        if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
            update_post_meta($post->ID, $key, $value);
        } else { // If the custom field doesn't have a value
            add_post_meta($post->ID, $key, $value);
        }
        if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
    }
}
add_action('save_post', 'wpt_save_babydelivered_meta', 1, 2); // save the custom fields


/* Custom For Baby Delivered url Ends */


/* Custom For Mothers Doctor*/

add_action( 'add_meta_boxes', 'add_baby_metaboxes6' );
// Add the job Meta Boxes
function add_baby_metaboxes6() {
    add_meta_box('wpt_baby_baby6', 'Mothers Doctor', 'wpt_baby_baby6', 'baby', 'normal', 'high');
}
// The Event job Metabox
function wpt_baby_baby6() {
    global $post;
    // Noncename needed to verify where the data originated
    echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' .
    wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
    // Get the job data if its already been entered
    $baby_mother = get_post_meta($post->ID, '_baby_mother', true);
    // Echo out the field
    echo '<input type="text" name="_baby_mother" value="' . $baby_mother  . '" class="widefat" />';
}

// Save the Metabox Data
function wpt_save_babymother_meta($post_id, $post) {
    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if ( !wp_verify_nonce( $_POST['eventmeta_noncename'], plugin_basename(__FILE__) )) {
    return $post->ID;
    }
    // Is the user allowed to edit the post or page?
    if ( !current_user_can( 'edit_post', $post->ID ))
        return $post->ID;
    // OK, we're authenticated: we need to find and save the data
    // We'll put it into an array to make it easier to loop though.
    $baby_meta['_baby_mother'] = $_POST['_baby_mother'];
    // Add values of $baby_meta as custom fields
    foreach ($baby_meta as $key => $value) { // Cycle through the $baby_meta array!
        if( $post->post_type == 'revision' ) return; // Don't store custom data twice
        $value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
        if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
            update_post_meta($post->ID, $key, $value);
        } else { // If the custom field doesn't have a value
            add_post_meta($post->ID, $key, $value);
        }
        if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
    }
}
add_action('save_post', 'wpt_save_babymother_meta', 1, 2); // save the custom fields


/* Custom For Baby Mother's doctor url Ends */


/* Custom For babys Doctor*/

add_action( 'add_meta_boxes', 'add_baby_metaboxes7' );
// Add the job Meta Boxes
function add_baby_metaboxes7() {
    add_meta_box('wpt_baby_baby7', 'Babys Doctor', 'wpt_baby_baby7', 'baby', 'normal', 'high');
}
// The Event job Metabox
function wpt_baby_baby7() {
    global $post;
    // Noncename needed to verify where the data originated
    echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' .
    wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
    // Get the job data if its already been entered
    $baby_babydoctor = get_post_meta($post->ID, '_baby_babydoctor', true);
    // Echo out the field
    echo '<input type="text" name="_baby_babydoctor" value="' . $baby_babydoctor  . '" class="widefat" />';
}

// Save the Metabox Data
function wpt_save_babybaby_meta($post_id, $post) {
    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if ( !wp_verify_nonce( $_POST['eventmeta_noncename'], plugin_basename(__FILE__) )) {
    return $post->ID;
    }
    // Is the user allowed to edit the post or page?
    if ( !current_user_can( 'edit_post', $post->ID ))
        return $post->ID;
    // OK, we're authenticated: we need to find and save the data
    // We'll put it into an array to make it easier to loop though.
    $baby_meta['_baby_babydoctor'] = $_POST['_baby_babydoctor'];
    // Add values of $baby_meta as custom fields
    foreach ($baby_meta as $key => $value) { // Cycle through the $baby_meta array!
        if( $post->post_type == 'revision' ) return; // Don't store custom data twice
        $value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
        if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
            update_post_meta($post->ID, $key, $value);
        } else { // If the custom field doesn't have a value
            add_post_meta($post->ID, $key, $value);
        }
        if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
    }
}
add_action('save_post', 'wpt_save_babybaby_meta', 1, 2); // save the custom fields


/* Custom For Baby baby's doctor url Ends */


/* Custom For Comments*/

add_action( 'add_meta_boxes', 'add_baby_metaboxes8' );
// Add the job Meta Boxes
function add_baby_metaboxes8() {
    add_meta_box('wpt_baby_baby8', 'Note/Quote', 'wpt_baby_baby8', 'baby', 'normal', 'high');
}
// The Event job Metabox
function wpt_baby_baby8() {
    global $post;
    // Noncename needed to verify where the data originated
    echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' .
    wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
    // Get the job data if its already been entered
    $baby_babycomments = get_post_meta($post->ID, '_baby_babycomments', true);
    // Echo out the field
    echo '<textarea name="_baby_babycomments" class="widefat">' . $baby_babycomments  . '</textarea>';
}

// Save the Metabox Data
function wpt_save_babycomments_meta($post_id, $post) {
    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if ( !wp_verify_nonce( $_POST['eventmeta_noncename'], plugin_basename(__FILE__) )) {
    return $post->ID;
    }
    // Is the user allowed to edit the post or page?
    if ( !current_user_can( 'edit_post', $post->ID ))
        return $post->ID;
    // OK, we're authenticated: we need to find and save the data
    // We'll put it into an array to make it easier to loop though.
    $baby_meta['_baby_babycomments'] = $_POST['_baby_babycomments'];
    // Add values of $baby_meta as custom fields
    foreach ($baby_meta as $key => $value) { // Cycle through the $baby_meta array!
        if( $post->post_type == 'revision' ) return; // Don't store custom data twice
        $value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
        if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
            update_post_meta($post->ID, $key, $value);
        } else { // If the custom field doesn't have a value
            add_post_meta($post->ID, $key, $value);
        }
        if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
    }
}
add_action('save_post', 'wpt_save_babycomments_meta', 1, 2); // save the custom fields


/* Single Baby Page */
function get_custom_post_type_template($single_template) {
     global $post;

     if ($post->post_type == 'baby') {
          $single_template = dirname( __FILE__ ) . '/templates/single_baby.php';
     }
     return $single_template;
}
add_filter( 'single_template', 'get_custom_post_type_template' );
?>