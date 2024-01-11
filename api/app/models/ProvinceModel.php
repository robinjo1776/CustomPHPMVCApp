<?php
class ProvinceModel extends Model
{
    private $tableFields = array(
        "id",
        "short_name",
        "english",
        "french",
        "created_by",
        "created_at",
        "updated_by",
        "updated_at",
        "status_prov"
    );
    public function getProvinceDetailsById($provinceId)
    {
        $query = $this->db->prepare("SELECT prov.*, c_usr.name as cname, 
            c_usr.sname as csname, u_usr.name as uname, u_usr.sname as usname 
            FROM provinces as prov
            LEFT JOIN users as c_usr ON c_usr.id = prov.created_by 
            LEFT JOIN users as u_usr ON u_usr.id = prov.updated_by
            WHERE prov.id = :provinceId");
        $query->bindParam(':provinceId', $provinceId);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $result['created_at'] = Helper::getDateTimeByFormat($result["created_at"], 'm/d/Y h:i:s a');
        $result['updated_at'] = (!empty($result["updated_at"])) ? Helper::getDateTimeByFormat($result["updated_at"], 'm/d/Y h:i:s a') : "";

        return $result;
    }

    public function createProvince($data)
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

            $sql = "INSERT INTO provinces ($fieldList) VALUES ($placeholderList)";
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
                $selectSql = "SELECT * FROM provinces WHERE id = :provinceId";
                $selectStmt = $this->db->prepare($selectSql);
                // Use the appropriate key
                $selectStmt->bindValue(':provinceId', $lastInsertedId);
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

    public function updateProvince($data)
    {
        try {
            $updateFields = [];
            $updatePlaceholders = [];
            $shortNameExistOrNot = $this->shortNameExistOrNot($data['short_name']);
            if (!empty($shortNameExistOrNot) && $shortNameExistOrNot["id"] != $data['province_id']) {
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
            $sql = "UPDATE provinces SET $updateSet WHERE id = :provinceId";
            // Assume $pdo is your PDO connection object
            $stmt = $this->db->prepare($sql);
            // Bind parameters
            foreach ($updatePlaceholders as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->bindValue(':provinceId', $data['province_id']);
            $selectedData = [];
            if ($stmt->execute()) {
                // Fetch and display the updated data
                $selectSql = "SELECT * FROM provinces WHERE id = :provinceId";
                $selectStmt = $this->db->prepare($selectSql);
                $selectStmt->bindValue(':provinceId', $data['province_id']);
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

    public function deleteProvince($provinceId)
    {
        try {
            $provinceItem = $this->getProvinceDetailsById($provinceId);
            // var_dump($provinceId);
            // var_dump($provinceItem);
            if ($provinceItem) {
                $query = $this->db->prepare("DELETE FROM provinces WHERE id = :provinceId");
                $query->bindParam(':provinceId', $provinceId);
                if ($query->execute()) {
                    return [
                        'data' => $provinceItem,
                        'msg' => "Your province has been deleted successfully!",
                        'status' => true,
                    ];
                } else {
                    $errorInfo = $query->errorInfo();
                    return [
                        'data' => $provinceItem,
                        'msg' => "You province has not been deleted successfully!" . ((isset($errorInfo[2]) && !empty($errorInfo[2])) ? "PDO Error: " . $errorInfo[2] : ""),
                        'status' => false,
                    ];
                }
            } else {
                return [
                    'data' => $provinceItem,
                    'msg' => "Your province has not been deleted successfully!",
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
        $query = $this->db->prepare("SELECT * FROM provinces WHERE short_name = :shortName");
        $query->bindParam(':shortName', $shortName);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}
