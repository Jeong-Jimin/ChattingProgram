<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>~~~ Let's Chat ~~~</title>
</head>
<body>

<h1 style="text-align: center"><a href="./chat_main.php" style="text-decoration: none; color: #0e0e0e"><BR>~~~~~ 채팅 프로그램 ~~~~~<BR><BR></a></h1>

<div id="loginResult">
</div>

<div style="text-align: center; margin: 0px auto;">


    <form action="./controller.php" method="get" id="loginform">
        <p><BR>로그인 후 이용해 주십시오<BR><BR></p>
        <p>ID : <input type="text" name="userID"></p>
        <p>PW : <input type="password" name="userPW"></p>
        <input type='hidden' name='function' value='login'>
        <input type="submit" value="로그인" onclick="ajax()">
    </form>


        <form action="./join.php" method="get" id = "joinform">
            <input type="submit" value="회원가입">
        </form>

</div>
<BR>


<?php
ini_set('memory_limit', -1);

if (isset($_SESSION['userID'])) {
    echo "<script>document.getElementById('loginform').style.display = 'none'</script>";
    echo "<script>document.getElementById('joinform').style.display = 'none'</script>";

    echo("<div style='text-align: center; margin: 0px auto; width: 500px; height: 200px;'>");
    echo $_SESSION['userID'] . "님 환영합니다<BR><BR>";
    echo "이름 : " . $_SESSION['username'] . "<BR><BR>";
    echo "<form action='./controller.php' method='get'>";
    echo "<input type='hidden' name='function' value='logout'>";
    echo "<input type='submit' value='로그아웃'><BR><BR>";

    echo "</form>";
    echo "</div>";
}

$se_id = $_SESSION['userID'];
$se_name = $_SESSION['username'];


if ($se_id != null) {//--- list 출력 -> 로그인 되야 함

    echo "<form action='./chat_list.php' method='get' style='text-align: center'>";
    //채팅방목록 안에서 -> 개설 가능하도록
    echo "<input type='hidden' value='$se_id' name='room_member'>";
    echo "<input type='submit' style='border: none; background-color: cornflowerblue' value='채팅방 목록'>";
    echo "</form>";
}

?>

<script>
    function ajax() {
        $.ajax({
            url: "./controller.php",
            type: "get",
            data: $("form").serialize()
        }).done(function (data) {
            $("#loginResult").append(data);
        });
    }
</script>