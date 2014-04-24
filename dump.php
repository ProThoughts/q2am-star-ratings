<?php

	require_once '../../qa-include/qa-base.php';
	require_once QA_INCLUDE_DIR.'qa-db.php';

	$result = qa_db_query_sub("
		INSERT INTO ^star_ratings(post_id) 
		SELECT postid 
		FROM qa_posts
		WHERE type in ('Q', 'A')
		ORDER BY postid ASC
	");

	if($result){

		echo '<h1 style="color:green;text-align:center;font-family:Arial,Tahoma;">All data dump successfully!</h1>';		
		unlink(__FILE__);
		header('Location: '.qa_opt('site_url'));

	} else {

		echo '<h1 style="color:red;text-align:center;font-family:Arial,Tahoma;">Unable to dump data</h1>';

	}