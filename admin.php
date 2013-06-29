<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Photographs by Daniel Yeo - ADMIN</title>
<link rel="stylesheet" type="text/css" href="photo.css" />
<script language="JavaScript" type="text/javascript" src="form.js"></script>
<script language="JavaScript" type="text/javascript">var checkAction=setInterval("showHidden()",100);</script>
</head>

<body>
<form onsubmit="return validate();" name="admin" action="admin.php" enctype="multipart/form-data" method="post">
<h1>photos by dkcy - control panel</h1>

<?php
#Includes database functions for using MySQL and opens a connection 

require("db-code.php");

# Defines permitted HTML tags
$allowable_tags = "<h1><h2><br><hr><p><a><i><u><blockquote>";

#Do this is a POST action has been submitted
if($HTTP_POST_VARS){
	# List acceptable image types
	$image_types = Array ("image/bmp","image/jpeg","image/pjpeg","image/gif","image/x-png");

	# Check what action is required
	# First see if an edit has been requested
	switch(@$edit){
		case 'collection':
			$ec_name = addslashes(strip_tags($ec_name,$allowable_tags));
			$ec_intro = addslashes(strip_tags($ec_intro,$allowable_tags));
			$query = "UPDATE collections SET name=\"{$ec_name}\",intro=\"{$ec_intro}\",active=\"{$ec_active}\" WHERE id=\"{$id}\"";
			if(ExecuteQuery($linkdb,$result,$query)){
				print("Collection '{$ec_name}' updated");
			} else { print ("Edit failed - ".mysql_error());}
			$thing = "NULL";
			break;
		case 'image':
			$ei_imgname = addslashes(strip_tags($ei_imgname,$allowable_tags));
			$ei_desc = addslashes(strip_tags($ei_desc,$allowable_tags));
			$query = "UPDATE photo SET collection=\"{$ei_colid}\",name=\"{$ei_imgname}\",description=\"{$ei_desc}\",active=\"{$ei_active}\" WHERE id=\"{$id}\"";
			if(ExecuteQuery($linkdb,$result,$query)){
				print("Image '{$ei_imgname}' updated");
			} else { print ("Edit failed - ".mysql_error());}
			$thing = "NULL";
			break;
	}

	# If not, then it's an add
	switch(@$thing){
		# To add a collection
		case 'collection':
			$colname = addslashes(strip_tags($colname,$allowable_tags));
			$intro = addslashes(strip_tags($intro,$allowable_tags));
			$query="INSERT INTO collections (id,name,intro,active) VALUES ('','{$colname}','{$intro}','{$coll_active}')";
				if(ExecuteQuery($linkdb,$result,$query)){
					print("Collection Added!");
				} else {print("Add failed - ".mysql_error());}
			break;

		# To add an image
		case 'image':
			# Put temporary filename into an easier variable
			$uploaded = $HTTP_POST_FILES['image']['tmp_name'];

			if(is_uploaded_file($uploaded)){
				$userfile = addslashes(fread(fopen($uploaded,"rb"),filesize($uploaded)));
				$file_type= $HTTP_POST_FILES['image']['type'];
				$image_size = getimagesize($uploaded);
				$imgname = addslashes(strip_tags($imgname,$allowable_tags));
				$desc = addslashes(strip_tags($desc,$allowable_tags));

# Not recognising functions!
#				$img_stream = imagecreatefromstring ($userfile);
#				$thumb = 
#				$width = $image_size[0];
#				$height = $image_size[1];
#				if($height > $width){
#					imagecopyresampled ($thumb,$img_stream,0,0,0,0,'',150,$width,$height);
#				} else {
#					imagecopyresampled ($thumb,$img_stream,0,0,0,0,150,'',$width,$height);
#				}
#				$thumb = imagejpeg($thumb,'');
#
#				$thumb = exif_thumbnail($uploaded, $width, $height, $type);
#				$thumb_size = "width=\"{$width}\" height=\"{$height}\"";

				if(in_array (strtolower($file_type),$image_types)){
					$query="INSERT INTO photo (id,collection,name,description,image,image_type,image_size,active) VALUES ('','{$colid}','{$imgname}','{$desc}','{$userfile}','{$file_type}','{$image_size[3]}','{$img_active}')";
					if(ExecuteQuery($linkdb,$result,$query)){
						print("Image Added!");
					} else {print("Add failed - ".mysql_error());}
				}
			}

			break;
	}
}

#Do this is if the user has clicked an image/collection name or has clicked remove

