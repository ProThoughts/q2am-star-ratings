<?php
/*
	Plugin Name: Q2A Market Star Ratings
	Plugin URI: http://store.q2amarket.com/q2a-free-plugins/star-ratings
	Plugin Description: This plugin will add a stars rating system to the question page and allow rating questions and answers.
	Plugin Version: 1.0.1
	Plugin Date: 2017-03-29
	Plugin Author: Q2A Market
	Plugin Author URI: http://www.q2amarket.com
	Plugin License: GPLv3+
	Plugin Minimum Question2Answer Version: 1.7
	Plugin Update Check URI: https://raw.github.com/q2amarket/q2am-star-ratings/master/qa-plugin.php
*/


	if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
		header('Location: ../../');
		exit;
	}
	
	qa_register_plugin_module('event', 'q2am-star-ratings-process.php', 'q2am_star_rating_process', 'Q2AM Star Rating Process');
	qa_register_plugin_layer('q2am-star-ratings-layer.php', 'Q2AM Star Ratings');
	
	

/*
	Omit PHP closing tag to help avoid accidental output
*/
