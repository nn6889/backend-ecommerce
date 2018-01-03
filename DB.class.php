<?php


class DB {
	private $connection;
	
	function __construct(){
		$this->connection = new mysqli($_SERVER['DB_SERVER'], 
									   $_SERVER['DB_USER'],
									   $_SERVER['DB_PASSWORD'], 
									   $_SERVER['DB']);
		
		if ($this->connection->connect_error) {
			echo "Connection failed. ".mysqli_connect_error();
			die();
		}
	} //constructor
	
	
/* 
	getAllProductsAsDropdown gets all of the productnames of the products into a dropdown list
	and appends it to the Admin page for the user to select which product to edit or remove 
*/	
	function getAllProductsAsDropdown(){
		$data = "";
		
		if ($stmt = $this->connection->prepare("select * from products")) {
			$stmt->execute();
			$stmt->store_result(); // how many rows
			$stmt->bind_result($productname, $description, $price, $quantity,
							   $imagename, $saleprice);
			
			if ($stmt->num_rows > 0) {
			$data= "<option value='null'> Choose</option>";
				while ($row=$stmt->fetch()) {
				
				$data.= "<option value='" .$productname. "'>" .$productname. "</option>";
				
				}
			//$data .= "</select>";
			}
			return $data;
		}
	}

/*
	When user selects a product to edit or remove, displayEditedProducts returns a form 
	that dynamically inputs products information into textfields.  
*/	
	function displayEditedProducts($query){
		
		$data = array();
		
		if ($stmt = $this->connection->prepare($query)) {
			$stmt->execute();
			$stmt->store_result(); // how many rows
			$stmt->bind_result($productname, $description, $price, $quantity,
							   $imagename, $saleprice);
			
			if ($stmt->num_rows > 0) {
				while ($stmt->fetch()) {
					$data[] = array('productname'=>$productname,
									'description'=>$description,
									'price'=>$price,
									'quantity'=>$quantity,
									'imagename'=>$imagename,
									'saleprice'=>$saleprice);
				}
				
			} //num rows > 0 
			
		} //if $stmt
		
		$result = "";
		foreach($data as $key=>$products){
			$result .= "</br>Product Name: <input type='text' name='productname' value={$products['productname']} /></br>
				Description: <textarea rows='4' cols='50' name='description'> {$products['description']}</textarea></br>
				Price: <input type='text' name='price' value= {$products['price']} /></br>
				Quantity: <input type='text' name='quantity' value={$products['quantity']} /></br>
				Sale Price: <input type='text' name='saleprice' value={$products['saleprice']} /></br>
				Image Name: <input type='text' name='imagename' value={$products['imagename']} /></br>
				Admin Password: <input type='text' name='password' /></br>
				<input type='submit' value='Update Item' name='updateitem' />
				<input type='submit' value='Remove Item' name='removeitem' /></br>";
		}
		
		return $result;
	
	}

/*
	getAllProducts returns the products name, description, price, image, quantity, and saleprice for each product
	in the database. When clicked on the addtocart button, it takes the information for each item clicked	
	and adds it to the cart. 
*/	
	function getAllProducts($q) {
		$data = array();
		
		if ($stmt = $this->connection->prepare($q)) {
			$stmt->execute();
			$stmt->store_result(); // how many rows
			$stmt->bind_result($productname, $description, $price, $quantity,
							   $imagename, $saleprice);
			
			if ($stmt->num_rows > 0) {
				while ($stmt->fetch()) {
					$data[] = array('productname'=>$productname,
									'description'=>$description,
									'price'=>$price,
									'quantity'=>$quantity,
									'imagename'=>$imagename,
									'saleprice'=>$saleprice);
				}
				
			} 
			
		} 
		
		$result = "";
		foreach($data as $key=>$products){
		
		$result .= "
					<form method='post'>
						<table class='coffeeTable'>
							<tr>
								<th><img src='images/{$products['imagename']}'></th>
								<th>Product Name: </th>
								<td><input type='hidden' name='p1' value='{$products['productname']}' />{$products['productname']}</td>
							</tr>
							
							<tr>
								<th>Description: </th>
								<td><input type='hidden' name='p2' value='{$products['description']}' />{$products['description']}</td>
							</tr>
							
							<tr>
								<th>Price: </th>
								<td><input type='hidden' name='p3' value='{$products['price']}' />{$products['price']}</td>
							</tr>
							
							<tr>
								<th>Quantity Left: </th>
								<td><input type='hidden' name='p4' value='{$products['quantity']}' />{$products['quantity']}</td>
							</tr>
							
							
							<tr>
								<th>Sale Price: </th>
								<td><input type='hidden' name='p5' value='{$products['saleprice']}' />{$products['saleprice']}</td>
							</tr>
							
							
						</table>
						<input type='submit' value='Add to Cart' name='addtocart'/>
					</form>	  
							
							
                    ";
		
		}
		return $result;
	} 
	

	
/*
	This function takes the product name, description, price, quantity, image name and saleprice, and inserts any new items
	to the database. 
*/	
	function insert($productname, $description, $price, $quantity, $imagename, $saleprice){
		$queryString = "insert into products (ProductName, Description, Price, Quantity, ImageName, SalePrice) values
						(?,?,?,?,?,?)";
		$insertId = -1; //if -1 returns, query is unsuccessful
		
		if($stmt = $this->connection->prepare($queryString)){
			$stmt->bind_param("ssdisd",$productname, $description, $price, $quantity, $imagename, $saleprice);
			$stmt->execute();
			$stmt->store_result();
			$insertId = $stmt->insert_id;
		}
		
		return $insertId;	
	} //insert
	
	

/*
	This function takes the product name, description, price, saleprice, quantity, and imagename and updates any changes made
	to each product and updates it to the database. 
*/	
	function updateDBS($productname, $description, $price, $saleprice, $quantity, $imagename){
		$queryString = "update products set ProductName = ?, Description = ?, Price = ?, SalePrice = ?, Quantity = ? where ImageName = ? "; 
		$insertId = -1;
		if($stmt = $this->connection->prepare($queryString)){
			$stmt->bind_param("ssddis", $productname, $description, $price, $saleprice, $quantity, $imagename);
			$stmt->execute();
			$stmt->store_result();
			$insertId = $stmt->affected_rows;
		}
		
		return $insertId;
	}


/* 
	This function updates the quantity on each product when it has been added to the cart.
*/
	function updateQuantity($quantity, $productname){
		$queryString = "update products set Quantity = ? where ProductName = ? "; 
		$updateId = -1;
		if($stmt = $this->connection->prepare($queryString)){
			$stmt->bind_param("is", $quantity, $productname);
			$stmt->execute();
			$stmt->store_result();
			$updateId = $stmt->affected_rows;
		}
		
		return $updateId;
	}
	
	
/* 
	This function takes in the product name as the parameter and removes the item off of the database.
*/
	function delete($productname){
		$queryString = "delete from products where ProductName = ?";
		$numRows = 0;
		
		if($stmt = $this->connection->prepare($queryString)){
			$stmt->bind_param("s", $productname);
			$stmt->execute();
			$stmt->store_result();
			$rumRows = $stmt->affected_rows;
		}
		
		return $rumRows;
	} 


/* 
	This function empties the cart by removing all items in the cart database.
*/
	function emptyCart($queryString){
		
		$numRows = 0;
		
		if($stmt = $this->connection->prepare($queryString)){
			//$stmt->bind_param("s", $productname);
			$stmt->execute();
			$stmt->store_result();
			$rumRows = $stmt->affected_rows;
		}
		
		
	} 
	
	
/* 
	This function takes the product name, description and price and inserts the products information
	into the cart where user can see their grand total.
*/	
	function insertCart($productname, $description, $price){
		$queryString = "insert into cart (ProductName, Description, Price) values
						(?,?,?)";
		$insertId = -1; //if -1 returns, query is unsuccessful
		
		if($stmt = $this->connection->prepare($queryString)){
			$stmt->bind_param("ssd",$productname, $description, $price);
			$stmt->execute();
			$stmt->store_result();
			$insertId = $stmt->insert_id;
		}
		
		return $insertId;	
	} //insert
	
	
	
/* 
	This function returns a form where each item added to the cart is being displayed along
	with the products name, description and price.
*/		
	function displayCartItems($q) {
		$data = array();
		
		if ($stmt = $this->connection->prepare($q)) {
			$stmt->execute();
			$stmt->store_result(); // how many rows
			$stmt->bind_result($productname, $description, $price);
			
			if ($stmt->num_rows > 0) {
				while ($stmt->fetch()) {
					$data[] = array('productname'=>$productname,
									'description'=>$description,
									'price'=>$price
									);
				}
				
			} 
			
		} 
		
		//return $data;
		$result = "";
		$total = 0;
		foreach($data as $key=>$products){
		$total += $price;
		$result .= "
					<form method='post'>	
						<table>
							<tr>
								
								<th>Product Name: </th>
								<td>{$products['productname']}</td>
							</tr>
							
							<tr>
								<th>Description: </th>
								<td>{$products['description']}</td>
							</tr>
							
							<tr>
								<th>Price: </th>
								<td>{$products['price']}</td>
							</tr>
							
						</table>
						
					</form>  
						<hr>	
							
                    ";
               
		
		}
		return $result;
	} 
	
	
	
/* 
	This function returns the total number of the rows in the database.
*/		
function getAllRows($query){
		//$numRows = 0;
		
		if ($stmt = $this->connection->prepare($query)) {
			
			$stmt->execute();
			$stmt->store_result();
		//	$stmt->bind_result($productname);
			$count = $stmt->num_rows;
			//echo $numRows;
		}
		return $count;
	}



/* 
	This function returns the grand total of the items in the cart.
*/		
function getGrandTotal($query){
		
		$data = "";
		if ($stmt = $this->connection->prepare($query)) {
			
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($price);
			if ($stmt->num_rows > 0) {
				while($row=$stmt->fetch()){
					$data .= "" .$price;
				}
			}
			return $data;
		}
		
	}

	

} //class



?>