<body>
    <?php
    include("check.php");
    ?>
    <script><?php
        if(isset($_POST['category'])){
            $category =$_POST['category'];
            if(verify()){
                mysqli_query($connect,"update preferences set $category =  $category  + 5 where id_u=" . $_SESSION['id_u']);
            } else {
                $result1 = mysqli_query($connect,"select ordinal_position as position from information_schema.columns where table_schema='electro' and table_name='preferences' and column_name='$category';");
                $row1 = $result1->fetch_assoc();
                $row3 = $_SESSION['array'];
                $category = $row1['position'];
                $row3[$category] = $row3[$category] + 5;
                $_SESSION['array'] = $row3;
            }
        }?>
    </script>


    <div class="main">
        <?php
        if(isset($_POST['category'])) {
            $category =$_POST['category'];
            $_SESSION['category'] = $category;
            $title = catname($category,2);
            $query = "select product.id_p,product.name,product.price,product.manufacturer,product.action,product.category,round(avg(rate.rate),2) rate from product,rate where category='$category' and product.id_p=rate.id_p group by product.id_p;";
            blockprint($title,$query);
        } else {
            $search = $_SESSION['search'];
            $query = $_SESSION['query'];
            $title = 'Результаты вашего поиска по слову "' . $search. '"';
            listprint($title,$query);
            unset($_SESSION['search']);
            unset($_SESSION['query']);
        }
        weightper();
        infobar();
        ?>
    </div>
</body>
</html>