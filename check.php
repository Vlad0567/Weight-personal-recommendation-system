<?php
session_start();

$connect = mysqli_connect('localhost','root','password','electro','3306');

if(!$connect){
    die('Ошибка при подключении к Базе данных...');
}

include("shablon.php");


//Выводит инфобар с рейтингом категорий
function infobar(){
  if(verify()){
    global $connect;
    $result1 = mysqli_query($connect,"select * from preferences where id_u=" .$_SESSION['id_u']. ";");
    while ($row1 = mysqli_fetch_row($result1)) {
        $row[] = $row1;
    }
    
    ?>
    <div class="infobar">
    <table id="infotable">
    <tr><th>ID_U</th><th>Ноут</th><th>Стир</th><th>Холод</th><th>Телеф</th><th>Прист</th><th>Мышка</th><th>Плита</th><th>Кофе</th><th>Утюг</th><th>Телев</th><th>Планшет</th><th>Камера</th></tr>
    <tr>
    <?php
    for ($i = 1; $i < count($row[0]); $i++) {?>
      <td><?= $row[0][$i];?></td>
      <?php
    }
  } else {
    if(!isset($_SESSION['array'])){
      initarray();
    } else {
      $row = $_SESSION['array'];
    }
    ?>
    <div class="infobar">
    <table id="infotable">
    <tr><th>ID_U</th><th>Ноут</th><th>Стир</th><th>Холод</th><th>Телеф</th><th>Прист</th><th>Мышка</th><th>Плита</th><th>Кофе</th><th>Утюг</th><th>Телев</th><th>Планшет</th><th>Камера</th></tr>
    <tr>
    <?php
    $row = $_SESSION['array'];
    for ($i = 2; $i < count($row); $i++) {?>
      <td><?= $row[$i];?></td>
      <?php
    }
  }
?></tr></table></div><?php
}

// Создание локального массива для незарегистрированных пользователей
function initarray(){
  global $connect;
  $result1 = mysqli_query($connect, "select count(*) as count from information_schema.columns where table_schema = 'electro' and table_name = 'preferences';");
  $row1 = $result1->fetch_assoc();
  $row = array();
  $count = $row1['count'] ;
  for($i=0;$i<=$count;$i++){
    $row[$i] = 0;
  }
  $_SESSION['array'] = $row;
}

// Создание локального массива истории посещений для незарегистрированных пользователей
function inithistory(){
  $history = array();
  for ($i = 1; $i <= 9; $i++) {
    $history[$i] = 0;
  }
  $_SESSION['history'] = $history;
}

// Запись посещений для незарегистрированных пользователей
function addhistory(){
 $history = $_SESSION['history'];
  if($history[1] != $_SESSION['id_p']) {
    $times = count($history);
    for($i=2;$i<count($history);$i++){
      if($history[$i]==$_SESSION['id_p']){
        $times =$i;
      }
    }

    for($i = $times;$i>1;$i--){
      $history[$i] = $history[$i-1];
    }
    $history[1]= $_SESSION['id_p'];
    $_SESSION['history'] = $history;
  }
}

//Разделы под товаром на странице продукта
function underproduct(){
  ?><div class="underproduct"><?php
  weightper();
  viewed();
  ?>
  </div><?php
}


function findLargestIndexes($array, $start, $end, $count) {
  // Отсекаем лишние элементы массива
  $array = array_slice($array, $start, $end - $start + 1);
  
  // Получаем индексы отсортированных элементов по убыванию
  arsort($array);
  
  // Берем первые $count элементов
  $topIndexes = array_slice(array_keys($array), 0, $count);
  
  // Смещаем индексы обратно к исходному массиву
  $shiftedIndexes = array_map(function($index) use ($start) {
      return $index + $start;
  }, $topIndexes);
  
  return $shiftedIndexes;
}

//проверяет массив на наличие 5 элементов > 0
function checkmorezero($arr) {
  $count = 0;
  
  foreach ($arr as $element) {
      if ($element > 0) {
          $count++;
      }
      
      if ($count >= 5) {
          return true;
      }
  }
  
  return false;
}

