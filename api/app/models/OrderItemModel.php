<?php
class OrderItemModel extends Model
{
    private $tableFields = array(
        "id",
        "orderid",
        "code",
        "cat_code",
        "weight",
        "qty",
        "minw",
        "maxw",
        "status_item",
        "description",
        "price",
        "unit",
        "box",
        "tr_type",
    );
    public function getOrderItemsByOrderId($orderId)
    {
        $query = $this->db->prepare("SELECT * FROM items WHERE orderid = :orderid");
        $query->bindParam(':orderid', $orderId);
        $query->execute();
        $result = array(); // Initialize an array to store the results
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $row; // Add each row to the result array
        }

        return $result; // Return the array containing all addresses
    }

    public function createOrderItem($data)
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

            $sql = "INSERT INTO items ($fieldList) VALUES ($placeholderList)";
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
                $selectSql = "SELECT * FROM items WHERE id = :itemId";
                $selectStmt = $this->db->prepare($selectSql);
                // Use the appropriate key
                $selectStmt->bindValue(':itemId', $lastInsertedId);
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

    public function updateOrderItem($data)
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
            $sql = "UPDATE items SET $updateSet WHERE id = :itemId";
            // Assume $pdo is your PDO connection object
            $stmt = $this->db->prepare($sql);
            // Bind parameters
            foreach ($updatePlaceholders as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->bindValue(':itemId', $data['ord_item_id']);
            $selectedData = [];
            if ($stmt->execute()) {
                // Fetch and display the updated data
                $selectSql = "SELECT * FROM items WHERE id = :itemId";
                $selectStmt = $this->db->prepare($selectSql);
                $selectStmt->bindValue(':itemId', $data['ord_item_id']);
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

    public function deleteOrderItem($orderId)
    {
        try {
            $orderItems = $this->getOrderItemsByOrderId($orderId);
            // var_dump($orderId);
            // var_dump($customerAddressItems);
            if ($orderItems) {
                $query = $this->db->prepare("DELETE FROM items WHERE orderid = :orderId");
                $query->bindParam(':orderId', $orderId);
                if ($query->execute()) {
                    return [
                        'data' => $orderItems,
                        'msg' => "Your order item has been deleted successfully!",
                        'status' => true,
                    ];
                } else {
                    $errorInfo = $query->errorInfo();
                    return [
                        'data' => $orderItems,
                        'msg' => "Your order item has not been deleted successfully!" . ((isset($errorInfo[2]) && !empty($errorInfo[2])) ? "PDO Error: " . $errorInfo[2] : ""),
                        'status' => false,
                    ];
                }
            } else {
                return [
                    'data' => $orderItems,
                    'msg' => "Your order item has not been deleted successfully!",
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
