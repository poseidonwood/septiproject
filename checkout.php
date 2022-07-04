<?php require_once('header.php'); ?>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $banner_checkout = $row['banner_checkout'];
}
?>

<?php
if (!isset($_SESSION['cart_p_id'])) {
    header('location: cart.php');
    exit;
}
?>

<div class="page-banner" style="background-image: url(assets/uploads/<?php echo $banner_checkout; ?>)">
    <div class="overlay"></div>
    <div class="page-banner-inner">
        <h1><?php echo LANG_VALUE_22; ?></h1>
    </div>
</div>

<div class="page">
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <?php
                if (!isset($_SESSION['customer'])) : ?>
                    <p>
                        <a href="login.php" class="btn btn-md btn-danger"><?php echo LANG_VALUE_160; ?></a>
                    </p>
                <?php else : ?>
                    <?php
                    $statement = $pdo->prepare("SELECT * FROM tbl_cart WHERE cust_id=?");
                    $statement->execute(array($_SESSION['customer']['cust_id']));
                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                    if (count($result) > 0) {
                    ?>
                        <h3 class="special"><?php echo LANG_VALUE_26; ?></h3>
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
                                </tr>
                                <?php
                                $statement = $pdo->prepare("SELECT * FROM tbl_cart WHERE cust_id=?");
                                $statement->execute(array($_SESSION['customer']['cust_id']));
                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                $no = 1;
                                $totalprice = 0;
                                foreach ($result as $row) : ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td>
                                            <img src="assets/uploads/<?php echo $row['cart_p_featured_photo']; ?>" alt="">
                                        </td>
                                        <td><?php echo $row['cart_p_name']; ?></td>
                                        <td><?php echo $row['cart_size_name']; ?></td>
                                        <td><?php echo $row['cart_color_name']; ?></td>
                                        <td><?php echo LANG_VALUE_1; ?><?php echo $row['cart_p_current_price']; ?></td>
                                        <td><?php echo $row['cart_p_qty']; ?></td>
                                        <td class="text-right">
                                            <?php
                                            $row_total_price = $row['cart_p_current_price'] * $row['cart_p_qty'];
                                            $totalprice += $row_total_price;
                                            echo LANG_VALUE_1;
                                            echo number_format($row_total_price); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <th colspan="7" class="total-text"><?php echo LANG_VALUE_81; ?></th>
                                    <th class="total-amount"><?php echo LANG_VALUE_1; ?><?php echo number_format($totalprice); ?></th>
                                </tr>
                                <?php
                                $statement = $pdo->prepare("SELECT * FROM tbl_shipping_cost WHERE country_id=?");
                                $statement->execute(array($_SESSION['customer']['cust_country']));
                                $total = $statement->rowCount();
                                if ($total) {
                                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($result as $row) {
                                        $shipping_cost = $row['amount'];
                                    }
                                } else {
                                    $statement = $pdo->prepare("SELECT * FROM tbl_shipping_cost_all WHERE sca_id=1");
                                    $statement->execute();
                                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($result as $row) {
                                        $shipping_cost = $row['amount'];
                                    }
                                }
                                ?>
                                <tr>
                                    <td colspan="7" class="total-text"><?php echo LANG_VALUE_84; ?></td>
                                    <td class="total-amount"><?php echo LANG_VALUE_1; ?><?php echo $shipping_cost; ?></td>
                                </tr>
                                <tr>
                                    <th colspan="7" class="total-text"><?php echo LANG_VALUE_82; ?></th>
                                    <th class="total-amount">
                                        <?php
                                        $final_total = $totalprice + $shipping_cost;
                                        ?>
                                        <?php echo LANG_VALUE_1; ?><?php echo number_format($final_total); ?>
                                    </th>
                                </tr>
                            </table>
                        </div>



                        <div class="billing-address">
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="special"><?php echo LANG_VALUE_161; ?></h3>
                                    <table class="table table-responsive table-bordered table-hover table-striped bill-address">
                                        <tr>
                                            <td><?php echo LANG_VALUE_102; ?></td>
                                            <td><?php echo $_SESSION['customer']['cust_b_name']; ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?php echo LANG_VALUE_103; ?></td>
                                            <td><?php echo $_SESSION['customer']['cust_b_cname']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><?php echo LANG_VALUE_104; ?></td>
                                            <td><?php echo $_SESSION['customer']['cust_b_phone']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><?php echo LANG_VALUE_106; ?></td>
                                            <td>
                                                <?php
                                                $statement = $pdo->prepare("SELECT * FROM tbl_country WHERE country_id=?");
                                                $statement->execute(array($_SESSION['customer']['cust_b_country']));
                                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                                foreach ($result as $row) {
                                                    echo $row['country_name'];
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?php echo LANG_VALUE_105; ?></td>
                                            <td>
                                                <?php echo nl2br($_SESSION['customer']['cust_b_address']); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?php echo LANG_VALUE_107; ?></td>
                                            <td><?php echo $_SESSION['customer']['cust_b_city']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><?php echo LANG_VALUE_108; ?></td>
                                            <td><?php echo $_SESSION['customer']['cust_b_state']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><?php echo LANG_VALUE_109; ?></td>
                                            <td><?php echo $_SESSION['customer']['cust_b_zip']; ?></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h3 class="special"><?php echo LANG_VALUE_162; ?></h3>
                                    <table class="table table-responsive table-bordered table-hover table-striped bill-address">
                                        <tr>
                                            <td><?php echo LANG_VALUE_102; ?></td>
                                            <td><?php echo $_SESSION['customer']['cust_s_name']; ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?php echo LANG_VALUE_103; ?></td>
                                            <td><?php echo $_SESSION['customer']['cust_s_cname']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><?php echo LANG_VALUE_104; ?></td>
                                            <td><?php echo $_SESSION['customer']['cust_s_phone']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><?php echo LANG_VALUE_106; ?></td>
                                            <td>
                                                <?php
                                                $statement = $pdo->prepare("SELECT * FROM tbl_country WHERE country_id=?");
                                                $statement->execute(array($_SESSION['customer']['cust_s_country']));
                                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                                foreach ($result as $row) {
                                                    echo $row['country_name'];
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?php echo LANG_VALUE_105; ?></td>
                                            <td>
                                                <?php echo nl2br($_SESSION['customer']['cust_s_address']); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?php echo LANG_VALUE_107; ?></td>
                                            <td><?php echo $_SESSION['customer']['cust_s_city']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><?php echo LANG_VALUE_108; ?></td>
                                            <td><?php echo $_SESSION['customer']['cust_s_state']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><?php echo LANG_VALUE_109; ?></td>
                                            <td><?php echo $_SESSION['customer']['cust_s_zip']; ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>



                        <div class="cart-buttons">
                            <ul>
                                <li><a href="cart.php" class="btn btn-primary"><?php echo LANG_VALUE_21; ?></a></li>
                            </ul>
                        </div>

                        <div class="clear"></div>
                        <h3 class="special"><?php echo LANG_VALUE_33; ?></h3>
                        <div class="row">

                            <?php
                            $checkout_access = 1;
                            if (
                                ($_SESSION['customer']['cust_b_name'] == '') ||
                                ($_SESSION['customer']['cust_b_cname'] == '') ||
                                ($_SESSION['customer']['cust_b_phone'] == '') ||
                                ($_SESSION['customer']['cust_b_country'] == '') ||
                                ($_SESSION['customer']['cust_b_address'] == '') ||
                                ($_SESSION['customer']['cust_b_city'] == '') ||
                                ($_SESSION['customer']['cust_b_state'] == '') ||
                                ($_SESSION['customer']['cust_b_zip'] == '') ||
                                ($_SESSION['customer']['cust_s_name'] == '') ||
                                ($_SESSION['customer']['cust_s_cname'] == '') ||
                                ($_SESSION['customer']['cust_s_phone'] == '') ||
                                ($_SESSION['customer']['cust_s_country'] == '') ||
                                ($_SESSION['customer']['cust_s_address'] == '') ||
                                ($_SESSION['customer']['cust_s_city'] == '') ||
                                ($_SESSION['customer']['cust_s_state'] == '') ||
                                ($_SESSION['customer']['cust_s_zip'] == '')
                            ) {
                                $checkout_access = 0;
                            }
                            ?>
                            <?php if ($checkout_access == 0) : ?>
                                <div class="col-md-12">
                                    <div style="color:red;font-size:22px;margin-bottom:50px;">
                                        You must have to fill up all the billing and shipping information from your dashboard panel in order to checkout the order. Please fill up the information going to <a href="customer-billing-shipping-update.php" style="color:red;text-decoration:underline;">this link</a>.
                                    </div>
                                </div>
                            <?php else : ?>
                                <div class="col-md-4">

                                    <div class="row">

                                        <div class="col-md-12 form-group">
                                            <label for=""><?php echo LANG_VALUE_34; ?> *</label>
                                            <select name="payment_method" class="form-control select2" id="advFieldsStatus">
                                                <option value=""><?php echo LANG_VALUE_35; ?></option>
                                                <!-- <option value="PayPal"><?php echo LANG_VALUE_36; ?></option> -->
                                                <option value="Bank Deposit"><?php echo LANG_VALUE_38; ?></option>
                                            </select>
                                        </div>

                                        <form class="paypal" action="<?php echo BASE_URL; ?>payment/paypal/payment_process.php" method="post" id="paypal_form" target="_blank">
                                            <input type="hidden" name="cmd" value="_xclick" />
                                            <input type="hidden" name="no_note" value="1" />
                                            <input type="hidden" name="lc" value="UK" />
                                            <input type="hidden" name="currency_code" value="USD" />
                                            <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest" />

                                            <input type="hidden" name="final_total" value="<?php echo $final_total; ?>">
                                            <div class="col-md-12 form-group">
                                                <input type="submit" class="btn btn-primary" value="<?php echo LANG_VALUE_46; ?>" name="form1">
                                            </div>
                                        </form>



                                        <form action="payment/bank/init.php" method="post" id="bank_form">
                                            <input type="hidden" name="amount" value="<?php echo $final_total; ?>">
                                            <div class="col-md-12 form-group">
                                                <label for=""><?php echo LANG_VALUE_43; ?></span></label><br>
                                                <?php
                                                $statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
                                                $statement->execute();
                                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                                foreach ($result as $row) {
                                                    echo nl2br($row['bank_detail']);
                                                }
                                                ?>
                                            </div>
                                            <div class="col-md-12 form-group">
                                                <label for=""><?php echo LANG_VALUE_44; ?> <br><span style="font-size:12px;font-weight:normal;">(<?php echo LANG_VALUE_45; ?>)</span></label>
                                                <textarea name="transaction_info" class="form-control" cols="30" rows="10" required></textarea>
                                            </div>
                                            <div class="col-md-12 form-group">
                                                <input type="submit" class="btn btn-primary" value="<?php echo LANG_VALUE_46; ?>" name="form3">
                                            </div>
                                        </form>

                                    </div>


                                </div>
                            <?php endif; ?>

                        </div>


                    <?php
                    } else {
                    ?>
                        <div class="col-md-12">
                            <div style="color:red;font-size:22px;margin-bottom:50px;">
                                Keranjang anda kosong , silahkan belanja dahulu.. <a href="./index.php" style="color:red;text-decoration:underline;">Kembali ke home</a>.
                            </div>
                        </div>
                <?php
                    }
                endif; ?>

            </div>
        </div>
    </div>
</div>


<?php require_once('footer.php'); ?>