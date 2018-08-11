<!DOCTYPE html>
<html lang="ja">
<head>
 <meta charset="UTF-8">
</head>
	<body>

		<form onsubmit="return confirm('送信しますか？');" action = "mission_4.php" method = "post">
<?php
/*MySQLに接続*/
$dsn = 'mysql:dbname=データベース名;host=localhost';
$user = 'ユーザー名';
$sqlPassword = 'PASSWORD';
$pdo = new PDO($dsn,$user,$sqlPassword);
/***********/
$name = $_POST["name"];
$comment = $_POST["comment"];
$password = $_POST["password"];
$num = $_POST["num"];
$send = $_POST["send"];
$row['id'] = 0;
$i = 0;

/*MySQL取得*/
$sql = 'SELECT * FROM mission_4';
$results = $pdo -> query($sql);
foreach ($results as $row)
{
	$i += 1;
	$backup[$row['id']]['name'] = $row['name'];
	$backup[$row['id']]['comment'] = $row['comment'];
	$backup[$row['id']]['time'] = $row['time'];
	$backup[$row['id']]['password'] = $row['password'];
}

switch($send){
case "送信":
echo <<< EOF
名前<br>
<input type = "text" name = "name" value = "名前"><br>
コメント<br>
<TEXTAREA cols="25" rows="8" name = "comment">コメント</TEXTAREA><br>
パスワード<br>
<input type = "text" name = "password" value = ""><br>
<input type = "submit" name = "send" value = "送信" >
<br><br>
EOF;
	if(!empty($comment)&&!empty($name))
	{
		echo "送信しました<br>";





/*MySQL挿入*/

		$sql = $pdo -> prepare("INSERT INTO mission_4 (id, name, comment, time, password) VALUES (:id, :name, :comment, :time, :password)");
		$sql -> bindParam(':id', $id, PDO::PARAM_STR);
		$sql -> bindParam(':name', $name, PDO::PARAM_STR);
		$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
		$sql -> bindParam(':time', $time, PDO::PARAM_STR);
		$sql -> bindParam(':password', $password, PDO::PARAM_STR);

		$i += 1;
		$id = $i;
		$time = date("Y/n/j G:i:s");
		$sql -> execute();

		$backup[$id]['name'] = $name;
		$backup[$id]['comment'] = $comment;
		$backup[$id]['time'] = $time;
		$backup[$id]['password'] = $password;
	}
	break;
case "編集":

	$editNum = $_POST["editNum"];
	if(empty($editNum)){
		if($num <= $i && $num >= 1){
			if($password == $backup[$num]['password']){
$name = $backup[$num]['name'];
$comment = $backup[$num]['comment'];
echo <<< EOF
名前<br>
<input type = "text" name = "name" value = $name><br>
コメント<br>
<TEXTAREA cols="25" rows="8" name = "comment">$comment</TEXTAREA><br>
<input type = "submit" name = "send" value = "編集" >
<input type = "hidden" name = "editNum" value = $num>
<br><br>
EOF;
				echo $num,"番を編集します<br>";
				

			}else{
echo <<< EOF
名前<br>
<input type = "text" name = "name" value = "名前"><br>
コメント<br>
<TEXTAREA cols="25" rows="8" name = "comment">コメント</TEXTAREA><br>
パスワード<br>
<input type = "text" name = "password" value = ""><br>
<input type = "submit" name = "send" value = "送信" >
<br><br>
EOF;
				echo "「パスワード」が適切ではありません。<br>";
			}
		}else{
echo <<< EOF
名前<br>
<input type = "text" name = "name" value = "名前"><br>
コメント<br>
<TEXTAREA cols="25" rows="8" name = "comment">コメント</TEXTAREA><br>
パスワード<br>
<input type = "text" name = "password" value = ""><br>
<input type = "submit" name = "send" value = "送信" >
<br><br>
EOF;
			echo "「編集対象番号」が適切ではありません。<br>";
		}
	}else{
echo <<< EOF
名前<br>
<input type = "text" name = "name" value = "名前"><br>
コメント<br>
<TEXTAREA cols="25" rows="8" name = "comment">コメント</TEXTAREA><br>
パスワード<br>
<input type = "text" name = "password" value = ""><br>
<input type = "submit" name = "send" value = "送信" >
<br><br>
EOF;
		if(!empty($comment)&&!empty($name))
		{
			echo "編集しました<br>";
			$id = $editNum;
			$nm = $name;
			$kome = $comment;
			$time = $backup[$editNum]['time'];
			$password = $backup[$editNum]['password'];
			$sql = "update mission_4 set name='$nm' , comment='$kome' , time = '$time' , password = '$password' where id = $id";
			$result = $pdo->query($sql);
//echo $sql,'<br>';
		}	
	}
	break;


case "削除":
echo <<< EOF
名前<br>
<input type = "text" name = "name" value = "名前"><br>
コメント<br>
<TEXTAREA cols="25" rows="8" name = "comment">コメント</TEXTAREA><br>
パスワード<br>
<input type = "text" name = "password" value = ""><br>
<input type = "submit" name = "send" value = "送信" >
<br><br>
EOF;
	if($num <= $i && $num >= 1){
		if($password == $backup[$num]['password']){
			echo "削除しました<br>";

			for($j = $num;$j < $i;$j ++){
				$id = $j;
				$nm = $backup[$j + 1]['name'];
				$kome = $backup[$j + 1]['comment'];
				$time = $backup[$j + 1]['time'];
				$password = $backup[$j + 1]['password'];
				$sql = "update mission_4 set name='$nm' , comment='$kome' , time='$time' , password='$password'  where id = $id";
				$result = $pdo->query($sql);



				$backup[$j]['name'] = $backup[$j + 1]['name'];
				$backup[$j]['comment'] = $backup[$j + 1]['comment'];
				$backup[$j]['time'] = $backup[$j + 1]['time'];
				$backup[$j]['password'] = $backup[$j + 1]['password'];
			}
			
			unset($backup[$i]['name'],$backup[$i]['comment'],$backup[$i]['time'],$backup[$i]['password']);

			$id = $i;
			$sql = "delete from mission_4 where id=$id";
			$result = $pdo->query($sql);
			$i -= 1;
		}else{
			echo "「パスワード」が適切ではありません。<br>";
		}
	}else{
		echo "「削除対象番号」が適切ではありません。<br>";
	}
	break;


default:
echo <<< EOF
名前<br>
<input type = "text" name = "name" value = "名前"><br>
コメント<br>
<TEXTAREA cols="25" rows="8" name = "comment">コメント</TEXTAREA><br>
パスワード<br>
<input type = "text" name = "password" value = ""><br>
<input type = "submit" name = "send" value = "送信" >
<br><br>
EOF;
}

