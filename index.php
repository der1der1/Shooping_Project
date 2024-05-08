<!-- 
Owner: Der1 謝德一
date : 3/28, 2024
_________________
The project takes me about 3 months to produce, and finally completed.
But for the days during haveing a bored job, the project would be done more earlier.
After finished, i realize that the bad habits procrastinate me.
So what's important is to quit the bad behavior, such as late sleeping, and late wakeing up.
____________________________________________________________________________________________

    introduction of the project
 
    the project was made to simulate a Shopping Web. 
    For improveing my coding skill in what a short duration, i decided to make this one.
    Classification tree:
        index____login    .php
              |__itemPage .php
              |__check    .php
              |__pay      .php
              |__list     .php
              |__contact  .php
              |__edit     .php
    
    an user with privilige classification:
        privilige_____A___access to every page
                   |__B___restricted:_____list
                                       |__edit
        if one wants to get the A privilige, entering "admin./" previous to the account during Sign Up.
        Next time it can get the A privilige, and no need to add "admin./", only account name is allowed.
_____________________________________________________________________________________________________________        

    Key WebSite Skill:
    1. $_session[];
        session is the most cleavest tool for cache memory, for recording products after run other pages.
        The session can call back the target.
        what's important is to "Keep the User on Logging in state"!
    2. location.href = "address.php ? id= 1,2,3, etc. "
        referenced to an address with a "? " tag, connected to a target id, the page runs the topic for the id.
        there're lots of products in the mySQL database, engineer Can't coding a new page for every product.
        address with a "? " tag is the best way to solve the problem.
    3. if(isset($_POST['123'])) in While{every single product}
        set "if(isset($_POST['123']))" in While circurt, allows us to control every product in While circurt.
        so we need no asigning ids to every product, just useing the row[array] to the Name tag, it goes work.
    4. move_uploaded_file($_FILES[][], $destination);
        Read, new, update, & delete are 4 main function in php for mySQL. 
        But there is a problem that, How to upload and store a picture?
        After try and error again and again, take Picture Shifting package in php is the best choice to store picture. 
    5. searcing skill on youtube, chatGPT, and blogs, and so on.
        tens of thousands of information on the web, every owner has its own solusion. it always not the solution for us.
        even it's not, but still like the solusion. don't be confused. And search more.


    ____________________________________________________________________________________________________________________
                                            \/       Acknowledgement        \/
    ____________________________________________________________________________________________________________________

      Thanks my wife, she lead me ahead and taught me at the very begining. makes me strengh to run such this projuct.
    ____________________________________________________________________________________________________________________
-->



