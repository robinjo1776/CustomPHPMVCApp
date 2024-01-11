<?php
class UserModel extends Model
{
    private $tableFields = array(
        "id",
        "name",
        "uname",
        "sname",
        "utype",
        "pic",
        "phone",
        "add1",
        "add2",
        "pcode",
        "email",
        "pass",
        "hpass",
        "city",
        "province",
        "created_by",
        "created_at",
        "updated_by",
        "updated_at",
        "status_user"
    );
    public function getUserDetailsByUserNo($userId)
    {
        $query = $this->db->prepare("SELECT * FROM users WHERE id = :userId");
        $query->bindParam(':userId', $userId);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($data, $filesData)
    {
        try {
            $fields = [];
            $placeholders = [];

            if (!empty($this->userNameExistOrNot($data['uname']))) {
                return [
                    'data' => $data,
                    'msg' => "Username is exist. Please select another username!",
                    'status' => "uname",
                ];
            }

            if (isset($filesData['pic']) && !empty($filesData['pic'])) {
                $uploadedFile = $filesData['pic'];

                // Check for errors during upload
                if ($uploadedFile['error'] === UPLOAD_ERR_OK) {
                    $tmpName = $uploadedFile['tmp_name'];
                    $fileName = basename($uploadedFile['name']);

                    // Move the uploaded file to your desired location
                    if (move_uploaded_file($tmpName, UPLOAD_PATH . 'users/' . $fileName)) {
                        $data['pic'] = $fileName;
                    }
                }
            }

            if (isset($data['pass']) && !empty($data['pass'])) {
                // Combine the password with the salt
                $saltedPassword = $data['pass'] . SALT;
                // Hash the combined password using BCRYPT
                $data['hpass'] = password_hash($saltedPassword, PASSWORD_BCRYPT);
            }
            $data['created_by'] = $this->user_id;
            $data['created_at'] = date("Y-m-d H:i:s");

            foreach ($data as $key => $item) {
                if (in_array($key, $this->tableFields)) {
                    $fields[] = $key;
                    $placeholders[] = ':' . $key;
                }
            }

            $fieldList = implode(",", $fields);
            $placeholderList = implode(",", $placeholders);
            // var_dump($fieldList);
            // var_dump($placeholders);
            // var_dump($placeholderList);

            $sql = "INSERT INTO users ($fieldList) VALUES ($placeholderList)";
            // Assume $pdo is your PDO connection object
            $stmt = $this->db->prepare($sql);
            foreach ($data as $key => $item) {
                if (in_array($key, $this->tableFields)) {
                    $stmt->bindValue(':' . $key, $item);
                }
            }
            $selectedData = [];
            if ($stmt->execute()) {
                // Fetch and display the inserted data
                // You can change the condition here
                // Get the last inserted ID
                $lastInsertedId = $this->db->lastInsertId();
                $selectSql = "SELECT * FROM users WHERE id = :userId";
                $selectStmt = $this->db->prepare($selectSql);
                // Use the appropriate key
                $selectStmt->bindValue(':userId', $lastInsertedId);
                $selectStmt->execute();
                $selectedData = $selectStmt->fetch(PDO::FETCH_ASSOC);

                // Display the selected data
                return [
                    'data' => $selectedData,
                    'msg' => "You data has been saved successfully!",
                    'status' => true,
                ];
            } else {
                $errorInfo = $stmt->errorInfo();
                return [
                    'data' => $selectedData,
                    'msg' => "You data has not been saved successfully!" . ((isset($errorInfo[2]) && !empty($errorInfo[2])) ? "PDO Error: " . $errorInfo[2] : ""),
                    'status' => false,
                ];
            }
        } catch (PDOException $e) {
            return [
                'data' => [],
                'msg' => "PDO Error: " . $e->getMessage(),
                'status' => false,
            ];
        }
    }

    public function updateUser($data, $filesData)
    {
        try {
            $updateFields = [];
            $updatePlaceholders = [];
            $userNameExistOrNot = $this->userNameExistOrNot($data['uname']);

            if (!empty($userNameExistOrNot) && $userNameExistOrNot["id"] != $data['user_id']) {
                return [
                    'data' => $data,
                    'msg' => "Username is exist. Please select another username!",
                    'status' => "uname",
                ];
            }

            if (isset($filesData['pic']) && !empty($filesData['pic'])) {
                $uploadedFile = $filesData['pic'];

                // Check for errors during upload
                if ($uploadedFile['error'] === UPLOAD_ERR_OK) {
                    $tmpName = $uploadedFile['tmp_name'];
                    $fileName = basename($uploadedFile['name']);

                    // Move the uploaded file to your desired location
                    if (move_uploaded_file($tmpName, UPLOAD_PATH . 'users/' . $fileName)) {
                        $data['pic'] = $fileName;
                    }
                }
            }

            if (isset($data['pass']) && !empty($data['pass'])) {
                // Combine the password with the salt
                $saltedPassword = $data['pass'] . SALT;
                // Hash the combined password using BCRYPT
                $data['hpass'] = password_hash($saltedPassword, PASSWORD_BCRYPT);
            }
            $data['updated_by'] = $this->user_id;
            $data['updated_at'] = date("Y-m-d H:i:s");

            foreach ($data as $key => $item) {
                if (in_array($key, $this->tableFields)) {
                    $updateFields[] = "$key = :$key";
                    $updatePlaceholders[":$key"] = $item;
                }
            }

            $updateSet = implode(", ", $updateFields);

            // UPDATE query
            $sql = "UPDATE users SET $updateSet WHERE id = :userId";
            // Assume $pdo is your PDO connection object
            $stmt = $this->db->prepare($sql);
            // Bind parameters
            foreach ($updatePlaceholders as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->bindValue(':userId', $data['user_id']);
            $selectedData = [];
            if ($stmt->execute()) {
                // Fetch and display the updated data
                $selectSql = "SELECT * FROM users WHERE id = :userId";
                $selectStmt = $this->db->prepare($selectSql);
                $selectStmt->bindValue(':userId', $data['user_id']);
                $selectStmt->execute();
                $selectedData = $selectStmt->fetch(PDO::FETCH_ASSOC);
                return [
                    'data' => $selectedData,
                    'msg' => "Your data has been updated successfully!",
                    'status' => true,
                ];
            } else {
                $errorInfo = $stmt->errorInfo();
                return [
                    'data' => $selectedData,
                    'msg' => "You data has not been saved successfully!" . ((isset($errorInfo[2]) && !empty($errorInfo[2])) ? "PDO Error: " . $errorInfo[2] : ""),
                    'status' => false,
                ];
            }
        } catch (PDOException $e) {
            return [
                'data' => [],
                'msg' => "PDO Error: " . $e->getMessage(),
                'status' => false,
            ];
        }
    }

    public function deleteUser($userId)
    {
        try {
            $userItem = $this->getUserDetailsByUserNo($userId);
            // var_dump($userId);
            // var_dump($userItem);
            if ($userItem) {
                $query = $this->db->prepare("DELETE FROM users WHERE id = :userId");
                $query->bindParam(':userId', $userId);
                if ($query->execute()) {
                    return [
                        'data' => $userItem,
                        'msg' => "Your user has been deleted successfully!",
                        'status' => true,
                    ];
                } else {
                    $errorInfo = $query->errorInfo();
                    return [
                        'data' => $userItem,
                        'msg' => "You user has not been deleted successfully!" . ((isset($errorInfo[2]) && !empty($errorInfo[2])) ? "PDO Error: " . $errorInfo[2] : ""),
                        'status' => false,
                    ];
                }
            } else {
                return [
                    'data' => $userItem,
                    'msg' => "Your user has not been deleted successfully!",
                    'status' => false,
                ];
            }
        } catch (PDOException $e) {
            return [
                'data' => [],
                'msg' => "PDO Error: " . $e->getMessage(),
                'status' => false,
            ];
        }
    }

    public function userNameExistOrNot($uname)
    {
        $query = $this->db->prepare("SELECT * FROM users WHERE uname = :uname");
        $query->bindParam(':uname', $uname);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}
