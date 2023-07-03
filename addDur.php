<?php
session_start();
$duration = $_POST['duration'];
$id_p = $_SESSION['id_p'];

if(isset($_SESSION['id_u'])){
    $id_u = $_SESSION['id_u'];

    mysqli_query($connect,"insert into visits values(NULL,'$id_u','$id_p','$duration')");

    $result = mysqli_query($connect,"select category from product where id_p=$id_p;");
    $row = $result->fetch_assoc();
    if($duration > 60000)
    {
        if($duration > 300000){
            mysqli_query($connect,"update preferences set " . $row['category'] . " = " . $row['category'] . " +1 where id_u=" . $_SESSION['id_u']);
        } else { 
            mysqli_query($connect,"update preferences set " . $row['category'] . " = " . $row['category'] . " - 1 where id_u=" . $_SESSION['id_u']);
        }
    } else {
        mysqli_query($connect,"update preferences set " . $row['category'] . " = " . $row['category'] . " - 2 where id_u=" . $_SESSION['id_u']);
    }
} else {
    mysqli_query($connect,"insert into visits values(NULL,0,'$id_p','$duration')");

    $result = mysqli_query($connect,"select category from product where id_p=$id_p;");
    $row = $result->fetch_assoc();
    $result1 = mysqli_query($connect,"select ordinal_position as position from information_schema.columns where table_schema='electro' and table_name='preferences' and column_name='".$row['category']."';");
    $row1 = $result1->fetch_assoc();
    $row3 = $_SESSION['array'];
    $category = $row1['position'];
    if($duration > 60000)
    {
        if($duration > 300000){
            $row3[$category] = $row3[$category] + 1;
            $_SESSION['array'] = $row3;
        } else { 
            $row3[$category] = $row3[$category] - 1;
        }
    } else {
        $row3[$category] = $row3[$category] - 2;
    }
    $_SESSION['array'] = $row3;
}
//unset($_SESSION['id_p']);
?>