//Вывод персональных рекомендуемых товаров по рейтингу категорий 
function weightper(){
  global $connect;

  //Запись в массив предпочтений
  if(verify()){
    $result = mysqli_query($connect,"select * from preferences where id_u=" .$_SESSION['id_u']. ";");
    $row = $result->fetch_row();
    $data[] = $row;
    $data = $data[0];
  } else {
    if(!isset($_SESSION['array'])){
      initarray();
    }
    $data = $_SESSION['array'];
    $result = mysqli_query($connect,"select * from preferences");
  }

  if(checkmorezero($data)){

    //Проверка игнорируемой категории
    if(isset($_SESSION['category'])){  
      $ignorecat = $_SESSION['category'];
      unset($_SESSION['category']);
    } elseif(isset($_GET['id_p'])){
      $result3 = mysqli_query($connect,"select category FROM product WHERE id_p =".$_GET['id_p']);
      $row3 = $result3->fetch_assoc();
      $ignorecat = $row3['category'];
    }

    //Сортировка по убыванию индексов
    $startIndex = 2;
    $endIndex = $result->field_count;
    $topCount = 5;

    $largestIndexes = findLargestIndexes($data, $startIndex, $endIndex, $topCount);

    //Нахожение имён категорий с наибольшими предпочтениями
    if(verify()){
      for($i=0;$i<5;$i++){
        $result1 = mysqli_query($connect,"select COLUMN_NAME as name FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'electro' AND TABLE_NAME = 'preferences' AND ordinal_position = ".$largestIndexes[$i]+1);
        $row3 = $result1->fetch_assoc();
        $catrow[$i] = $row3['name'];
      }
    } else {
      for($i=0;$i<5;$i++){
        $result1 = mysqli_query($connect,"select COLUMN_NAME as name FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'electro' AND TABLE_NAME = 'preferences' AND ordinal_position = ".$largestIndexes[$i]);
        $row3 = $result1->fetch_assoc();
        $catrow[$i] = $row3['name'];
      }
    }
    
    // Вывод трех товаров из категории наибольшего предпочтения и наибольшего рейтинга
    if(!isset($_GET['id_p'])){
      $result = mysqli_query($connect,"select product.id_p, product.name, product.price, product.manufacturer, product.action, product.category, round(avg(rate.rate),2) rate from product,rate where product.id_p=rate.id_p and product.category='" .$catrow[0]. "' group by id_p order by avg(rate.rate) DESC LIMIT 3;");
    } else {
      $result = mysqli_query($connect,"select product.id_p, product.name, product.price, product.manufacturer, product.action, product.category, round(avg(rate.rate),2) rate from product,rate where product.id_p=rate.id_p and product.id_p<>".$_GET['id_p']." and product.category='" .$catrow[0]. "' group by id_p order by avg(rate.rate) DESC LIMIT 3;");
    }?>

    <p>Рекомендуем вам</p>
    <div class="blockproduct">
      <div class="otstup"></div><?php
      while($row = $result->fetch_assoc()){?>
        <div class="blockproduct1">
          <a href ="product.php?id_p=<?php echo $row["id_p"]?>" method=post>
            <img src="img/<?=stripcslashes($row["id_p"])?>.jpg" style="width:200;height:150;" >
            <p2><?php echo catname($row["category"],1); ?></p2>
            <p><?php echo $row["manufacturer"] ." ". $row["name"] ."<br>" ?></p>
            <?php $end_price = $row["price"] * (1 - $row["action"]);?>
            <p1><?php echo number_format($end_price, 0, "."," ") ." руб." ?></p1>
            <p3><?php echo $row['rate'] . "*"?></p3>
          </a>
        </div><?php
      }
      ?><?php

      $countshow = 0;
      // Вывод кнопок категорий 2-4 места наибольшего предпочтения 
      for($i=1;$i<=4;$i++){
        if($catrow[$i] != $ignorecat){
          if($countshow<3) {
            ?>
            <div class="catbutton">
            <form action ="/category.php" method=post>
            <button type="submit" id="regvam" method=post name="category" value=<?php echo $catrow[$i]?>>
            <img src="img/<?=stripcslashes($catrow[$i])?>.jpg">
            <div class="text-block">
              <h4><?php echo catname($catrow[$i],2)?></h4>
            </div>
            </button>
            </form>
            </div><?php
          }
          $countshow++;
        }
      }
    ?>
    </div><?php
  }
} 


