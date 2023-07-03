<?php
session_start();
$connect = mysqli_connect('localhost','root','password','electro','3306');
$ivent =$_POST['ivent'];
$site =$_POST['site'];

switch($ivent){
    //Регистрация
    case 1:
    $FIO = $_POST['FIO'];
	$login =$_POST['login'];
    $password = $_POST['password'];
    $password1 = password_hash($password,PASSWORD_DEFAULT);
    mysqli_query($connect,"insert into Users values(NULL,'$FIO','$login','$password1',1)");
    
    $result = mysqli_query($connect,"select id_u from users where login='$login';");
    $row1 = $result->fetch_assoc();

    $row = $_SESSION['array'];
    $query = "insert into preferences values(NULL," .$row1['id_u'];
    for($i=3;$i<count($row);$i++){

        $query .= ", " .$row[$i];
    }
    $query .= ")";
    mysqli_query($connect,$query);
    break;
    
    //Авторизация
    case 2:
    $login =$_POST['login'];
    $password =$_POST['password'];
    $site = $_POST['site'];
    $result = mysqli_query($connect,"select * from users where login='$login';");
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['password'])){
        $_SESSION['id_u'] = $row['id_u'];
        $_SESSION['fio'] = $row['FIO'];
        $_SESSION['login'] = $login;
        $_SESSION['password']=$password;
        $_SESSION['role'] = $row['role'];
    }
    unset($_SESSION['history']);
    break;

    //Выход
    case 3:
    session_unset();
    session_destroy();
    break;

    //Поиск
    case 4:
    $search = $_POST['search'];
    $result = mysqli_query($connect,"select id_p from product where name LIKE '%$search%'");
    $row = $result->fetch_assoc();
    if ($result && $result->num_rows == 1)
    {
        $site = "/product.php?id_p={$row['id_p']}";
    } else {
        $_SESSION['query'] = "select * from product where name LIKE '%$search%'";
        $_SESSION['search'] =$search;
        $site = "/category.php";
    }
    break;

    //Положить в корзину
    case 5:
    mysqli_query($connect,"insert into basket values(NULL," .$_SESSION['id_u']."," .$_SESSION['id_p']. ");");
    $result = mysqli_query($connect,"select category from product where id_p=" .$_SESSION['id_p']);
    $row = $result->fetch_assoc();
    mysqli_query($connect,"update preferences set " . $row['category'] . " = " . $row['category'] . " + 2  where id_u=" . $_SESSION['id_u']);
    break;

    //Убрать из корзины
    case 6:
    mysqli_query($connect,"delete from basket where id_u=" .$_SESSION['id_u']." and id_p=" .$_SESSION['id_p']);
    $result = mysqli_query($connect,"select category from product where id_p=" .$_SESSION['id_p']);
    $row = $result->fetch_assoc();
    mysqli_query($connect,"update preferences set " . $row['category'] . " = " . $row['category'] . " - 6 where id_u=" . $_SESSION['id_u']);
    break;

    //Покупка
    case 7:
    $result = mysqli_query($connect,"select product.id_p, product.price, product.action,product.category from product, basket where product.id_p=basket.id_p and basket.id_u=" . $_SESSION['id_u']);
    while($row = mysqli_fetch_row($result)){
        mysqli_query($connect,"insert into purchase values (null," .$_SESSION['id_u'] ."," .$row[0]. "," .$row[1]. "," .$row[2]. "," .$row[1]*(1-$row[2]).",CURDATE() );");
        mysqli_query($connect,"update preferences set " . $row[3] . " = " . $row[3] . " - 30 where id_u=" . $_SESSION['id_u']);

        $result1= mysqli_query($connect,"select child from bond where parent='$row[3]'");
        while($row1 = mysqli_fetch_row($result1)){
            mysqli_query($connect,"update preferences set " . $row1[0] . " = " . $row1[0] . " + 15 where id_u=" . $_SESSION['id_u']);
        }
    }
    mysqli_query($connect,"delete from basket where id_u=" .$_SESSION['id_u']);
    break;

    // Отзыв
    case 8:
    $rate = $_POST['rate'];
    $id_p = $_SESSION['id_p'];
    mysqli_query($connect,"insert into rate values (null," .$_SESSION['id_u'] ."," .$id_p. "," .$rate. ")");
    break;
}

header("Location:" . $site);
exit;
?>