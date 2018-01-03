<?php
//session_start();
require_once("DB.class.php");
include 'LIB_project1.php';
$db = new DB();
$title = "Home";
$contentregular = "";
$showing = "";

$stringSale = $db->getAllProducts("select * from products where SalePrice != 0");
$contentsale = $stringSale; 

if(!isset($_GET) || empty($_GET)){
	$string = $db->getAllProducts("select * from products where SalePrice=0 limit 5");
	$contentregular = $string;
	$showing = " &nbsp;&nbsp; &nbsp;&nbsp;Showing items 1-5";
} else if($_GET['page']==1){
	$string = $db->getAllProducts("select * from products where SalePrice=0 limit 5");
	$contentregular = $string;
	$showing = " &nbsp;&nbsp; &nbsp;&nbsp;Showing items 1-5";
} else if($_GET['page']==2){
	$string = $db->getAllProducts("select * from products where SalePrice=0 limit 5 offset 5");
	$contentregular = $string;
	$showing = " &nbsp;&nbsp; &nbsp;&nbsp;Showing items 5-10";
} else if($_GET['page']==3){
	$string = $db->getAllProducts("select * from products where SalePrice=0 limit 5 offset 10");
	$contentregular = $string;
	$showing = " &nbsp;&nbsp; &nbsp;&nbsp;Showing items 10-15";
}



//$contentregular = $stringRegular;


if(isset($_POST['addtocart'])){
  if($_POST['addtocart']){
  
    $productname = sanitizeString($_POST['p1']);
    $desc = sanitizeString($_POST['p2']);
    $price = sanitizeString($_POST['p3']);
    $quantity = sanitizeString($_POST['p4']);
    $updatedQuantity = $quantity - 1;
    $saleprice = sanitizeString($_POST['p5']);
    if($saleprice == 0){
    	$num = $db->insertCart($productname, $desc, $price);
    	$returnQ = $db->updateQuantity($updatedQuantity, $productname);

    	
    } else if($saleprice != 0){
    	$num = $db->insertCart($productname, $desc, $saleprice);
    	$returnQ = $db->updateQuantity($updatedQuantity, $productname);
  
    }
  
    echo "$productname added to cart";
  
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
			<div id="banner">
			
			</div>
			
			<nav id="navigation">
				<ul id="nav">
					<li><a href="index.php">Home</a></li>
					<li><a href="cart.php">Cart</a></li>
					<li><a href="admin.php">Admin</a></li>
				</ul>
			</nav>
			
			<div id="content_area">
			<section><h3>Items on Sale </h3></section>

				<?php echo $contentsale; ?>
			
			<section><h3>Regular Items </h3></section>
			
		
			</div>
					<?php echo $contentregular; ?>
	<div class="pagination">
			<a href='index.php?page=1'>1</a>
			<a href='index.php?page=2'>2</a>
			<a href='index.php?page=3'>3</a>
			<a href='index.php?page=4'>></a>
			<?php echo $showing; ?>
		</div>
		</div>	
	</body>
</html>


