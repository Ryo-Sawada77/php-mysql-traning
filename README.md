# PHP MySQL Training
## 使用言語
* PHP(8.4)
* MySQL(8.4)

## How to Start
```bash
$ cd app

# コンテナ起動に必要な情報を準備
$ cp .env.example .env

# コンテナ起動
$ docker-compose up

# コンテナ状態確認
$ docker-compose ps
```

## PHP
```bash
# PHPコンテナに入る
$ docker-compose exec php-server bash

# PHPバージョン確認
root@XXXXXXXXXXXXX:/var/www/html# php --version
```

## MySQL
```bash
# MySQLコンテナに入る
$ docker-compose exec mysql-server bash

# MySQLバージョン確認
bash-5.1# mysql --version

# envファイルのDB_USERNAMEでMySQLにログインする
bash-5.1# mysql -u [DB_USERNAME] -p

# MySQLサーバー内のデータベースを表示
mysql> SHOW DATABASES;

# envファイルのDB_DATABASEを選択する
mysql> use [DB_DATABASE];

# テーブル一覧を表示する
mysql> SHOW TABLES;
```