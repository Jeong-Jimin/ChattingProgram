<title>chat_list</title>
<?php
include 'chatDB_info.php';

ini_set('memory_limit', -1);
date_default_timezone_set("Asia/Seoul");
$today = date('Y-m-d H:i');
//---mysqli 객체 생성, DB 접속
$chat_conn = new mysqli(chatDB_info::DB_url, chatDB_info::DB_host,
    chatDB_info::DB_PW, chatDB_info::DB_name);

$query = new process(); //--- 쿼리 처리용

$userID = $_GET['room_member'];

echo "<BR><BR>";
echo "<div style='margin: 0px auto; text-align: center; border: solid'><h2>사용자 ID : $userID </h2></div><BR>";

$list_Result =$chat_conn->query("select * from list");

echo "<table style='text-align: center; margin: 0px auto;' border='1'>";
echo "<tr>";
echo "<td width='100'>채팅방 번호</td>";
echo "<td width='200'>채팅방 이름</td>";
echo "<td width='100'>채팅방 방장</td>";
echo "<td width='100'>채팅방 인원</td>";
echo "<td width='200'>채팅 개설일</td>";
echo "</tr>";
echo "</table>";


//--- 채팅방 list 테이블
while ($row = $list_Result->fetch_array(MYSQLI_BOTH)) {
    echo "<table style='text-align: center; margin: 0px auto'>";
    echo "<tr>";
    echo "<td width='100'>" . $row['room_number'] . "</td>";
    $room_number = $row['room_number'];
    echo "<td width='200'><a href='./chat_room.php?room_number=$room_number&room_member=$userID'style='text-decoration: none;'>" . $row['room_name'] . "</a></td>";
    echo "<td width='100'>" . $row['room_master'] . "</td>";
    echo "<td width='100'>" . $row['room_member'] . "</td>";
    echo "<td width='200'>" . $row['room_date'] . "</td>";
    echo "</tr>";
    echo "</table>";
}

echo "<BR><BR>";

//--- 채팅방 만들기
echo "<h3 style='text-align: center; margin: 0px auto'>채팅방 만들기</h3>";
echo "<form action='./controller.php' method='get' style='text-align: center; margin: 0px auto'>";
echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input type='text' name='room_name'>&nbsp";
echo "<input type='hidden' name='userID' value='$userID'>";
echo "<input type='hidden' name='room_number' value='$room_number'>";
echo "<input type='hidden' name='function' value='chat_create'>";
echo "<input type='submit' value='개설!'>&nbsp";
echo "</form>";

//--- 로그아웃 폼
echo "<BR><form action='controller.php' style='text-align: center; margin: 0px auto;'>";
echo "<input type='submit' value='로그아웃'>";
echo "<input type='hidden' name='function' value='logout'>";
echo "</form>";



?>