<?php require_once('header.php'); ?>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $banner_cart = $row['banner_cart'];
}
?>

<?php
$error_message = '';
if (isset($_POST['form1'])) {

    $i = 0;
    $statement = $pdo->prepare("SELECT * FROM tbl_product");
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row) {
        $i++;
        $table_product_id[$i] = $row['p_id'];
        $table_quantity[$i] = $row['p_qty'];
    }

    $i = 0;
    foreach ($_POST['product_id'] as $val) {
        $i++;
        $arr1[$i] = $val;
    }
    $i = 0;
    foreach ($_POST['quantity'] as $val) {
        $i++;
        $arr2[$i] = $val;
    }
    $i = 0;
    foreach ($_POST['product_name'] as $val) {
        $i++;
        $arr3[$i] = $val;
    }

    $allow_update = 1;
    for ($i = 1; $i <= count($arr1); $i++) {
        for ($j = 1; $j <= count($table_product_id); $j++) {
            if ($arr1[$i] == $table_product_id[$j]) {
                $temp_index = $j;
                break;
            }
        }
        if ($table_quantity[$temp_index] < $arr2[$i]) {
            $allow_update = 0;
            $error_message .= '"' . $arr2[$i] . '" items are not available for "' . $arr3[$i] . '"\n';
        } else {
            $_SESSION['cart_p_qty'][$i] = $arr2[$i];
        }
    }
    $error_message .= '\nOther items quantity are updated successfully!';
?>

    <?php if ($allow_update == 0) : ?>
        <script>
            alert('<?php echo $error_message; ?>');
        </script>
    <?php else : ?>
        <script>
            alert('All Items Quantity Update is Successful!');
        </script>
    <?php endif; ?>
<?php

}
?>

<div class="page-banner" style="background-image: url(assets/uploads/<?php echo $banner_cart; ?>)">
    <div class="overlay"></div>
    <div class="page-banner-inner">
        <h1><?php echo LANG_VALUE_18; ?></h1>
    </div>
</div>

<div class="page">
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <?php if (!isset($_SESSION['cart_p_id'])) : ?>
                    <?php echo '<h2 class="text-center">Cart is Empty!!</h2></br>'; ?>
                    <?php echo '<h4 class="text-center">Add products to the cart in order to view it here.</h4>'; ?>
                <?php else : ?>
                    <?php
                    if($_SESSION['is_login'] == false){
                     echo '<h2 class="text-center">Anda belum login!!</h2></br>';
                     echo '<h4 class="text-center">Pastikan anda login terlebih dahulu .</h4>';
                    }else{
                    ?>
                    <form action="" method="post">
                        <?php $csrf->echoInputField(); ?>
                        <div class="cart">
                            <table class="table table-responsive table-hover table-bordered">
                                <tr>
                                    <th><?php echo '#' ?></th>
                                    <th><?php echo LANG_VALUE_8; ?></th>
                                    <th><?php echo LANG_VALUE_47; ?></th>
                                    <th><?php echo LANG_VALUE_157; ?></th>
                                    <th><?php echo LANG_VALUE_158; ?></th>
                                    <th><?php echo LANG_VALUE_159; ?></th>
                                    <th><?php echo LANG_VALUE_55; ?></th>
                                    <th class="text-right"><?php echo LANG_VALUE_82; ?></th>
                                    <th class="text-center" style="width: 100px;"><?php echo LANG_VALUE_83; ?></th>
                                </tr>
                                <?php
                                $statement = $pdo->prepare("SELECT * FROM tbl_cart WHERE cust_id=?");
                                $statement->execute(array($_SESSION['customer']['cust_id']));
                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                $no = 1;
                                $totalprice = 0;
                                foreach ($result as $row) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td>
                                            <img src="assets/uploads/<?php echo $row['cart_p_featured_photo']; ?>" alt="">
                                        </td>
                                        <td><?php echo $row['cart_p_name']; ?></td>
                                        <td><?php echo $row['cart_size_name']; ?></td>
                                        <td><?php echo $row['cart_color_name']; ?></td>
                                        <td><?php echo LANG_VALUE_1; ?><?php echo number_format($row['cart_p_current_price']); ?></td>
                                        <td>
                                            <input type="hidden" name="product_id[]" value="<?php echo $row['id']; ?>">
                                            <input type="hidden" name="product_name[]" value="<?php echo $row['cart_p_name']; ?>">
                                            <!-- <input type="number" readonly class="input-text qty text" step="1" min="1" max="" name="quantity[]" value="<?php echo $row['cart_p_qty']; ?>" title="Qty" size="4" pattern="[0-9]*" inputmode="numeric"> -->
                                            <?php echo $row['cart_p_qty']; ?>
                                        </td>
                                        <td class="text-right">
                                            <?php
                                            $row_total_price = $row['cart_p_current_price'] * $row['cart_p_qty'];
                                            $totalprice += $row_total_price;
                                            echo LANG_VALUE_1;
                                            echo number_format($row_total_price); ?>
                                        </td>
                                        <td class="text-center">
                                            <a onclick="return confirmDelete();" href="cart-item-delete.php?id=<?php echo $row['id']; ?>&p_id=<?php echo $row['cart_p_id']; ?>&p_qty=<?php echo $row['cart_p_qty']; ?>&size=<?php echo $row['cart_size_id']; ?>&color=<?php echo $row['cart_color_id']; ?>" class="trash"><i class="fa fa-trash" style="color:red;"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <th colspan="7" class="total-text">Total</th>
                                    <th class="total-amount"><?php echo LANG_VALUE_1; ?><?php echo number_format($totalprice); ?></th>
                                    <th></th>
                                </tr>
                            </table>
                        </div>

                        <div class="cart-buttons">
                            <ul>
                                <!-- <li><input type="submit" value="<?php echo LANG_VALUE_20; ?>" class="btn btn-primary" name="form1"></li> -->
                                <li><a href="index.php" class="btn btn-primary"><?php echo LANG_VALUE_85; ?></a></li>
                                <li><a href="checkout.php" class="btn btn-primary"><?php echo LANG_VALUE_23; ?></a></li>
                            </ul>
                        </div>
                    </form>
                    <?php }?>
                <?php endif; ?>



            </div>
        </div>
    </div>
</div>


<?php require_once('footer.php'); ?>