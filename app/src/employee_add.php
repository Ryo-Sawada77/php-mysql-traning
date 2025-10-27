<?php

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>社員新規登録フォーム</title>
</head>
<body>
  <form method="post">
      <!-- 社員番号は数字だけ想定 -->
    <label for="staff_id">社員番号:</label>
    <input type="number" id="staff_id" name="staff_id" placeholder="12345" required>

    <!-- 名前は文字列 -->
    <label for="name">名前:</label>
    <input type="text" id="name" name="name" placeholder="山田 太郎" required>

    <!-- 入社日は日付入力 -->
    <label for="joined_at">入社日:</label>
    <input type="date" id="joined_at" name="joined_at" required>

    <input type="submit" value="送信">
  </form>
</body>
</html>