<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");
$xDate = date('Y-m-d');

function OnLoadPage($conn,$DATA){
  $count = 0;
  $boolean = false;
  $Sql = "SELECT site.HptCode,site.HptName FROM site WHERE site.IsStatus = 0";
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptCode'] = $Result['HptCode'];
    $return[$count]['HptName'] = $Result['HptName'];
    $count++;
    $boolean = true;
  }
  $boolean = true;
  if($boolean){
    $return['status'] = "success";
    $return['form'] = "OnLoadPage";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "failed";
    $return['form'] = "OnLoadPage";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function getDepartment($conn,$DATA){
  $count = 0;
  $boolean = false;
  $Hotp = $DATA["Hotp"];

  $Sql = "SELECT department.DepCode,department.DepName,department.IsDefault
  FROM department
  WHERE department.HptCode = '$Hotp' 
  AND  department.IsDefault = 1
  AND department.IsStatus = 0
  ORDER BY department.DepCode DESC";
  $return['sql'] = $Sql;
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DepCode'] = $Result['DepCode'];
    $return[$count]['DepName'] = $Result['DepName'];
    $return[$count]['IsDefault'] = $Result['IsDefault'];
    $count++;
    $boolean = true;
  }

  if($boolean){
    $return['status'] = "success";
    $return['form'] = "getDepartment";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "failed";
    $return['form'] = "getDepartment";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}
// $Sqlx = "INSERT INTO log ( log ) VALUES ('$DocNo : ".$xUsageCode[$i]."')";
// mysqli_query($conn,$Sqlx);

function ShowDocument($conn,$DATA){
  $boolean = false;
  $count = 0;
  $dept = $DATA["dept"];
  $hos = $DATA["hos"];
  $search = $DATA["search"];
  $selecta = $DATA["selecta"];

  $Sql = "SELECT
  item_stock_detail.ItemCode,
  item.ItemName,
  department.DepCode,
  department.DepName,
  site.HptName,
  item_stock_detail.Qty,
  item_category.CategoryName
  FROM
  item_stock_detail
  INNER JOIN item ON item_stock_detail.ItemCode = item.ItemCode
  INNER JOIN department ON item_stock_detail.DepCode = department.DepCode
  INNER JOIN site ON department.HptCode = site.HptCode
  INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode ";
  if ($selecta==0) {
    $Sql.="WHERE site.HptCode = '$hos' AND item_stock_detail.DepCode =  $dept AND item.ItemName LIKE '%$search%' ";
  }
  $Sql.="ORDER BY department.DepCode,item_stock_detail.ItemCode";

  $return['sql'] = $Sql;
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['ItemCode'] 	= $Result['ItemCode'];
    $return[$count]['ItemName'] 	= $Result['ItemName'];
    $return[$count]['CategoryName'] 	= $Result['CategoryName'];
    $return[$count]['DepCode'] 	= $Result['DepCode'];
    $return[$count]['DepName'] 	= $Result['DepName'];
    $return[$count]['Qty'] 	= $Result['Qty'];
    $boolean = true;
    $count++;
  }

  if($boolean){
    $return['status'] = "success";
    $return['form'] = "ShowDocument";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return[$count]['DocNo'] = "";
    $return[$count]['DocDate'] = "";
    $return[$count]['Qty'] = "";
    $return[$count]['Elc'] = "";
    $return['status'] = "failed";
    $return['form'] = "ShowDocument";
    $return['msg'] = "nodetail";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

//==========================================================
//
//==========================================================
if(isset($_POST['DATA']))
{
  $data = $_POST['DATA'];
  $DATA = json_decode(str_replace ('\"','"', $data), true);

  if($DATA['STATUS']=='OnLoadPage'){
    OnLoadPage($conn,$DATA);
  }elseif ($DATA['STATUS']=='getDepartment') {
    getDepartment($conn, $DATA);
  }elseif($DATA['STATUS']=='ShowDocument'){
    ShowDocument($conn,$DATA);
  }

}else{
  $return['status'] = "error";
  $return['msg'] = 'noinput';
  echo json_encode($return);
  mysqli_close($conn);
  die;
}
?>