if($HTTP_GET_VARS){
	$act = $HTTP_GET_VARS["act"];
	$id = $HTTP_GET_VARS["id"];
	switch($act){
		case 'remcol':
			$query = "DELETE FROM collections WHERE id=$id";
			if(ExecuteQuery($linkdb,$result,$query)){
				print("Collection Deleted");
			} else {print("Delete Failed".mysql_error());}
			break;
		case 'remimg':
			$query = "DELETE FROM photo WHERE id=$id";
			if(ExecuteQuery($linkdb,$result,$query)){
				print("Image Deleted");
			} else {print("Delete Failed".mysql_error());}
			break;
		case 'viewcol':
			$query = "SELECT * FROM collections WHERE id=$id";
			if(ExecuteQuery($linkdb,$result,$query)){
				$row=NextRow($result);
				print("<input type=\"hidden\" name=\"id\" value=\"{$row['id']}\" />");
				print("<input type=\"hidden\" name=\"edit\" value=\"collection\" />");
				print("<table width=\"100%\">\r");
				print("<tr><td><label for=\"ec_name\">Name:</label></td><td><input size=\"50\" id=\"ec_name\" type=\"text\" name=\"ec_name\" value=\"{$row['name']}\" /></td></tr>\r");
				print("<tr><td><label for=\"ec_intro\">Description:</label></td><td><textarea cols=\"40\" id=\"ec_intro\" rows=\"10\" name=\"ec_intro\" />{$row['intro']}</textarea></td></tr>\r");
				print("<tr><td>Active:</td><td>");
				switch($row['active']){
					case 'y':
						$yes = " checked=\"yes\"";
						$no = "";
						break;
					case 'n':
						$yes = "";
						$no = " checked=\"yes\"";
						break;
				}
				print("<input type=\"radio\" id=\"ec_active\" name=\"ec_active\" value=\"y\"{$yes} /><label for=\"ec_active\">Yes</label> | <input type=\"radio\" id=\"ec_inactive\" name=\"ec_active\" value=\"n\"{$no} />");
				print("<label for=\"ec_inactive\">No</label></td></tr>");
				print("<tr><td colspan=\"2\"><button type=\"submit\" name=\"editcoll\">Save Changes</button> | <a href=\"admin.php\">Discard Changes</a></tr>");
				print("</table>");
			}
			print("<hr />");
			break;

		case 'viewimg':
			$query = "SELECT * FROM photo WHERE id=$id";
			if(ExecuteQuery($linkdb,$result,$query)){
				$row=NextRow($result);
				switch($row['active']){
					case 'y':
						$yes = " checked=\"yes\"";
						$no = "";
						break;
					case 'n':
						$yes = "";
						$no = " checked=\"yes\"";
						break;
				}
				$collection = $row['collection'];
				$title = $row['name'];
				$img_size = $row['image_size'];

				print("<input type=\"hidden\" name=\"id\" value=\"{$row['id']}\" />");
				print("<input type=\"hidden\" name=\"edit\" value=\"image\" />");
				
				print("<table width=\"100%\">\r");
				print("<tr><td><label for=\"ei_coll\">Collection:</label></td>\r");
				print("<td><select id=\"ei_coll\" name=\"ei_colid\">\r");

				$query2 = "SELECT * FROM collections ORDER BY name ASC";
				if(ExecuteQuery($linkdb,$result2,$query2)){
					while($row2=NextRow($result2)){
						print("<option ");
						if($row2['id']==$collection){print("selected=\"yes\"");}
						print("value=\"{$row2[0]}\">{$row2[1]}</option>\r");
					}
				}

				print("</select></td>\r");
				print("<td rowspan=\"5\"><img src=\"showblob.php?id={$id}\" alt=\"{$title}\" height=\"150px\" /></td></tr>");
				print("<tr><td><label for=\"ei_img\">Image Name:</label></td><td><input size=\"50\" id=\"ei_img\" type=\"text\" name=\"ei_imgname\" value=\"{$title}\" /></td></tr>\r");
				print("<tr><td><label for=\"ei_desc\">Description:</label></td><td><textarea cols=\"40\" rows=\"5\" id=\"ei_desc\" type=\"text\" name=\"ei_desc\">{$row['description']}</textarea></td></tr>\r");
				print("<tr><td>Active:</td><td><input type=\"radio\" id=\"ei_active\" name=\"ei_active\" value=\"y\"{$yes} /><label for=\"ei_active\">Yes</label> | <input type=\"radio\" id=\"ei_inactive\" name=\"ei_active\" value=\"n\"{$no} /><label for=\"ei_inactive\">No</label></td></tr>\r");
				print("<tr><td colspan=\"2\"><button type=\"submit\" name=\"editimage\">Save Changes</button> | <a href=\"admin.php\">Discard Changes</a></td></tr>");
				print("</table>\r");
			}
			print("<hr />");
			break;
	}
}
?>

