<?php
class Jersey {
  public $id;
  public $jersey_name;
  public $price;
  public $size;
  public $image_id;
  public $sport_id;

  public function __construct() {
    $this->id = null;
  }

  public function save() {
    try {
      $db = new DB();
      $db ->open();
      $conn = $db->get_connection();

      $params = [
        ":jersey_name" => $this->jersey_name,
        ":price" => $this->price,
        ":size" => $this->size,
        ":image_id" => $this->image_id,
        ":sport_id" => $this->sport_id,

      ];
      if ($this->id === null) {
        $sql = "INSERT INTO jerseys (jersey_name,price,size,image_id,sport_id) VALUES (:jersey_name,:price,:size,:image_id,:sport_id)";
      }
      else {
        $sql = "UPDATE festivals SET " .
        "jersey_name = :jersey_name, " .
        "price = :price, " .
        "size = :size, " .
        "image_id = :image_id, " .
        "sport_id = :sport_id, " .
        "WHERE id = :id" ;
        $params[":id"] = $this->id;
      }
      $stmt = $conn->prepare($sql);
      $status = $stmt->execute($params);

      if (!$status) {
        $error_info = $stmt->errorInfo();
        $message = "SQLSTATE error code = ".$error_info[0]."; error message = ".$error_info[2];
        throw new Exception("Database error executing database query: " . $message);
      }

      if ($stmt->rowCount() !==1) {
        throw new Exception("Failed to save festival");
      }

      if ($this->id === null) {
        $this->id = $conn->lastInsertId();
      }
    } finally{
      if ($db !== null && $db->is_open()) {
        $db->close();
      }
    }

  }



  public static function findAll() {
    $jerseys = array();

    try {
      $db = new DB();
      $db->open();
      $conn = $db->get_connection();

      $select_sql = "SELECT * FROM jerseys";
      $select_stmt = $conn->prepare($select_sql);
      $select_status = $select_stmt->execute();

      if (!$select_status) {
        $error_info = $select_stmt->errorInfo();
        $message = "SQLSTATE error code = ".$error_info[0]."; error message = ".$error_info[2];
        throw new Exception("Database error executing database query: " . $message);
      }

      if ($select_stmt->rowCount() !== 0) {
        $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
        while ($row !== FALSE) {
          $jersey = new Jersey();
          $jersey->id = $row['id'];
          $jersey->jersey_name = $row['jersey_name'];
          $jersey->price = $row['price'];
          $jersey->size = $row['size'];
          $jersey->image_id = $row['image_id'];
          $jersey->sport_id = $row['sport_id'];

          $jerseys[] = $jersey;

          $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
        }
      }
    }
    finally {
      if ($db !== null && $db->is_open()) {
        $db->close();
      }
    }

    return $jerseys;
  }

  public static function findById($id) {
    $jerseys = null;

    try {
      $db = new DB();
      $db->open();
      $conn = $db->get_connection();

      $select_sql = "SELECT * FROM jerseys WHERE id = :id";
      $select_params = [
        ":id" => $id
      ];
      $select_stmt = $conn->prepare($select_sql);
      $select_status = $select_stmt->execute($select_params);

      if (!$select_status) {
        $error_info = $select_stmt->errorInfo();
        $message = "SQLSTATE error code = ".$error_info[0]."; error message = ".$error_info[2];
        throw new Exception("Database error executing database query: " . $message);
      }

      if ($select_stmt->rowCount() !== 0) {
        $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
          $jersey = new Jersey();
          $jersey->id = $row['id'];
          $jersey->jersey_name = $row['jersey_name'];
          $jersey->price = $row['price'];
          $jersey->size = $row['size'];
          $jersey->image_id = $row['image_id'];
          $jersey->sport_id = $row['sport_id'];


      }
    }
    finally {
      if ($db !== null && $db->is_open()) {
        $db->close();
      }
    }

    return $jersey;
  }
  }

?>
