<?php
//--- 채팅방 프로그램의 흐름 제어하는 controller(로그인, 틀린 로그인, 로그아웃, DB에 메시지 저장, 채팅방 나가기...)
include 'chatDB_info.php';
$proceed_query = new process();
date_default_timezone_set("Asia/Seoul");
$today = date('Y-m-d H:i');

//html 에서 ID, PW를 받아 목적에 맞게 사용..
$userID = $_GET['userID'];
$userPW = $_GET['userPW'];
$username = $_GET['username'];
$function = $_GET['function'];

//---mysqli 객체 생성, DB 접속
$chat_conn = new mysqli(chatDB_info::DB_url, chatDB_info::DB_host,
    chatDB_info::DB_PW, chatDB_info::DB_name);

//DB연결 예외처리
if ($chat_conn->connect_errno) {
    echo "Failed to connect to Mysql : " . $chat_conn->connect_error;
}

//로그인 정보가 담긴 Table 조회
$login_Result = $chat_conn->query("select * from user_info where userID='$userID'");

//로그인 기능
if ($function == "login") {

    while ($row = $login_Result->fetch_array(MYSQLI_BOTH)) {
        //아이디 일치할 때 (비밀번호 일치, 불일치)
        if ($userID == $row['userID']) {
            if ($userPW == $row['userPW']) {
                ini_set('memory_limit', -1);
                echo "<script>alert('로그인 성공 $today')</script>";
                $_SESSION['userID'] = $row['userID'];
                $_SESSION['username'] = $row['username'];
                echo "<script>location.href = './chat_main.php'</script>";
            } else {
                echo "<script>alert('비밀번호가 일치하지 않습니다(ID존재)')</script>";
                echo "<script>location.href = './chat_main.php'</script>";
            }
        }
    }
    //아예 없는 ID일 때
    if (isset($row) == null) {
        echo "<script>alert('등록된 ID가 없습니다')</script>";
        echo "<script>location.href = './chat_main.php'</script>";
    }
} //로그아웃 기능
elseif ($function == "logout") {
    session_destroy();
    echo "<script>location.href = './chat_main.php'</script>";
} elseif ($function == "join") {

    $joinID = $_GET['joinID'];
    $joinPW = $_GET['joinPW'];
    $joinNM = $_GET['joinNM'];

    if ($joinID == null || $joinPW == null || $joinNM == null) {
        echo "<script>alert('입력안한 항목 입력하십시오')</script>";
        echo "<script>location.href='./join.php'</script>";
    }


    $joinquery = $proceed_query->insert("user_info", "('','$joinID','$joinPW','$joinNM')");

    if (isset($joinquery)) {
        echo "<script>alert('가입성공! 로그인해주세요')</script>";
        echo "<script>location.href='./chat_main.php'</script>";
    }

} //채팅방 생성(처리 query -> room 추가,
elseif ($function == "chat_create") { //---query에 새 채팅방 정보 등록 후 list에 돌아감

    $room_name = $_GET['room_name'];
    $userID = $_GET['userID'];
    $room_number = $_GET['room_number'];
    //---채팅방 만들기 -> DB저장(방장 포함)
    $room_create = $chat_conn->query("insert into list values('','$room_name','$userID',1,'$today')");

    //멤버 정보를 member_check 테이블에 넣음
    $InsertMember = $chat_conn->query("insert into member_check values($room_number,'$userID')");

    if (isset($room_create)) {
        echo "<script>alert('채팅방 개설 완료')</script>";
        echo "<script>location.href='./chat_list.php?userID=$userID'</script>";
    }
} //채팅 입력
elseif ($function == "insert_chat") {
    //--채팅입력시 필요한 정보 받아옴
    $room_number = $_GET['room_number'];
    $send_message = $_GET['send_message'];
    $room_member = $_GET['room_member'];
    //---message 테이블에 저장
    $chat_conn->query("insert into message values($room_number,'$room_member','$send_message','')");
    echo "<script>location.href='./chat_room.php?room_number=$room_number&room_member=$room_member'</script>";
} //채팅방에서 나감
elseif ($function == "roomout") {
    $room_number = $_GET['room_number'];
    $room_member = $_GET['room_member'];

    //--- 방장 조회
    $master_inquiry = $chat_conn->query("select room_master from list where room_number=$room_number");
    $master_row = $master_inquiry->fetch_array(MYSQLI_BOTH);

    //--- 방장이 나가려고 하면, 방장 위임 절차 거침
    if ($room_member == $master_row[0]) {

        $inquiry = $chat_conn->query("select room_member from member_check where room_number= $room_number");
        $inquiry_row = $inquiry->fetch_array(MYSQLI_BOTH);

        $change_master = $chat_conn->query("update list set room_master = '$inquiry_row[0]' where room_number = $room_number");
    }

    //--- 나가기 전 퇴장 멘트
    $out_coment = $chat_conn->query("insert into message values($room_number,'$room_member','[$room_member] 님이 퇴장 하셨습니다','')");

    //--- 멤버 정보 삭제
    $delete_out = $chat_conn->query("delete from member_check where room_member = '$room_member'");

    //---채팅방의 멤버 수 조회 (select count)
    $membernum_check = $chat_conn->query("select count(room_member) from member_check where room_number=$room_number");
    $numResult = $membernum_check->fetch_array(MYSQLI_BOTH);
    $remain_member = $numResult[0];

    //채팅인원 바꿔 줌
    $decrease_member = $chat_conn->query("update list set room_member=$remain_member where room_number=$room_number");

    //멤버수가 0인 채팅방 삭제
    if ($remain_member == 0) {
        $room_delete = $proceed_query->delete("list","room_number","$room_number");
        echo "<script>alert('채팅방이 삭제되었습니다')</script>";
    }


    echo "<script>location.href='./chat_list.php?room_member=$room_member'</script>";
}
?>


