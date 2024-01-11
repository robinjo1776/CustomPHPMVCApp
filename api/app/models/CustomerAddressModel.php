<?php
class CustomerAddressModel extends Model
{
    private $tableFields = array(
        "id",
        "cus_id",
        "cust_type",
        "address1",
        "address2",
        'city',
        "province",
        "postalCode",
        "status_cust_address"
    );
    public function getCustomerAddressesByCustomerId($customerId)
    {
        $query = $this->db->prepare("SELECT * FROM cust_addresses WHERE cus_id = :cusId");
        $query->bindParam(':cusId', $customerId);
        $query->execute();
        $result = array(); // Initialize an array to store the results
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $row; // Add each row to the result array
        }

        return $result; // Return the array containing all addresses
    }

    public function createCustomerAddress($data)
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

            $sql = "INSERT INTO cust_addresses ($fieldList) VALUES ($placeholderList)";
            // Assume $pdo is your PDO connection object
            $stmt = $this->db->prepare($sql);
            foreach ($data as $key => $item) {
                if (in_array($key, $this->tableFields)) {
                    $stmt->bindValue(':' . $key, $item);
                }
            }
            $selectedData = [];
            $errorInfo = $stmt->errorInfo();
            // var_dump($errorInfo);
            if ($stmt->execute()) {
                // Fetch and display the inserted data
                // You can change the condition here
                // Get the last inserted ID
                $lastInsertedId = $this->db->lastInsertId();
                $selectSql = "SELECT * FROM cust_addresses WHERE id = :cusAddId";
                $selectStmt = $this->db->prepare($selectSql);
                // Use the appropriate key
                $selectStmt->bindValue(':cusAddId', $lastInsertedId);
                $selectStmt->execute();
                $selectedData = $selectStmt->fetch(PDO::FETCH_ASSOC);
                // var_dump($lastInsertedId);

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

    public function updateCustomerAddress($data)
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
            $sql = "UPDATE cust_addresses SET $updateSet WHERE id = :cusAddId";
            // Assume $pdo is your PDO connection object
            $stmt = $this->db->prepare($sql);
            // Bind parameters
            foreach ($updatePlaceholders as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->bindValue(':cusAddId', $data['cus_add_id']);
            $selectedData = [];
            if ($stmt->execute()) {
                // Fetch and display the updated data
                $selectSql = "SELECT * FROM cust_addresses WHERE id = :cusAddId";
                $selectStmt = $this->db->prepare($selectSql);
                $selectStmt->bindValue(':cusAddId', $data['cus_add_id']);
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

    public function deleteCustomerAddress($customerId)
    {
        try {
            $customerAddressItems = $this->getCustomerAddressesByCustomerId($customerId);
            // var_dump($customerId);
            // var_dump($customerAddressItems);
            if ($customerAddressItems) {
                $query = $this->db->prepare("DELETE FROM cust_addresses WHERE cus_id = :customerId");
                $query->bindParam(':customerId', $customerId);
                if ($query->execute()) {
                    return [
                        'data' => $customerAddressItems,
                        'msg' => "Your customer address has been deleted successfully!",
                        'status' => true,
                    ];
                } else {
                    return [
                        'data' => $customerAddressItems,
                        'msg' => "Your customer address has not been deleted successfully!",
                        'status' => false,
                    ];
                }
            } else {
                return [
                    'data' => $customerAddressItems,
                    'msg' => "Your customer has not been deleted successfully!",
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
