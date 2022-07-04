<?php
ob_start();
session_start();
include("../../admin/inc/config.php");
include("../../admin/inc/functions.php");
// Getting all language variables into array as global variable
$i = 1;
$statement = $pdo->prepare("SELECT * FROM tbl_language");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
	define('LANG_VALUE_' . $i, $row['lang_value']);
	$i++;
}
?>
<?php
if (!isset($_REQUEST['msg'])) {
	if (empty($_POST['transaction_info'])) {
		header('location: ../../checkout.php');
	} else {
		$payment_date = date('Y-m-d H:i:s');
		$payment_id = time();

		$statement = $pdo->prepare("INSERT INTO tbl_payment (   
	                            customer_id,
	                            customer_name,
	                            customer_email,
	                            payment_date,
	                            txnid, 
	                            paid_amount,
	                            card_number,
	                            card_cvv,
	                            card_month,
	                            card_year,
	                            bank_transaction_info,
	                            payment_method,
	                            payment_status,
	                            shipping_status,
	                            payment_id
	                        ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
		$statement->execute(array(
			$_SESSION['customer']['cust_id'],
			$_SESSION['customer']['cust_name'],
			$_SESSION['customer']['cust_email'],
			$payment_date,
			'',
			$_POST['amount'],
			'',
			'',
			'',
			'',
			$_POST['transaction_info'],
			'Bank Deposit',
			'Pending',
			'Pending',
			$payment_id
		));
		$statement = $pdo->prepare("SELECT * FROM tbl_cart WHERE cust_id = ?");
		$statement->execute(array($_SESSION['customer']['cust_id']));
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);

		foreach ($result as $row) {
			$statement = $pdo->prepare("INSERT INTO tbl_order (
	                        product_id,
	                        product_name,
	                        size, 
	                        color,
	                        quantity, 
	                        unit_price, 
	                        payment_id
	                        ) 
	                        VALUES (?,?,?,?,?,?,?)");
			$sql = $statement->execute(array(
				$row['cart_p_id'],
				$row['cart_p_name'],
				$row['cart_size_name'],
				$row['cart_color_name'],
				$row['cart_p_qty'],
				$row['cart_p_current_price'],
				$payment_id
			));
			$statementhapus = $pdo->prepare("DELETE FROM tbl_cart where id = {$row['id']}");
			$statementhapus->execute();
		}
		// unset($_SESSION['cart_p_id']);
		// unset($_SESSION['cart_size_id']);
		// unset($_SESSION['cart_size_name']);
		// unset($_SESSION['cart_color_id']);
		// unset($_SESSION['cart_color_name']);
		// unset($_SESSION['cart_p_qty']);
		// unset($_SESSION['cart_p_current_price']);
		// unset($_SESSION['cart_p_name']);
		// unset($_SESSION['cart_p_featured_photo']);

		header('location: ../../payment_success.php');
	}
}
?>