<?php


echo "<form action='./controller.php' method='get'>";
echo "아이디 : <input type='text' name='joinID'>&nbsp;";
echo "비밀번호 : <input type='text' name='joinPW'>";
echo "이름 : <input type='text' name='joinNM'>";
echo "<input type='hidden' name='function' value='join'>&nbsp;";
echo "<input type='submit' value='가입!'>";
echo "</form>";


?>