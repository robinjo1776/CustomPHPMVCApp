<?php
class LoadModel extends Model
{
    private $tableFields = array(
        "id",
        "invoiceno",
        "pnumber",
        "pdescription",
        "loading_date",
        "cases",
        "birds",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by",
        "status_load"
    );
    public function getLoadDetailsByLoadNo($loadId)
    {
        $query = $this->db->prepare("SELECT
            lda.id,
            lda.invoiceno,
            lda.pnumber,
            lda.pdescription,
            lda.loading_date,
            lda.cases,
            lda.birds,
            lda.created_by,
            lda.created_at,
            lda.updated_by,
            lda.updated_at,
            lda.status_load
        FROM loads AS lda 
        LEFT JOIN users as c_usr ON c_usr.id = lda.created_by 
        LEFT JOIN users as u_usr ON u_usr.id = lda.updated_by 
        WHERE lda.id = :loadId");
        $query->bindParam(':loadId', $loadId);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $result['loading_date'] = Helper::getDateTimeByFormat($result["loading_date"], 'm/d/Y');
        $result['created_at'] = Helper::getDateTimeByFormat($result["created_at"], 'm/d/Y h:i:s a');
        $result['updated_at'] = (!empty($result["updated_at"])) ? Helper::getDateTimeByFormat($result["updated_at"], 'm/d/Y h:i:s a') : "";

        return $result;
    }

    public function createLoad($data)
    {
        try {
            $fields = [];
            $placeholders = [];

            if (!empty($this->palletNumberExistOrNot($data['pnumber']))) {
                return [
                    'data' => $data,
                    'msg' => "Pallet Number is exist. Please type valid pallet number.",
                    'status' => "pnumber",
                ];
            }
            $data['created_by'] = $this->user_id;
            $data['created_at'] = date("Y-m-d H:i:s");
            $data['loading_date'] = (isset($data['loading_date']) && !empty($data['loading_date'])) ? Helper::getDateTimeByFormat($data['loading_date'], "Y-m-d") : NULL;

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

            $sql = "INSERT INTO loads ($fieldList) VALUES ($placeholderList)";
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
                $selectSql = "SELECT * FROM loads WHERE id = :loadId";
                $selectStmt = $this->db->prepare($selectSql);
                // Use the appropriate key
                $selectStmt->bindValue(':loadId', $lastInsertedId);
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

    public function updateLoad($data)
    {
        try {
            $updateFields = [];
            $updatePlaceholders = [];
            $palletNumberExistOrNot = $this->palletNumberExistOrNot($data['pnumber']);
            if (!empty($palletNumberExistOrNot) && $palletNumberExistOrNot["id"] != $data['lod_id']) {
                return [
                    'data' => $data,
                    'msg' => "Pallet Number is exist. Please type valid pallet number.",
                    'status' => "pnumber",
                ];
            }
            $data['updated_by'] = $this->user_id;
            $data['updated_at'] = date("Y-m-d H:i:s");
            $data['loading_date'] = (isset($data['loading_date']) && !empty($data['loading_date'])) ? Helper::getDateTimeByFormat($data['loading_date'], "Y-m-d") : NULL;

            foreach ($data as $key => $item) {
                if (in_array($key, $this->tableFields)) {
                    $updateFields[] = "$key = :$key";
                    $updatePlaceholders[":$key"] = $item;
                }
            }

            $updateSet = implode(", ", $updateFields);

            // UPDATE query
            $sql = "UPDATE loads SET $updateSet WHERE id = :loadId";
            // Assume $pdo is your PDO connection object
            $stmt = $this->db->prepare($sql);
            // Bind parameters
            foreach ($updatePlaceholders as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->bindValue(':loadId', $data['lod_id']);
            $selectedData = [];
            if ($stmt->execute()) {
                // Fetch and display the updated data
                $selectSql = "SELECT * FROM loads WHERE id = :loadId";
                $selectStmt = $this->db->prepare($selectSql);
                $selectStmt->bindValue(':loadId', $data['lod_id']);
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

    public function deleteLoad($loadId)
    {
        try {
            $loadItem = $this->getLoadDetailsByLoadNo($loadId);
            // var_dump($loadId);
            // var_dump($loadItem);
            if ($loadItem) {
                $query = $this->db->prepare("DELETE FROM loads WHERE id = :loadId");
                $query->bindParam(':loadId', $loadId);
                if ($query->execute()) {
                    return [
                        'data' => $loadItem,
                        'msg' => "Your load has been deleted successfully!",
                        'status' => true,
                    ];
                } else {
                    $errorInfo = $query->errorInfo();
                    return [
                        'data' => $loadItem,
                        'msg' => "Your load has not been deleted successfully!" . ((isset($errorInfo[2]) && !empty($errorInfo[2])) ? "PDO Error: " . $errorInfo[2] : ""),
                        'status' => false,
                    ];
                }
            } else {
                return [
                    'data' => $loadItem,
                    'msg' => "Your load has not been deleted successfully!",
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

    public function palletNumberExistOrNot($pnumber)
    {
        $query = $this->db->prepare("SELECT * FROM loads WHERE pnumber = :pnumber");
        $query->bindParam(':pnumber', $pnumber);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}
