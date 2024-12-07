<?php

    if(!isset($_SESSION)){
    session_start();
    }

    require_once(__DIR__."/../config/Directories.php"); //to handle folder specific path
    include("../config/DatabaseConnect.php"); //to access database connection

    if(!isset($_SESSION["user_id"])){

        header("location:" .BASE_URL."login.php");
        exit;
    }

    $db = new DatabaseConnect(); //make a new database instance



    if($_SERVER["REQUEST_METHOD"] == "POST"){

        //Retrieve user input
        $productId = htmlspecialchars($_POST["id"]);
        $quantity = htmlspecialchars($_POST["quantity"]);
        $userId = $_SESSION["user_id"];
        
        
        
        

        //Validate user input
        if(trim($productId) == "" || empty($productId)){
            $_SESSION["error"] = "Product Name field is empty";
            
            header("location: ".BASE_URL."views/product/product.php?id=".$productId["id"]);
            exit;
        }
        
        if(trim($quantity) == "" || empty($quantity) || $quantity < 1){
            $_SESSION["error"] = "Product Description field is empty";
                
            header("location: ".BASE_URL."views/product/product.php?id=".$productId["id"]);
            exit;
        }
         
        if(trim($userId) == "" || empty($userId)){
            $_SESSION["error"] = "Category field is empty";
                
            header("location: ".BASE_URL."views/product/product.php?id=".$productId["id"]);
            exit;
        }

        

        //Insert record to database
    try{

        $conn = $db->connectDB();
        $sql = "SELECT * FROM products WHERE products.id = :p_product_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':p_product_id', $productId);
        if(!$stmt->execute()){


        }

        $product = $stmt->fetch();
        
        
        $conn = $db->connectDB();
        $computedPrice = ($product["unit_price"] * $quantity);
        $sql = "INSERT INTO carts (user_id,product_id,quantity,unit_price,total_price, created_at, updated_at) 
        VALUES (:p_user_id,:p_product_id, :p_quantity, :p_unit_price, :p_total_price,NOW(),NOW()
        )";
        
        $stmt = $conn->prepare($sql);
        
        $data = [':p_user_id'        => $userId,
                 ':p_product_id' => $productId,
                 ':p_quantity'         => $quantity,
                 ':p_unit_price'          => $product["unit_price"],
                 ':p_total_price'         => $product ["total_price"]
                
                
                ];

        
        if(!$stmt->execute($data)){
            $_SESSION["error"] = "Failed to add to cart";
            header("location: ".BASE_URL."views/product/product.php?id=".$productId);
            exit;
        }

        $lastId = $productId;
        


        $_SESSION["success"] = "Added to product successfully";
        header("location: ".BASE_URL."views/product/product.php?id=".$productId);
        exit;

    } catch (PDOException $e){
    echo "Connection Failed: " . $e->getMessage();
    $db = null;
    }

}
