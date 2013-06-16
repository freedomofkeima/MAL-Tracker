<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>MAL Tracker</title>
<link rel="stylesheet" type="text/css" href="./css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="./css/style.css" />
<script src="./js/jquery-1.8.2.js"></script>
<script src="./js/bootstrap.js"></script>
</head>

<?php
	# Open the File.
	if (($handle = fopen("MAL_database_extract-test.txt", "r")) !== FALSE) {
		# Set the parent multidimensional array key to 0.
		$nn = 0;
		while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
			# Count the total keys in the row.
			$c = count($data);
			# Populate the multidimensional array.
			for ($x=0;$x<$c;$x++)
			{
				$csvarray[$nn][$x] = $data[$x];
			}
			$nn++;
		}
		# Close the File.
		fclose($handle);
	}
?>

<body>
<?php
$current_url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
?>	


<form method="POST" action="<?php echo $current_url ?>"> 
<input type="hidden" name="box1" value="0" checked /> <input type="checkbox" class="checkbox" name="box1" value="1" <?php if(isset($_POST["submit"])){ if($_POST['box1'] == "1") echo "checked";} else {echo "checked";}?> /> G - All Ages <br>
<input type="hidden" name="box2" value="0" checked /> <input type="checkbox" class="checkbox" name="box2" value="1" <?php if(isset($_POST["submit"])){ if($_POST['box2'] == "1") echo "checked";} else {echo "checked";}?> />PG-13 - Teens 13 or older <br>
<input type="hidden" name="box3" value="0" checked /> <input type="checkbox" class="checkbox" name="box3" value="1" <?php if(isset($_POST["submit"])){ if($_POST['box3'] == "1") echo "checked";} else {echo "checked";}?> /> R - 17+ (violence & profanity) <br>
<input type="hidden" name="box4" value="0" checked /> <input type="checkbox" class="checkbox" name="box4" value="1" <?php if(isset($_POST["submit"])){ if($_POST['box4'] == "1") echo "checked";} else {echo "checked";}?> /> R+ - Mild Nudity <br>
<input type="hidden" name="box5" value="0" checked /> <input type="checkbox" class="checkbox" name="box5" value="1" <?php if(isset($_POST["submit"])){ if($_POST['box5'] == "1") echo "checked";} else {echo "checked";}?> /> Rx - Hentai <br>
<input type ="submit" name ="submit" class="btn">
</form>

<table class="table table-striped">
<thead>
	<th> No. 		</th>
	<th> Id. 		</th>
	<th> Name		</th>
	<th> Rating 	</th>
	<th> Rank		</th>
	<th> Score 		</th>
</thead>
<tbody>
<?php

function SortRank($file, $argc, $size, $param)
{
	#sort by param
	for ($x = 0; $x < $size - 1; $x++)
	 {
	    $min = $x;
		for ($y =$x+1 ; $y < $size; $y++)
		{
			if ($file[$min][$param] > $file[$y][$param])
			   $min = $y;
		}
		//swap
		for ($y = 0; $y < $argc; $y++)
		{
			$temp = $file[$x][$y];
			$file[$x][$y] = $file[$min][$y];
			$file[$min][$y] = $temp;
		}
	 }
	return $file;
}

	
$csvarray = SortRank($csvarray, $c, $nn, 3);
# Print the File.
$nomor = 0;
   $x1 = "G - All Ages";
   $x2 = "PG-13 - Teens 13 or older";
   $x3 = "R - 17+ (violence & profanity)";
   $x4 = "R+ - Mild Nudity";
   $x5 = "Rx - Hentai";
if (isset($_POST['submit']))
 {
   if ($_POST['box1'] == "0")
    $x1 = "";
   if ($_POST['box2'] == "0")
    $x2 = "";
   if ($_POST['box3'] == "0")
    $x3 = "";
   if ($_POST['box4'] == "0")
    $x4 = "";
   if ($_POST['box5'] == "0")
    $x5 = "";
 }
for($x=0;$x<$nn;$x++)
{
  if (($csvarray[$x][2] == $x1) || ($csvarray[$x][2] == $x2) || ($csvarray[$x][2] == $x3) || ($csvarray[$x][2] == $x4) || ($csvarray[$x][2] == $x5))
    {
		echo "<tr>\n";
		echo "<td>" . ($nomor+1) . "</td>\n";
		$nomor = $nomor + 1;
		$s = "http://myanimelist.net/anime/" . $csvarray[$x][0];
		   echo"<td><a href={$s}>" . $csvarray[$x][0] . "</a></td>\n";
		for ($y = 1; $y < $c ; $y++)
		  echo "<td>" . $csvarray[$x][$y] . "</td>\n";
		echo "</tr>\n";
	}
}
?>
</tbody>
</table>
</body>
</html>

<!-- created by freedomofkeima 2013 -->