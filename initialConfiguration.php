<?php

//this file have all initial configuration settings for followng operations
//1. database connection
//2. app access token (get)
//3. app bearer token (set,get)
//4. twitter codebird library inplmentation


function getDatabaseConnection()
{
   // include("databaseConnection.php");
	$host="localhost";
	$dbUser="stagingf_sail";
	$dbPassword=";4+HeTp,sfeg";
	$dbName="stagingf_sail";

	$connection= new mysqli($host,$dbUser,$dbPassword,$dbName);
    return $connection;
}


//set user token from database/file

//get app bearer token to send app only request. Required only once and store  in database. Need to refresh when expired
function getAppBearerTokens()
{
		$bearer_token='';
		//check if bearer token already available in database
		$resultArr=checkBearerTokenInDatabase();
		if(!$resultArr)
		{
					
				require_once('library/codebird.php');
			
				//get app consumer key and consumer secret key
				$keyArr =getAppAccessTokens();
				$consumerKey= $keyArr['app_consumer_key'];
				$consumerKeySecret= $keyArr['app_consumer_secret'];
				$dbAppId=$keyArr['app_id'];
				\Codebird\Codebird::setConsumerKey($consumerKey, $consumerKeySecret); 
				$twitterObject = \Codebird\Codebird::getInstance();

				$response = $twitterObject->oauth2_token();
				$bearer_token = $response->access_token;
				
				//bind bearer token data into array
				$bearerDataArr=array("bearer_token"=>$bearer_token,"app_id"=>$dbAppId);
				insertBearerTokenInDatabase($bearerDataArr);
				
				
					echo '<pre>';
					print_r($response);
					echo '</pre>';
			
		}
		else
		{
				
			$bearer_token=$resultArr['app_bearer_token'];	
		}

		return $bearer_token;
}

//get access tokens from database
function getAppAccessTokens ()
{
	$dbCon=getDatabaseConnection();
        
    $selectAppToken ="SELECT * FROM app_access_tokens LIMIT 0,1";
    $result=$dbCon->query($selectAppToken);
    
	$dbTokenArray=$result->fetch_assoc();
	echo "Tokens->".$dbArray['app_consumer_key'];
	
	return $dbTokenArray;
			
}


//get app bearer token from database
function checkBearerTokenInDatabase()
{
	$dbCon=getDatabaseConnection();
	
	//select bearer tokens from database
	$selectQuery="SELECT * FROM app_bearer_token LIMIT 0,1";
	$result=$dbCon->query($selectQuery);
	$dbBearerTokenArr=$result->fetch_assoc();
	$rowCount=sizeof($dbBearerTokenArr);
	if($rowCount>0)
	{
		return $dbBearerTokenArr;
	}
	else
		return false;

}

//insert bear token from database

function insertBearerTokenInDatabase($bearerDataArr)
{
		$dbCon=getDatabaseConnection();
		$bearerToken=$bearerDataArr['bearer_token'];
		$appId=$bearerDataArr['app_id'];
		$insertQuery="INSERT INTO app_bearer_token(app_id,app_bearer_token) VALUES($appId, '$bearerToken')";
		
		$dbCon->query($insertQuery);
}

//initialize app-only access tokens--------
function initializeAPIRequest()
{
	require_once('library/codebird.php');
	$bearerToken=getAppBearerTokens();
	\Codebird\Codebird::setBearerToken($bearerToken);
	$twitterObject = \Codebird\Codebird::getInstance();
	
	return $twitterObject;

}


