<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in</title>
    <!-- 將 CSS 文件連結到 HTML -->
    <link rel="stylesheet" href="login.css">
    <!-- 將 JS  文件連結到 HTML -->
    <script src="login.js"></script>
</head>

<body>
    <header id="title">
        <h1>Log in, for the best purchase quality.</h1>
    </header>

    <div id="backsback">
        <div id="back">
            <div id="login_words">
                <h2 id="login_words_h2">Log In</h2>
                <h3 id="login_words_h3">Log in, if you had an account. Perchasing with account, you would enjoy a joyful experience.</h3>
                <button id="goto_signup" onclick="goto_signup()">go to Sign Up</button>
            </div>
            <div id="sighup_words">
                <h2 id="sighup_words_h2">Sign Up (admin./)</h2>
                <h3 id="sighup_words_h3">If you don't have account, just join us. A brilliant decision always keep people be with wisdom.</h3>
                <button id="goto_login" onclick="goto_login()">go to Log In</button>
            </div>

            <!-- 跳動面板由於CSS設定需以id="back"為根據，故須放置在其<div>下 -->
            <form method="post" enctype="multipart/form-data">
                <div id="panel" class="panel_log">
                    <h2 id="panel_login" class="panel_login">Log In</h2>
                    <h2 id="panel_signup" class="panel_signup">Sign Up</h2>
                    <input type="email" id="account_input" name="account_input" placeholder="e-mail">
                    <input type="password" id="password_input" name="password_input" placeholder="password">
                    <div id="choice">
                        <input type="submit" id="choice_forget" name="forget" value="Forget Password?">
                        <input type="submit" id="choice_login" name="login" value="Log In" class="choice_login_signup">
                        <input type="submit" id="choice_signup" name="signup" value="Sign Up" class="choice_signup">
                    </div>
                </div>
            </form>
        </div>


        <!-- 開始登入等功能 -->
        <?php
        // 0. 1st rows are filled
        // 1. if can't, to sign up
        // 2. wrong password
        // 3. log in
        // 4. sign up
        // 5. signup if exist(re-assign acc name)
        if (isset($_POST['login'])) {
            // 0.
            $account = $_POST['account_input'];
            $password = $_POST['password_input'];
            if (empty($account) or empty($password)) {
                echo "<script>alert('請填寫帳號及密碼')</script>";
                echo '<script>location.href = "login.php";</script>';
            }

            //1.
            $mysqli = new mysqli("localhost", "root", "den959glow487", "test1");
            $mysqli->query("SET NAMES 'UTF8' ");
            $result1 = $mysqli->query("SELECT * FROM test1.`user` WHERE (`account` = '" . $account . "');");
            if (mysqli_num_rows($result1) === 0) { //此用於檢查mysql是否有回傳值(此偵測無且跳警示)
                echo "<script>alert('There is not exit such that user.')</script>";
                echo "<script>alert('If you don't have account, please sign in for purchase more!')</script>";
                echo '<script>location.href = "login.php";</script>';
            }
            //2.1--叫出該帳號的密碼，用於比對。
            $right_password = '';
            $user = '';
            $prvilige = '';
            while ($row1 = mysqli_fetch_row($result1)) {
                $right_password = $row1[2];
                $user_account = $row1[1];
                $user = explode("@", $row1[1]);    //分離資料字串，抓取mail前的名字
                $user = $user[0];                  //[0]為分離後第一節字串；[1]第二節
                $prvilige = $row1[3];
            }
            //2.2--比對
            if ($password != $right_password) {
                echo "<script>alert('WRONG password! return')</script>";
                echo '<script>location.href = "login.php";</script>';
            }
            //3. login
            if ($password = $right_password) {
                $_SESSION["user"] = $user;
                $_SESSION["user_account"] = $user_account;
                $_SESSION["prvilige"] = $prvilige;
                echo "<script>alert('Logged In! Enjoy!')</script>";
                echo '<script>location.href = "check.php";</script>';
            }
            $result1->close();   
            $mysqli ->close();   //關閉資料庫
        }

        if (isset($_POST['signup'])) {
            $account = $_POST['account_input'];
            if (stripos($account, './')) {
                //設計 新開帳戶前面加上 "admin./" 則給予管理者權限
                $admin_or_user = explode("./", $account);    //分離資料字串
                $admin = $admin_or_user[0];                  //[0]為分離後第一節字串；[1]第二節
                if ($admin == 'admin') {
                    $admin = 'A';
                    $user_name = $admin_or_user[1];
                } else {
                    echo "<script>alert('錯誤的管理者命名(error: admin./ name)')</script>";
                    echo '<script>location.href = "login.php";</script>';
                }
            } else {   //如果沒有"admin./"則為普通使用者
                $admin = 'B';
                $user_name = $account;
            }
            $password = $_POST['password_input'];

            if (empty($account) or empty($password)) {
                echo "<script>alert('請填寫新的帳號及密碼')</script>";
                echo '<script>location.href = "login.php";</script>';
            }
            // 4. sign up (若查詢無資料，允許signup)
            $mysqli = new mysqli("localhost", "root", "den959glow487", "test1");
            $mysqli->query("SET NAMES 'UTF8' ");
            $result2 = $mysqli->query("SELECT * FROM test1.`user` WHERE (`account` = '" . $user_name . "');");
            if (mysqli_num_rows($result2) === 0) { //此用於檢查mysql是否有回傳值(此偵測無，即可signup)
                $mysqli = new mysqli("localhost", "root", "den959glow487", "test1"); //最後一個是資料庫Name
                $mysqli->query("SET NAMES 'UTF8' ");
                $sql = "INSERT INTO `test1`.`user` (`account`, `password`, `prvilige`) VALUES ('" . $user_name . "', '" . $password . "', '" . $admin . "');";  //欲寫入的單項目
                $mysqli->query($sql); //寫入sql, 寫入資料表table
                $result2->close();    
                $mysqli->close();     //關閉資料庫
                echo "<script>alert('Sign Up Successfully!')</script>";
                echo '<script>location.href = "check.php";</script>';
            }
            // 5. signup if exist (若查詢有資料，不可signup)
            else {
                echo "<script>alert('帳號已存在，請重新輸入！')</script>";
                echo '<script>location.href = "login.php";</script>';
            }
        }
        ?>
    </div>
</body>
<footer>
    <div id="home_page"><a href="index.php">HOME</a></div>
    <div id="author">
        <p>本網站由德斯貿易公司(Desmo co.,ltd.)所有 Copy Right &copy; 2023</p>
    </div>
    <div id="cont"><a href="contact.php">Contact Us</a> </div>
</footer>

</html>