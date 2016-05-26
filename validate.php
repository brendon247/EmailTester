<?php
/**
 * Validates an email address using the EmailHunter API
 *
 * Returns "valid" or "invalid"
 */
if ($_REQUEST['email'] != "") {
	// sent an email to be validated
	$email = $_REQUEST['email'];
	
	// get json result from emailhunter
	$valid = file_get_contents("https://api.emailhunter.co/v1/verify?email=$email&api_key=21078f9432fcd4b9ca9e31d2542fb1cf281ff31f");

	$returnResult = array('status', 'result', 'score');
	// decode json and get result
	$valid = json_decode($valid);
	
	$returnResult['status'] = $valid->status;
	$returnResult['result'] = $valid->result;
	$returnResult['score'] = $valid->score;

	if ($valid->status == "success") {
		if ($valid->result == "deliverable") {
			$result = "valid";
		} else {
			$result = "invalid";
		}
	} else {
		$result = "error";
	}

	// write to flat file
	$fp = fopen("validate.dat", "a");
	if ($fp) {
		fwrite($fp, $email . ", " . $result . "\n");
		fclose($fp);
	}
	$encodedResult = json_encode($returnResult);
	// return result
	echo $encodedResult;
	//echo $result;
}
