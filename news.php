<body>
    <?php
    include("check.php");
    ?>
    <script>
        <?php
        $news = $_GET['id_new'];
        $result = mysqli_query($connect,"select * from news where id_n=$news;");
        $row = $result->fetch_assoc();
        $category = $row['category'];
        if(isset($_SESSION['news1'])){
            if(verify()){
                mysqli_query($connect,"update preferences set $category =  $category  + 3 where id_u=" . $_SESSION['id_u']);
            } else {            
                $result1 = mysqli_query($connect,"select ordinal_position as position from information_schema.columns where table_schema='electro' and table_name='preferences' and column_name='$category';");
                $row1 = $result1->fetch_assoc();
                $row3 = $_SESSION['array'];
                $category = $row1['position'];
                $row3[$category] = $row3[$category] + 3;
                $_SESSION['array'] = $row3;
            }
            unset($_SESSION['news1']);
        }
        ?>
    </script>

    <div class="main"><?php
        $news = $_GET['id_new'];
        $result = mysqli_query($connect,"select * from news where id_n=$news;");
        $row = $result->fetch_assoc();
        ?>
        <div class=newspage>
            <img src="img/news<?=$row['id_n']?>.jpg">
            <?php
            $text = $row['text'];
            $paragraphs = explode("\n", $text);
            ?>
            <div class = "newspage1"><?php
                foreach ($paragraphs as $paragraph) {
                    ?><p><?php echo $paragraph ?></p><?php
                }
                ?>
            </div>
        </div>
        <?php
        infobar();?>
    </div>
</body>
</html>