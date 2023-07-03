<!doctype html>
<html lang="ru">
<meta charset="UTF-8">

<head>
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <title>ЭЛЕКТРО</title>
</head>

<script> startTime = new Date(); </script>
<body onbeforeunload="myfunc()">
  <link rel="stylesheet" type="text/css" href="css/style.css">

  <div class="topnav">
    <div class ="topnav1">

      <!-- Категории -->
      <div class ="topnav2">
      <button id ="cat" onclick="document.getElementById('category').style.display='block'">Категории</button>
        <div class="category" id="category" style="display:none;">
          <form action="/category.php" method=post>
            <?php
            $result = mysqli_query($connect,"select category,name from catname where number =2 order by name;");
            while($row = $result->fetch_assoc()){?>
            <button type="submit" id="cat1" method=post name="category" value=<?php echo $row['category']?>><?php echo $row['name']?></button>
            <?php
            }?>
          </form>
        </div>
      </div>

      <!-- Поиск-->
      <div class ="topnav2">    
        <form action="request.php" method=post>
          <input type="text" id="search" placeholder="Поиск по сайту" name="search" >
          <input type="hidden" name="ivent" value=4>
        </form>
      </div>

      <!-- Кнопка "Домой"-->
      <div class ="topnav2">
        <button onclick="window.location.href='general_page.php'">Домой</button>
      </div>


      <?php
      if(!verify())
      {?>

        <!-- Авторизация-->
        <div class ="topnav2">
        <button onclick="document.getElementById('authorization').style.display='block'">Авторизация</button>
        
          <div class="registration" id="authorization" style="display:none;">
          <span onclick="document.getElementById('authorization').style.display='none'"
            class="close" title="Close Modal">&times;</span>  
          <form action="/request.php" method=post>
              <p><?php echo "Авторизация пользователя" ?></p>
              <p2>Логин</p2>
              <input type="text" placeholder="Введите логин" name="login"><br /><br />
              <p2>Пароль</p2>
              <input type="text" placeholder="Введите пароль" name="password"><br /><br />
              <button type="submit" id="reg" name="ivent" value=2>Авторизация</button>
              <input type="hidden" name="site" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
            </form>
          </div>
        </div>


        <!-- Регистрация-->
        <div class ="topnav2">
        <button onclick="document.getElementById('registrarion').style.display='block'">Зарегистрироваться</button>
        
          <div class="registration" id="registrarion" style="display:none;">
            <span onclick="document.getElementById('registrarion').style.display='none'"
              class="close" title="Close Modal">&times;</span>  
            <form action="/request.php"  method=post>

              <p><?php echo "Регистрация нового пользователя" ?></p>
              <p2>ФИО</p2>
              <input type="text" placeholder="Введите ваши ФИО" name="FIO"><br /><br />
              <p2>Логин</p2>
              <input type="text" placeholder="Введите логин" name="login"><br /><br />
              <p2>Пароль</p2>
              <input type="text" placeholder="Введите пароль" name="password"><br /><br />
              <button type="submit" id="reg" name="ivent" value=1>Регистрация</button>
              <input type="hidden" name="site" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
            </form>
          </div>
        </div><?php
      } else {?>
        
        <div class ="topnav2">
        <button onclick="window.location.href='basket.php'">Корзина</button>
        </div>

        <div class="topnav2">
        <form action="profile.php" method=post>
          <input type="hidden" name="site" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
          <button type="submit">Профиль</button>
        </form></div>

        <div class="topnav2">
        <form action="request.php" method=post>
          <input type="hidden" name="site" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
          <button type="submit" name="ivent" value="3">Выйти</button>
        </form></div>

        <?php
      }?>
    </div>
  </div>
</body>
</html>