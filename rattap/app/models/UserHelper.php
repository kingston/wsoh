<?php

class UserHelper {
  public static function createUser($conn, $name, $pass) {
    $sql = "insert into userauth(username, password, active,userlevel) values(?, ?, 1, 3)";
    $sth = $conn->prepare($sql);
    $sth->execute(array($name, $pass));
    return $conn->lastInsertId();
  }

  public static function updateUser($conn, $userid, $pass) {
    $sql = "UPDATE userauth SET password = ? WHERE userid = ?";
    $sth = $conn->prepare($sql);
    $sth->execute(array($pass, $userid));
  }

  public static function getUser($conn, $user_id) {
    $sql = "SELECT * FROM userauth WHERE userid = ?";
    $sth = $conn->prepare($sql);
    $sth->execute(array($user_id));
    return $sth->fetch();
  }
}
