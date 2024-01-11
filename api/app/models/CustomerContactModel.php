<?php
class CustomerContactModel extends Model
{
    private $tableFields = array(
        "id",
        "cus_id",
        "type",
        "name",
        "method",
        "detailed_info",
        "phone_email",
        "status_cust_phone"
    );
    public function getCustomerContactsByCustomerId($customerId)
    {
        $query = $this->db->prepare("SELECT * FROM cust_phones WHERE cus_id = :cusId");
        $query->bindParam(':cusId', $customerId);
        $query->execute();
        $result = array(); // Initialize an array to store the results
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $row; // Add each row to the result array
        }

        return $result; // Return the array containing all addresses
    }

    public function createCustomerContact($data)
    {
        try {
            $fields = [];
            $placeholders = [];

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

            $sql = "INSERT INTO cust_phones ($fieldList) VALUES ($placeholderList)";
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
                $selectSql = "SELECT * FROM cust_phones WHERE id = :cusAddId";
                $selectStmt = $this->db->prepare($selectSql);
                // Use the appropriate key
                $selectStmt->bindValue(':cusAddId', $lastInsertedId);
                $selectStmt->execute();
                $selectedData = $selectStmt->fetch(PDO::FETCH_ASSOC);


                // Display the selected data
                return [
                    'data' => $selectedData,
                    'msg' => "You data has been saved successfully!",
                    'status' => true,
                ];
            } else {
                return [
                    'data' => $selectedData,
                    'msg' => "You data has not been saved successfully!",
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

    public function updateCustomerContact($data)
    {
        try {
            $updateFields = [];
            $updatePlaceholders = [];

            foreach ($data as $key => $item) {
                if (in_array($key, $this->tableFields)) {
                    $updateFields[] = "$key = :$key";
                    $updatePlaceholders[":$key"] = $item;
                }
            }

            $updateSet = implode(", ", $updateFields);

            // UPDATE query
            $sql = "UPDATE cust_phones SET $updateSet WHERE id = :cusAddId";
            // Assume $pdo is your PDO connection object
            $stmt = $this->db->prepare($sql);
            // Bind parameters
            foreach ($updatePlaceholders as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->bindValue(':cusAddId', $data['cus_contact_id']);
            $selectedData = [];
            if ($stmt->execute()) {
                // Fetch and display the updated data
                $selectSql = "SELECT * FROM cust_phones WHERE id = :cusAddId";
                $selectStmt = $this->db->prepare($selectSql);
                $selectStmt->bindValue(':cusAddId', $data['cus_contact_id']);
                $selectStmt->execute();
                $selectedData = $selectStmt->fetch(PDO::FETCH_ASSOC);
                return [
                    'data' => $selectedData,
                    'msg' => "Your data has been updated successfully!",
                    'status' => true,
                ];
            } else {
                return [
                    'data' => $selectedData,
                    'msg' => "Your data has not been updated successfully!",
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

    public function deleteCustomerContact($customerId)
    {
        try {
            $customerContactItems = $this->getCustomerContactsByCustomerId($customerId);
            // var_dump($customerId);
            // var_dump($customerItem);
            if ($customerContactItems) {
                $query = $this->db->prepare("DELETE FROM cust_phones WHERE cus_id = :customerId");
                $query->bindParam(':customerId', $customerId);
                if ($query->execute()) {
                    return [
                        'data' => $customerContactItems,
                        'msg' => "Your customer contact has been deleted successfully!",
                        'status' => true,
                    ];
                } else {
                    return [
                        'data' => $customerContactItems,
                        'msg' => "Your customer contact has not been deleted successfully!",
                        'status' => false,
                    ];
                }
            } else {
                return [
                    'data' => $customerContactItems,
                    'msg' => "Your customer contact has not been deleted successfully!",
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
}
