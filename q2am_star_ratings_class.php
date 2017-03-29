<?php
/*
*	Q2AM Star Ratings
*
*	Core system interact with the database.
*	File: q2am_star_ratings_class.php
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

class q2am_star_ratings
{
    /**
     * q2am_star_rating class
     * 
     * this class connect star ratings system
     * to the database and insert, updates
     * and retrieve database as per the request
     * 
     * this is the core functionality to 
     * interact with q2a dtabase however
     * star ratings syste mostly using
     * jquery plugin by jRating
     *
     * NOTE: Currently this class doesn't contains
     * more methods but I have written this class
     * for future update so I don't have to rewrite
     * code agian and I can reuse it quickly
     *      
     */

	public function render($post_id)
	{
		$query = qa_db_query_sub("SELECT title FROM ^posts WHERE postid=$post_id");
		$result = qa_db_read_one_assoc($query, true);
		return $result;
	}
	/*------------------------------------------------------
		get items from database

		this method will get rating data from 
		qa_star_ratings table by postid
	 ------------------------------------------------------*/

	public function get_items($post_id = null)
	{
		if(isset($post_id)){
			$query = qa_db_query_sub("SELECT * FROM ^star_ratings WHERE post_id = '$post_id'");		
		} else {
			$query = qa_db_query_sub("SELECT * FROM ^star_ratings");		
		}

		$rowcount = qa_db_query_sub("SELECT COUNT(*) FROM ^star_ratings");

		if ($rowcount->num_rows >= 1){
			$result = qa_db_read_one_assoc($query, true);
		} else {
			$result = 0;
		}

		return $result;
	}

	/*------------------------------------------------------
		insert ratings to database

		this method will insert/update ratings value given
		by user and store into qa_star_ratings table
	 ------------------------------------------------------*/

	public function insert_ratings($post_id, $rating, $total_rating, $total_rates, $ipaddress)
	{
		$query = qa_db_query_sub("
			UPDATE ^star_ratings 
			SET rating = '$rating', 
			total_rating = '$total_rating', 
			total_rates = '$total_rates', 
			ipaddress = CONCAT(ifnull(ipaddress,''), ',$ipaddress')

			WHERE post_id = '$post_id'
		");
		
		$rowcount = qa_db_query_sub("SELECT COUNT(*) FROM ^star_ratings");
		return $rowcount;
	}

	/*------------------------------------------------------
		get ratings count

		this method simply retrieve total ratings by
		postid and ready to display

		if there is no rating than will display
		"Be the first" which you can change as per
		yoru choice
	 ------------------------------------------------------*/

	public function q2am_ratings_count($post_id)
	{
		$existingdata = self::get_items($post_id);
		$rating = $existingdata['rating'];

		if($rating){
			return 'Rating: <strong>'.number_format($rating, 2).'</strong>';
		} else {
			return '<span class="q2am-zero-rate-count">Be the first</span>';
		}
		
	}

}
/*
	Omit PHP closing tag to help avoid accidental output
*/