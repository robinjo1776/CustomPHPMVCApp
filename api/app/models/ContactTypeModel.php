<?php
class ContactTypeModel extends Model
{
    private $tableFields = array(
        "id",
        "description",
        "created_by",
        "created_at",
        "updated_by",
        "updated_at",
        "status_cont_type"
    );
    public function getContactTypeDetailsById($contactTypeId)
    {
        $query = $this->db->prepare("SELECT cont.*, c_usr.name as cname, 
            c_usr.sname as csname, u_usr.name as uname, u_usr.sname as usname 
            FROM contact_types as cont
            LEFT JOIN users as c_usr ON c_usr.id = cont.created_by 
            LEFT JOIN users as u_usr ON u_usr.id = cont.updated_by
            WHERE cont.id = :contactTypeId");
        $query->bindParam(':contactTypeId', $contactTypeId);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $result['created_at'] = Helper::getDateTimeByFormat($result["created_at"], 'm/d/Y h:i:s a');
        $result['updated_at'] = (!empty($result["updated_at"])) ? Helper::getDateTimeByFormat($result["updated_at"], 'm/d/Y h:i:s a') : "";

        return $result;
    }

    public function createContactType($data)
    {
        try {
            $fields = [];
            $placeholders = [];

            if (!empty($this->shortNameExistOrNot($data['description']))) {
                return [
                    'data' => $data,
                    'msg' => "Name is exist. Please type valid name.",
                    'status' => "description",
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

            $sql = "INSERT INTO contact_types ($fieldList) VALUES ($placeholderList)";
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
                $selectSql = "SELECT * FROM contact_types WHERE id = :contactTypeId";
                $selectStmt = $this->db->prepare($selectSql);
                // Use the appropriate key
                $selectStmt->bindValue(':contactTypeId', $lastInsertedId);
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

    public function updateContactType($data)
    {
        try {
            $updateFields = [];
            $updatePlaceholders = [];
            $shortNameExistOrNot = $this->shortNameExistOrNot($data['description']);
            if (!empty($shortNameExistOrNot) && $shortNameExistOrNot["id"] != $data['contact_type_id']) {
                return [
                    'data' => $data,
                    'msg' => "Name is exist. Please type valid name.",
                    'status' => "description",
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
            $sql = "UPDATE contact_types SET $updateSet WHERE id = :contactTypeId";
            // Assume $pdo is your PDO connection object
            $stmt = $this->db->prepare($sql);
            // Bind parameters
            foreach ($updatePlaceholders as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->bindValue(':contactTypeId', $data['contact_type_id']);
            $selectedData = [];
            if ($stmt->execute()) {
                // Fetch and display the updated data
                $selectSql = "SELECT * FROM contact_types WHERE id = :contactTypeId";
                $selectStmt = $this->db->prepare($selectSql);
                $selectStmt->bindValue(':contactTypeId', $data['contact_type_id']);
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

    public function deleteContactType($contactTypeId)
    {
        try {
            $$contactTypeItem = $this->getContactTypeDetailsById($contactTypeId);
            // var_dump($contactTypeId);
            // var_dump($contactTypeItem);
            if ($contactTypeItem) {
                $query = $this->db->prepare("DELETE FROM contact_types WHERE id = :contactTypeId");
                $query->bindParam(':contactTypeId', $contactTypeId);
                if ($query->execute()) {
                    return [
                        'data' => $contactTypeItem,
                        'msg' => "Your contact type has been deleted successfully!",
                        'status' => true,
                    ];
                } else {
                    $errorInfo = $query->errorInfo();
                    return [
                        'data' => $contactTypeItem,
                        'msg' => "You contact type has not been deleted successfully!" . ((isset($errorInfo[2]) && !empty($errorInfo[2])) ? "PDO Error: " . $errorInfo[2] : ""),
                        'status' => false,
                    ];
                }
            } else {
                return [
                    'data' => $contactTypeItem,
                    'msg' => "Your contact type has not been deleted successfully!",
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

    public function shortNameExistOrNot($name)
    {
        $query = $this->db->prepare("SELECT * FROM contact_types WHERE description = :name");
        $query->bindParam(':name', $name);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}
