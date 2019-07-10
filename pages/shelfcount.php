<?php
session_start();
$Userid = $_SESSION['Userid'];
$TimeOut = $_SESSION['TimeOut'];
$PmID = $_SESSION['PmID'];
$HptCode = $_SESSION['HptCode'];

$DocnoXXX = $_GET['DocNo'];
if($Userid==""){
  header("location:../index.html");
}

if(empty($_SESSION['lang'])){
  $language ='th';
}else{
  $language =$_SESSION['lang'];

}

header ('Content-type: text/html; charset=utf-8');
$xml = simplexml_load_file('../xml/general_lang.xml');
$json = json_encode($xml);
$array = json_decode($json,TRUE);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>shelfcount</title>

  <link rel="icon" type="image/png" href="../img/pose_favicon.png">
  <!-- Bootstrap core CSS-->
  <link href="../template/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../bootstrap/css/tbody.css" rel="stylesheet">
  <link href="../bootstrap/css/myinput.css" rel="stylesheet">

  <!-- Custom fonts for this template-->
  <link href="../template/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Page level plugin CSS-->
  <link href="../template/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../template/css/sb-admin.css" rel="stylesheet">
  <link href="../css/xfont.css" rel="stylesheet">

  <!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
  <script src="../jQuery-ui/jquery-1.12.4.js"></script>
  <script src="../jQuery-ui/jquery-ui.js"></script>
  <script type="text/javascript">
  jqui = jQuery.noConflict(true);
  </script>

  <link href="../dist/css/sweetalert2.min.css" rel="stylesheet">
  <script src="../dist/js/sweetalert2.min.js"></script>
  <script src="../dist/js/jquery-3.3.1.min.js"></script>
  <link href="../css/responsive.css" rel="stylesheet">

  <link href="../datepicker/dist/css/datepicker.min.css" rel="stylesheet" type="text/css">
  <script src="../datepicker/dist/js/datepicker.min.js"></script>
  <!-- Include English language -->
  <script src="../datepicker/dist/js/i18n/datepicker.en.js"></script>

  <script type="text/javascript">
  var summary = [];
  var xItemcode;

  $(document).ready(function(e){
    $("#bSend").hide();
    OnLoadPage();
    getDepartment();
    ShowMenu();

  }).mousemove(function(e) { parent.last_move = new Date();;
  }).keyup(function(e) { parent.last_move = new Date();;
  });

  jqui(document).ready(function($){


    dialogUsageCode = jqui( "#dialogUsageCode" ).dialog({
      autoOpen: false,
      height: 680,
      width: 1200,
      modal: true,
      buttons: {
        "<?php echo $array['close'][$language]; ?>": function() {
          dialogUsageCode.dialog( "close" );
        }
      },
      close: function() {
        console.log("close");
      }
    });

    // dialog1 = jqui( "#dialogListDetail" ).dialog({
    //   autoOpen: false,
    //   height: 650,
    //   width: 1200,
    //   modal: true,
    //   buttons: {
    //     "<?php echo $array['close'][$language]; ?>": function() {
    //       dialog1.dialog( "close" );
    //     }
    //   },
    //   close: function() {
    //     console.log("close");
    //   }
    // });

    //    jqui( "#dialogItem" ).button().on( "click", function() {
    //      dialog.dialog( "open" );
    //    });

  });


  function ShowMenu(){
    var DocnoXXX = "<?php echo $DocnoXXX ?>";
    if( DocnoXXX != "" ){

    // alert(DocnoXXX);

    var data = {
        'STATUS'  : 'ShowMenu',
        'DocnoXXX'   : DocnoXXX
      }
      senddata(JSON.stringify(data));

      };
  }


  