<?PHP
//如果之前有點按過搜尋，則先取回搜尋session。
$home_search_category = '';
if (isset($_GET['home_search_category'])) {
    $home_search_category = $_GET['home_search_category'];
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

//取得權限等級。若session已取得則視其權限給予；若無則先設B級。
$prvilige = '';
if (isset($_SESSION['prvilige'])) {
    $prvilige = $_SESSION["prvilige"];
    if (empty($prvilige)) {
        $prvilige = 'B';
    }
}

//檢查是否有暫存要存到購物車的商品(from login from itemPage to Cart)
$interested_product;
if (empty($_SESSION["interested_product"])) {
} else {
    $interested_product = $_SESSION["interested_product"];
    echo '<script>location.href = "itemPage.php?id="' . $interested_product . '";</script>';
}

?>

<!DOCTYPE html>
<html lang="en">
<!-- https://medium.com/javascript%E5%88%9D%E5%BF%83%E8%80%85%E8%B8%8F%E9%9B%AA%E5%B0%8B%E6%A2%85/%E7%94%A8%E7%B0%A1%E5%96%AE%E7%9A%84-html-%E5%AF%A6%E7%8F%BE%E8%B7%91%E9%A6%AC%E7%87%88%E6%95%88%E6%9E%9C-41195af17a80 -->
<!-- 上面那個是製作跑馬燈的教學網站 -->
<!-- http://localhost/Shooping_Project/ -->
<!-- 懶人直接貼網址 -->
<!-- https://youtu.be/NvPNtscRHhY?si=QscFyPhwXSDVPtFs -->
<!-- 製作點選新id頁面的影片 -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopit</title>
    <!-- import of Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- import of Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- 將 CSS 文件連結到 HTML -->
    <link rel="stylesheet" href="index.css">
    <!-- 將 JS  文件連結到 HTML -->
    <script src="index.js"></script>
</head>

<body id="top">
    <div id="contener">
        <header class="col-pc-12 col-mobile-12">
            <nav id="run">
                <?php
                //把MySQL的跑馬燈AD叫出來
                //開資料庫
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

            <form method="post" enctype="multipart/form-data">
                <nav id="tool">
                    <div id="home">
                        <a href="index.php"><img src="icon/house-solid.svg" class="icon" title="home page" alt="go to homepage"></a>
                    </div>
                    <?PHP
                    //如果user權限是A級，即顯示所有人購買清單連結。
                    if ($prvilige == 'A') {
                        echo '
                    <div id="check_list">
                        <a href="list.php"><img src="icon/check-list.png" class="icon" title="go to check list" alt="go to check list" width="20px" height="20px"></a>
                    </div>
                    ';
                    }
                    ?>

                    <!-- 搜索區 -->
                    <div id="searchBar">
                        <label id="label" for="search">搜索Bar:</label>
                        <input type="text" id="search" name="search_name" placeholder="我想看看...冰箱?" height="10px" title="您想尋找甚麼?">
                        <input type="submit" name="search_button" value="搜索" title="搜索">
                    </div>

                    <!-- 購物車 -->
                    <div id="cart">
                        <a href="check.php"><img src="icon/cart-shopping-solid.svg" class="icon" alt="cart icon" title="go to cart"><span></span></a>
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
            </form>
        </header>


        <main>
            <!-- 把Aside和Content放在middbody內 -->
            <div id="middbody" class="ROW">
                <!-- 小螢幕，顯示的aside Icon-->
                <button onclick="aside_fold()" id="aside_fold">
                    <img src="icon/menuburger.png" title="選擇ㄉ展開" width="70px" height="50px">
                </button>
                <!-- 小螢幕，點開顯示的aside-->

                <!-- 普通螢幕，顯示的aside(小螢幕點開顯示) -->
                <aside id="aside" class="col-pc-2 col-mobile-12">
                    <?PHP
                    //登入後出現訊息icon。
                    //取出訊息
                    if ($user != empty($user)) {
                        $mysqli = new mysqli("localhost", "root", "den959glow487", "test1");
                        $mysqli->query("SET NAMES 'UTF8' ");
                        $result4 = $mysqli->query('SELECT `info0`, `info1`, `info2` FROM test1.`user` where `account` = "' . $user_account . '" ;');
                        $info0 = '';
                        $info1 = '';
                        $info2 = '';
                        while ($info_row = mysqli_fetch_row($result4)) {
                            $info0 = $info_row[0];
                            $info1 = $info_row[1];
                            $info2 = $info_row[2];
                        }
                        $result4->close();
                        $mysqli->close();   //關閉資料庫
                        //將調出的info放入訊息欄位。
                        if (empty($info0)) {
                        } else {
                            echo '
                            <div id="infos" style="margin:0 0 20px 0;">
                                <h5 style="margin:0 0 10px 0;">您的訊息：</h5>
                                <div id="info0" class="info">最新！<br>' . $info0 . '</div>
                                <div id="info1" class="info">' . $info1 . '</div>
                                <div id="info2" class="info">' . $info2 . '</div>
                            </div>   
                            ';
                        }
                    }
                    ?>
                    <!-- 訊息框結束 -->

                    <h4>產品分類：</h4>
                    <div id="Products">
                        <ul>
                            <form method="get" enctype="multipart/form-data">
                                <?php
                                //廣告文display
                                //再開資料庫
                                $mysqli = new mysqli("localhost", "root", "den959glow487", "test1");
                                $mysqli->query("SET NAMES 'UTF8' ");
                                $result2 = $mysqli->query('SELECT distinct `category` FROM test1.`products` ORDER BY RAND()'); //抓table
                                while ($row2 = mysqli_fetch_row($result2)) {
                                    echo '<li><a href="index.php?home_search_category=' . $row2[0] . '">' . $row2[0] . '</a></li>';
                                }
                                $result2->close();
                                $mysqli->close();   //關閉資料庫
                                ?>
                            </form>
                        </ul>
                    </div>
                </aside>

                <div id="contents" class="col-pc-10 col-mobile-12">
                    <!-- 下分三大類greatPromotion、interested、normal -->
                    <div id="greatPromotion" >
                        <!-- Retrieved the div code from Bootstrap -->
                        <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <div class="carousel-item active" data-bs-interval="4000">
                                    <img src="./img/ad2.png" class="d-block w-100" height="255px">
                                </div>
                                <div class="carousel-item" data-bs-interval="4000">
                                    <img src="./img/ad3.png" class="d-block w-100" height="255px">
                                </div>
                                <div class="carousel-item" data-bs-interval="4000">
                                    <img src="./img/ad4.png" class="d-block w-100" height="255px">
                                </div>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                    <div id="interested">
                        <div id="interested_title">
                            <p>推薦你可能也喜歡!</p>
                        </div>

                        <div id="items">
                            <?php
                            //隨機選擇5項產品做為推薦商品
                            $mysqli = new mysqli("localhost", "root", "den959glow487", "test1");
                            $mysqli->query("SET NAMES 'UTF8' ");
                            $result3 = $mysqli->query('SELECT * FROM test1.`products` ORDER BY RAND() limit 5'); //抓table
                            //row[0] id
                            //row[1] pic_name, no used now
                            //row[2] pic_dir
                            //row[3] product_name
                            //row[4] description
                            //row[5] price
                            //row[6] ori_price
                            //row[7] category
                            //row[8] selected
                            while ($row3 = mysqli_fetch_row($result3)) {
                                echo '<a href="itemPage.php?id=' . $row3[0] . '">';
                                echo '<button id="item" height: "210px" width: "180px">';
                                echo '<img src="' . $row3[2] . '" alt="" title="優質特賣" width="110px" height="110px">';
                                echo '<div id="level1">';
                                echo '<div id="stars"><img src="icon/star-solid.svg" alt="" height="15px" width="15"></div>';
                                echo '<div id="stars"><img src="icon/star-solid.svg" alt="" height="15px" width="15"></div>';
                                echo '<div id="stars"><img src="icon/star-solid.svg" alt="" height="15px" width="15"></div>';
                                echo '<div id="stars"><img src="icon/star-solid.svg" alt="" height="15px" width="15"></div>';
                                echo '<div id="stars"><img src="icon/star-stroke.svg" alt="" height="15px" width="15"></div>';
                                echo '<div id="stars"><img src="icon/star-regular.svg" alt="" height="15px" width="15"></div>';
                                echo '</div>';
                                echo '<div id="level2">';
                                echo '<img src="icon/dollar.svg" alt="" height="15px" width="15">';
                                echo '<div id="price1">' . $row3[6] . '</div>';
                                echo '<img src="icon/arrow-right.png" alt="" height="15px" width="15">';
                                echo '<img src="icon/dollar.svg" alt="" height="15px" width="15">';
                                echo '<div id="price">' . $row3[5] . '</div>';
                                echo '</div>';
                                echo '<div id="intro">' . $row3[4] . '</div>';
                                echo '</button>';
                                echo '</a>';
                            }
                            $result3->close();
                            $mysqli->close();   //關閉資料庫
                            ?>
                        </div>
                    </div>

                    <div id="normal">
                        <?php //1.先檢查並執行右欄分類；2.在檢查上方站內搜尋；3.若皆無再執行原應出現的所有物件else。                        
                        if (empty($home_search_category) == false) {  //判讀$home_search_category不是空的，就要執行類別查詢
                            $mysqli = new mysqli("localhost", "root", "den959glow487", "test1"); //最後一個是資料庫Name
                            $mysqli->query("SET NAMES 'UTF8' ");
                            $result6 = $mysqli->query('SELECT * FROM test1.`products` where `category` = "' . $home_search_category . '" or `product_name` = "' . $home_search_category . '";'); //抓table

                            //如果搜尋無此品項
                            if (mysqli_num_rows($result6) === 0) {
                                echo "<script>alert('很抱歉無此品項，請嘗試使用其他關鍵字。')</script>"; //彈窗
                                echo '<script>location.href = "./index.php";</script>';  //然後就重置
                            }
                            while ($row6 = mysqli_fetch_row($result6)) {
                                //row[0] id
                                //row[1] pic_name, no used now
                                //row[2] pic_dir
                                //row[3] product_name
                                //row[4] description
                                //row[5] price
                                //row[6] ori_price
                                //row[7] category
                                //row[8] selected
                                echo '<a href="itemPage.php?id=' . $row6[0] . '">';
                                echo '<button id="oneProduct" height: "120px" width: "240px">';
                                echo '<div id="pic">';
                                echo '<img src="' . $row6[2] . '" alt="" title="' . $row6[3] . '" width="110px" height="110px">';
                                echo '</div>';
                                echo '<div id="description">';
                                echo '<div id="title">' . $row6[3] . '</div>';
                                echo '<div id="level">';
                                echo '<div id="stars"><img src="icon/star-solid.svg" alt="" height="15px" width="15"></div>';
                                echo '<div id="stars"><img src="icon/star-solid.svg" alt="" height="15px" width="15"></div>';
                                echo '<div id="stars"><img src="icon/star-solid.svg" alt="" height="15px" width="15"></div>';
                                echo '<div id="stars"><img src="icon/star-stroke.svg" alt="" height="15px" width="15"></div>';
                                echo '<div id="stars"><img src="icon/star-regular.svg" alt="" height="15px" width="15"></div>';
                                echo '<img src="icon/dollar.svg" alt="" height="15px" width="15">';
                                echo '<div id="price">' . $row6[5] . '</div>';
                                echo '</div>';
                                echo '<div id="intro">' . $row6[4] . '</div>';
                                echo '</div>';
                                echo '</button>';
                                echo '</a>';
                            }
                            $result6->close();
                            $mysqli->close();   //關閉資料庫
                        }
                        if (isset($_POST['search_button'])) {
                            $search_name = $_POST['search_name'];
                            if (empty($search_name)) {                    //此劇判斷是否輸入內容，若為空則入此if
                                echo "<script>alert('未輸入查詢')</script>";
                                echo "<script>alert('即將返回...')</script>";
                                normal_read_products();
                            } else {
                                // 使用網址後"?"連結並抓取儲存的查詢，並借用上面asid 的 if 跑出搜尋結果。
                                echo '<script>location.href = "./index.php?home_search_category=' . $search_name . '";</script>';  //重置
                            }
                        }
                        //若無使用搜尋功能，則普通列出。
                        if (empty($home_search_category)) {
                            normal_read_products(); //函式放在網頁最後面
                        }
                        ?>

                    </div>
                </div>
            </div>
        </main>
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

// 普通讀取商品
function normal_read_products()
{
    $mysqli = new mysqli("localhost", "root", "den959glow487", "test1");
    $mysqli->query("SET NAMES 'UTF8' ");
    $result5 = $mysqli->query('SELECT * FROM test1.`products` ORDER BY RAND()'); //抓table
    //to再開資料庫    
    //row[0] id
    //row[1] pic_name, no used now
    //row[2] pic_dir
    //row[3] product_name
    //row[4] description
    //row[5] price
    //row[6] ori_price
    while ($row5 = mysqli_fetch_row($result5)) {
        echo '<a href="itemPage.php?id=' . $row5[0] . '">';
        echo '<button id="oneProduct" height: "120px" width: "240px">';
        echo '<div id="pic">';
        echo '<img src="' . $row5[2] . '" alt="" title="' . $row5[3] . '" width="110px" height="110px">';
        echo '</div>';
        echo '<div id="description">';
        echo '<div id="title">' . $row5[3] . '</div>';
        echo '<div id="level">';
        echo '<div id="stars"><img src="icon/star-solid.svg" alt="" height="15px" width="15"></div>';
        echo '<div id="stars"><img src="icon/star-solid.svg" alt="" height="15px" width="15"></div>';
        echo '<div id="stars"><img src="icon/star-solid.svg" alt="" height="15px" width="15"></div>';
        echo '<div id="stars"><img src="icon/star-stroke.svg" alt="" height="15px" width="15"></div>';
        echo '<div id="stars"><img src="icon/star-regular.svg" alt="" height="15px" width="15"></div>';
        echo '<img src="icon/dollar.svg" alt="" height="15px" width="15">';
        echo '<div id="price">' . $row5[5] . '</div>';
        echo '</div>';
        echo '<div id="intro">' . $row5[4] . '</div>';
        echo '</div>';
        echo '</button>';
        echo '</a>';
    }
    $result5->close();
    $mysqli->close();   //關閉資料庫  
}
?>