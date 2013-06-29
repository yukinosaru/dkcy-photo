<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<?php

#Includes database functions for using MySQL and opens a connection 

require("db-code.php");

# Defaults to first collection if no id sent
if (!@$colid) {
$colid='0';
}

$query = "SELECT * FROM collections WHERE id=$colid AND active='y'";

if(ExecuteQuery($linkdb,$result,$query)){
	while($row=NextRow($result)){
		$title = $row['name'];
	}
}

$query = "SELECT * FROM photo WHERE collection=$colid AND active='y' ORDER BY id ASC";

ExecuteQuery($linkdb,$result,$query);

?>


<title>thumbnails</title>
<link rel="stylesheet" type="text/css" href="photo.css" />
</head>

<body>

<div class="thumbs">
<?php

if ($mysql_result = mysql_query($query, $linkdb)) {
		if (ExecuteQuery($linkdb, $result, $query)) {
			while ($row = NextRow($result)) {
				$width = substr($row['image_size'],7,3);
				$height = substr($row['image_size'],-4,3);
				$orient = "width";
				if($height>$width){$orient="height";}
				print("<div><a href=\"images.php?colid={$colid}&amp;id={$row[0]}\" target=\"image\">\r<img src=\"showthumb.php?id={$row['id']}\" {$orient}=\"150px\" alt=\"{$row['description']}\" border=\"0\" /><br /><span>\r{$row['name']}\r</span></a></div>\r\r");
			}
		}
	}
?>
</div>



</body>

</html>