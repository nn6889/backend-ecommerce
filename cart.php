<?php
	
	require_once("DB.class.php");
	include 'LIB_project1.php';
	$db = new DB();
	
	$title = "Cart";
	$cartOutput = "";
	$query = "DELETE from cart";
	$msg = "";
	
	if(isset($_POST['emptycart'])){
		if($_POST['emptycart']){
			$num = $db->emptyCart($query);
			$msg .= "Cart emptied!";	
		}
	}	
	
	$out = $db->displayCartItems("select * from cart");
	$cartOutput = $out;
	
	$quer1 = "select SUM(price) FROM cart";
	if(isset($_POST['grandtotal'])){
		if($_POST['grandtotal']){
			$num = $db->getGrandTotal($quer1);
			$msg .= "Total: $" .round($num, 2);
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
			<h4>Curent Cart Contents</h4>
			<div id="content_area">
				<?php echo $cartOutput; ?>
				<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method="post">
					<input type='submit' name='emptycart' value='Empty Cart' />
					<input type='submit' value='Get Total' name='grandtotal'/>
				</form>
				<?php echo $msg; ?>
			</div>
			
		</div>	
	</body>
</html>