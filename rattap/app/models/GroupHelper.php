<?php

class GroupHelper {
  public static function createGroup($conn, $user, $long, $lat) {
    $group_name = $user['username'] . "'s Group";
    $sql = "INSERT INTO groups(groupcreatorid, groupname, longtitude, latitude) values(?, ?, ?, ?)";
    $sth = $conn->prepare($sql);
    $sth->execute(array($user['userid'], $group_name, $long, $lat));
    return $conn->lastInsertId();
  }

  public static function joinGroup($conn, $group_id, $user) {
    $sql = "INSERT INTO group_user_assoc(groupid, userid) values(?, ?)";
    $sth = $conn->prepare($sql);
    $sth->execute(array($group_id, $user['userid']));
  }

  public static function getNearbyGroups($conn, $long, $lat) {
    $variance = 180.1;
    $sql = "SELECT * FROM groups WHERE longtitude BETWEEN ? AND ? AND latitude BETWEEN ? AND ? AND creationtime > now() - INTERVAL 20 MINUTE;";
    $sth = $conn->prepare($sql);
    $data = array($long - $variance, $long + $variance, $lat - $variance, $lat + $variance);
    $sth->execute($data);
    //return $data;
    return $sth->fetchAll();
  }

  public static function getGroup($conn, $group_id) {
    $sql = "SELECT * FROM groups WHERE groupid = ?";
    $sth = $conn->prepare($sql);
    $sth->execute(array($group_id));
    return $sth->fetch();
  }

  public static function getMembers($conn, $group_id) {
    $sql = "SELECT * FROM userauth WHERE userid IN (SELECT userid FROM group_user_assoc WHERE groupid = ?)";
    $sth = $conn->prepare($sql);
    $sth->execute(array($group_id));
    return $sth->fetchAll();
  }
}
