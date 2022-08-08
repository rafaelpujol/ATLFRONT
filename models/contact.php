<?php
class Contact {
    // DB Stuff
    private $conn;
    private $table = 'contacts';

    // Properties
    public $Id;
    public $Name;
    public $LastName;
    public $Email;

    // Constructor with DB
    public function __construct($db) {
        $this->conn = $db;
    }

    // Get categories
    public function GetAll() {
        // Create query
        $query = 'SELECT
        Id,
        Name,
        LastName,
        Email
      FROM
        ' . $this->table . '
      ORDER BY
        Name DESC';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Execute query
        $stmt->execute();

        return $stmt;
    }

    public function GetById(){
        // Create query
        $query = 'SELECT
          Id,
          Name,
          LastName,
          Email
        FROM
          ' . $this->table . '
      WHERE id = ?
      LIMIT 0,1';

        //Prepare statement
        $stmt = $this->conn->prepare($query);
        // Bind ID
        $stmt->bindParam(1, $this->Id);

        // Execute query
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties
        $this->Id = $row['Id'];
        $this->Name = $row['Name'];
        $this->LastName = $row['LastName'];
        $this->Email = $row['Email'];
    }

    public function Insert() {

        $query = 'INSERT INTO ' .
            $this->table . '
    SET
      Id = :Id ,
      Name = :Name ,
      LastName = :LastName ,
      Email = :Email
      ';

        // Prepare Statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->Name = htmlspecialchars(strip_tags($this->Name));
        $this->LastName = htmlspecialchars(strip_tags($this->LastName));

        // Bind data
        $stmt-> bindParam(':Id', $this->Id);
        $stmt-> bindParam(':Name', $this->Name);
        $stmt-> bindParam(':LastName', $this->LastName);
        $stmt-> bindParam(':Email', $this->Email);


        // Execute query
        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Update Category
    public function Update() {
        // Create Query
        $query = 'UPDATE ' .
            $this->table . '
    SET
      Name = :Name,
      LastName = :LastName,
      Email = :Email
      WHERE
      Id = :Id';

        // Prepare Statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->Name = htmlspecialchars(strip_tags($this->Name));
        $this->LastName = htmlspecialchars(strip_tags($this->LastName));

        // Bind data
        $stmt-> bindParam(':Name', $this->Name);
        $stmt-> bindParam(':Id', $this->Id);
        $stmt-> bindParam(':LastName', $this->LastName);
        $stmt-> bindParam(':Email', $this->Email);

        // Execute query
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete Category
    public function Delete() {
        // Create query
        $query = 'DELETE FROM ' . $this->table . ' WHERE Id = :Id';

        // Prepare Statement
        $stmt = $this->conn->prepare($query);

        // Bind Data
        $stmt-> bindParam(':Id', $this->Id);

        // Execute query
        if($stmt->execute()) {
            return true;
        }

        return false;
    }
}
