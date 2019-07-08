<?php
session_start();
require '../connect/connect.php';

function CreateDoc($conn, $DATA)
{
    $count = 0;
    $HptCode = $DATA['HptCode'];
    $xDate = $DATA['xDate'];

    $Sql = "SELECT CONCAT('CD',lpad('$HptCode', 3, 0),'/',SUBSTRING(YEAR(DATE(NOW())),3,4),LPAD(MONTH(DATE(NOW())),2,0),'-',
          LPAD( (COALESCE(MAX(CONVERT(SUBSTRING(DocNo,12,5),UNSIGNED INTEGER)),0)+1) ,5,0)) AS DocNo,DATE(NOW()) AS DocDate,
          CURRENT_TIME() AS RecNow
          FROM category_price_time
          WHERE DocNo Like CONCAT('CD',lpad('$HptCode', 3, 0),'/',SUBSTRING(YEAR(DATE(NOW())),3,4),LPAD(MONTH(DATE(NOW())),2,0),'%')
          AND HptCode = '$HptCode'
          ORDER BY DocNo DESC LIMIT 1";

    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
        $DocNo = $Result['DocNo'];
        $return['DocNo'] = $DocNo;

    }

    $Sql = "SELECT item_category.CategoryCode,category_price.Price
            FROM item_main_category
            INNER JOIN item_category ON item_category.MainCategoryCode = item_main_category.MainCategoryCode
            INNER JOIN category_price ON category_price.CategoryCode = item_category.CategoryCode
            WHERE item_category.IsStatus = 0";

    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
        $CategoryCode[$count] = $Result['CategoryCode'];
        $Price[$count] = $Result['Price'];
        $count++;
    }

    for($i=0;$i<$count;$i++){
        $Sql_Insert = "INSERT INTO category_price_time (DocNo,xDate,HptCode,CategoryCode,Price,Cnt) VALUES ('$DocNo','$xDate','$HptCode',".$CategoryCode[$i].",".$Price[$i].",$count)";
        mysqli_query($conn, $Sql_Insert);
    }

    if($count>0){
        $return['status'] = "success";
        $return['form'] = "CreateDoc";
        echo json_encode($return);
        mysqli_close($conn);
        die;
    }else{
        $return[0]['RowID'] = "";
        $return[0]['HptName'] = "";
        $return[0]['MainCategoryName'] = "";
        $return[0]['CategoryName'] = "";
        $return[0]['Price'] = "";
        $return['status'] = "success";
        $return['form'] = "CreateDoc";
        $return['msg'] = $Sql;
        echo json_encode($return);
        mysqli_close($conn);
        die;
    }
}

function ShowDoc($conn, $DATA)
{
    $count = 0;
    $HptCode = $DATA['HptCode'];
    $Sql="SELECT category_price_time.DocNo,
                    category_price_time.xDate,
                    site.HptCode,site.HptName
            FROM category_price_time
            INNER JOIN site ON site.HptCode = category_price_time.HptCode
            WHERE site.HptCode = '$HptCode'
            GROUP BY site.HptCode,category_price_time.xDate,category_price_time.DocNo
            ORDER BY category_price_time.RowID DESC";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
        $return[$count]['DocNo'] = $Result['DocNo'];
        $return[$count]['xDate'] = $Result['xDate'];
        $return[$count]['HptName'] = $Result['HptName'];
        $return[$count]['HptCode'] = $Result['HptCode'];
        $count++;
    }
    $return['xCnt'] = $count;
    if($count>0){
        $return['status'] = "success";
        $return['form'] = "ShowDoc";
        echo json_encode($return);
        mysqli_close($conn);
        die;
    }else{
        $return['status'] = "success";
        $return['form'] = "ShowDoc";
        $return['msg'] = "";
        echo json_encode($return);
        mysqli_close($conn);
        die;
    }
}

