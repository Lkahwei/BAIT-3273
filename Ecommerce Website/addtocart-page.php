<?php

/**
 *Template Name: ADD TO CART 
 */

get_header();
?>
<?php
	session_start();
?>

<?php
    $pName = $_GET['counter'];
    $userID = $_SESSION["userID"];
    // $userID = 'U001';
    global $wpdb;

    

    if(isset($_GET['itemid']) && isset($_GET['quantity'])) {

        $productID = $_GET['itemid'];
        $quantity = $_GET['quantity']; 
        console.log($productID);

        $table = 'cartitem';

        $cartidquery = $wpdb -> prepare("SELECT CARTID
                                         FROM SHOPPINGCART 
                                         WHERE USERID = %s", $userID); 
        
        $cartid = $wpdb->get_results($cartidquery);
        
        $cartID_USER = $cartid[0]->CARTID ; 
        
      
        $allcartitem =  $wpdb -> get_results("SELECT CARTID, PRODUCTID, QUANTITY
                                              FROM CARTITEM"); 

        $productqty = $wpdb -> prepare("SELECT PRODUCTID, QUANTITY
                                        FROM CARTITEM 
                                        WHERE CARTID = %s AND PRODUCTID = %s", $cartID_USER, $productID);

        $cartproductqty = $wpdb->get_results($productqty);
        
        foreach($allcartitem as $row){
             
            if($row->CARTID == $cartID_USER && $row->PRODUCTID == $productID ){

                $cartID_productqty = $cartproductqty[0] -> QUANTITY ; 
                $updateQTY = $cartID_productqty + $quantity;
                echo $updateQTY;
                $wpdb->update($table, array('QUANTITY' => $updateQTY),  array('CARTID' => $cartID_USER, 'PRODUCTID' => $productID));
                // $wpdb->show_errors();

            }
        }

        date_default_timezone_set("Asia/Kuala_Lumpur");
        
        $sysdate = date("Y-m-d H:i:s");  
     
        $insertData = array('CARTID' => $cartID_USER, 'PRODUCTID' => $productID, 'QUANTITY' => $quantity, 'DATETIME' => $sysdate);
        $wpdb->insert($table, $insertData);
        $wpdb->show_errors();
    }


    $query = $wpdb->prepare("SELECT DISTINCT P.PRODUCTID, P.PRODUCTNAME, C.TYPE, P.PRODUCTSTATUS, P.SIZE, P.PRODUCTPRICE, P.IMAGELINK, P.QUANTITYAVAILABLE
                             FROM PRODUCT P, CATEGORY C  
                             WHERE P.CATEGORYID = C.CATEGORYID AND
                             P.PRODUCTNAME = %s", $pName);
    $productDetails = $wpdb->get_results($query);	

    $query2 = $wpdb->prepare("SELECT DISTINCT P.PRODUCTNAME, C.TYPE, P.IMAGELINK
                              FROM PRODUCT P, CATEGORY C 
                              WHERE P.CATEGORYID = C.CATEGORYID AND
                              P.PRODUCTNAME = %s", $pName);
    $product = $wpdb->get_results($query2);	

    
    echo '<div style="font-weight:bold;font-size:36px;width:100%;text-align:center"> Product Details </div>';
    echo '<body div style="width: 100%;";>'; 
  
    
    echo '
    <body>
        <div class="itemBox" style="display: flex; width: 100%; height: 100%;">
            <div style="width: 35%;height: 100%;display: flex;align-items: center;justify-content: center;background-color:white;">
                <img src=" '.$product[0]->IMAGELINK.' " alt="image">
            </div>
            <div class = "detailBox" style="display: flex ; margin-left: 50px; width: 60%; height: 100%;flex-direction: column;">
                
                <div id= "productName" value = "'.$product[0] ->PRODUCTNAME.'" style="display: flex; font-size:30px; text-align:center; font-weight: bold; margin-top: 30px; margin-bottom: 15px;">
                     '.$product[0] ->PRODUCTNAME.' 
                </div>

                <div style="display: flex; margin-bottom: 15px; font-size:20px;">
                    <b>Category: </b> &nbsp '.$product[0] ->TYPE.' 
                </div>

                <div style="display: flex; margin-bottom: 15px; font-size:20px;" > 
                    <b>Size: </b>  &nbsp
                    <div id = "productSize" value = '.$productDetails[0]->PRODUCTID.'>
                         '.$productDetails[0] ->SIZE.' 
                    </div>   
                </div> 

                <div style="display: flex; margin-bottom: 15px; font-size:20px;" > 
                    <b>Price: </b>  &nbsp RM  &nbsp
                    <div id = "productPrice"">
                    '.$productDetails[0] ->PRODUCTPRICE.' 
                    </div>   
                </div> 


                <div style="margin-bottom: 15px; align-items: baseline; font-size:20px;"> 
                    <label><b>Variation:  </b> </label>

                    <div style="display: flex; align-items: center; justify-content: space-around;width:100%">

                        <div  id="buttonsize" style="display:flex;justify-content:space-between;width:100%">';?>
                        <?php 
                        foreach($productDetails as $row){
              
                            echo '<div style="width:20%;background-color:#ff8c00;color:white;padding:10px;text-align:center;cursor:pointer;border-radius:3px;" class="size" name="buttonL" value='.$row->PRODUCTPRICE. "," .$row->QUANTITYAVAILABLE. "," .$row->PRODUCTID.'>'.$row->SIZE.' </div>';
                            $prodID = $row->PRODUCTID;
                            $addCart = "https://laixinyi.azurewebsites.net/product-details/?addcart=" . $prodID;
                            $minusCart = "https://laixinyi.azurewebsites.net/product-details/?minuscart=" . $prodID;
                        }
       
                     echo   '</div>

                    </div>
                </div>

                <div style="margin-bottom: 10px; align-items: baseline; font-size:20px;"> 
                    <label><b>Quantity:  </b></label>
                    
                </div>

                <div style="width: 100%;height: 100%;display: flex;">
                    
                    <div class="qtyadjust"  style="display: flex;align-items: center;padding: 10px;justify-content: space-between;width: 25%; border-style: solid;border-width: 0.1px;border-radius: 5px;border-color: gray; margin-right:40px">
                        <div class="minusQuantity" value='.$minusCart.' style="width:30%;height: 25px;width: 25px;display: flex;align-items: center;justify-content: center; cursor:pointer">
                            <img src="https://laixinyi.azurewebsites.net/wp-content/uploads/2021/09/minus.png" alt="minus">
                        </div>
                        <div class="productqty" value=1 style="width:30%;text-align: center;display: flex;align-items: center;justify-content: center;font-size: 25px;">
                        1
                        </div>
                        <div class="addQuantity" value='.$addCart.'  style="width:30%;display: flex;align-items: center;justify-content: center;height: 25px;width: 25px;cursor:pointer">
                            <img src="https://laixinyi.azurewebsites.net/wp-content/uploads/2021/09/add.png" alt="add">
                        </div>
                        
                    </div> 

                    <div style="display: flex;align-items: center; margin-left=5%; font-size:18px; font-family: Arial, Helvetica, sans-serif;">
                        
                        <b> Quantity Available: </b> &nbsp

                        <div id = "productStockQTY">
                                '.$productDetails[0] ->QUANTITYAVAILABLE.'
                        </div> 
                         

                    </div>
                   
                        
                   
                </div> '?> 
<!-- <a href="https://laixinyi.azurewebsites.net/login/" >  -->
                <?php
                
                if (!$userID){

                    echo'
                    <div class="button">
                        
                        <div class="nologin_Cart"><a style="color:white;" href="https://laixinyi.azurewebsites.net/login/"> Add To Cart </a></div>
                       
                     </div>' ;
                   
                }
                else {
                    echo'  
                    <div class="button">
                        <div id="addtocartBtn" >Add To Cart</div>
                    </div>' ;
                }

                echo' 

            </div>

            
        </div>
      
    </body>';?>

<style>
 .button{
  display:flex;  
  border-radius: 4px;
  background-color:#212121;
  text-align: center;
  font-size: 28px;
  transition: all 0.5s;
  cursor: pointer;
  justify-content:center;
  margin-top:20px;
  float: right;
  width: 35%;
  margin-left: 65%;
  color : white; 
  padding: 15px;
}

.button div {
  cursor: pointer;
  display: inline-block;
  position: relative;
  transition: 0.5s;
}

.button div:after {
  position: absolute;
  opacity: 0;
  top: 0;
  right: -20px;
  transition: 0.5s;
}

.button:hover div {
  padding-right: 25px;
}

#addtocartBtn:hover div:after {
  opacity: 1;
  right: 0;
}

#nologin_Cart:hover div:after {
  opacity: 1;
  right: 0;
}

    
</style>
    


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
   $('.size').click(function(){
       var price = String(this.getAttribute("value")).split(",")[0];
       var qty = String(this.getAttribute("value")).split(",")[1];
       var pickedSize = $(this).html() ;
       var prodID = String(this.getAttribute("value")).split(",")[2];
        // document.getElementByID("productSize").innerText = this.getAttribute("value");
        console.log(pickedSize);
        $(this).closest(".detailBox").find('#productSize').html(pickedSize);
        $(this).closest(".detailBox").find('#productPrice').html(price);
        $(this).closest(".detailBox").find('#productStockQTY').html(qty);

       setValueProdID(prodID);
        
   })

   function setValueProdID(prodID){
       document.getElementById("productSize").setAttribute('value', prodID);
   }
   $('#addtocartBtn').click(function(){
       
       passItemIntoDB();
    //    console.log('pickedSize');
   })

   function passItemIntoDB() {
    var prodName = document.getElementById("productName").innerText;
    var prodID = document.getElementById("productSize").getAttribute('value');
    var size = document.getElementById("productSize").innerText;
    var qty = document.getElementsByClassName("productqty")[0].innerText;
    var prodName = document.getElementById("productName").getAttribute('value');

    console.log(prodID, qty);
    var url = "https://laixinyi.azurewebsites.net/product-womenclothes/product-details/?itemid=" + prodID.trim() + "&quantity=" + qty;
   
    console.log(url);
        $.ajax({
            type: "POST",
            url: url
        });
    
        alert("Added successfully " + qty + " " + prodName+ " with " + size + " size in the cart");
   
       
    }

    $(".minusQuantity").click(function() {
        event.preventDefault();
        this.getAttribute("value"); // link  
        // console.log(this.getAttribute("value"));

        var qty = parseInt($(this).parent().find('.productqty').html(), 10) ;  // 1
        // console.log(qty);
        if(qty > 1){
            minusQty(qty);
        
        }
    });
    
    function minusQty(qty){
        var updateqty = qty - 1;
        console.log(updateqty);
        // document.getElementsByClassName('productqty').setAttribute('value',updateqty); //div de value 
        console.log(document.getElementsByClassName('productqty'));

        document.getElementsByClassName('productqty')[0].innerText = updateqty; // for disaplay purpsose 
    }

    $(".addQuantity").click(function() {
        event.preventDefault();
        var qty =parseInt($(this).parent().find('.productqty').html(), 10) ;
    
        // console.log(qty);
        addQty(qty);
  
        
    });
    function addQty(qty){
        var updateqty = qty + 1;
        // console.log();
        // document.getElementsByClassName('productqty').setAttribute('value',updateqty );
        console.log(updateqty);
        document.getElementsByClassName('productqty')[0].innerText = updateqty ;

    }

</script>

<?php
get_footer();
?>