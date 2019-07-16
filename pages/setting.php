<!DOCTYPE html>
<?php
  session_start();
  $Id = $_SESSION['Userid'];
  $TimeOut = $_SESSION['TimeOut'];



$language = $_SESSION['lang'];


header ('Content-type: text/html; charset=utf-8');
$xml = simplexml_load_file('../xml/general_lang.xml');
$json = json_encode($xml);
$array = json_decode($json,TRUE);
?>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <script  type="text/javascript" src="../bootstrap/js/bootstrap.min.js"></script>
    <link href="../fontawesome/css/fontawesome.min.css" rel="stylesheet"> <!--load all styles -->
    <script src="../dist/js/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/css/sweetalert2.min.css">
    <script src="../dist/js/jquery-3.3.1.min.js"></script>
    <script src="../fontawesome/js/all.js"></script>

    <script type="text/javascript">

        $(document).ready(function(e) {

        }).mousemove(function(e) { parent.afk();parent.last_move = new Date();
        }).keyup(function(e) { parent.last_move = new Date();
        });

    function switchlang() {
        var lang = $('#lang').val();
        swal({
        title:'<?php echo $array['changelang'][$language]; ?>',
        // text: "You won't be able to revert this!",
        type: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '<?php echo $array['confirm'][$language]; ?>',
        cancelButtonText: '<?php echo $array['cancel'][$language]; ?>'
        }).then((result) => {
            var data = {
                'STATUS' : 'SETLANG',
                'lang' : lang,
                'UserID' : <?php echo $Id ?>
            }
            senddata(JSON.stringify(data));
            swal({
            title: "<?php echo $array['success'][$language]; ?>",
                type: "success",
                showCancelButton: false,
                timer: 1000,
                confirmButtonText: 'Ok',
                showConfirmButton: false
            });
            setTimeout(function () {
            parent.location.reload();
            }, 1000);
        })

    }

        function timeoutUpdate() {
            var timeout = document.getElementById("timeout").value;

            parent.redirectInSecond = timeout;
            parent.target = parent.redirectInSecond * 1000; // แปลงค่าเป็น microsecond
            parent.target = parent.target * 60;

            var Id = "<?php echo $Id ?>";
            if(timeout!=0){
                var data = {
                    'STATUS' : 'cTimeout',
                    'timeout': timeout,
                    'ID' : Id
                };
                console.log(JSON.stringify(data));
                senddata(JSON.stringify(data));
            }else{
                swal({
                    type: 'warning',
                    title: 'Something Wrong',
                    text: 'Please recheck your username and password!'
                })
            }
        }

    function senddata(data)
    {
        var form_data = new FormData();
        form_data.append("DATA",data);
        var URL = '../process/setting.php';
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
                            title: 'Please wait..',
                            text: 'Processing',
                            allowOutsideClick: false
                        })
                        swal.showLoading()
                    },
                    success: function (result) {
                        try {
                            var temp = $.parseJSON(result);
                            console.log(result);
                        } catch (e) {
                            console.log('Error#542-decode error');
                        }

                        if(temp["status"]=='success'){

                            if(temp["page"]=='language') {
                                swal.hideLoading()
                                swal({
                                    title: '',
                                    text: temp["msg"],
                                    type: 'success',
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    timer: 3000,
                                    confirmButtonText: 'Ok',
                                    showConfirmButton: false
                                }).then(function () {
                                    // window.location.href = 'pages/menu.php';
                                    //return loadIframe('ifrm', this.href)
                                }, function (dismiss) {
                                    // window.location.href = 'pages/menu.php';
                                    if (dismiss === 'cancel') {

                                    }
                                })
                            }else if(temp["page"]=='timeout') {
                                swal.hideLoading()
                                swal({
                                    title: '',
                                    text: temp["msg"],
                                    type: 'success',
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    timer: 3000,
                                    confirmButtonText: 'Ok',
                                    showConfirmButton: false
                                }).then(function () {
                                    // window.location.href = 'pages/menu.php';
                                    // //return loadIframe('ifrm', this.href)
                                }, function (dismiss) {
                                    // window.location.href = 'pages/menu.php';
                                    if (dismiss === 'cancel') {

                                    }
                                })
                            }
                        }else{
                            swal.hideLoading()
                            swal({
                                title: 'Something Wrong',
                                text: temp["msg"],
                                type: 'error',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ok'
                            }).then(function () {

                            }, function (dismiss) {
                                // dismiss can be 'cancel', 'overlay',
                                // 'close', and 'timer'
                                if (dismiss === 'cancel') {

                                }
                            })
                            //alert(temp["msg"]);
                        }
                    },
                    failure: function (result) {
                        alert(result);
                    },
                    error: function (xhr, status, p3, p4) {
                        var err = "Error " + " " + status + " " + p3 + " " + p4;
                        if (xhr.responseText && xhr.responseText[0] == "{")
                                err = JSON.parse(xhr.responseText).Message;
                        console.log(err);
                    }
        });
    }
    </script>
    <style>
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
      input[type="text"]{
        text-align: center;
        font-size: 190%;
        height: 70px;
      }

          .centered {
              position: fixed;
              top: 50%;
              left: 50%;
              margin-top: -150px;
              margin-left: -250px;
          }
    </style>
    <title>Login</title>
  </head>
  <body>

  <div class="centered">
      <div class="row">
          <div class="col-md-12"> <!-- tag column 1 -->

              <table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="TableItemDetail" width="100%" cellspacing="0" role="grid" style="">

                  <tbody id="tbody" class="nicescrolled" style="font-size:23px;height:300px;">
                    <tr>
                        <td style='width: 50%;'>
                            <?php echo $array['changetimeout'][$language]; ?>
                        </td>
                        <td style='width: 50%;'>
                            <div class="form-group">
                                <input type="text" class="form-control" id="timeout" onchange="timeoutUpdate();"
                                       placeholder="set new timeout" value="<?= $TimeOut ?>">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $array['changelang'][$language]; ?>
                        </td>
                        <td>
                            <select  class="form-control" id="lang" style="font-size:30px;height: 65px" onchange="switchlang()">
                                <?php if($language=='th'){ ?>
                                    <option selected value="th"><?php echo $array['thai'][$language]; ?></option>
                                    <option value="en"><?php echo $array['eng'][$language]; ?></option>
                                <?php } else { ?>
                                    <option value="th"><?php echo $array['thai'][$language]; ?></option>
                                    <option selected value="en"><?php echo $array['eng'][$language]; ?></option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                  </tbody>
              </table>
          </div> <!-- tag column 1 -->
      </div>

  </div>

  </body>
</html>
