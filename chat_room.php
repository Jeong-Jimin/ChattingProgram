<?php
//--- 각각의 채팅 room 안
include './chatDB_info.php';

//---mysqli 객체 생성, DB 접속
$chat_conn = new mysqli(chatDB_info::DB_url, chatDB_info::DB_host,
    chatDB_info::DB_PW, chatDB_info::DB_name);

$room_number     =      $_GET['room_number'];
$userID          =      $_GET['room_member'];
$room_member     =      $_SESSION['userID'];
$room_master     =      $row['room_master'];

//채팅방 조회하는 query문
$inquiry_room = $chat_conn->query("select * from list where room_number=$room_number");
$row = $inquiry_room->fetch_array(MYSQLI_BOTH);

$room_name = $row['room_name'];
$member_query = $chat_conn->query("select room_member from member_check where room_number=$room_number");

//채팅방에 정보 표시
echo "<BR><BR><table border='2' style='text-align: center; margin: 0px auto'>";
echo "<tr>";
echo "<td width='100'>채팅방 명</td>";
echo "<td width='500'>$room_name</td>";
echo "</tr>";
echo "<tr>";
echo "<td width='100'>참가인원</td>";
echo "<td width='500'>";
while ($row = $member_query->fetch_array(MYSQLI_BOTH)) {
    echo $row['room_member'] . " ♡ ";
}
echo "</td>";
echo "</tr>";
echo "</table><BR><BR>";


//현재 접속한 사람이 방장이 아니고, 채팅방에 새로 입장한 사람일 때 인원++
$member_query = $chat_conn->query("select room_member from member_check where room_number=$room_number and room_member='$userID'");
$row = $member_query->fetch_array(MYSQLI_BOTH);

if ($row[0] == null) {

    $member_check = $chat_conn->query("insert into member_check values($room_number,'$userID')");
    $roomIn_coment = $chat_conn->query("insert into message values($room_number,'$room_member','[$room_member] 님이 입장 하셨습니다','')");

    if (isset($member_check)) {
        //채팅방 인원수 업데이트
        $count_query = $chat_conn->query("select count(room_member) from member_check where room_number=$room_number");
        $count_row = $count_query->fetch_array(MYSQLI_BOTH);
        $chat_conn->query("update list set room_member = $count_row[0] where room_number=$room_number");
    }

    $delete_query = $chat_conn->query("delete from member_check where room_member=''");
    $delete_query2 = $chat_conn->query("delete from message where userID=''");
}


$send_query = $chat_conn->query("select * from message where room_number = $room_number");

echo "<table style='text-align: center; margin: 0px auto;'>";
echo "<tr>";
echo "<td width='500'>";
while ($row = $send_query->fetch_array(MYSQLI_BOTH)) {
    echo $row['userID'] . "  )  " . $row['message'] . "<BR>";
}
echo "</td>";
echo "</tr>";
echo "</table><BR><BR>";


//--- 채팅 입력 창
echo "<form action='./controller.php' method='get' style='margin: 0px auto; text-align: center'>";
echo "<input type='text' name='send_message' style='width: 500px'>&nbsp";
echo "<input type='hidden' name='function' value='insert_chat'>";
echo "<input type='hidden' name='room_number' value='$room_number'>";
echo "<input type='hidden' name='room_member' value='$room_member'>";
echo "<input type='submit' value='입력'>";
echo "</form><BR>";


echo "<table style='margin: 0px auto; text-align: center'>";
echo "<tr>";

echo "<td>";
echo "<form action='./controller.php' method='get'style='margin: 0px auto; text-align: center'>";
echo "<input type='submit' value='로그아웃'>";
echo "<input type='hidden' name='function' value='logout'>";
echo "</form>";
echo "</td>";

echo "<td>";
echo "<form action='./chat_list.php' method='get'style='margin: 0px auto; text-align: center'>";
echo "<input type='submit' value='목록보기'>";
echo "</form>";
echo "</td>";

echo "<td>";
echo "<form action='./controller.php' method='get'style='margin: 0px auto; text-align: center'>";
echo "<input type='submit' value='채팅방 나가기'>";
echo "<input type='hidden' name='function' value='roomout'>";
echo "<input type='hidden' name='room_number' value='$room_number'>";
echo "<input type='hidden' name='room_member' value='$room_member'>";
echo "</form>";
echo "</td>";
?>