function ShowItem1($conn, $DATA)
{
  $count = 0;
  $xHptCode = $DATA['HptCode'];
  $CgMainID = $DATA['CgMainID'];
  $CgSubID = $DATA['CgSubID'];
  $Chk = $DATA['chk'];

  $Sql = "SELECT category_price.RowID,
            site.HptName,
            item_main_category.MainCategoryName,
            item_category.CategoryName,
            category_price.Price
        FROM category_price
        INNER JOIN site ON site.HptCode = category_price.HptCode
        INNER JOIN item_category ON item_category.CategoryCode = category_price.CategoryCode
        INNER JOIN item_main_category ON item_main_category.MainCategoryCode = item_category.MainCategoryCode ";
    if($Chk==1){
        $Sql .= "WHERE site.HptCode = '$xHptCode'";
    }else if($Chk==2){
        $Sql .= "WHERE site.HptCode = '$xHptCode' AND item_main_category.MainCategoryCode = $CgMainID";
    }else if($Chk==3){
        $Sql .= "WHERE site.HptCode = '$xHptCode' AND item_main_category.MainCategoryCode = $CgMainID AND category_price.CategoryCode = $CgSubID";
    }
    $return['sql'] = $Sql;

  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['RowID'] = $Result['RowID'];
    $return[$count]['HptName'] = $Result['HptName'];
    $return[$count]['MainCategoryName'] = $Result['MainCategoryName'];
	$return[$count]['CategoryName'] = $Result['CategoryName'];
    $return[$count]['Price'] = $Result['Price'];
    $count++;
  }

  if($count>0){
    $return['status'] = "success";
    $return['form'] = "ShowItem1";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
      $return[0]['RowID'] = "";
      $return[0]['HptName'] = "";
      $return[0]['MainCategoryName'] = "";
      $return[0]['CategoryName'] = "";
      $return[0]['Price'] = "";
    $return['status'] = "success";
    $return['form'] = "ShowItem1";
    $return['msg'] = $Sql;
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function ShowItem2($conn, $DATA)
{
    $count = 0;
    $xHptCode = $DATA['HptCode'];
    $DocNo = $DATA['DocNo'];
    if($xHptCode=="")  $xHptCode = 1;
    $Keyword = $DATA['Keyword'];
    if($Keyword=="")  $Keyword = "%";

    $Sql = "SELECT
        category_price_time.RowID,
        site.HptName,
        item_main_category.MainCategoryName,
        item_category.CategoryName,
        category_price_time.Price
        FROM category_price_time
        INNER JOIN item_category ON item_category.CategoryCode = category_price_time.CategoryCode
        INNER JOIN item_main_category ON item_main_category.MainCategoryCode = item_category.MainCategoryCode
        INNER JOIN site ON site.HptCode = category_price_time.HptCode 
        WHERE category_price_time.DocNo = '$DocNo' AND item_category.CategoryName LIKE '%$Keyword%'
        ORDER BY category_price_time.RowID ASC";

    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
        $return[$count]['RowID'] = $Result['RowID'];
        $return[$count]['HptName'] = $Result['HptName'];
        $return[$count]['MainCategoryName'] = $Result['MainCategoryName'];
        $return[$count]['CategoryName'] = $Result['CategoryName'];
        $return[$count]['Price'] = $Result['Price'];
        $count++;
    }

    if($count>0){
        $return['status'] = "success";
        $return['form'] = "ShowItem2";
        //$return['msg'] = $Sql;
        echo json_encode($return);
        mysqli_close($conn);
        die;
    }else{
        $return['status'] = "success";
        $return['form'] = "ShowItem2";
        //$return['msg'] = $Sql;
        echo json_encode($return);
        mysqli_close($conn);
        die;
    }
}

function getdetail($conn, $DATA)
{
  $count = 0;
  $RowID = $DATA['RowID'];
  //---------------HERE------------------//
  $Sql = "SELECT category_price.RowID,
                    site.HptName,
                    item_main_category.MainCategoryName,
                    item_category.CategoryName,
                    category_price.Price
        FROM category_price
        INNER JOIN site ON site.HptCode = category_price.HptCode
        INNER JOIN item_category ON item_category.CategoryCode = category_price.CategoryCode
        INNER JOIN item_main_category ON item_main_category.MainCategoryCode = item_category.MainCategoryCode
        WHERE category_price.RowID = $RowID";
  // var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
      $return['RowID'] = $Result['RowID'];
      $return['HptName'] = $Result['HptName'];
      $return['MainCategoryName'] = $Result['MainCategoryName'];
      $return['CategoryName'] = $Result['CategoryName'];
      $return['Price'] = $Result['Price'];
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

function getHotpital($conn, $DATA)
{
  $count = 0;
  $Sql = "SELECT
          site.HptCode,
          site.HptName
          FROM
          site
					WHERE IsStatus = 0";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptCode']  = $Result['HptCode'];
    $return[$count]['HptName']  = $Result['HptName'];
    $count++;
  }

  $return['status'] = "success";
  $return['form'] = "getHotpital";
  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function getCategoryMain($conn, $DATA)
{
  $count = 0;
  $Sql = "SELECT
          item_main_category.MainCategoryCode,
          item_main_category.MainCategoryName
          FROM item_main_category
					WHERE IsStatus = 0";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['MainCategoryCode']  = $Result['MainCategoryCode'];
    $return[$count]['MainCategoryName']  = $Result['MainCategoryName'];
    $count++;
  }

  $return['status'] = "success";
  $return['form'] = "getCategoryMain";
  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function getCategorySub($conn, $DATA)
{
  $count = 0;
  $CgrID = $DATA['CgrID'];
  $Sql = "SELECT item_category.CategoryCode,item_category.CategoryName
  FROM item_main_category
  INNER JOIN item_category ON item_main_category.MainCategoryCode = item_category.MainCategoryCode
  WHERE item_category.IsStatus = 0
  AND item_category.MainCategoryCode = $CgrID";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['CategoryCode']  = $Result['CategoryCode'];
    $return[$count]['CategoryName']  = $Result['CategoryName'];
    $count++;
  }

  $return['status'] = "success";
  $return['form'] = "getCategorySub";
  echo json_encode($return);
  mysqli_close($conn);
  die;

}

