<?php
/*
*	Q2AM Star Ratings
*
*	Interaction between jquery star rating plugins and database
*	File: q2am-jquery-process.php
*	
*	@author			Q2A Market
*	@category		Plugin
*	@Version: 		1.0
*   @author URL:	http://www.q2amarket.com
*	
*	@Q2A Version	1.5.3
*
*	Do not modify this file unless you know what you are doing
*/


	require_once '../../qa-include/qa-base.php';
	require_once QA_INCLUDE_DIR.'qa-db.php';
	require_once QA_PLUGIN_DIR.'q2am-star-ratings/q2am_star_ratings_class.php';
	
	$star = new q2am_star_ratings;

	if($_POST)
	{
		$post_id = $_POST['idBox'];
		$rate = $_POST['rate'];
	}
	
	$ipaddress = qa_remote_ip_address();
	$existingdata = $star->get_items($post_id);

	$old_total_rating = $existingdata['total_rating'];
	$total_rates = $existingdata['total_rates'];

	$current_rating = $old_total_rating + $rate;
	$new_total_rates = $total_rates + 1;
	$new_rating = $current_rating / $new_total_rates;

	$insert = $star->insert_ratings($post_id, $new_rating, $current_rating, $new_total_rates, $ipaddress);


	if($insert)
	{
		echo 'sucsses';
	} else {
		echo 'error';
	}

/*
	Omit PHP closing tag to help avoid accidental output
*/