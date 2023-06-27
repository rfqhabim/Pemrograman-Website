<?php include("../connections.php");
session_start();
if (!$_SESSION['login']==1 && !isset($_SESSION['userId'])) {
    header('location: ../login/login_user.php');
	exit;
}
if (isset($_GET['action'])) {
    $id =  $_GET['id'];
    // $quantity = intval($_POST["quantity"]);
    $query = "SELECT * FROM product where ID_PRODUCT = :id";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $productByCode = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $codeProduct = strval($productByCode[0]["ID_PRODUCT"])."-".$productByCode[0]["NAME_PRODUCT"];

    $itemArray = array($codeProduct => array(
        'name' => $productByCode[0]["NAME_PRODUCT"],
        'id' => $productByCode[0]["ID_PRODUCT"],
        'quantity' => 1,
        'price' => $productByCode[0]["PRICE_PRODUCT"],
        'image' => $productByCode[0]["GAMBAR_PRODUCT"]
    ));
    if ($_GET['action']=='plus') {
        if (!empty($_SESSION["cart_item"])) {
            if (in_array($codeProduct, array_keys($_SESSION["cart_item"]))) {
                foreach ($_SESSION["cart_item"] as $k => $v) {
                    if ($codeProduct == $k) {
                        if (empty($_SESSION["cart_item"][$k]["quantity"])) {
                            $_SESSION["cart_item"][$k]["quantity"] = 0;
                        }
                        $_SESSION["cart_item"][$k]["quantity"] += 1;
                    }
                }
            } else {
                $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"], $itemArray);
            }
        } else {
            $_SESSION["cart_item"] = $itemArray;
        }

    }elseif ($_GET['action']=='min') {
        if (!empty($_SESSION["cart_item"])) {
            if (in_array($codeProduct, array_keys($_SESSION["cart_item"]))) {
                foreach ($_SESSION["cart_item"] as $k => $v) {
                    if ($codeProduct == $k) {
                        if (empty($_SESSION["cart_item"][$k]["quantity"])) {
                            $_SESSION["cart_item"][$k]["quantity"] = 0;
                        }
                        $_SESSION["cart_item"][$k]["quantity"] -= 1;
                    }
                }
            } else {
                $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"], $itemArray);
            }
        } else {
            $_SESSION["cart_item"] = $itemArray;
        }
    } elseif ($_GET['action']=='remove') {
        if(!empty($_SESSION["cart_item"])) {
			foreach($_SESSION["cart_item"] as $k => $v) {
					if($_GET["codeProduct"] == $k)
						unset($_SESSION["cart_item"][$k]);				
					if(empty($_SESSION["cart_item"]))
						unset($_SESSION["cart_item"]);
			}
		}
    }

    if ($_GET['link']=='keranjang') {
        header('location: keranjang.php');
        exit;
    }if ($_GET['link']=='product') {
        header('location: ../view-more-product/all.php');
        exit;
    }
}
var_dump($_SESSION);