//Блок товаров "Вы смотрели"
function viewed() {
  if(verify()){
    $query = "select product.id_p,product.name,product.price,product.manufacturer,product.action, product.category, round(avg(rate.rate),2) rate from product,visits, rate where product.id_p=visits.id_p  and product.id_p=rate.id_p and visits.id_u=".$_SESSION['id_u']." group by visits.id_p order by max(visits.id_v) desc limit 9;";
    $title = "Вы просматривали";
    blockprint($title,$query);
  } else {
    if(!isset($_SESSION['history'])){
      inithistory();
    }
    $history = $_SESSION['history'];
    if($history[1]!=0){
      $diap = '';
      for($i=1;$i<=count($history);$i++){
          $diap .= $history[$i]. ",";
      }
      $diap = rtrim($diap, ',');
      $query = "select product.id_p, product.name, product.price, product.manufacturer, product.action, product.category, round(avg(rate.rate), 2) as rate from product join rate ON product.id_p = rate.id_p WHERE product.id_p IN ($diap) GROUP BY product.id_p, product.name, product.price, product.manufacturer, product.action, product.category ORDER BY FIELD(product.id_p, $diap);";
      $title = "Вы просматривали";
      blockprint($title,$query);
    }
  }
}

// Вывод блоков с товарами
function blockprint($title,$query) {
  global $connect;
  $result = mysqli_query($connect, $query);

  if ($result->num_rows > 0){?>
    <p><?php echo $title ?></p>
    <div class="blockproduct">
      <div class="otstup"></div><?php

      while($row = $result->fetch_assoc()){?>
        <div class="blockproduct1">
          <a href ="product.php?id_p=<?php echo $row["id_p"]?>" method=post>
            <img src="img/<?=stripcslashes($row["id_p"])?>.jpg" style="width:200;height:150;" >
            <p2><?php echo catname($row["category"],1); ?></p2>
            <p><?php echo $row["manufacturer"] ." ". $row["name"] ."<br>" ?></p>
            <?php $end_price = $row["price"] * (1 - $row["action"]);?>
            <p1><?php echo number_format($end_price, 0, "."," ") ." руб." ?></p1>
            <p3><?php echo "★" . $row['rate']?></p3>
          </a>
        </div><?php
      }?>
    </div><?php
  }
}

//Выводит на всю страницу товары
function listprint($title,$query) {?>
  <p><?php echo $title ?></p>
  <?php
  global $connect;
  $result = mysqli_query($connect, $query);

  if ($result->num_rows > 0){?>
    <div class="listproduct">
      <div class="otstup"></div><?php

      while($row = $result->fetch_assoc()){?>
        <div class="listproduct1">
          <a href ="product.php?id_p=<?php echo $row["id_p"]?>" method=post>
            <img src="img/<?=stripcslashes($row["id_p"])?>.jpg" style="width:200;height:150;" >
            <p2><?php echo catname($row["category"],1); ?></p2>
            <p><?php echo $row["manufacturer"] ." ". $row["name"] ."<br>" ?></p>
            <?php $end_price = $row["price"] * (1 - $row["action"]);?>
            <p1><?php echo number_format($end_price, 0, "."," ") ." руб.<br>" ?></p1>
          </a>
        </div><?php
      }
    ?>
    </div><?php
  }
}

function catname($category,$num){
  global $connect;
  $result = mysqli_query($connect,"select name from catname where category='$category' and number='$num';");
  $row = $result->fetch_assoc();
  return $row['name'];
}

