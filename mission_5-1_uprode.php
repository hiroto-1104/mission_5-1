<?php
    //PHPの作成
    //DB接続設定
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    //テーブルの作成
    $sql = "CREATE TABLE IF NOT EXISTS mission5"
    ."("
    ."id INT AUTO_INCREMENT PRIMARY KEY,"
    ."name char(32),"
    ."comment TEXT,"
    ."time DATETIME,"
    ."password TEXT"
    .");";
    $stmt = $pdo -> query($sql);
    
    if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["password_1"]))
    {
        //編集
        if(!empty($_POST["editer"]))
        {
            $id = $_POST["editer"];
            $name = $_POST["name"];
            $comment = $_POST["comment"];
            $time = date("Y/m/d/H:i:s");
            $password = $_POST["password_1"];
            
            $sql ='UPDATE mission5 SET name=:name,comment=:comment,time=:time,password=:password WHERE id=:id';
            $stmt = $pdo -> prepare($sql);
            $stmt -> bindParam(':name',$name,PDO::PARAM_STR);
            $stmt -> bindParam(':comment',$comment,PDO::PARAM_STR);
            $stmt -> bindParam(':time',$time,PDO::PARAM_STR);
            $stmt -> bindParam(':password',$password,PDO::PARAM_STR);
            $stmt -> bindParam(':id',$id,PDO::PARAM_INT);
            $stmt -> execute();
        }
        //新規投稿
        else
        {
            $sql = $pdo -> prepare("INSERT INTO mission5(name,comment,time,password) VALUE(:name,:comment,:time,:password)");
            $sql -> bindParam(':name',$name,PDO::PARAM_STR);
            $sql -> bindParam(':comment',$comment,PDO::PARAM_STR);
            $sql -> bindParam(':time',$time,PDO::PARAM_STR);
            $sql -> bindParam(':password',$pass_1,PDO::PARAM_STR);
            $name = $_POST["name"];
            $comment = $_POST["comment"];
            $time = date("Y/m/d/H:i:s");
            $pass_1 = $_POST["password_1"];
            $sql -> execute();
        }
    }
    //削除
    elseif(!empty($_POST['del_num']) && !empty($_POST['password_2']))
    {
         //削除対象番号のパスワードを求める
         $id = $_POST['del_num']; //削除対象番号
         $pass_2 = $_POST['password_2']; //パスワード
         $sql = $pdo -> prepare('SELECT * FROM mission5 WHERE id = :id');
         $sql -> bindParam(':id',$id,PDO::PARAM_INT);
         $stmt = $sql -> execute();
         $result = $sql -> fetchAll();
         foreach($result as $row)
         {
             if($pass_2 == $row['password'])
             {
                $sql = 'delete from mission5 where id = :id';
                $stmt = $pdo -> prepare($sql);
                $stmt -> bindParam(':id',$id,PDO::PARAM_INT);
                $stmt -> execute();
             }
         }
    }
    //編集
    elseif(!empty($_POST["edit"]) && !empty($_POST["password_3"]))
    {
        $edit_num = $_POST["edit"];
        $pass_3 = $_POST["password_3"];
        //該当Noのレコードを抽出
        $sql = $pdo -> prepare('SELECT * FROM mission5 WHERE id = :id');
        $sql -> bindParam(':id',$edit_num,PDO::PARAM_INT);
        $sql-> execute();
        $result = $sql -> fetchAll();
        foreach($result as $row)
        {
            $n = $row['id'];    //編集対象番号
            $m = $row['name'];  //名前
            $c = $row['comment'];//コメント
        }
    }
?>
<!DOCTYPE html>
<html lang ="ja">
    <head>
        <meta charset="UTF-8">
        <title>mission_5-1</title>
    </head>
    <body>
        
        <form action="" method="post">
            <input type="text" name="name" value="<?php if(!empty($m)){echo $m;} else{echo "";} ?>" placeholder="名前">
            <input type="text" name="comment" value="<?php if(!empty($c)){echo $c;} else{echo "";}?>" placeholder="コメント">
            <input type="hidden" name="editer" value="<?php echo $n; ?>">
            <input type="text" name="password_1" placeholder="パスワード">
            <input type="submit" name="submit_1">
        </form>
        <form action="" method="post">
            <input type="number" name="del_num" placeholder="削除番号">
            <input type="text" name="password_2" placeholder="パスワード">
            <input type="submit" name="submit_2"value="削除">
        </form>
        <form action="" method="post">
            <input type="number" name="edit" placeholder="編集番号">
            <input type="text" name="password_3" placeholder="パスワード">
            <input type="submit" name="submit_3" value="編集">
        </form>
        <hr>
    </body>
</html>
<?php
    //DB接続設定
    $dsn = 'mysql:dbname=tb220944db;host:localhost';
    $user = 'tb-220944';
    $password = 'LtLhV383mH';
    $pdo = new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    //データベースを表示
    $sql = 'SELECT * FROM mission5';
    $stmt = $pdo -> query($sql);
    $results = $stmt -> fetchAll();
    foreach($results as $row)
    {
        echo $row['id']." ";
        echo $row['name']." ";
        echo $row['time']."<br>";
        echo $row['comment']."<br>";
        echo "<hr>";
    }
?>