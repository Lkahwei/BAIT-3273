  <?php

  /**
   *Template Name: CHECK OUT
  */

  get_header();
  ?>
  <?php
    session_start();
  ?>
  <?php 
  global $wpdb;
  $userid = $_SESSION["userID"];

  if(!isset($_SESSION["userID"])){
    header("Location: https://laixinyi.azurewebsites.net/login/");
    exit();
   }

  if(isset($_GET['recipientName']) &&  isset($_GET['phone']) && isset($_GET['streetname']) && isset($_GET['postalCode']) && isset($_GET['city']) && isset($_GET['state']) &&
    isset($_GET['remark']) && isset($_GET['payment'])){
    $RECIPIENTNAME = $_GET['recipientName'];
    $RECIPIENTPHONE = $_GET['phone'];
    $STREETNAME = $_GET['streetname'];
    $POSTCODE = $_GET['postalCode'];
    $CITY = $_GET['city'];
    $STATE = $_GET['state'];
    $REMARK = $_GET['remark'];
    $PAYMENTTYPE = $_GET['payment'];

    echo $RECIPIENTNAME = $_GET['recipientName'];
    echo $PHONE = $_GET['phone'];
    echo $STREETNAME = $_GET['streetname'];
    echo $POSTCODE = $_GET['postalCode'];
    echo $CITY = $_GET['city'];
    echo $STATE = $_GET['state'];
    echo $REMARK = $_GET['remark'];
    echo $PAYMENTTYPE = $_GET['payment'];
    
    $cartidquery = $wpdb->prepare("SELECT CARTID  
                                FROM SHOPPINGCART
                                WHERE USERID = %s", $userid);
    $cartid =  $wpdb->get_results($cartidquery)[0]->CARTID;
    print_r($cartid);
    date_default_timezone_set("Asia/Kuala_Lumpur");
          
  $deliveryIDCounter = 0;
    
  $deliveryQuery = $wpdb->prepare("SELECT DELIVERYID FROM DELIVERY");
  $delivery = $wpdb->get_results($deliveryQuery);

  foreach ($delivery as $row) {
    $deliveryIDCounter += 1;
  }
  $deliveryIDCounter += 1;

  if(strlen(strval($deliveryIDCounter)) == 2){
    $DELIVERYID = "D0".$deliveryIDCounter;
  }else{
    $DELIVERYID = "D".$deliveryIDCounter;
  }
  print_r($DELIVERYID);

  //INSERT DELIVERY DETAILS 
  $insertDelivery = array('DELIVERYID' => $DELIVERYID, 
                          'RECIPIENTNAME' => $RECIPIENTNAME, 
                          'RECIPIENTPHONE' => $RECIPIENTPHONE, 
                          'STREETNAME' => $STREETNAME,
                          'POSTCODE' => $POSTCODE , 
                          'CITY' => $CITY,
                          'STATE' => $STATE,
                          'REMARK' => $REMARK);
  $wpdb->insert('DELIVERY', $insertDelivery);
  $wpdb->show_errors();



  $orderIDCounter = 0;
  $orderQuery = $wpdb->prepare("SELECT ORDERID FROM ORDERTABLE");
  $order = $wpdb->get_results($orderQuery);

  foreach ($order as $row) {
    $orderIDCounter += 1;
  }
  $orderIDCounter += 1;

  if(strlen(strval($orderIDCounter)) == 2){
    $ORDERID = "O0".$orderIDCounter;
  }else{
    $ORDERID = "O".$orderIDCounter;
  }

  // INSERT ORDER  
  $orderQuery = $wpdb->prepare("SELECT DELIVERYID FROM DELIVERY");
  $order = $wpdb->get_results($orderQuery);

  //SELECT ORDERID FROM ORDERTABLE;

  date_default_timezone_set("Asia/Kuala_Lumpur");
  $ORDERDATE = date("Y-m-d");

  $SHIPDATE = date("Y-m-d", strtotime("+5 days"));

  date('Y-m-d', strtotime("+30 days"));
  $totalAmountQuery = $wpdb->prepare("SELECT SUM(P.PRODUCTPRICE * C.QUANTITY) as total
                                      FROM  CARTITEM C, PRODUCT P, CATEGORY CA
                                      WHERE C.PRODUCTID = P.PRODUCTID AND
                                          P.CATEGORYID = CA.CATEGORYID AND
                                          C.CARTID = %s", $cartid);
                                  
  $AMOUNT = $wpdb->get_results($totalAmountQuery)[0]->total;
  $AMOUNT = $AMOUNT + 5;
  $insertOrder = array('ORDERID' => $ORDERID, 
                        'DELIVERYID' => $DELIVERYID, 
                        'USERID' => $userid, 
                        'ORDERDATE' => $ORDERDATE,
                        'SHIPDATE' => $SHIPDATE , 
                        'AMOUNT' => $AMOUNT,
                        'PAYMENTTYPE' => $PAYMENTTYPE);
  $wpdb->insert('ORDERTABLE', $insertOrder);


  // INSERT ORDER  DETAILS
  $cartItem = $wpdb->prepare("SELECT C.PRODUCTID, C.QUANTITY
                              FROM CARTITEM C, SHOPPINGCART SC 
                              WHERE C.CARTID = SC.CARTID AND 
                                    SC.USERID = %s" ,$userid);

  $cartItemResult = $wpdb->get_results($cartItem);


  foreach($cartItemResult as $row) {

    $insertOrderDetails = array('ORDERID' => $ORDERID, 
                                'PRODUCTID' => $row -> PRODUCTID, 
                                'QUANTITY' => $row -> QUANTITY 
                                );
    
    $CART_PRODUCTID = $row -> PRODUCTID; 
    $CART_QUANTITY = $row -> QUANTITY; 

    $wpdb->insert('ORDERDETAILS', $insertOrderDetails);
    $wpdb->show_errors();

    $product = $wpdb->prepare("SELECT PRODUCTID, QUANTITYAVAILABLE
                              FROM PRODUCT
                              WHERE PRODUCTID = %s", $CART_PRODUCTID);
    $productResult = $wpdb->get_results($product);

    $productQty = $productResult[0] -> QUANTITYAVAILABLE ;
    $productID = $productResult[0] -> PRODUCTID ;

    $updateQty = $productQty - $CART_QUANTITY  ; 

    $wpdb->update('PRODUCT', array('QUANTITYAVAILABLE' => $updateQty),  array('PRODUCTID' => $productID));
    $wpdb->show_errors();

  }

  $wpdb->delete(
    'CARTITEM',
    array('CARTID' => $cartid)
  );


  }

  $user = $wpdb->prepare("SELECT USERNAME, USERGENDER, STREETNAME, POSTCODE, CITY, STATE 
                          FROM USER
                          WHERE  USERID = %s" ,$userid);

  $userResult = $wpdb->get_results($user);
    



  echo '<body>';
  echo '<div style="font-weight:bold;font-size:36px;width:100%;text-align:center"> Checkout </div>
  <div class="container">
      <div class="row">
        <div class="left">
          <label for="recipientName">Recipient Name </label>
        </div> 

        <div class="right"> 
          <input type="text" id="recipientName" value="'?> <?php echo $userResult[0]->USERNAME;?> <?php echo '" name="recipientName" required>
        </div>
      </div>

      <div class="row">
        <div class="left">
          <label for="phone">Recipient Phone</label>
        </div> 
        
        <div class="right"> 
          <input type="tel" id="phone" name="phone" placeholder="012-6219896" required>
        </div>
      </div>

      <div class="row">
        <div class="left">
          <label for="streetname">Street Name</label>
        </div> 
        
        <div class="right"> 
          <input type="text" id="streetname" name="streetname" value="'?> <?php echo $userResult[0]->STREETNAME;?> <?php echo '" name="recipientName"  required>
        </div>
      </div> 

      <div class="row">
        <div class="left">
          <label for="postalCode">Postal Code</label>
        </div>  
        
        <div class="right"> 
          <input style="width: 20%" type="text" id="postalCode" name="postalCode" value="'?> <?php echo $userResult[0]->POSTCODE;?> <?php echo '" required>
        </div>
        
      </div>

      <div class="row">
        <div class="left">
          <label for="city">City</label>
        </div> 
        
        <div class="right"> 
          <input style="width: 40%"  type="text" id="city" name="city" value="'?> <?php echo $userResult[0]->CITY;?> <?php echo '" required>
        </div>
      </div>

      <div class="row">
        <div class="left">
          <label for="state">State</label>
          </div> 
        
        <div class="right"> 
          <input style="width: 40%"  type="text" id="state" name="state" value="'?> <?php echo $userResult[0]->STATE;?> <?php echo '" required>
        </div>
      </div>

      <div class="row">
        <div class="left">
          <label for="payment">Payment Type</label>
        </div>  
        
        <div class="right"> 
          <select style="width: 40%"  id="payment" name="payment">
            <option value="Online Banking">Online Banking</option>
            <option value="E-Wallet">E-Wallet</option>
            <option value="Debit Card">Debit Card</option>
            <option value="Credit Card">Credit Card</option>
          </select>
        </div>
        
      </div>

      <div class="row">
        <div class="left">
          <label for="remark">Remark</label>
        </div> 
        
        <div class="right"> 
          <textarea id="remark" name="remark" placeholder="Remark.." style="height:100px"></textarea>
        </div>
      </div>
      
    
          <div id="submitBtn" style="width:80%"> 
              <input type="submit" value="Submit >>>">
          </div>
  

  </div>'; 

  echo '</body>';
  ?>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script>

  $("#submitBtn").click(function(){
    
    
    var recipientName = document.getElementById('recipientName').value  ;
    var phone = $(this).parent().find("#phone").val() ;
    var streetname = document.getElementById('streetname').value; 
    var postalCode = document.getElementById('postalCode').value; 
    var city = document.getElementById('city').value; 
    var state = document.getElementById('state').value; 
    var remark =$(this).parent().find("#remark").val(); 
    var payment = $(this).parent().find("#payment").val();

    // console.log(document.getElementById('recipientName'));
    console.log(recipientName);
    console.log(phone);
    console.log(streetname);
    console.log(postalCode);
    console.log(city);
    console.log(state);
    console.log(remark);
    console.log(payment);


    // var recipientName = $(this).parent().find("#recipientName").val()
    // var phone = $(this).parent().find("#phone").val()
    // var streetname = $(this).parent().find("#streetname").val()
    // var postalCode = $(this).parent().find("#postalCode").val()
    // var city = $(this).parent().find("#city").val()
    // var state = $(this).parent().find("#state").val()
    // var remark =  $(this).parent().find("#remark").val()
    // var payment =  $(this).parent().find("#payment").val()

    if(!recipientName){
      alert("Please fill up all the fields");
      
    }else if(!phone){
      alert("Please fill up all the fields");
    }else if(!streetname){
      alert("Please fill up all the fields");
    }else if(!postalCode){
      alert("Please fill up all the fields");
    }else if(!city){
      alert("Please fill up all the fields");
    }else if(!state){
      alert("Please fill up all the fields");
    }else if(!payment){
      alert("Please fill up all the fields");
    }else{
      var r = confirm("Confirm Checkout?");
        if (r == true) {
          var url = "https://laixinyi.azurewebsites.net/my-cart/checkout/?recipientName=" + recipientName.trim().replace(' ', '%20') + "&phone=" + phone.trim().replace(' ', '%20')  + "&streetname=" + streetname.trim().replace(' ', '%20')  + "&postalCode=" + postalCode.trim().replace(' ', '%20')  + "&city=" 
          + city.trim().replace(' ', '%20')  + "&state=" + state.trim().replace(' ', '%20')  + "&remark=" + remark.trim().replace(' ', '%20')  + "&payment=" + payment.trim().replace(' ', '%20') ;
          // console.log(url);
          insertDB(url);
        } 
    }
    
  })
  function insertDB(url){
    $.ajax({
        method: "POST",
        url: url,
        success: function( data ) {
        // console.log("success");
    }});
    alert("Payment Successful !");
    window.setTimeout(function(){

  // Move to a new location or you can do something else
      window.location.href = "https://laixinyi.azurewebsites.net/my-cart/checkout/receipt/";

  }, 1000);  
  }

  </script>

  <style>
    * {
      box-sizing: border-box;
    }

    input[type=tel] {
      width: 30%;
      padding: 12px;
      border: 1px solid black;
      border-radius: 4px;
      resize: vertical;
    }

    input[type=text],
    select,
    textarea {
      width: 70%;
      padding: 12px;
      border: 1px solid black;
      border-radius: 4px;
      resize: vertical;
      
    }

    label {
      float: left;
      padding: 12px 12px 12px 0;
      display: inline-block;
      color: black;
      margin-top: 6px;
      font-size: 20px;
      
    }

    input {
      float: left;
      margin-top: 6px;
    }

    input[type=submit] {
      background-color: #212121;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      float: right;
      width: 30%

    }

    input[type=submit]:hover {
      background-color: #ff8c00;
      color: white;
      border-radius: 5px;
      float: right;
    }

    .container {
      border-radius: 5px;
      width: 100%;
    }

    .row {
      margin-bottom: 20px;
    }

    .row:after {
      content: "";
      display: table;
      clear: both;
      
    }
    .left {
      float: left;
      width: 30%;
      margin-top: 6px;
    }

    .right {
      float: left;
      width: 70%;
      margin-top: 6px;
    }
  </style>