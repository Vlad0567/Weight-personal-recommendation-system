<body>
    <?php
    include("check.php");
    ?>
    <div class="main">
        <?php
        $result = mysqli_query($connect,"select product.name, purchase.price,purchase.action,purchase.end_price,purchase.date from purchase,product where product.id_p=purchase.id_p and purchase.id_u=" .$_SESSION['id_u']);
        if($result && $result->num_rows >= 1){?>
            <table id="table">
            <tr><th>№</th><th>Наименование</th><th>Цена</th><th>Cкидка</th><th>Итоговая стоимость</th><th>Дата</th></tr><?php
            $i=1;
            while($row = mysqli_fetch_row($result)){
                ?>
                <tr><td><?php echo $i?></td><td><?= $row[0]?></td><td><?= number_format($row[1],0, "", " ")   ." руб."?></td><td><?= $row[2] * 100 . "%"?></td><td><?= number_format($row[3],0, "", " ") . " руб."?></td><td><?= $row[4]?></td></tr>
                <?php
                $i++;
            }?>
            </table><?php
        } else {
            echo "<br><br>Корзина пуста";
        }
        infobar();
        ?>
    </div>
</body>