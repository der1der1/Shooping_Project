<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <!-- session -->
            <?PHP
            //第一頁面
            session_start();
            $user = '';
            $_SESSION["user"] = $user;

            // 第二頁面
            $user = '';
            session_start();
            if (isset($_SESSION['user'])) {
                $user = $_SESSION["user"];
            }
            ?>
    <!-- post -->
            <form method="post" enctype="multipart/form-data"></form>
            <input type="text" id="search" name="search_name" placeholder="我想看看...冰箱?" title="您想尋找甚麼?">
            <?PHP
            if (isset($_POST['search_button'])) {
                $search_name = $_POST['search_name'];
                if (empty($search_name)) {                    //此劇判斷是否輸入內容，若為空則入此if
                    echo "<script>alert('未輸入查詢')</script>";
                    normal_read_products();}
            }
            ?>
    <!-- get Quaring string -->
            <?PHP
            //如果之前有點按過搜尋，則先取回搜尋session。
            $home_search_category = '';
            if (isset($_GET['home_search_category'])) {
                $home_search_category = $_GET['home_search_category'];
            }    ?>


            <form method="get" enctype="multipart/form-data">
                <?php
                $result2 = $mysqli->query('SELECT distinct `category` FROM test1.`products` ORDER BY RAND()'); //抓table
                while ($row2 = mysqli_fetch_row($result2)) {
                    echo '<li><a href="index.php?home_search_category=' . $row2[0] . '">' . $row2[0] . '</a></li>';
                }
                ?>
            </form>
    <!-- Picture translocation -->
            <?PHP
            // 上傳
            $file_arr1 = explode(".", $_FILES['file1']['name']);    //分離檔案名稱及副檔名
            $file_type1 = $file_arr1[count($file_arr1) - 1];    //取得副檔名
            //$file_arr[0] 是檔名; $file_arr[1]是附檔名
            $destination1 = "./pictureTarget/" . $file_arr1[0] . "." . $file_type1; // !!!指定儲存的資料夾!!!
            move_uploaded_file($_FILES['file1']['tmp_name'], $destination1);    //將上傳的檔案移至設定路徑
            ?>
    <!-- 資料庫 -->
            <?PHP
            if (isset($_POST['search_button'])) {
                $search_name = $_POST['search_name'];
                $mysqli = new mysqli("localhost", "root", "den959glow487", "test1"); //最後一個是資料庫Name
                $mysqli->query("SET NAMES 'UTF8' ");
                $result6 = $mysqli->query('SELECT * FROM test1.`products` where `category` = "' . $home_search_category . '" or `product_name` = "' . $home_search_category . '";'); }//抓table
                while ($row1 = mysqli_fetch_row($result1)) {
                    echo $row1[0];
                    for ($i = 1; $i <= 200; $i++) {
                        echo '&nbsp;';
                    } //產出每個$row[0] ad 之間的空格
                }
                $result6->close();   
                $mysqli ->close();
                // 新增
                $sql = "INSERT INTO `test1`.`pic_ads` (`name`, `img_dir`) VALUES ('" . $file_arr1[0] . "', '" . $destination1 . "');";
                $mysqli->query($sql);         //寫入sql, 寫入資料表table

                // 刪除
                $sql = "DELETE FROM `test1`.`word ads 2` WHERE (`id` = '" . $get_id . "');";  //欲刪除的單項目
                $mysqli->query($sql);         //寫入sql, 寫入資料表table

                // 修改
                $sql = "UPDATE `test1`.`products` SET `description`='" . $edit_description . "', `price`='" . $edit_price . "'  WHERE (`id` = '" . $get_id8 . "');";
                $mysqli->query($sql);
                // 插入
                $sql = "INSERT INTO `test1`.`products` (`pic_name`,`pic_dir`) VALUES ('" . $pic_name . "','" . $destination2 . "');";  //欲寫入的單項目
                $mysqli->query($sql);
            ?>
</body>

</html>