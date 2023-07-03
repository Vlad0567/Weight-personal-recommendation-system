<body>
    <?php
    include("check.php");
    ?>
    <div class="main"><?php
        $result = mysqli_query($connect,"select product.name, product.price, product.action from product, basket where product.id_p=basket.id_p and basket.id_u=" . $_SESSION['id_u']);
        if($result && $result->num_rows >= 1){?>
            <table id="table">
            <tr><th>№</th><th>Наименование</th><th>Цена</th><th>Cкидка</th><th>Итоговая стоимость</th></tr><?php
            $i=1;
            while($row = mysqli_fetch_row($result)){
                ?>
                <tr><td><?php echo $i?></td><td><?= $row[0]?></td><td><?= number_format($row[1],0, "", " ")   ." руб."?></td><td><?= $row[2] * 100 . "%"?></td><td><?= number_format( $row[1] * (1 - $row[2]),0, "", " ") . " руб."?></td></tr>
                <?php
                $i++;
            }?>
            <form action="/request.php" method=post>
                <button type="submit" id="busket" name="ivent" value=7>Купить</button>
                <input type="hidden" name="site" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
            </form><?php
        } else {
            echo "<br><br>Корзина пуста";
        }
        ?>
        </table><?php
        infobar();
        ?>
    </div>
</body>