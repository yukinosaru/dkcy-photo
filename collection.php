<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN"        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php

#Includes database functions for using MySQL and opens a connection 

require("db-code.php");

# Defaults to first collection if no id sent
if (!@$colid) {
$colid='1';
}


$query = "SELECT * FROM collections WHERE id=$colid";

if(ExecuteQuery($linkdb,$result,$query)){
	while($row=NextRow($result)){
		$title = $row[1];
	}
}

?>

<title><? print($title); ?></title>
</head>

<frameset cols="200px,*">
<frame name="thumbs" src="thumbs.php?colid=<?php print($colid); ?>" noresize="noresize" scrolling="auto" frameborder="0" />

<frame name="image" src="images.php?colid=<?php print($colid); ?>" noresize="noresize" scrolling="auto" frameborder="0" />
</frameset>

<!--
<iframe width="200px" height="100%" name="thumbs" src="thumbs.php?colid=<?php print($colid); ?>" scrolling="auto" frameborder="0" />
<iframe name="image" src="images.php?colid=<?php print($colid); ?>" scrolling="auto" frameborder="0" />
-->

</html>