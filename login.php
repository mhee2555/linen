<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/jquery-3.3.1.slim.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <script src="dist/js/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="dist/css/sweetalert2.min.css">
    <script src="dist/js/jquery-3.3.1.min.js"></script>

    <title>Login | Linen</title>
</head>
<body>
    <!-- ====================== form Login======================= -->
        <div id="form_input">
            <div id="form_white">
                <div class="row">
                    <!-- logo -->
                    <div id="logo_top">
                        <img src="img/logo.png">
                    </div>
                    <!-- end logo -->
                    <!-- input username -->
                    <div id="username_div">
                        <div id="label1">
                            <label for="username">Username</label>
                        </div>
                        <div class="input-group color1">
                            <input type="text" class="form-control" id="username">
                        </div>
                        <div class='icon_username'>
                            <img src="img/icon1.png">
                        </div>
                    </div>
                    <!-- endinput username -->
                    <!-- input password -->
                    <div id="password_div">
                        <div id="label2">
                            <label for="password">Password</label>
                        </div>
                        <div class="input-group color1">
                            <input type="password" class="form-control" id="password" required>
                        </div>
                        <div class='icon_password'>
                            <img src="img/icon2.png">
                        </div>
                    </div>
                    <!-- endinput username -->
                    <div id="reset_pass">
                        <a href="javascript:void(0)" >Reset Password</a>
                    </div>
                    <div id="change_pass">
                        <a href="javascript:void(0)" onclick="change_pass();">Change Password</a>
                    </div>
                    <div id='btn_submit'>
                        <div class="col-md-12">
                            <a class='btn btn-block' onclick="chklogin();">LOGIN</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- ==================== End form ========================== -->

    <script>
        function change_pass()
        {
            $.ajax({
                method: "POST",
                url: "change_password.html",
                success: function (data) {
                    $('#form_white').attr('hidden', true);
                    $('#form_input').append(data);
                }
            })
        }

        function back()
        {
            $('#form_white').attr('hidden', false);
            $('#form_change').remove();
        }

        function chklogin() {
            var user = document.getElementById("username").value;
            var password = document.getElementById("password").value;
    
            // alert(user);
            // alert(password);
    
            if (user != "" && password != "") {
            var data = {
                'STATUS': 'checklogin',
                'PAGE': 'login',
                'USERNAME': user,
                'PASSWORD': password
            };
            console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
            } else {
            swal({
                type: 'warning',
                title: 'Something Wrong',
                text: 'Please recheck your username and password!'
            })
            }
        }


        function passwordUpdate() {
            var oldpassword = document.getElementById("oldpassword").value;
            var newpassword = document.getElementById("newpassword").value;
            var confirmpassword = document.getElementById("confirmpassword").value;
            var Username = document.getElementById("username2").value;
            if(oldpassword!="" && newpassword!=""&& confirmpassword!=""){
                var data = {
                'STATUS' : 'cPassword',
                'PAGE' : 'cPassword',
                'oldpassword': oldpassword,
                'newpassword' : newpassword,
                'confirmpassword' : confirmpassword,
                'Username' : Username,
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

        function senddata(data) {
            var form_data = new FormData();
            form_data.append("DATA", data);
            var URL = 'process/login.php';
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
                    console.log(result);
                } catch (e) {
                    console.log('Error#542-decode error');
                }
                if (temp["status"] == 'success') {
                    if(temp["form"] == 'chk_login'){
                        swal({
                            title: '',
                            text: temp["msg"],
                            type: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            timer: 1000,
                            confirmButtonText: 'Ok',
                            showConfirmButton: false
                        });
                        setTimeout(function(){ 
                            window.location.href = 'main.php';
                        }, 1000);
                    }else if(temp["form"] == 'change_password'){
                        swal({
                            title: '',
                            text: temp["msg"],
                            type: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            timer: 1000,
                            confirmButtonText: 'Ok',
                            showConfirmButton: false
                        });
                        setTimeout(function(){ 
                            window.location.href = 'main.php';
                        }, 1000);
                    }
                } else if (temp["status"] == 'change_pass') {
                    $.ajax({
                        method: "POST",
                        url: "change_password.html",
                        success: function (data) {
                            $('#form_white').attr('hidden', true);
                            $('#form_input').append(data);
                            $('#username2').val(temp['username']);
                            $('#oldpassword').val(temp['password']);
                        }
                    })
                   
                } else {
                    // swal.hideLoading()
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
</body>
</html>