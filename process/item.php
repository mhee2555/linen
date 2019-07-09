<?php
session_start();
require '../connect/connect.php';

function ShowItem($conn, $DATA)
{
  $count = 0;
  $Keyword = $DATA['Keyword'];
  $Catagory = $DATA['Catagory'];
  $Sql = "SELECT
            item.ItemCode,
            item.ItemName,
            item_category.CategoryName,
            item_unit.UnitName,
          CASE item.SizeCode
          WHEN '1' THEN 'SS'
          WHEN '2' THEN 'S'
          WHEN '3' THEN 'M'
          WHEN '4' THEN 'L'
          WHEN '5' THEN 'XL'
          WHEN '6' THEN 'XXL' END AS SizeCode,
            item.CusPrice,
            item.FacPrice,
            item.Weight,
            item.Picture
          FROM item
          INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode
          INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode";

    if($Keyword==''){
      $Sql .= " WHERE item.CategoryCode = $Catagory ORDER BY item.ItemCode ASC";
    }else{
      $Sql .= " WHERE item.ItemCode LIKE '%$Keyword%' OR item.ItemName LIKE '%$Keyword%' 
                OR item.Weight LIKE '%$Keyword%' OR item_unit.UnitName LIKE '%$Keyword%' ";
    }
    $return['sql'] = $Sql;

  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['ItemCode'] = $Result['ItemCode'];
    $return[$count]['ItemName'] = $Result['ItemName'];
    $return[$count]['CategoryName'] = $Result['CategoryName'];
    $return[$count]['UnitName'] = $Result['UnitName'];
    $return[$count]['SizeCode'] = $Result['SizeCode'];
    $return[$count]['CusPrice'] = $Result['CusPrice'];
    $return[$count]['FacPrice'] = $Result['FacPrice'];
    $return[$count]['Weight'] = $Result['Weight'];
    $return[$count]['Picture'] = $Result['Picture'];
    $count++;
  }

  if($count>0){
    $return['status'] = "success";
    $return['form'] = "ShowItem";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['form'] = "ShowItem";
    $return['status'] = "failed";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}

