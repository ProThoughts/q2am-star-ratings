<?php
/*
	Plugin Name: Q2AM Star Ratings
	Plugin URI: http://store.q2amarket.com/store/products/q2am-star-ratings/
	Plugin Description: This plugin will add star ratings sytem to the question page and allows to rates question and answers
	Plugin Version: 1.00
	Plugin Date: 2013-04-27
	Plugin Author: Question2Answer
	Plugin Author URI: http://www.q2amarket.com
	Plugin License: GPLv2
	Plugin Minimum Question2Answer Version: 1.5
	Plugin Update Check URI: https://github.com/q2amarket/q2am-star-ratings/raw/master/qa-plugin.php
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