<?php
class OrderModel extends Model
{
    private $orderFields = array(
        "id",
        "orderno",
        "invoiceno",
        "customer_id",
        "customer_add",
        "ord_address1",
        "ord_address2",
        "ord_city",
        "ord_province",
        "ord_postal_code",
        "orderedby",
        "ord_terms",
        "shipvia",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by",
        "invoicedate",
        "reqdate",
        "shipdate",
        "istatus",
        "status_ord"
    );
    public function getOrderDetailsByOrderNo($orderId)
    {
        $query = $this->db->prepare("SELECT orders.*, c_usr.name as cname, 
            c_usr.sname as csname, u_usr.name as uname, u_usr.sname as usname 
            FROM orders 
            LEFT JOIN users as c_usr ON c_usr.id = orders.created_by 
            LEFT JOIN users as u_usr ON u_usr.id = orders.updated_by
            WHERE orders.orderno = :orderId");
        $query->bindParam(':orderId', $orderId);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $result['created_at'] = (!empty($result["created_at"])) ? Helper::getDateTimeByFormat($result["created_at"], 'm/d/Y h:i:s a') : "";
        $result['updated_at'] = (!empty($result["updated_at"])) ? Helper::getDateTimeByFormat($result["updated_at"], 'm/d/Y h:i:s a') : "";
        $result['shipdate'] = (!empty($result["shipdate"]) && $result["shipdate"] != "0000-00-00") ? Helper::getDateTimeByFormat($result['shipdate'], "m/d/Y") : "";
        $result['reqdate'] = (!empty($result["reqdate"]) && $result["reqdate"] != "0000-00-00") ? Helper::getDateTimeByFormat($result['reqdate'], "m/d/Y") : "";
        $result['invoicedate'] = (!empty($result["invoicedate"]) && $result["invoicedate"] != "0000-00-00") ? Helper::getDateTimeByFormat($result['invoicedate'], "m/d/Y") : "";

        return $result;
    }

    public function getOrderDetailsById($orderId)
    {
        $query = $this->db->prepare("SELECT orders.*, c_usr.name as cname, 
            c_usr.sname as csname, u_usr.name as uname, u_usr.sname as usname 
            FROM orders 
            LEFT JOIN users as c_usr ON c_usr.id = orders.created_by 
            LEFT JOIN users as u_usr ON u_usr.id = orders.updated_by
            WHERE orders.id = :orderId");
        $query->bindParam(':orderId', $orderId);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $result['created_at'] = (!empty($result["created_at"])) ? Helper::getDateTimeByFormat($result["created_at"], 'm/d/Y h:i:s a') : "";
        $result['updated_at'] = (!empty($result["updated_at"])) ? Helper::getDateTimeByFormat($result["updated_at"], 'm/d/Y h:i:s a') : "";
        $result['shipdate'] = (!empty($result["shipdate"]) && $result["shipdate"] != "0000-00-00") ? Helper::getDateTimeByFormat($result['shipdate'], "m/d/Y") : "";
        $result['reqdate'] = (!empty($result["reqdate"]) && $result["reqdate"] != "0000-00-00") ? Helper::getDateTimeByFormat($result['reqdate'], "m/d/Y") : "";
        $result['invoicedate'] = (!empty($result["invoicedate"]) && $result["invoicedate"] != "0000-00-00") ? Helper::getDateTimeByFormat($result['invoicedate'], "m/d/Y") : "";

        return $result;
    }

    public function createOrder($data)
    {
        try {
            $fields = [];
            $placeholders = [];

            if (!empty($this->orderNoExistOrNot($data['orderno']))) {
                return [
                    'data' => $data,
                    'msg' => "Order No is exist. Please type valid order number",
                    'status' => "orderno",
                ];
            }
            if (!empty($data['invoiceno']) && !empty($this->invoiceNoExistOrNot($data['invoiceno']))) {
                return [
                    'data' => $data,
                    'msg' => "Invoice No is exist. Please type valid invoice number",
                    'status' => "invoiceno",
                ];
            }
            $data['created_by'] = $this->user_id;
            $data['created_at'] = date("Y-m-d H:i:s");
            $data['shipvia'] = (isset($data['shipvia']) && !empty($data['shipvia'])) ? $data['shipvia'] : 0;
            $data['shipdate'] = (isset($data['shipdate']) && !empty($data['shipdate'])) ? Helper::getDateTimeByFormat($data['shipdate'], "Y-m-d") : NULL;
            $data['reqdate'] = (isset($data['reqdate']) && !empty($data['reqdate'])) ? Helper::getDateTimeByFormat($data['reqdate'], "Y-m-d") : NULL;
            $data['invoicedate'] = (isset($data['invoicedate']) && !empty($data['invoicedate'])) ? Helper::getDateTimeByFormat($data['invoicedate'], "Y-m-d") : NULL;

            foreach ($data as $key => $item) {
                if (in_array($key, $this->orderFields)) {
                    $fields[] = $key;
                    $placeholders[] = ':' . $key;
                }
            }

            $fieldList = implode(",", $fields);
            $placeholderList = implode(",", $placeholders);
            // var_dump($fieldList);
            // var_dump($placeholders);
            // var_dump($placeholderList);

            $sql = "INSERT INTO orders ($fieldList) VALUES ($placeholderList)";
            // Assume $pdo is your PDO connection object
            $stmt = $this->db->prepare($sql);
            foreach ($data as $key => $item) {
                if (in_array($key, $this->orderFields)) {
                    $stmt->bindValue(':' . $key, $item);
                }
            }
            $selectedData = [];
            if ($stmt->execute()) {
                // Fetch and display the inserted data
                // You can change the condition here
                // Get the last inserted ID
                $lastInsertedId = $this->db->lastInsertId();
                $selectSql = "SELECT * FROM orders WHERE id = :orderId";
                $selectStmt = $this->db->prepare($selectSql);
                // Use the appropriate key
                $selectStmt->bindValue(':orderId', $lastInsertedId);
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

    public function updateOrder($data)
    {
        try {
            $updateFields = [];
            $updatePlaceholders = [];
            $orderNoExistOrNot = $this->orderNoExistOrNot($data['orderno']);

            if (!empty($orderNoExistOrNot) && $orderNoExistOrNot["id"] != $data['ord_id']) {
                return [
                    'data' => $data,
                    'msg' => "Order No is exist. Please type valid order number",
                    'status' => "orderno",
                ];
            }
            if (!empty($data['invoiceno'])) {
                $invoiceNoExistOrNot = $this->invoiceNoExistOrNot($data['invoiceno']);
                if (!empty($invoiceNoExistOrNot) && $invoiceNoExistOrNot["id"] != $data['ord_id']) {
                    return [
                        'data' => $data,
                        'msg' => "Invoice No is exist. Please type valid invoice number",
                        'status' => "invoiceno",
                    ];
                }
            }

            $data['updated_by'] = $this->user_id;
            $data['updated_at'] = date("Y-m-d H:i:s");
            $data['shipvia'] = (isset($data['shipvia']) && !empty($data['shipvia'])) ? $data['shipvia'] : 0;
            $data['shipdate'] = (isset($data['shipdate']) && !empty($data['shipdate'])) ? Helper::getDateTimeByFormat($data['shipdate'], "Y-m-d") : NULL;
            $data['reqdate'] = (isset($data['reqdate']) && !empty($data['reqdate'])) ? Helper::getDateTimeByFormat($data['reqdate'], "Y-m-d") : NULL;
            $data['invoicedate'] = (isset($data['invoicedate']) && !empty($data['invoicedate'])) ? Helper::getDateTimeByFormat($data['invoicedate'], "Y-m-d") : NULL;

            foreach ($data as $key => $item) {
                if (in_array($key, $this->orderFields)) {
                    $updateFields[] = "$key = :$key";
                    $updatePlaceholders[":$key"] = $item;
                }
            }

            $updateSet = implode(", ", $updateFields);

            // UPDATE query
            $sql = "UPDATE orders SET $updateSet WHERE id = :orderId";
            // Assume $pdo is your PDO connection object
            $stmt = $this->db->prepare($sql);
            // Bind parameters
            foreach ($updatePlaceholders as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->bindValue(':orderId', $data['ord_id']);
            $selectedData = [];
            if ($stmt->execute()) {
                // Fetch and display the updated data
                $selectSql = "SELECT * FROM orders WHERE id = :orderId";
                $selectStmt = $this->db->prepare($selectSql);
                $selectStmt->bindValue(':orderId', $data['ord_id']);
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

    public function deleteOrder($orderId)
    {
        try {
            $orderItem = $this->getOrderDetailsById($orderId);
            // var_dump($orderItem);
            if ($orderItem) {
                $query = $this->db->prepare("DELETE FROM orders WHERE id = :orderId");
                $query->bindParam(':orderId', $orderId);
                if ($query->execute()) {
                    return [
                        'data' => $orderItem,
                        'msg' => "Your order has been deleted successfully!",
                        'status' => true,
                    ];
                } else {
                    $errorInfo = $query->errorInfo();
                    return [
                        'data' => $orderItem,
                        'msg' => "Your order has not been deleted successfully!" . ((isset($errorInfo[2]) && !empty($errorInfo[2])) ? "PDO Error: " . $errorInfo[2] : ""),
                        'status' => false,
                    ];
                }
            } else {
                return [
                    'data' => $orderItem,
                    'msg' => "Your order has not been deleted successfully!",
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

    public function orderNoExistOrNot($orderno)
    {
        $query = $this->db->prepare("SELECT * FROM orders WHERE orderno = :orderno");
        $query->bindParam(':orderno', $orderno);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function invoiceNoExistOrNot($invoiceno)
    {
        $query = $this->db->prepare("SELECT * FROM orders WHERE invoiceno = :invoiceno");
        $query->bindParam(':invoiceno', $invoiceno);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function invoiceUpdate($orderId, $data)
    {
        try {
            $orderItem = $this->getOrderDetailsById($orderId);
            // var_dump($orderItem);
            if (!empty($orderItem)) {
                // var_dump($data);
                if (isset($data['label'])) {
                    $labelText = $data['label'];
                    $query = $this->db->prepare("UPDATE orders SET inv_label = :invLabel WHERE id = :orderId");
                    $query->bindParam(':invLabel', $labelText);
                    $query->bindParam(':orderId', $orderId);
                } else if (isset($data['val'])) {
                    $val = !empty($data['val']) ? $data['val'] : 0.00;
                    $query = $this->db->prepare("UPDATE orders SET inv_val = :val WHERE id = :orderId");
                    $query->bindParam(':val', $val);
                    $query->bindParam(':orderId', $orderId);
                }

                if ($query->execute()) {
                    return [
                        'data' => $orderItem,
                        'msg' => "Your invoice has been updated successfully!",
                        'status' => true,
                    ];
                } else {
                    $errorInfo = $query->errorInfo();
                    return [
                        'data' => $orderItem,
                        'msg' => "Your invoice has not been updated successfully!" . ((isset($errorInfo[2]) && !empty($errorInfo[2])) ? "PDO Error: " . $errorInfo[2] : ""),
                        'status' => false,
                    ];
                }
            } else {
                return [
                    'data' => $orderItem,
                    'msg' => "Your invoice has not been updated successfully!",
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
