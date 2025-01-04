<?php include 'conn.php';
session_start();

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $username_account = test_input($_POST['username']);
    $password_account = test_input($_POST['password']);
    $confirm_password_account = test_input($_POST['password-confirm']);
    $social_id = test_input($_POST['character']);
    $email = $_POST['email'];
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = str_replace(' ','',$data);
    $data = preg_replace("/[^a-zA-Z0-9]+/", "", $data);
    return $data;
}

// Query
$sql = "INSERT INTO account (login, password, social_id, email) VALUES ('$username_account',PASSWORD('$password_account'),'$social_id','$email')";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbaccount);

try{
    if (strlen($username_account) < 5 || strlen($username_account) > 12) {
        $errors[] = 'Login precisa conter no mínimo 5 e no máximo 12 caracteres';
    }

    if (strcmp($password_account, $confirm_password_account) !== 0) {
        $errors[] = 'As senha não coincidem';
    }

    if(strlen($password_account)< 5){
        $errors[] = 'A senha precisa conter no mínimo 5 e no máximo 12 caracteres';
    }

    if(strlen($social_id)<7){
        $errors[] = 'Senha do personagem precisa conter 7 caracteres';
    }
    if(count($errors) > 0){
        $_SESSION['errors'] = $errors;
        header('Location: index.php');
        exit();
    }

    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "Sua conta foi criada com sucesso!";
        header("location: index.php");
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
    mysqli_close($conn);
}catch (Exception $e) {
    echo 'Exceção capturada: ',  $e->getMessage(), "\n";
}
?>