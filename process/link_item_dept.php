<?php
session_start();
require '../connect/connect.php';

function getHospital($conn, $DATA)
{
  $count = 0;
  $userid = $DATA['Userid'];
  $Sql = "SELECT
          side.HptCode,
          side.HptName
          FROM side
          WHERE side.IsStatus = 0 AND side.HptCode = (SELECT
					users.HptCode
					FROM
					users
					INNER JOIN employee ON users.ID = employee.EmpCode
					INNER JOIN department ON employee.DepCode = department.DepCode
					INNER JOIN side ON department.HptCode = side.HptCode
          WHERE users.ID = $userid) 
          ";
  //var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptCode'] = $Result['HptCode'];
    $return[$count]['HptName'] = $Result['HptName'];
    $count++;
  }

  if($count>0){
    $return['status'] = "success";
    $return['form'] = "getHospital";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "notfound";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function getDepartment($conn, $DATA)
{
  $count = 0;
  $userid = $DATA['Userid'];
  $Sql = "SELECT
          department.DepCode,
          department.DepName
          FROM
          department
          WHERE department.DepCode = (
          SELECT
          department.DepCode
          FROM
          users
          INNER JOIN employee ON users.ID = employee.EmpCode
          INNER JOIN department ON employee.DepCode = department.DepCode
          WHERE users.ID = $userid ) AND department.IsStatus = 0
          ORDER BY department.DepCode DESC
          ";
  // var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DepCode'] = $Result['DepCode'];
    $return[$count]['DepName'] = $Result['DepName'];
    $count++;
  }

  if($count>0){
    $return['status'] = "success";
    $return['form'] = "getDepartment";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "notfound";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function ShowItem($conn, $DATA)
{
  $count = 0;
  $Keyword = $DATA['Keyword'];
  $userid = $DATA['Userid'];
  $Sql = "SELECT
          item.ItemCode,
          item.ItemName
          FROM
          item
          WHERE item.ItemCode LIKE '%$Keyword%' OR item.ItemName LIKE '%$Keyword%'
          ORDER BY item.ItemCode
          ";
  // var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['ItemCode'] = $Result['ItemCode'];
    $return[$count]['ItemName'] = $Result['ItemName'];
    $count++;
  }

  if($count>0){
    $return['status'] = "success";
    $return['form'] = "ShowItem";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "notfound";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}

function getdetail($conn, $DATA)
{
  $count = 0;
  $UnitCode = $DATA['UnitCode'];
  //---------------HERE------------------//
  $Sql = "SELECT
          item_Unit.UnitCode,
          item_Unit.UnitName,
          CASE item_Unit.IsStatus WHEN 0 THEN '0' WHEN 1 THEN '1' END AS IsStatus
          FROM
          item_Unit
          WHERE item_Unit.IsStatus = 0
          AND item_Unit.UnitCode = $UnitCode LIMIT 1
          ";
  // var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return['UnitCode'] = $Result['UnitCode'];
    $return['UnitName'] = $Result['UnitName'];
    //$return['IsStatus'] = $Result['IsStatus'];
    $count++;
  }

  if($count>0){
    $return['status'] = "success";
    $return['form'] = "getdetail";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "notfound";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}

function getSection($conn, $DATA)
{
  $count = 0;
  $Sql = "SELECT
          department.DepCode,
          department.UnitCode,
          department.DepName,
          department.IsStatus
          FROM
          department";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DepCode']       = $Result['DepCode'];
    $return[$count]['DepName']  = $Result['DepName'];
    $count++;
  }

  $return['status'] = "success";
  $return['form'] = "getSection";
  echo json_encode($return);
  mysqli_close($conn);
  die;

}

function AddItem($conn, $DATA)
{
  $count = 0;
  $Sql = "INSERT INTO item_Unit(
          UnitName,
          IsStatus
         )
          VALUES
          (
            '".$DATA['UnitName']."',
            0
          )
  ";
  // var_dump($Sql); die;
  if(mysqli_query($conn, $Sql)){
    $return['status'] = "success";
    $return['form'] = "AddItem";
    $return['msg'] = "addsuccess";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "failed";
    $return['msg'] = "addfailed";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}

function EditItem($conn, $DATA)
{
  $count = 0;
  if($DATA["UnitCode"]!=""){
    $Sql = "UPDATE item_Unit SET
            UnitCode = '".$DATA['UnitCode']."',
            UnitName = '".$DATA['UnitName']."'
            WHERE UnitCode = ".$DATA['UnitCode']."
    ";
    // var_dump($Sql); die;
    if(mysqli_query($conn, $Sql)){
      $return['status'] = "success";
      $return['form'] = "EditItem";
      $return['msg'] = "editsuccess";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }else{
      $return['status'] = "failed";
      $return['msg'] = "editfailed";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }
  }else{
    $return['status'] = "failed";
    $return['msg'] = "editfailed";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}

function CancelItem($conn, $DATA)
{
  $count = 0;
  if($DATA["UnitCode"]!=""){
    $Sql = "UPDATE item_Unit SET
            IsStatus = 1
            WHERE UnitCode = ".$DATA['UnitCode']."
    ";
    // var_dump($Sql); die;
    if(mysqli_query($conn, $Sql)){
      $return['status'] = "success";
      $return['form'] = "CancelItem";
      $return['msg'] = "cancelsuccess";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }else{
      $return['status'] = "failed";
      $return['msg'] = "cancelfailed";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }
  }else{
    $return['status'] = "failed";
    $return['msg'] = "cancelfailed";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}

function additemstock($conn, $DATA)
{
  $boolean = 0;
  $count = 0;
  $Deptid = $DATA['DeptID'];
  $ParQty = $DATA['Par'];
  $Itemcode = explode(",",$DATA['ItemCode']);
  $Number = explode(",",$DATA['Number']);

  // var_dump($Number[0]); die;
  for ($i=0; $i < sizeof($Itemcode,0) ; $i++) {
    for ($j=0; $j < $Number[$i] ; $j++) {
      $Sql = "INSERT INTO item_stock(ItemCode,DepCode,ParQty,IsStatus)
        VALUES(
          '".$Itemcode[$i]."',
          '$Deptid',
          '$ParQty',
          9
        )";
        if(mysqli_query($conn,$Sql)){
          $boolean++;
        }
    }

  }

  if($boolean>0){
    $return['status'] = "success";
    $return['msg'] = "addsuccess";
    $return['form'] = "additemstock";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "failed";
    $return['msg'] = "addfailmsg";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}

function ShowItemStock($conn, $DATA)
{
  $boolean = 0;
  $count = 0;
  $Deptid = $DATA['Deptid'];
  $Keyword = $DATA['Keyword'];
  $Sql = "SELECT
          item_stock.RowID,
          item_stock.ItemCode,
          item.ItemName,
          item_stock.ParQty,
          DATE(item_stock.ExpireDate) AS ExpireDate
          FROM
          item_stock
          INNER JOIN item ON item_stock.ItemCode = item.ItemCode
          WHERE item_stock.IsStatus = 9 AND item_stock.DepCode = $Deptid AND (item_stock.ItemCode LIKE '%$Keyword%' OR item.ItemName LIKE '%$Keyword%')
          ORDER BY item_stock.RowID DESC";
          $return['sql'] = $Sql;
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['RowID'] = $Result['RowID'];
    $return[$count]['ItemCode'] = $Result['ItemCode'];
    $return[$count]['ItemName'] = $Result['ItemName'];
    $return[$count]['ParQty'] = $Result['ParQty'];
    if($Result['ExpireDate']!=""){
    $tempdate = explode("-",$Result['ExpireDate']);
    $tempdate = $tempdate[2]."/".$tempdate[1]."/".$tempdate[0];
    }else{
      $tempdate = "";
    }
    $return[$count]['ExpireDate'] = $tempdate;
    $count++;
  }

  if($count>0){
    $return['status'] = "success";
    $return['form'] = "ShowItemStock";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "failed";
    $return['msg'] = "refresh";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}

function setdateitemstock($conn, $DATA)
{
  // var_dump($DATA); die;
  $boolean = 0;
  $count = 0;
  $Exp = $DATA['Exp'];
  $Exp = explode("/",$Exp);
  $Exp = $Exp[2]."-".$Exp[1]."-".$Exp[0];
  $RowID = $DATA['RowID'];
  $Sql = "UPDATE item_stock SET ExpireDate = DATE('$Exp') WHERE RowID = $RowID";
  // var_dump($Sql); die;
  if(mysqli_query($conn,$Sql)){
    $count++;
  }
  if($count>0){
    $return['status'] = "success";
    $return['form'] = "setdateitemstock";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "failed";
    $return['msg'] = "addfailed";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}

function Submititemstock($conn, $DATA)
{
  // var_dump($DATA); die;
  $boolean = 0;
  $count = 0;
  $Deptid = $DATA['Deptid'];

  $Sqlsearch = "SELECT
                item_stock.RowID,
                item_stock.ItemCode,
                item.ItemName,
                item_stock.ParQty,
                item_stock.ExpireDate
                FROM
                item_stock
                INNER JOIN item ON item_stock.ItemCode = item.ItemCode
                WHERE item_stock.IsStatus = 9 AND item_stock.DepCode = $Deptid AND item_stock.ExpireDate IS NOT NULL
                GROUP BY item_stock.ItemCode
                ORDER BY item_stock.RowID DESC";
  $meQuery2 = mysqli_query($conn,$Sqlsearch);
  while ($row = mysqli_fetch_assoc($meQuery2)) {

    $Sql = "SELECT
            COUNT(*) AS Cnt
            FROM
            item_stock_detail
            WHERE item_stock_detail.DepCode = $Deptid AND item_stock_detail.ItemCode = '".$row['ItemCode']."'";
    $meQuery = mysqli_query($conn,$Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      if($Result['Cnt']==0){
        $Sqlinsert = "INSERT INTO item_stock_detail(ItemCode,ExpireDate,DepCode,ParQty,Qty)
          VALUES(
            '".$row['ItemCode']."',
            '".$row['ExpireDate']."',
            $Deptid,
            ".$row['ParQty'].",
            0
          )";
          if(mysqli_query($conn,$Sqlinsert)){
            $count++;
          }
      }
    }
  }

  $Sql = "UPDATE item_stock SET IsStatus = 1 WHERE DepCode = $Deptid AND ExpireDate <> '' AND ExpireDate IS NOT NULL";
  // var_dump($Sql); die;
  if(mysqli_query($conn,$Sql)){
    $count++;
  }
  if($count>0){
    $return['status'] = "success";
    $return['msg'] = "success";
    $return['form'] = "Submititemstock";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "failed";
    $return['msg'] = "addfailed";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}

if(isset($_POST['DATA']))
{
  $data = $_POST['DATA'];
  $DATA = json_decode(str_replace ('\"','"', $data), true);

      if ($DATA['STATUS'] == 'ShowItem') {
        ShowItem($conn, $DATA);
      }else if ($DATA['STATUS'] == 'getSection') {
        getSection($conn, $DATA);
      }else if ($DATA['STATUS'] == 'AddItem') {
        AddItem($conn,$DATA);
      }else if ($DATA['STATUS'] == 'EditItem') {
        EditItem($conn,$DATA);
      }else if ($DATA['STATUS'] == 'CancelItem') {
        CancelItem($conn,$DATA);
      }else if ($DATA['STATUS'] == 'getdetail') {
        getdetail($conn,$DATA);
      }else if ($DATA['STATUS'] == 'getDepartment') {
        getDepartment($conn,$DATA);
      }else if ($DATA['STATUS'] == 'getHospital') {
        getHospital($conn,$DATA);
      }else if ($DATA['STATUS'] == 'additemstock') {
        additemstock($conn,$DATA);
      }else if ($DATA['STATUS'] == 'ShowItemStock') {
        ShowItemStock($conn,$DATA);
      }else if ($DATA['STATUS'] == 'setdateitemstock') {
        setdateitemstock($conn,$DATA);
      }else if ($DATA['STATUS'] == 'Submititemstock') {
        Submititemstock($conn,$DATA);
      }

}else{
	$return['status'] = "error";
	$return['msg'] = 'noinput';
	echo json_encode($return);
	mysqli_close($conn);
  die;
}