function getUnit($conn, $DATA)
{
  $count = 0;
  $Sql = "SELECT
          item_unit.UnitCode,
          item_unit.UnitName,
          item_unit.IsStatus
          FROM
          item_unit
          WHERE item_unit.IsStatus = 0
          ";
  // var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['UnitCode'] = $Result['UnitCode'];
    $return[$count]['UnitName'] = $Result['UnitName'];
    $count++;
  }

  if($count>0){
    $return['status'] = "success";
    $return['form'] = "getUnit";
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

function getCatagory($conn, $DATA)
{
  $count = 0;
  $maincatagory = $DATA['maincatagory'];
  // var_dump($Maincat); die;
  $Sql = "SELECT
          item_category.CategoryCode,
          item_category.CategoryName,
          item_category.IsStatus
          FROM
          item_category
          INNER JOIN item_main_category ON item_category.MainCategoryCode = item_main_category.MainCategoryCode
          WHERE item_category.MainCategoryCode = $maincatagory AND item_category.IsStatus = 0
          ";
  //var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['CategoryCode'] = $Result['CategoryCode'];
    $return[$count]['CategoryName'] = $Result['CategoryName'];
    $count++;
  }

  if($count>0){
    $return['status'] = "success";
    $return['form'] = "getCatagory";
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
  $ItemCode = $DATA['ItemCode'];
  $Sql = "SELECT
          item.ItemCode,
          item.ItemName,
          item.CategoryCode,
          item.UnitCode,
          item_unit.UnitName,
          item.SizeCode,
          item.CusPrice,
          item.FacPrice,
          item.Weight,
          item.Picture,
          item_multiple_unit.RowID,
          U1.UnitName AS MpCode,
          U2.UnitName AS UnitName2,
          Multiply,
          item_multiple_unit.ItemCode
          item
          FROM
          item
          INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode
          INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
          INNER JOIN item_unit AS item_unit2 ON item.SizeCode = item_unit2.UnitCode
          LEFT JOIN item_multiple_unit ON item_multiple_unit.ItemCode = item.ItemCode
          LEFT JOIN item_unit AS U1 ON item_multiple_unit.UnitCode = U1.UnitCode
					LEFT JOIN item_unit AS U2 ON item_multiple_unit.MpCode = U2.UnitCode
          WHERE item.ItemCode = '$ItemCode'
          ";
  // var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['ItemCode'] = $Result['ItemCode'];
    $return[$count]['ItemName'] = $Result['ItemName'];
    $return[$count]['CategoryCode'] = $Result['CategoryCode'];
    $return[$count]['UnitCode'] = $Result['UnitCode'];
    $return[$count]['SizeCode'] = $Result['SizeCode'];
    $return[$count]['CusPrice'] = $Result['CusPrice'];
    $return[$count]['FacPrice'] = $Result['FacPrice'];
    $return[$count]['Weight'] = $Result['Weight'];
    $return[$count]['Picture'] = $Result['Picture'];
    $return[$count]['RowID'] = $Result['RowID'];
    $return[$count]['MpCode'] = $Result['MpCode'];
    $return[$count]['UnitName2'] = $Result['UnitName2'];
    $return[$count]['Multiply'] = $Result['Multiply'];
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

function GetmainCat($conn, $DATA)
{
  $count = 0;
  $Sql = "SELECT
          item_main_category.MainCategoryCode,
          item_main_category.MainCategoryName,
          item_main_category.IsStatus
          FROM
          item_main_category
          WHERE item_main_category.IsStatus = 0
          ";
  // var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['MainCategoryCode'] = $Result['MainCategoryCode'];
    $return[$count]['MainCategoryName'] = $Result['MainCategoryName'];
    $count++;
  }
  if($count>0){
    $return['status'] = "success";
    $return['form'] = "GetmainCat";
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
  // var_dump($DATA); die;
  $Sql = "SELECT COUNT(*) AS Countn
          FROM
          item
          WHERE item.ItemCode = '".$DATA["ItemCode"]."'";
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $boolcount = $Result['Countn'];
  }
  if($boolcount==0){
    $count = 0;
    $Sql = "INSERT INTO item(
            ItemCode,
            CategoryCode,
            ItemName,
            UnitCode,
            SizeCode,
            CusPrice,
            FacPrice,
            Weight
           )
            VALUES
            (
              '".$DATA['ItemCode']."',
              '".$DATA['Catagory']."',
              '".$DATA['ItemName']."',
              '".$DATA['UnitName']."',
              '".$DATA['SizeCode']."',
              '".$DATA['CusPrice']."',
              '".$DATA['FacPrice']."',
              '".$DATA['Weight']."'
            )
    ";
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
  }else{
    $Sql = "UPDATE item SET
            CategoryCode = '".$DATA['Catagory']."',
            ItemName = '".$DATA['ItemName']."',
            UnitCode = '".$DATA['UnitName']."',
            SizeCode = '".$DATA['SizeCode']."',
            CusPrice = '".$DATA['CusPrice']."',
            FacPrice = '".$DATA['FacPrice']."',
            Weight = '".$DATA['Weight']."'
            WHERE ItemCode = '".$DATA['ItemCode']."'
            ";
    if(mysqli_query($conn, $Sql)){
      $return['status'] = "success";
      $return['form'] = "AddItem";
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
  }
}

function AddUnit($conn, $DATA)
{
  $count = 0;
  $Sql = "INSERT INTO item_multiple_unit(
          MpCode,
          UnitCode,
          Multiply,
          ItemCode
        )
        VALUES
        (
          ".$DATA['MpCode'].",
          ".$DATA['UnitCode'].",
          ".$DATA['Multiply'].",
          '".$DATA['ItemCode']."'
        )
  ";
  // var_dump($Sql); die;
  if(mysqli_query($conn, $Sql)){
    $return['status'] = "success";
    $return['form'] = "AddUnit";
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
  if($DATA["ItemCode"]!=""){
    $Sql = "DELETE FROM item
            WHERE ItemCode = '".$DATA['ItemCode']."'
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

function DeleteUnit($conn, $DATA)
{
  $count = 0;
  if($DATA["RowID"]!=""){
    $Sql = "DELETE FROM item_multiple_unit
            WHERE RowID = ".$DATA['RowID']."
    ";
    // var_dump($Sql); die;
    if(mysqli_query($conn, $Sql)){
      $return['status'] = "success";
      $return['form'] = "CancelUnit";
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

if(isset($_POST['DATA']))
{
  $data = $_POST['DATA'];
  $DATA = json_decode(str_replace ('\"','"', $data), true);

      if ($DATA['STATUS'] == 'ShowItem') {
        ShowItem($conn, $DATA);
      }else if ($DATA['STATUS'] == 'getCatagory') {
        getCatagory($conn, $DATA);
      }else if ($DATA['STATUS'] == 'getUnit') {
        getUnit($conn, $DATA);
      }else if ($DATA['STATUS'] == 'AddItem') {
        AddItem($conn,$DATA);
      }else if ($DATA['STATUS'] == 'AddUnit') {
        AddUnit($conn,$DATA);
      }else if ($DATA['STATUS'] == 'EditItem') {
        EditItem($conn,$DATA);
      }else if ($DATA['STATUS'] == 'CancelItem') {
        CancelItem($conn,$DATA);
      }else if ($DATA['STATUS'] == 'DeleteUnit') {
        DeleteUnit($conn,$DATA);
      }else if ($DATA['STATUS'] == 'getdetail') {
        getdetail($conn,$DATA);
      }else if ($DATA['STATUS'] == 'GetmainCat') {
        GetmainCat($conn,$DATA);
      }

}else{
	$return['status'] = "error";
	$return['msg'] = 'noinput';
	echo json_encode($return);
	mysqli_close($conn);
  die;
}
