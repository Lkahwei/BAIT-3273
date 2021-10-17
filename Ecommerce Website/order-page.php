<?php
/**
 *Template Name: Order Page
 */
 
 get_header();
?>
<?php
	session_start();
?>
<!DOCTYPE html>
<html>
<body>
<div style="margin: auto;" class = "main">
	<div style="margin-bottom:30px;text-align:center;font-size:30px;" class='bigOrderTitle'>
		All Orders
	</div>
	<?php 
		$userID = $_SESSION["userID"];
		global $wpdb;
			$query = $wpdb->prepare( "SELECT orderID, recipientName, userName, orderDate, shipDate, amount, paymentType FROM ordertable o, delivery d, user u where u.userID = %s AND u.userID = o.userID AND o.deliveryID = d.deliveryID order by orderDate DESC", $userID);
			$result_order = $wpdb->get_results($query);
			<?php
/**
 *Template Name: Order Page
 */
 
 get_header();
?>
<?php
	session_start();
?>
<!DOCTYPE html>
<html>
<body>
<div style="margin: auto;" class = "main">
	<div style="margin-bottom:30px;text-align:center;font-size:30px;font-weight:bold;" class='bigOrderTitle'>
		All Orders
	</div>
	<?php 
		$userID = $_SESSION["userID"];
		global $wpdb;
			$query = $wpdb->prepare( "SELECT orderID, recipientName, userName, orderDate, shipDate, amount, paymentType FROM ordertable o, delivery d, user u where u.userID = %s AND u.userID = o.userID AND o.deliveryID = d.deliveryID order by orderDate DESC", $userID);
			$result_order = $wpdb->get_results($query);
			
			?>

			<table>
				<tr>
					<th>Order ID</th>
					<th>Recipient Name</th>
					<th>Order Date</th>
					<th>Ship Date</th>
					<th>Amount(RM)</th>
					<th>Payment Type</th>
				</tr>

				<?php 
					foreach ($result_order as $row) {
						$orderID = $row->orderID;
						$recipientName = $row->recipientName;
						$userName = $row->userName;
						$orderDate = $row->orderDate;
						$shipDate = $row->shipDate;
						$amount = $row->amount;
						$paymentType = $row->paymentType;
						$url = "https://laixinyi.azurewebsites.net/order-details/?orderID=".$orderID;?>
						<tr><td><a href="<?php echo $url;?>"><?php echo $orderID;?></a></td><td><a href="<?php echo $url;?>"><?php echo $recipientName;?></a></td><td><a href="<?php echo $url;?>"><?php echo $orderDate;?></a></td><td><a href="<?php echo $url;?>"><?php echo $shipDate;?></a></td><td><a href="<?php echo $url;?>"><?php echo $amount;?></a></td><td><a href="<?php echo $url;?>"><?php echo $paymentType;?></a></td></tr>
				<?php	
					}
				?>
			</table>
</div>
</body>
</html>

<?php
get_footer();
			?>

			<table>
				<tr>
					<th>Order ID</th>
					<th>recipientName</th>
					<th>orderDate</th>
					<th>shipDate</th>
					<th>amount(RM)</th>
					<th>paymentType</th>
				</tr>

				<?php 
					foreach ($result_order as $row) {
						$orderID = $row->orderID;
						$recipientName = $row->recipientName;
						$userName = $row->userName;
						$orderDate = $row->orderDate;
						$shipDate = $row->shipDate;
						$amount = $row->amount;
						$paymentType = $row->paymentType;
						$url = "https://laixinyi.azurewebsites.net/order-details/?orderID=".$orderID;?>
						<tr><td><a href="<?php echo $url;?>"><?php echo $orderID;?></a></td><td><a href="<?php echo $url;?>"><?php echo $recipientName;?></a></td><td><a href="<?php echo $url;?>"><?php echo $orderDate;?></a></td><td><a href="<?php echo $url;?>"><?php echo $shipDate;?></a></td><td><a href="<?php echo $url;?>"><?php echo $amount;?></a></td><td><a href="<?php echo $url;?>"><?php echo $paymentType;?></a></td></tr>
				<?php	
					}
				?>
			</table>
</div>
</body>
</html>

<?php
get_footer();