<?php
/**
 *Template Name: New Order Details Page
 */
 
 get_header();
?>
<?php
	session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<style>
		body {
            font-family: Arial, Helvetica, sans-serif;
		}

		.container {
			margin-bottom: 50px;
		}

        .bigTitle{
            padding: 0.75rem 1.25rem;
        }

		.card {
			position: relative;
			display: -webkit-box;
			display: -ms-flexbox;
			display: flex;
			-webkit-box-orient: vertical;
			-webkit-box-direction: normal;
			-ms-flex-direction: column;
			flex-direction: column;
			min-width: 0;
			word-wrap: break-word;
			background-color: #fff;
			background-clip: border-box;
			border: 1px solid rgba(0, 0, 0, 0.1);
			border-radius: 0.10rem
		}

		.card-header:first-child {
			border-radius: calc(0.37rem - 1px) calc(0.37rem - 1px) 0 0
		}

		.card-header {
			padding: 0.75rem 1.25rem;
			margin-bottom: 0;
			background-color: #fff;
			border-bottom: 1px solid rgba(0, 0, 0, 0.1)
		}

        .card-body {
			padding: 0.75rem 1.25rem;
			margin-bottom: 0;
			background-color: #fff;
			border-bottom: 1px solid rgba(0, 0, 0, 0.1)
		}

        .track {
            position: relative;
            background-color: #ddd;
            height: 7px;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            margin-bottom: 60px;
            margin-top: 50px
        }

        .track .step {
            -webkit-box-flex: 1;
            -ms-flex-positive: 1;
            flex-grow: 1;
            width: 25%;
            margin-top: -18px;
            text-align: center;
            position: relative
        }

        .track .step.active:before {
            background: #FF5722
        }

        .track .step::before {
            height: 7px;
            position: absolute;
            content: "";
            width: 100%;
            left: 0;
            top: 18px
        }

        .track .step.active .icon {
            background: #ee5435;
            color: #fff
        }

        .track .icon {
            display: inline-block;
            width: 40px;
            height: 40px;
            line-height: 40px;
            position: relative;
            border-radius: 100%;
            background: #ddd
        }

        .track .step.active .text {
            font-weight: 400;
            color: #000
        }

        .track .text {
            display: block;
            margin-top: 7px
        }

		.itemside {
			position: relative;
			display: -webkit-box;
			display: -ms-flexbox;
			display: flex;
			width: 100%
		}

		.itemside .aside {
			position: relative;
			-ms-flex-negative: 0;
			flex-shrink: 0
		}

		.img-sm {
			width: 80px;
			height: 80px;
			padding: 7px
		}

		ul.row, ul.row-sm {
			list-style: none;
			padding: 0
		}

		.itemside .info {
			padding-left: 15px;
			padding-right: 7px
		}

		.itemside .title {
			display: block;
			margin-bottom: 5px;
			color: #212529
		}

		p {
			margin-top: 0;
			margin-bottom: 1rem
		}

        .product_image{
            width: 30%;
            height: 30%;
        }

        .product{
            padding: 0;
            margin-left: 30px;
        }

        .cardProd {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            max-width: 300px;
            margin: auto;
            text-align: center;
            font-family: arial;
            }

        .price {
            color: grey;
            font-size: 18px;
            }

        .column_order {
		box-sizing: border-box;
		float: left;
		width: 100%;
		padding: 10px;
		border: 1px solid #aaa;
	}
	</style>
