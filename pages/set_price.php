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

    <!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
    <script src="../jQuery-ui/jquery-1.12.4.js"></script>
    <script src="../jQuery-ui/jquery-ui.js"></script>
    <link href="../css/responsive.css" rel="stylesheet">

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
        var last_move, cur_date, target;
        var redirectInSecond=<?=$TimeOut?>; // กำหนดเวลา redirect เป็นวินาที
        var redirect_url = 'http://localhost:8181/linen/index.html'; // กำหนด url ที่ต้องการเมื่อครบเวลาที่กำหนด
            $(document).ready(function(e) {
            parent.afk();
            parent.chk_last_move();
        }).mousemove(function(e) { last_move = parent.afk();
        }).keyup(function(e) { last_move = parent.afk();
        });

        $(document).ready(function($) {
            //On create
            $('.TagImage').bind('click', {
                imgId: $(this).attr('id')
            }, function(evt) {
                alert(evt.imgId);
            });
            $('#rem').hide();
            getHotpital();
            getCategoryMain();
            getCategorySub(1);
            var HptCode = $('#hptsel').val();
            var data = {
                'STATUS': 'ShowItem1',
                'HptCode': HptCode
            };

          // $('#datepicker').val(twoDigit(d.getDate())+"/"+(twoDigit(d.getMonth()+1))+"/"+d.getFullYear());

            console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
            // }

            $('#searchitem').keyup(function(e) {
                if (e.keyCode == 13) {
                    ShowItem1();
                }
            });

            $('.editable').click(function() {
                alert('hi');
            });

            $('.numonly').on('input', function() {
                this.value = this.value.replace(/[^0-9.]/g, ''); //<-- replace all other than given set of values
            });
            $('.charonly').on('input', function() {
                this.value = this.value.replace(/[^a-zA-Zก-ฮๅภถุึคตจขชๆไำพะัีรนยบลฃฟหกดเ้่าสวงผปแอิืทมใฝ๑๒๓๔ู฿๕๖๗๘๙๐ฎฑธํ๊ณฯญฐฅฤฆฏโฌ็๋ษศซฉฮฺ์ฒฬฦ. ]/g, ''); //<-- replace all other than given set of values
            });

        });

      function getHotpital() {
              var data2 = {
                  'STATUS': 'getHotpital'
              };
              // console.log(JSON.stringify(data2));
              senddata(JSON.stringify(data2));
      }

      function getCategoryMain() {
              var data2 = {
                  'STATUS': 'getCategoryMain'
              };
              // console.log(JSON.stringify(data2));
              senddata(JSON.stringify(data2));
      }

      function getCategorySub(Sel) {
        var CgrID;
        if(Sel==1)
          CgrID = $('#Category_Main').val();
        else
          CgrID = $('#Category_Main1').val();

        if( CgrID == null ) CgrID = 1;
              var data2 = {
                  'STATUS': 'getCategorySub',
                  'CgrID' : CgrID
              };
              // console.log(JSON.stringify(data2));
              senddata(JSON.stringify(data2));
      }

      function getItemPrice() {
          var data = {
              'STATUS': 'ShowItemPrice'
          };

            console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
      }

        jqui(document).ready(function($){

            // dialog = jqui( "#dialog" ).dialog({
            //     autoOpen: false,
            //     height: 650,
            //     width: 1200,
            //     modal: true,
            //     buttons: {
            //         "<?php echo $array['close'][$language]; ?>": function() {
            //             dialog.dialog( "close" );
            //         }
            //     },
            //     close: function() {
            //         console.log("close");
            //         $("#docno").val("");
            //     }
            // });

            jqui( "#dialogItem" ).button().on( "click", function() {
                // dialog.dialog( "open" );
                $('#dialog').modal('show');
            });

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
            var dept = '<?php echo $_SESSION['Deptid ']; ?>';
            var data = {
                'STATUS': 'getDocDetail',
                'HptCode': HptCode,
                'DocNo': DepCode
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
            var dept = '<?php echo $_SESSION['Deptid ']; ?>';

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

        function onCreate() {
            var xDate = $('#datepicker').val();
            var HptCode = $("#hptsel1").val();

            if(xDate==""){
                $('#rem').show(5).css("color","red");
            }else{
                $('#rem').hide();
                /* we join the array separated by the comma */
                xDate = xDate.substr(6,4)+"-"+xDate.substr(3,2)+"-"+xDate.substr(0,2);

                var data = {
                    'STATUS' : 'CreateDoc',
                    'Price' : Price,
                    'HptCode' : HptCode,
                    'xDate' : xDate
                };
                // console.log(JSON.stringify(data));
                senddata(JSON.stringify(data));
            }
        }

        function ShowDoc() {
            $('#show_btn').attr('disabled', true);
            var HptCode = $('#hptsel2').val();
            var Keyword = $('#search2').val();
            var data = {
                'STATUS': 'ShowDoc',
                'HptCode': HptCode,
                'Keyword': Keyword
            };

            // console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
        }

        function ShowItem1(Sel) {
            var HptCode = $('#hptsel').val();
            var CgMainID = $('#Category_Main').val();
            var CgSubID = $('#Category_Sub').val();
            var chk;
            // alert(CgSubID);
            if (Sel == 1) {
                CgMainID = "-";
                CgSubID = "-";
                chk = 1;
            } else if (Sel == 2){
                CgSubID = "-";
                chk = 2;
            }else if(Sel == 3){
                chk = 3;
            }

            var data = {
                'STATUS': 'ShowItem1',
                'HptCode': HptCode,
                'CgMainID': CgMainID,
                'CgSubID': CgSubID,
                'chk' : chk
            };

            console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
        }

        function ShowItem2() {
            var DocNo = $('#docno').val();
            var HptCode = $('#hptsel1').val();
            var Keyword = $('#search1').val();

            var data = {
                'STATUS': 'ShowItem2',
                'DocNo' : DocNo,
                'HptCode': HptCode,
                'Keyword': Keyword
            };

            console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
        }

        function SavePrice(Sel) {
            var RowID = $('#RowID').val();
            var Price = $('#Price').val();
            var DocNo = $('#docno').val();
            // alert('RowID = ' + RowID);
            // alert('Price = ' + Price);

            var data = {
                    'STATUS': 'SavePrice',
                    'RowID': RowID,
                    'Price': Price,
                    'Sel' : Sel,
                    'DocNo' : DocNo
                };
                console.log(JSON.stringify(data));
                senddata(JSON.stringify(data));
        }

        function UpdatePrice(chk) {
            var DocNo = $('#docno').val();
            var NumRow = $('#NumRow').val();
            var Price = [];
            var chk = chk;
            // alert(chk);
            for (var i = 0; i < NumRow; i++) {
                Price[i] = $("#price_"+i).val();
            }
            // alert(Price);
            // var price = array();
            var data = {
                'STATUS': 'UpdatePrice',
                'DocNo': DocNo,
                'Price':Price,
                'chk':chk
            };
            console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
        }

        function Blankinput() {
            $('.checkdocno').each(function() {
                $(this).val("");
            });
            $('#hptsel2').val("1");
            // ShowItem();
        }
        function ClearChecked() {
            $('.checkblank').each(function() {
                $(".checkblank").prop("checked", false);
            });
            $('#show_btn').attr('disabled', true);
        }
        function cancelDoc(DocNo,row){
            $('.btn_cancel').each(function() {
                $(".btn_cancel").attr("disabled", true);
            });
            var DocNo = DocNo;
            var row = row;
            $('#cancel').val(DocNo);
            $('#show_btn').attr('disabled', false);
            $('#cancel_btn'+row+'').attr('disabled', false);
        }
        function ButtoncancelDoc(){
            var DocNo = $('#cancel').val();
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
                    'STATUS' : 'CancelItem',
                    'DocNo' : DocNo
                }
                console.log(JSON.stringify(data));
                senddata(JSON.stringify(data));
            })

        }

        function getdetail(RowID) {

            if (RowID != "" && RowID != undefined) {
                var data = {
                    'STATUS': 'getdetail',
                    'RowID': RowID
                };

                // console.log(JSON.stringify(data));
                senddata(JSON.stringify(data));
            }
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

        function OpenDialog(Sel){
            var selectdocument = "";

            if(Sel==1) {
                $("#checkdocno:checked").each(function () {
                    selectdocument = $(this).val();
                });
            }

            $("#docno").val("");
            $("#datepicker").val("");

            if(selectdocument!=""){
                var aData = selectdocument.split(",");
                $("#docno").val(aData[0]);
                $("#datepicker").val(aData[1]);
                $("#create1").hide();

                $("#hptsel1").empty();
                var StrTr = "<option selected value = '" + aData[2] + "'> " + aData[3] + " </option>";
                $("#hptsel1").append(StrTr);
                ShowItem2();
            }else{
                getHotpital();
                $("#create1").show();
            }
            $("#search1").hide();
            $("#TableItemPrice tbody").empty();

            // dialog.dialog( "open" );
            $('#dialog').modal('show');
        }

        function logoff() {
            swal({
                title: '',
                text: '<?php echo $array['
                logout '][$language]; ?>',
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
            var URL = '../process/set_price.php';
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
                    // console.log(result); <!-- =================== chk error ================ -->
                    // alert(temp['form']);
                    if (temp["status"] == 'success') {
                        if ((temp["form"] == 'CreateDoc')) {
                            $("#docno").val( temp["DocNo"] );
                            $("#create1").hide(500);
                            ShowItem2();
                        }else if ((temp["form"] == 'UpdatePrice')) {
                            var sv = "<?php echo $array['save'][$language]; ?>";
                            var svs = "<?php echo $array['savesuccess'][$language]; ?>";
                            swal({
                                title: sv,
                                text: svs,
                                type: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                showConfirmButton: false,
                                timer: 1000,
                                confirmButtonText: 'Ok'
                            }).catch(function(timeout) { }); //important --> Error Uncaught (in promise) timer
                            setTimeout(function () {
                                // dialog.dialog( "close" );
                                $('#dialog').modal('toggle');
                                ShowDoc();
                            }, 1500);
                        }else if ((temp["form"] == 'ShowDoc')) {
                            $("#TableDoc tbody").empty();
                            for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                                var rowCount = $('#TableDoc >tbody >tr').length;
                                var chkDoc = "<input type='radio' class='checkblank' data-value='"+i+"' name='checkdocno' id='checkdocno' " +
                                    "value='" + temp[i]['DocNo'] + "," + temp[i]['xDate'] + "," + temp[i]['HptCode'] + "," + temp[i]['HptName'] + "' onclick='cancelDoc(\"" + temp[i]["DocNo"] + "\","+i+")'>";
                                    StrTR = "<tr id='tr"+temp[i]['DocNo']+"'>" +
                                        "<td style='width: 5%;'>" + chkDoc + "</td>" +
                                        "<td style='width: 25%;'>" + temp[i]['HptName'] + "</td>" +
                                        "<td style='width: 26%;'>" + temp[i]['DocNo'] + "</td>" +
                                        "<td style='width: 25%;'>" + temp[i]['xDate'] + "</td>" +
                                        "<td style='width: 19%;'><button class='btn btn_cancel' style='background: none;' onclick='ButtoncancelDoc();' id='cancel_btn"+i+"' disabled='true'><i class='fas fa-trash'></i></button></td>" +
                                        "</tr>";
                                    if (rowCount == 0) {
                                        $("#TableDoc tbody").append(StrTR);
                                    } else {
                                        $('#TableDoc tbody:last-child').append(StrTR);
                                    }

                            }
                        }else if ((temp["form"] == 'ShowItem1')) {
                            $("#TableItem tbody").empty();
                            // console.log(temp);
                            for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                                var rowCount = $('#TableItem >tbody >tr').length;
                                var chkDoc = "<input type='radio' name='checkitem' id='checkitem' value='" + temp[i]['RowID'] + "' onclick='getdetail(\"" + temp[i]["RowID"] + "\")'>";
                                var Price = "<div class='row' style='margin-left:2px;'><input class='form-control' style='height:40px;width:150px; margin-left:3px; margin-right:3px; text-align:center;' id='price_"+i+"' value='"+temp[i]['Price']+"' OnBlur='updateWeight(\""+i+"\",\""+temp[i]['RowID']+"\")'></div>";

                                StrTR = "<tr id='tr" + temp[i]['RowID'] + "'>" +
                                "<td style='width: 5%;'>" + chkDoc + "</td>" +
                                "<td style='width: 25%;'>" + temp[i]['HptName'] + "</td>" +
                                "<td style='width: 26%;'>" + temp[i]['MainCategoryName'] + "</td>" +
                                "<td style='width: 25%;'>" + temp[i]['CategoryName'] + "</td>" +
                                "<td style='width: 19%;'>" + temp[i]['Price'] + " </td>" +
                                "</tr>";

                                if (rowCount == 0) {
                                    $("#TableItem tbody").append(StrTR);
                                } else {
                                    $('#TableItem tbody:last-child').append(StrTR);
                                }
                            }
                        }else if ((temp["form"] == 'ShowItem2')) {
                            $("#TableItemPrice tbody").empty();
                            for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                                var rowCount = $('#TableItem >tbody >tr').length;
                                var RowID = "<input type='hidden' name='RowID_"+i+"' id='RowID_"+i+"' value='" + temp[i]['RowID'] +"'>";
                                var Price = "<div class='row' style='margin-left:2px;'><input class='form-control' style='height:40px;width:150px; margin-left:3px; margin-right:3px; text-align:center;' id='price_"+i+"' value='"+temp[i]['Price']+"' onKeyPress='if(event.keyCode==13){SavePrice("+i+")}'></div>";

                                StrTR = "<tr id='tr" + RowID + "'>" +
                                    "<td style='width: 5%;'>"+ RowID +"</td>" +
                                    "<td style='width: 25%;'>" + temp[i]['HptName'] + "</td>" +
                                    "<td style='width: 26%;'>" + temp[i]['MainCategoryName'] + "</td>" +
                                    "<td style='width: 25%;'>" + temp[i]['CategoryName'] + "</td>" +
                                    "<td style='width: 19%;'>" + Price + " </td>" +
                                    "</tr>";

                                if (rowCount == 0) {
                                    $("#TableItemPrice tbody").append(StrTR);
                                } else {
                                    $('#TableItemPrice tbody:last-child').append(StrTR);
                                }
                            }
                            var NumRow = i;
                            $('#NumRow').val(NumRow);
                            $("#hptsel1").empty();
                            for (var i = 0; i < 1; i++) {
                                var StrTr = "<option value = '" + temp[i]['HptCode'] + "'> " + temp[i]['HptName'] + " </option>";
                                $("#hptsel1").append(StrTr);
                            }

                        } else if ((temp["form"] == 'getdetail')) {
                            if ((Object.keys(temp).length - 2) > 0) {
                                $('#RowID').val(temp['RowID']);
                                $('#HotName').val(temp['HptName']);
                                $('#MainCategoryName').val(temp['MainCategoryName']);
                                $('#CategoryName').val(temp['CategoryName']);
                                $('#Price').val(temp['Price']);

                            }
                        } else if ((temp["form"] == 'SavePrice')) {
                            $('#RowID').val("");
                            $('#HotName').val("");
                            $('#CategoryMain').val("");
                            $('#CategorySub').val(temp['CategoryName']);
                            $('#Price').val(temp['Price']);
                            var rowCount = $('#TableDoc >tbody >tr').length;
                            var Sel = temp["Sel"];
                            var cn = temp["Cnt"];
                            var sv = "<?php echo $array['save'][$language]; ?>";
                            var svs = "<?php echo $array['savesuccess'][$language]; ?>";

                            if((Sel+1)==6)
                                $('#price_0').focus().select();
                            else
                                $('#price_'+(Sel+1)).focus().select();

                            swal({
                                title: sv,
                                text: svs,
                                type: 'success',
                                showCancelButton: false,
                                // confirmButtonColor: '#3085d6',
                                // cancelButtonColor: '#d33',
                                showConfirmButton: false,
                                confirmButtonText: 'Ok',
                                timer: 1000
                            }).catch(function(timeout) { }); //important --> Error Uncaught (in promise) timer
                            setTimeout(function () {
                                ShowItem1();
                            }, 1000);
                        } else if ((temp["form"] == 'getHotpital')) {
                            $("#hptsel").empty();
                            $("#hptsel1").empty();
                            $("#hptsel2").empty();
                            for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                                var StrTr = "<option value = '" + temp[i]['HptCode'] + "'> " + temp[i]['HptName'] + " </option>";
                                $("#hptsel").append(StrTr);
                                $("#hptsel1").append(StrTr);
                                $("#hptsel2").append(StrTr);
                            }
                        } else if ((temp["form"] == 'getCategoryMain')) {
                          $("#Category_Main").empty();
                          $("#Category_Main1").empty();
                            for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                                var StrTr = "<option value = '" + temp[i]['MainCategoryCode'] + "' > " + temp[i]['MainCategoryName'] + " </option>";
                                $("#Category_Main").append(StrTr);
                                $("#Category_Main1").append(StrTr);
                            }
                        } else if ((temp["form"] == 'getCategorySub')) {
                          $("#Category_Sub").empty();
                          $("#Category_Sub1").empty();
                              for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                                  var StrTr = "<option value = '" + temp[i]['CategoryCode'] + "'> " + temp[i]['CategoryName'] + " </option>";
                                  $("#Category_Sub").append(StrTr);
                                  $("#Category_Sub1").append(StrTr);
                              }
                        }else if ((temp["form"] == 'CancelItem')) {
                            var sv = "<?php echo $array['save'][$language]; ?>";
                            var svs = "<?php echo $array['savesuccess'][$language]; ?>";
                            swal({
                                title: sv,
                                text: svs,
                                type: 'success',
                                showCancelButton: false,
                                showConfirmButton: false,
                                confirmButtonText: 'Ok',
                                timer: 1000
                            }).catch(function (timeout) {
                            }); //important --> Error Uncaught (in promise) timer
                            setTimeout(function () {
                                ShowDoc();
                            }, 1000);
                        }
                    }else if (temp['status'] == "failed") {
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
        body{
		   font-family: 'THSarabunNew';
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
    <input type="hidden" id="NumRow">
    <input type="hidden" id="cancel">

    <div id="wrapper">
        <!-- content-wrapper -->
        <div id="content-wrapper">
            <div class="container-fluid">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><?php echo $array['setprice'][$language]; ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><?php echo $array['search'][$language]; ?></a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="row">
                            <div class="col-md-12">
                                <!-- tag column 1 -->
                                <div class="container-fluid">
                                    <div class="card-body" style="padding:0px; margin-top:12px;">
                                        <div class="row">
                                          <div class="col-md-2">
                                              <div class="row" style="margin-left:5px;">
                                                  <select class="form-control" id="hptsel"></select>
                                              </div>
                                          </div>
                                          <div class="col-md-2">
                                              <div class="row" style="margin-left:5px;">
                                                  <select class="form-control" id="Category_Main" onchange="getCategorySub(1);"></select>
                                              </div>
                                          </div>
                                          <div class="col-md-2">
                                              <div class="row" style="margin-left:5px;">
                                                  <select class="form-control" id="Category_Sub"></select>
                                              </div>
                                          </div>
                                          <div class="col-md-5">
                                                <div class="row" style="margin-left:5px;">
                                                    <button type="button" style="margin-left:10px;" class="btn btn-primary" name="button" onclick="ShowItem1(1);">
                                                        <?php echo $array['search_hp'][$language]; ?></button>
                                                    <button type="button" style="margin-left:10px;" class="btn btn-primary" name="button" onclick="ShowItem1(2);">
                                                        <?php echo $array['search_ct_main'][$language]; ?></button>
                                                    <button type="button" style="margin-left:10px;" class="btn btn-primary" name="button" onclick="ShowItem1(3);">
                                                        <?php echo $array['search_ct_sub'][$language]; ?></button>
                                                </div>
                                            </div>
                                            <div class="col-md-2">

                                            </div>
                                        </div>
                                        <table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="TableItem" width="100%" cellspacing="0" role="grid">
                                            <thead id="theadsum" style="font-size:11px;">
                                                <tr role="row">
                                                    <th style='width: 5%;'>&nbsp;</th>
                                                    <th style='width: 25%;'>
                                                        <?php echo $array['side'][$language]; ?>
                                                    </th>
                                                    <th style='width: 25%;'>
                                                        <?php echo $array['categorymain'][$language]; ?>
                                                    </th>
                                                    <th style='width: 25%;'>
                                                        <?php echo $array['categorysub'][$language]; ?>
                                                    </th>
                                                    <th style='width: 20%;'>
                                                        <?php echo $array['price'][$language]; ?>
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
                                            <div class="col-md-6" style="margin-left:15px;">
                                                <div class="row">
                                                    <input type="hidden" class="form-control" style="width:90%;" name="RowID" id="RowID" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- =================================================================== -->
                                        <div class="row mt-4">
                                            <div class="col-md-7">
                                                <div class='form-group row'>
                                                <label class="col-sm-4 col-form-label text-right"><?php echo $array['side'][$language]; ?></label>
                                                <input type="text"  class="form-control col-sm-8 " id="HotName"    placeholder="<?php echo $array['side'][$language]; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- =================================================================== -->
                                        <div class="row">
                                            <div class="col-md-7">
                                                <div class='form-group row'>
                                                <label class="col-sm-4 col-form-label text-right"><?php echo $array['categorymain'][$language]; ?></label>
                                                <input type="text"  class="form-control col-sm-8 " id="Category_Main2"    placeholder="<?php echo $array['categorymain'][$language]; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- =================================================================== -->
                                        <div class="row">
                                            <div class="col-md-7">
                                                <div class='form-group row'>
                                                <label class="col-sm-4 col-form-label text-right"><?php echo $array['categorysub'][$language]; ?></label>
                                                <input type="text"  class="form-control col-sm-8 " id="Category_Sub2"    placeholder="<?php echo $array['categorysub'][$language]; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- =================================================================== -->
                                        <div class="row">
                                            <div class="col-md-7">
                                                <div class='form-group row'>
                                                <label class="col-sm-4 col-form-label text-right"><?php echo $array['price'][$language]; ?></label>
                                                <input type="text"  class="form-control col-sm-8 " id="Price"    placeholder="<?php echo $array['price'][$language]; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- =================================================================== -->

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
                                                        <button style="width:150px" ; type="button" class="btn btn-success" onclick="SavePrice()">
                                                            <?php echo $array['save'][$language]; ?></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top:5px;">
                                            <div class="col-md-4">
                                                <div class="row" style="margin-left:5px;">
                                                    <div class="row" style="margin-left:30px;">
                                                        <button style="width:150px" ; type="button" class="btn btn-info" onclick="OpenDialog(2)">
                                                            <?php echo $array['setprice'][$language]; ?></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- tag column 1 -->
                        </div>
                    </div>
                    <!-- search document -->
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="card-body" style="padding:0px; margin-top:12px;margin-left:30px;">
                            <div class="row">
                                <div class="col-md-11">
                                    <div class="row">
                                        <select class="form-control" style="margin-left:20px;font-family: 'THSarabunNew';font-size:22px;width:250px;" id="hptsel2"></select>
                                        <input type="hidden" class="form-control" style="margin-left:20px;font-family: 'THSarabunNew';font-size:22px;width:210px;" name="search2" id="search2" placeholder="<?php echo $array['search'][$language]; ?>" >
                                        <button type="button" style="margin-left:20px;" class="btn btn-primary" name="button" onclick="ShowDoc();">
                                            <?php echo $array['search'][$language]; ?></button>
                                        <button type="button" style="margin-left:10px;" class="btn btn-success" name="button" onclick="OpenDialog(1);" id='show_btn' disabled='true'>
                                            <?php echo $array['show'][$language]; ?>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="dropdown-divider" style="margin-top:20px; margin-bottom:20px;"></div>

                            <div class="row">
                                <div class="card-body" style="padding:0px;">
                                    <table class="table table-fixed table-condensed table-striped" id="TableDoc" cellspacing="0" role="grid" style="font-size:24px;width:98%;font-family: 'THSarabunNew'">
                                        <thead style="font-size:24px;">
                                            <tr role="row">
                                                <th style='width: 5%;'>&nbsp;</th>
                                                <th style='width: 25%;'><?php echo $array['side'][$language]; ?></th>
                                                <th style='width: 25%;'><?php echo $array['docno'][$language]; ?></th>
                                                <th style='width: 45%;'><?php echo $array['date'][$language]; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody1_modal" class="nicescrolled" style="font-size:23px;height:450px;" />
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- /#wrapper -->
            <!-- Scroll to Top Button-->
            <a class="scroll-to-top rounded" href="#page-top">
                <i class="fas fa-angle-up"></i>
            </a>

            <!-- Dialog Modal-->
            <!-- <div id="dialog" title="<?php echo $array['import'][$language]; ?>"  style="z-index:999999 !important;font-family: 'THSarabunNew';font-size:24px;">
                <div class="container">
                    <div class="row">
                        <div class="col-md-11">
                            <div class="row">
                                <input type="text" class="form-control" style="font-family: 'THSarabunNew';font-size:22px;width:200px;" name="docno" id="docno" placeholder="<?php echo $array['docno'][$language]; ?>" >
                                <label id="rem" style="margin-left:20px;"> *** </label>
                                <input type="text" class="form-control datepicker-here" style="margin-left:20px;font-family: 'THSarabunNew';font-size:22px;width:200px;" id="datepicker" data-language='en' data-date-format='dd/mm/yyyy' placeholder="<?php echo $array['datepicker'][$language]; ?>">
                                <select class="form-control" style="margin-left:20px;font-family: 'THSarabunNew';font-size:22px;width:250px;" id="hptsel1"></select>
                                <button type="button" style="font-size:18px;margin-left:20px; width:100px;font-family: 'THSarabunNew'" class="btn btn-warning" id="create1" name="button" onclick="onCreate();"><?php echo $array['createdocno'][$language]; ?></button>
                                <input type="text" class="form-control" style="margin-left:20px;font-family: 'THSarabunNew';font-size:22px;width:210px;" name="search1"  id="search1" onKeyPress='if(event.keyCode==13){ShowItem2()}' placeholder="<?php echo $array['search'][$language]; ?>" >
                                <button type="button" style="font-size:18px;margin-left:20px; width:100px;font-family: 'THSarabunNew'" class="btn btn-primary" name="button" onclick="UpdatePrice(1);"><?php echo $array['saveprice'][$language]; ?></button>
                            </div>
                        </div>
                    </div>

                    <div class="dropdown-divider" style="margin-top:20px; margin-bottom:20px;"></div>

                    <div class="row">
                        <div class="card-body" style="padding:0px;">
                            <table class="table table-fixed table-condensed table-striped" id="TableItemPrice" cellspacing="0" role="grid" style="font-size:24px;width:1100px;font-family: 'THSarabunNew'">
                                <thead style="font-size:24px;">
                                <tr role="row">
                                    <th style='width: 5%;'>&nbsp;</th>
                                    <th style='width: 25%;'><?php echo $array['side'][$language]; ?></th>
                                    <th style='width: 25%;'><?php echo $array['categorymain'][$language]; ?></th>
                                    <th style='width: 25%;'><?php echo $array['categorysub'][$language]; ?></th>
                                    <th style='width: 20%;'><?php echo $array['price'][$language]; ?></th>
                                </tr>
                                </thead>
                                <tbody id="tbody1_modal" class="nicescrolled" style="font-size:23px;height:290px;">
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div> -->

<!-- -----------------------------Custom1------------------------------------ -->
<div class="modal" id="dialog" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                        <div class="col-md-12">
                            <div class="row mb-3">
                                <select class="form-control ml-5" style="font-family: 'THSarabunNew';font-size:22px;width:250px;" id="hptsel1"></select>

                                <label id="rem" style="margin-left:20px;"> *** </label>
                                <input type="text" class="form-control datepicker-here" style="margin-left:20px;font-family: 'THSarabunNew';font-size:22px;width:200px;" id="datepicker" data-language='en' data-date-format='dd/mm/yyyy' placeholder="<?php echo $array['datepicker'][$language]; ?>">
                                <input type="text" class="form-control" style="margin-left:20px;font-family: 'THSarabunNew';font-size:22px;width:200px;" name="docno" id="docno" placeholder="<?php echo $array['docno'][$language]; ?>" >

                                <button type="button" style="font-size:18px;margin-left:20px; width:100px;font-family: 'THSarabunNew'" class="btn btn-warning" id="create1" name="button" onclick="onCreate();"><?php echo $array['createdocno'][$language]; ?></button>
                                <input type="text" class="form-control" style="margin-left:20px;font-family: 'THSarabunNew';font-size:22px;width:210px;" name="search1"  id="search1" onKeyPress='if(event.keyCode==13){ShowItem2()}' placeholder="<?php echo $array['search'][$language]; ?>" >
                                <button type="button" style="font-size:18px;margin-left:20px; width:100px;font-family: 'THSarabunNew'" class="btn btn-primary" name="button" onclick="UpdatePrice();"><?php echo $array['saveprice'][$language]; ?></button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-fixed table-condensed table-striped" id="TableItemPrice" width="100%" cellspacing="0" role="grid" style="font-size:24px;width:1100px;font-family: 'THSarabunNew'">
                        <thead style="font-size:24px;">
                            <tr role="row">
                            <th style='width: 5%;'>&nbsp;</th>
                                <th style='width: 25%;' nowrap><?php echo $array['side'][$language]; ?></th>
                                <th style='width: 25%;' nowrap><?php echo $array['categorymain'][$language]; ?></th>
                                <th style='width: 25%;' nowrap><?php echo $array['categorysub'][$language]; ?></th>
                                <th style='width: 20%;' nowrap><?php echo $array['price'][$language]; ?></th>
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
