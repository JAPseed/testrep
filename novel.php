<?php
class UseMySQL{
  private $dsn;
  private $user;
  private $pw;
  public function __construct($dsn,$user,$pw){
    $this->dsn = $dsn;
    $this->user = $user;
    $this->pw = $pw;
  }
  //SQL文を受け取って実行する
  public function execute($sql,$size,$namelist){ 
	$dbh = new PDO($this->dsn, $this->user, $this->pw);   //接続
	$sth = $dbh->prepare($sql);         //SQL準備
	$sth->execute();

	$datalist;
	$count=0;
	while(true){
      $buff = $sth->fetch(PDO::FETCH_ASSOC);//PDO::FETCH_ASSOCを引数に入れる事で、連想配列としてデータが帰って来る、データがこれ以上取れなくなったらfalseが帰って来る
	    if( $buff === false ){
    	    break;    //データがもう存在しない場合はループを抜ける
    	}
    	$ary=array();
    	for($i=0;$i<$size;$i++){
 		   	$ary=array_pad($ary,$i+1,$buff[$namelist[$i]]);
 			//=[$buff['id'],$buff['actorid'],$buff['text']];
    	}
    	$datalist[$count]=$ary;
    	$count=$count+1;
	}
	return $datalist;

  }
}

//SQLを利用する
$dsn  = 'mysql:dbname=mynovelgame;host=127.0.0.1';   //接続先(どの種類のDBへアクセスするか:dbname=データベース名;host=どこのサーバーにアクセスするか)
$user = 'root';         //MySQLのユーザーID
$pw   = 'H@chiouji1';   //MySQLのパスワード

//$sqluse=new UseMySQL('mysql:dbname=mynovelgame;host=127.0.0.1','root','H@chiouji1');

if(array_key_exists('playername',$_GET)){
 $sql='select name from save';
 $dbh = new PDO($dsn, $user, $pw);   //接続
 $sth = $dbh->prepare($sql);         //SQL準備
 $sth->bindValue(':name',$_GET['playername'],PDO::PARAM_STR);
 $sth->execute();
 $buff = $sth->fetch(PDO::FETCH_ASSOC);//PDO::FETCH_ASSOCを引数に入れる事で、連想配列としてデータが帰って来る、データがこれ以上取れなくなったらfalseが帰って来る
 if( $buff === false ){
  $sql='insert into save values(:name,0,"","","","","",0,"")';
  $dbh = new PDO($dsn, $user, $pw);   //接続
  $sth = $dbh->prepare($sql);         //SQL準備
  $sth->bindValue(':name',$_GET['playername'],PDO::PARAM_STR);
  $sth->execute();
 }
 else{
  $sql='update save set name=:name,currentNum=0,text="",back="",leftchara="",rightchara="",bgm="",isstl=0,still=""';
  $dbh = new PDO($dsn, $user, $pw);   //接続
  $sth = $dbh->prepare($sql);         //SQL準備
  $sth->bindValue(':name',$_GET['playername'],PDO::PARAM_STR);
  $sth->execute();
 } 
 
 $playername=$_GET['playername'];
 $currentNum=0;
 
 $curtext='';
 $curback='';
 $curleft='';
 $curright='';
 $curbgm='';
 $isstl=0;
 $curstl='';
}
else{
 $sql='select * from save';
 $dbh = new PDO($dsn, $user, $pw);   //接続(dsn,username,password)
 $sth = $dbh->prepare($sql);         //SQL準備(実行する命令文を先に渡すことで、準備をさせる)
 $sth->execute();                    //実行

 $buff = $sth->fetch(PDO::FETCH_ASSOC);
 $playername=$buff['name'];
 if(empty($playername))$playername="default";
 $currentNum=$buff['currentNum'];
 if(empty($currentNum))$currentNum="0";
 $curtext=$buff['text'];
 if(empty($curtext))$curtext="none";
 $curback=$buff['back'];
 if(empty($curback))$curback="none";
 $curleft=$buff['leftchara'];
 if(empty($curleft))$curleft="none";
 $curright=$buff['rightchara'];
 if(empty($curright))$curright="none";
 $curbgm=$buff['bgm'];
 if(empty($curbgm))$curbgm="none";
 $isstl=$buff['isstl'];
 if(empty($isstl))$isstl=0;
 $curstl=$buff['still'];
 if(empty($curstl))$curstl="none";
}
$sqluse=new UseMySQL('mysql:dbname=mynovelgame;host=127.0.0.1','root','H@chiouji1');
$actors=$sqluse->execute('select * from actor',2,['id','path']);
$backs=$sqluse->execute('select * from background',2,['id','path']);
$bgms=$sqluse->execute('select * from bgm',2,['id','path']);
$coms=$sqluse->execute('select * from command',2,['id','path']);
$mains=$sqluse->execute('select * from main',4,['id','typeid','currentrecord','isnext']);
$names=$sqluse->execute('select * from name',2,['id','path']);
$ses=$sqluse->execute('select * from se',2,['id','path']);
$stills=$sqluse->execute('select * from still',2,['id','path']);
$texts=$sqluse->execute('select * from text',3,['id','actorid','text']);