/*MySQL取得*/

		$sql = 'SELECT * FROM mission_4';
		$results = $pdo -> query($sql);
		foreach ($results as $row){
		$backup[$row['id']]['name'] = $row['name'];
		$backup[$row['id']]['comment'] = $row['comment'];
		$backup[$row['id']]['time'] = $row['time'];
		$backup[$row['id']]['password'] = $row['password'];
		}
		for($j = 1;$j <= $i;$j ++){
			echo $j,' ';
			echo $backup[$j]['name'],' ';
			echo $backup[$j]['comment'],' ';
			echo $backup[$j]['time'],' ';
			echo '<br>';
		}

?>
<br>
		</form>

		<form onsubmit="return confirm('編集しますか？');" action = "mission_4.php" method = "post">
			編集対象番号（半角数字）<br>
			<input type = "text" name = "num" value = ""><br>
			パスワード<br>
			<input type = "text" name = "password" value = ""><br>
			<input type = "submit" name = "send" value = "編集" >
		</form>
		<form onsubmit="return confirm('削除しますか？');" action = "mission_4.php" method = "post">
			削除対象番号（半角数字）<br>
			<input type = "text" name = "num" value = ""><br>
			パスワード<br>
			<input type = "text" name = "password" value = ""><br>
			<input type = "submit" name = "send" value = "削除" >
			
		</form>
	</body>

</html>