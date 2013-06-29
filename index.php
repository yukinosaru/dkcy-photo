<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<title>Photographs by Daniel Yeo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="photo.css" />
</head>

<body>
<h1>photos by dkcy</h1>

<?php

#Includes database functions for using MySQL and opens a connection 

require("db-code.php");

# Sets up a series of arrays with details of each collection

$query = "SELECT * FROM collections WHERE active='y' ORDER BY id ASC LIMIT 6";


if(ExecuteQuery($linkdb,$result,$query)){
	$no_of_rows = mysql_num_rows($result);
	while($row=NextRow($result)){
		$colid[] = $row[0];
		$colname[] = $row[1];
		$query2="SELECT * FROM photo WHERE collection={$row['id']} AND active='y' ORDER BY id ASC LIMIT 1";
		if(ExecuteQuery($linkdb,$result2,$query2)){
			while($row2=NextRow($result2)){
				$width = substr($row2['image_size'],7,3);
				$height = substr($row2['image_size'],-4,3);
				if($height>$width){$orient[]="height";}
				else{$orient[]="width";}
				$firstimage[] = $row2['id'];
			}
			ClearQuery($result2);
		}
	}
}

# Defines HTML output format
$output= "<div class='box' id='box%s'>\r<a href='collection.php?colid=%d'>\r<img class='index' %s='150px' src='showthumb.php?id=%d' alt='%s' /></a>\r<div class='collection-title'>%s</div>\r</div>\r\r";

#Iterates through arrays created to display 3 collections
$i = 0;

while ($i < $no_of_rows){
		if(@$firstimage[$i]){
			$j = $i+1;
			$div_id = "{$no_of_rows}-{$j}";
			printf($output,$div_id,$colid[$i],$orient[$i],$firstimage[$i],$colname[$i],$colname[$i]);
		}
		$i++;
}
?>

</body>

</html>