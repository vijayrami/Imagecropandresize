<?php
$image='';
$err='';
$imgname = null;
if(isset($_POST['submit'])){
	//error variable to hold your error message 
	$err="";
	$path = "uploads/";
	//alled image format will be used for filter	
	$allowed_formats = array("jpg", "png", "gif", "bmp");
	$imgname = $_FILES['img']['name'];
	$tmpname = $_FILES['img']['tmp_name'];
	$size = $_FILES['img']['size'];
	//validate image
	if(!$imgname){
		$err="<strong>Oh snap!</strong>Please select image..!";
		return false;
	}
	if($size > (1024*1024)){
		$err="<strong>Oh snap!</strong>File Size is too large..!";
	}
	list($name, $ext) = explode(".", $imgname);
	if(!in_array($ext,$allowed_formats)){
			$err="<strong>Oh snap!</strong>Invalid file formats only use jpg,png,gif";
			return false;					
	}
	if($ext=="jpg" || $ext=="jpeg" ){
		$src = imagecreatefromjpeg($tmpname);
	}
	else if($ext=="png"){
		$src = imagecreatefrompng($tmpname);
	}
	else {
		$src = imagecreatefromgif($tmpname);
	}
	list($width,$height)=getimagesize($tmpname);
	if($width > 400){
		$newwidth=399;
		$newheight=($height/$width)*$newwidth;
		$tmp=imagecreatetruecolor($newwidth,$newheight);
		imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
		$image = $path.$imgname;
		imagejpeg($tmp,$path.$imgname,100);
		move_uploaded_file($image,$path.$imgname);
	}
	else{
		if(move_uploaded_file($tmpname,$path.$imgname)){
			$image="uploads/".$imgname;
		}
		else{
			$err="<strong>Oh snap!</strong>failed";
		}
	}	
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Image Crop using php and javascript</title>
	<link rel="stylesheet" href="lib/imgareaselect/css/imgareaselect-default.css" />
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
		<div class="container wrap">
			<div class="left">
				<?php 
				//if image uploaded this section will shown
					if($image){
						echo "<h2>Select an area on image</h2><img style='' src='".$image."' id=\"imgc\" style='width:100%' >";
					}
				?>
			</div>
			<div class="right">
					<?php 
					//if image uploaded this section will shown
					if($image){	
						//echo '<div>' ;
						echo '<h2>Preview</h2>' ;
						echo '<div class="frame">' ;
						echo '<div id="preview">';
						echo '<img src="'.$image.'" >'; 
						echo '</div></div></div>';
						echo "<div id='output'></div>";
						echo "<img src='' id='cropedimg' />";
						echo '<button id="cropbtn">Crop</button>';
					}
				?>
			</div>	
				<?php
				//if any error while uploading
				if($err){
					echo '<div class="alert alert-error">'.$err.'</div>';
				}
				?>	
				
				<form id="imgcrop" method="post" enctype="multipart/form-data" style="<?php echo $imgname != null  ? 'display:none;' : ''?>">
				<input type="file" name="img" id="img" />
				<input type="hidden" name="imgName" id="imgName" value="<?php echo($imgname) ?>" />
				<button name="submit">Submit</button>
				</form>
				
				
			<div style="clear:both"></div>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script type="text/javascript" src="lib/imgareaselect/jquery.imgareaselect.js"></script>
  <script type="text/javascript" src="js/process.js"></script>
</body>
</html>

