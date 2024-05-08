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

//檢查此帳號是否有權限
$mysqli = new mysqli("localhost", "root", "den959glow487", "test1");
$mysqli->query("SET NAMES 'UTF8' ");
$result1 = $mysqli->query("SELECT * FROM test1.`user` WHERE (`account` = '" . $user . "');");
$prvilige = '';
while ($row1 = mysqli_fetch_row($result1)) {
    $prvilige = $row1[3];
}
//只有權限為A即才可使用
if ($prvilige != "A") {
    echo "<script>alert('you're NOT ADMINISTRATOR, loading rejected.');</script>"; //彈窗
    echo '<script>location.href = "./index.php";</script>';  //重置
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EditingMode</title>
    <!-- 將 CSS 文件連結到 HTML -->
    <link rel="stylesheet" href="edit.css">
</head>

<body>
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

            <nav id="tool">
                <div id="home">
                    <a href="index.php"><img src="icon/house-solid.svg" class="icon" alt="go to homepage" width="20px" height="20px"></a>
                </div>
                <form id="searchBar" action="/search" method="get">
                    <label id="label" for="search">搜索Bar:</label>
                    <input type="text" id="search" name="q" placeholder="我想看看...冰箱?" height="10px" title="您想尋找甚麼?">
                    <input type="image" src="icon/GO.png" height="40px" width="40px" value="搜索">
                </form>
                <div id="cart">
                    <a href="check.php"><img src="icon/cart-shopping-solid.svg" class="icon" alt="cart icon" width="20px" height="20px" title="go to cart"><span></span></a>
                    <?PHP
                    if (empty($user)) {
                        echo "請登入，以使用購物車。";
                    } else {
                        echo 'Hi~ &nbsp; &nbsp;' . $user;
                    }
                    ?>
                </div>
            </nav>
        </header>

        <main>
            <form method="post" enctype="multipart/form-data">
                <div id="ads_edit">
                    <!-- 文字廣告 -->
                    <h2>文字廣告</h2>
                    <div id="wordspart">

                        <div id="editing">
                            <p id="editword">以下是資料庫內的廣告詞，請在右側指定修改。</p>
                            <input type="text" class="Words_Ads" placeholder=" id? " name="get_id1">
                            <input type="text" class="Words_Ads" placeholder=" 廣告文修改成...(約40字)" name="wordchange">
                            <input type="submit" title="儲存編輯文字" placeholder="儲存修改" name="edit">
                        </div>
                        <div id="delete">
                            <p id="editword">以下是資料庫內的廣告詞，請在右側指定刪除。</p>
                            <input type="text" class="Words_Ads" placeholder=" id? " name="get_id2" id="deletetext">
                            <input type="submit" title="刪除" placeholder="刪除" name="delete">
                        </div>
                        <div id="adding">
                            <p id="editword">請新增欲插入的 id , 並寫入您想要的新廣告詞。</p>
                            <input type="text" class="Words_Ads" placeholder=" id? [不得重複] " name="get_id3">
                            <input type="text" class="Words_Ads" placeholder=" 新增廣告詞...(約40字)" name="addtext">
                            <input type="submit" title="儲存編輯文字" placeholder="儲存修改" name="adding">
                        </div>
                        <?php
                        //廣告文display
                        $mysqli = new mysqli("localhost", "root", "den959glow487", "test1");
                        $mysqli->query("SET NAMES 'UTF8' ");
                        $sql = 'SELECT * FROM test1.`word ads 2` ORDER BY id ASC';    //抓廣告文的table
                        $result = $mysqli->query($sql);
                        while ($row = mysqli_fetch_row($result)) {
                            echo '<div id="word_part">';
                            echo "id=" . $row[0] . ", 文字 =【" . $row[1] . "】";
                            echo '</div>';
                        }
                        $result->close();
                        $mysqli ->close();   //關閉資料庫
                        ?>
                        <?PHP
                        //修改、刪除 & 新增的介面                        
                        //修改
                        if (isset($_POST['edit'])) {
                            $mysqli = new mysqli("localhost", "root", "den959glow487", "test1"); //最後一個是資料庫Name
                            $mysqli->query("SET NAMES 'UTF8' ");
                            $get_id = $_POST['get_id1']; //因無法取得變數，必須要字串，所以要自己打；用於抓取要修改的該row
                            $modify = $_POST['wordchange']; //修改的內容
                            //欲寫入的單項
                            $sql = "UPDATE `test1`.`word ads 2` SET `words` = '" . $modify . "' WHERE (`id` = '" . $get_id . "');";
                            $mysqli->query($sql);         //寫入sql, 寫入資料表table
                            echo '<script>location.href = "./edit.php";</script>';  //重置
                            $mysqli ->close();   //關閉資料庫
                        };
                        //刪除
                        if (isset($_POST['delete'])) {
                            $mysqli = new mysqli("localhost", "root", "den959glow487", "test1"); //最後一個是資料庫Name
                            $mysqli->query("SET NAMES 'UTF8' ");
                            $get_id = $_POST['get_id2'];
                            $sql = "DELETE FROM `test1`.`word ads 2` WHERE (`id` = '" . $get_id . "');";  //欲刪除的單項目
                            //DELETE FROM `資料庫`.`資料表` WHERE (`序號` = '5');
                            $mysqli->query($sql);         //寫入sql, 寫入資料表table
                            echo '<script>location.href = "./editinModeDatabase.php";</script>';  //重置
                            $mysqli ->close();   //關閉資料庫
                        };
                        //新增
                        if (isset($_POST['adding'])) {
                            //再開資料庫
                            $mysqli = new mysqli("localhost", "root", "den959glow487", "test1"); //最後一個是資料庫Name
                            $mysqli->query("SET NAMES 'UTF8' ");
                            $get_id = $_POST['get_id3'];
                            $words = $_POST['addtext'];
                            $sql = "INSERT INTO `test1`.`word ads 2` (`id`, `words`) VALUES ('" . $get_id . "', '" . $words . "');";  //欲寫入的單項目
                            //insert into 資料表 value('值1','值2','值3');
                            $mysqli->query($sql);         //寫入sql, 寫入資料表table
                            echo '<script>location.href = "./edit.php";</script>';  //重置
                            $mysqli ->close();   //關閉資料庫
                        };
                        ?>

                    </div>

                    <!-- 圖片廣告 -->
                    <h2>圖片廣告</h2>
                    <div id="pic_edit">
                        <div id="pic_edit_new">
                            <h3>新增廣告圖片</h3>
                            <input type="file" name="file1">
                            <input type="submit" name="upLoadPicture" value="上傳圖片(1000px x 200px)">
                        </div>
                        <div id="pic_edit_del">
                            <h3>刪除廣告圖片</h3>
                            <input type="text" name="get_id5" placeholder="id plz~">
                            <input type="submit" name="deletePicture" value="刪除圖片">
                        </div>
                    </div>

                    <?php
                    $mysqli = new mysqli("localhost", "root", "den959glow487", "test1");
                    $result = $mysqli->query("select * from `test1`.`pic_ads`");

                    echo "<div id='Pic_part'>";
                    // 展示廣告圖片
                    while ($row = mysqli_fetch_row($result)) {
                        echo "<div>";
                        echo "<h2>pictur id =" . $row[0] . "</h2>";
                        echo "<h2>picture name: " . $row[1] . "</h2>";
                        echo "<img src='" . $row[2] . "'  style=' width: 1000px;height: 200px; '";
                        echo "</div>";
                    }
                    echo "</div>";
                    $result->close();   
                    $mysqli ->close();   //關閉資料庫

                    //新增圖片
                    if (isset($_POST['upLoadPicture'])) {
                        $mysqli = new mysqli("localhost", "root", "den959glow487", "test1");
                        $mysqli->query("SET NAMES 'UTF8' ");

                        // 上傳
                        $file_arr1 = explode(".", $_FILES['file1']['name']);    //分離檔案名稱及副檔名
                        $file_type1 = $file_arr1[count($file_arr1) - 1];    //取得副檔名
                        //$file_arr[0] 是檔名; $file_arr[1]是附檔名
                        $destination1 = "./pictureTarget/" . $file_arr1[0] . "." . $file_type1; // !!!指定儲存的資料夾!!!
                        move_uploaded_file($_FILES['file1']['tmp_name'], $destination1);    //將上傳的檔案移至設定路徑

                        //post用於input要直接寫進mySql，如果物件已在php中處理過，則直接寫變數到sql式中。
                        $sql = "INSERT INTO `test1`.`pic_ads` (`name`, `img_dir`) VALUES ('" . $file_arr1[0] . "', '" . $destination1 . "');";
                        $mysqli->query($sql);         //寫入sql, 寫入資料表table
                        echo '<script>location.href = "./edit.php";</script>';  //重置
                        $mysqli ->close();   //關閉資料庫
                    };

                    //刪除圖片 
                    if (isset($_POST['deletePicture'])) {
                        $getid5 = $_POST['get_id5'];
                        //1.刪除資料夾的圖片
                        // unlink('檔案名稱.jpg'); it's useless 
                        //再開資料庫，取得要刪除的圖面的名稱用
                        $mysqli = new mysqli("localhost", "root", "den959glow487", "test1");
                        $mysqli->query("SET NAMES 'UTF8' ");
                        $result1 = $mysqli->query("SELECT `img_dir` from `test1`.`pic_ads` where id = '" . $getid5 . "' ");
                        echo "<img src='" . $row[0] . "' width = '30%' height='30%'";
                        unlink('' . $row[0] . '');

                        //2.再刪除資料庫的
                        $result2 = $mysqli->query("DELETE FROM `test1`.`pic_ads` WHERE (`id` = '" . $getid5 . "');");         //寫入sql, 寫入資料表table
                        echo '<script>location.href = "./edit.php";</script>';  //重置
                        $result->close();   
                        $mysqli ->close();   //關閉資料庫
                    };
                    ?>
                </div>

                <div id="items">

                    <!-- 編輯產品按鈕與輸入區 -->
                    <h2>修改產品</h2>
                    <div style="margin-bottom: 10px; border: 3px solid gray; border-radius: 7px;">
                        <div id="editing" style="margin-bottom: 10px; background-color: white; border-radius: 7px; height: 50px; padding:10px;">
                            <p id="editword">修改前指定ID</p>
                            <input type="text" placeholder=" id? " name="get_id6">
                            <input type="submit" title="以ID查詢您的商品" value="查詢商品" name="edit2">
                        </div>
                        <?PHP //本同一階層有3組 isset_post, 分別是1.修改前指定ID、2.僅修改文字、3.加上修改圖片
                        //row[0] id
                        //row[1] pic_name, no used now
                        //row[2] pic_dir
                        //row[3] product_name
                        //row[4] description
                        //row[5] price
                        //row[6] ori_price
                        //row[7] category
                        //row[8] selected
                        $row3_0 = '';  //需先建立空變數，到迴圈時再存單一值的陣列，否則將顯示錯誤
                        $row3_1 = '';
                        $row3_2 = '';
                        $row3_3 = '';
                        $row3_4 = '';
                        $row3_5 = '';
                        $row3_6 = '';
                        $row3_7 = '';

                        if (isset($_POST['edit2'])) {
                            $getid6 = $_POST['get_id6'];
                            $mysqli = new mysqli("localhost", "root", "den959glow487", "test1");
                            $result3 = $mysqli->query("select * from `test1`.`products` where(`id` = '" . $getid6 . "');");
                            while ($row3 = mysqli_fetch_row($result3)) {
                                $row3_0 = $row3[0];  //須將陣列存入變數中，迴圈外才可繼續使用!!!
                                $row3_1 = $row3[1];
                                $row3_2 = $row3[2];
                                $row3_3 = $row3[3];
                                $row3_4 = $row3[4];
                                $row3_5 = $row3[5];
                                $row3_6 = $row3[6];
                                $row3_7 = $row3[7];
                            }
                        }
                        echo '<div id="editing" style=" background-color: #cbcbcb; border-radius: 7px; height: 90px; padding:10px;">';
                        echo '<p id="editword">以ID指定修改。</p>';
                        echo '<input type="file" name="file3">';
                        echo '<img src="' . $row3_2 . '" alt="" height="70px" width="70px">';
                        echo '<input type="text" value="' . $row3_0 . '" name="product_ID">商品ID';
                        echo '<input type="text" value="' . $row3_3 . '" name="product_name1">商品名稱';
                        echo '<input type="text" value="' . $row3_4 . '" name="description1">商品描述(約80字)';
                        echo '<input type="text" value="' . $row3_5 . '" name="price">售價(打折後)';
                        echo '<input type="text" value="' . $row3_6 . '" name="ori_price">原價';
                        echo '<input type="text" value="' . $row3_7 . '" name="category">類別';
                        echo '<input type="submit" name="edit_only_text" value="確認(不改圖)">';
                        echo '<input type="submit" name="edit_with_pic"  value="確認(改圖)  ">';
                        echo '</div>';

                        //修改-無換圖  
                        if (isset($_POST['edit_only_text'])) {
                            $mysqli = new mysqli("localhost", "root", "den959glow487", "test1");
                            $mysqli->query("SET NAMES 'UTF8' ");
                            $get_id8 = $_POST['product_ID']; //因無法取得變數，必須要字串，所以要自己打；用於抓取要修改的該row
                            $edit_product_name = $_POST['product_name1']; //修改商品名
                            $edit_description = $_POST['description1']; //修改文字敘述
                            $edit_price = $_POST['price']; //修改售價
                            $edit_ori_price = $_POST['ori_price']; //修改原價
                            $edit_category = $_POST['category']; //修改類別
                            //欲寫入的單項
                            $sql = "UPDATE `test1`.`products` SET `product_name`='" . $edit_product_name . "', `description`='" . $edit_description . "', `price`='" . $edit_price . "',`ori_price`='" . $edit_ori_price . "',`category`='" . $edit_category . "'  WHERE (`id` = '" . $get_id8 . "');";
                            $mysqli->query($sql);
                            $mysqli ->close();   //關閉資料庫
                            echo '<script>location.href = "./edit.php";</script>';  //重置
                        };

                        //修改-有換圖
                        if (isset($_POST['edit_with_pic'])) {
                            $mysqli = new mysqli("localhost", "root", "den959glow487", "test1");
                            $mysqli->query("SET NAMES 'UTF8' ");
                            //處理圖片路徑
                            $file_arr2 = explode(".", $_FILES['file3']['name']);    //分離檔案名稱及副檔名
                            $file_type2 = $file_arr2[count($file_arr2) - 1];    //取得副檔名
                            $destination3 = "./pictureTarget/" . $file_arr2[0] . "." . $file_type2; // !!!指定儲存的資料夾!!!
                            move_uploaded_file($_FILES['file3']['tmp_name'], $destination3);    //將上傳的檔案移至設定路徑
                            // echo "<script>alert('" . $destination3 . "')</script>";
                            $get_id8 = $_POST['product_ID']; //因無法取得變數，必須要字串，所以要自己打；用於抓取要修改的該row
                            $edit_product_name = $_POST['product_name1']; //修改商品名
                            $edit_description = $_POST['description1']; //修改文字敘述
                            $edit_price = $_POST['price']; //修改售價
                            $edit_ori_price = $_POST['ori_price']; //修改原價
                            //欲寫入的單項
                            $sql = "UPDATE `test1`.`products` SET `pic_dir`='" . $destination3 . "', `product_name`='" . $edit_product_name . "', `description`='" . $edit_description . "', `price`='" . $edit_price . "',`ori_price`='" . $edit_ori_price . "' WHERE (`id` = '" . $get_id8 . "');";
                            $mysqli->query($sql);
                            echo '<script>location.href = "./edit.php";</script>';  //重置
                            $mysqli ->close();   //關閉資料庫
                        };
                        ?>

                    </div>
                    <div id="delete" style="margin-bottom: 10px; background-color: white; border: 3px solid gray; border-radius: 7px;
                    height: 50px; padding:10px;">
                        <p id="editword">以ID指定刪除。</p>
                        <input type="text" placeholder=" id? " name="get_id7">
                        <input type="submit" title="刪除" placeholder="刪除" name="delete2">
                    </div>

                    <div id="adding" style="margin-bottom: 10px; background-color: #cbcbcb; border: 2px solid black; border-radius: 7px;
                    height: 50px; padding:10px;">
                        <p id="editword">新增產品</p>
                        <input type="text" placeholder="圖片名稱" name="pic_name">
                        <input type="file" name="file2">
                        <input type="text" placeholder="商品名稱" name="product_name">
                        <input type="text" placeholder="商品描述(約80字)" name="description2">
                        <input type="text" placeholder="售價(折後價)" name="price2">
                        <input type="text" placeholder="原價" name="ori_price2">
                        <input type="text" placeholder="類別" name="category2">
                        <input type="submit" name="upLoadProducts" value="確認新增">
                    </div>

                    <?PHP
                    //修改、刪除 & 新增的介面                        
                    //刪除
                    if (isset($_POST['delete2'])) {
                        $mysqli = new mysqli("localhost", "root", "den959glow487", "test1");
                        $mysqli->query("SET NAMES 'UTF8' ");
                        $get_id7 = $_POST['get_id7'];
                        $sql = "DELETE FROM `test1`.`products` WHERE (`id` = '" . $get_id7 . "');";  //欲刪除的單項目
                        $mysqli->query($sql);         //寫入sql, 寫入資料表table
                        echo '<script>location.href = "./edit.php";</script>';  //重置
                        $mysqli ->close();   //關閉資料庫
                    };

                    //新增
                    if (isset($_POST['upLoadProducts'])) {
                        $mysqli = new mysqli("localhost", "root", "den959glow487", "test1");
                        $mysqli->query("SET NAMES 'UTF8' ");
                        // 圖片處理，最終獲得destination2
                        $file_arr = explode(".", $_FILES['file2']['name']);    //分離檔案名稱及副檔名
                        $file_type = $file_arr[count($file_arr) - 1];    //取得副檔名
                        //$file_arr[0] 是檔名; $file_arr[1]是附檔名
                        $destination2 = "./pictureTarget/" . $file_arr[0] . "." . $file_type; // !!!指定儲存的資料夾!!!
                        move_uploaded_file($_FILES['file2']['tmp_name'], $destination2);    //將上傳的檔案移至設定路徑

                        $pic_name = $_POST['pic_name'];
                        $product_name = $_POST['product_name'];
                        $description2 = $_POST['description2'];
                        $price2 = $_POST['price2']; //修改售價
                        $ori_price2 = $_POST['ori_price2']; //修改原價
                        $category2 = $_POST['category2']; //修改類別
                        $sql = "INSERT INTO `test1`.`products` (`pic_name`,`pic_dir`,`product_name`, `description`, `price`, `ori_price`, `category`) VALUES ('" . $pic_name . "','" . $destination2 . "','" . $product_name . "','" . $description2 . "', '" . $price2 . "','" . $ori_price2 . "', '" . $category2 . "');";  //欲寫入的單項目
                        $mysqli->query($sql);
                        echo '<script>location.href = "./edit.php";</script>';  //重置
                        $mysqli ->close();   //關閉資料庫
                    };

                    ?>
                    <div id="item_here">
                        <!--  展示 Take PHP to show all products -->
                        <?PHP
                        $mysqli = new mysqli("localhost", "root", "den959glow487", "test1");
                        $result = $mysqli->query("select * from `test1`.`products`");
                        while ($row2 = mysqli_fetch_row($result)) {
                            echo "<div class='item'>";
                            echo "<div id='showID'> id: " . $row2[0] . " </div>";
                            echo "<div id='Pic'><img src='" . $row2[2] . "' Title='" . $row2[3] . "' alt='" . $row2[1] . "' width='155px' height='155px'></div>";
                            echo "<div id='product_name'>" . $row2[3] . "</div>";
                            echo "<div id='description'>" . $row2[4] . "</div>";
                            echo "<div id='price'>價格 $ = " . $row2[5] . "</div>";
                            echo "<div id='ori_price'>原始價格 $ = " . $row2[6] . "</div>";
                            echo "<div id='category'>類別:" . $row2[7] . "</div>";
                            echo "</div>";
                        }
                        $result->close();   
                        $mysqli ->close();   //關閉資料庫
                        ?>
                    </div>
                </div>
            </form>
        </main>
        <footer>
            <div id="author">
                <p>本網站由德斯貿易公司(Desmo co.,lmt.)所有 Copy Right &copy; 2023</p>
            </div>
            <div id="cont"><a href="contact.php">Contact Us</a> </div>
        </footer>
    </div>
</body>

</html>