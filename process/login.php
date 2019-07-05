<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");
$xDate = date('Y-m-d');

function checklogin($conn,$DATA)
{
  if (isset($DATA)) {
    $user = $DATA['USERNAME'];
    $password = $DATA['PASSWORD'];
    $boolean = false;
    $Sql = "SELECT
            users.ID,
            users.UserName,
            users.`Password`,
            users.lang,
            permission.PmID,
            permission.Permission,
            site.HptCode,
            site.HptName,
            users.Count,
            users.TimeOut
            FROM
            permission
            INNER JOIN users ON users.PmID = permission.PmID
            INNER JOIN site ON users.HptCode = site.HptCode
        WHERE users.UserName = '$user' AND users.`Password` = '$password' AND users.IsCancel = 0";
    $meQuery = mysqli_query($conn,$Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $_SESSION['Userid'] = $Result['ID'];
      $_SESSION['Username'] = $Result['UserName'];
      $_SESSION['PmID'] = $Result['PmID'];
      $_SESSION['HptCode'] = $Result['HptCode'];
      $_SESSION['TimeOut'] = $Result['TimeOut'];
      $_SESSION['lang'] = $Result['lang']==null?'th':$Result['lang'];

      $Count = $Result['Count'];

      $FirstName = $Result['FirstName'];

      $boolean = true;
    }

    if($boolean){
      $return['status'] = "success";
      $return['Count'] = $Count;
      $return['msg'] = "Login Success";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }else{
      $return['status'] = "failed";
      $return['msg'] = "Not found username or password";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }
  }
}

if(isset($_POST['DATA']))
{
  $data = $_POST['DATA'];
  $DATA = json_decode(str_replace ('\"','"', $data), true);
  checklogin($conn,$DATA);
}else{
	$return['status'] = "error";
	$return['msg'] = 'ไม่มีข้อมูลนำเข้า [ $FirstName ]';
	echo json_encode($return);
	mysqli_close($conn);
  die;
}
?>
