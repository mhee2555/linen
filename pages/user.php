<?php
session_start();
$Userid = $_SESSION['Userid'];
$TimeOut = $_SESSION['TimeOut'];
if ($Userid == "") {
   header("location:../index.html");
}

$language = $_GET['lang'];
if ($language == "en") {
  $language = "en";
} else {
  $language = "th";
}

header('Content-type: text/html; charset=utf-8');
$xml = simplexml_load_file('../xml/general_lang.xml');
$json = json_encode($xml);
$array = json_decode($json, true);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>
        <?php echo $array['department'][$language]; ?>
    </title>

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

    <link href="../dist/css/sweetalert2.min.css" rel="stylesheet">
    <script src="../dist/js/sweetalert2.min.js"></script>
    <script src="../dist/js/jquery-3.3.1.min.js"></script>


    <link href="../datepicker/dist/css/datepicker.min.css" rel="stylesheet" type="text/css">
    <script src="../datepicker/dist/js/datepicker.min.js"></script>
    <!-- Include English language -->
    <script src="../datepicker/dist/js/i18n/datepicker.en.js"></script>

    <script type="text/javascript">
        var summary = [];

        $(document).ready(function(e) {
            ShowItem();
            //On create
            $('.TagImage').bind('click', {
                imgId: $(this).attr('id')
            }, function(evt) {
                alert(evt.imgId);
            });

            getHotpital();
            getEmployee();
            getPermission();
            getHotpital_user();
            $('#searchitem').keyup(function(e) {
                if (e.keyCode == 13) {
                    ShowItem();
                }
            });

            $('.numonly').on('input', function() {
                this.value = this.value.replace(/[^0-9.]/g, ''); //<-- replace all other than given set of values
            });
            $('.charonly').on('input', function() {
                this.value = this.value.replace(/[^a-zA-Zก-ฮๅภถุึคตจขชๆไำพะัีรนยบลฃฟหกดเ้่าสวงผปแอิืทมใฝ๑๒๓๔ู฿๕๖๗๘๙๐ฎฑธํ๊ณฯญฐฅฤฆฏโฌ็๋ษศซฉฮฺ์ฒฬฦ. ]/g, ''); //<-- replace all other than given set of values
            });

        }).mousemove(function(e) { parent.last_move = new Date();;
        }).keyup(function(e) { parent.last_move = new Date();;
        });

        dialog = jqui("#dialog").dialog({
            autoOpen: false,
            height: 650,
            width: 1200,
            modal: true,
            buttons: {
                "<?php echo $array['close'][$language]; ?>": function() {
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

        function getEmployee(){
          var data2 = {
              'STATUS': 'getEmployee'
          };
          // console.log(JSON.stringify(data2));
          senddata(JSON.stringify(data2));
        }

        function getHotpital(){
          var data2 = {
              'STATUS': 'getHotpital'
          };
          // console.log(JSON.stringify(data2));
          senddata(JSON.stringify(data2));
        }

        function getHotpital_user(){
          var data2 = {
              'STATUS': 'getHotpital_user'
          };
          // console.log(JSON.stringify(data2));
          senddata(JSON.stringify(data2));
        }


        function getPermission(){
          var data2 = {
              'STATUS': 'getPermission'
          };
          // console.log(JSON.stringify(data2));
          senddata(JSON.stringify(data2));
        }

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
            var dept = '<?php echo $_SESSION['Deptid ']; ?>';
            var data = {
                'STATUS': 'getDocDetail',
                'HptCode': HptCode,
                'DocNo': DepCode
            };
            // console.log(JSON.stringify(data));
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
            var dept = '<?php echo $_SESSION['
            Deptid ']; ?>';

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

            // console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
        }

        function ShowItem() {
            var HptCode = $('#hptsel').val();
            var keyword = $('#searchitem').val();
            var data = {
                'STATUS': 'ShowItem',
                'HptCode': HptCode,
                'Keyword': keyword
            };
            senddata(JSON.stringify(data));
        }

        function AddItem() {
            var UsID = $('#UsID').val();
            var UserName = $('#username').val();
            var Password = $('#Password').val();
            var FName = $('#flname').val();
            var host = $('#host').val();
            var Permission = $('#Permission').val();

            var data = {
                'STATUS': 'AddItem',
                'UsID': UsID,
                'UserName': UserName,
                'Password': Password,
                'FName': FName,
                'host': host,
                'Permission' : Permission
            };

            senddata(JSON.stringify(data));
        }

        function CancelItem() {
            var UsID = $('#UsID').val();

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
                var data = {
                    'STATUS': 'CancelItem',
                    'UsID': UsID
                }
                // console.log(JSON.stringify(data));
                senddata(JSON.stringify(data));
            })
        }

        function Blankinput() {
            $('#username').val("");
            $('#Password').val("");
            $('#flname').val("");
            $('#host tbody').empty();
            $('#Permission tbody').empty();
            $('#UsID').empty();

            getHotpital();
            getEmployee();
            getPermission();
            ShowItem();
        }

        function getdetail(ID) {
            if (ID != "" && ID != undefined) {
                var data = {
                    'STATUS': 'getdetail',
                    'ID': ID
                };
                // console.log(JSON.stringify(data));
                senddata(JSON.stringify(data));
            }
        }


        function senddata(data) {
            var form_data = new FormData();
            form_data.append("DATA", data);
            var URL = '../process/user.php';
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
                        title: '<?php echo $array['
                        pleasewait '][$language]; ?>',
                        text: '<?php echo $array['
                        processing '][$language]; ?>',
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
                        if ((temp["form"] == 'ShowItem')) {
                            $("#TableItem tbody").empty();
                            console.log(temp);
                            for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                                var rowCount = $('#TableItem >tbody >tr').length;
                                var chkDoc = "<input type='radio' name='checkitem' id='checkitem' value='" + temp[i]['ID'] + "' onclick='getdetail(\"" + temp[i]["ID"] + "\")'>";
                                // var Qty = "<div class='row' style='margin-left:5px;'><button class='btn btn-danger' style='width:35px;' onclick='subtractnum(\""+i+"\")'>-</button><input class='form-control' style='width:50px; margin-left:3px; margin-right:3px; text-align:center;' id='qty"+i+"' value='0' disabled><button class='btn btn-success' style='width:35px;' onclick='addnum(\""+i+"\")'>+</button></div>";
                                StrTR = "<tr id='tr" + temp[i]['DepCode'] + "'>" +
                                    "<td style='width: 5%;'>" + chkDoc + "</td>" +
                                    "<td style='width: 10%;'>" + (i + 1) + "</td>" +
                                    "<td style='width: 29%;'>" + temp[i]['FName'] + "</td>" +
                                    "<td style='width: 15%;'>" + temp[i]['UserName'] + "</td>" +
                                    "<td style='width: 22%;'>" + temp[i]['Password'] + "</td>" +
									"<td style='width: 3%;'>" + temp[i]['Permission'] + "</td>" +
                                    "</tr>";

                                if (rowCount == 0) {
                                    $("#TableItem tbody").append(StrTR);
                                } else {
                                    $('#TableItem tbody:last-child').append(StrTR);
                                }
                            }
                        } else if ((temp["form"] == 'getdetail')) {
                            if ((Object.keys(temp).length - 2) > 0) {
                                $('#UsID').val(temp['ID']);
                                $('#username').val(temp['UserName']);
                                $('#Password').val(temp['Password']);
                                $('#flname').val(temp['FName']);

                                var StrTr="";
                                $("#host").empty();
                                for (var i = 0; i < temp['EmpCnt']; i++) {
                                    if(temp['hos'+i]['xHptCode']==temp['HptCode']){
                                        StrTr = "<option selected value = '" + temp['hos'+i]['xHptCode'] + "'> " + temp['hos'+i]['xHptName'] + " </option>";
                                    }else{
                                        StrTr = "<option value = '" + temp['hos'+i]['xHptCode'] + "'> " + temp['hos'+i]['xHptName'] + " </option>";
                                    }
                                    $("#host").append(StrTr);
                                }

                                StrTr="";
                                $("#Permission").empty();
                                for (var i = 0; i < temp['PmCnt']; i++) {
                                    if(temp['Pm'+i]['xPmID']==temp['PmID']){
                                        StrTr = "<option selected value = '" + temp['Pm'+i]['xPmID'] + "'> " + temp['Pm'+i]['xPermission'] + " </option>";
                                    }else{
                                        StrTr = "<option value = '" + temp['Pm'+i]['xPmID'] + "'> " + temp['Pm'+i]['xPermission'] + " </option>";
                                    }
                                    $("#Permission").append(StrTr);
                                }

                            }
                        } else if ((temp["form"] == 'AddItem')) {
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

                                $('#DepCode').val("");
                                $('#hptsel2').val("1");
                                ShowItem();
                            })
                        } else if ((temp["form"] == 'EditItem')) {
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

                                $('#DepCode').val("");
                                $('#hptsel2').val("1");
                                ShowItem();
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
                                Blankinput();
                                ShowItem();
                            })
                        } else if ((temp["form"] == 'getHotpital')) {
                            $("#host").empty();
                            for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                                var StrTr = "<option value = '" + temp[i]['HptCode'] + "'> " + temp[i]['HptName'] + " </option>";
                                $("#host").append(StrTr);
                            }
                        }else if ((temp["form"] == 'getHotpital_user')) {
                            $("#host").empty();
                            for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                                var StrTr = "<option value = '" + temp[i]['HptCode'] + "'> " + temp[i]['HptName'] + " </option>";
                                $("#host").append(StrTr);
                            }
                        } else if ((temp["form"] == 'getEmployee')) {
                            $("#EmpCode").empty();
                            for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                                var StrTr = "<option value = '" + temp[i]['EmpCode'] + "'> " + temp[i]['xName'] + " </option>";
                                $("#EmpCode").append(StrTr);
                            }
                        } else if ((temp["form"] == 'getPermission')) {
                            $("#Permission").empty();
                            for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                                var StrTr = "<option value = '" + temp[i]['PmID'] + "'> " + temp[i]['Permission'] + " </option>";
                                $("#Permission").append(StrTr);
                            }
                        }
                    } else if (temp['status'] == "failed") {
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

                    } else if (temp['status'] == "notfound") {
                        // swal({
                        //   title: '',
                        //   text: temp['msg'],
                        //   type: 'info',
                        //   showCancelButton: false,
                        //   confirmButtonColor: '#3085d6',
                        //   cancelButtonColor: '#d33',
                        //   showConfirmButton: false,
                        //   timer: 2000,
                        //   confirmButtonText: 'Ok'
                        // })
                        $("#TableItem tbody").empty();
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
          body{
            font-family: myFirstFont;
                  font-size:22px;
          }

        .nfont{
          font-family: myFirstFont;
          font-size:22px;
        }
    input,select{
      font-size:24px!important;
    }
    th,td{
      font-size:24px!important;
    }
    .table > thead > tr >th {
      background: #4f88e3!important;
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
    button{
      font-size: 24px!important;
    }
      a.nav-link{
        width:auto!important;
      }
      .datepicker{z-index:9999 !important}
      .hidden{visibility: hidden;}
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        <!-- content-wrapper -->
        <div id="content-wrapper">
            <!--
          <div class="mycheckbox">
            <input type="checkbox" name='useful' id='useful' onclick='setTag()'/><label for='useful' style='color:#FFFFFF'> </label>
          </div>
-->

            <div class="row">
                <div class="col-md-12">
                    <!-- tag column 1 -->
                    <div class="container-fluid">
                        <div class="card-body" style="padding:0px; margin-top:-12px;">
                            <div class="row">


                                <div class="col-md-6">
                                    <div class="row" style="margin-left:5px;">
                                        <input type="text" class="form-control" style="width:70%;" name="searchitem" id="searchitem" placeholder="<?php echo $array['searchplace'][$language]; ?>">
                                        <button type="button" style="margin-left:10px;" class="btn btn-primary" name="button" onclick="ShowItem();">
                                            <?php echo $array['search'][$language]; ?></button>
                                    </div>
                                </div>
                                <div class="col-md-2">

                                </div>

                                <div class="col-md-3">
                                    <div class="row" style="margin-left:5px;">
                                        <select class="form-control" id="hptsel" style="visibility:hidden;">

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="TableItem" width="100%" cellspacing="0" role="grid">
                                <thead id="theadsum" style="font-size:11px;">
                                    <tr role="row">
                                        <th style='width: 5%;'>&nbsp;</th>
                                        <th style='width: 10%;'>
                                            <?php echo $array['no'][$language]; ?>
                                        </th>
                                        <th style='width: 29%;'>
                                            <?php echo $array['flname'][$language]; ?>
                                        </th>
                                        <th style='width: 16%;'>
                                            <?php echo $array['username'][$language]; ?>
                                        </th>
                                        <th style='width: 20%;'>
                                            <?php echo $array['password'][$language]; ?>
                                        </th>
                                        <th style='width: 20%;'>
                                            <?php echo $array['permission'][$language]; ?>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="tbody" class="nicescrolled" style="font-size:11px;height:250px;">
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div> <!-- tag column 1 -->
            </div>
            <!-- /.content-wrapper -->
            <div class="row">
                <div class="col-md-8">
                    <!-- tag column 1 -->
                    <div class="container-fluid">
                        <div class="card-body" style="padding:0px; margin-top:10px;">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
                                        <?php echo $array['detail'][$language]; ?></a>
                                </li>
                            </ul>

                            <div class="row" style="margin-top:10px;">
                                <div class="col-md-2">
                                    <div class="row" style="margin-left:30px;">

                                    </div>
                                </div>
                                <div class="col-md-6" style="margin-left:15px;">
                                    <div class="row">
                                        <input type="hidden" class="form-control" style="width:90%;" name="UsID" id="UsID">
                                    </div>
                                </div>
                            </div>

                            <div class="row" style="margin-top:10px;">
                                <div class="col-md-2">
                                    <div class="row" style="margin-left:30px;">
                                        <label>
                                            <?php echo $array['side'][$language]; ?></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-control" id="host">
                                    </select>
                                </div>
                            </div>

                            <div class="row" style="margin-top:10px;">
                                <div class="col-md-2">
                                    <div class="row" style="margin-left:30px;">
                                        <label>
                                            <?php echo $array['flname'][$language]; ?></label>
                                    </div>
                                </div>
                                <div class="col-md-6" style="margin-left:15px;">
                                    <div class="row">
                                        <input type="text" class="form-control checkblank " style="width:90%;" name="Password" id="flname" placeholder="<?php echo $array['flname'][$language]; ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row" style="margin-top:10px;">
                                <div class="col-md-2">
                                    <div class="row" style="margin-left:30px;">
                                        <label>
                                            <?php echo $array['username'][$language]; ?></label>
                                    </div>
                                </div>
                                <div class="col-md-6" style="margin-left:15px;">
                                    <div class="row">
                                        <input type="text" class="form-control checkblank " style="width:90%;" name="Password" id="username" placeholder="<?php echo $array['username'][$language]; ?>">
                                    </div>
                                </div>
                            </div>


                            <div class="row" style="margin-top:10px;">
                                <div class="col-md-2">
                                    <div class="row" style="margin-left:30px;">
                                        <label>
                                            <?php echo $array['password'][$language]; ?></label>
                                    </div>
                                </div>
                                <div class="col-md-6" style="margin-left:15px;">
                                    <div class="row">
                                        <input type="text" class="form-control checkblank " style="width:90%;" name="Password" id="Password" placeholder="<?php echo $array['password'][$language]; ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row" style="margin-top:10px;">
                                <div class="col-md-2">
                                    <div class="row" style="margin-left:30px;">
                                        <label>
                                            <?php echo $array['permission'][$language]; ?></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-control" id="Permission">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- tag column 2 -->
                <div class="col-md-4">
                    <!-- tag column 1 -->
                    <div class="container-fluid">
                        <div class="card-body" style="padding:0px; margin-top:50px;">
                            <div class="row" style="margin-top:5px;">
                                <div class="col-md-4">
                                    <div class="row" style="margin-left:5px;">
                                        <div class="row" style="margin-left:30px;">
                                            <button style="width:150px" ; type="button" class="btn btn-success" onclick="AddItem()">
                                                <?php echo $array['save'][$language]; ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top:5px;">
                                <div class="col-md-4">
                                    <div class="row" style="margin-left:5px;">
                                        <div class="row" style="margin-left:30px;">
                                            <button style="width:150px" ; type="button" class="btn btn-info" onclick="Blankinput()">
                                                <?php echo $array['clear'][$language]; ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top:5px;">
                                <div class="col-md-4">
                                    <div class="row" style="margin-left:5px;">
                                        <div class="row" style="margin-left:30px;">
                                            <button style="width:150px" ; type="button" class="btn btn-danger" onclick="CancelItem()">
                                                <?php echo $array['cancel'][$language]; ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- tag column 1 -->
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
