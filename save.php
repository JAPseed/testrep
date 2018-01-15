<?php


//SQLを利用する
$dsn  = 'mysql:dbname=mynovelgame;host=127.0.0.1';   //接続先(どの種類のDBへアクセスするか:dbname=データベース名;host=どこのサーバーにアクセスするか)
$user = 'root';         //MySQLのユーザーID
$pw   = 'H@chiouji1';   //MySQLのパスワード

 //$sql='update save set name="bu",currentNum=1,text="eee",back="",leftchara="",rightchara="",bgm="",isstl=0,still=""';
 //$sql='update save set name=:pn,currentNum=:num,text=:txt,back=:bg,leftchara=:lc,rightchara=:rc,bgm=:bm,isstl=:is,still=:stl';
 $sql='update save set name=:pn,currentNum=:num,text=:txt,back=:bg,leftchara=:lc,rightchara=:rc,bgm=:bm,isstl=:is,still=:stl';
 //$sqluse.execute($sql);
 $isstill;
 
 $dbh = new PDO($dsn, $user, $pw);   //接続
 $sth = $dbh->prepare($sql);         //SQL準備
 $sth->bindValue(':pn',$_GET['name'],PDO::PARAM_STR);
 $sth->bindValue(':num',$_GET['num'],PDO::PARAM_INT);
 $sth->bindValue(':txt',$_GET['txt'],PDO::PARAM_STR);
 $sth->bindValue(':bg',$_GET['bg'],PDO::PARAM_STR);
 $sth->bindValue(':lc',$_GET['lc'],PDO::PARAM_STR);
 $sth->bindValue(':rc',$_GET['rc'],PDO::PARAM_STR);
 $sth->bindValue(':bm',$_GET['bm'],PDO::PARAM_STR);
 $sth->bindValue(':is',$_GET['is'],PDO::PARAM_BOOL);
 $sth->bindValue(':stl',$_GET['stl'],PDO::PARAM_STR);
 $sth->execute();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf8">
	<title>Novel</title>

</head>
<body>
	<script>
		window.location.href="load.html";
	</script>
</body>
</html>

