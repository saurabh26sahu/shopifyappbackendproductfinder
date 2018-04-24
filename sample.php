<?php
//Titile Insertion working here.... 
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
		//Mysql Connection Starts
		$servername = "localhost";
		$username = "root";
		$password = "";
		$dbname = "shopifyapp";

     	// Create connection
		$conn = mysqli_connect($servername, $username, $password, $dbname);
		// Check connection
		if (!$conn) {
    	die("Connection failed: " . mysqli_connect_error());
		}

		//Data Insertion Starts
		if(is_array($product_title)){
    	foreach ($product_title as $row) {      
        $query ="INSERT INTO products_data (title) VALUES ( '". $row."')";
        mysqli_query($conn, $query);
    	}
	}

  	if (mysqli_query($conn, $query)) 
	{
    echo "App Data Inserted Successfully";
    } 

	else {
    echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }

	mysqli_close($conn);

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

