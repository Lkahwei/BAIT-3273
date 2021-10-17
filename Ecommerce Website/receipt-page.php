<?php

/**
 *Template Name: Receipt
 */

get_header();
?>
<?php
session_start();
?>

<?php
global $wpdb;
$userid = $_SESSION["userID"];
if (!isset($_SESSION["userID"])) {
    header("Location: https://laixinyi.azurewebsites.net/login/");
    exit();
}


$q1 = $wpdb->prepare("SELECT USERNAME
                         FROM user
                         WHERE USERID = %s", $userid);
$username = $wpdb->get_results($q1)[0]->USERNAME;

$q2 = $wpdb->prepare("SELECT MAX(ORDERID) as ORDERID
                         FROM ordertable 
                         WHERE USERID = %s", $userid);
$orderid = $wpdb->get_results($q2)[0]->ORDERID;

$q3 = $wpdb->prepare("SELECT AMOUNT, PAYMENTTYPE, ORDERDATE
                         FROM ordertable 
                         WHERE ORDERID = %s", $orderid);
$orderdetails = $wpdb->get_results($q3);

$q4 = $wpdb->prepare("SELECT PRODUCTNAME, SIZE, QUANTITY, QUANTITY * PRODUCTPRICE as PRICE
                       FROM orderdetails O , product P
                       WHERE O.PRODUCTID = P.PRODUCTID AND
                             ORDERID = %s", $orderid);

$productdetails = $wpdb->get_results($q4);
$subtotal = $orderdetails[0]->AMOUNT - 5;
$subtotal = number_format($subtotal, 2);



echo '<div
    style="width: 100vw;height:100%;min-height:100vh;;display: flex;align-items: center;flex-direction: column;">
    <div style="font-weight:bold;font-size:36px;width:100%;text-align:center"> Receipt </div>
    <div
        style="width:60%;height: 60%;box-shadow: 1px 3px 5px;border-radius: 2px;display: flex;flex-direction: column;padding: 10px;">
        <div style="display: flex;flex-direction: column;border-bottom:2px dashed black;margin-bottom:10px;padding-bottom:5px;">
            <label><b>Order ID:</b> ' . $orderid . ' </label>
            <label><b>Username:</b> ' . $username . ' </label>
            <label><b>Transaction Date:</b> ' . $orderdetails[0]->ORDERDATE . ' </label>
            <label><b>Payment Method:</b> ' . $orderdetails[0]->PAYMENTTYPE . ' </label>
            <div style="display: flex;width: 100%;justify-content:space-between;margin-top: 20px;">
                <label><b>Product Name</b> </label>
                <label><b>Size </b></label>
                <label><b>Quantity </b> </label>
                <label><b>Price (RM)</b> </label>
            </div>

        </div>
        
        ' ?>
<?php
echo '<table width=100% class="mytable" style="border:hidden;">';
foreach ($productdetails as $row) {
    echo '
                    <tr style="border:hidden;">
                        <td style="border:hidden;" width="36%"> ' . $row->PRODUCTNAME . '</td>
                        <td style="border:hidden;" width="24%">' . $row->SIZE . '</td>
                        <td style="border:hidden;" width="16%">' . $row->QUANTITY . '</td>
                        <td style="border:hidden;text-align: right;" width="24%"  >' . number_format($row->PRICE, 2)  . '</td>
                    </tr>
                  ';
}
echo '</table>';
echo '
        <div style="width:100%;justify-content: space-between;display: flex;border-top:2px dashed black;padding-top:5px;">
            <div style="font-weight:bold;font-size:16px;text-align: center;">Sub-Total</div>

            <div style="font-weight:bold;font-size:16px;text-align: center;">' . $subtotal . '</div>
        </div>
        <div style="width:100%;justify-content: space-between;display: flex;margin-bottom:5px;">
            <div style="font-weight:bold;font-size:16px;text-align: center;">Delivery fee</div>

            <div style="font-weight:bold;font-size:16px;text-align: center;">  5.00</div>
        </div>
        <div style="width:100%;justify-content: space-between;display: flex;border-bottom:2px dashed black;border-top:2px dashed black;padding-top:5px;padding-bottom:5px;">
            <div style="font-weight:bold;font-size:16px;text-align: center;">Total</div>

            <div style="font-weight:bold;font-size:16px;text-align: center;">RM &nbsp;&nbsp;&nbsp;' . $orderdetails[0]->AMOUNT . '</div>
        </div>
    
        <div style="width: 100%;display: flex;justify-content: flex-end;">
        <a id="parentHomeBtn" href="https://laixinyi.azurewebsites.net/" style="width:100%;display:flex;justify-content:flex-end;">
            <div id="backtohome" style="width: 30%;color: white;font-weight: bold;padding: 10px;border-radius: 3px;text-align: center;cursor: pointer;margin-top: 20px;">
                Back To Home
            </div>
        </a>
        </div>
        


    </div>


</div>'

?>
<style>
    table,
    tr {
        border: hidden;
    }

    td,
    th {
        border: hidden;
    }

    .mytable {
        border-collapse: collapse;
    }

    #backtohome {
        background-color: #212121;
    }

    #parentHomeBtn :hover {
        background-color: #ff8c00;
    }

    /* And this to your table's `td` elements. */
    .mytable td {
        padding: 0;
        margin: 0;
    }
</style>