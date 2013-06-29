<?php

# PHP script to retrieve BLOBs from database as images

require("db-code.php");

$query = "SELECT * FROM photo WHERE id={$id}";
if(ExecuteQuery($linkdb,$result,$query)){
	while($row=NextRow($result)){
		$image_type=$row['image_type'];
		$image=$row['image'];
		Header("Content-type:$image_type");
		print $image;
	}
}
?>