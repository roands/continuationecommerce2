<?php

if(!isset($_SESSION)){
    session_start();

}

require_once(__DIR__."/../config/Directories.php"); //to handle folder specific path
include("..\config\DatabaseConnect.php"); //to access database connection
if(!isset($_SESSION["user_id"])){
 header("location: ".BASE_URL."login.php");
 exit;   
}

$db = new DatabaseConnect(); //make a new database instance

if($_SERVER["REQUEST_METHOD"] == "POST"){
 // retrieve user input
    $productId = htmlspecialchars($_POST["id"]);
    $quantity  = htmlspecialchars($_POST["quantity"]);
    $userid    = $_SESSION["user_id"];
   
}

    //validate user input
    if(trim($productId) == "" || empty($productId)){
	    $_SESSION["error"] = "Product Id field is empty";

        header("location: ".BASE_URL."views/product/product.php?id=" .$productId    );
        exit;
    }

    if(trim($quantity) == "" || empty($quantity)){
        $_SESSION["error"] = "Quantity field is empty";
                
        header("location: ".BASE_URL."views/product/product.php?id= " .$productId   );
        exit;
    }

    if(trim($userid) == "" || empty($userid)){
        $_SESSION["error"] = "User Id field is empty";
            
        header("location: ".BASE_URL."views/product/product.php?id= " .$productId   );
        exit;
     }

    

 //validation end 
  //update record to database   
try{
  /*  
     $conn = $db->connectDB();
     $sql = "UPDATE products SET products.product_name = :p_product_name,
                    products.product_description = :p_product_description,
                    products.category_id = :p_category_id,
                    products.base_price = :p_base_price,
                    products.stocks = :p_stocks,
                    products.unit_price = :p_unit_price,
                    products.total_price = :p_total_price,
                    products.updated_at = NOW()
                    WHERE products.id = :p_id";

     $stmt = $conn->prepare($sql);
     $data = [':p_product_name'        => $productName,
              ':p_product_description' => $productDesc,
              ':p_category_id'         => $category,
              ':p_base_price'          => $basePrice,
              ':p_stocks'              => $numberOfStocks,
              ':p_unit_price'          => $unitPrice,
              ':p_total_price'         => $totalPrice,
              ':p_id'                  => $productId];
    if(!$stmt->execute($data)){
        $_SESSION["error"] = "Failed to update record";
        header("location: " .BASE_URL."views/admin/products/edit.php");
        exit;
    
    }
    $lastId = $productId;
     */
    

    $_SESSION["success"] = "Added to cart successfully";
    header("location: " .BASE_URL. "views/product/product.php?id=".$productId);
    exit;
} catch (PDOException $e){
    echo "Connection Failed: " . $e->getMessage();
    $db = null;


}