//$dbh = new PDO($dsn, $user, $pw);   //接続
//$sth = $dbh->prepare($sql);         //SQL準備
//$sth->execute();

//$datalist;
//$count=0;
//while(true){
      //$buff = $sth->fetch(PDO::FETCH_ASSOC);//PDO::FETCH_ASSOCを引数に入れる事で、連想配列としてデータが帰って来る、データがこれ以上取れなくなったらfalseが帰って来る
    //if( $buff === false ){
     //   break;    //データがもう存在しない場合はループを抜ける
    //}
    //$datalist[$count]=[$buff['text'],$buff['bg']];
  //  $count=$count+1;
//}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf8">
	<title>Novel</title>
	
	<link rel="stylesheet" href="style.css">
	<style>
		#novelwindow{
			border:1px solid gray;
			width:800px;
			height:600px;
			background-image:url(image/earth.jpg);
			background-size:800px 600px;
		}
		#message{
			position:absolute;
			top:350px;
			left:75px;
			
			border:1px solid blue;
			width:	650px;
			height:200px;	
			font-size:22pt;
			padding:5px;
			background-color: rgba(255,255,255,0.7);
		}
		#characters{
		 display: flex;
		}		
		#buttons{
		 position:absolute;
		 top:320px;
		 left:520px;
		}
		#still{
		 position:relative;
		 top:-600px;
		 width:800px;
		 height:600px;
		}
		#char1{
		 height:600px;
		 max-width:400px;
		 position:relative;
		}		
		#char2{
		 height:600px;
		 max-width:400px;
		 position:relative;
		}
	</style>
