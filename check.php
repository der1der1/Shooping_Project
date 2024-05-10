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

if (empty($user)) {
    echo '<script>location.href = "login.php";</script>';
}

if (isset($_SESSION['jumpto'])) {
    $product_id = $_SESSION["jumpto"];
}

if (empty($product_id) == false) {
    echo '<script>location.href = "#' . $product_id . '";</script>';
} else {
    $product_id = '0';
};



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check</title>

    <!-- 連結到AOS -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>

    <!-- 將 CSS & JS 文件連結到 HTML -->
    <link rel="stylesheet" href="check.css">
    <script src="check.js"></script>
</head>

<body>
    <header class="col-pc-12 col-mobile-12">
        <nav id="run">
            <?php
            //把MySQL的跑馬燈AD叫出來
            //再開資料庫
            $mysqli = new mysqli("localhost", "root", "den959glow487", "test1");
            $mysqli->query("SET NAMES 'UTF8' ");
            $result1 = $mysqli->query('SELECT words FROM test1.`word ads 2` ORDER BY RAND()'); //抓廣告文跑馬燈的table
            //marquee for跑馬燈
            echo '<marquee direction="left" width="100%" scrollamount="10" >';
            while ($row1 = mysqli_fetch_row($result1)) {
                echo $row1[0];
                for ($i = 1; $i <= 200; $i++) {
                    echo '&nbsp;';
                } //產出每個$row[0] ad 之間的空格
            }
            echo '</marquee>';
            $result1->close();
            $mysqli->close();   //關閉資料庫
            ?>
        </nav>
        <nav id="tool">
            <div id="home">
                <a href="index.php"><img src="icon/house-solid.svg" class="icon" alt="go to homepage" width="20px" height="20px"></a>
            </div>
            <form method="post" enctype="multipart/form-data">
                <div id="searchBar">
                    <label id="label" for="search">搜索Bar:</label>
                    <input type="text" id="search" name="search_name" placeholder="我想看看...冰箱?" height="10px" title="您想尋找甚麼?">
                    <input type="submit" name="search_button" value="搜索">
                </div>
            </form>
            <div id="cart">
                <a href="check.php"><img src="icon/cart-shopping-solid.svg" class="icon" alt="cart icon" width="20px" height="20px" title="go to cart"><span></span></a>
                <?PHP
                echo '&nbsp; Hi !&nbsp;' . $user . '&nbsp;';
                echo '<input type="submit" name="logout" value="登出">';
                //如果點案登出按鈕，下處理登出:結束SESSION
                if (isset($_POST['logout'])) {
                    session_destroy();
                    echo '<script>location.href = "index.php";</script>';
                } ?>
            </div>
        </nav>
    </header>
    <main>
        <div id="items">

            <form method="post" enctype="multipart/form-data">
                <?php
                $mysqli = new mysqli("localhost", "root", "den959glow487", "test1");
                $mysqli->query("SET NAMES 'UTF8' ");
                $result2 = $mysqli->query('SELECT * FROM test1.`cart` where `user` = "' . $user_account . '";');
                //$row2[0] id
                //$row2[1] user
                //$row2[2] product_id
                //$row2[3] product_name
                //$row2[4] product_pic_dir
                //$row2[5] price
                //$row2[6] num
                //$row2[7] buy_bool
                //$row2[8] product_description
                if (mysqli_num_rows($result2) === 0) {
                    echo '<div id="item"><h2>您的購物車內尚無商品</h2></div>';
                }
                $id = 0;
                $buy_bool = 0;
                while ($row2 = mysqli_fetch_row($result2)) {
                    $number = 0;
                    $price = 0;
                    $product_name = $row2[3];
                    $sum = 0;
                    $id = $row2[0];
                    $buy_bool = $row2[7];
                    echo '<div id="' . $id . '" data-aos="fade-up" class="item" >';
                    echo '<div id="item_pic">';
                    echo '<img src="' . $row2[4] . '" alt="' . $product_name . '"  width="140px" height="140px">';
                    echo '</div>';
                    echo '<div id="item_description">';
                    echo '<p id="p">' . $product_name . '</br></br>' . $row2[8] . '</p>';
                    echo '</div>';
                    echo '<div id="item_money">';
                    echo '<div id="item_money_total"> Price $ = ' . $row2[5] . '</div>';
                    echo '<div id="item_money_top">';
                    echo '<p> number: &nbsp;</p>';
                    echo '<input type="number" min="0" name="num_adjust_' . $id . '" value="' . $row2[6] . '" id="num_adjust">';
                    echo '<input type="submit" name="num_submit_' . $id . '" value="確認數量" id="num_submit">';
                    $number = $row2[6];
                    $price = $row2[5];
                    $sum = $price * $number;
                    echo '</div>';
                    echo '<div id="item_money_total"> SUM $ = ' . $sum . '</div>';
                    echo '</div>';
                    echo '<div id="item_tradding_item">';

                    echo '<button id="choosing" name="choosing_' . $id . '" >';
                    // 偵測如果以按下 選取的booloean的話，則顯示打勾小圖標
                    if ($row2[7] == 1) {
                        echo '<img src="icon/check.png" title="I Want" height="25px" width="25px" id="check_icon">';
                        echo '<div id="select">';
                        echo '<p id="select_word">已選擇！</p>';
                        echo '</div>';
                    } else {
                        echo '<div id="select">';
                        echo '<p id="select_word">未選擇</p>';
                        echo '</div>';
                    }
                    echo '</button>';
                    echo '</div>';
                    echo '</div>';

                    //將修改數量更新到資料表
                    if (isset($_POST['num_submit_' . $id . ''])) {
                        $num_adjust = $_POST['num_adjust_' . $id];
                        $sqlj = "UPDATE `test1`.`cart` SET `num` = '" . $num_adjust . "' WHERE (`id` = '" . $id . "');";
                        $mysqli->query($sqlj);
                        $_SESSION["jumpto"] = $id;
                        echo '<script>location.href = "check.php";</script>';
                    }

                    //變更是否勾選購買，此部分需在每個商品的迴圈中描述。
                    if (isset($_POST['choosing_' . $id . ''])) {
                        $buy_bool = $buy_bool + 1;
                        if ($buy_bool % 2 == 0) {
                            $buy_bool = 0;    //做是否選取購買的暫存boolean
                        } else {
                            $buy_bool = 1;
                        }
                        echo  $id, $buy_bool;
                        $sql = "UPDATE `test1`.`cart` SET `buy_bool` = '" . $buy_bool . "' WHERE (`id` = '" . $id . "');";
                        $mysqli->query($sql);
                        $_SESSION["jumpto"] = $id;
                        echo '<script>location.href = "check.php";</script>';
                    }
                }
                $result2->close();
                $mysqli->close();   //關閉資料庫
                //逐項列出商品完畢
                ?>
            </form>
        </div>

        <footer>
            <form method="post" enctype="multipart/form-data">
                <div id="trading_bar">
                    <button id="onsell_ticket_button">
                        <img src="icon/ticket.png" title="gain perfact price" alt="ticketsIcon" height="70px" width="70px">
                        <p id="ticketword">YOUR ONSELL TICKETS HERE !</p>
                    </button>

                    <button id="check" name="check" title="pay for my favorits">
                        <img src="icon/takeaway.png" alt="takeawayIcon" height="65px" width="65px">
                        <span id="checkword">Purchase !!</span>
                    </button>

                </div>
            </form>

            <div id="last_footer">
                <div id="author">
                    <p>本網站由德斯貿易公司(Desmo co.,lmt.)所有 Copy Right &copy; 2023</p>
                </div>
                <div id="cont"><a href="contact.php">Contact Us</a> </div>
            </div>
        </footer>
    </main>
    <script>
        AOS.init({
            duration:500,
        });
    </script>
</body>
<!-- 偵測如果沒有選擇商品則無法進入結帳頁面 -->
<?php
$mysqli = new mysqli("localhost", "root", "den959glow487", "test1");
$mysqli->query("SET NAMES 'UTF8' ");
$result3 = $mysqli->query('SELECT * FROM test1.`cart` where `user` = "' . $user_account . '" and `buy_bool` = "1";');
if (isset($_POST['check'])) {
    if (mysqli_num_rows($result3) === 0) {
        echo "<script>alert('您無勾選想購買的商品。回到購物車。')</script>";
        $result3->close();
        $mysqli->close();   //關閉資料庫
    } else {
        $result3->close();
        $mysqli->close();   //關閉資料庫
        echo '<script>location.href = "pay.php";</script>';  //去結帳
    }
}
?>

</html>
