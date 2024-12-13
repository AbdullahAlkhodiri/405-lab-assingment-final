<?php
class Bookmark {
    private $id;
    private $title;
    private $link;
    private $dateAdded;
    private $dbConnection;
    private $dbTable = 'bookmarks';

    public function __construct($dbConnection) {
        $this->dbConnection = $dbConnection;
    }

    public function getId() { return $this->id; }
    public function getTitle() { return $this->title; }
    public function getLink() { return $this->link; }
    public function getDateAdded() { return $this->dateAdded; }

    public function setId($id) { $this->id = $id;}
    public function setTitle($title) { $this->title = $title; }
    public function setLink($link) { $this->link = $link; }

    public function create() {
        $query = "INSERT INTO " . $this->dbTable . "(title, link, date_added) VALUES(:title, :link, NOW())";
        $stmt = $this->dbConnection->prepare($query);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':link', $this->link);
        return $stmt->execute();
    }

    public function readAll() {
        $query = "SELECT * FROM " . $this->dbTable;
        $stmt = $this->dbConnection->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete() {
        $query = "DELETE FROM " . $this->dbTable . " WHERE id = :id";
        $stmt = $this->dbConnection->prepare($query);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }
}
