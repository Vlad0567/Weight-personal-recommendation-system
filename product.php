<html lang="ru">
<meta charset="UTF-8">
<?php
    include("check.php");
?>

<script>
    <?php
    $id_p = $_GET['id_p'];
    
    if(verify()){
        $_SESSION['id_p'] = $id_p;

        $result = mysqli_query($connect,"select category from product where id_p=$id_p;");
        $row = $result->fetch_assoc();
        mysqli_query($connect,"update preferences set " . $row['category'] . " = " . $row['category'] . " + 2 where id_u=" . $_SESSION['id_u']);
    } else {
        if(!isset($_SESSION['array'])){
            initarray();
        }

        $result = mysqli_query($connect,"select category from product where id_p=$id_p;");
        $row = $result->fetch_assoc();
        $result1 = mysqli_query($connect,"select ordinal_position as position from information_schema.columns where table_schema='electro' and table_name='preferences' and column_name='".$row['category']."';");
        $row1 = $result1->fetch_assoc();
        $row3 = $_SESSION['array'];
        $category = $row1['position'];
        $row3[$category] = $row3[$category] + 2;
        $_SESSION['array'] = $row3;

        if(!isset($_SESSION['history'])){
            inithistory();
            addhistory();
        } else {
            addhistory();
        }
    }
    ?>
</script>

<head>
    <script src="script.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script> starttime = new Date(); 
        var requestSent = false;
    </script>
</head>

<body beforeunload="myFunc()">
    <div class="main" tabindex="0"><?php
    $id_p=$_GET['id_p'];
    $_SESSION['id_p'] = $id_p;

    showname($id_p);
    showimage($id_p);
    showinfo($id_p);
    busketbutton($id_p);
    ratebutton($id_p);

    underproduct();

    infobar();
    
    ?>
    <script>  
        function myFunc(){
            endtime = new Date();

            var duration = endtime - starttime;
            
            $.ajax({
                url:'addDur.php',
                method:'POST',
                data:{
                    duration:endtime - starttime,
                }
            });
        }
    </script>
    </div>
</body>
</html>