<?php

function array_unshift_assoc(&$arr, $key, $val) 
{ 
    $arr = array_reverse($arr, true); 
    $arr[$key] = $val; 
    return  array_reverse($arr, true); 
} 


$a = array();

$file = explode("\n\n", file_get_contents("question.txt"));
$i = 0;
foreach ($file as $key => $value) {
	#echo "$value\n";
	$line = explode("\n",$value);
	
	$a[$i]["question1"] = $line[0];
	unset($line[0]);
	foreach ($line as $k => $v) {
		if (empty($v)) continue;

		if(preg_match('/^([A-Z0-9])\s(.*)/', $v,$m)){

			$a[$i]["list"][$m[1]] = $m[2];
		}else{
			echo "boh $value \n";
		}
	}
	
	$i++;
	#echo "\n#####\n";
}

$k = 0;
$file = file("answer.txt");

		foreach ($a as $idx => $full) {

				foreach ($file as $key => $value) {
						$line_clean = substr(rtrim(ltrim($value)),2);
						similar_text(rtrim(ltrim($full["question1"])), $line_clean , $percent);
						$per_int=intval($percent); 

				if ($per_int > 70 && $per_int < 90 ){
				    #echo $full["question1"]."\n";
				   #echo $line_clean."\n$per_int\n\n";
				 }
				   if ($per_int > 90){

					array_unshift_assoc($a[$k], "question2" , $line_clean);

					$str_answer = rtrim(ltrim(substr($file[$key + 1 ] , 2 )));
					array_unshift_assoc($a[$k], "answer_str" , $str_answer);
					$found_an = false;
					foreach ($full['list'] as $k_st => $l_st) {
						similar_text($l_st, $str_answer, $p); 
						if ($p > 80){
							$found_an = true;
							array_unshift_assoc($a[$k], "answer" , $k_st);
						}
					}
					if( $found_an  ==false ) echo "mismatch answer :".$str_answer."\n";
					
					}

				}	
				
				$k++;

			}






#asort($a);
#print_r($a);

unlink("full.json");
file_put_contents("full.json", json_encode($a,JSON_PRETTY_PRINT) );




$a = array();


$file = explode("\n\n", file_get_contents("question2.txt"));
$i = 0;
$an = 0;
$an_file = file("answer2.txt");
foreach ($file as $key => $value) {
	#echo "$value\n";
	$line = explode("\n",$value);
	
	$a[$i]["question1"] = $line[0];
	$a[$i]["answer"] = rtrim(ltrim(substr($an_file[$an],0,1)));
	$a[$i]["answer_str"] = substr($an_file[$an],2);

	if(preg_match('/^N:/', $an_file[$an+1])){ 
			$a[$i]["note"] = substr($an_file[$an + 1 ],2);
			$an++;
	}

	unset($line[0]);
	foreach ($line as $k => $v) {
		if (empty($v)) continue;

		if(preg_match('/^([A-Z0-9])\s(.*)/', $v,$m)){

			$a[$i]["list"][$m[1]] = $m[2];
		}else{
			echo "boh $value \n";
		}
	}
	
	$i++;
	$an++;
	#echo "\n#####\n";
}

unlink("full2.json");
file_put_contents("full2.json", json_encode($a,JSON_PRETTY_PRINT) );
?>