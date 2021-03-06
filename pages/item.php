<?php
session_start();
$Userid = $_SESSION['Userid'];
$TimeOut = $_SESSION['TimeOut'];
$PmID = $_SESSION['PmID'];
if ($Userid == "") {
  // header("location:../index.html");
}

if (empty($_SESSION['lang'])) {
  $language = 'th';
} else {
  $language = $_SESSION['lang'];
}

header('Content-type: text/html; charset=utf-8');
$xml = simplexml_load_file('../xml/general_lang.xml');
$xml2 = simplexml_load_file('../xml/main_lang.xml');
$json = json_encode($xml);
$array = json_decode($json, TRUE);
$json2 = json_encode($xml2);
$array2 = json_decode($json2, TRUE);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title><?php echo $array['item'][$language]; ?></title>

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

  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="../jQuery-ui/jquery-1.12.4.js"></script>
  <script src="../jQuery-ui/jquery-ui.js"></script>
  <script type="text/javascript">
    jqui = jQuery.noConflict(true);
  </script>

  <link href="../dist/css/sweetalert2.css" rel="stylesheet">
  <script src="../dist/js/sweetalert2.min.js"></script>
  <script src="../dist/js/jquery-3.3.1.min.js"></script>


  <link href="../datepicker/dist/css/datepicker.min.css" rel="stylesheet" type="text/css">
  <script src="../datepicker/dist/js/datepicker.min.js"></script>
  <!-- Include English language -->
  <script src="../datepicker/dist/js/i18n/datepicker.en.js"></script>

  <script type="text/javascript">
    var summary = [];

    $(document).ready(function(e) {
      $('#NewItem').show();
      $('#BlankItemBNT').show();
      $('#ActiveBNT').hide();
      $('#AddItemBNT').hide();
      $('#xPrice').hide();

      GetHospital();
      GetmainCat();
      getCatagory();
      //On create
      $('.TagImage').bind('click', {
        imgId: $(this).attr('id')
      }, function(evt) {
        alert(evt.imgId);
      });
      //On create
      var userid = '<?php echo $Userid; ?>';
      if (userid != "" && userid != null && userid != undefined) {
        var dept = '<?php echo $_SESSION['Deptid']; ?>';
        var data = {
          'STATUS': 'getDocument',
          'DEPT': dept
        };

        // console.log(JSON.stringify(data));
        senddata(JSON.stringify(data));
      }

      var data = {
        'STATUS': 'getUnit'
      };

      // console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));

      $('#UnitName').on('change', function() {
        $('#Unitshows').val(this.value);
      });

      $('.numonly').on('input', function() {
        this.value = this.value.replace(/[^0-9.]/g, ''); //<-- replace all other than given set of values
      });
      $('.charonly').on('input', function() {
        this.value = this.value.replace(/[^a-zA-Zก-ฮๅภถุึคตจขชๆไำพะัีรนยบลฃฟหกดเ้่าสวงผปแอิืทมใฝ๑๒๓๔ู฿๕๖๗๘๙๐ฎฑธํ๊ณฯญฐฅฤฆฏโฌ็๋ษศซฉฮฺ์ฒฬฦ. ]/g, ''); //<-- replace all other than given set of values
      });
    }).mousemove(function(e) {
      parent.afk();
    }).keyup(function(e) {
      parent.afk();
    });

    dialog = jqui("#dialog").dialog({
      autoOpen: false,
      height: 650,
      width: 1200,
      modal: true,
      buttons: {
        "ปิด": function() {
          dialog.dialog("close");
        }
      },
      close: function() {
        console.log("close");
      }
    });

    jqui("#dialogreq").button().on("click", function() {
      dialog.dialog("open");
    });

    function unCheckDocDetail() {
      // alert( $('input[name="checkdocno"]:checked').length + " :: " + $('input[name="checkdocno"]').length );
      if ($('input[name="checkdocdetail"]:checked').length == $('input[name="checkdocdetail"]').length) {
        $('input[name="checkAllDetail').prop('checked', true);
      } else {
        $('input[name="checkAllDetail').prop('checked', false);
      }
    }

    function getDocDetail() {
      // alert( $('input[name="checkdocno"]:checked').length + " :: " + $('input[name="checkdocno"]').length );
      if ($('input[name="checkdocno"]:checked').length == $('input[name="checkdocno"]').length) {
        $('input[name="checkAllDoc').prop('checked', true);
      } else {
        $('input[name="checkAllDoc').prop('checked', false);
      }

      /* declare an checkbox array */
      var chkArray = [];

      /* look for all checkboes that have a class 'chk' attached to it and check if it was checked */
      $("#checkdocno:checked").each(function() {
        chkArray.push($(this).val());
      });

      /* we join the array separated by the comma */
      var DocNo = chkArray.join(',');
      // alert( DocNo );
      $('#TableDetail tbody').empty();
      var dept = '<?php echo $_SESSION['Deptid']; ?>';
      var data = {
        'STATUS': 'getDocDetail',
        'DEPT': dept,
        'DocNo': DocNo
      };
      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }

    var isChecked1 = false;
    var isChecked2 = false;

    function getCheckAll(sel) {
      if (sel == 0) {
        isChecked1 = !isChecked1;
        // $( "div #aa" )
        //   .text( "For this isChecked " + isChecked1 + "." )
        //   .css( "color", "red" );

        $('input[name="checkdocno"]').each(function() {
          this.checked = isChecked1;
        });
        getDocDetail();
      } else {
        isChecked2 = !isChecked2;
        $('input[name="checkdocdetail"]').each(function() {
          this.checked = isChecked2;
        });
      }
    }

    function getSearchDocNo() {
      var dept = '<?php echo $_SESSION['Deptid']; ?>';

      $('#TableDocumentSS tbody').empty();
      var str = $('#searchtxt').val();
      var datepicker = $('#datepicker').val();
      datepicker = datepicker.substring(6, 10) + "-" + datepicker.substring(3, 5) + "-" + datepicker.substring(0, 2);

      var data = {
        'STATUS': 'getSearchDocNo',
        'DEPT': dept,
        'DocNo': str,
        'Datepicker': datepicker
      };

      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }

    function CreateSentSterile() {
      var userid = '<?php echo $Userid; ?>';
      var dept = '<?php echo $_SESSION['Deptid']; ?>';
      /* declare an checkbox array */
      var chkArray1 = [];

      /* look for all checkboes that have a class 'chk' attached to it and check if it was checked */
      $("#checkdocno:checked").each(function() {
        chkArray1.push($(this).val());
      });

      /* we join the array separated by the comma */
      var DocNo = chkArray1.join(',');

      /* declare an checkbox array */
      var chkArray2 = [];

      /* look for all checkboes that have a class 'chk' attached to it and check if it was checked */
      $("#checkdocdetail:checked").each(function() {
        chkArray2.push($(this).val());
      });

      /* we join the array separated by the comma */
      var UsageCode = chkArray2.join(',');
      var data = {
        'STATUS': 'CreateSentSterile',
        'DEPT': dept,
        'DocNo': DocNo,
        'UsageCode': UsageCode,
        'userid': userid
      };

      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }

    function setTag() {
      var DocNo = $("#docnofield").val();
      /* declare an checkbox array */
      var chkArray = [];

      /* look for all checkboes that have a class 'chk' attached to it and check if it was checked */
      $("#IsTag:checked").each(function() {
        chkArray.push($(this).val());
      });

      /* we join the array separated by the comma */
      var UsageCode = chkArray.join(',');
      var userid = '<?php echo $Userid; ?>';
      var dept = '<?php echo $_SESSION['Deptid']; ?>';
      var data = {
        'STATUS': 'SSDTag',
        'DEPT': dept,
        'userid': userid,
        'DocNo': DocNo,
        'UsageCode': UsageCode
      };

      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }

    function CreatePayout() {
      var userid = '<?php echo $Userid; ?>';
      var dept = '<?php echo $_SESSION['Deptid']; ?>';
      var data = {
        'STATUS': 'CreatePayout',
        'DEPT': dept,
        'userid': userid
      };

      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }

    function AddPayoutDetail() {
      var userid = '<?php echo $Userid; ?>';
      var dept = '<?php echo $_SESSION['Deptid']; ?>';
      var data = {
        'STATUS': 'CreatePayout',
        'DEPT': dept,
        'userid': userid
      };

      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }

    function GetmainCat() {
      var maincatagory = $("#maincatagory").val();
      var data = {
        'STATUS': 'GetmainCat',
      };
      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }

    function getCatagory() {
      var maincatagory = $('#maincatagory option:selected').attr("value");
      if (typeof maincatagory == 'undefined') maincatagory = "1";
      $('#maincatagory2').val(maincatagory);
      console.log($('#maincatagory2 option:selected').attr("value"));
      var catagory1 = $("#catagory1").val();
      var data = {
        'STATUS': 'getCatagory',
        'maincatagory': maincatagory

      };
      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }

    function GetHospital() {
      var data = {
        'STATUS': 'GetHospital',
      };
      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }

    function getCatagory2() {
      var maincatagory = $('#maincatagory2 option:selected').attr("value");
      if (typeof maincatagory == 'undefined') maincatagory = "1";
      $('#maincatagory').val(maincatagory);
      var catagory1 = $("#catagory1").val();
      var data = {
        'STATUS': 'getCatagory',
        'maincatagory': maincatagory

      };
      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }


    function ShowItem() {
      var item = $("#searchitem").val();
      var catagory = $("#catagory1").val();
      // alert(item);
      var active = '0';

      var data = {
        'STATUS': 'ShowItem',
        'Catagory': catagory,
        'Keyword': item,
        'active': active
      };
      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }

    function ShowItem_Active_0() {
      var item = $("#searchitem").val();
      var catagory = $("#catagory1").val();
      // alert(item);
      var active = '0';
      var data = {
        'STATUS': 'ShowItem_Active_0',
        'Catagory': catagory,
        'Keyword': item,
        'active': active
      };
      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }

    function AddItem() {
      var count = 0;
      $(".checkblank").each(function() {
        if ($(this).val() == "" || $(this).val() == undefined) {
          count++;
        }
      });
      console.log(count);

      var Catagory = $('#catagory2').val();
      var ItemCode = $('#ItemCode').val();
      var ItemName = $('#ItemName').val();
      var CusPrice = $('#CusPrice').val();
      var FacPrice = $('#FacPrice').val();
      var UnitName = $('#UnitName').val();
      var SizeCode = $('#SizeCode').val();
      var Weight = $('#Weight').val();

      if (count == 0) {
        $('.checkblank').each(function() {
          if ($(this).val() == "" || $(this).val() == undefined) {
            $(this).css('border-color', 'red');
          } else {
            $(this).css('border-color', '');
          }
        });
        if (ItemCode != "") {
          swal({
            title: "<?php echo $array['addoredit'][$language]; ?>",
            text: "<?php echo $array['addoredit1'][$language]; ?>",
            type: "question",
            showCancelButton: true,
            confirmButtonClass: "btn-success",
            confirmButtonText: "<?php echo $array['confirm'][$language]; ?>",
            cancelButtonText: "<?php echo $array['cancel'][$language]; ?>",
            confirmButtonColor: '#6fc864',
            cancelButtonColor: '#3085d6',
            closeOnConfirm: false,
            closeOnCancel: false,
            showCancelButton: true
          }).then(result => {
            var data = {
              'STATUS': 'AddItem',
              'Catagory': Catagory,
              'ItemCode': ItemCode,
              'ItemName': ItemName,
              'CusPrice': CusPrice,
              'FacPrice': FacPrice,
              'UnitName': UnitName,
              'SizeCode': SizeCode,
              'Weight': Weight
            };

            console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
          })

        }
      } else {
        swal({
          title: '',
          text: "<?php echo $array['required'][$language]; ?>",
          type: 'info',
          showCancelButton: false,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          showConfirmButton: false,
          timer: 2000,
          confirmButtonText: 'Ok'
        })
        $('.checkblank').each(function() {
          if ($(this).val() == "" || $(this).val() == undefined) {
            $(this).css('border-color', 'red');
          } else {
            $(this).css('border-color', '');
          }
        });
      }
    }

    function NewItem() {
      var count = 0;
      $(".checkblank").each(function() {
        if ($(this).val() == "" || $(this).val() == undefined) {
          count++;
        }
      });
      console.log(count);

      var Catagory = $('#catagory2').val();
      var ItemCode = $('#ItemCode').val();
      var ItemName = $('#ItemName').val();
      var CusPrice = $('#CusPrice').val();
      var FacPrice = $('#FacPrice').val();
      var UnitName = $('#UnitName').val();
      var SizeCode = $('#SizeCode').val();
      var Weight = $('#Weight').val();

      if (count == 0) {
        $('.checkblank').each(function() {
          if ($(this).val() == "" || $(this).val() == undefined) {
            $(this).css('border-color', 'red');
          } else {
            $(this).css('border-color', '');
          }
        });
        if (ItemCode != "") {
          swal({
            title: "<?php echo $array['addoredit'][$language]; ?>",
            text: "<?php echo $array['addoredit1'][$language]; ?>",
            type: "question",
            showCancelButton: true,
            confirmButtonClass: "btn-success",
            confirmButtonText: "<?php echo $array['confirm'][$language]; ?>",
            cancelButtonText: "<?php echo $array['cancel'][$language]; ?>",
            confirmButtonColor: '#6fc864',
            cancelButtonColor: '#3085d6',
            closeOnConfirm: false,
            closeOnCancel: false,
            showCancelButton: true
          }).then(result => {
            var data = {
              'STATUS': 'NewItem',
              'Catagory': Catagory,
              'ItemCode': ItemCode,
              'ItemName': ItemName,
              'CusPrice': CusPrice,
              'FacPrice': FacPrice,
              'UnitName': UnitName,
              'SizeCode': SizeCode,
              'Weight': Weight
            };

            console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
          })

        }
      } else {
        swal({
          title: '',
          text: "<?php echo $array['required'][$language]; ?>",
          type: 'info',
          showCancelButton: false,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          showConfirmButton: false,
          timer: 2000,
          confirmButtonText: 'Ok'
        })
        $('.checkblank').each(function() {
          if ($(this).val() == "" || $(this).val() == undefined) {
            $(this).css('border-color', 'red');
          } else {
            $(this).css('border-color', '');
          }
        });
      }
    }

    function CreateItemCode() {
      var Catagory = $('#catagory2').val();
      var modeCode = $('#formatitem:checked').val();
      var modeCheck = $('#checkitem:checked').val();
      console.log(typeof modeCheck == 'undefined');
      if (typeof modeCheck == 'undefined') {
        if (modeCode == 1) {
          $('#oldCodetype').show();
          var hospitalCode = $('#hospital').val();
          var typeCode = $('#typeLinen').val();
          var packCode = $('#numPack').val();
        } else {
          $('#oldCodetype').hide();
          var hospitalCode = "";
          var typeCode = "";
          var packCode = "";
        }
        var data = {
          'STATUS': 'CreateItemCode',
          'Catagory': Catagory,
          'modeCode': modeCode,
          'hospitalCode': hospitalCode,
          'typeCode': typeCode,
          'packCode': packCode,
        };
        console.log(JSON.stringify(data));
        senddata(JSON.stringify(data));
      }
    }

    function AddUnit() {
      var priceunit = $('#priceunit').val();
      var mul = $('#mulinput').val();
      var u1 = $('#Unitshows').val();
      var u2 = $('#subUnit').val();


      var itemcode = $('#ItemCode').val();

      if (mul != "" && priceunit != "") {
        var data = {
          'STATUS': 'AddUnit',
          'ItemCode': itemcode,
          'MpCode': u2,
          'UnitCode': u1,
          'Multiply': mul,
          'priceunit': priceunit
        };

        console.log(JSON.stringify(data));
        senddata(JSON.stringify(data));
      } else {
        swal({
          title: '',
          text: "<?php echo $array['required'][$language]; ?>",
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

    function CancelItem() {
      var itemcode = $("#ItemCode").val();
      swal({
        title: "<?php echo $array['canceldata'][$language]; ?>",
        text: "<?php echo $array['confirm1'][$language]; ?>" + itemcode + "<?php echo $array['confirm2'][$language]; ?>",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "<?php echo $array['confirm'][$language]; ?>",
        cancelButtonText: "<?php echo $array['cancel'][$language]; ?>",
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        closeOnConfirm: false,
        closeOnCancel: false,
        showCancelButton: true
      }).then(result => {
        var ItemCode = $('#ItemCode').val();
        var data = {
          'STATUS': 'CancelItem',
          'ItemCode': itemcode
        }
        console.log(JSON.stringify(data));
        senddata(JSON.stringify(data));
      })
      $('#NewItem').show();
      $('#AddItemBNT').hide();
      $("input[name=formatitem][value=1]").prop('checked', true);
    }

    function Blankinput() {
      $(".radio-c :input").attr("disabled", false);
      $("input[name=formatitem][value=1]").prop('checked', true);
      $('#oldCodetype').show();
      $('.checkblank').each(function() {
        $(this).val("");
      });
      $('#ItemCode').val("");
      $('#catagory2').val("1");
      $('#UnitName').val("1");
      $('#SizeCode').val("1");
      $('#hospital').val("BHQ");
      $('#typeLinen').val("P");
      $('#numPack').val("01");
      ShowItem();
      $('#bCancel').attr('disabled', true);
      $('#delete_icon').addClass('opacity');
      $('#NewItem').show();
      $('#AddItemBNT').hide();
      CreateItemCode();
    }

    function getdetail(ItemCode) {
      if (ItemCode.length > 9) {
        $("input[name=formatitem][value=1]").prop('checked', true);
        $('#oldCodetype').show();

      } else {
        $("input[name=formatitem][value=2]").prop('checked', true);
        $('#oldCodetype').hide();
      }
      if (ItemCode != "" && ItemCode != undefined) {
        var data = {
          'STATUS': 'getdetail',
          'ItemCode': ItemCode
        };

        console.log(JSON.stringify(data));
        senddata(JSON.stringify(data));
      }
      $('#NewItem').hide();
      $('#AddItemBNT').show();
      $(".radio-c :input").attr("disabled", true);
    }

    function ActiveItem() {
      var ItemCode = $('#ItemCode').val();
      if (ItemCode != "" && ItemCode != undefined) {
        var data = {
          'STATUS': 'ActiveItem',
          'ItemCode': ItemCode
        };

        console.log(JSON.stringify(data));
        senddata(JSON.stringify(data));
      }
    }

    function DeleteUnit() {
      var RowID = $("#checkitem2:checked").val();
      swal({
        title: "<?php echo $array['canceldata'][$language]; ?>",
        text: "<?php echo $array['canceldata1'][$language]; ?>",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "<?php echo $array['confirm'][$language]; ?>",
        cancelButtonText: "<?php echo $array['cancel'][$language]; ?>",
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        closeOnConfirm: false,
        closeOnCancel: false,
        showCancelButton: true
      }).then(result => {
        var ItemCode = $('#ItemCode').val();
        var data = {
          'STATUS': 'DeleteUnit',
          'RowID': RowID
        }
        console.log(JSON.stringify(data));
        senddata(JSON.stringify(data));
      })
    }

    function SavePY() {
      $('#TableDocumentSS tbody').empty();
      var dept = '<?php echo $_SESSION['Deptid']; ?>';
      var datepicker = $('#datepicker').val();
      datepicker = datepicker.substring(6, 10) + "-" + datepicker.substring(3, 5) + "-" + datepicker.substring(0, 2);

      var DocNo = $("#docno").val();
      $("#searchtxt").val(DocNo);

      if (DocNo.length > 0) {
        swal({
          title: '<?php echo $array['savesuccess'][$language]; ?>',
          text: DocNo,
          type: 'success',
          showCancelButton: false,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          showConfirmButton: false,
          timer: 2000,
          confirmButtonText: 'Ok'
        })
        var data = {
          'STATUS': 'SavePY',
          'DocNo': DocNo,
          'DEPT': dept,
          'Datepicker': datepicker
        };

        console.log(JSON.stringify(data));
        senddata(JSON.stringify(data));
      }
    }

    function DelItem() {
      var DocNo = $("#docno").val();
      /* declare an checkbox array */
      var chkArray = [];
      /* look for all checkboes that have a class 'chk' attached to it and check if it was checked */
      $("#checkitemdetail:checked").each(function() {
        chkArray.push($(this).val());
      });

      /* we join the array separated by the comma */
      var UsageCode = chkArray.join(',');

      // alert(DocNo + " : " + UsageCode);
      var data = {
        'STATUS': 'DelItem',
        'DocNo': DocNo,
        'UsageCode': UsageCode
      };

      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }

    function canceldocno(docno) {
      swal({
        title: "<?php echo $array['canceldata'][$language]; ?>",
        text: "<?php echo $array['canceldata2'][$language]; ?>" + docno + "<?php echo $array['canceldata3'][$language]; ?>",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "<?php echo $array['confirm'][$language]; ?>",
        cancelButtonText: "<?php echo $array['cancel'][$language]; ?>",
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        closeOnConfirm: false,
        closeOnCancel: false,
        showCancelButton: true
      }).then(result => {
        var data = {
          'STATUS': 'CancelDocNo',
          'DocNo': docno
        };

        console.log(JSON.stringify(data));
        senddata(JSON.stringify(data));
        getSearchDocNo();
      })
    }

    function addnum(cnt) {
      var add = parseInt($('#qty' + cnt).val()) + 1;
      if ((add >= 0) && (add <= 500)) {
        $('#qty' + cnt).val(add);
      }
    }

    function subtractnum(cnt) {
      var sub = parseInt($('#qty' + cnt).val()) - 1;
      if ((sub >= 0) && (sub <= 500)) {
        $('#qty' + cnt).val(sub);
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
      }).then(function() {
        window.location.href = "../logoff.php";
      }, function(dismiss) {
        window.location.href = "../logoff.php";
        if (dismiss === 'cancel') {

        }
      })
    }

    function senddata(data) {
      var form_data = new FormData();
      form_data.append("DATA", data);
      var URL = '../process/item.php';
      $.ajax({
        url: URL,
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        beforeSend: function() {
          swal({
            title: '<?php echo $array['pleasewait'][$language]; ?>',
            text: '<?php echo $array['processing'][$language]; ?>',
            allowOutsideClick: false
          })
          swal.showLoading();
        },
        success: function(result) {
          try {
            var temp = $.parseJSON(result);
          } catch (e) {
            console.log('Error#542-decode error');
          }
          swal.close();

          if (temp["status"] == 'success') {
            if (temp["form"] == 'getDocument') {
              $("#TableDocument tbody").empty();
              $("#TableDetail tbody").empty();

              $("#TableDocumentSS tbody").empty();
              $("#TableSendSterileDetail tbody").empty();

              $("#docno").val("");

              $("input[type='checkbox']").prop('checked', false);

              for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                var rowCount = $('#TableDocument >tbody >tr').length;
                var chkDoc = "<input type='radio' class='form-check-input' name='checkdocno' id='checkdocno' value='" + temp[i]['DocNo'] + "' onclick='getDocDetail()'>";
                var StrTr = "<tr id='tr" + temp[i]['DocNo'] + "'>" +
                  "<td style='width: 5%;'nowrap>" + chkDoc + "</td>" +
                  "<td style='width: 5%;'nowrap><label>" + (i + 1) + "</label></td>" +
                  "<td style='width: 20%;'nowrap>" + temp[i]['DocNo'] + "</td>" +
                  "<td style='width: 20%;' align='center'nowrap>" + temp[i]['DocDate'] + "</td>" +
                  "<td style='width: 10%;' align='center'nowrap>" + temp[i]['Qty'] + "</td>" +
                  "<td style='width: 30%;'nowrap>" + temp[i]['Elc'] + "</td>" +
                  "<td style='width: 10%;'nowrap><img src='../img/delete-32.png' onclick='canceldocno(\"" + temp[i]['DocNo'] + "\")'></td>" +
                  "</tr>";

                if (rowCount == 0) {
                  $("#TableDocument tbody").append(StrTr);
                } else {
                  $('#TableDocument tbody:last-child').append(StrTr);
                }
                if (temp[0]['DocNo'].length == 0) {
                  $("#TableDocument tbody").empty();
                  $("#TableDetail tbody").empty();
                  $("#TableDocumentSS tbody").empty();
                  $("#TableSendSterileDetail tbody").empty();
                  $("#docno").val("");
                }
              }
            } else if ((temp["form"] == 'CreatePayout')) {
              swal({
                title: "<?php echo $array['createdocno'][$language]; ?>",
                text: temp[0]['DocNo'] + " <?php echo $array['success'][$language]; ?>",
                type: "success",
                showCancelButton: false,
                timer: 5000,
                confirmButtonText: 'Ok',
                closeOnConfirm: false
              });
              getSearchDocNo();
            } else if ((temp["form"] == 'getCatagory')) {
              $("#catagory1").empty();
              $("#catagory2").empty();
              for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                var StrTr = "<option value = '" + temp[i]['CategoryCode'] + "'> " + temp[i]['CategoryName'] + " </option>";
                $("#catagory1").append(StrTr);
                $("#catagory2").append(StrTr);
              }
              CreateItemCode();
            } else if ((temp["form"] == 'GetHospital')) {
              for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                var StrTr = "<option value = '" + temp[i]['HospitalCode'] + "'> " + temp[i]['HospitalName'] + " </option>";
                $("#hospital").append(StrTr);
              }
            } else if ((temp["form"] == 'GetmainCat')) {
              for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                var StrTr = "<option value = '" + temp[i]['MainCategoryCode'] + "'> " + temp[i]['MainCategoryName'] + " </option>";
                $("#maincatagory").append(StrTr);
                $("#maincatagory2").append(StrTr);
                // $("#catagory2").append(StrTr);
              }
            } else if ((temp["form"] == 'getUnit')) {
              for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                var StrTr = "<option value = '" + temp[i]['UnitCode'] + "'> " + temp[i]['UnitName'] + " </option>";
                $("#UnitName").append(StrTr);
                $("#subUnit").append(StrTr);
                $("#Unitshows").append(StrTr);
              }

            } else if (temp["form"] == 'getDocDetail') {
              $("#TableDetail tbody").empty();
              $("#TableUnit tbody").empty();
              for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                var rowCount = $('#TableDetail >tbody >tr').length;
                var chkDoc = "<input type='checkbox' name='checkitemdetail' id='checkitemdetail' value='" + temp[i]['ID'] + ":" + temp[i]['UsageCode'] + "' onclick='unCheckDocDetail()'>";
                console.log(temp);
                $StrTr = "<tr id='tr" + temp[i]['UsageCode'] + "'>" +
                  "<td style='width: 5%;'nowrap>" + chkDoc + "</td>" +
                  "<td style='width: 5%;'nowrap><label> " + (i + 1) + "</label></td>" +
                  "<td style='width: 15%;'nowrap>" + temp[i]['UsageCode'] + "</td>" +
                  "<td style='width: 50%;'nowrap>" + temp[i]['itemname'] + "</td>" +
                  "<td style='width: 15%;' align='center'nowrap>" + temp[i]['UnitName'] + "</td>" +
                  "<td style='width: 10%;' align='center'nowrap>" + temp[i]['Qty'] + "</td>" +
                  "</tr>";

                if (rowCount == 0) {
                  $("#TableDetail tbody").append($StrTr);
                } else {
                  $('#TableDetail tbody:last-child').append($StrTr);
                }

                ShowItem();

              }
            } else if ((temp["form"] == 'ShowItem') || (temp["form"] == 'ShowItem_Active_0')) {

              $("#TableItem tbody").empty();
              $("#TableUnit tbody").empty();
              for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                var rowCount = $('#TableItem >tbody >tr').length;
                var chkDoc = "<input type='radio' name='checkitem' id='checkitem' value='" + i + ":" + temp[i]['ItemCode'] + "' onclick='getdetail(\"" + temp[i]['ItemCode'] + "\")'>";
                // var Qty = "<div class='row' style='margin-left:2px;'><button class='btn btn-danger' style='width:32px;' onclick='subtractnum(\""+i+"\")'>-</button><input class='form-control' style='width:50px; margin-left:3px; margin-right:3px; text-align:center;' id='qty"+i+"' value='0' disabled><button class='btn btn-success' style='width:32px;' onclick='addnum(\""+i+"\")'>+</button></div>";
                $StrTR = "<tr id='tr" + temp[i]['ItemCode'] + "'>" +
                  "<td style='width: 5%;' align='center'nowrap>" + chkDoc + "</td>" +
                  "<td style='width: 5%;' align='center'nowrap><label> " + (i + 1) + "</label></td>" +
                  "<td style='width: 25%;' align='left'nowrap>" + temp[i]['ItemCode'] + "</td>" +
                  "<td style='width: 25%;' align='left'nowrap>" + temp[i]['ItemName'] + "</td>" +
                  "<td style='width: 15%;' align='left'nowrap>" + temp[i]['UnitName'] + "</td>" +
                  "<td style='width: 13%;' align='left'nowrap>&nbsp;&nbsp;" + temp[i]['SizeCode'] + "</td>" +
                  "<td style='width: 12%;' align='center'nowrap>" + temp[i]['Weight'] + "</td>" +
                  "</tr>";

                if (rowCount == 0) {
                  $("#TableItem tbody").append($StrTR);
                } else {
                  $('#TableItem tbody:last-child').append($StrTR);
                }
              }
              $('#catagory2').val("1");
              $('#UnitName').val("1");
              $('#SizeCode').val("1");
              $('#hospital').val("BHQ");
              $('#typeLinen').val("P");
              $('#numPack').val("01");
              $('.checkblank').each(function() {
                $(this).val("");
              });
            } else if ((temp["form"] == 'getdetail')) {
              if ((Object.keys(temp).length - 2) > 0) {
                $("#TableUnit tbody").empty();
                // console.log(temp);
                $('#catagory2').val(temp[0]['CategoryCode']);
                $('#ItemCode').val(temp[0]['ItemCode']);
                $('#ItemName').val(temp[0]['ItemName']);
                $('#CusPrice').val(temp[0]['CusPrice']);
                $('#FacPrice').val(temp[0]['FacPrice']);
                $('#UnitName').val(temp[0]['UnitCode']);
                $('#Unitshows').val(temp[0]['UnitCode']);
                $('#SizeCode').val(temp[0]['SizeCode']);
                $('#Weight').val(temp[0]['Weight']);
                $('#bCancel').attr('disabled', false);
                $('#delete_icon').removeClass('opacity');
                if (temp[0]['RowID']) {
                  for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                    var rowCount = $('#TableUnit >tbody >tr').length;
                    var chkDoc = "<input type='radio' name='checkitem2' id='checkitem2' value='" + temp[i]['RowID'] + "'>";
                    StrTR = "<tr id='tr" + temp[i]['RowID'] + "'>" +
                      "<td style='width: 5%;' align='center'nowrap>" + chkDoc + "</td>" +
                      "<td style='width: 5%;' align='center'nowrap><label> " + (i + 1) + "</label></td>" +
                      "<td style='width: 30%;' align='left'nowrap>" + temp[i]['ItemName'] + "</td>" +
                      "<td style='width: 15%;' align='left'nowrap>" + temp[i]['MpCode'] + "</td>" +
                      "<td style='width: 17%;' align='left'nowrap>" + temp[i]['UnitName2'] + "</td>" +
                      "<td style='width: 18%;' align='left'nowrap>" + temp[i]['Multiply'] + "</td>" +
                      "<td style='width: 10%;' align='left'nowrap>" + temp[i]['PriceUnit'] + "</td>" +

                      "</tr>";

                    if (rowCount == 0) {
                      $("#TableUnit tbody").append(StrTR);
                    } else {
                      $('#TableUnit tbody:last-child').append(StrTR);
                    }
                  }
                }
              }
            } else if ((temp["form"] == 'AddItem')) {
              $('#NewItem').show();
              $('#AddItemBNT').hide();
              $(".radio-c :input").attr("disabled", false);
              $('#ItemCode').val("");
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
                type: 'success',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                showConfirmButton: false,
                timer: 2000,
                confirmButtonText: 'Ok'
              }).then(function() {

              }, function(dismiss) {
                $('.checkblank').each(function() {
                  $(this).val("");
                });
                $('#catagory2').val("1");
                $('#UnitName').val("1");
                $('#SizeCode').val("1");
                $('#hospital').val("BHQ");
                $('#typeLinen').val("P");
                $('#numPack').val("01");

                ShowItem();
              })
            } else if ((temp["form"] == 'AddUnit')) {
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
                type: 'success',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                showConfirmButton: false,
                timer: 2000,
                confirmButtonText: 'Ok'
              }).then(function() {

              }, function(dismiss) {

                var itemcode = $('#ItemCode').val();
                getdetail(itemcode);
                $('#subUnit').val("1");
                $('#mulinput').val("");
              })
            } else if ((temp["form"] == 'CancelUnit')) {
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
                type: 'success',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                showConfirmButton: false,
                timer: 2000,
                confirmButtonText: 'Ok'
              }).then(function() {

              }, function(dismiss) {
                var itemcode = $('#ItemCode').val();
                getdetail(itemcode);
                $('#subUnit').val("1");
                $('#mulinput').val("");
              })
            } else if ((temp["form"] == 'CancelItem')) {
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
                type: 'success',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                showConfirmButton: false,
                timer: 2000,
                confirmButtonText: 'Ok'
              }).then(function() {

              }, function(dismiss) {
                $('.checkblank').each(function() {
                  $(this).val("");
                });

                $('#catagory2').val("1");
                $('#UnitName').val("1");
                $('#SizeCode').val("1");
                $('#hospital').val("BHQ");
                $('#typeLinen').val("P");
                $('#numPack').val("01");
                ShowItem();
              })
            } else if (temp['form'] == 'CreateItemCode') {
              $('#ItemCode').val(temp['ItemCode']);
            } else if (temp['form'] == 'ActiveItem') {
              temp['msg'] = "<?php echo $array['addsuccessmsg'][$language]; ?>";
              swal({
                title: '',
                text: temp['msg'],
                type: 'success',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                showConfirmButton: false,
                timer: 2000,
                confirmButtonText: 'Ok'
              })
            }
          } else if (temp['status'] == "failed") {
            $("#TableItem tbody").empty();
            $("#TableUnit tbody").empty();
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
              timer: 2000,
              confirmButtonText: 'Ok'
            })
            $("#docnofield").val(temp[0]['DocNo']);

            $("#TableDocumentSS tbody").empty();
            $("#TableSendSterileDetail tbody").empty();

          } else if (temp['status'] == "notfound") {
            $("#TableItem tbody").empty();
          } else {
            console.log(temp['msg']);
          }
        },
        failure: function(result) {
          alert(result);
        },
        error: function(xhr, status, p3, p4) {
          var err = "Error " + " " + status + " " + p3 + " " + p4;
          if (xhr.responseText && xhr.responseText[0] == "{")
            err = JSON.parse(xhr.responseText).Message;
          console.log(err);
        }
      });
    }
  </script>

  <style media="screen">
    @font-face {
      font-family: myFirstFont;
      src: url("../fonts/DB Helvethaica X.ttf");
    }

    body {
      font-family: myFirstFont;
      font-size: 22px;
    }

    .nfont {
      font-family: myFirstFont;
      font-size: 22px;
    }

    input,
    select {
      font-size: 24px !important;
    }

    th,
    td {
      font-size: 24px !important;
    }

    .table>thead>tr>th {
      background-color: #1659a2;
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
      border-top-left-radius: 15px;
    }

    table tr:first-child th:first-child {
      border-bottom-left-radius: 15px;
    }

    /* top-right border-radius */
    table tr:first-child th:last-child {
      border-top-right-radius: 15px;
    }

    table tr:first-child th:last-child {
      border-bottom-right-radius: 15px;
    }

    /* bottom-left border-radius */
    table tr:last-child td:first-child {
      border-bottom-left-radius: 6px;
    }

    /* bottom-right border-radius */
    table tr:last-child td:last-child {
      border-bottom-right-radius: 6px;
    }

    button {
      font-size: 24px !important;
    }

    a.nav-link {
      width: auto !important;
    }

    .datepicker {
      z-index: 9999 !important
    }

    .hidden {
      visibility: hidden;
    }

    .sidenav {
      height: 100%;
      overflow-x: hidden;
      /* padding-top: 20px; */
      border-left: 2px solid #bdc3c7;
    }

    .search {
      /* padding: 6px 8px 6px 16px; */
      text-decoration: none;
      font-size: 25px;
      color: #818181;
      display: block;
    }

    .mhee a {
      /* padding: 6px 8px 6px 16px; */
      text-decoration: none;
      font-size: 25px;
      color: #818181;
      display: block;
    }

    .mhee a:hover {
      color: #2c3e50;
      font-weight: bold;
      font-size: 26px;
    }

    .mhee button {
      /* padding: 6px 8px 6px 16px; */
      font-size: 25px;
      color: #2c3e50;
      background: none;
      box-shadow: none !important;
    }

    .mhee button:hover {
      color: #2c3e50;
      font-weight: bold;
      font-size: 26px;
      outline: none;
    }

    .sidenav a {
      padding: 6px 8px 6px 16px;
      text-decoration: none;
      font-size: 25px;
      color: #818181;
      display: block;
    }

    .sidenav a:hover {
      color: #2c3e50;
      font-weight: bold;
      font-size: 26px;
    }

    .icon {
      padding-top: 6px;
      padding-left: 33px;
    }

    .opacity {
      opacity: 0.5;
    }

    @media (min-width: 992px) and (max-width: 1199.98px) {

      .icon {
        padding-top: 6px;
        padding-left: 23px;
      }

      .sidenav {
        margin-left: 30px;
      }

      .sidenav a {
        font-size: 20px;

      }
    }
  </style>

