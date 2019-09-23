<html>
	<head>
		<meta charset = "UTF-8">
	</head>

	<body>

		<h1> テーマ：好きな映画 </h1>

		<?php 

			$edit_name = "";
			$edit_com = "";
			$edit_count = "";
			$edit_pass = "";

			$dsn = 'データベース名';
			$user = 'ユーザー名';
			$password = 'パスワード';
			$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING)); //new PDO: 接続の管理
	
	
				
			$sql = "CREATE TABLE IF NOT EXISTS tb_51" 
				. " ("
				. "id INT AUTO_INCREMENT PRIMARY KEY,"
				. "name char(32),"
				. "comment TEXT,"
				. "date TEXT,"
				. "pass TEXT"
				. ");";

			$stmt = $pdo->query($sql); //PDO::query(): SQL ステートメントを実行し、結果セットを PDOStatement オブジェクトとして返す
						   //$stmt に PDOStatement を返している

			/*$sql ='SHOW TABLES'; //SHOW TABLES: 結果が1列のデータとして得られる
			$result = $pdo -> query($sql);
			foreach ($result as $row){
				echo $row[0];
				echo '<br>';
			}
			echo "<hr>"; //水平の横線を引くためのタグ*/

			$sql = $pdo -> prepare("INSERT INTO tb_51 (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");


			//送信フォーム
			if (!empty($_POST['name']) && !empty($_POST['comment'])) {
				$name1 = $_POST['name'];
				$com1 = $_POST['comment'];
				$date1 = date ('Y/m/d H:i:s');
				$pass1 = $_POST['pass'];

				//$sql = $pdo -> prepare("INSERT INTO tb_test (name, comment, date) VALUES (:name, :comment, :date)");


				if (!empty($_POST['pass'])) {
					if (!empty($_POST['num'])) {
						$num = $_POST['num'];

						$name = $name1;
						$comment = $com1;
						$date = $date1;
						$pass = $pass1;

						$sql = 'update tb_51 set name=:name,comment=:comment,date=:date,pass=:pass where id=:id';
						$stmt = $pdo->prepare($sql);
						$stmt->bindParam(':name', $name, PDO::PARAM_STR);
						$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
						$stmt -> bindParam(':date', $date, PDO::PARAM_STR);
						$stmt -> bindParam(':pass', $pass, PDO::PARAM_STR);
						$stmt->bindParam(':id', $num, PDO::PARAM_INT);
						$stmt->execute();
					

					}
					else {

						$sql -> bindParam(':name', $name, PDO::PARAM_STR);
						$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
						$sql -> bindParam(':date', $date, PDO::PARAM_STR);
						$sql -> bindParam(':pass', $pass, PDO::PARAM_STR);

						$name = $name1;
						$comment = $com1; 
						$date = $date1;
						$pass = $pass1;

						$sql -> execute(); //実行

					}

				}
				else {
					$sql -> bindParam(':name', $name, PDO::PARAM_STR);
					$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
					$sql -> bindParam(':date', $date, PDO::PARAM_STR);
					$sql -> bindParam(':pass', $pass, PDO::PARAM_STR);

					$name = $name1;
					$comment = $com1; 
					$date = $date1;
					$pass = "";

					$sql -> execute(); //実行
				}



			}



			//削除フォーム
			elseif (!empty($_POST['d_num']) && !empty($_POST['d_pass'])) {
				$d_num = $_POST['d_num'];
				$d_pass = $_POST['d_pass'];

				$sql = 'select * from tb_51';
				$stmt = $pdo->query($sql); //SQL文を実行するコードを、変数に格納
				$results = $stmt->fetchAll(); //全ての結果行を含む配列を返す。該当する全てのデータを配列として返す
				foreach ($results as $row){
					if ($d_num == $row['id'] && $d_pass == $row['pass']) {
						$sql = 'delete from tb_51 where id=:id'; //delete: データの削除
						$stmt = $pdo->prepare($sql);
						$stmt->bindParam(':id', $d_num, PDO::PARAM_INT);
						$stmt->execute();
					}

				}

			}


			//編集番号指定用フォーム
			elseif (!empty($_POST['e_num']) && !empty($_POST['e_pass'])) {
				$e_num = $_POST['e_num'];
				$name = $_POST['name'];
				$comment = $_POST['comment'];
				$e_pass = $_POST['e_pass'];



				$sql = 'select * from tb_51';
				$stmt = $pdo->query($sql); //SQL文を実行するコードを、変数に格納
				$results = $stmt->fetchAll(); //全ての結果行を含む配列を返す。該当する全てのデータを配列として返す
				foreach ($results as $row){
					if ($e_num == $row['id'] && $e_pass == $row['pass']) {
						$edit_name = $row['name'];
						$edit_com = $row['comment'];
						$edit_count = $row['id'];
						$edit_pass = $row['pass'];
					}

				}


			}



		?>
		
		<form action = "mission_5-1.php" method = "post">
			<!-- 入力フォーム -->
			<p>
			<input type = "hidden" name = "num" placeholder = "番号" value = "<?php echo $edit_count; ?>">
			<input type = "text" name = "name" placeholder = "名前" value = "<?php echo $edit_name; ?>">	
			<input type = "text" name = "comment" placeholder = "コメント" value = "<?php echo $edit_com; ?>">
			<input type = "text" name = "pass" placeholder = "パスワード" value = "<?php echo $edit_pass; ?>">
			<input type = "submit" name = "submit" value = "送信">
			</p>

			<!-- 編集番号指定用フォーム -->
			<p>
			<input type = "text" name = "e_num" placeholder = "編集対象番号">
			<input type = "text" name = "e_pass" placeholder = "パスワード"> 
			<input type = "submit" name = "edit" value = "編集">
			</p>


			<!-- 削除番号指定用フォーム-->
			<p>
			<input type = "text" name = "d_num" placeholder = "削除対象番号">
			<input type = "text" name = "d_pass" placeholder = "パスワード">
			<input type = "submit" name = "delete" value = "削除">
			</p>

		</form>


		<?php


			$sql = 'SELECT * FROM tb_51'; //テーブルにある全てのデータを取得するSQL文を、変数に格納
			$stmt = $pdo->query($sql); //SQL文を実行するコードを、変数に格納
			$results = $stmt->fetchAll(); //全ての結果行を含む配列を返す。該当する全てのデータを配列として返す
			foreach ($results as $row){
				//$rowの中にはテーブルのカラム名が入る
				echo $row['id'].':  ';
				echo $row['name'] . '  ';
				echo $row['date'].'<br>';
				echo $row['comment'].'<br>';
				echo "<hr>";
			}

		?>


	</body>
</html>
