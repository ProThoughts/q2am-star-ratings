<?php
/*
*	Q2AM Star Ratings
*
*	Add required elements to the layers system.
*	File: q2am-star-ratings-layer.php
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

class qa_html_theme_layer extends qa_html_theme_base
{

	/*------------------------------------------------------
		Define plugin root function to reuse

		this can be done in other way
		but I prefer this way
	 ------------------------------------------------------*/

	function the_root()
	{
		return qa_opt('site_url').'qa-plugin/q2am-star-ratings/';
	}

	/*------------------------------------------------------
		Embading all required stylesheet files 
	 ------------------------------------------------------*/

	 function head_css()
	 {
	 	$this->output('<link type="text/css" rel="stylesheet" href="'.$this->the_root().'q2am-star-ratings.css" />');
	 	qa_html_theme_base::head_css();
	 }

	/*------------------------------------------------------
		Embading all required javascript files

		I prefer to use head_script() than body_script()
		sometimes other plugin may override or conflict

		using haed_script() at least there is a chance
		to not get overridden with scripts added into
		body_script()
	 ------------------------------------------------------*/

	function head_script()
	{
		qa_html_theme_base::head_script();

		$this->output('<script src="'.$this->the_root().'js/jRating.jquery.js"></script>');

		$this->output('
			<script>
				$(document).ready(function(){
					$(".q2am-star-ratings-element").jRating({
						decimalLength : '.qa_opt('q2am_decimal_length').',
						length : '.qa_opt('q2am_stars_count').', //number of stars to display
						rateMax : '.qa_opt('q2am_stars_rates').', //total rates for stars
						step : '.(qa_opt('q2am_enable_rate_step') ? 'true' : 'false').',
						type : "'.qa_opt('q2am_star_ratings_star_size').'",
						showRateInfo : '.(qa_opt('q2am_enable_info') ? 'true' : 'false').',
						rateInfosY : '.qa_opt('q2am_info_pos').',			
						bigStarsPath : "'.$this->the_root().'js/icons/stars.png",
						mediumStarsPath : "'.$this->the_root().'js/icons/medium.png",
						smallStarsPath : "'.$this->the_root().'js/icons/small.png",
						phpPath : "qa-plugin/q2am-star-ratings/q2am-jquery-process.php",
													
					});
				})
			</script>
		');

		if(qa_opt('q2am_star_ratings_enable') && qa_opt('q2am_star_ratings_position') === 'below votebox' && qa_opt('q2am_affect_tick_position'))
		$this->output('
			<script>
				$(document).ready(function(){
					$(".qa-a-selection").css("top", "'.qa_opt('q2am_tick_position_value').'px");
				})
			</script>
		');
	}

	/*------------------------------------------------------
		add start ratings below voting box

		this will add star rating elements below votebox
		I have modified with some additional html to style

		you are free to play with it to style as you need
		but make sure you do not change any variable as
		all variables are system variables for star ratings
		and can stop system working
	 ------------------------------------------------------*/

	function voting($post)
	{	
		if(qa_opt('q2am_star_ratings_enable') && qa_opt('q2am_star_ratings_position') === 'below votebox'){

			$post_id = $post['raw']['postid'];

			require_once QA_PLUGIN_DIR.'q2am-star-ratings/q2am_star_ratings_class.php';
			$star = new q2am_star_ratings;
			$item = $star->get_items($post_id);

			$allipaddress = explode(',', $item['ipaddress']);
			$currentip = qa_remote_ip_address();

			if((in_array($currentip, $allipaddress)) || (qa_opt('q2am_star_ratings_loggedin') && !qa_is_logged_in())){
				$class = 'jDisabled';
			} else {
				$class = '';
			}

			if($this->template=='question') {

				$this->output('<DIV CLASS="q2am-star-ratings-container-vote">');

				qa_html_theme_base::voting($post);

				$this->output('<DIV CLASS="q2am-star-ratings-element '.$class.'" data-average="'.$item['rating'].'" data-id="'.$post_id.'"></DIV>');

				if(qa_opt('q2am_enable_rate_count'))
				$this->output('<DIV CLASS="q2am-star-ratings-counter">'.$star->q2am_ratings_count($post_id).'</DIV>');

				$this->output('</DIV><!--q2am-star-ratings-container-vote-->');

			}

		} else {
			qa_html_theme_base::voting($post);
		}
	}

	/*------------------------------------------------------
		add start ratings below question conent

		this will add star rating elements below question
		content.

		you are free to play with it to style as you need
		but make sure you do not change any variable as
		all variables are system variables for star ratings
		and can stop system working
	 ------------------------------------------------------*/	

	function q_view_content($q_view)
	{
		if(qa_opt('q2am_star_ratings_enable') && qa_opt('q2am_star_ratings_position') === 'after content'){

			$post_id = $q_view['raw']['postid'];

			require_once QA_PLUGIN_DIR.'q2am-star-ratings/q2am_star_ratings_class.php';
			$star = new q2am_star_ratings;
			$item = $star->get_items($post_id);

			$allipaddress = explode(',', $item['ipaddress']);
			$currentip = qa_remote_ip_address();

			if((in_array($currentip, $allipaddress)) || (qa_opt('q2am_star_ratings_loggedin') && !qa_is_logged_in())){
				$class = 'jDisabled';
			} else {
				$class = '';
			}

			if($this->template=='question' ) {

				qa_html_theme_base::q_view_content($q_view);

				$this->output('<DIV CLASS="q2am-star-ratings-container-content">');
				$this->output('<DIV CLASS="q2am-star-ratings-element '.$class.'" data-average="'.$item['rating'].'" data-id="'.$post_id.'"></DIV>');

				if(qa_opt('q2am_enable_rate_count'))
				$this->output('<DIV CLASS="q2am-star-ratings-counter">'.$star->q2am_ratings_count($post_id).'</DIV>');

				$this->output('</DIV><!--q2am-star-ratings-container-content-->');

			}

		} else {
			qa_html_theme_base::q_view_content($q_view);
		}
	}

	/*------------------------------------------------------
		add start ratings below answer conent

		this will add star rating elements below answer
		content.

		you are free to play with it to style as you need
		but make sure you do not change any variable as
		all variables are system variables for star ratings
		and can stop system working
	 ------------------------------------------------------*/

	function a_item_content($a_item)
	{
		if(qa_opt('q2am_star_ratings_enable') && qa_opt('q2am_star_ratings_position') === 'after content' ){

			$post_id = $a_item['raw']['postid'];

			require_once QA_PLUGIN_DIR.'q2am-star-ratings/q2am_star_ratings_class.php';
			$star = new q2am_star_ratings;
			$item = $star->get_items($post_id);

			$allipaddress = explode(',', $item['ipaddress']);
			$currentip = qa_remote_ip_address();

			if((in_array($currentip, $allipaddress)) || (qa_opt('q2am_star_ratings_loggedin') && !qa_is_logged_in())){
				$class = 'jDisabled';
			} else {
				$class = '';
			}

			if($this->template=='question' ) {

				qa_html_theme_base::a_item_content($a_item);

				$this->output('<DIV CLASS="q2am-star-ratings-container-content">');
				$this->output('<DIV CLASS="q2am-star-ratings-element '.$class.'" data-average="'.$item['rating'].'" data-id="'.$post_id.'"></DIV>');

				if(qa_opt('q2am_enable_rate_count'))
				$this->output('<DIV CLASS="q2am-star-ratings-counter">'.$star->q2am_ratings_count($post_id).'</DIV>');

				$this->output('</DIV><!--q2am-star-ratings-container-content-->');

			}

		} else {
			qa_html_theme_base::a_item_content($a_item);
		}
		

	}

}
/*
	Omit PHP closing tag to help avoid accidental output
*/
