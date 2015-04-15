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


$file = file("cisco-question.txt");
$i = 0;
$o = 0;
$q = 0;
$ch = 0 ; 

$an_file = file("cisco-answer.txt");
foreach ($file as $key => $value) {

	

	if(preg_match('/^([0-9]+)\. (.*)/', $value,$m)){

		if ($o != 0 ){
			$tmp = str_replace(array("."," "),array("",""), rtrim(ltrim($an_file[$i])) );
			$a[$i]["answer_key"] = $ch.".".$tmp;
			$a[$i]["answer"] = strtoupper(preg_replace("/^([0-9]+)/","", $tmp ));
			$a[$i]["answer_mode"] = get_answer_mode($a[$i]["answer"]);


			$i++;
				
		}
		$o = 0;

		if ($m[1] == 1 ) $ch++;


		$a[$i]["question1"] = $ch.".".$m[1].": ".$m[2];
		
		
	}elseif (preg_match('/^([a-z])\. (.*)/', $value,$m)){
			$a[$i]["list"][chr(65 + $o)] =  $m[2];
			$o++;
	}else{

		echo "boh $value\n";
	}


}

// add last answer
$tmp = str_replace(array("."," "),array("",""), rtrim(ltrim($an_file[$i])) );
$a[$i]["answer_key"] = $ch.".".$tmp;
$a[$i]["answer"] = strtoupper(preg_replace("/^([0-9]+)/","", $tmp ));
$a[$i]["answer_mode"] = get_answer_mode($a[$i]["answer"]);


@unlink("full-cisco.json");
file_put_contents("full-cisco.json", json_encode($a,JSON_PRETTY_PRINT) );


echo "Updating myjson.com\n";
$curl = curl_init("https://api.myjson.com/bins/34x9j");
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