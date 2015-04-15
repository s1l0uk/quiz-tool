<?php

function array_unshift_assoc(&$arr, $key, $val) 
{ 
    $arr = array_reverse($arr, true); 
    $arr[$key] = $val; 
    return  array_reverse($arr, true); 
} 

function get_answer_mode($val) 
{ 
			if (strpos($val,',') !== false) {
			    return "checkbox";
			}elseif (strpos($val,'>') !== false) {
			    return "order";
			}else{
			    return "radio";
			}
} 






$a = array();


$file = file("data.txt");
$i = 0;
$o = 0;
$q = 0;
$ch = 0 ; 


foreach ($file as $key => $value) {

	

	if(preg_match('/^([0-9]+)\. (.*)/', $value,$m)){
		$a[$i]["question1"] = $m[1].". ".$m[2];
		
		
	}elseif (preg_match('/^answer: (.*)/i', $value,$m)){
			$a[$i]["answer"] = str_replace(" ", "", $m[1]);
			$a[$i]["answer_mode"] = get_answer_mode($a[$i]["answer"]);
			

	}elseif (preg_match('/^([a-z]) (.*)/i', $value,$m)){
			$a[$i]["list"][$m[1]] =  $m[2];
	}elseif (preg_match('/^([a-z])\. (.*)/i', $value,$m)){
			$a[$i]["list"][$m[1]] =  $m[2];
	}elseif (preg_match('/^note: (.*)/i', $value,$m)){
			$a[$i]["note"] =  $m[1];
	}elseif (preg_match('/^#/i', $value,$m)){
			#$a[$i]["note"] =  $m[1];
	}else{

		if ($value == "\n"){
			$i++;
		}else{
			echo "boh #$value#\n";
			echo "at ". $key."\n";
		}

		
	}


}







@unlink("data.json");
file_put_contents("data.json", json_encode($a,JSON_PRETTY_PRINT) );


echo "Updating myjson.com\n";
$curl = curl_init("https://api.myjson.com/bins/4fbu3");
$data =  json_encode($a,JSON_PRETTY_PRINT);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

// Make the REST call, returning the result
$response = curl_exec($curl);
if (!$response) {
    die("Connection Failure.n");
}
curl_close($curl);
?>