function OpenDialogItem(){
      var docno = $("#docno").val();
      if( docno != "" ){
        $( "#TableItem tbody" ).empty();
        // dialogItemCode.dialog( "open" );
        $('#dialogItemCode').modal('show');

      }
}

 
  function OpenDialogUsageCode(itemcode){
    xItemcode = itemcode;
    var docno = $("#docno").val();
    if( docno != "" ){
      dialogItemCode.dialog( "close" );
      dialogUsageCode.dialog( "open" );
      $( "#TableItem tbody" ).empty();
      ShowUsageCode();
    }
  }

  function ShowDetailSub() {
    var docno = $("#docno").val();
    if( docno != "" )  $('#dialogListDetail').modal('show');
    var data = {
      'STATUS'  : 'ShowDetailSub',
      'DocNo'   : docno
    };
    console.log(JSON.stringify(data));
    senddata(JSON.stringify(data));
  }

  function ShowUsageCode(){
    // var searchitem = $('#searchitem1').val();
    // alert( xItemcode );
    var docno = $("#docno").val();
    var data = {
      'STATUS'  : 'ShowUsageCode',
      'docno'	: docno,
      'xitem'	: xItemcode
    };
    senddata(JSON.stringify(data));
  }

  function DeleteItem(){
    var docno = $("#docno").val();
    var xrow = $("#checkrow:checked").val() ;

    xrow = xrow.split(",");
    swal({
      title: "<?php echo $array['confirm'][$language]; ?>",
      text: "<?php echo $array['confirm1'][$language]; ?>"+xrow[1]+"<?php echo $array['confirm2'][$language]; ?>",
      type: "warning",
      showCancelButton: true,
      confirmButtonClass: "btn-danger",
      confirmButtonText: "<?php echo $array['confirm'][$language]; ?>",
      cancelButtonText: "<?php echo $array['cancel'][$language]; ?>",
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      closeOnConfirm: false,
      closeOnCancel: false,
      showCancelButton: true}).then(result => {
        var data = {
          'STATUS'    : 'DeleteItem',
          'rowid'  : xrow[0],
          'DocNo'   : docno
        };
        senddata(JSON.stringify(data));
      })
    }

  function CancelDocument(){
      var docno = $("#docno").val();

      swal({
          title: "<?php echo $array['confirm'][$language]; ?>",
          text: "<?php echo $array['canceldata4'][$language];?> "+docno+" ?",
          type: "warning",
          showCancelButton: true,
          confirmButtonClass: "btn-danger",
          confirmButtonText: "<?php echo $array['confirm' ][$language]; ?>",
          cancelButtonText: "<?php echo $array['cancel'][$language]; ?>",
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          closeOnConfirm: false,
          closeOnCancel: false,
          showCancelButton: true}).then(result => {
          CancelBill();
      })
  }

    //======= On create =======
    //console.log(JSON.stringify(data));
    function OnLoadPage(){
      var data = {
        'STATUS'  : 'OnLoadPage'
      };
      senddata(JSON.stringify(data));
      $('#isStatus').val(0)
    }

    function getDepartment(){
      var Hotp = $('#hotpital option:selected').attr("value");
      if( typeof Hotp == 'undefined' ) 
      {
        Hotp = '<?php echo $HptCode; ?>';
      var data = {
        'STATUS'  : 'getDepartment',
        'Hotp'	: Hotp
      };

      senddata(JSON.stringify(data));
      }
    }
    function ShowDocument(selecta){
      var searchdocument = $('#searchdocument').val();
      if( typeof searchdocument == 'undefined' ) searchdocument = "";
      var hosCode = $('#side option:selected').attr("value");
      if( typeof hosCode == 'undefined' ) hosCode = "1";
      var deptCode = $('#Dep2 option:selected').attr("value");
      if( typeof deptCode == 'undefined' ) deptCode = "1";

      var data = {
        'STATUS'  	: 'ShowDocument',
        'xdocno'	: searchdocument,
        'selecta' : selecta,
        'deptCode'	: deptCode,
        'hosCode' :   hosCode

      };
      console.log(data);
      senddata(JSON.stringify(data));
    }

    function ShowDocument_sub(){
      var searchdocument = $('#searchdocument').val();
      if( typeof searchdocument == 'undefined' ) searchdocument = "";
      var deptCode = $('#Dep2 option:selected').attr("value");
      if( typeof deptCode == 'undefined' ) deptCode = "1";

      var data = {
        'STATUS'  	: 'ShowDocument_sub',
        'xdocno'	: searchdocument,
        'deptCode'	: deptCode
      };
      senddata(JSON.stringify(data));
    }

    function ShowItem(){
      var deptCode = $('#department option:selected').attr("value");
      var searchitem = $('#searchitem').val();
      var data = {
        'STATUS'  : 'ShowItem',
        'xitem'	  : searchitem,
        'deptCode'	  : deptCode

      };
      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }

    function SelectDocument(){
      var selectdocument = "";
      $("#checkdocno:checked").each(function() {
        selectdocument = $(this).val();
      });
      var deptCode = $('#Dep2 option:selected').attr("value");
      if( typeof deptCode == 'undefined' ) deptCode = "1";

      var data = {
        'STATUS'  	: 'SelectDocument',
        'xdocno'	: selectdocument
      };
      senddata(JSON.stringify(data));
    }

    function unCheckDocDetail(){
      // alert( $('input[name="checkdocno"]:checked').length + " :: " + $('input[name="checkdocno"]').length );
      if ($('input[name="checkdocdetail"]:checked').length == $('input[name="checkdocdetail"]').length){
        $('input[name="checkAllDetail').prop('checked',true);
      }else {
        $('input[name="checkAllDetail').prop('checked',false);
      }
    }

    function ShowDetail() {
      var docno = $("#docno").val();
      var data = {
        'STATUS'  : 'ShowDetail',
        'DocNo'   : docno
      };
      senddata(JSON.stringify(data));
    }

    function CancelBill() {
      var docno = $("#docno").val();
      var data = {
        'STATUS'  : 'CancelBill',
        'DocNo'   : docno
      };
      senddata(JSON.stringify(data));
      $('#profile-tab').tab('show');
        ShowDocument();
    }

    function getImport(Sel) {
      //alert(Sel);
      var docno = $("#docno").val();
      /* declare an checkbox array */
      var iArray = [];
      var qtyArray = [];
      var chkArray = [];
      var weightArray = [];
      var unitArray = [];
      var i=0;

      if(Sel==1){
        $("#checkitem:checked").each(function() {
          iArray.push($(this).val());
        });
      }else{
        $("#checkitemSub:checked").each(function() {
          iArray.push($(this).val());
        });
      }

      /* we join the array separated by the comma */

      for(var j=0;j<iArray.length; j++){
        if(Sel==1)
        chkArray.push( $("#RowID"+iArray[j]).val() );
        else
        chkArray.push( $("#RowIDSub"+iArray[j]).val() );

        qtyArray.push( $("#iqty"+iArray[j]).val() );
        weightArray.push( $("#iweight"+iArray[j]).val() );
        unitArray.push( $("#iUnit_"+iArray[j]).val() );
      }

      var xrow = chkArray.join(',') ;
      var xqty = qtyArray.join(',') ;
      var xweight = weightArray.join(',') ;
      var xunit = unitArray.join(',') ;

      var deptCode = $('#department option:selected').attr("value");

      // alert("xrow : "+xrow);
      //	  alert("xqty : "+xqty);
      //	  alert("xweight : "+xweight);
      //	  alert("xunit : "+xunit);

      $('#TableDetail tbody').empty();
      var data = {
        'STATUS'  : 'getImport',
        'xrow'		: xrow,
        'xqty'		: xqty,
        'xweight'	: xweight,
        'xunit'		: xunit,
        'DocNo'   : docno,
        'Sel'     : Sel,
        'deptCode'     : deptCode
      };
      senddata(JSON.stringify(data));
          $('#dialogItemCode').modal('toggle')
      dialogUsageCode.dialog( "close" );
    }

    var isChecked1 = false;
    var isChecked2 = false;
    function getCheckAll(sel){
      if(sel==0){
        isChecked1 = !isChecked1;
        $('input[name="checkdocno"]').each(function(){
          this.checked = isChecked1;
        });
        getDocDetail();
      }else{
        isChecked2 = !isChecked2;
        $('input[name="checkdocdetail"]').each(function(){
          this.checked = isChecked2;
        });
      }
    }

    function convertUnit(rowid,selectObject){
      var docno = $("#docno").val();
      var data = selectObject.value;
      var chkArray = data.split(",");
      var weight = $('#weight_'+chkArray[0]).val();
      var qty = $('#qty1_'+chkArray[0]).val();
      qty = oleqty*chkArray[2];
      $('#qty1_'+chkArray[0]).val(qty);

      var data = {
        'STATUS'  	: 'updataDetail',
        'Rowid'     : rowid,
        'DocNo'   	: docno,
        'unitcode'	: chkArray[1],
        'qty'		: qty
      };
      senddata(JSON.stringify(data));
    }

    function CreateDocument(){

      var userid = '<?php echo $Userid; ?>';
      var hotpCode = $('#hotpital option:selected').attr("value");
      var deptCode = $('#department option:selected').attr("value");
      $('#TableDetail tbody').empty();
      swal({
        title: "<?php echo $array['confirm'][$language]; ?>",
        text: "<?php echo $array['side'][$language]; ?> : " +$('#hotpital option:selected').text()+ " <?php echo $array['department'][$language]; ?> : " +$('#department option:selected').text(),
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "<?php echo $array['confirm'][$language]; ?>",
        cancelButtonText: "<?php echo $array['cancel'][$language]; ?>",
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        closeOnConfirm: false,
        closeOnCancel: false,
        showCancelButton: true}).then(result => {
          var data = {
            'STATUS'    : 'CreateDocument',
            'hotpCode'  : hotpCode,
            'deptCode'  : deptCode,
            'userid'	: userid
          };
          senddata(JSON.stringify(data));
        })
    }

      function canceldocno(docno) {
        swal({
          title: "<?php echo $array['confirmdelete'][$language]; ?>",
          text: "<?php echo $array['confirmdelete1'][$language]; ?>" +docno+ "<?php echo $array['confirmdelete2'][$language]; ?>",
          type: "warning",
          showCancelButton: true,
          confirmButtonClass: "btn-danger",
          confirmButtonText: "<?php echo $array['delete'][$language]; ?>",
          cancelButtonText: "<?php echo $array['cancel'][$language]; ?>",
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          closeOnConfirm: false,
          closeOnCancel: false,
          showCancelButton: true}).then(result => {
            var data = {
              'STATUS'      : 'CancelDocNo',
              'DocNo'       : docno
            };
            senddata(JSON.stringify(data));
            getSearchDocNo();
          })
      }

        function addnum(cnt) {
          var add = parseInt($('#iqty'+cnt).val())+1;
          var max = $('#qty_'+cnt).data('value');
          if(add>max){
            $('#iqty'+cnt).val(max);
          }else{ 
            $('#iqty'+cnt).val(add);
          }
        }

        function subtractnum(cnt) {
          var sub = parseInt($('#iqty'+cnt).val())-1;
          if((sub>0) && (sub<=500)) {
            $('#iqty'+cnt).val(sub);
          }
        }

        function addnum1(rowid,cnt,unitcode) {
          var deptCode = $('#department option:selected').attr("value");
          var Dep = $("#Dep_").val();
          var max = $('#max'+cnt).val();
          var docno = $("#docno").val();
          var add = parseInt($('#qty1_'+cnt).val())+1;
          var isStatus = $("#IsStatus").val();
          
          if(isStatus==0){
            if(add>max){
              $('#qty1_'+cnt).val(max);
            }else{
              $('#qty1_'+cnt).val(add);
              var data = {
                'STATUS'      : 'UpdateDetailQty',
                'Rowid'       : rowid,
                'DocNo'       : docno,
                'CcQty'		    : add,
                'max'		: max
              };
              senddata(JSON.stringify(data));
            }
          }
        }

        function subtractnum1(rowid,cnt,unitcode) {
          var deptCode = $('#department option:selected').attr("value");
          var Dep = $("#Dep_").val();
          var max = $('#max'+cnt).val();
          var docno = $("#docno").val();
          var sub = parseInt($('#qty1_'+cnt).val())-1;
          var isStatus = $("#IsStatus").val();
          if((sub>=0) && (sub<=500)) {
            if(isStatus==0){
              // alert(sub);
              $('#qty1_'+cnt).val(sub);
              var data = {
                'STATUS'      : 'UpdateDetailQty',
                'Rowid'       : rowid,
                'DocNo'       : docno,
                'CcQty'		    : sub,
                'max'		: max
              };
              senddata(JSON.stringify(data));
            }
          }
        }

        function updateWeight(row,rowid) {
          var docno = $("#docno").val();
          var weight = $("#weight_"+row).val();
          var price = 0; //$("#price_"+row).val();
          var isStatus = $("#IsStatus").val();
          //alert(rowid+" :: "+docno+" :: "+weight);
          if(isStatus==0){
            var data = {
              'STATUS'      : 'UpdateDetailWeight',
              'Rowid'       : rowid,
              'DocNo'       : docno,
              'Weight'      : weight,
              'Price'      : price
            };
            senddata(JSON.stringify(data));
          }
        }

        function SaveBill(){
          var docno = $("#docno").val();
          var isStatus = $("#IsStatus").val();
          var dept = $('#Dep2').val();
          // alert( isStatus );
          chk_par();
          // if(isStatus==1)
          // isStatus=0;
          // else
          // isStatus=1;

          // if(isStatus==1){
          //   var data = {
          //     'STATUS'      : 'SaveBill',
          //     'xdocno'      : docno,
          //     'isStatus'    : isStatus,
          //     'deptCode'    : dept
          //   };
          //   senddata(JSON.stringify(data));

          //   $('#profile-tab').tab('show');

          //     $("#bImport").prop('disabled', true);
          //     $("#bDelete").prop('disabled', true);
          //     $("#bSave").prop('disabled', true);
          //     $("#bCancel").prop('disabled', true);

          //     ShowDocument();
          // }else{
          //   $("#bImport").prop('disabled', false);
          //   $("#bDelete").prop('disabled', false);
          //   $("#bSave").prop('disabled', false);
          //   $("#bCancel").prop('disabled', false);
          //   $("#bSave").text('<?php echo $array['save'][$language]; ?>');
          //   $("#IsStatus").val("0");
          //     $("#docno").prop('disabled', false);
          //     $("#docdate").prop('disabled', false);
          //     $("#recorder").prop('disabled', false);
          //     $("#timerec").prop('disabled', false);
          //     $("#total").prop('disabled', false);
          //     var rowCount = $('#TableItemDetail >tbody >tr').length;
          //     for (var i = 0; i < rowCount; i++) {

          //         $('#qty1_'+i).prop('disabled', false);
          //         $('#weight_'+i).prop('disabled', false);
          //         $('#price_'+i).prop('disabled', false);

          //         $('#unit'+i).prop('disabled', false);
          //     }
          // }
        }

        function chk_par(){
          var ItemCodeArray = [];
          var Item = [];
          var HptCode = $('#hotpital option:selected').attr("value");
          var DepCode = $('#department option:selected').attr("value");
          var DocNo = $('#docno').val();
          $(".item_array").each(function() {
            ItemCodeArray.push($(this).val());
          });

          for(var j=0;j<ItemCodeArray.length; j++){
            Item.push( $("#item_array"+ItemCodeArray[j]).val() );
          }
          var ItemCode = Item.join(',') ;
          var data = {
            'STATUS'      : 'chk_par',
            'HptCode'      : HptCode,
            'DepCode'    : DepCode,
            'DocNo'    : DocNo,
            'ItemCode'    : ItemCode
          };
            senddata(JSON.stringify(data));
        }

        function PrintData(){
          var docno = $('#docno').val();
          var lang = '<?php echo $language; ?>';
          if(docno!=""&&docno!=undefined){
            var url  = "../report/Report_Shelfcount.php?DocNo="+docno+"&lang="+lang;
            window.open(url);
          }else{
            swal({
              title: '',
              text: '<?php echo $array['docfirst'][$language]; ?>',
              type: 'info',
              showCancelButton: false,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              showConfirmButton: false,
              timer: 2000,
              confirmButtonText: 'Ok'
            })
          }
        }

        function SendData(){
          var docno = $('#docno').val();
          swal({
            title: '',
            text: '<?php echo $array['sendlinendep'][$language]; ?>',
            type: 'success',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            showConfirmButton: false,
            timer: 2000,
            confirmButtonText: 'Ok'
          }).then(result => {
            var data = {
              'STATUS'      : 'SendData',
              'DocNo'       : docno
            };
            senddata(JSON.stringify(data));
            getSearchDocNo();
          })
        }

        function keydownupdate(rowid,cnt){
          var max = $('#max'+cnt).val();
          var par = $('#qty1_'+cnt).val();
          var sub = max - par;
          var docno = $("#docno").val();
          var isStatus = $("#IsStatus").val();
          if((sub>=0) && (sub<=500)) {
            if(isStatus==0){
              console.log(sub);
              $('#order'+cnt).val(sub);
              var data = {
                'STATUS'      : 'UpdateDetailQty_key',
                'Rowid'       : rowid,
                'DocNo'       : docno,
                'CcQty'		    : par,
                'TotalQty'		: sub
              };
              senddata(JSON.stringify(data));
            }
          }
        }
        function logoff() {
          swal({
            title: '',
            text: '<?php echo $array['logout'][$language]; ?>',
            type: 'success',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            showConfirmButton: false,
            timer: 1000,
            confirmButtonText: 'Ok'
          }).then(function () {
            window.location.href="../logoff.php";
          }, function (dismiss) {
            window.location.href="../logoff.php";
            if (dismiss === 'cancel') {

            }
          })
        }

        function senddata(data){
          var form_data = new FormData();
          form_data.append("DATA",data);
          var URL = '../process/shelfcount.php';
          $.ajax({
            url: URL,
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function (result) {
              try {
                var temp = $.parseJSON(result);
              } catch (e) {
                console.log('Error#542-decode error');
              }

              if(temp["status"]=='success'){
                if(temp["form"]=='OnLoadPage'){
                  var PmID = <?php echo $PmID;?>;
                  var HptCode = '<?php echo $HptCode;?>';
                  for (var i = 0; i < (Object.keys(temp).length-2); i++) {
                    if(PmID != 1 && HptCode == temp[i]['HptCode']){
                      var Str = "<option value="+temp[i]['HptCode']+" selected>"+temp[i]['HptName']+"</option>";
                      $("#side").append(Str);
                      $("#side").attr('disabled', true);
                      $("#hotpital").append(Str);
                      $("#hotpital").attr('disabled', true);
                    }else{
                      var Str = "<option value="+temp[i]['HptCode']+">"+temp[i]['HptName']+"</option>";
                      $("#side").append(Str);
                      $("#hotpital").append(Str);
                    }
                    
                  }
                }else if(temp["form"]=='getDepartment'){
                  $("#department").empty();
                  $("#Dep2").empty();
                  for (var i = 0; i < (Object.keys(temp).length-2); i++) {
                    var Str = "<option value="+temp[i]['DepCode']+">"+temp[i]['DepName']+"</option>";
                    $("#department").append(Str);
                    $("#Dep2").append(Str);
                  }
                }else if( (temp["form"]=='CreateDocument') ){
                  $("#docno").val(temp[0]['DocNo']);
                  $("#docdate").val(temp[0]['DocDate']);
                  $("#recorder").val(temp[0]['Record']);
                  $("#timerec").val(temp[0]['RecNow']);
                  
                  // ShowDocument_sub();
                  swal({
                    title: "<?php echo $array['createdocno'][$language]; ?>",
                    text: temp[0]['DocNo'] + " <?php echo $array['success'][$language]; ?>",
                    type: "success",
                    showCancelButton: false,
                    timer: 1000,
                    confirmButtonText: 'Ok',
                    showConfirmButton: false
                  });
                  setTimeout(function () {
                    parent.OnLoadPage();
                  }, 1000);
                }else if(temp["form"]=='ShowDocument'){

                  setTimeout(function () {
                  parent.OnLoadPage();
                }, 500);

                  $( "#TableDocument tbody" ).empty();
                  $( "#TableItemDetail tbody" ).empty();
                  // $("#docno").val(temp[0]['DocNo']);
                  // $("#docdate").val(temp[0]['DocDate']);
                  // $("#recorder").val(temp[0]['Record']);
                  // $("#timerec").val(temp[0]['RecNow']);
                  $("#docno").val("");
                  $("#docdate").val("");
                  $("#recorder").val("");
                  $("#timerec").val("");
                  $("#docno").prop('disabled', false);
                  $("#docdate").prop('disabled', false);
                  $("#recorder").prop('disabled', false);
                  $("#timerec").prop('disabled', false);

                  for (var i = 0; i < (Object.keys(temp).length-2); i++) {
                    var rowCount = $('#TableDocument >tbody >tr').length;
                    var chkDoc = "<input type='radio' name='checkdocno' id='checkdocno' value='"+temp[i]['DocNo']+"' >";
                    var Status = "";
                    var Style  = "";
                    if(temp[i]['IsStatus']==1){
                      Status = "<?php echo $array['savesuccess'][$language]; ?>";
                      Style  = "style='width: 10%;color: #20B80E;'";
                    }else{
                      Status = "<?php echo $array['draft'][$language]; ?>";
                      Style  = "style='width: 10%;color: #3399ff;'";
                    }if(temp[i]['IsStatus']==2){
                      Status = "<?php echo $array['cancelbill'][$language]; ?>";
                      Style  = "style='width: 10%;color: #ff0000;'";
                    }

                    $StrTr="<tr id='tr"+temp[i]['DocNo']+"'>"+
                    "<td style='width: 10%;'nowrap>"+chkDoc+"</td>"+
                    "<td style='width: 15%;'nowrap>"+temp[i]['DocDate']+"</td>"+
                    "<td style='width: 15%;'nowrap>"+temp[i]['DocNo']+"</td>"+
                    "<td style='width: 15%;'nowrap>"+temp[i]['DepName']+"</td>"+
                    "<td style='width: 15%;'nowrap>"+temp[i]['Record']+"</td>"+
                    "<td style='width: 10%;'nowrap>"+temp[i]['RecNow']+"</td>"+
                    "<td style='width: 10%;'nowrap>"+temp[i]['Total']+"</td>"+
                    "<td "+Style+"nowrap>"+Status+"</td>"+
                    "</tr>";

                    if(rowCount == 0){
                      $("#TableDocument tbody").append( $StrTr );
                    }else{
                      $('#TableDocument tbody:last-child').append(  $StrTr );
                    }
                  }

                }else if(temp["form"]=='ShowDocument_sub'){
                  $( "#TableDocument tbody" ).empty();
                  $( "#TableItemDetail tbody" ).empty();
                  //               $("#docno").val(temp[0]['DocNo']);
                  // $("#docdate").val(temp[0]['DocDate']);
                  // $("#recorder").val(temp[0]['Record']);
                  // $("#timerec").val(temp[0]['RecNow']);
                  // $("#docno").val("");
                  // $("#docdate").val("");
                  // $("#recorder").val("");
                  // $("#timerec").val("");
                  // $("#docno").prop('disabled', false);
                  // $("#docdate").prop('disabled', false);
                  // $("#recorder").prop('disabled', false);
                  // $("#timerec").prop('disabled', false);

                  for (var i = 0; i < (Object.keys(temp).length-2); i++) {
                    var rowCount = $('#TableDocument >tbody >tr').length;
                    var chkDoc = "<input type='radio' name='checkdocno' id='checkdocno' value='"+temp[i]['DocNo']+"' >";
                    var Status = "";
                    var Style  = "";
                    if(temp[i]['IsStatus']==1){
                      Status = "<?php echo $array['savesuccess'][$language]; ?>";
                      Style  = "style='width: 10%;color: #20B80E;'";
                    }else{
                      Status = "<?php echo $array['draft'][$language]; ?>";
                      Style  = "style='width: 10%;color: #3399ff;'";
                    }if(temp[i]['IsStatus']==2){
                      Status = "<?php echo $array['cancelbill'][$language]; ?>";
                      Style  = "style='width: 10%;color: #ff0000;'";
                    }

                    $StrTr="<tr id='tr"+temp[i]['DocNo']+"'>"+
                    "<td style='width: 10%;'nowrap>"+chkDoc+"</td>"+
                    "<td style='width: 15%;'nowrap>"+temp[i]['DocDate']+"</td>"+
                    "<td style='width: 15%;'nowrap>"+temp[i]['DocNo']+"</td>"+
                    "<td style='width: 15%;'nowrap>"+temp[i]['DepName']+"</td>"+
                    "<td style='width: 15%;'nowrap>"+temp[i]['Record']+"</td>"+
                    "<td style='width: 10%;'nowrap>"+temp[i]['RecNow']+"</td>"+
                    "<td style='width: 10%;'nowrap>"+temp[i]['Total']+"</td>"+
                    "<td "+Style+">"+Status+"</td>"+
                    "</tr>";

                    if(rowCount == 0){
                      $("#TableDocument tbody").append( $StrTr );
                    }else{
                      $('#TableDocument tbody:last-child').append(  $StrTr );
                    }
                  }

                }else if(temp["form"]=='SelectDocument'){
                  $('#home-tab').tab('show')
                  $( "#TableItemDetail tbody" ).empty();
                  $("#docno").val(temp[0]['DocNo']);
                  $("#docdate").val(temp[0]['DocDate']);
                  $("#recorder").val(temp[0]['Record']);
                  $("#timerec").val(temp[0]['RecNow']);
                  $("#IsStatus").val(temp[0]['IsStatus']);

                  if(temp[0]['IsStatus']==0){
                    $("#bSave").text('<?php echo $array['save'][$language]; ?>');
                    $("#bImport").prop('disabled', false);
                    $("#bDelete").prop('disabled', false);
                    $("#bSave").prop('disabled', false);
                    $("#bCancel").prop('disabled', false);
                  }else if(temp[0]['IsStatus']==1){
                    $("#bSave").text('<?php echo $array['edit'][$language]; ?>');
                    $("#bImport").prop('disabled', true);
                    $("#bDelete").prop('disabled', true);
                    $("#bSave").prop('disabled', false);
                    $("#bCancel").prop('disabled', true);
                  }else{
                    $("#bImport").prop('disabled', true);
                    $("#bDelete").prop('disabled', true);
                    $("#bSave").prop('disabled', true);
                    $("#bCancel").prop('disabled', true);

                    $("#docno").prop('disabled', true);
                    $("#docdate").prop('disabled', true);
                    $("#recorder").prop('disabled', true);
                    $("#timerec").prop('disabled', true);
                    $("#total").prop('disabled', true);

                    $('#qty1_'+i).prop('disabled', true);
                    $('#weight_'+i).prop('disabled', true);
                    $('#price_'+i).prop('disabled', true);

                    $('#unit'+i).prop('disabled', true);
                  }
                  ShowDetail();
                }else if(temp["form"]=='ShowMenu'){
                  $('#home-tab').tab('show')
                  $( "#TableItemDetail tbody" ).empty();
                  $("#docno").val(temp[0]['DocNo']);
                  $("#docdate").val(temp[0]['DocDate']);
                  $("#recorder").val(temp[0]['Record']);
                  $("#timerec").val(temp[0]['RecNow']);
                  $("#IsStatus").val(temp[0]['IsStatus']);

                  if(temp[0]['IsStatus']==0){
                    $("#bSave").text('<?php echo $array['save'][$language]; ?>');
                    $("#bImport").prop('disabled', false);
                    $("#bDelete").prop('disabled', false);
                    $("#bSave").prop('disabled', false);
                    $("#bCancel").prop('disabled', false);
                  }else if(temp[0]['IsStatus']==1){
                    $("#bSave").text('<?php echo $array['edit'][$language]; ?>');
                    $("#bImport").prop('disabled', true);
                    $("#bDelete").prop('disabled', true);
                    $("#bSave").prop('disabled', false);
                    $("#bCancel").prop('disabled', true);
                  }else{
                    $("#bImport").prop('disabled', true);
                    $("#bDelete").prop('disabled', true);
                    $("#bSave").prop('disabled', true);
                    $("#bCancel").prop('disabled', true);

                    $("#docno").prop('disabled', true);
                    $("#docdate").prop('disabled', true);
                    $("#recorder").prop('disabled', true);
                    $("#timerec").prop('disabled', true);
                    $("#total").prop('disabled', true);

                    $('#qty1_'+i).prop('disabled', true);
                    $('#weight_'+i).prop('disabled', true);
                    $('#price_'+i).prop('disabled', true);

                    $('#unit'+i).prop('disabled', true);
                  }
                  ShowDetail();
                }else if(temp["form"]=='getImport'  || temp["form"]=='ShowDetail'){
                  $( "#TableItemDetail tbody" ).empty();
                  var isStatus = $("#IsStatus").val();
                  var st1 = "style='font-size:24px;margin-left:20px; width:130px;font-family:THSarabunNew'";
                  for (var i = 0; i < temp["Row"]; i++) {
                    var rowCount = $('#TableItemDetail >tbody >tr').length;
                    var chkDoc = "<input type='radio' name='checkrow' id='checkrow' value='"+temp[i]['RowID']+","+temp[i]['ItemName']+"'>";
                    var chkunit ="<select onchange='convertUnit(\""+temp[i]['RowID']+"\",this)' class='form-control' style='font-size:24px;' id='unit"+i+"'>";

                    for(var j = 0; j < temp['Cnt_'+temp[i]['ItemCode']][i]; j++){
                      if(temp['MpCode_'+temp[i]['ItemCode']+'_'+i][j]==temp[i]['UnitCode2'])
                      chkunit += "<option selected value="+i+","+temp['MpCode_'+temp[i]['ItemCode']+'_'+i][j]+","+temp['Multiply_'+temp[i]['ItemCode']+'_'+i][j]+">"+temp['UnitName_'+temp[i]['ItemCode']+'_'+i][j]+"</option>";
                      else
                      chkunit += "<option value="+i+","+temp['MpCode_'+temp[i]['ItemCode']+'_'+i][j]+","+temp['Multiply_'+temp[i]['ItemCode']+'_'+i][j]+">"+temp['UnitName_'+temp[i]['ItemCode']+'_'+i][j]+"</option>";
                    }

                    chkunit += "</select>";

                    var Qty = "<div class='row' style='margin-left:2px;'><button class='btn btn_mhee' style='height:40px;width:32px;' onclick='subtractnum1(\""+temp[i]['RowID']+"\",\""+i+"\",\""+temp[i]['UnitCode2']+"\")'>-</button><input class='form-control' style='height:40px;width:90px; margin-left:3px; margin-right:3px; text-align:center;' id='qty1_"+i+"' value='"+temp[i]['CcQty']+"' onkeyup='if(this.value > "+temp[i]['ParQty']+"){this.value="+temp[i]['ParQty']+"}else if(this.value<0){this.value=0}' onblur='keydownupdate(\""+temp[i]['RowID']+"\",\""+i+"\")' ><button class='btn btn_mheesave' style='height:40px;width:32px;' onclick='addnum1(\""+temp[i]['RowID']+"\",\""+i+"\",\""+temp[i]['UnitCode2']+"\")'>+</button></div>";

                    var Order = "<input class='form-control' id='order"+i+"' type='text' style='text-align:center;' value='"+(temp[i]['TotalQty'])+"' disabled>";

                    var Max = "<input class='form-control' id='max"+i+"' type='text' style='text-align:center;' value='"+(temp[i]['ParQty'])+"' disabled>";

                    var Weight = "";

                    var Price = "";

                    $StrTR = "<tr id='tr"+temp[i]['RowID']+"'>"+
                    "<td style='width: 7%;'nowrap>"+chkDoc+" <label style='margin-left:10px;'> "+(i+1)+"</label></td>"+
                    "<td style='width: 20%;'nowrap><input type='hidden' id='item_array"+temp[i]['ItemCode']+"' value='"+temp[i]['ItemCode']+"' class='item_array'></input>"+temp[i]['ItemCode']+"</td>"+
                    "<td style='width: 20%;'nowrap>"+temp[i]['ItemName']+"</td>"+
                    "<td style='width: 10%;'nowrap>"+temp[i]['UnitName']+"</td>"+
                    "<td style='width: 10%;'nowrap>"+Max+"</td>"+
                    "<td style='width: 22%;'nowrap>"+Qty+"</td>"+
                    "<td style='width: 10%;'nowrap>"+Order+"</td>"+
                    "</tr>";


                    if(rowCount == 0){
                      $("#TableItemDetail tbody").append( $StrTR );
                    }else{
                      $('#TableItemDetail tbody:last-child').append( $StrTR );
                    }
                    if(isStatus==0){
                      $("#docno").prop('disabled', false);
                      $("#docdate").prop('disabled', false);
                      $("#recorder").prop('disabled', false);
                      $("#timerec").prop('disabled', false);
                      $("#total").prop('disabled', false);

                      $('#qty1_'+i).prop('disabled', false);
                      $('#weight_'+i).prop('disabled', false);
                      $('#price_'+i).prop('disabled', false);
                      $('#price_'+i).prop('disabled', false);

                      $('#unit'+i).prop('disabled', false);
                    }else{
                      $("#docno").prop('disabled', true);
                      $("#docdate").prop('disabled', true);
                      $("#recorder").prop('disabled', true);
                      $("#timerec").prop('disabled', true);
                      $("#total").prop('disabled', true);

                      $('#qty1_'+i).prop('disabled', true);
                      $('#weight_'+i).prop('disabled', true);
                      $('#price_'+i).prop('disabled', true);

                      $('#unit'+i).prop('disabled', true);
                    }
                  }

                }else if( (temp["form"]=='ShowItem') ){
                  var st1 = "style='font-size:24px;margin-left:20px; width:130px;font-family:THSarabunNew'";
                  var st2 = "style='height:40px;width:60px; margin-left:3px; margin-right:3px; text-align:center;font-family:THSarabunNew'"
                  $( "#TableItem tbody" ).empty();

                  for (var i = 0; i < (Object.keys(temp).length-2); i++) {
                    var rowCount = $('#TableItem >tbody >tr').length;

                    var chkunit ="<select "+st1+" class='form-control' id='iUnit_"+i+"'>";
                    var nUnit = "";

                    for(var j = 0; j < temp['Cnt_'+temp[i]['ItemCode']][i]; j++){
                      if(temp['MpCode_'+temp[i]['ItemCode']+'_'+i][j]==temp[i]['UnitCode']){
                        chkunit += "<option selected value="+temp['MpCode_'+temp[i]['ItemCode']+'_'+i][j]+">"+temp['UnitName_'+temp[i]['ItemCode']+'_'+i][j]+"</option>";
                        nUnit = temp['MpCode_'+temp[i]['ItemCode']+'_'+i][j];
                      }else{
                        chkunit += "<option value="+temp['MpCode_'+temp[i]['ItemCode']+'_'+i][j]+">"+temp['UnitName_'+temp[i]['ItemCode']+'_'+i][j]+"</option>";
                        nUnit = temp['MpCode_'+temp[i]['ItemCode']+'_'+i][j];
                      }
                    }
                    chkunit += "</select>";

                    var chkDoc = "<input type='checkbox' name='checkitem' id='checkitem' value='"+i+"'><input type='hidden' id='RowID"+i+"' value='"+temp[i]['RowID']+"'>";
                    var Qty = "<div class='row' style='margin-left:2px;'><button class='btn btn-danger' style='height:40px;width:32px;' onclick='subtractnum(\""+i+"\")'>-</button><input class='form-control' "+st2+" id='iqty"+i+"' value='1' onkeyup='if(this.value>"+temp[i]['Qty']+"){this.value="+temp[i]['Qty']+"}else if(this.value<0){this.value=0}'><button class='btn btn-success' style='height:40px;width:32px;' onclick='addnum(\""+i+"\")'>+</button></div>";

                    var Weight = "<div class='row' style='margin-left:2px;'><input class='form-control' style='height:40px;width:134px; margin-left:3px; margin-right:3px; text-align:center;' id='iweight"+i+"' value='0' ></div>";

                    $StrTR = "<tr id='tr"+temp[i]['RowID']+"'>"+
                    "<td style='width: 10%;'nowrap>"+chkDoc+" <label style='margin-left:10px;'> "+(i+1)+"</label></td>"+
                    "<td style='width: 20%;cursor: pointer;' onclick='OpenDialogUsageCode(\""+temp[i]['ItemCode']+"\")''nowrap>"+temp[i]['ItemCode']+"</td>"+
                    "<td style='width: 25%;cursor: pointer;' onclick='OpenDialogUsageCode(\""+temp[i]['ItemCode']+"\")''nowrap>"+temp[i]['ItemName']+"</td>"+
                    "<td style='width: 15%;'nowrap>"+chkunit+"</td>"+
                    "<td style='width: 15%;' id='qty_"+i+"' data-value='"+temp[i]['Qty']+"'nowrap>"+Qty+"</td>"+
                    "<td style='width: 10%;'nowrap>"+Weight+"</td>"+
                    "</tr>";
                    if(rowCount == 0){
                      $("#TableItem tbody").append( $StrTR );
                    }else{
                      $('#TableItem tbody:last-child').append( $StrTR );
                    }
                  }
                }else if( (temp["form"]=='ShowUsageCode') ){
                  var st1 = "style='font-size:18px;margin-left:3px; width:100px;font-family:THSarabunNew;font-size:24px;'";
                  var st2 = "style='height:40px;width:60px; margin-left:0px; text-align:center;font-family:THSarabunNew;font-size:32px;'"
                  $( "#TableUsageCode tbody" ).empty();
                  for (var i = 0; i < temp["Row"]; i++) {
                    var rowCount = $('#TableUsageCode >tbody >tr').length;

                    var chkunit ="<select "+st1+" onchange='convertUnit(\""+temp[i]['RowID']+"\",this)' class='form-control' style='font-size:32px;' id='iUnit_"+i+"'>";

                    for(var j = 0; j < temp['Cnt_'+temp[i]['ItemCode']][i]; j++){
                      if(temp['MpCode_'+temp[i]['ItemCode']+'_'+i][j]==temp[i]['UnitCode'])
                      chkunit += "<option selected value="+temp['MpCode_'+temp[i]['ItemCode']+'_'+i][j]+">"+temp['UnitName_'+temp[i]['ItemCode']+'_'+i][j]+"</option>";
                      else
                      chkunit += "<option value="+temp['MpCode_'+temp[i]['ItemCode']+'_'+i][j]+">"+temp['UnitName_'+temp[i]['ItemCode']+'_'+i][j]+"</option>";
                    }
                    chkunit += "</select>";

                    var chkDoc = "<input type='checkbox' name='checkitemSub' id='checkitemSub' value='"+i+"'><input type='hidden' id='RowIDSub"+i+"' value='"+temp[i]['RowID']+"'>";

                    //var Qty = "<div class='row' style='margin-left:2px;'><button class='btn btn-danger' style='height:40px;width:32px;' onclick='subtractnum(\""+i+"\")'>-</button><input class='form-control' "+st2+" id='iqty"+i+"' value='1' ><button class='btn btn-success' style='height:40px;width:32px;' onclick='addnum(\""+i+"\")'>+</button></div>";

                    var Weight = "<div class='row' style='margin-left:2px;'><input class='form-control' style='height:40px;width:134px; margin-left:3px; margin-right:3px; text-align:center;' id='iweight"+i+"' value='0' ></div>";

                    $StrTR = "<tr id='tr"+temp[i]['RowID']+"'>"+
                    "<td style='width: 10%;'nowrap>"+chkDoc+" <label style='margin-left:10px;'> "+(i+1)+"</label></td>"+
                    "<td style='width: 20%;'nowrap>"+temp[i]['UsageCode']+"</td>"+
                    "<td style='width: 40%;'nowrap>"+temp[i]['ItemName']+"</td>"+
                    "<td style='width: 15%;'nowrap>"+chkunit+"</td>"+
                    "<td style='width: 13%;' align='center'nowrap>1</td>"+
                    "</tr>";
                    if(rowCount == 0){
                      $("#TableUsageCode tbody").append( $StrTR );
                    }else{
                      $('#TableUsageCode tbody:last-child').append( $StrTR );
                    }
                  }
                }else if( (temp["form"]=='ShowDetailSub') ){
                  //console.log(temp);
                  var st1 = "style='font-size:18px;margin-left:20px; width:100px;font-family:THSarabunNew'";
                  var st2 = "style='height:40px;width:70px; margin-left:3px; margin-right:3px; text-align:center;font-family:THSarabunNew'"
                  $( "#TableItemListDetailSub tbody" ).empty();
                  
                  for (var i = 0; i < temp["Row"]; i++) {
                    var rowCount = $('#TableItemListDetailSub >tbody >tr').length;
                    //console.log(rowCount);
                    var Str = "<tr>"+
                    "<td style='width: 10%;'nowrap><label style='margin-left:10px;'> "+(i+1)+"</label></td>"+
                    "<td style='width: 25%;'nowrap>"+temp[i]['UsageCode']+"</td>"+
                    "<td style='width: 50%;'nowrap>"+temp[i]['ItemName']+"</td>"+
                    "<td style='width: 15%;'nowrap>"+temp[i]['UnitName']+"</td>"+
                    "</tr>";
                    // console.log(Str);
                    if(rowCount == 0){
                      $("#TableItemListDetailSub tbody").append( Str );
                    }else{
                      $('#TableItemListDetailSub tbody:last-child').append( Str );
                    }
                  }
                }else if( (temp["form"]=='chk_par') ){
                  for(var i = 0; i < temp['Row']; i++){
                    if(temp[i]['MoreThan'] > temp[i]['ParQty']){
                      alert(temp[i]['ItemCode']);
                      alert(temp[i]['MoreThan']);
                      alert(temp[i]['ParQty']);
                    }
                  }
                }
              }else if (temp['status']=="failed") {
                switch (temp['msg']) {
                  case "notchosen":
                  temp['msg'] = "<?php echo $array['choosemsg'][$language]; ?>";
                  break;
                  case "cantcreate":
                  temp['msg'] = "<?php echo $array['cantcreatemsg'][$language]; ?>";
                  break;
                  case "noinput":
                  temp['msg'] = "<?php echo $array['noinputmsg'][$language]; ?>";
                  break;
                  case "notfound":
                  temp['msg'] = "<?php echo $array['notfoundmsg'][$language]; ?>";
                  break;
                  case "addsuccess":
                  temp['msg'] = "<?php echo $array['addsuccessmsg'][$language]; ?>";
                  break;
                  case "addfailed":
                  temp['msg'] = "<?php echo $array['addfailedmsg'][$language]; ?>";
                  break;
                  case "editsuccess":
                  temp['msg'] = "<?php echo $array['editsuccessmsg'][$language]; ?>";
                  break;
                  case "editfailed":
                  temp['msg'] = "<?php echo $array['editfailedmsg'][$language]; ?>";
                  break;
                  case "cancelsuccess":
                  temp['msg'] = "<?php echo $array['cancelsuccessmsg'][$language]; ?>";
                  break;
                  case "cancelfailed":
                  temp['msg'] = "<?php echo $array['cancelfailed'][$language]; ?>";
                  break;
                  case "nodetail":
                  temp['msg'] = "<?php echo $array['nodetail'][$language]; ?>";
                  break;
                }
                swal({
                  title: '',
                  text: temp['msg'],
                  type: 'warning',
                  showCancelButton: false,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  showConfirmButton: false,
                  timer: 1500,
                  confirmButtonText: 'Ok'
                })

                $( "#docnofield" ).val( temp[0]['DocNo'] );
                $( "#TableDocumentSS tbody" ).empty();
                $( "#TableSendSterileDetail tbody" ).empty();
                $( "#TableUsageCode tbody" ).empty();
                $( "#TableItem tbody" ).empty();

              }else{
                console.log(temp['msg']);
              }
            },failure: function (result) {
              alert(result);
            },error: function (xhr, status, p3, p4) {
              var err = "Error " + " " + status + " " + p3 + " " + p4;
              if (xhr.responseText && xhr.responseText[0] == "{")
              err = JSON.parse(xhr.responseText).Message;
              console.log(err);
              alert(err);
            }
          });
        }
        </script>
          <style media="screen">

            @font-face {
              font-family: myFirstFont;
              src: url("../fonts/DB Helvethaica X.ttf");
              }
          body{
            font-family: myFirstFont;
            font-size:22px;
          }

          .nfont{
            font-family: myFirstFont;
            font-size:22px;
          }

          button,input[id^='qty'],input[id^='order'],input[id^='max'] {
            font-size: 24px!important;
          }

          .table > thead > tr >th {
            /* background: #4f88e3!important; */
            background-color: rgb(0, 51, 141);

          }

          table tr th,
          table tr td {
            border-right: 0px solid #bbb;
            border-bottom: 0px solid #bbb;
            padding: 5px;
          }
          table tr th:first-child,
          table tr td:first-child {
            border-left: 0px solid #bbb;
          }
          table tr th {
            background: #eee;
            border-top: 0px solid #bbb;
            text-align: left;
          }

          /* top-left border-radius */
          table tr:first-child th:first-child {
            border-top-left-radius: 6px;
          }

          /* top-right border-radius */
          table tr:first-child th:last-child {
            border-top-right-radius: 6px;
          }

          /* bottom-left border-radius */
          table tr:last-child td:first-child {
            border-bottom-left-radius: 6px;
          }

          /* bottom-right border-radius */
          table tr:last-child td:last-child {
            border-bottom-right-radius: 6px;
          }
          .btn_mhee{
            background-color: #e83530;
            color:white;
          }

          .btn_mheesave{
            background-color:#ee9726;
            color:white;
          }
          .btn_mheedel{
            background-color:#b12f31;
            color:white;
          }
          .btn_mheeIM{
            background-color:#3e3a8f;
            color:white;
          }
          .btn_mheedetail{
            background-color:#535d55;
            color:white;
          }
          .btn_mheereport{
            background-color:#d8d9db;
            color:white;
          }
          .btn_mheeCREATE{
            background-color:#1458a3; 
  
            color:white;
          }
          a.nav-link{
            width:auto!important;
          }
          .datepicker{z-index:9999 !important}
          .hidden{visibility: hidden;}
        </style>
      </head>

      <body id="page-top">
        <input class='form-control' type="hidden" style="margin-left:-48px;margin-top:10px;font-size:16px;width:100px;height:30px;text-align:right;padding-top: 15px;" id='IsStatus'>

        <div id="wrapper">
          <!-- content-wrapper -->
          <div id="content-wrapper">

            <div class="row" style="margin-top:-15px;"> <!-- start row tab -->
              <div class="col-md-12"> <!-- tag column 1 -->
                <div class="container-fluid">
                  <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><?php echo $array['titleshelf'][$language]; ?></a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><?php echo $array['search'][$language]; ?></a>
                    </li>
                  </ul>

                  <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                      <!-- /.content-wrapper -->
                    <div class="row">
                        <div class="col-md-11"> <!-- tag column 1 -->
                          <div class="container-fluid">
                            <div class="card-body mt-3" >

                            <div class="row">
                              <div class="col-md-6">
                                <div class='form-group row'>
                                  <label class="col-sm-3 col-form-label text-right"><?php echo $array['side'][$language]; ?></label>
                                  <select class="form-control col-sm-9" id="hotpital" onchange="getDepartment();"></select>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class='form-group row'>
                                  <label class="col-sm-3 col-form-label text-right"><?php echo $array['department'][$language]; ?></label>
                                    <select  class="form-control col-sm-9" id="department"></select>
                                </div>
                              </div>
                            </div>
                            
                            <div class="row">
                              <div class="col-md-6">
                                <div class='form-group row'>
                                  <label class="col-sm-3 col-form-label text-right"><?php echo $array['docdate'][$language]; ?></label>
                                  <input type="text" class="form-control col-sm-9" name="searchitem" id="docdate" placeholder="<?php echo $array['docdate'][$language]; ?>" >
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class='form-group row'>
                                    <label class="col-sm-3 col-form-label text-right"><?php echo $array['docno'][$language]; ?></label>
                                    <input type="text" class="form-control col-sm-9"  name="searchitem" id="docno" placeholder="<?php echo $array['docno'][$language]; ?>" >
                                </div>
                              </div>
                            </div>

                            <div class="row">
                              <div class="col-md-6">
                                <div class='form-group row'>
                                  <label class="col-sm-3 col-form-label text-right"><?php echo $array['employee'][$language]; ?></label>
                                  <input type="text" class="form-control col-sm-9"  name="searchitem" id="recorder" placeholder="<?php echo $array['employee'][$language]; ?>" >
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class='form-group row'>
                                  <label class="col-sm-3 col-form-label text-right"><?php echo $array['time'][$language]; ?></label>
                                    <input type="text" class="form-control col-sm-9" name="searchitem" id="timerec" placeholder="<?php echo $array['time'][$language]; ?>" >
                                </div>
                              </div>
                            </div>


                            </div>
                          </div>
                        </div> <!-- tag column 1 -->
                      </div>

                      <div class="row">
                        <div class="col-md-10"> <!-- tag column 1 -->


                          <table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="TableItemDetail" width="98%" cellspacing="0" role="grid" style="">
                            <thead id="theadsum" style="font-size:24px;" >
                              <tr role="row" >
                                <th style='width: 7%;'nowrap><?php echo $array['no'][$language]; ?></th>
                                <th style='width: 20%;'nowrap><?php echo $array['code'][$language]; ?></th>
                                <th style='width: 28%;'nowrap><?php echo $array['item'][$language]; ?></th>
                                <th style='width: 10%;'nowrap><?php echo $array['unit'][$language]; ?></th>
                                <th style='width: 10%;'nowrap><center><?php echo $array['parsc'][$language]; ?></center></th>
                                <th style='width: 15%;'nowrap><center><?php echo $array['leftsc'][$language]; ?></center></th>
                                <th style='width: 10%;'nowrap><center><?php echo $array['order'][$language]; ?><center></th>
                              </tr>
                            </thead>
                            <tbody id="tbody" class="nicescrolled" style="font-size:23px;height:300px;">
                            </tbody>
                          </table>


                        </div> <!-- tag column 1 -->
                        
                        <div class="col-md-1" <?php if($PmID == 1) echo 'hidden'; ?>>  <!-- tag column 2 -->

                          <div class="container-fluid" style="margin-top:5px;">
                            <div class="card-body" style="padding:0px; margin-top:10px;">
                              <div class="row" style="margin-top:0px;">
                                <div class="col-md-1">
                                  <div class="row" style="margin-left:2px;">
                                    <div class="row" style="margin-left:20px;">
                                      <button style="width:105px"; type="button" class="btn btn_mheeCREATE" onclick="CreateDocument()" id="bCreate"><?php echo $array['createdocno'][$language]; ?></button>
                                    </div>
                                  </div>
                                </div>
                              </div>

                              <div class="row" style="margin-top:4px;">
                                <div class="col-md-1">
                                  <div class="row" style="margin-left:2px;">
                                    <div class="row" style="margin-left:20px;">
                                      <button onclick="OpenDialogItem()" type="button" style="width:105px" class="btn btn_mheeIM" id="bImport"><?php echo $array['import'][$language]; ?></button>
                                    </div>
                                  </div>
                                </div>
                              </div>

                              <div class="row" style="margin-top:4px;">
                                <div class="col-md-1">
                                  <div class="row" style="margin-left:2px;">
                                    <div class="row" style="margin-left:20px;">
                                      <button onclick="DeleteItem()" type="button" style="width:105px" class="btn btn_mheedel" style="background : #F98707;" id="bDelete"><?php echo $array['delitem'][$language]; ?></button>
                                    </div>
                                  </div>
                                </div>
                              </div>

                              <div class="row" style="margin-top:4px;">
                                <div class="col-md-1">
                                  <div class="row" style="margin-left:2px;">
                                    <div class="row" style="margin-left:20px;">
                                      <button onclick="SaveBill()" style="width:105px" type="button" class="btn btn_mheesave" onclick="AddItem()" id="bSave"><?php echo $array['save'][$language]; ?></button>
                                    </div>
                                  </div>
                                </div>
                              </div>

                              <div class="row" style="margin-top:4px;">
                                <div class="col-md-1">
                                  <div class="row" style="margin-left:2px;">
                                    <div class="row" style="margin-left:20px;">
                                      <button style="width:105px"; type="button" class="btn btn_mhee" onclick="CancelDocument()" id="bCancel"><?php echo $array['cancel'][$language]; ?></button>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="row" style="margin-top:4px;">
                                <div class="col-md-1">
                                  <div class="row" style="margin-left:2px;">
                                    <div class="row" style="margin-left:20px;">
                                      <button style="width:105px;"; type="button" class="btn btn_mheedetail" onclick="ShowDetailSub()" id="bShowDetailSub"><?php echo $array['detail'][$language]; ?></button>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="row" style="margin-top:4px;">
                                <div class="col-md-1">
                                  <div class="row" style="margin-left:2px;">
                                    <div class="row" style="margin-left:20px;">
                                      <button style="width:105px;"; type="button" class="btn btn_mheereport" onclick="PrintData()" id="bPrint"><?php echo $array['print'][$language]; ?></button>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="row" style="margin-top:4px;">
                                <div class="col-md-1">
                                  <div class="row" style="margin-left:2px;">
                                    <div class="row" style="margin-left:20px;">
                                      <button style="width:105px;background-color:#FFFFFF;color:#000000"; type="button" class="btn btn-success" onclick="SendData()" id="bSend"><?php echo $array['sendlinen'][$language]; ?></button>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
 
                        </div>
                      </div>

                    </div>
                    <!-- search document -->
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                      <div class="row" style="margin-top:10px;">
                        <div class="col-md-2">

                          <select class="form-control" style='font-size:24px;' id="side" onchange="getDepartment();">
                          </select>

                      </div>
                      <div class="col-md-2">

                          <select class="form-control" style='font-size:24px;' id="Dep2">
                          </select>

                      </div>
                      <div class="col-md-6">
                        <div class="row" style="margin-left:2px;">
                          <input type="text" class="form-control" style="font-size:24px;width:30%;" name="searchdocument" id="searchdocument" placeholder="<?php echo $array['searchplace'][$language]; ?>" >
                          <button type="button" style="margin-left:10px;" class="btn btn-primary" name="button" onclick="ShowDocument(0);"><?php echo $array['search'][$language]; ?></button>
                          <button type="button" style="margin-left:10px;" class="btn btn-primary" name="button" onclick="ShowDocument(1);"><?php echo $array['searchalldep'][$language]; ?></button>
                        </div>
                      </div>
                      <div class="col-md-2 text-right">
                        <button type="button"  class="btn btn-warning" name="button" onclick="SelectDocument();"><?php echo $array['show'][$language]; ?></button>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-12"> <!-- tag column 1 -->
                        <table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="TableDocument" width="100%" cellspacing="0" role="grid">
                          <thead id="theadsum" style="font-size:24px;">
                            <tr role="row">
                              <th style='width: 10%;'nowrap>&nbsp;</th>
                              <th style='width: 15%;'nowrap><?php echo $array['docdate'][$language]; ?></th>
                              <th style='width: 15%;'nowrap><?php echo $array['docno'][$language]; ?></th>
                              <th style='width: 15%;'nowrap><?php echo $array['department'][$language]; ?></th>
                              <th style='width: 15%;'nowrap><?php echo $array['employee'][$language]; ?></th>
                              <th style='width: 10%;'nowrap><?php echo $array['time'][$language]; ?></th>
                              <th style='width: 10%;'nowrap><?php echo $array['order'][$language]; ?></th>
                              <th style='width: 10%;'nowrap><?php echo $array['status'][$language]; ?></th>
                            </tr>
                          </thead>
                          <tbody id="tbody" class="nicescrolled" style="font-size:23px;height:400px;">
                          </tbody>
                        </table>
                      </div> <!-- tag column 1 -->
                    </div>

                  </div> <!-- end row tab -->


                </div>



                <!-- /#wrapper -->
                <!-- Scroll to Top Button-->
                <a class="scroll-to-top rounded" href="#page-top">
                  <i class="fas fa-angle-up"></i>
                </a>

                <!-- /#wrapper -->
                <!-- Scroll to Top Button-->
                <a class="scroll-to-top rounded" href="#page-top">
                  <i class="fas fa-angle-up"></i>
                </a>

                <!-- Dialog Modal-->
                <div id="dialogUsageCode" title="<?php echo $array['import'][$language]; ?>"  style="z-index:999999 !important;font-family: 'THSarabunNew';font-size:24px;">
                  <div class="container">
                    <div class="row">
                      <div class="col-md-10">
                        <!--
                        <div class="row">
                        <label><?php echo $array['searchplace'][$language]; ?></label>
                        <div class="row" style="font-size:16px;margin-left:20px;width:350px;">
                        <input type="text" class="form-control" style="font-size:24px;width:100%;font-family: 'THSarabunNew'" name="searchitem1" id="searchitem1" placeholder="<?php echo $array['searchplace'][$language]; ?>" >
                      </div>
                      <button type="button" style="font-size:18px;margin-left:30px; width:100px;font-family: 'THSarabunNew'" class="btn btn-primary" name="button" onclick="ShowUsageCode();"><?php echo $array['search'][$language]; ?></button>
                    </div>
                  -->
                </div>
                <div class="col-md-1">
                  <button type="button" style="font-size:18px;margin-left:70px; width:100px;font-family: 'THSarabunNew'" class="btn btn-warning" name="button" onclick="getImport(2);"><?php echo $array['import'][$language]; ?></button>
                </div>
              </div>

              <div class="dropdown-divider" style="margin-top:20px;; margin-bottom:20px;"></div>

              <div class="row">
                <div class="card-body" style="padding:0px;">
                  <table class="table table-fixed table-condensed table-striped" id="TableUsageCode" cellspacing="0" role="grid" style="font-size:24px;width:1100px;font-family: 'THSarabunNew'">
                    <thead style="font-size:24px;">
                      <tr role="row">
                        <th style='width: 10%;'nowrap><?php echo $array['no'][$language]; ?></th>
                        <th style='width: 20%;'nowrap><?php echo $array['rfid'][$language]; ?></th>
                        <th style='width: 40%;'nowrap><?php echo $array['item'][$language]; ?></th>
                        <th style='width: 15%;'nowrap><?php echo $array['unit'][$language]; ?></th>
                        <th style='width: 15%;'nowrap><?php echo $array['numofpiece'][$language]; ?></th>
                      </tr>
                    </thead>
                    <tbody id="tbody1_modal" class="nicescrolled" style="font-size:23px;height:300px;">
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <!-- Dialog Modal
          <div id="dialogListDetail" title="<?php echo $array['detail'][$language]; ?>"  style="z-index:999999 !important;font-family: 'THSarabunNew';font-size:24px;">
            <div class="container">
              <div class="dropdown-divider" style="margin-top:20px;; margin-bottom:20px;"></div>
              <div class="row">
                <div class="card-body" style="padding:0px;">
                  <table class="table table-bordered table-hover" id="TableItemListDetailSub" cellspacing="0" role="grid" style="font-size:24px;width:100%;font-family: 'THSarabunNew'">
                    <thead style="font-size:24px;">
                      <tr role="row">
                        <th style='width: 10%;'nowrap><?php echo $array['no'][$language]; ?></th>
                        <th style='width: 25%;'nowrap><?php echo $array['rfid'][$language]; ?></th>
                        <th style='width: 50%;'nowrap><?php echo $array['item'][$language]; ?></th>
                        <th style='width: 15%;'nowrap><?php echo $array['unit'][$language]; ?></th>
                      </tr>
                    </thead>
                    <tbody id="tbody1_modal" style="font-size:23px;height:300px;">
                    </tbody>
                  </table>
                </div> -->
 <!-- -----------------------------Custom1------------------------------------ -->
 <div class="modal" id="dialogItemCode" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="card-body" style="padding:0px;">
            <div class="row">
              <div class="col-md-8">
                <div class='form-group row'>
                  <label class="col-sm-3 col-form-label text-right pr-5"><?php echo $array['searchplace'][$language]; ?></label>
                  <input type="text" class="form-control col-sm-9" name="searchitem" id="searchitem" placeholder="<?php echo $array['searchplace'][$language]; ?>" >
                </div>
              </div>
              <div class="col-md-2">
                <button type="button" class="btn btn-primary btn-block" name="button" onclick="ShowItem();"><?php echo $array['search'][$language]; ?></button>
              </div>
              <div class="col-md-2">
                  <button type="button" class="btn btn-warning  btn-block" name="button" onclick="getImport(1);"><?php echo $array['import'][$language]; ?></button>
              </div>
            </div>
            <table class="table table-fixed table-condensed table-striped" id="TableItem" width="100%" cellspacing="0" role="grid" style="font-size:24px;width:1100px;font-family: 'THSarabunNew'">
              <thead style="font-size:24px;">
                <tr role="row">
                  <th style='width: 10%;' nowrap><?php echo $array['no'][$language]; ?></th>
                  <th style='width: 20%;' nowrap><?php echo $array['code'][$language]; ?></th>
                  <th style='width: 25%;' nowrap><?php echo $array['item'][$language]; ?></th>
                  <th style='width: 15%;' nowrap><center><?php echo $array['unit'][$language]; ?></center></th>
                  <th style='width: 15%;' nowrap><?php echo $array['numofpiece'][$language]; ?></th>
                  <th style='width: 15%;' nowrap><?php echo $array['weight'][$language]; ?></th>
                </tr>
              </thead>
              <tbody id="tbody1_modal" class="nicescrolled" style="font-size:23px;height:300px;">
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- custom modal2 -->
  <div class="modal" id="dialogListDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <div class="card-body" style="padding:0px;">
                    <div class="row">
                    </div>
                    <table class="table table-fixed table-condensed table-striped" id="TableRefDocNo" cellspacing="0" role="grid">
                      <thead style="font-size:24px;">
                        <tr role="row">
                        <th style='width: 10%;'nowrap><?php echo $array['no'][$language]; ?></th>
                        <th style='width: 25%;'nowrap><?php echo $array['rfid'][$language]; ?></th>
                        <th style='width: 50%;'nowrap><?php echo $array['item'][$language]; ?></th>
                        <th style='width: 15%;'nowrap><?php echo $array['unit'][$language]; ?></th>
                        </tr>
                      </thead>
                      <tbody id="tbody1_modal" class="nicescrolled" style="font-size:23px;height:300px;">
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
    
              
              <!-- Bootstrap core JavaScript-->
              <script src="../template/vendor/jquery/jquery.min.js"></script>
              <script src="../template/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

              <!-- Core plugin JavaScript-->
              <script src="../template/vendor/jquery-easing/jquery.easing.min.js"></script>

              <!-- Page level plugin JavaScript-->
              <script src="../template/vendor/datatables/jquery.dataTables.js"></script>
              <script src="../template/vendor/datatables/dataTables.bootstrap4.js"></script>

              <!-- Custom scripts for all pages-->
              <script src="../template/js/sb-admin.min.js"></script>

              <!-- Demo scripts for this page-->
              <script src="../template/js/demo/datatables-demo.js"></script>

            </body>

            </html>
