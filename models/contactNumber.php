<?php
class ContactNumber
{
    // DB Stuff
    private $conn;
    private $table = 'contactnumbers';

    // Properties
    public $Id;
    public $ContactId;
    public $Name;
    public $Value;

    // Constructor with DB
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function GetByContactId() {
        // Create query
        $query = 'SELECT
        Id,
        ContactId,
        Name,
        Value 
      FROM
        ' . $this->table . '
      WHERE ContactId = ?
      ORDER BY
        Name DESC';

        // Prepare statement
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ContactId);

        // Execute query
        $stmt->execute();

        return $stmt;
    }


    public function Insert() {

        $query = 'INSERT INTO ' .
            $this->table . '
    SET
      ContactId = :ContactId ,
      Name = :Name ,
      Value = :Value 
      ';

        // Prepare Statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->Name = htmlspecialchars(strip_tags($this->Name));
        $this->LastName = htmlspecialchars(strip_tags($this->LastName));

        // Bind data
        $stmt-> bindParam(':ContactId', $this->ContactId);
        $stmt-> bindParam(':Name', $this->Name);
        $stmt-> bindParam(':Value', $this->Value);


        // Execute query
        if($stmt->execute()) {
            return true;
        }

        echo ($stmt->error);

        return false;
    }

    public function Update() {

        // Create Query
        $query = 'UPDATE ' .
            $this->table . '
    SET
      Name = :Name,
      Value = :Value
      WHERE
      Id = :Id';

        // Prepare Statement
        $stmt = $this->conn->prepare($query);


        // Bind data

        $stmt-> bindParam(':Name', $this->Name);
        $stmt-> bindParam(':Id', $this->Id);
        $stmt-> bindParam(':Value', $this->Value);
        // Execute query
        if($stmt->execute()) {
            return true;
        }
        return false;


    }

    public function Delete() {
        // Create query
        $query = 'DELETE FROM ' . $this->table . ' WHERE Id = :Id  ';

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

    public function DeleteByContactId() {
        // Create query
        $query = 'DELETE FROM ' . $this->table . ' WHERE ContactId = :Id';

        // Prepare Statement
        $stmt = $this->conn->prepare($query);

        // Bind Data
        $stmt-> bindParam(':Id', $this->ContactId);

        // Execute query
        if($stmt->execute()) {
            return true;
        }

        return false;
    }

}