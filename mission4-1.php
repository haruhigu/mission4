<?php
header('Content-Type: text/html; charset=UTF-8');//文字化け防止
?>

<?php
//データベースへ接続
$dsn='データベース名';
$user='ユーザー名';
$password='パスワード';
$pdo=new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));//array()はエラーの警告をしてくれる要素
if($pdo!==false){}else{echo"データベースに接続できていません";}


?>

<html>

<head>
<title>mission4-1</title>
<meta http-equiv="content-type" charset="utf-8"> 
</head>

<?php
$name=$_POST["name"];
$comment=$_POST["comment"]; 
$date=date("Y/m/d H:i:s");
$delate=$_POST["削除"];
$edit=$_POST["編集"];
$hidden=$_POST["hidden"];
$pass0=$_POST["pass0"];//新規
$pass1=$_POST["pass1"];//編集
$pass2=$_POST["pass2"];//削除


//編集モードの用意
if(!empty($edit)&&!empty($pass1)){
	//$pass1と保存されているパスワードが一致した時
    $sql='SELECT * FROM mission4';
	$stmt=$pdo->query($sql);
	$results=$stmt->fetchAll();
	foreach ($results as $row){//$rowの中にはテーブルのカラム名が入る 
        if($_POST["編集"]==$row[0]and$pass1==$row['pass']){
            $A=$row['id'];//番号
            $B=$row['name'];//名前
            $C=$row['comment'];//コメント
            }//if
    }//foreach
}//if
?>

<body>
<form action="mission4-1.php"method="post">
名前:<input type="text" name="name"value="<?php echo$B;?>"placeholder="お名前を入力して下さい" ><br>
コメント:<input type="text" name="comment"value="<?php echo$C;?>"placeholder="コメントを入力してください">
パスワード:<input type="text" name="pass0"placeholder="パスワードを決めてください">
<input type="hidden" name="hidden"value="<?php echo$A;?>">
<input type="submit" name><br>
編集:<input type="text" name="編集"placeholder="編集対象番号">
パスワード:<input type="text" name="pass1"placeholder="パスワードを決めてください">
<input type="submit"value="編集"><br>
削除:<input type="text" name="削除"placeholder="削除対象番号">
パスワード:<input type="text" name="pass2"placeholder="パスワードを決めてください">
<input type="submit"value="削除"><br>
</form>


<?php


//編集
if(!empty($hidden)){
	//echo"編集可能です"."<br>";
$id1=$edit;//編集番号
$name = $name; 
$comment = $comment; 
$sql = 'update mission4 set name=:name,comment=:comment,pass=:pass,date=:date where id=:id';
$stmt = $pdo->prepare($sql);
//bindParam:指定された変数にパラメーターを割り当てる
$stmt->bindParam(':id', $hidden, PDO::PARAM_INT);//番号
$stmt->bindParam(':name', $name, PDO::PARAM_STR);//名前
$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);//;コメント
$stmt->bindParam(':pass', $pass0, PDO::PARAM_STR);//パスワード
$stmt->bindParam(':date', $date, PDO::PARAM_STR);//日時
$stmt->execute();
$sql='SELECT * FROM mission4';
$stmt=$pdo->query($sql);
$results=$stmt->fetchAll();
}

//削除
if(empty($hidden)){//隠しが空でない時
if(!empty($delate)&&!empty($pass2)){
$id2 = $delate;
$sql = 'delete from mission4 where id=:id and pass=:pass';
$stmt = $pdo->prepare($sql); 
$stmt->bindParam(':id', $id2, PDO::PARAM_INT); 
$stmt->bindParam(':pass', $pass2, PDO::PARAM_INT); 
$stmt->execute();
$sql='SELECT * FROM mission4';
$stmt=$pdo->query($sql);
$results=$stmt->fetchAll();
}
}



//新規投稿
if(empty($hidden)){
	$A=empty($name)&&empty($comment)&&empty($pass0);
	if($A==false){
		//echo"かきこみをお願いします"."<br>";
		}
	if(!$A){//全て空欄ではない時
		$sql=$pdo -> prepare("INSERT INTO mission4 (id,name, comment,date,pass) VALUES (' ',:name, :comment,:date,:pass)");
		if($sql==false){
			//echo"テーブルの準備ができないよ"."<br>";
			}
//bindParam:指定された変数にパラメーターを割り当てる
$A=$pass0;
		$sql -> bindParam(':name', $name, PDO::PARAM_STR);
		$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
		$sql -> bindParam(':date', $date, PDO::PARAM_STR);
		$sql -> bindParam(':pass',$A, PDO::PARAM_STR);
		$sql -> execute();//execute:前もって準備された文を実行する
		//echo"入力を受け付けました"."<br>";
	}
}


//表示
$sql='SELECT * FROM mission4 order by id';
$stmt=$pdo->query($sql);
$results=$stmt->fetchAll();
foreach ($results as $row){//$rowの中にはテーブルのカラム名が入る 
	echo $row['id']." ".$row['name']." ".$row['comment']." ".$row['date']."<br>";//passは非表示
	}

?>
	
</body>
</html>
