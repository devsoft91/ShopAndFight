<?php
  session_start();

  if(!empty($_SESSION['login'])){

    $host="basidati";
    $user="gbeltram";
    $pwd= "UuRnP33o";

    $connect=mysql_connect($host, $user, $pwd) or die("Impossibile connettersi!");

    $dbname="gbeltram-PR";
    $connection=mysql_select_db($dbname,$connect);

    $login=$_SESSION['login'];
    $tipoutente=$_SESSION['tipoutente'];

    $query="select idp from Carrello natural join Utenti where username=\"$login\"";

    $send=mysql_query($query,$connect) or die("Query fallita. ".mysql_error($connect));

    $num_rows=mysql_num_rows($send);
  }
  echo <<< HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Sportify - Sconti</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="title" content="Login"/>
  <meta name="author" content="Viviana Alessio, Giacomo Beltrame"/>
  <meta name="language" content="italian it"/>
  <meta name="viewport" content="width=device-width, user-scalable=no"/>
  <meta http-equiv="Content-Script-Type" content="text/javascript"/>
  <link rel="icon" href="imgs/favicon.png" type="image/ico"/>
  <link href="stylesheet/style.css" rel="stylesheet" type="text/css" media="screen"/>
  <script type="text/javascript" src="script/script.js"></script>
</head>
<body>
  <div id="content">
    <div id="navbar">
      <div id="logo">
      </div>
      <ul id="menu">
        <li><a href="index.php">HOME</a></li>
        <li><a href="filtra.php">CERCA/FILTRA</a></li>
        <li>SCONTI</li>
        <li><a href="ordini.php">ORDINI</a></li>
        <li><a href="carrello.php">CARRELLO
HTML;
  if(!empty($_SESSION['login']) and $num_rows>0) echo "($num_rows)";
  echo "</a></li>";
  if(!empty($_SESSION['login']) and $tipoutente=="Proprietario") echo "<li><a href=\"gestisci_utenti.php\">UTENTI</a></li>";
  echo <<< HTML
      </ul>
      <div id="path">Ti trovi in: Sconti</div>
    </div>
    <div id="body">
HTML;

  if(empty($_SESSION['login'])){
    echo <<< HTML
      <div id="register">
        <h1>Registrazione</h1>
          <form action="register.php" method="post">
            <fieldset>
              <legend>Dati Utente</legend>
              <label for="nickname">Nickname:</label>
              <input type="text" name="login" id="nickname" maxlength="15"/><br/>
              <label for="pwd">Password:</label>
              <input type="password" name="pwd" id="pwd" maxlength="10"/><br/>
              <label for="rpwd">Ripeti Password:</label>
              <input type="password" name="rpwd" id="rpwd" maxlength="10"/>
            </fieldset>
            <fieldset>
              <legend>Dati Utente</legend>
              <label for="nome">Nome:</label>
              <input type="text" name="nome" id="nome" maxlength="10"/><br/>
              <label for="cognome">Cognome:</label>
              <input type="text" name="cognome" id="cognome" maxlength="10"/><br/>
              <label for="indirizzo">Indirizzo:</label>
              <input type="text" name="indirizzo" id="indirizzo" maxlength="20"/>
            </fieldset>
            <input type="submit" value="Registrati"/><br/>
          </form>
      </div>
      <div id="login">
        <h1>Login</h1>
          <form action="login.php" method="post">
            <fieldset>
              <legend>Dati Utente</legend>
              <label for="nickname" xml:lang="en">Nickname:</label>
              <input type="text" name="login" id="nickname"/><br/>
              <label for="pwd" xml:lang="en">Password:</label>
              <input type="password" name="pwd" id="pwd"/>
            </fieldset>
            <input type="submit" value="Login"/><br/>
          </form>
      </div>
HTML;
  }
  else{

    $query1="select tipoutente from Utenti where username=\"$login\"";

    $check=mysql_query($query1,$connect) or die("Query fallita".mysql_error($connect));
    
    $type=mysql_fetch_row($check);

    echo "<h1>Sconti</h1>\n";

    if($type[0]=='Cliente'){
      $tipo=0;
      $query="select ScontoAccumulato from Sconti natural join Utenti where username=\"$login\"";
      $send=mysql_query($query,$connect) or die("Query fallita".mysql_error($connect));
      $num_colonne=mysql_num_fields($send);
      echo "<p>Il tuo buono sconto:<p>";
    }
    else{
      $tipo=1;
      $query="select idu as IDUtente, ScontoAccumulato from Sconti";
      $send=mysql_query($query,$connect) or die("Query fallita".mysql_error($connect));
      $num_colonne=mysql_num_fields($send);
      $num_righe=mysql_num_rows($send);
      echo "<p>Tutti gli sconti degli utenti:<p>\n<br>$num_righe risultati:<br>";
    }

    echo"<table>";
    echo"<tr>";

    $i = 0;
    while($i<$num_colonne){
      $meta = mysql_fetch_field($send,$i);
      echo "<th>$meta->name</th>";
      $i++;
    }
    echo "</tr>";
    while($row=mysql_fetch_row($send)){
      $j=0;
      echo "<tr>";
      while($j<$num_colonne){
        echo "<td>$row[$j]";
        if($tipo==0){
          if($j==0) echo " €";
        }
        else
          if($j==1) echo " €";
        $j++;
      }
      echo "</td>";
      $j++;
    }
    echo "</tr>";
  }
  echo "</table>";

?>

  </div>
  </div>
  <div id="footer">
    Copyright &#xA9; 2014 - All Rights Reserved.
  </div>
</body>
</html>