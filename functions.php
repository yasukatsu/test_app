<?php
//データの受け取り・受け渡しとDBへの処理を依頼する機能をまとめるファイルを作成します


require_once('connection.php');//外部ファイル読み込み
session_start();


// エスケープ処理
function h($text)
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');//ENT_QUOTES→シングルクオートとダブルクオートを共に変換します。
}

// SESSIONに暗号化したtokenを入れる
function setToken()
{
    $_SESSION['token'] = sha1(uniqid(mt_rand(), true));
    // var_dump($_SESSION['token']);
    //sha1で ハッシュ化・uniqid関数＝マイクロ秒単位の現在時刻に基づく13文字の文字列が作成される。引数を指定すると頭に引数が設置される・第一引数で乱数表示をし第二引数にtrueを入れることで『.』+13の文字列を追加できる・(初期値はfalse)
}

// SESSIONに格納されてたtokenのチェックを行いCSRF対策を行う
function checkToken($token)
{
    if (empty($_SESSION['token']) || ($_SESSION['token'] !== $token)) {
        $_SESSION['err'] = '不正な操作です';
        header('Location: '.$_SERVER['HTTP_REFERER']);
        exit();
    }
    return true;
}


function unsetSession()
{
    $_SESSION['err'] = '';
}


function saveDataAfterRedirect()
{
  // echo '<pre>';
  //     var_dump($_SESSION['token']);
  // echo '</pre>';
  // echo '<pre>';
  //     var_dump($_POST);
  // echo '</pre>';
  // exit;

    // unsetSession();
  // var_dump($_POST);
  // var_dump($_SESSION['token']);
    $post = $_POST;
    $path = checkReferer();
    if(validate($post)){
      if ($path === '/new.php') {
          createData($post);
      } elseif($path === '/edit.php') {
          updateData($post);
      } else {
          deleteData($post['id']);
      }
      return 'index';
    } else {
        return 'error';
    }
}

function validate($post)
{
    if (isset($post['todo']) && $post['todo'] === '') {
        $_SESSION['err'] = '入力がありません';
        return false;
    }
    return true;
}

function checkReferer()
{
    $httpArr = parse_url($_SERVER['HTTP_REFERER']);//parse_url()でURL の様々な構成要素のうち特定できるものに関して 連想配列にして返します。
    // $_SERVER関数でhttpの参照元（referer）を取得する
    return $httpArr['path'];//urlの中のpathを取得してreturnで返している
}



function createData($post) //データの受け取り処理
{
  if (checkToken($post['token'])) {
    createTodoData($post['todo']);
  }

  // createTodoData($post['todo']);  // 実際にPOSTされた際に上記の処理にデータを渡す
}



//index.php(元はindex.html)で呼び出してTODOリスト一覧を表示させる
function todoList()
{
  return getAllTodos();
}


// 下記で記述したupdataData()にPOSTデータを渡して呼び出せばDBのupdata処理が実行させる
function updateData($post)
{
  if (checkToken($post['token'])) {
    updateTodoData($post['id'], $post['todo']);
  }
  // updateTodoData($post['id'],$post['todo']);
}


function select($id)
{
  return getSelectedTodo($id);
}


function deleteData($id)
{
  deleteTodoData($id);
}





