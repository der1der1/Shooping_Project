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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay</title>
    <!-- 將 CSS 文件連結到 HTML -->
    <link rel="stylesheet" href="pay.css">
</head>

<body>
    <form method="post" enctype="multipart/form-data">
        <div id="contener">
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
                    $mysqli ->close();   //關閉資料庫
                    ?>
                </nav>

                <form method="post" enctype="multipart/form-data">
                    <nav id="tool">
                        <div id="home">
                            <a href="index.php"><img src="icon/house-solid.svg" class="icon" alt="go to homepage" width="20px" height="20px"></a>
                        </div>

                        <div id="searchBar">
                            <label id="label" for="search">搜索Bar:</label>
                            <input type="text" id="search" name="search_name" placeholder="我想看看...冰箱?" height="10px" title="您想尋找甚麼?">
                            <input type="submit" name="search_button" value="搜索">
                        </div>
                        <?PHP
                        if (isset($_POST['search_button'])) {
                            $search_name = $_POST['search_name'];
                            if (empty($search_name)) {
                                //不做任何事，但若無此空if則會直接跳轉，不需跳轉。
                            } else {
                                // 使用網址後"?"連結並抓取儲存的查詢，並借用上面asid 的 if 跑出搜尋結果。
                                echo '<script>location.href = "./index.php?home_search_category=' . $search_name . '";</script>';  //重置
                            }
                        }
                        ?>
                        <div id="cart">
                            <?PHP
                            if (empty($user)) {
                                echo '<a href="login.php"><img src="icon/cart-shopping-solid.svg" class="icon" alt="cart icon" width="20px" height="20px" title="plz log in first">請登入使用購物車</a>';
                            } else {
                                // echo '</a>';
                                $_SESSION["interested_product"] = '';
                                echo '<a href="check.php"><img src="icon/cart-shopping-solid.svg" class="icon" alt="cart icon" width="20px" height="20px" title="Cart"></a>';
                                echo '&nbsp; Hi !&nbsp;' . $user. '&nbsp;';
                                echo '<input type="submit" name="logout" value="登出">';
                            }
                            //如果點案登出按鈕，下處理登出:結束SESSION
                if (isset($_POST['logout'])) {
                                session_destroy();
                                echo '<script>location.href = "itemPage.php";</script>';
                            }
                            ?>
                        </div>
                    </nav>
                </form>
            </header>
            <main>
                <div id="items">
                    <?php
                    $mysqli = new mysqli("localhost", "root", "den959glow487", "test1");
                    $mysqli->query("SET NAMES 'UTF8' ");
                    $result1 = $mysqli->query('SELECT * FROM test1.`cart` where `user` = "' . $user_account . '" and `buy_bool` = "1";');
                    //$row1[0] id
                    //$row1[1] user
                    //$row1[2] product_id
                    //$row1[3] product_name
                    //$row1[4] product_pic_dir
                    //$row1[5] price
                    //$row1[6] num
                    //$row1[7] buy_bool
                    //$row1[8] product_description
                    while ($row1 = mysqli_fetch_row($result1)) {
                        $sum = $row1[5] * $row1[6];
                        echo '
                    <div id="item">
                        <div id="picture"><img src="' . $row1[4] . '" alt="' . $row1[3] . '" title="' . $row1[3] . '" width="120px" height="120px"></div>
                        <div id="info">
                            <div id="infoName">' . $row1[3] . '</div>
                            <div id="infoPrice">$' . $row1[5] . ' x ' . $row1[6] . ' = $ ' . $sum . ' </div>
                        </div>
                    </div>
                    ';
                    }
                    $result1->close();   
                    $mysqli ->close();   //關閉資料庫
                    //逐項列出商品完畢
                    ?>
                </div>

                <div id="where">
                    <!-- 本div中有5個部分 1. toStore; 2. toHome; 3. 收貨姓名; 4. 付款帳號; 5. 地圖API-->
                    <!-- 1. toStore -->
                    <div id="toStore">
                        <button id="market" title="market">
                            <img src="icon/shop.png" alt="to store" height="50px" width="50px">
                        </button>
                        <!--711的下拉式表單，此有機會可以再加入mysql-->
                        <select name="store">
                            <option value="新竹中山店">新竹中山店 - 新竹市東區中山路176號</option>
                            <option value="大安仁愛店">大安仁愛店 - 台北市大安區仁愛路四段345號</option>
                            <option value="中正忠孝店">中正忠孝店 - 台北市中正區忠孝西路一段50號</option>
                            <option value="西屯文心店">西屯文心店 - 台中市西屯區文心路三段100號</option>
                            <option value="南屯大墩店">南屯大墩店 - 台中市南屯區大墩路一段766號</option>
                            <option value="前鎮中華店">前鎮中華店 - 高雄市前鎮區中華五路596號</option>
                            <option value="苓雅中華店">苓雅中華店 - 高雄市苓雅區中華四路282號</option>
                            <option value="板橋文化店">板橋文化店 - 新北市板橋區文化路一段128號</option>
                            <option value="新店中正店">新店中正店 - 新北市新店區中正路151號</option>
                            <option value="桃園中正店">桃園中正店 - 桃園市桃園區中正路550號</option>
                            <option value="中壢中北店">中壢中北店 - 桃園市中壢區中北路二段466號</option>
                        </select>
                        <input type="submit" name="to_store" value="請選擇貨運711">
                    </div>

                    <!-- 2. toHome -->
                    <div id="toHome">
                        <button id="express" title="express">
                            <img src="icon/home.png" alt="to home" height="50px" width="50px">
                        </button>
                        <?php
                        $mysqli = new mysqli("localhost", "root", "den959glow487", "test1");
                        $mysqli->query("SET NAMES 'UTF8' ");
                        $result2 = $mysqli->query('SELECT * FROM test1.`user` where `account` = "' . $user_account . '" ;');
                        //$row[0] user_id
                        //$row[1] account
                        //$row[2] password
                        //$row[3] prvilige
                        //$row[4] name
                        //$row[5] to_shop
                        //$row[6] to_address
                        //$row[7] bank_account
                        //$row[8] 1shop_2addr
                        while ($row3 = mysqli_fetch_row($result2)) {
                            $address = $row3[6];
                            if (empty($address)) {
                                $address = 'eg. 台南市善化區小新里';
                            }
                            echo '
                                <label for="deliver to your home">輸入住址：</label>
                                <input type="text"   name="address"  value="' . $address . '">
                                <input type="submit" name="to_address" value="選擇宅配到家">
                                ';
                        }
                        $result2->close();   
                        $mysqli ->close();   //關閉資料庫
                        ?>
                    </div>

                    <!--3. 收貨姓名; 4. 付款帳號 -->
                    <?php
                    $mysqli = new mysqli("localhost", "root", "den959glow487", "test1");
                    $mysqli->query("SET NAMES 'UTF8' ");
                    $result3 = $mysqli->query('SELECT * FROM test1.`user` where `account` = "' . $user_account . '" ;');
                    //$row[0] user_id
                    //$row[1] account
                    //$row[2] password
                    //$row[3] prvilige
                    //$row[4] name
                    //$row[5] to_shop
                    //$row[6] to_address
                    //$row[7] bank_account
                    //$row[8] 1shop_2addr
                    while ($row3 = mysqli_fetch_row($result3)) {
                        $name = $row3[4];
                        $acc  = $row3[7];
                        if (empty($name)) {
                            $name = '王大明';
                        }
                        if (empty($acc)) {
                            $acc = '0191227-0082229';
                        }
                        echo '
                    <div id="check_name">
                        <label for="deliver to your home">請輸入收貨人姓名：</label>
                        <input type="text"   name="name_input"  value="' . $name . '">
                        <input type="submit" name="name_submit" value="確認">
                    </div>

                    <div id="check_account">
                        <label for="deliver to your home">請輸入扣款帳號：</label>
                        <input type="text"   name="account_input"  value="' . $acc . '">
                        <input type="submit" name="account_submit" value="確認">
                    </div>
                    ';
                    }
                    $result3->close();   
                    $mysqli ->close();   //關閉資料庫
                    ?>

                    <!-- 5. 地圖API -->
                    <div id="LocationMap">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d13321.760098507479!2d120.21569612878777!3d22.998651853240307!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1szh-TW!2stw!4v1705164389576!5m2!1szh-TW!2stw" width="100%" height="255px" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>

                <!-- Where區的PHP控制區 -->
                <?PHP
                $mysqli = new mysqli("localhost", "root", "den959glow487", "test1");
                $mysqli->query("SET NAMES 'UTF8' ");

                // 1.
                if (isset($_POST['to_address'])) {
                    $address = $_POST['address'];
                    $sql = "UPDATE `test1`.`user` SET `to_address` = '" . $address . "', `1shop_2addr` = '2' WHERE (`account` = '" . $user_account . "');";
                    $mysqli->query($sql);
                    echo '<script>location.href = "pay.php";</script>';  //重置 
                }

                // 2.
                if (isset($_POST['to_store'])) {
                    $store = $_POST['store'];
                    $sql = "UPDATE `test1`.`user` SET `to_shop` = '" . $store . "',  `1shop_2addr` = '1' WHERE (`account` = '" . $user_account . "');";
                    $mysqli->query($sql);
                    echo '<script>location.href = "pay.php";</script>';  //重置 
                }

                // 3.
                if (isset($_POST['name_submit'])) {
                    $name_input = $_POST['name_input'];
                    $sql = "UPDATE `test1`.`user` SET `name` = '" . $name_input . "' WHERE (`account` = '" . $user_account . "');";
                    $mysqli->query($sql);
                    echo '<script>location.href = "pay.php";</script>';  //重置 
                }

                // 4.
                if (isset($_POST['account_submit'])) {
                    $account_input = $_POST['account_input'];
                    $sql = "UPDATE `test1`.`user` SET `bank_account` = '" . $account_input . "' WHERE (`account` = '" . $user_account . "');";
                    $mysqli->query($sql);
                    echo '<script>location.href = "pay.php";</script>';  //重置 
                }

                ?>

                <div id="trading">
                    <?PHP
                    echo '<table id="check_list"> 購買確認清單';
                    $mysqli = new mysqli("localhost", "root", "den959glow487", "test1");
                    $mysqli->query("SET NAMES 'UTF8' ");
                    $result4 = $mysqli->query('SELECT * FROM test1.`user` where `account` = "' . $user_account . '" ;');
                    // $row[0] user_id
                    // $row[1] account
                    // $row[2] password
                    // $row[3] prvilige
                    // $row[4] name
                    // $row[5] to_shop
                    // $row[6] to_address
                    // $row[7] bank_account
                    // $row[8] 1shop_2addr
                    while ($row4 = mysqli_fetch_row($result4)) {
                        echo '
                            <tr><td>收件姓名：</td><td>' . $row4[4] . '</td></tr>
                            <tr><td>訂購帳號：</td><td>' . $row4[1] . '</td></tr>
                            <tr><td>扣款帳號：</td><td>' . $row4[7] . '</td></tr>
                        '; 
                        if($row4[8] == 1){
                            echo '
                            <tr><td>貨運方式：</td><td>超商取貨(位置以下)</td></tr>
                            <tr><td>收件商店：</td><td>' . $row4[5] . '</td></tr>';
                        }   
                        else{
                            echo '
                            <tr><td>貨運方式：</td><td>快遞到家(位置以下)</td></tr>
                            <tr><td>收件地址：</td><td>' . $row4[6] . '</td></tr>';
                        }                          
                    }

                    echo '
                        <tr><th>名稱</th><th>編號</th><th>數量</th><th>單價</th><th>小計</th></tr>
                        <tr><th>_____</th><th>_____</th><th>_____</th><th>_____</th><th>_____</th></tr>
                    ';
                    $mysqli = new mysqli("localhost", "root", "den959glow487", "test1");
                    $mysqli->query("SET NAMES 'UTF8' ");
                    $result5 = $mysqli->query('SELECT * FROM test1.`cart` where `user` = "' . $user_account . '" and `buy_bool` = "1";');
                    //$row1[0] id
                    //$row1[1] user
                    //$row1[2] product_id
                    //$row1[3] product_name
                    //$row1[4] product_pic_dir
                    //$row1[5] price
                    //$row1[6] num
                    //$row1[7] buy_bool
                    //$row1[8] product_description
                    //$row1[9] buy_confirm
                    $total = 0;
                    while ($row5 = mysqli_fetch_row($result5)) {
                        $sum = $row5[6] * $row5[5];
                        echo '
                            <tr><th>' . $row5[3] . '</th><th>' . $row5[2] . '</th><th>' . $row5[6] . '</th><th>' . $row5[5] . '</th><th>' . $sum . '</th></tr>
                        ';
                        $total = $total + $sum;
                    }
                    echo '
                    <tr><th>_____</th><th>_____</th><th>_____</th><th>_____</th><th>_____</th></tr>
                    <tr><td>交易金額：</td><td>$ ' . $total . '</td></tr>
                    </table>
                    ';
                    $result4->close();   
                    $result5->close();   
                    $mysqli ->close();   //關閉資料庫
                    ?>


                    <button title="結帳" name="checkit" id="checkit">
                        <h3>結帳</h3>
                        <img src="icon/bill.png" alt="pay for it" width="50x" height="50px">
                    </button>

                    <?PHP
                    if (isset($_POST['checkit'])) {
                        $mysqli = new mysqli("localhost", "root", "den959glow487", "test1");
                        $mysqli->query("SET NAMES 'UTF8' ");
                        $sql = "UPDATE `test1`.`cart` SET `buy_confirm` = '1' WHERE (`user` = '" . $user_account . "' and `buy_bool` = 1);";
                        $mysqli->query($sql);
                        $mysqli ->close();   //關閉資料庫

                        $mysqli = new mysqli("localhost", "root", "den959glow487", "test1");
                        $mysqli->query("SET NAMES 'UTF8' ");
                        $sqli = "UPDATE `test1`.`user` SET `list` = '1' WHERE (`account` = '" . $user_account . ");";
                        $mysqli->query($sqli);
                        $mysqli ->close();   //關閉資料庫
                        echo "<script>alert('訂購單已成功送出！返回首頁');</script>";
                        echo '<script>location.href = "index.php";</script>';  //回首頁
                    }
                    ?>
                </div>

                <footer>
                    <div id="author">
                        <p>本網站由德斯貿易公司(Desmo co.,lmt.)所有 Copy Right &copy; 2023</p>
                    </div>

                    <div id="cont"><a href="contact.php">Contact Us</a> </div>
                </footer>
            </main>
        </div>
    </form>
</body>

</html>