</head>

<body id="page-top">
  <!-- iii -->
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0)"><?php echo $array2['menu']['system']['title'][$language]; ?></a></li>
    <li class="breadcrumb-item active"><?php echo $array2['menu']['system']['sub'][3][$language]; ?></li>
  </ol>

  <div id="wrapper">
    <!-- content-wrapper -->
    <div id="content-wrapper">
      <!--
          <div class="container-fluid">
            <ol class="breadcrumb" style="font-size:20px;">
              <li class="breadcrumb-item"><a href="#">รายการ</a></li>
              <li class="breadcrumb-item active">Item</li>
            </ol>
          </div>

          <div class="mycheckbox">
            <input type="checkbox" name='useful' id='useful' onclick='setTag()'/><label for='useful' style='color:#FFFFFF'> </label>
          </div> style="font-family: 'THSarabunNew'; font-size:20px;"
-->

      <div class="row">
        <div class="col-md-12">
          <!-- tag column 1 -->
          <div class="container-fluid">
            <div class="card-body" style="padding:0px; margin-top:-12px;">
              <div class="row">
                <div class="col-md-2">
                  <div class="row" style="font-size:24px;margin-left:2px;">
                    <select class="form-control" style="font-size:24px;" id="maincatagory" onchange="getCatagory();"></select>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="row" style="font-size:24px;margin-left:2px;">
                    <select class="form-control" style="font-size:24px;" id="catagory1"></select>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="row " style="margin-left:2px;">
                    <input type="text" class="form-control" style="font-size:24px;" name="searchitem" id="searchitem" placeholder="<?php echo $array['searchplace'][$language]; ?>">
                    
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="row mhee" style="margin-left:0px;">
                  <img src="../img/icon/i_search.png" style="margin-left: 15px;width:36px;" class='mr-3 mhee'>
                    <a href='javascript:void(0)' onclick="ShowItem()" id="bSave" class="search">
                      <?php echo $array['search'][$language]; ?></a>
                  </div>
                </div>
                <!-- 
                <div class="col-md-3">
                  <div class="row mhee" style="margin-left:0px;">
                    <img src="../img/icon/i_search.png" style="margin-left: 15px;width:36px;" class='mr-3'>
                    <a href='javascript:void(0)' onclick="ShowItem_Active_0()" id="bSave">
                      <?php echo $array['search_active'][$language]; ?></a>
                  </div>
                </div>
                -->
              </div>
              <table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="TableItem" width="100%" cellspacing="0" role="grid">
                <thead id="theadsum">
                  <tr role="row">
                    <th style='width: 5%; font-size:13px;'>&nbsp;</th>
                    <th style='width: 5%;' nowrap><?php echo $array['no'][$language]; ?></th>
                    <th style='width: 25%;' nowrap><?php echo $array['codecode'][$language]; ?></th>
                    <th style='width: 25%;' nowrap><?php echo $array['item'][$language]; ?></th>
                    <th style='width: 15%;' nowrap><?php echo $array['unit2'][$language]; ?></th>
                    <th style='width: 13%;' nowrap><?php echo $array['size'][$language]; ?></th>
                    <th style='width: 12%;' nowrap><?php echo $array['weight'][$language]; ?></th>
                  </tr>
                </thead>
                <tbody id="tbody" class="nicescrolled" style="font-size:23px;height:250px;">
                </tbody>
              </table>

            </div>
          </div>
        </div> <!-- tag column 1 -->
      </div>

      <div class="row">
        <!-- start row tab -->
        <div class="col-md-10">
          <!-- tag column 1 -->
          <div class="container-fluid">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><?php echo $array['detail'][$language]; ?></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><?php echo $array['mulmultiply'][$language]; ?></a>
              </li>
            </ul>

            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <!-- /.content-wrapper -->
                <div class="row">
                  <div class="col-md-12">
                    <!-- tag column 1 -->
                    <div class="container-fluid">
                      <div class="card-body" style="padding:0px; margin-top:10px;">
                        <!-- =================================================================== -->
                        <div class="row mt-4">
                          <div class="col-md-6">
                            <div class='form-group row'>
                              <label class="col-sm-4 col-form-label text-right"><?php echo $array['code'][$language]; ?></label>
                              <input type="text" class="form-control col-sm-8" id="ItemCode" placeholder="<?php echo $array['code'][$language]; ?>" disabled>
                            </div>
                          </div>
                          <div class="col-md-1">
                          </div>
                          <div class="col-md-5">
                            <div class="row ">
                              <div class="col-md-6">
                                <div class='form-group row'>
                                  <div class='radio-c' style="align-content:center">
                                    <input type='radio' name='formatitem' id='formatitem' value='1' onclick="CreateItemCode()" checked="checked">
                                  </div>
                                  <label class="col-sm-10 col-form-label text-left"><?php echo $array['oldFormatItemCode'][$language]; ?></label>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class='form-group row'>
                                  <div class='radio-c' style="align-content:center">
                                    <input type='radio' name='formatitem' id='formatitem' value='2' onclick="CreateItemCode()">
                                  </div>
                                  <label class="col-sm-10 col-form-label text-left"><?php echo $array['newFormatItemCode'][$language]; ?></label>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <!-- =================================================================== -->
                        <div class="row" id="oldCodetype">
                          <div class="col-md-6">
                            <div class='form-group row'>
                              <label class="col-sm-4 col-form-label text-right"><?php echo $array['hosname'][$language]; ?></label>
                              <select class="form-control col-sm-8 checkblank" id="hospital" onchange="CreateItemCode()"></select>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="row">
                              <label class="col-sm-4 col-form-label text-right"><?php echo $array['type'][$language]; ?></label>
                              <div class="col-md-8">
                                <div class='form-group row'>
                                  <select class="form-control col-sm-5 checkblank" id="typeLinen" onchange="CreateItemCode()">
                                    <option value="P">Patient Shirt</option>
                                    <option value="S">Staff Uniform</option>
                                    <option value="F">Flat Sheet</option>
                                    <option value="T">Towel</option>
                                    <option value="G">Green Linen</option>
                                    <option value="O">Other</option>
                                  </select>

                                  <label class="col-sm-3 col-form-label text-right"><?php echo $array['pack'][$language]; ?></label>
                                  <select class="form-control col-sm-4 checkblank numonly" id="numPack" onchange="CreateItemCode()">
                                    <option value="01">1 Psc</option>
                                    <option value="05">5 Pc</option>
                                    <option value="10">10 Pc</option>
                                    <option value="15">15 Pc</option>
                                    <option value="20">20 Pc</option>
                                    <option value="00">None</option>
                                  </select>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <!-- =================================================================== -->
                        <div class="row">

                          <div class="col-md-6">
                            <div class='form-group row'>
                              <label class="col-sm-4 col-form-label text-right"><?php echo $array['categorymain'][$language]; ?></label>
                              <select class="form-control col-sm-8" id="maincatagory2" onchange="getCatagory2()"></select>
                            </div>
                          </div>

                          <div class="col-md-6">
                            <div class='form-group row'>
                              <label class="col-sm-4 col-form-label text-right"><?php echo $array['category'][$language]; ?></label>
                              <select class="form-control col-sm-8" id="catagory2" onchange="CreateItemCode()"></select>
                            </div>
                          </div>

                        </div>

                        <!-- =================================================================== -->
                        <div class="row" id="xPrice">

                          <div class="col-md-6">
                            <div class='form-group row'>
                              <label class="col-sm-4 col-form-label text-right"><?php echo $array['pricecus'][$language]; ?></label>
                              <input type="text" class="form-control col-sm-8 numonly" id="CusPrice" placeholder="<?php echo $array['pricecus'][$language]; ?>">
                            </div>
                          </div>

                          <div class="col-md-6">
                            <div class='form-group row'>
                              <label class="col-sm-4 col-form-label text-right"><?php echo $array['pricefac'][$language]; ?></label>
                              <input type="text" class="form-control col-sm-8 numonly" id="FacPrice" placeholder="<?php echo $array['pricefac'][$language]; ?>">
                            </div>
                          </div>

                        </div>
                        <!-- =================================================================== -->
                        <div class="row">
                          <div class="col-md-6">
                            <div class='form-group row'>
                              <label class="col-sm-4 col-form-label text-right"><?php echo $array['item'][$language]; ?></label>
                              <input type="text" class="form-control col-sm-8 checkblank" id="ItemName" placeholder="<?php echo $array['item'][$language]; ?>">
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class='form-group row'>
                              <label class="col-sm-4 col-form-label text-right"><?php echo $array['weight'][$language]; ?></label>
                              <input type="text" class="form-control col-sm-8 checkblank numonly" id="Weight" placeholder="<?php echo $array['weight'][$language]; ?>">
                            </div>
                          </div>
                        </div>
                        <!-- =================================================================== -->
                        <div class="row">
                          <div class="col-md-6">
                            <div class='form-group row'>
                              <label class="col-sm-4 col-form-label text-right"><?php echo $array['unit'][$language]; ?></label>
                              <select class="form-control col-sm-8" id="UnitName"></select>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class='form-group row'>
                              <label class="col-sm-4 col-form-label text-right"><?php echo $array['size'][$language]; ?></label>
                              <select class="form-control col-sm-8 checkblank numonly" id="SizeCode">
                                <option value="1">SS</option>
                                <option value="2">S</option>
                                <option value="3">M</option>
                                <option value="4">L</option>
                                <option value="5">XL</option>
                                <option value="6">XXL</option>
                              </select>
                            </div>
                          </div>
                        </div>
                        <!-- =================================================================== -->
                      </div>
                    </div>
                  </div> <!-- tag column 1 -->

                </div>
              </div>

              <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <div class="row">
                  <div class="container-fluid mhee">
                    <div class="card-body" style="padding:0px; margin-top:10px;">
                      <div class="row">
                        <div style="margin-left:20px;width:60px;">
                          <label><?php echo $array['unit'][$language]; ?></label>
                        </div>
                        <div style="width:160px;">
                          <div style="font-size:24px;width:130px;">
                            <select class="form-control" style="font-size:24px;" id="Unitshows" disabled>
                            </select>
                          </div>
                        </div>
                        <div style="margin-left:20px;width:90px;">
                          <label><?php echo $array['secunit'][$language]; ?></label>
                        </div>
                        <div style="width:200px;">
                          <div style="font-size:24px;width:150px;">
                            <select class="form-control" style="font-size:24px;" id="subUnit">
                            </select>
                          </div>
                        </div>
                        <div style="margin-left:20px;width:60px;">
                          <label><?php echo $array['multiply'][$language]; ?></label>
                        </div>
                        <input type="text" class="form-control numonly" style="font-size:24px;width:59px;" name="mulinput" id="mulinput" placeholder="0.00">
                        <div style="margin-left:20px;width:100px;">
                          <label><?php echo $array['priceunit'][$language]; ?></label>
                        </div>
                        <input type="text" class="form-control numonly" style="font-size:24px;width:59px;" name="priceunit" id="priceunit" placeholder="0.00">
                        <!-- <button style="margin-left:11px;width:64px;" type="button" class="btn btn-success" onclick="AddUnit();"><?php echo $array['save'][$language]; ?></button>
                        <button style="margin-left:4px;width:64px;" type="button" class="btn btn-danger" onclick="DeleteUnit();"><?php echo $array['delete'][$language]; ?></button> -->
                        <!-- <div class="mhee"> -->
                        <a href="javascript:void(0)" onclick="AddUnit();" class="ml-3 "><img src="../img/icon/ic_save.png" style="width:34px;" class="mr-2"><?php echo $array['save'][$language]; ?></a>
                        <a href="javascript:void(0)" onclick="DeleteUnit();" class="ml-3 "><img src="../img/icon/ic_delete.png" style="width:36px;" class="mr-2"><?php echo $array['delete'][$language]; ?></a>
                        <!-- </div>  -->
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row" style="margin-top:5px;">
                  <table class="table table-fixed table-condensed table-striped" id="TableUnit" width="100%" cellspacing="0" role="grid">
                    <thead id="theadsum" style="font-size:18px;">
                      <tr role="row">
                        <th style='width: 5%;'>&nbsp;</th>
                        <th style='width: 5%;'><?php echo $array['no'][$language]; ?></th>
                        <th style='width: 30%;'><?php echo $array['item'][$language]; ?></th>
                        <th style='width: 12%;'><?php echo $array['unit'][$language]; ?></th>
                        <th align='center' style='width: 20%;'><?php echo $array['secunit'][$language]; ?></th>
                        <th align='center' style='width: 10%;'><?php echo $array['multiply'][$language]; ?></th>
                        <th align='center' style='width: 18%;'><?php echo $array['priceunit'][$language]; ?></th>
                      </tr>
                    </thead>
                    <tbody id="tbody" class="nicescrolled" style="font-size:11px;height:200px;">
                    </tbody>
                  </table>
                </div>
              </div>

            </div>
          </div>
        </div>

        <div class="col-md-2 mhee">
          <div class="sidenav" style=" margin-left: 0px;margin-top: 73px;">
            <div class="" style="margin-top:5px;">
              <div class="card-body" style="padding:0px; margin-top:10px;">
                <!-- =============================================================================================== -->
                <div class="row" style="margin-top:0px;" id="ActiveBNT">
                  <div class="col-md-3 icon">
                    <img src="../img/icon/i_active.png" style='width:36px;' class='mr-3'>
                  </div>
                  <div class="col-md-9">
                    <button class="btn" onclick="ActiveItem()" id="bActive">
                      <?php echo $array['activeItem'][$language]; ?>
                    </button>
                  </div>
                </div>

                <!-- =============================================================================================== -->
                <div class="row" style="margin-top:0px;" id="NewItem">
                  <div class="col-md-3 icon">
                    <img src="../img/icon/i_listnew.png" style='width:36px;' class='mr-3'>
                  </div>
                  <div class="col-md-9">
                    <button class="btn" onclick="NewItem()" id="bNewItem">
                      <?php echo $array['itemnew'][$language]; ?>
                    </button>
                  </div>
                </div>
                <!-- =============================================================================================== -->

                <div class="row" style="margin-top:0px;" id="AddItemBNT">
                  <div class="col-md-3 icon">
                    <img src="../img/icon/ic_save.png" style='width:36px;' class='mr-3'>
                  </div>
                  <div class="col-md-9">
                    <button class="btn" onclick="AddItem()" id="bSave">
                      <?php echo $array['save'][$language]; ?>
                    </button>
                  </div>
                </div>

                <!-- =============================================================================================== -->
                <div class="row" style="margin-top:0px;" id="BlankItemBNT">
                  <div class="col-md-3 icon">
                    <img src="../img/icon/i_clean.png" style='width:40px;' class='mr-3'>
                  </div>
                  <div class="col-md-9">
                    <button class="btn" onclick="Blankinput()" id="bDelete">
                      <?php echo $array['clear'][$language]; ?>
                    </button>
                  </div>
                </div>
                <!-- =============================================================================================== -->
                <div class="row" style="margin-top:0px;" id="CancelBNT">
                  <div class="col-md-3 icon">
                    <img src="../img/icon/ic_cancel.png" style='width:34px;' class='mr-3 opacity' id="delete_icon">
                  </div>
                  <div class="col-md-9">
                    <button class="btn" href='javascript:void(0)' onclick="CancelItem()" id="bCancel" disabled="true">
                      <?php echo $array['cancel'][$language]; ?>
                    </button>
                  </div>
                </div>
                <!-- =============================================================================================== -->
              </div>
            </div>
          </div>
          <!-- =============================================================================================== -->

        </div> <!-- end row tab -->


      </div>

      <!-- /#wrapper -->
      <!-- Scroll to Top Button-->
      <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
      </a>


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