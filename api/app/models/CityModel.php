<?php
class CityModel extends Model
{
    private $tableFields = array(
        "id",
        "city_name",
        "pid",
        "psname",
        "pname",
        "created_by",
        "created_at",
        "updated_by",
        "updated_at",
        "status_prov"
    );
    private $provinceModel;

    function __construct()
    {
        parent::__construct();
        Helper::AddModel("ProvinceModel");
        $this->provinceModel = new ProvinceModel();
    }
    public function getCityDetailsById($cityId)
    {
        $query = $this->db->prepare("SELECT prov.*, c_usr.name as cname, 
            c_usr.sname as csname, u_usr.name as uname, u_usr.sname as usname 
            FROM cities as prov
            LEFT JOIN users as c_usr ON c_usr.id = prov.created_by 
            LEFT JOIN users as u_usr ON u_usr.id = prov.updated_by
            WHERE prov.id = :cityId");
        $query->bindParam(':cityId', $cityId);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $result['created_at'] = Helper::getDateTimeByFormat($result["created_at"], 'm/d/Y h:i:s a');
        $result['updated_at'] = (!empty($result["updated_at"])) ? Helper::getDateTimeByFormat($result["updated_at"], 'm/d/Y h:i:s a') : "";

        return $result;
    }

    public function createCity($data)
    {
        try {
            $fields = [];
            $placeholders = [];

            if (!empty($this->cityNameExistOrNot($data['city_name']))) {
                return [
                    'data' => $data,
                    'msg' => "City is exist. Please type valid city name.",
                    'status' => "city_name",
                ];
            }

            $province = $this->provinceModel->getProvinceDetailsById($data['pid']);
            $data['pname'] = $province["english"];
            $data['psname'] = $province["short_name"];
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

            $sql = "INSERT INTO cities ($fieldList) VALUES ($placeholderList)";
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
                $selectSql = "SELECT * FROM cities WHERE id = :cityId";
                $selectStmt = $this->db->prepare($selectSql);
                // Use the appropriate key
                $selectStmt->bindValue(':cityId', $lastInsertedId);
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

    public function updateCity($data)
    {
        try {
            $updateFields = [];
            $updatePlaceholders = [];
            $shortNameExistOrNot = $this->cityNameExistOrNot($data['city_name']);
            if (!empty($shortNameExistOrNot) && $shortNameExistOrNot["id"] != $data['city_id']) {
                return [
                    'data' => $data,
                    'msg' => "City is exist. Please type valid city name.",
                    'status' => "city_name",
                ];
            }
            $province = $this->provinceModel->getProvinceDetailsById($data['pid']);
            $data['pname'] = $province["english"];
            $data['psname'] = $province["short_name"];
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
            $sql = "UPDATE cities SET $updateSet WHERE id = :cityId";
            // Assume $pdo is your PDO connection object
            $stmt = $this->db->prepare($sql);
            // Bind parameters
            foreach ($updatePlaceholders as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->bindValue(':cityId', $data['city_id']);
            $selectedData = [];
            if ($stmt->execute()) {
                // Fetch and display the updated data
                $selectSql = "SELECT * FROM cities WHERE id = :cityId";
                $selectStmt = $this->db->prepare($selectSql);
                $selectStmt->bindValue(':cityId', $data['city_id']);
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

    public function deleteCity($cityId)
    {
        try {
            $cityItem = $this->getCityDetailsById($cityId);
            // var_dump($cityId);
            // var_dump($cityItem);
            if ($cityItem) {
                $query = $this->db->prepare("DELETE FROM cities WHERE id = :cityId");
                $query->bindParam(':cityId', $cityId);
                if ($query->execute()) {
                    return [
                        'data' => $cityItem,
                        'msg' => "Your city has been deleted successfully!",
                        'status' => true,
                    ];
                } else {
                    $errorInfo = $query->errorInfo();
                    return [
                        'data' => $cityItem,
                        'msg' => "You city has not been deleted successfully!" . ((isset($errorInfo[2]) && !empty($errorInfo[2])) ? "PDO Error: " . $errorInfo[2] : ""),
                        'status' => false,
                    ];
                }
            } else {
                return [
                    'data' => $cityItem,
                    'msg' => "Your city has not been deleted successfully!",
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

    public function cityNameExistOrNot($cityName)
    {
        $query = $this->db->prepare("SELECT * FROM cities WHERE city_name = :cityName");
        $query->bindParam(':cityName', $cityName);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}
