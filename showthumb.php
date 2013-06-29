<?php

# PHP script to retrieve BLOBs from database as images

require("db-code.php");

$query = "SELECT * FROM photo WHERE id={$id}";
if(ExecuteQuery($linkdb,$result,$query)){
	while($row=NextRow($result)){
		$image_type=$row['image_type'];
		$image=$row['image'];

		$original = imagecreatefromstring($image);
		$width = substr($row['image_size'],7,3);
		$height = substr($row['image_size'],-4,3);
		if($height>$width){
			$thumb_width= floor((150*$width)/$height);
			$thumb = imagecreate($thumb_width,150);
			imagecopyresized($thumb,$original,0,0,0,0,$thumb_width,150, $width,$height);
		}
		else{
			$thumb_height= floor((150*$height)/$width);
			$thumb = imagecreate(150,$thumb_height);
			imagecopyresized($thumb,$original,0,0,0,0,150,$thumb_height,$width,$height);
		}
		Header("Content-type:$image_type");
		imagejpeg($thumb);
	}
}
?>