function SavePrice($conn, $DATA)
{
  $RowID = $DATA['RowID'];
  $Price = $DATA['Price'];
  $Sel = $DATA['Sel'];
  $DocNo = $DATA['DocNo'];

    $Sql = "SELECT COUNT(*) AS Cnt
        FROM category_price_time
        WHERE category_price_time.RowID = '$RowID'";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
        $Cnt = $Result['Cnt'];
    }
    $UpdatePrice = "UPDATE category_price SET Price = $Price WHERE RowID = $RowID";
    $Sql = "UPDATE category_price_time SET Price = $Price WHERE RowID = $RowID";

    mysqli_query($conn, $UpdatePrice);
  if(mysqli_query($conn, $Sql)){
    $return['status'] = "success";
    $return['Cnt'] = $Cnt;
    $return['Sel'] = $Sel;
    $return['form'] = "SavePrice";
    $return['msg'] = "Save Success...";
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

function CheckPrice($conn,$HptCode,$CategoryCode)
{
    $Cnt = 0;
    $Sql = "SELECT COUNT(*) AS Cnt FROM category_price WHERE HptCode = '$HptCode' AND CategoryCode = $CategoryCode";
//    echo 'console.log('.$Sql.')';
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
        $Cnt = $Result['Cnt'];
    }
    return $Cnt;
}

function UpdatePrice($conn, $DATA)
{
    $DocNo = $DATA['DocNo'];
    $count = 0;
    $chk = $DATA['chk'];
    if($chk != 1){
        $Sql = "SELECT category_price_time.HptCode,category_price_time.CategoryCode,category_price_time.Price
            FROM category_price_time
            WHERE category_price_time.DocNo = '$DocNo'";
        $meQuery = mysqli_query($conn, $Sql);
        while ($Result = mysqli_fetch_assoc($meQuery)) {
            $HptCode = $Result['HptCode'];
            $CategoryCode = $Result['CategoryCode'];
            $Price = $Result['Price'];

            $InsertSql = "INSERT INTO category_price (HptCode,CategoryCode,Price) VALUES ('$HptCode',$CategoryCode,$Price)";
            mysqli_query($conn, $InsertSql);

            $count++;
        }
    }else if($chk == 1){
        $i = 0;
        $Price = $DATA['Price'];
        $Sql = "SELECT category_price_time.RowID,category_price_time.HptCode,category_price_time.CategoryCode
            FROM category_price_time
            WHERE category_price_time.DocNo = '$DocNo'";
        $meQuery = mysqli_query($conn, $Sql);
        while ($Result = mysqli_fetch_assoc($meQuery)) {
            $HptCode = $Result['HptCode'];
            $RowID = $Result['RowID'];
            $UpdateSql = "UPDATE category_price_time SET Price = $Price[$i] WHERE RowID = $RowID AND DocNo = '$DocNo'";
            mysqli_query($conn, $UpdateSql);
            $count++;
            $i++;
        }
    }
    $return['xCnt'] = $count;

    $return['status'] = "success";
    $return['form'] = "UpdatePrice";
    $return['msg'] = $Sql;
    echo json_encode($return);
    mysqli_close($conn);
    die;
}

function CancelItem($conn,$DATA)
{
    $DocNo = $DATA['DocNo'];
    if($DocNo!=""){
        $Sql = "DELETE FROM category_price_time WHERE DocNo = '$DocNo'";
        // var_dump($Sql); die;
        if(mysqli_query($conn, $Sql)){
            $return['status'] = "success";
            $return['form'] = "CancelItem";
            $return['msg'] = "Delete Success...";
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
      if ($DATA['STATUS'] == 'CreateDoc') {
          CreateDoc($conn, $DATA);
      }else if ($DATA['STATUS'] == 'ShowDoc') {
          ShowDoc($conn, $DATA);
      }else if ($DATA['STATUS'] == 'ShowItem1') {
          ShowItem1($conn, $DATA);
      }else if ($DATA['STATUS'] == 'ShowItem2') {
        ShowItem2($conn, $DATA);
      }else if ($DATA['STATUS'] == 'ShowItemPrice') {
        ShowItemPrice($conn, $DATA);
      }else if ($DATA['STATUS'] == 'UpdatePrice') {
        UpdatePrice($conn, $DATA);
      }else if ($DATA['STATUS'] == 'getHotpital') {
        getHotpital($conn, $DATA);
      }else if ($DATA['STATUS'] == 'getCategoryMain') {
        getCategoryMain($conn, $DATA);
      }else if ($DATA['STATUS'] == 'getCategorySub') {
        getCategorySub($conn, $DATA);
      }else if ($DATA['STATUS'] == 'SavePrice') {
        SavePrice($conn,$DATA);
      }else if ($DATA['STATUS'] == 'EditItem') {
        EditItem($conn,$DATA);
      }else if ($DATA['STATUS'] == 'CancelItem') {
        CancelItem($conn,$DATA);
      }else if ($DATA['STATUS'] == 'getdetail') {
        getdetail($conn,$DATA);
      }

}else{
	$return['status'] = "error";
	$return['msg'] = 'noinput';
	echo json_encode($return);
	mysqli_close($conn);
  die;
}
