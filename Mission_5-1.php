<?php

//DB接続
$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//テーブル作成 テーブル名に-は使えない。
$sql = "CREATE TABLE IF NOT EXISTS mission5"
." ("
."num INT AUTO_INCREMENT PRIMARY KEY,"
."name char(32),"
."comment TEXT,"
."date char(32),"
."password char(32)"
.");";
$stmt = $pdo->query($sql);

    //編集フォーム用処理(実行されたら、その内容をコピーする)
if(isset($_POST["edit"]) && $_POST["edi_num"]){
    $num = $_POST["edi_num"];
    $sql = 'SELECT * FROM mission5 WHERE num=:num';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':num',$num,PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetchAll();
    foreach($results as $row){
        $copy_num = $row['num'];
        $copy_name = $row['name'];
        $copy_comment = $row['comment'];
    }echo "新規作成した時のパスワードを入力してください。";    
}

//入力フォーム用処理
if(!($_POST["copy_num"]) && $_POST["name"] && $_POST["comment"] && $_POST["password"]){
    
    $sql = $pdo -> prepare("INSERT INTO mission5 (name, comment, date, password) VALUES (:name, :comment, :date, :password)");
    $sql ->bindParam(':name', $name, PDO::PARAM_STR);
    $sql ->bindParam(':comment',$comment,PDO::PARAM_STR);
    $sql ->bindParam(':date',$date,PDO::PARAM_STR);
    $sql ->bindParam(':password',$password,PDO::PARAM_STR);

    $name = $_POST["name"];
    $comment = $_POST["comment"];
    $date = date("Y年m月d日 H時i分s秒");
    //パスワードはハッシュ化するなりセキュリティを高くしたほうが良い！
    $password = $_POST["password"];
    $sql -> execute();

}else if($_POST["copy_num"] && $_POST["name"] && $_POST["comment"] && $_POST["password"]){
    //更新内容
    $num = $_POST["copy_num"];
    $name = $_POST["name"];
    $comment = $_POST["comment"];
    $date = date("Y年m月d日 H時i分s秒");
    $password = $_POST["password"];
    
    $sql = 'UPDATE mission5 SET name=:name,comment=:comment,date=:date WHERE num=:num AND password=:password';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':num',$num,PDO::PARAM_INT);
    $stmt->bindParam(':name',$name,PDO::PARAM_STR);
    $stmt->bindParam(':comment',$comment,PDO::PARAM_STR);
    $stmt->bindParam(':date',$date,PDO::PARAM_STR);
    $stmt->bindParam(':password',$password,PDO::PARAM_STR);
    $stmt->execute();
}else if(isset($_POST["delete"]) && $_POST["del_num"] && $_POST["password"]){
    $password = $_POST["password"];
    $num = $_POST["del_num"];
    $sql = 'delete from mission5 where num=:num AND password=:password';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':num',$num,PDO::PARAM_INT);
    $stmt->bindParam(':password',$password,PDO::PARAM_STR);
    $stmt->execute();
}
    ?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>Mission_5-1</title>
</head>

<body>
    <h1>入力フォーム</h1>
    <form action="" method="post">
        <input type="hidden" name="copy_num" value="<?php echo $copy_num;?>">
        名前    ：<input type="text" name="name" value="<?php echo $copy_name;?>"><br>
        コメント：<input type="text" name="comment" value="<?php echo $copy_comment;?>"><br>
        パスワード：<input type="password" name="password">
        <input type="submit" name="post" value="作成">
    </form>
    
    <hr>
    
    <h1>削除フォーム</h1>
    <form action="" method="post">
        削除対象番号：<input type="number" name="del_num"><br>
        パスワード：<input type="password" name="password">
        <input type="submit" name="delete" value="削除">
    </form>
    
    <hr>
    
    <h1>編集フォーム</h1>
    <form action="" method="post">
        編集対象番号：<input type="number" name="edi_num">
        <input type="submit" name="edit" value="編集">
    </form>
    
    <hr>
    
    <h1>掲示板</h1> 
    
    <?php
	
    
    //表示処理
    $sql = "SELECT * FROM mission5";
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach($results as $row){
        echo $row['num'].' ';
        echo $row['name'].' ';
        echo $row['comment'].' ';
        echo $row['date'].' ';
        echo "<br>";
    }echo "<hr>";
    
/*    
    //DBのテーブルを表示。デバッグ用
    $sql ='SHOW TABLES';
    $result = $pdo -> query($sql);
    foreach ($result as $row){
    	echo $row[0];
    	echo '<br>';
    }echo "<hr>";
    
    //テーブルの詳細表示。デバッグ用
    $sql ='SHOW CREATE TABLE mission5';
	$result = $pdo -> query($sql);
	foreach ($result as $row){
		echo $row[1];
	}echo "<hr>"
*/
    ?>
</body>
</html>