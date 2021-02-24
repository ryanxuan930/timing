<?php
include_once("C:\wamp64\www\hsnua3\database.php");
$account = $_POST["account"];
$password = $_POST["password"];
$code = $_POST["code"];
$conn->select_db("hsnua3_main");
$stmt = $conn->prepare("SELECT * FROM admin WHERE account=?");
$stmt->bind_param("s",$account);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows){
    while($row = $result->fetch_assoc()){
        $hash = $row["password"];
    }
    if(password_verify($password,$hash)){
        if($code=="110mrth"){
            session_start();
            $_SESSION["account"]=$account;
            echo '
            <script>
            alert("歡迎登入");
            location.href="../timer.php";
            </script>
            ';
        }else{
            echo '
            <script>
            alert("代碼錯誤");
            location.href="../index.html";
            </script>
            ';
        }
    }else{
        echo '
        <script>
        alert("密碼錯誤");
        location.href="../index.html";
        </script>
        ';
    }
}else{
    echo '
    <script>
    alert("帳號錯誤");
    location.href="../index.html";
    </script>
    ';
}
?>