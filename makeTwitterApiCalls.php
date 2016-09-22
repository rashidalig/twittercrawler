<?php

//This file will be used to call twitter GET/POST methods.

error_reporting(E_ALL);
ini_set('display_errors', 1);
include('initialConfiguration.php');

//search tweets by providing campaign string

function searchTweets($searchCriteria)
{
		$twitterObject = initializeAPIRequest();
	
		//define necceasry parameter values

//		$campaignId=$searchCriteria['campaign_id'];
		$searchString=$searchCriteria['campaign_name']; //campaign name
//		$geolocation=$searchCriteria['geolocation'];
//		$range=$searchCriteria['range'];
//		$countTweet=$searchCriteria['max_entries'];	//number of max return tweets
		

		$parameter=array('q'=>$searchString,'lang'=>'en');


		$jsonResponse = $twitterObject->search_tweets($parameter,true);

			echo '<pre>';
					print_r($jsonResponse);
					echo '</pre>';
}

/*$serachString='abc';

	//provide search query via url get method
	//$serachString=$_GET['q'];

	$searchCriteria['campaign_name']=$serachString;
	
searchTweets($searchCriteria);*/


?>