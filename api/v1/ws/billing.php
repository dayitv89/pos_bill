<?php
//-- gives you the product category list
$app->post('/bill/:paymentDone', function ($paymentDone) {
  $bill = getRequestBody();
  try {
    $sql = "INSERT INTO `bill` (`bill_detail`, `payment`, `date`) VALUES (':bill', ':paymentInfo', NOW())";
    $db = getDB();
    $stmt = $db->prepare($sql);
    $stmt->bindParam("bill", $bill);
    $stmt->bindParam("paymentInfo", $paymentDone);
    $stmt->execute();
    $id = $db->lastInsertId();
    $db = null;
    if ($id == '') {
      showError(400, "Can't create bill.");
      return;
    }
    $bill['bill_id'] = $id;
    echo json_encode($bill);
  } catch(PDOException $e) {
    showError(400, "Report this bug to Developer", $e->getMessage(), $e);
  }
});

$app->get('/bill/:billid', function ($billid) {
  try {
    $sql = "SELECT * FROM `bill` WHERE `bid` = $billid";
    $db = getDB();
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $updates = $stmt->fetch(PDO::FETCH_OBJ);
    $db = null;
    if ($updates == '') {
      showError(400, "Bill not found.");
      return;
    }
    echo json_encode($updates);
  } catch(PDOException $e) {
    showError(400, "Report this bug to Developer", $e->getMessage(), $e);
  }
});
 ?>
