<?php

	
	//include makeTwiterApiCalls files
	include('makeTwitterApiCalls.php');
	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	//default query string
	$serachString='india';

	//provide search query via url get method
	/*if(!empty($_GET['q'])
		{
			$serachString=$_GET['q'];
		}
*/
	$searchCriteria['campaign_name']=$serachString;
	searchTweets($searchCriteria);

?>