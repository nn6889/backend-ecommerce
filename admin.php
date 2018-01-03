<?php
require_once("DB.class.php");
include 'LIB_project1.php';
$title = "Admin";
$db = new DB();
$content="";
$msg = "";
$addForm = "";
$success = "";

$print = "";
$dropdown = $db->getAllProductsAsDropdown();
$print .= "" .$dropdown. "";

$maxrows = $db->getAllRows("select * from products where SalePrice != 0");


if (isset($_POST['additem']) || isset($_POST['removeitem']) || isset($_POST['updateitem']))
	{
		
		if (!isset($_POST['productname']) || strlen($_POST['productname'])){
			echo "<p>You need to enter product name.</p>";
		} else if(!isset($_POST['description']) || strlen($_POST['description']) == 0){
			echo "<p>You need to enter a description.</p>";
		} else if(!isset($_POST['price']) || strlen($_POST['price']) == 0){
			echo "<p>You need to enter a price.</p>";
		} else if(!isset($_POST['quantity']) || strlen($_POST['quantity']) == 0){
			echo "<p>You need to enter a quantity.</p>";
		}  else if(!isset($_POST['saleprice']) || strlen($_POST['saleprice']) == 0){
			echo "<p>You need to enter a saleprice.</p>";
		} 
	}

if(isset($_POST['value']) && isset($_POST['edititem'])){
		$choose = sanitizeString($_POST['value']);
		$query = "select * from products where ProductName='" .$choose. "'";
		$string = $db->displayEditedProducts($query);
		$content .= "" .$string. "";

}

if(isset($_POST['updateitem'])){
   if($_POST['password'] == "password"){
	if($maxrows > 5){
 	$msg .= "You cannot have more than 5 items on sale. ";
 } else
	if($_POST['updateitem']){
		$productname = sanitizeString($_POST['productname']);
		$description = sanitizeString($_POST['description']);
		$price = sanitizeString($_POST['price']);
		$quantity = sanitizeString($_POST['quantity']);
		$imagename = sanitizeString($_POST['imagename']);
		$saleprice = sanitizeString($_POST['saleprice']);

		$num = $db->updateDBS($productname, $description, $price, $saleprice, $quantity, $imagename);
		$msg .= "You updated $num row(s). Your edit has been updated.";
		
	} 
   } else {
   		$msg .= "Sorry, you need admin access to update an item. Please contact admin. ";
   	}

}

if(isset($_POST['removeitem'])){
 if($_POST['password'] == "password"){
	if($_POST['removeitem']){
		//remove item
		$productname = sanitizeString($_POST['productname']);
		$saleprice = sanitizeString($_POST['saleprice']);
		if($saleprice !=0 && $maxrows <=3){
			$msg .= "You cannot have less than 3 items on sale. ";
		} else if($maxrows >= 3){
		$removerow = $db->delete($productname);
		$msg .= "Item removed. ";
	}
	
	}
   }else {
   		$msg .= "Sorry you need admin access to remove an item. ";
   }

}

$addForm .= "Product Name: <input type='text' name='productname' /><br/>
				Description: <textarea rows='4' cols='50' name='description'></textarea><br/>
				Price: <input type='text' name='price' /><br/>
				Quantity: <input type='text' name='quantity' /><br/>
				Sale Price: <input type='text' name='saleprice' /><br/>
				<input type='hidden' name='imagename' /><br/>
				Upload image: <input type='file' name='fileToUpload' /></br>
				
				Admin Password: <input type='text' name='password' /><br/>
				<input type='submit' value='Add Item' name='additem' /><br/>";
				
if(isset($_POST['additem'])  && (isset($_FILES['fileToUpload']))){
 	if($_POST['password'] == "password"){
		if($maxrows >= 5){
 			$success .= "You cannot have more than 5 items on sale. ";
		 } else if($_POST['additem']){
 
				$target_dir = "images/";
				$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
				$uploadOk = 1;
				$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
				
				if (file_exists($target_file)) {
    				 $success .= "Sorry,you need to choose a file. ";
    				 $uploadOk = 0;
				} else if ($_FILES["fileToUpload"]["size"] > 500000){
    				 $succes .=  "Sorry, your file is too large. ";
   					 $uploadOk = 0;
				} else if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"){
   					 $success .=  "Sorry, only JPG, JPEG, PNG & GIF files are allowed. ";
    				 $uploadOk = 0;
				} else if ($uploadOk == 0) {
   					 $success .= "Sorry, your file was not uploaded. ";
				} else {
   					 if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)){ 
    					$success .= "Your file has been uploaded. ";
        				$productname = sanitizeString($_POST['productname']);
						$description = sanitizeString($_POST['description']);
						$price = sanitizeString($_POST['price']);
						$quantity = sanitizeString($_POST['quantity']);
						$imagename = sanitizeString($_FILES['fileToUpload']['name']);
						$saleprice = sanitizeString($_POST['saleprice']);
		
						$num = $db->insert($productname, $description, $price, $quantity, $imagename, $saleprice);
						$success .= "You've inserted a row. ";
       	 
    				} else {
        				$success .= "Sorry, there was an error uploading your file. ";
   					}
				}
		}
   } else {
   		$success .= "Sorry, you need admin access to insert an item. ";
   	}

} 

?>

<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $title; ?></title>
		<link rel="stylesheet" type="text/css" href="stylesheet.css"/>
	</head>
	<body>
		<div id="wrapper">
			
			<nav id="navigation">
				<ul id="nav">
					<li><a href="index.php">Home</a></li>
					<li><a href="cart.php">Cart</a></li>
					<li><a href="admin.php">Admin</a></li>
				</ul>
			</nav>
			
			<div id="content_area">
				<h4>What would you like to edit? </h4>
				<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'>
					<select name="value">
						<?php echo $print; ?>
						
					</select>	
					<input type='submit' value='Edit Item' name='edititem'/>
				</form>
				
				<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'>
					<?php echo $content; ?><br/>
					<?php echo $msg; ?>
				</form>
				
				<h4>Add a product </h4>
				<form action='<?php echo $_SERVER['PHP_SELF']; ?>' enctype='multipart/form-data' method='post'>
					 
					<?php echo $addForm; ?>
					<?php echo $success; ?>
				</form>
				
				
			</div>
		</div>
			
	</body>
</html>
