<?PHP
$id = '1';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

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
    <!-- 將 CSS 文件連結到 HTML -->
    <link rel="stylesheet" href="itemPage.css">
    
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?PHP
    $mysqli = new mysqli("localhost", "root", "den959glow487", "test1");
    $mysqli->query("SET NAMES 'UTF8' ");
    $result0 = $mysqli->query('SELECT * FROM test1.`products` where (`id` = "' . $id . '")'); //抓title
    while ($row0 = mysqli_fetch_row($result0)) {
        echo '<title>' . $row0[3] . '</title>';
    }
    $result0->close();   
    $mysqli ->close();   //關閉資料庫
    ?>

</head>

<body id="top">
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
            <form method="POST" enctype="multipart/form-data">
                <?PHP
                //廣告文display
                $mysqli = new mysqli("localhost", "root", "den959glow487", "test1");
                $mysqli->query("SET NAMES 'UTF8' ");
                $result1 = $mysqli->query('SELECT * FROM test1.`products` where (`id` = "' . $id . '")'); //抓table
                //row[0] id*
                //row[1] pic_name, no used now
                //row[2] pic_dir*
                //row[3] product_name*
                //row[4] description
                //row[5] price*
                //row[6] ori_price
                $product_id = '';
                $product_pic_dir = '';
                $product_name = '';
                $product_price = '';
                while ($row1 = mysqli_fetch_row($result1)) {
                    echo '  
                        <div id="interested_product">
                            <div id="picture1"><img src="' . $row1[2] . '" alt="" height="340px" width="340px   "></div>
                                <div id="info">
                                    <div id="title_select">
                                        <div id="title">' . $row1[3] . '</div>
                                        <input type="submit" name="select_product" id="select" value="加入購物車">
                                    </div>
                                <div id="paragraph">售價 $ ' . $row1[5] . ' </br></br></br>' . $row1[4] . '</div>
                            </div>
                        </div>
                        ';
                    $product_id = $row1[0];
                    $product_pic_dir = $row1[2];
                    $product_name = $row1[3];
                    $product_description = $row1[4];
                    $product_price = $row1[5];
                }
                $result1->close();   
                $mysqli ->close();   //關閉資料庫

                //存入購物車
                //1. 如果沒有登入要先去登入(已經暫存當前商品在$ID，此須將?ID存到SESSION，但登入後要跳轉回來。)
                //2. 如果登入可以存入
                if (isset($_POST['select_product'])) {
                    // 1.
                    if (empty($user)) {
                        $_SESSION["interested_product"] = $id;
                        echo "<script>alert('您尚未登入，前往登入畫面。')</script>";
                        echo '<script>location.href = "login.php";</script>';
                    }
                    //2. 存入購物車
                    else {
                        //C'est pour la teste/
                        echo $user, "--", $id, "--", $product_name, "--", $product_pic_dir, "--", $product_price;
                        $mysqli = new mysqli("localhost", "root", "den959glow487", "test1");
                        $mysqli->query("SET NAMES 'UTF8' ");
                        $sql = "INSERT INTO `test1`.`cart` (`user`, `product_id`, `product_name`, `product_pic_dir`,`price`,`num`,`buy_bool`, `product_description`, `buy_confirm`) VALUES ('" . $user_account . "', '" . $id . "', '" . $product_name . "', '" . $product_pic_dir . "', '" . $product_price . "', '1', '0', '" . $product_description . "', '0');";  //欲寫入的單項目
                        //insert into 資料表 value('值1','值2','值3');
                        $mysqli->query($sql); //寫入sql, 寫入資料表table
                        $mysqli ->close();   //關閉資料庫

                        //下方適用於確認是否存入此品項from
                        $mysqli = new mysqli("localhost", "root", "den959glow487", "test1");
                        $mysqli->query("SET NAMES 'UTF8' ");
                        $result3 = $mysqli->query('SELECT `product_id` FROM test1.`cart` where (`user` = "' . $user_account . '" and `product_id` = "' . $id . '")'); //抓table
                        $check_id_in_cart = '';
                        while ($row3 = mysqli_fetch_row($result3)) {
                            $check_id_in_cart = $row3[0];
                        }
                        $result3->close();
                        $mysqli->close();
                        if ($check_id_in_cart = $id) {
                            echo "<script>alert('已成功加入購物車！！(回到首頁)')</script>"; //彈窗
                            echo '<script>location.href = "./index.php";</script>';  //重置
                        } else {
                            echo "錯誤, 請重新選擇, 或請洽Administrator。";
                        }
                        //用於確認是否存入此品項to
                    }
                }
                ?>
            </form>
            <form method="get" enctype="multipart/form-data">
                <div id="may_interesteds">
                    <?php
                    $mysqli = new mysqli("localhost", "root", "den959glow487", "test1");
                    $mysqli->query("SET NAMES 'UTF8' ");
                    $result2 = $mysqli->query('SELECT * FROM test1.`products` ORDER BY RAND() limit 4'); //抓table
                    //row[0] id
                    //row[1] pic_name, no used now
                    //row[2] pic_dir
                    //row[3] product_name
                    //row[4] description
                    //row[5] price
                    //row[6] ori_price
                    while ($row2 = mysqli_fetch_row($result2)) {
                        echo '<a href="itemPage.php?id=' . $row2[0] . '" id="item2">';
                        echo '<div id="title2">' . $row2[3] . '</div>';
                        echo '<div id="picture2"><img src="' . $row2[2] . '" alt="' . $row2[2] . '" height=100px width=100px></div>';
                        echo '<div id="paragraph2">' . $row2[4] . '</div>';
                        echo '</a>';
                    }
                    $result2->close();   
                    $mysqli ->close();   //關閉資料庫
                    ?>
                </div>

                <footer>
                    <div id="author">
                        <p>本網站由德斯貿易公司(Desmo co.,lmt.)所有 Copy Right &copy; 2023</p>
                    </div>

                    <div id="cont"><a href="contact.php">Contact Us</a> </div>
                </footer>
            </form>
        </main>
    </div>
</body>
<span id="toTop"> <a href="#top"><img src="icon/arrow-up.svg" alt="" title="to top" height="35px" width="35px"></a></span>

</html>