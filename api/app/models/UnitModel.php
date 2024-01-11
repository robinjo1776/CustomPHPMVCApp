<?php
class UnitModel extends Model
{
    private $tableFields = array(
        "id",
        "short_name",
        "description",
        "created_by",
        "created_at",
        "updated_by",
        "updated_at",
        "status_unit"
    );
    public function getUnitDetailsById($unitId)
    {
        $query = $this->db->prepare("SELECT units.*, c_usr.name as cname, 
            c_usr.sname as csname, u_usr.name as uname, u_usr.sname as usname 
            FROM units as units
            LEFT JOIN users as c_usr ON c_usr.id = units.created_by 
            LEFT JOIN users as u_usr ON u_usr.id = units.updated_by 
            WHERE units.id = :unitId");
        $query->bindParam(':unitId', $unitId);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $result['created_at'] = Helper::getDateTimeByFormat($result["created_at"], 'm/d/Y h:i:s a');
        $result['updated_at'] = (!empty($result["updated_at"])) ? Helper::getDateTimeByFormat($result["updated_at"], 'm/d/Y h:i:s a') : "";

        return $result;
    }

    public function createUnit($data)
    {
        try {
            $fields = [];
            $placeholders = [];

            if (!empty($this->shortNameExistOrNot($data['short_name']))) {
                return [
                    'data' => $data,
                    'msg' => "Short Name is exist. Please type valid short name.",
                    'status' => "shortname",
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

            $sql = "INSERT INTO units ($fieldList) VALUES ($placeholderList)";
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
                $selectSql = "SELECT * FROM units WHERE id = :unitId";
                $selectStmt = $this->db->prepare($selectSql);
                // Use the appropriate key
                $selectStmt->bindValue(':unitId', $lastInsertedId);
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

    public function updateUnit($data)
    {
        try {
            $updateFields = [];
            $updatePlaceholders = [];
            $shortNameExistOrNot = $this->shortNameExistOrNot($data['short_name']);
            if (!empty($shortNameExistOrNot) && $shortNameExistOrNot["id"] != $data['unit_id']) {
                return [
                    'data' => $data,
                    'msg' => "Short Name is exist. Please type valid short name.",
                    'status' => "shortname",
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
            $sql = "UPDATE units SET $updateSet WHERE id = :unitId";
            // Assume $pdo is your PDO connection object
            $stmt = $this->db->prepare($sql);
            // Bind parameters
            foreach ($updatePlaceholders as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->bindValue(':unitId', $data['unit_id']);
            $selectedData = [];
            if ($stmt->execute()) {
                // Fetch and display the updated data
                $selectSql = "SELECT * FROM units WHERE id = :unitId";
                $selectStmt = $this->db->prepare($selectSql);
                $selectStmt->bindValue(':unitId', $data['unit_id']);
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

    public function deleteUnit($unitId)
    {
        try {
            $unitItem = $this->getUnitDetailsById($unitId);
            // var_dump($unitId);
            // var_dump($unitItem);
            if ($unitItem) {
                $query = $this->db->prepare("DELETE FROM units WHERE id = :unitId");
                $query->bindParam(':unitId', $unitId);
                if ($query->execute()) {
                    return [
                        'data' => $unitItem,
                        'msg' => "Your unit has been deleted successfully!",
                        'status' => true,
                    ];
                } else {
                    $errorInfo = $query->errorInfo();
                    return [
                        'data' => $unitItem,
                        'msg' => "You unit has not been deleted successfully!" . ((isset($errorInfo[2]) && !empty($errorInfo[2])) ? "PDO Error: " . $errorInfo[2] : ""),
                        'status' => false,
                    ];
                }
            } else {
                return [
                    'data' => $unitItem,
                    'msg' => "Your unit has not been deleted successfully!",
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

    public function shortNameExistOrNot($shortName)
    {
        $query = $this->db->prepare("SELECT * FROM units WHERE short_name = :shortName");
        $query->bindParam(':shortName', $shortName);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}
