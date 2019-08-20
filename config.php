<?php

// DB接続をするにあたっての設定file

define('DSN', 'mysql:dbname=php_lesson;host=localhost;unix_socket=/tmp/mysql.sock');
define('DB_USER', 'root');
define('DB_PASSWORD', '');

// difine()メソッドにより定義する。第一引数に定数の名前・第二引数に定数の値を入れることで定義できる