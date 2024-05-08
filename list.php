<?PHP

//處理登入
$user = '';
session_start();
if (isset($_SESSION['user'])) {
    $user = $_SESSION["user"];
}
$user_account = '';
if (isset($_SESSION['user_account'])) {
    $user_account = $_SESSION["user_account"];
}
if (isset($_SESSION['prvilige'])) {
    $prvilige = $_SESSION["prvilige"];
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Lists</title>
    <!-- 將 CSS 文件連結到 HTML -->
    <link rel="stylesheet" href="list.css">
</head>

<body id="top">
    <div id="contener">
        <form method="post" enctype="multipart/form-data">
            <header class="col-pc-12 col-mobile-12">

                <nav id="tool">
                    <div id="home">
                        <a href="index.php"><img src="icon/house-solid.svg" class="icon" alt="go to homepage" width="20px" height="20px"></a>
                    </div>

                    <div id="cart">
                        <?PHP
                        if (empty($user)) {
                            echo '<a href="check.php">請登入使用購物車</a>';
                        } else {
                            echo '&nbsp; Hi !&nbsp;' . $user . '&nbsp;';
                            echo '<input type="submit" name="logout" value="登出">';
                        }
                        //如果點案登出按鈕，下處理登出:結束SESSION
                        if (isset($_POST['logout'])) {
                            session_destroy();
                            echo '<script>location.href = "index.php";</script>';
                        }
                        ?>
                    </div>
                </nav>

            </header>
            <main>
                <div id="outer">
                    <?PHP
                    $result_user;
                    $result_cart;
                    $mysqli = new mysqli("localhost", "root", "den959glow487", "test1");
                    $mysqli->query("SET NAMES 'UTF8' ");
                    $result_user = $mysqli->query('SELECT * FROM test1.user where account in 
                    (SELECT distinct user FROM test1.cart where buy_confirm = 1);'); //抓有訂單的人名的table

                    //如果沒有訂單則顯示
                    if (mysqli_num_rows($result_user) === 0) {
                        echo '
                            <div id="item">
                            <div id="row_no_list" class="row">
                                <div>目前尚無訂單需要處理！</div>
                            ';
                        $result_user->close();
                        $mysqli->close();   //關閉資料庫
                    } else {
                        //$row_user[0] user_id
                        //$row_user[1] account
                        //$row_user[2] password
                        //$row_user[3] prvilige
                        //$row_user[4] name
                        //$row_user[5] to_shop
                        //$row_user[6] to_address
                        //$row_user[7] bank_account
                        //$row_user[8] 1shop_2addr
                        while ($row_user = mysqli_fetch_row($result_user)) {
                            $rows_user = $row_user[0];
                            $account = $row_user[1];
                            echo '
                                <div id="item">
                                <div id="row1" class="row">
                                    <div id="acount">acount :&nbsp;&nbsp;' . $account . '</div>
                                    <input type="submit" name="done_' . $rows_user . '" id="done" value="done1" title="完成此訂單，刪除。" style=" height:22px"></div>
                                <div id="row2" class="row">
                                    <div id="name">name :&nbsp;&nbsp;' . $row_user[4] . '</div>
                                    <div id="to_home">home :&nbsp;&nbsp;' . $row_user[6] . '</div>
                                </div>
                                <div id="row3" class="row">
                                    <div id="user_id">user id :&nbsp;&nbsp;' . $row_user[0] . '</div>
                                    <div id="to_shop">shop :&nbsp;&nbsp;' . $row_user[5] . '</div>
                                </div>
                                <div id="row4" class="row">
                                <div id="product">
                                    <div id="product_id">編號</div>
                                    <div id="product_name">商品</div>
                                    <div id="product_price">價錢</div>
                                    <div id="product_num">數量</div>
                                    <div id="product_sum">小計</div>
                                </div>
                            ';
                            $result_cart = $mysqli->query('SELECT * FROM test1.`cart` where `user` = "' . $account . '" and `buy_confirm` = "1" and `buy_bool` = "1";'); //抓table
                            //$row_cart[0] id
                            //$row_cart[1] user
                            //$row_cart[2] product_id
                            //$row_cart[3] product_name
                            //$row_cart[4] product_pic_dir
                            //$row_cart[5] price
                            //$row_cart[6] num
                            //$row_cart[7] buy_bool
                            //$row_cart[8] product_description

                            //暫時儲存該員所被完成出貨的項目名稱，在出貨時要傳遞到使用者的訊息通知欄
                            $ones_buy = [];
                            $countter = 0;
                            while ($row_cart = mysqli_fetch_row($result_cart)) {
                                echo '
                                    <div id="product">
                                        <div id="product_id">' . $row_cart[0] . '</div>
                                        <div id="product_name">' . $row_cart[3] . '</div>
                                        <div id="product_price">' . $row_cart[5] . '</div>
                                        <div id="product_num">' . $row_cart[6] . '</div>
                                        <div id="product_sum">' . $row_cart[5] * $row_cart[6] . '</div>
                                    </div>
                                ';
                                $ones_buy[$countter] = $row_cart[3];
                                $countter++;
                            }
                            echo '
                                </div>
                                </div>
                            ';
                            echo join('+',$ones_buy);
                            // foreach ($ones_buy as $ones_buy) {  此方法也可以
                            //     echo $ones_buy . " ";
                            // }
                            // print_r($ones_buy);    此方法也可以，但是會把定義的title也印出。如: [0]=>a [1]=>b,etc.。

                            //如果管理者已經處裡完訂單則需點擊 "done" 以消除該使用者的訂購項目
                            //下有兩部分 1.刪除購物車項目；2.建立通知
                            if (isset($_POST['done_' . $rows_user . ''])) {
                                echo "<script>alert('gonna delete the object! & inform the user " . $account . "')</script>;";
                                //1.刪除購物車項目
                                $sqlj = 'DELETE FROM `test1`.`cart` where `user` = "' . $account . '" and `buy_confirm` = "1" and `buy_bool` = "1";';  //欲刪除的單項目
                                $mysqli->query($sqlj);
                                //建立通知訊息
                                $info0 = '訂購商品出貨嘍！'.join('、',$ones_buy) .'&nbsp;&nbsp;'. date("Y/m/d");
                                //2.訊息存入該user的資料表
                                write_to_buyer($account, $info0);
                                echo '<script>location.href = "list.php";</script>';
                            }
                            $result_cart->close();
                        }
                        $result_user->close();
                        $mysqli->close();   //關閉資料庫   
                    }
                    ?>

                </div>
            </main>
        </form>
    </div>
    <footer>
        <div id="editing_page"><a href="edit.php">go to Editing Page</a> </div>
        <div id="author">
            <p>本網站由德斯貿易公司(Desmo co.,lmt.)所有 Copy Right &copy; 2023</p>
        </div>
        <div id="cont"><a href="contact.php">Contact Us</a> </div>
    </footer>
</body>
<span id="toTop"> <a href="#top"><img src="icon/arrow-up.svg" alt="" title="to top" height="35px" width="35px"></a></span>

</html>
<?PHP
function write_to_buyer($account, $info0)
{
    //a.將info2變info3，b.info1變info2，c.現在訊息存入info1。
    //將info1、2調出
    $mysqli = new mysqli("localhost", "root", "den959glow487", "test1");
    $mysqli->query("SET NAMES 'UTF8' ");
    $info1 = '';
    $info2 = '';
    $result1 = $mysqli->query('SELECT `info0`, `info1` FROM test1.`user` where `account` = "' . $account . '" ;');
    while ($info1_2_row = mysqli_fetch_row($result1)) {
        $info1 = $info1_2_row[0];
        $info2 = $info1_2_row[1];
    }
    //將調出的info1、2放入info2、3；函數外傳入的info0，也放入info0。
    $sqlj = "UPDATE `test1`.`user` SET `info0` = '" . $info0 . "', `info1` = '" . $info1 . "',`info2` = '" . $info2 . "'  where `account` = '" . $account . "';";
    $mysqli->query($sqlj);
    $result1->close();
    $mysqli->close();   //關閉資料庫
}
?>