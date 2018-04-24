<?php 
	session_start();

	require __DIR__.'/vendor/autoload.php';
	use phpish\shopify;

	require __DIR__.'/conf.php';

	$shopify = shopify\client($_SESSION['shop'], SHOPIFY_APP_API_KEY, $_SESSION['oauth_token']);

	try
	{
		# Making an API request can throw an exception
		$products = $shopify('GET /admin/products.json', array('published_status'=>'published'));
		$product_title = array_column($products, 'title');
		$title_title = implode(',', $product_title);
		$product_handle = array_column($products, 'handle');
		$title_handle = implode(',', $product_handle);
		$product_tags = array_column($products, 'tags');
		$title_tags = implode(',', $product_tags);
		$product_type = array_column($products, 'product_type');
		$title_type = implode(',', $product_type);
	
		$servername = "localhost";
		$username = "root";
		$password = "";
		$dbname = "shopifyapp";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

if(is_array($product_title)){
	    $product_title = array_column($products, 'title');
		$title_title = implode(',', $product_title);
		$product_handle = array_column($products, 'handle');
		//$title_handle = implode(',', $product_handle);
		$product_tags = array_column($products, 'tags');
		//$title_tags = implode(',', $product_tags);
		$product_type = array_column($products, 'product_type');
		//$title_type = implode(',', $product_type);

    $DataArr = array($product_title);
   // print_r($DataArr);

    foreach($product_title as $row){
    	echo "<pre>";
    	//print_r($row);
       // echo $product_title = mysql_real_escape_string($products[$row]['title']);
       // $product_handle= mysql_real_escape_string($products[$row]['product_type']);
       //  $product_tags= mysql_real_escape_string($products[$row]['handle']);
    //$DataArr[] = "('$row')";
    
//$data = implode(',', $DataArr);
    	$title_title = implode(',', $product_title);
   //echo $data;

    
        
    }
    $sql = "INSERT INTO products_data (title) values ($title_title)";
    mysqli_query($conn, $sql); 
}


if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

		// echo "<pre>";
		// print_r($products);

	}
	catch (shopify\ApiException $e)
	{
		# HTTP status code was >= 400 or response contained the key 'errors'
		echo $e;
		print_r($e->getRequest());
		print_r($e->getResponse());
	}
	catch (shopify\CurlException $e)
	{
		# cURL error
		echo $e;
		print_r($e->getRequest());
		print_r($e->getResponse());
	}