</head>
<body>
	<div id="novelwindow">
		<div id="characters">
			<img id="char1" alt="">
			<img id="char2" alt="">
		</div>
		
		<img id="still" alt="">

		<div id="buttons">		       
        <button type="button" name="button" id="save">セーブ</button>
        <button type="button" name="button" id="load">ロード</button>
        <button type="button" name="button" id="title">タイトル</button>

		</div>
		<div id="message">
			押したらスタート
		</div>
	</div>
	
	<audio id="bgm" loop></audio>
	<audio id="se"></audio>
	<script>
	 //入力文字列を取ってくる
	 var playername="<?= $playername ?>";
	 //var textList= <?php echo json_encode($datalist, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
	 //var textList=[["さいしょ",""],["つ##NAME##ぎ","image/f047.png"],["さいご","image/earth.jpg"]];
	 var actorList=<?= json_encode($actors); ?>;
	 var backList=<?= json_encode($backs); ?>;
	 var bgmList=<?= json_encode($bgms); ?>;
	 var comList=<?= json_encode($coms); ?>;
	 var mainList=<?= json_encode($mains); ?>;
	 var nameList=<?= json_encode($names); ?>;
	 var seList=<?= json_encode($ses); ?>;
	 var stillList=<?= json_encode($stills); ?>;
	 var textList=<?= json_encode($texts); ?>;
	 
	 
	 var count=<?= $currentNum ?>;
	 count=count-1;
	 var textBox=document.querySelector("#message");
	 var chara1=document.querySelector("#char1");
	 var chara2=document.querySelector("#char2");
	 var bgmmgr=document.querySelector("#bgm");
	 var semgr=document.querySelector("#se");
	 var still=document.querySelector("#still");
	 var savebtn=document.querySelector("#save");
	 var loadbtn=document.querySelector("#load");
	 var titlebtn=document.querySelector("#title");
	 var isStl=<?= $isstl ?>;
	 var curright="<?= $curright ?>";
	 var curleft="<?= $curleft ?>";
	 var curtext="<?= $curtext ?>";
	 var curbgm="<?= $curbgm ?>";
	 var curback="<?= $curback ?>";
	 var curstl="<?= $curstl ?>";
	 
	 var bg=document.querySelector("#novelwindow");
	 

	 if(curtext!="none"&&curtext!="")textBox.innerHTML=curtext;//テキストを変更

	 if(curbgm!="none"&&curbgm!=""){
	 	bgmmgr.src="gameres/bgm/"+curbgm;
	 	bgmmgr.play();
	 }
	 
	 if(isStl){	 	
	 	chara1.setAttribute("src","");	 				  
	 	chara2.setAttribute("src","");
	 	curright="";
	 	curleft="";
	 	curback="";
	 	still.setAttribute("src","gameres/still/"+curstl);
	 }
	 else{
	 	if(curleft!="none"&&curleft!="")chara1.setAttribute("src","gameres/character/"+curleft);
		if(curright!="none"&&curright!="")chara2.setAttribute("src","gameres/character/"+curright);
	 	if(curback!="none"&&curback!="")bg.style.backgroundImage="url("+"gameres/background/"+curback+")";
	 }
	 textBox.addEventListener("click",function(){
	 	while(true){
	 		//テキスト一覧を全て表示し終わったら更新しない
	  		if(count<mainList.length-1)count++;
	  		else break;
	  		var key=mainList[count][2] -1;
	  		var target=comList[mainList[count][1]-1];
	 		switch(target[1]){
	 			case 'TXT':{
	 				var text=textList[key][2];
	  				text=text.replace(/王子/g,"<span style='color:red'>"+playername+"</span>");
	  				this.innerHTML=text;//テキストを変更
	  				curtext=text;
	 				break;
	 			}
	 			case 'BACK':{
				   bg.style.backgroundImage="url("+"gameres/background/"+backList[key][1]+")";
				   curback=backList[key][1];
	 				break;
	 			}
	 			case 'CL':{
	 				  chara1.setAttribute("src","gameres/character/"+actorList[key][1]);
	 				  curleft=actorList[key][1];
	 				break;
	 			}
	 			case 'CR':{
	 				  chara2.setAttribute("src","gameres/character/"+actorList[key][1]);
	 				  curright=actorList[key][1];
	 				break;
	 			}
	 			case 'BGM':{
	 				  bgmmgr.src="gameres/bgm/"+bgmList[key][1];
	 				  bgmmgr.play();
	 				  curbgm=bgmList[key][1];
	 				break;
	 			}
	 			case 'SE':{	 				
	 				  semgr.src="gameres/se/"+seList[key][1];
	 				  semgr.play();
	 				break;
	 			}
	 			case 'STL':{	 		
	 				chara1.setAttribute("src","");	 				  
	 				chara2.setAttribute("src","");
	 				isStl=true;
	 				curright="";
	 				curleft="";
	 				curback="";
	 				curstl=stillList[key][1];
	 				var m="gameres/still/"+stillList[key][1];
	 				still.setAttribute("src","gameres/still/"+stillList[key][1]);
	 				break;
	 			}
	 			case 'ESTL':{
		 			isStl=false;
	 				still.setAttribute("src","");
	 				break;
	 			}
	 			case 'END':{
	 				window.location.href='end.html';
	 				break;
	 			}
	 		}
	 		
	 		if(mainList[count][3]!=true)break;
	 		
	 	}
	  //var text=textList[count][2];
	  //text=text.replace(/王子/g,"<span style='color:red'>"+playername+"</span>");
	  //this.innerHTML=text;//テキストを変更
	  //背景画像指定があったら変更
	  //if(textList[count][1]!==null){
	   //bg.style.backgroundImage="url("+textList[count][1]+")";
	   //chara.setAttribute("src",textList[count][1]);
	  //}
	 
	 });
	 savebtn.addEventListener("click",function(){
	 	count=count+1;
	 	var iss=0;
	 	if(isStl==true)iss=1;
	 	window.location.href='save.php?name='+playername+'&txt='+curtext+'&num='+count+'&bg='+curback+'&lc='+curleft+'&rc='+curright+'&bm='+curbgm+'&is='+iss+'&stl='+curstl;
	 });	 
	 loadbtn.addEventListener("click",function(){
	 	var iss=0;
	 	if(isStl==true)iss=1;
	 	window.location.href='novel.php';
	 });	 
	 titlebtn.addEventListener("click",function(){
	 	count=count+1;
	 	var iss=0;
	 	if(isStl==true)iss=1;
	 	window.location.href='title.html';
	 });
	 
	</script>
</body>
</html>

