<?php
class ContactMethodModel extends Model
{
    private $tableFields = array(
        "id",
        "method",
        "created_by",
        "created_at",
        "updated_by",
        "updated_at",
        "status_add_type"
    );
    public function getContactMethodDetailsById($contactMethodId)
    {
        $query = $this->db->prepare("SELECT conm.*, c_usr.name as cname, 
            c_usr.sname as csname, u_usr.name as uname, u_usr.sname as usname 
            FROM contact_methods as conm
            LEFT JOIN users as c_usr ON c_usr.id = conm.created_by 
            LEFT JOIN users as u_usr ON u_usr.id = conm.updated_by
            WHERE conm.id = :contactMethodId");
        $query->bindParam(':contactMethodId', $contactMethodId);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $result['created_at'] = Helper::getDateTimeByFormat($result["created_at"], 'm/d/Y h:i:s a');
        $result['updated_at'] = (!empty($result["updated_at"])) ? Helper::getDateTimeByFormat($result["updated_at"], 'm/d/Y h:i:s a') : "";

        return $result;
    }

    public function createContactMethod($data)
    {
        try {
            $fields = [];
            $placeholders = [];

            if (!empty($this->shortNameExistOrNot($data['method']))) {
                return [
                    'data' => $data,
                    'msg' => "Name is exist. Please type valid name.",
                    'status' => "method",
                ];
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

            $sql = "INSERT INTO contact_methods ($fieldList) VALUES ($placeholderList)";
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
                $selectSql = "SELECT * FROM contact_methods WHERE id = :contactMethodId";
                $selectStmt = $this->db->prepare($selectSql);
                // Use the appropriate key
                $selectStmt->bindValue(':contactMethodId', $lastInsertedId);
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

    public function updateContactMethod($data)
    {
        try {
            $updateFields = [];
            $updatePlaceholders = [];
            $shortNameExistOrNot = $this->shortNameExistOrNot($data['method']);
            if (!empty($shortNameExistOrNot) && $shortNameExistOrNot["id"] != $data['contact_method_id']) {
                return [
                    'data' => $data,
                    'msg' => "Name is exist. Please type valid name.",
                    'status' => "method",
                ];
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
            $sql = "UPDATE contact_methods SET $updateSet WHERE id = :contactMethodId";
            // Assume $pdo is your PDO connection object
            $stmt = $this->db->prepare($sql);
            // Bind parameters
            foreach ($updatePlaceholders as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->bindValue(':contactMethodId', $data['contact_method_id']);
            $selectedData = [];
            if ($stmt->execute()) {
                // Fetch and display the updated data
                $selectSql = "SELECT * FROM contact_methods WHERE id = :contactMethodId";
                $selectStmt = $this->db->prepare($selectSql);
                $selectStmt->bindValue(':contactMethodId', $data['contact_method_id']);
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

    public function deleteContactMethod($contactMethodId)
    {
        try {
            $$contactMethodItem = $this->getContactMethodDetailsById($contactMethodId);
            // var_dump($contactMethodId);
            // var_dump($contactMethodItem);
            if ($contactMethodItem) {
                $query = $this->db->prepare("DELETE FROM contact_methods WHERE id = :contactMethodId");
                $query->bindParam(':contactMethodId', $contactMethodId);
                if ($query->execute()) {
                    return [
                        'data' => $contactMethodItem,
                        'msg' => "Your contact method has been deleted successfully!",
                        'status' => true,
                    ];
                } else {
                    $errorInfo = $query->errorInfo();
                    return [
                        'data' => $contactMethodItem,
                        'msg' => "You contact method has not been deleted successfully!" . ((isset($errorInfo[2]) && !empty($errorInfo[2])) ? "PDO Error: " . $errorInfo[2] : ""),
                        'status' => false,
                    ];
                }
            } else {
                return [
                    'data' => $contactMethodItem,
                    'msg' => "Your contact method has not been deleted successfully!",
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

    public function shortNameExistOrNot($method)
    {
        $query = $this->db->prepare("SELECT * FROM contact_methods WHERE method = :method");
        $query->bindParam(':method', $method);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}
