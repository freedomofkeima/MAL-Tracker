<?php
$file_output = "17001-18000.txt";
$fp = fopen($file_output, 'wt');
for ($i = 17001; $i <= 18000; $i++)
{
	$id = $i;
	$file = 'http://mal-api.com/anime/'. $id . '?format=xml';
	$file_headers = @get_headers($file);
	if ($file_headers[0] !== 'HTTP/1.1 404 Not Found') //if file exists
	   {
		$text= file_get_contents($file); 
		$filename = "temp.xml"; //creating an xml file
		$fh = fopen($filename, 'wt');
		fwrite($fh,$text);
		fclose($fh);
		$xml = simplexml_load_file($filename);
		
		$title = '';
		$classification = '';
		$rank = '';
		$members_score = '';
		
		foreach($xml->children() as $child) //printing data to screen
		  {
		   if ($child->getName() == 'title')
		    $title = $child;
		   if ($child->getName() == 'classification')
		    $classification = $child;
		   if ($child->getName() == 'rank')
		    $rank = $child;
		   if ($child->getName() == 'members_score')
		    $members_score =  $child; 
		  }
		 
		$output = $id . ",\"" . $title . "\",\"" . $classification . "\"," . $rank . "," . $members_score . "\n";
		fwrite($fp,$output);
		}

}
fclose($fp);
//1.8 sec per query
?>
<!-- created by freedomofkeima 2013 -->
