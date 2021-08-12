<?php

namespace App\Model;

class Cost
{
    private $conn;
    private $table_name = "costs";

    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
        $this->created_at = $this->updated_at = date('Y-m-d H:i:s');
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC");
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getMyPosts($userId) {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table_name . " WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function first($id) {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table_name . " WHERE id = :id");
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function search($search) {
        $query = "SELECT * FROM " . $this->table_name . " 
            WHERE title LIKE :search OR body LIKE :search
            ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([":search" => '%' . $search . '%']);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function create(array $data)
    {
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    user_id=:user_id, title=:title, body=:body, img=:img, created_at=:created_at";

        $stmt = $this->conn->prepare($query);

        if ($stmt->execute([
            ":user_id" => $_SESSION['id'],
            ":title" => $data['title'],
            ":body" => $data['body'],
            ":img" => $data['img'],
            ":created_at" => $this->created_at
        ])) {
            return true;
        }

        return false;
    }

    function update($id, $data)
    {
        $query = "UPDATE " . $this->table_name . " 
            SET title= :title, body= :body, img= :img, updated_at= :updated_at
            WHERE id= :id AND user_id= :user_id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ":id" => $id,
            ":title" => $data['title'],
            ":user_id" => $_SESSION['id'],
            ":body" => $data['body'],
            ":img" => $data['img'],
            ":updated_at" => $this->updated_at
        ]);

        if($stmt){
            return true;
        } else {
            return false;
        }
    }

    function delete($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id= :id AND user_id= :user_id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(array(
            ":id" => $id,
            ":user_id" => $_SESSION['id']
        ));

        if($stmt){
            return true;
        } else {
            return false;
        }
    }
}