<?php

/**
 *Template Name: My Cart 
 */

get_header();

?>
<?php
session_start();
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<?php
global $wpdb;
$ID = $_SESSION["userID"];

if(!isset($_SESSION["userID"])){
    header("Location: https://laixinyi.azurewebsites.net/login/");
    exit();
}

function deleteCartItem($productid,$deletecartid, $wpdb)
{
    $wpdb->delete(
        'CARTITEM',
        array('PRODUCTID' => $productid, 'CARTID' => $deletecartid)
    );
}
function updateCart($prodID,$newQty,$cartid, $wpdb)
{
    $wpdb->update(
        'CARTITEM',
        array('QUANTITY' => $newQty),
        array('PRODUCTID' => $prodID, 'CARTID' => $cartid)
    );
}
$cartidquery = $wpdb->prepare("SELECT CARTID  
                              FROM SHOPPINGCART
                              WHERE USERID = %s", array($ID));

$cartid =  $wpdb->get_results($cartidquery)[0]->CARTID;



    $res1 = $wpdb ->prepare( "SELECT C.CARTID, P.PRODUCTID, P.PRODUCTNAME, P.PRODUCTPRICE, C.QUANTITY, P.IMAGELINK, P.SIZE, CA.TYPE
                              FROM  CARTITEM C, PRODUCT P, CATEGORY CA
                              WHERE C.PRODUCTID = P.PRODUCTID AND
                                    P.CATEGORYID = CA.CATEGORYID AND
                                    C.CARTID = %s
                              ORDER BY DATETIME DESC", array($cartid)); 
    $result = $wpdb->get_results($res1);


    $queryTotal = $wpdb ->prepare( "SELECT SUM(P.PRODUCTPRICE * C.QUANTITY) as total, COUNT(C.CARTID) AS COUNTER
                                    FROM  CARTITEM C, PRODUCT P, CATEGORY CA
                                    WHERE C.PRODUCTID = P.PRODUCTID AND
                                        P.CATEGORYID = CA.CATEGORYID AND
                                        C.CARTID = %s", array($cartid));
$total = $wpdb->get_results($queryTotal);
$numOfItems = $wpdb->get_results($queryTotal);


if (isset($_GET['productid']) && isset($_GET['deletecartid'])) {
    deleteCartItem($_GET['productid'], $_GET['deletecartid'], $wpdb);
} else if (isset($_GET['addcart'])) {

    // updateCart($_GET['cart'], $wpdb);
}else if (isset ($_GET['prodid']) && isset ($_GET['newQty']) && isset ($_GET['cartid']) ){
    updateCart($_GET['prodid'], $_GET['newQty'],$_GET['cartid'], $wpdb);
    
}


echo '<div style="font-weight:bold;font-size:36px;width:100%;text-align:center"> My Cart </div>';
echo '<body div style="width: 100%";>
<div style="width: 100%;display: flex;height: 100vh;padding: 20px;justify-content: space-between;">
<div id = "leftbox" style="width: 68%;padding: 20px;display: flex;flex-direction: column;height: 100%;box-shadow: 1px 3px 6px;border-radius: 5px;overflow-y:auto; scroll-behavior: smooth;">
<div id = "counter" value="' . $numOfItems[0]->COUNTER . '" style="font-size: 20px;font-weight: bold;margin-bottom: 20px;">Cart Items (' . $numOfItems[0]->COUNTER . ')</div>';
// for each here
$totalamount = 0;
foreach ($result as $row) {
    $prodID = $row->PRODUCTID;
    $url = "https://laixinyi.azurewebsites.net/my-cart/?productid=" . $prodID . "&deletecartid=" . $cartid;
    
    $addCart = "https://laixinyi.azurewebsites.net/my-cart/?addcart=" . $prodID;
    $minusCart = "https://laixinyi.azurewebsites.net/my-cart/?minuscart=" . $prodID;
    $shipping = 5.00;
    $totalamount = $total[0]->total + $shipping;
    $totalamount = number_format($totalamount, 2, '.', '');
   
    echo
    '<div class="itemBox" value = '.$prodID.' style="width: 100%;display: flex;height: 250px;border-bottom-style: solid;padding-bottom: 20px;border-width: 0.5px;margin-bottom:20px">
        <div id="prodImage" value = '.$cartid.'   style="width: 25%;height: 100%;background-color: black;display: flex;align-items: center;justify-content: center;background-color:white;">
        <img src="' . $row->IMAGELINK . '"alt="image"  style="width: 100%;height: 100%;border-radius: 10px;">
        </div>
    <div id="details" style="width: 75%;;padding: 1px 0px 10px 10px;display: flex;flex-wrap: wrap;">
    <div style="width: 70%;display: flex;flex-direction: column;">
        <div id="prodName" style="margin-bottom: 10px;font-size: 25px;font-weight: bold;width: 100%;height: 50%;">
        ' . $row->PRODUCTNAME . '
        </div>
        <div style="display: flex;margin-bottom: 10px;">
            <div style="color: grey;">Category :</div>
            <div style="margin-left: 10px;">'  . $row->TYPE . '</div>
        </div>
        <div style="display: flex;margin-bottom: 10px;">
            <div style="color: grey;">SIZE :</div>
            <div style="margin-left: 10px;">'  . $row->SIZE . '</div>
        </div>
    </div>
  <div style="width: 30%;height: 60%;display: flex;flex-direction: column; align-items: center;">
   <div class="qtyadjust"  style="display: flex;margin-top: 30px;align-items: center;padding: 10px;justify-content: space-between;width: 80%;border-style: solid;border-width: 0.1px;border-radius: 5px;border-color: gray;">
        <div class="minusQuantity" value='.$minusCart.' style="width:30%;height: 25px;width: 25px;display: flex;align-items: center;justify-content: center; cursor:pointer">
        <img src="https://laixinyi.azurewebsites.net/wp-content/uploads/2021/09/minus.png" alt="">
        </div>
        <div class="productqty" value=' . $row->QUANTITY . ' style="width:30%;text-align: center;display: flex;align-items: center;justify-content: center;font-size: 25px;">
        ' . $row->QUANTITY . '
        </div>
        <div class="addQuantity" value='.$addCart.'  style="width:30%;display: flex;align-items: center;justify-content: center;height: 25px;width: 25px;cursor:pointer">
            <img src="https://laixinyi.azurewebsites.net/wp-content/uploads/2021/09/add.png" alt="">
        
        </div>
    </div>
    </div>
        
        <div id = "removeBox" style="display: flex;margin-top: 20px;width: 100%;align-items: center;">

            <div style="color: grey;display: flex;height: 20px;width: 20px;align-items: center;">
                <img src="https://laixinyi.azurewebsites.net/wp-content/uploads/2021/09/bin-with-lid.png" alt="dusbin"
                style="max-height: 100%;max-width: 100%;">
            </div>
            <div class="dustbin"  style="display: flex;justify-content: space-between;width: 100%;align-items:center">
                <div class ="deleteItem" value ='.$url.'   style="margin-left: 10px;display: flex;align-items: flex-end;font-size: 18px;font-weight: 300;cursor:pointer;" >
                REMOVE ITEM
                </div>
                <div class = "productPrice" value = '.  $row->PRODUCTPRICE .'style="display: flex;justify-content: flex-end;font-size: 18px;padding-right: 5px;font-weight: 300;">RM ' . $row->PRODUCTPRICE . '</div>
            </div>
        </div>
    </div>
    </div>';
}


echo '</div>
<div style="width: 30%;padding: 20px;display: flex;height: 40%;box-shadow: 1px 3px 6px;border-radius: 5px;flex-direction: column;justify-content: space-between;">

    <div style="width: 100%;display: flex;flex-direction: column;">
        <div style="font-size: 20px;">The total amount of</div>
        <div style="margin-top: 20px;font-size: 16px;display: flex;justify-content: space-between;width: 100%;font-weight: 300;">
        Temporary amount
          <div id = "temporarytotal" value = '.$total[0]->total.'>' . number_format($total[0]->total, 2, '.', '') . '</div>
        </div>
        <div style="padding-bottom: 10px;border-bottom-style: solid;border-width: 1px;border-color: gray;font-size: 16px;margin-top: 10px;display: flex;justify-content: space-between;font-weight: 300;">
        Shipping
          <div id = "shippingFees">' . number_format($shipping, 2, '.', '') . '</div>
        </div>
        <div style="display: flex;font-size: 16px;justify-content: space-between;margin-top: 10px;">
            <div>Total amount</div>
            <div id = "totalamount" value = '. $totalamount.'>RM ' . number_format($totalamount, 2, '.', '') . '</div>
        </div>
        </div>' ?>
<?php 
        if($numOfItems[0]->COUNTER == 0){
            echo '
            <div style="background-color: grey;color: white;padding: 15px;font-weight: bold;display: flex;justify-content: center;border-radius: 5px;-webkit-user-select: none;" >
                GO TO CHECK OUT
            </div>
            ';
        }else
        {
            echo '
            <div class="checkoutbtn" style="color: white;padding: 15px;font-weight: bold;display: flex;justify-content: center;border-radius: 5px;" >
                GO TO CHECK OUT
            </div>
            ';
        }
    echo'   
    </div>
</div>';

echo '</body>';
?>

<style>
    .removeBox {
        cursor: pointer;
    }

    .checkoutbtn {
        cursor: pointer;
        -webkit-user-select: none;
        background-color:#212121 ;
    }

    .checkoutbtn:hover {
       background-color: #ff8c00;  
    }
/*  */
    /* .leftbox::-webkit-scrollbar {
        display: none;
    } */
</style>;

<script type="text/javascript">

$(".checkoutbtn").click(function() {
    // console.log($(this).parent().find('counter'));
    findCounter();
})

function findCounter(){
    var counterdiv =  document.getElementById('counter');
    var count =counterdiv.getAttribute('value');
    if(count !=0){
        window.location.href = "https://laixinyi.azurewebsites.net/checkout/";
    }
}

$(".minusQuantity").click(function() {
        event.preventDefault();
        this.getAttribute("value");
        // console.log(this.getAttribute("value"));
        // console.log($(this).parent().find('#productqty').html());
        var index = $(this).closest('.itemBox').index() - 1
        var qty = parseInt($(this).parent().find('.productqty').html(), 10) ;
        var price = $(this).closest('.itemBox').find('.productPrice').html();
        price = price.substr(3);
        if(qty != 1){
            minusQty(qty, index);
            reduceAmount(price);
        }
    });
    
    function minusQty(qty, index){
        var updateqty = qty- 1;
        document.getElementsByClassName('productqty')[index].setAttribute('value',updateqty);
        document.getElementsByClassName('productqty')[index].innerText = updateqty;
        var pid = document.getElementsByClassName('itemBox')[index].getAttribute('value');
        var cartid = document.getElementById('prodImage').getAttribute('value')
       
        updatedCart(pid, updateqty, cartid);
    }

    $(".addQuantity").click(function() {
        event.preventDefault();
        var index = $(this).closest('.itemBox').index() - 1
        var qty =parseInt($(this).parent().find('.productqty').html(), 10) ;
        var price = $(this).closest('.itemBox').find('.productPrice').html();
        price = price.substr(3);
        
        
        // console.log(index)
        // console.log(price);
        addQty(qty, index);
        addAmount(price);
        
    });
    function addQty(qty, index){
        var updateqty = qty + 1;
        // console.log();
        
        var pid = document.getElementsByClassName('itemBox')[index].getAttribute('value');
        document.getElementsByClassName('productqty')[index].setAttribute('value',updateqty );
        // console.log(updateqty);
        document.getElementsByClassName('productqty')[index].innerText = updateqty ;
        var cartid = document.getElementById('prodImage').getAttribute('value')
       
        updatedCart(pid, updateqty, cartid);
    } 

    function updatedCart(id, qty, cartid){
        var url = "https://laixinyi.azurewebsites.net/my-cart/?prodid=" + id + "&newQty=" + qty + "&cartid=" + cartid;
        $.ajax({
            method: "POST",
            url: url,
        });
    }

    $(".deleteItem").click(function() {
        event.preventDefault();
        $(this).closest(".itemBox").fadeOut(500);
        reduceItem();
        deleteItem(this.getAttribute('value'));
        var price = $(this).closest('.itemBox').find(".productPrice").html();
        var qty = parseFloat($(this).closest('.itemBox').find('.productqty').html());
        // console.log($(this).closest('.itemBox').find(".productPrice"));
        price = price.substr(3);
        price = parseFloat(price) * qty;
        
        // console.log(price);
        reduceAmount(price);
    });

    function deleteItem(url) {
        // console.log(url);
        $.ajax({
            method: "POST",
            url: url
        });
    }

    function reduceAmount(price){
        var temp = document.getElementById('temporarytotal');
        var total = document.getElementById('totalamount');
       
        var tempamount = parseFloat(temp.getAttribute('value'));
        var totalamount = parseFloat(total.getAttribute('value'));
        var paraprice = parseFloat(price);
        tempamount = tempamount - price;
        totalamount = totalamount - price;
        temp.setAttribute('value', tempamount);
        total.setAttribute('value', totalamount);
        temp.innerText = tempamount.toFixed(2);

        total.innerText = "RM " +  totalamount.toFixed(2);
        

        var countBox = document.getElementById('counter');
        var counter = countBox.getAttribute('value');
        // console.log(counter);
        if(counter == 0){
            var fees = 0.0;
            total.innerText = "RM " + fees.toFixed(2);
            total.setAttribute('value', fees);
        }
    }
    function addAmount(price){
        var temp = document.getElementById('temporarytotal');
        var total = document.getElementById('totalamount');
       
        var tempamount = parseFloat(temp.getAttribute('value'));
        var totalamount = parseFloat(total.getAttribute('value'));
        var paraprice = parseFloat(price);
        tempamount = tempamount + paraprice;
        totalamount = totalamount + paraprice;
        // console.log(tempamount);
        // console.log(totalamount);
        temp.setAttribute('value', tempamount);
        total.setAttribute('value', totalamount);
        temp.innerText = tempamount.toFixed(2);
        total.innerText = "RM " +  totalamount.toFixed(2);

    }


    function reduceItem() {
        var countBox = document.getElementById('counter');
        var counter = countBox.getAttribute('value');
        counter = counter - 1;
        document.getElementById('counter').setAttribute('value', counter);
        document.getElementById('counter').innerText = "Cart Items(" + counter + ")";
        var checkoutbtn = document.getElementsByClassName('checkoutbtn')[0];
        
        if(counter == 0){
            var fees = 0;
            checkoutbtn.style.backgroundColor = "grey";
            checkoutbtn.style.cursor = "default";
            document.getElementById('shippingFees').innerText = fees.toFixed(2);
           
        }
    }
</script>

<?php
get_footer();
?>