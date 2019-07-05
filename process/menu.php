<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");
$xDate = date('Y-m-d');

function OnLoadPage($conn,$DATA){
  $count = 0;
  $boolean = false;
  $Sql = "SELECT
  shelfcount.DocNo,
  shelfcount.DocDate,
  department.DepName,
  site.HptName,
  shelfcount.IsStatus,
  shelfcount.RefDocNo,
  shelfcount.Detail
  FROM shelfcount
  INNER JOIN department ON shelfcount.DepCode = department.DepCode
  INNER JOIN site ON department.HptCode = site.HptCode
  WHERE shelfcount.IsStatus = 0
  ORDER BY shelfcount.DocNo DESC";
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DocNo'] = $Result['DocNo'];
    $return[$count]['RefDocNo'] = $Result['RefDocNo'];
    $return[$count]['Detail'] = $Result['Detail'];
    $return[$count]['DepName'] = $Result['DepName'];
    $return[$count]['HptName'] = $Result['HptName'];
    $return[$count]['DocDate'] = $Result['DocDate'];
    $return[$count]['IsStatus'] = $Result['IsStatus'];
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

function getnotification($conn,$DATA){
  //  var_dump($DATA);
  $count = 0;
  $boolean = false;
  $Sql = "SELECT COUNT(*) AS Cnt
  FROM shelfcount
  WHERE IsRequest = 0";
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return['shelfcount_Cnt'] = $Result['Cnt'];
    $boolean = true;
  }

  $Sql = "SELECT COUNT(*) AS Cnt
  FROM factory_out
  WHERE IsRequest = 0";
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return['fac_out_Cnt'] = $Result['Cnt'];
    $boolean = true;
  }

  $Sql = "SELECT COUNT(*) AS Cnt
  FROM daily_request WHERE DATE(daily_request.DocDate) = DATE(NOW())";
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return['daily_request_Cnt'] = $Result['Cnt'];
    $boolean = true;
  }

  if($boolean){
    $return['status'] = "success";
    $return['form'] = "getnotification";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "failed";
    $return['form'] = "getnotification";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function alert_SetPrice($conn,$DATA)
{
  $PmID = $DATA['PmID'];
  $HptCode = $DATA['HptCode'];
  $Userid = $DATA['Userid'];
  $boolean = false;
  $count = 0;
  if($PmID == 1){
    $Sql = "SELECT cat_P.DocNo, 
    CURDATE() AS cur, 
    cat_P.xDate, 
    DATEDIFF(cat_P.xDate+1, CURDATE()) AS dateDiff,
    site.HptName,
    item_category.CategoryName
    FROM category_price_time cat_P
    INNER JOIN site ON site.HptCode = cat_P.HptCode
    INNER JOIN item_category ON item_category.CategoryCode = cat_P.CategoryCode
    WHERE DATEDIFF(cat_P.xDate+1, CURDATE()) = 30 
     OR DATEDIFF(cat_P.xDate+1, CURDATE()) = 7 
     OR DATEDIFF(cat_P.xDate+1, CURDATE()) = 6 
     OR DATEDIFF(cat_P.xDate+1, CURDATE()) = 5 
     OR DATEDIFF(cat_P.xDate+1, CURDATE()) = 4 
     OR DATEDIFF(cat_P.xDate+1, CURDATE()) = 3
     OR DATEDIFF(cat_P.xDate+1, CURDATE()) = 2
     OR DATEDIFF(cat_P.xDate+1, CURDATE()) = 1
    GROUP BY cat_P.DocNo ORDER BY cat_P.xDate";
  }else{
      $Sql = "SELECT cat_P.DocNo, 
      CURDATE() AS cur, 
      cat_P.xDate, 
      site.HptName,
      item_category.CategoryName,
      CASE
          WHEN DATEDIFF(cat_P.xDate+1, CURDATE()) = 30 THEN 30
          WHEN DATEDIFF(cat_P.xDate+1, CURDATE()) = 7 THEN 7
          WHEN DATEDIFF(cat_P.xDate+1, CURDATE()) = 6 THEN 6
          WHEN DATEDIFF(cat_P.xDate+1, CURDATE()) = 5 THEN 5
          WHEN DATEDIFF(cat_P.xDate+1, CURDATE()) = 4 THEN 4
          WHEN DATEDIFF(cat_P.xDate+1, CURDATE()) = 3 THEN 3
          WHEN DATEDIFF(cat_P.xDate+1, CURDATE()) = 2 THEN 2
          WHEN DATEDIFF(cat_P.xDate+1, CURDATE()) = 1 THEN 1
      END AS dateDiff
      FROM category_price_time cat_P
      INNER JOIN users ON users.ID = $Userid 
      INNER JOIN site ON site.HptCode = '$HptCode'
      INNER JOIN item_category ON item_category.CategoryCode = cat_P.CategoryCode
      WHERE cat_P.HptCode = '$HptCode'
      GROUP BY cat_P.DocNo ORDER BY cat_P.xDate";
  }
  $return['sql'] = $Sql;
  $meQuery = mysqli_query($conn,$Sql);

  while ($Result = mysqli_fetch_assoc($meQuery)) {
    if($Result['dateDiff']!=null){
      $date = explode('-',$Result['xDate']);
      $newDate = $date[2].'-'.$date[1].'-'.$date[0];
      $return[$count]['DocNo'] = $Result['DocNo'];
      $return[$count]['HptName'] = $Result['HptName'];
      $return[$count]['CategoryName'] = $Result['CategoryName'];
      $return[$count]['DateDiff'] = $Result['dateDiff'];
      $return[$count]['newDate'] = $newDate;
      $return[$count]['newDate'] = $newDate;
      $count++;
      $boolean = true; 
    }
    
  }

  $return['countRow'] = $count;

  if($boolean){
    $return['status'] = "success";
    $return['form'] = "alert_SetPrice";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "failed";
    $return['form'] = "alert_SetPrice";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}
//==========================================================
//==========================================================
if(isset($_POST['DATA']))
{
  $data = $_POST['DATA'];
  $DATA = json_decode(str_replace ('\"','"', $data), true);

  if($DATA['STATUS']=='OnLoadPage'){
    OnLoadPage($conn,$DATA);
  }elseif ($DATA['STATUS']=='getnotification') {
    getnotification($conn,$DATA);
  }elseif ($DATA['STATUS']=='alert_SetPrice') {
    alert_SetPrice($conn,$DATA);
  }
}else{
  $return['status'] = "error";
  $return['msg'] = 'noinput';
  echo json_encode($return);
  mysqli_close($conn);
  die;
}
?>
