<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./admindashboardcss/admincashpayment.css">
</head>
<body>
    
    <div class="container">
         <center> <h3>Cash Payment</h3> </center>


         <?php

            echo "Hello PHP it's me Richmon, your Web Developer!";
            echo "<br>";
            echo "Today is ". date("Y-m-d H:i:s");

         ?>


        <div class="payment-form">
           <form action="">
                <label for="Name">Name</label>
                <br>
                <input type="text">
                <br>
                <br>
                <label for="Name">Contact Number</label>
                <br>
                <input type="number">
                <br>
                <br>
                <label for="Name">Amount</label>
                <br>
                <input type="number">
                <br>
                <br>
               <button><input type="submit"></button>
           </form>
           
        </div>
    </div>   
</body>
</html>