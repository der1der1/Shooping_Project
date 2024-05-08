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
$prvilige = "B";
if (isset($_SESSION['prvilige'])) {
    $prvilige = $_SESSION["prvilige"];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <!-- 將 CSS 文件連結到 HTML -->
    <link rel="stylesheet" href="contact.css">
    <!-- 將 JS  文件連結到 HTML -->
    <script src="contact.js"></script>
</head>

<body id="top">
    <div id="contener">

        <header>
            <h1>與我們聯絡 Contact us</h1>
        </header>
        <main>
            <div id="choicies" class="choiciesclass">
                <button id="mail_us" class="mail_us"><a href="mailto: deniel87deniel87@gmail.com">Mail to Us</a></button>
                <button id="message_local" class="message_local" onclick="message_card_shows()">Message on Local</button>
            </div>

            <!--email 如果以登入會直接帶入 -->
            <div id="message_card" class="message_card">
                <form method="post" enctype="multipart/form-data">
                    <div id="zone1">
                        <div><input type="text" id="name" name="name" placeholder="輸入姓名* Name plz"></div>
                        <div><input type="submit" name="submit" id="submit" value="送出 Submit"></div>
                    </div>

                    <div id="zone2">
                        <div><input type="text" id="phone" name="phone" placeholder="聯絡電話 Phone Num"></div>
                        <?PHP
                        if (empty($user) == false) {
                            echo '<div><input type="text" id="mail" name="mail" value="' . $user . '"></div>';
                        } else {
                            echo '<div><input type="text" id="mail" name="mail" placeholder="電子郵件* E-mail"></div>';
                        }
                        ?>
                    </div>
                    <div id="zone3">
                        <textarea name="text" id="text" cols="20" rows="15" placeholder="聯繫內容(請少於300中文字)* Content (less than 600 words)"></textarea>
                    </div>
                </form>
            </div>
            <?PHP
            if (isset($_POST['submit'])) {
                //先檢查是有有空白值
                $user_name = $_POST['name'];
                $user_phone = $_POST['phone'];
                $user_mail = $_POST['mail'];
                $text = $_POST['text'];
                if (empty($user_name)) {
                    echo "<script>alert('請填寫大名 plz enter your name');</script>";
                    echo "<script>message_card_shows()</script>";  //alert之後頁面會重置，需回到此頁面。
                } elseif (empty($user_mail)) {
                    echo "<script>alert('請填寫電子信箱 plz enter your email');</script>";
                    echo "<script>message_card_shows()</script>";  //alert之後頁面會重置，需回到此頁面。
                } elseif (empty($text)) {
                    echo "<script>alert('請填寫聯絡內容 plz enter your contents');</script>";
                    echo "<script>message_card_shows()</script>";  //alert之後頁面會重置，需回到此頁面。
                } else {

                    //確定沒有空白值後才可繼續下面；再開資料庫
                    $mysqli = new mysqli("localhost", "root", "den959glow487", "test1");
                    $mysqli->query("SET NAMES 'UTF8' ");
                    $sql = "INSERT INTO `test1`.`mail_us` (`name`,`phone`,`email`, `message`) VALUES ('" . $user_name . "','" . $user_phone . "','" . $user_mail . "','" . $text . "');";  //欲寫入的單項目
                    $mysqli->query($sql);
                    $mysqli ->close();   //關閉資料庫
                    echo "<script>alert('已送出，返回首頁。');</script>";
                    echo '<script>location.href = "./index.php";</script>';  //回到首頁
                }
            }
            ?>
        </main>
        <footer>
            <?PHP
            if ($prvilige == 'A') {
                echo '<div id="editing_page"><a href="edit.php">go to Editing Page</a> </div>';
            } else {
                echo '<div id="home_page"><a href="index.php">HOME</a></div>';
            }
            ?>
            <div id="author">
                <p>本網站由德斯貿易公司(Desmo co.,lmt.)所有 Copy Right &copy; 2023</p>
            </div>
        </footer>
    </div>
</body>

</html>
<?PHP

?>