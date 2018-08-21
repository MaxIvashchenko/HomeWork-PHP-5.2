<?php
session_start(); 
require_once('./vendor/autoload.php');

$loader = new Twig_Loader_Filesystem('./templates');
$twig = new Twig_Environment($loader, array(
  'cache' => './tmp/cache',
  'auto_reload' => true,
));
$templateParams = [
  'userId' => '',
  'isEdit' => false,
  'editedId' => '',
  'editedDescription' => '',
  'usersDataOptionsHtml' => '',
  'taskData' => [],
  'assignedData' => [],
];

$username = 'root';
$password = '';
$pdo = new PDO('mysql:host=localhost;dbname=test;charset=utf8', $username, $password);
if(!empty($_POST)) {
    $login = ($_POST['login']);
    $password = ($_POST['password']);
    $query = $pdo->prepare('SELECT login, password FROM user WHERE login = ?');
    $query->execute([$login]);
    $userData = $query->fetch();
}
// Вход на сайт
if (isset($_POST['sign_in'])) {
   if ($userData && password_verify($_POST['password'], $userData['password'])) {
       $_SESSION['login'] = $login;
       header('Location: list.php');
   } else {
       echo 'Ошибка! Неверные данные.';
   }
}
// Регистрация
if (isset($_POST['sign_up'])) {
    if ($userData == false) {
    $reg = $pdo->prepare('INSERT INTO  user (login, password) VALUES (?,?)');
    $reg->execute([$login, $password]);
    $_SESSION['login'] = $login;
    header('Location: list.php');
    } else { 
        echo 'Такой пользователь уже есть!';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Домашнее задание к лекции 5.2
</title>
<link href='css/style.css' rel='stylesheet' type='text/css' >
</head>
<body>
   <?php
  echo $twig->render('registration.twig.html');
?>
</body>
</html>