<p>
<input id="coll" type="radio" name="thing" value="collection" /><label for="coll">Add Collection</label> |
<input id="img" type="radio" name="thing" value="image" /><label for="img">Add Image</label> | Or click below to edit/delete images and collections
</p>
<!--****************************************************************************************************************-->
<div style="display:none;" id="addImage">
<hr />

<table width="100%">
<tr>
<td><label for="ai_coll">Collection:</label></td>
<td>
<select id="ai_coll" name="colid">
<option selected="selected">Select a collection to add this image to ...</option>
<option>--------------------------------------------</option>
<?php
$query = "SELECT * FROM collections ORDER BY name ASC";

if(ExecuteQuery($linkdb,$result,$query)){
	while($row=NextRow($result)){
		$printname = htmlspecialchars($row[1]);
		print("<option value=\"{$row[0]}\">{$printname}</option>\r");
	}
}
?>
</select></td></tr>
<tr><td><label for="ai_img">Name:</label></td><td><input size="50" id="ai_img" type="text" name="imgname" /></td></tr>
<tr><td><label for="ai_desc">Description:</label></td><td><textarea cols="40" rows="5" id="ai_desc" name="desc"></textarea></td></tr>
<tr><td><label for="ai_image">Image:</label></td><td><input type="file" id="ai_image" name="image" /></td></tr>
<tr><td>Active:</td><td><input type="radio" id="img_active" name="img_active" checked="checked" value="y" /><label for="img_active">Yes</label> | <input type="radio" id="img_inactive" name="img_active" value="n" /><label for="img_inactive">No</label></td></tr>
<tr><td colspan="2"><button type="submit" name="addimage">Add Image</button></td></tr>
</table>

</div>

<!--****************************************************************************************************************-->
<div style="display:none;" id="addColl">
<hr />

<table width="100%">
<tr><td><label for="coll_name">Name:</label></td><td><input size="50" id="coll_name" type="text" name="colname" /></td></tr>
<tr><td><label for="coll_intro">Description:</label></td><td><textarea cols="40" rows="10" id="coll_intro"  name="intro">&lt;p id="copyright"&gt;All images © Daniel Yeo 2003&lt;/p&gt;</textarea></td></tr>
<tr><td>Active:</td><td><input type="radio" id="coll_active" name="coll_active" checked="checked" value="y" /><label for="coll_active">Yes</label> | <input type="radio" id="coll_inactive" name="coll_active" value="n" /><label for="coll_inactive">No</label></td></tr>
<tr><td colspan="2"><button type="submit" name="addcoll">Add Collection</button></td></tr>
</table>

</div>
</form>

<hr />

<h2>current collections and images</h2>
<ul class="collection">
<?php

$query = "SELECT * FROM collections ORDER BY id ASC";

if(ExecuteQuery($linkdb,$result,$query)){
	while($row=NextRow($result)){
		switch($row['active']){
			case 'y':
				$state='active';
				break;
			case 'n':
				$state='inactive';
				break;
		}
		$delname = addslashes($row['name']);

# Escapes special characters (e.g. changes & to &amp;)
		$delname = str_replace("&","&amp;",$delname);
		$printname = htmlspecialchars($row['name']);

		print("<li class=\"{$state}\"><a href=\"admin.php?act=viewcol&amp;id={$row['id']}\">{$printname}</a> <a class=\"remove\"href=\"admin.php?act=remcol&amp;id={$row['id']}\" onclick=\"return verify('{$delname}');\">remove</a>\r");
		print("<ol class=\"images\">\r");

		$query2 = "SELECT * FROM photo WHERE collection={$row['id']} ORDER BY id ASC";
		if(ExecuteQuery($linkdb,$result2,$query2)){
			while($row2=NextRow($result2)){
				switch($row2['active']){
					case 'y':
						$state='active';
						break;
					case 'n':
						$state='inactive';
						break;
				}
				$delname = addslashes($row2['name']);
# Escapes special characters (e.g. changes & to &amp;)
				$delname = str_replace("&","&amp;",$delname);
				$printname = htmlspecialchars($row2['name']);
				print("<li class=\"{$state}\"><a href=\"admin.php?act=viewimg&amp;id={$row2['id']}\">{$printname}</a> | <a class=\"remove\"href=\"admin.php?act=remimg&amp;id={$row2['id']}\" onclick=\"return verify('{$delname}');\">remove</a></li>\r");
			}
		}
		print("</ol>\r");
		print("</li>\r");
	}
}

?>
</ul>

</body>

</html>