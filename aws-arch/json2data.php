<?php



$i = 0;
$o = 0;
$q = 0;
$ch = 0 ; 
$file="data.txt";

@unlink($file);

$data = json_decode(  file_get_contents("data.json") , true);
foreach ($data as $k => $v){
	$txt = ($k +1)  . ". ". $v["question1"]."\n";

	foreach ($v["list"] as $kk => $vv){
			$txt .= $kk ." ". $vv."\n";
	}

	$txt .= "answer: ". $v["answer"]."\n";

	if (array_key_exists('note', $v)) {
	    $txt .= "note: ". $v["note"]."\n";
	}

	$txt .= "\n";

	echo $txt;

	file_put_contents($file, $txt, FILE_APPEND | LOCK_EX);
}

?>