//Название модели
function showname($id_p){
  global $connect;
  $result = mysqli_query($connect, "select name,manufacturer,category from product where id_p='$id_p';");

  $row = $result->fetch_assoc();?>
  <p id="Heading"><?php echo catname($row["category"],1) ." ". $row['manufacturer'] ." ". $row['name']; ?></p>
  <?php
}

function showimage($id_p){?>
  <div class ="imagesquare">
    <img src="img/<?=$id_p?>.jpg">
  </div><?php
}

//Выводит блок с информацией про товары
function showinfo ($id_p){?>
  <p id="Heading2">Характеристики:</p>
  <div class ="infosquare"><?php
    global $connect;
    $result = mysqli_query($connect, "select * from product where id_p='$id_p';");

    $row = $result->fetch_assoc();
    echo "Производитель<br />Полная стоимость товара<br />Скидка<br />Итоговая стоимость товара<br>";
    $result1 = mysqli_query($connect, "select name,value from configuration where id_p='$id_p' order by num asc;");
    while($row1 = mysqli_fetch_row($result1)){
      echo $row1[0] . "<br />";
    }
    ?>
    <div id="parametrs"><?php
      echo $row['manufacturer'] ."<br>" . number_format($row['price'],0, "", " ")   ." руб. <br>" . $row['action'] * 100 . "% <br>" . number_format( $row['price'] * (1 - $row['action']),0, "", " ") . " руб. <br>";
      $row1 = mysqli_data_seek($result1,0);
      while($row1 = mysqli_fetch_row($result1)){
        echo $row1[1] . "<br>";
      }?>
    </div>
  </div><?php
}

//Проверка на авторизацию пользователя
function verify(){
  if(isset($_SESSION['login'])){
    global $connect;
    $login = $_SESSION['login'];
    $password = $_SESSION['password'];
    $result = mysqli_query($connect, "select * from users where login='$login';");

    $row = $result->fetch_assoc();
    if (password_verify($password, $row['password'])){
      return true;
    } else {
      return false;
    }
  } else {
    return false;
  }
}

// Кнопка добавить в корзину
function busketbutton($id_p) {
  if(verify())
  {
    global $connect;
    $result = mysqli_query($connect, "select id_p from basket where id_p=$id_p and id_u=" . $_SESSION['id_u']);
    $result->fetch_assoc();

    if($result && $result->num_rows >= 1){
      $_SESSION['id_p'] = $id_p;?>
      <form action="/request.php"  method=post>
        <button type="submit" id="busket" name="ivent" value=6>Убрать из корзины</button>
        <input type="hidden" name="site" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
      </form><?php       
    } else {
      $_SESSION['id_p'] = $id_p;?>
      <form action="/request.php"  method=post>
        <button type="submit" id="busket" name="ivent" value=5>Добавить в корзину</button>
        <input type="hidden" name="site" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
      </form><?php    
    }
  }
}

//Кнопка оценить
function ratebutton($id_p){
  if(verify())
  {
    global $connect;
    $result = mysqli_query($connect, "select(select count(purchase.id_pur) from purchase where purchase.id_u = " .$_SESSION['id_u']. " and purchase.id_p='$id_p') as purchase, (select count(rate.id_r) from rate where rate.id_u = " .$_SESSION['id_u']. " and rate.id_p='$id_p') as rate;");
    $row = $result->fetch_assoc();

    if($row['purchase']>$row['rate']){
      ?>
      <button id ="cat2" onclick="document.getElementById('rate').style.display='block'">Оценить</button>
      <div class="category2" id="rate" style="display:none;">
        <form action="/request.php" method=post><?php
          for($i=1;$i<=5;$i++) {?>
            <button type="submit" id="cat21" method=post name="rate" value=<?php echo $i?>><?php echo $i?></button>
            <input type="hidden" name="site" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
            <input type="hidden" name="ivent" value=8>
            <?php
          }?>
        </form>
      </div><?php
    }
  } 
}