</head>
<body>

    <?php 
        $orderID = $_GET['orderID'];
        $userID = $_SESSION["userID"];
        global $wpdb;
        $query = $wpdb->prepare( "SELECT orderID, recipientName, userName, orderDate, shipDate, amount, paymentType FROM orderTable o, delivery d, user u where u.userID = %s AND o.orderID = %s AND u.userID = o.userID AND o.deliveryID = d.deliveryID order by orderDate DESC", $userID, $orderID);
		$query_prod = $wpdb->prepare( "SELECT productName, productPrice, imageLink, type, quantity FROM orderTable o, orderdetails od, product p, category c WHERE o.orderID = %s AND o.orderID = od.orderID AND od.productID = p.productID AND p.categoryID = c.categoryID", $orderID);
        $result_order = $wpdb->get_results($query);
        $result_product = $wpdb->get_results($query_prod);

        foreach ($result_order as $row) {
            $orderID = $row->orderID;
            $recipientName = $row->recipientName;
            $userName = $row->userName;
            $orderDate = $row->orderDate;
            $shipDate = $row->shipDate;
            $amount = $row->amount;
            $paymentType = $row->paymentType;
        }
    ?>
<div style="width:100%;"class="bigTitleContainer">
    <div style="font-size:30px; text-align:center;" class="bigTitle">My Order Details
        <form method="POST">
            <input style="float:right; margin-bottom: 10px" class="bigTitle" type="submit" class="button" name="viewOrder" value="View All Orders" />
        </form>  
    </div>
    
</div>

<?php 
if (isset($_POST["viewOrder"])){
    $url = "https://laixinyi.azurewebsites.net/order/";
    redirect($url);
}
?>


   <div class="container">
    <article class="card">
        <header class="card-header"> My Orders / Tracking </header>
        <div class="card-body">
            <h6>Order ID: <?php echo $orderID?></h6>
            <article class="card">
                <div class="card-body row">
                    <div class="col"> <strong>Order Date:</strong> <br><?php echo $orderDate?></div>
                    <div class="col"> <strong>Estimated Shipped Date:</strong> <br><?php echo $shipDate?></div>
                    <div class="col"> <strong>Username:</strong> <br><?php echo $userName?></div>
                    <div class="col"> <strong>Payment Type:</strong> <br><?php echo $paymentType ?></div>
                </div>
            </article>
            <div class="track">
                <div class="step active"> <span class="icon"> <i class="fa fa-check"></i> </span> <span class="text">Order confirmed</span> </div>
                <div class="step active"> <span class="icon"> <i class="fa fa-user"></i> </span> <span class="text"> Picked by courier</span> </div>
                <div class="step active"> <span class="icon"> <i class="fa fa-truck"></i> </span> <span class="text"> On the way </span> </div>
                <div class="step active"> <span class="icon"> <i class="fa fa-box"></i> </span> <span class="text">Ready for pickup</span> </div>
            </div>
            <hr>
                <?php
                    $counter = 1; 
                    $total = 0.00;
                    foreach ($result_product as $row_prod) {
                        $productName = $row_prod->productName;
                        $productPrice = $row_prod->productPrice;
                        $type = $row_prod->type;
                        $quantity = $row_prod->quantity;
                        $image = $row_prod->imageLink;
                        $name = str_replace(' ', '%20', $productName);
                        $url = 'https://laixinyi.azurewebsites.net/product-womenclothes/product-details/?counter='.$name;
                    ?>
                    
                    <a href="<?php echo $url?>">
                        <div class="column_order">
                            <div><?php echo "Product $counter"; ?></div>
							<div style="display: flex;">
                            <?php echo "<img style=\"max-width:100px;\" src='" . $image . "' alt='error'>"; ?>
                                <div style="margin-top:30px; margin-left:20px">
                                    <div><b>Product Name: </b><?php echo $productName; ?></div>
                                    <div><b>Quantity Purchased: </b><?php echo  $quantity;?></div>
                                    <div><b>Total: </b>RM <?php $subtotal = $productPrice * $quantity;$total += $subtotal;echo number_format((float)$subtotal, 2, '.', '');?></div>
                                </div>
                        </div>
                        </div>
                    </a>
                <?php
                    $counter +=1;
                    }
                ?>
                <div style="float:right; font-size:22px;" class='totalAmount'>Subtotal: RM <b><?php echo "".number_format((float)$total, 2, '.', '');?></b></div>

        </div>
    </article>
            
    </div>
</body>
</html>