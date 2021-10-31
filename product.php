<?php
require './product.class.php';
// step two
function validate(array $data)
{
    $err = '';
    foreach ($data as $key => $data1) {
        if ($data1 == null && strlen($data1) < 1) {
            $err .= " $key is empty <br>";
        }
    }
    return $err;
}
$err = '';

// step one
if (isset($_POST['submit'])) {
    $productName = $_POST['productName'];
    $productId = $_POST['productId'];
    $productPrice = $_POST['productPrice'];
    $productQuantity = $_POST['productQuantity'];
    $productImage = $_FILES['upload']['name'];
    $producttemp = $_FILES['upload']['tmp_name'];
    $datas = ['productname' => $productName, 'productid' => $productId, 'productprice' => $productPrice, 'productquantity' => $productQuantity, 'productImage' => $productImage];

    $err = validate($datas);

    // step three
    if ($err == '') {
        $connec = new DbConnection();
        // var_dump($connec);
        $query1 = "INSERT INTO products (productname, productid, productprice, productquantity, productimage) VALUES ('$productName', '$productId','$productPrice','$productQuantity','$productImage')";
        // $query2 = 'SELECT * FROM items';
        $connect = $connec->connection1($query1);

        if ($connect) {
            move_uploaded_file($producttemp, "images/" . $productImage);
            $err = "Data succesfully uploaded";
        } else {
            $err = "Data not uploaded";
        }
    }
}



?>


<html>

<head>
    <title>form</title>
    <link rel="stylesheet" href="./product.css" type="text/css">
</head>

<body>
    <form action="" method="POST" enctype="multipart/form-data" class="card1">
        <div class="container">
            <h2 class="header1">Upload Product</h2>
            <div class="firstname">
                <label>Product Name:</label>
                <input type="text" name="productName" placeholder="Product Name">
            </div>
            <div class="lastname">
                <label>Product ID:</label>
                <input type="text" name="productId" placeholder="Product ID">
            </div>
            <div class="identity">
                <label>Product Price:</label>
                <input type="text" name="productPrice" placeholder="Product Price">
            </div>
            <div class="dob">
                <label>Product Quantity:</label>
                <input type="number" name="productQuantity" placeholder="Product Quantity">
            </div>
            <div class="issued_date">
                <label>Product Image:</label>
                <input type="file" name="upload">
            </div>
            <button type="submit" name="submit" class="submit">Submit</button><br>

            <?php echo $err; ?>

        </div>

    </form>


    <!-- Receipt -->

    <?php
    $conn = new DbConnection();
    $query11 = "SELECT * FROM products";

    $connect = $conn->connection1($query11);
    if ($connect) {
        $data = $conn->getData($connect);
        // var_dump($data);
    }

    ?>
    <div class="container2">
        <h3>Transaction Details</h3>
        <div class="header">
            <p class="item">ITEM</p>
            <p class="amount">AMOUNT</p>
        </div>

        <!-- This is where the loop start -->
        <?php $subTotal = 0; ?>
        <?php foreach ($data as $files) {
            $product_name = $files['productname'];
            $product_number = $files['productid'];
            $product_price = $files['productprice'];
            $product_quantity = $files['productquantity'];
            // $product_amount = $files['productid'];
            $product_image = $files['productimage'];
            $total = $files['productprice'] * $files['productquantity'];

            $subTotal += $total;

        ?>
            <div class="items">
                <div class="items-image">
                    <img src="<?php echo './images/' . $product_image ?>">
                </div>
                <div class="items-details">
                    <p class="product_name"><?php echo $product_name ?></p>
                    <p class="product_number"><?php echo $product_number ?></p>
                    <p class="product_price">Price:<span>$<?php echo $product_price ?></span></p>
                    <p class="product_quantity">Qty:<span><?php echo $product_quantity ?></span></p>
                </div>
                <div class="items-amount">$<?php echo $total ?></div>

            </div>
            <hr />
        <?php }
        ?>
        <!-- The looping stops here -->
        <?php
        $PST = 7 / 100 * ($subTotal);
        $TPS = 5 / 100 * ($subTotal);
        $Netpay = $subTotal - ($PST + $TPS);
        ?>
        <div class="total">
            <p class="subtotal">Subtotal:<span>$<?php echo $subTotal ?></span></p>
            <p>BRITISH COLUMBIA PST(7%):<span>$<?php echo $PST ?></span></p>
            <p>CANADA GST/TPS(5%):<span>$<?php echo $TPS ?></span></p>
            <p>Total:<span>$<?php echo $Netpay ?></span></p>
        </div>

    </div>
    </div>


</body>

</html>