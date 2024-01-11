<?php

use PhpOffice\PhpSpreadsheet\IOFactory;

class CustomerModel extends Model
{
    private $tableFields = array(
        "id",
        "name",
        "ctype",
        "address_bill",
        "address_ship",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by",
        "status_cust"
    );
    private $importFields = array(
        "Customer full name",
        "Email address",
        "Billing address",
        "Billing city",
        "Billing province",
        "Billing Postal code"
    );
    public function getCustomerAddressesByCustomerId($customerId)
    {
        $query = $this->db->prepare("SELECT cust_add.*, addt.description as name
            FROM cust_addresses as cust_add
            LEFT JOIN address_types as addt ON addt.id = cust_add.cust_type
            WHERE cust_add.cus_id = :cusId");
        $query->bindParam(':cusId', $customerId);
        $query->execute();
        $result = array(); // Initialize an array to store the results
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $row; // Add each row to the result array
        }

        return $result; // Return the array containing all addresses
    }

    public function getCustomerDetailsByCustomerId($customerId)
    {
        $query = $this->db->prepare("SELECT cus.*, c_usr.name as cname, 
            c_usr.sname as csname, u_usr.name as uname, u_usr.sname as usname
            FROM customers AS cus 
            LEFT JOIN users as c_usr ON c_usr.id = cus.created_by 
            LEFT JOIN users as u_usr ON u_usr.id = cus.updated_by 
            WHERE cus.id = :customerId");
        $query->bindParam(':customerId', $customerId);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $result['created_at'] = Helper::getDateTimeByFormat($result["created_at"], 'm/d/Y h:i:s a');
        $result['updated_at'] = (!empty($result["updated_at"])) ? Helper::getDateTimeByFormat($result["updated_at"], 'm/d/Y h:i:s a') : "";

        return $result;
    }

    public function createCustomer($data)
    {
        try {
            $fields = [];
            $placeholders = [];
            if (!empty($this->nameExistOrNot($data['name']))) {
                return [
                    'data' => $data,
                    'msg' => "Company Name is exist. Please type valid name.",
                    'status' => "name",
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

            $sql = "INSERT INTO customers ($fieldList) VALUES ($placeholderList)";
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
                $selectSql = "SELECT * FROM customers WHERE id = :customerId";
                $selectStmt = $this->db->prepare($selectSql);
                // Use the appropriate key
                $selectStmt->bindValue(':customerId', $lastInsertedId);
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

    public function updateCustomer($data)
    {
        try {
            $updateFields = [];
            $updatePlaceholders = [];
            $nameExistOrNot = $this->nameExistOrNot($data['name']);
            if (!empty($nameExistOrNot) && $nameExistOrNot["id"] != $data['customer_id']) {
                return [
                    'data' => $data,
                    'msg' => "Company Name is exist. Please type valid name.",
                    'status' => "name",
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
            $sql = "UPDATE customers SET $updateSet WHERE id = :customerId";
            // Assume $pdo is your PDO connection object
            $stmt = $this->db->prepare($sql);
            // Bind parameters
            foreach ($updatePlaceholders as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->bindValue(':customerId', $data['customer_id']);
            $selectedData = [];
            if ($stmt->execute()) {
                // Fetch and display the updated data
                $selectSql = "SELECT * FROM customers WHERE id = :customerId";
                $selectStmt = $this->db->prepare($selectSql);
                $selectStmt->bindValue(':customerId', $data['customer_id']);
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

    public function deleteCustomer($customerId)
    {
        try {
            $customerItem = $this->getCustomerDetailsByCustomerId($customerId);
            // var_dump($customerId);
            // var_dump($customerItem);
            if ($customerItem) {
                $query = $this->db->prepare("DELETE FROM customers WHERE id = :customerId");
                $query->bindParam(':customerId', $customerId);
                if ($query->execute()) {
                    return [
                        'data' => $customerItem,
                        'msg' => "Your customer has been deleted successfully!",
                        'status' => true,
                    ];
                } else {
                    $errorInfo = $query->errorInfo();
                    return [
                        'data' => $customerItem,
                        'msg' => "Your customer has not been deleted successfully!" . ((isset($errorInfo[2]) && !empty($errorInfo[2])) ? "PDO Error: " . $errorInfo[2] : ""),
                        'status' => false,
                    ];
                }
            } else {
                return [
                    'data' => $customerItem,
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
    public function nameExistOrNot($name)
    {
        $query = $this->db->prepare("SELECT * FROM customers WHERE name = :name");
        $query->bindParam(':name', $name);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function uploadFile($data, $filesData)
    {
        try {
            if (isset($filesData['importFile']) && !empty($filesData['importFile'])) {
                $uploadedFile = $filesData['importFile'];

                if ($uploadedFile['error'] === UPLOAD_ERR_OK) {
                    $tmpName = $uploadedFile['tmp_name'];
                    $originalFileName = basename($uploadedFile['name']);
                    // Get the file extension using pathinfo
                    $extension = pathinfo($originalFileName, PATHINFO_EXTENSION);

                    // Define the target directory where you want to move the file
                    $targetDirectory = UPLOAD_PATH . 'importCustomer/';

                    // Check if the target directory exists; if not, create it
                    if (!file_exists($targetDirectory)) {
                        mkdir($targetDirectory, 0755, true);
                    }

                    // Generate a new file name (you can use a custom logic here)
                    $newFileName = time() . '.' . $extension; // Define your custom logic

                    // Combine the target directory and the new file name
                    $destinationPath = $targetDirectory . $newFileName;

                    // Move the uploaded file to the target directory with the new name
                    if (move_uploaded_file($tmpName, $destinationPath)) {
                        $data['importFile'] = $newFileName;

                        return [
                            'data' => $data,
                            'msg' => "Successfully imported",
                            'status' => true,
                        ];
                    }
                }
            }

            return [
                'data' => $data,
                'msg' => "Error occurred",
                'status' => false,
            ];
        } catch (Exception $e) {
            return [
                'data' => [],
                'msg' => "Exception: " . $e->getMessage(),
                'status' => false,
            ];
        }
    }

    public function importCustomerData($data)
    {
        try {
            Helper::AddModel("CustomerAddressModel");
            Helper::AddModel("CustomerContactModel");
            $customerAddressModel = new CustomerAddressModel();
            $customerContactModel = new CustomerContactModel();

            if (isset($data["deleteAllCus"]) && !empty($data["deleteAllCus"]) && $data["deleteAllCus"] == "on") {
                $sql = "TRUNCATE TABLE customers";
                $this->db->exec($sql);
                $sql = "TRUNCATE TABLE cust_addresses";
                $this->db->exec($sql);
                $sql = "TRUNCATE TABLE cust_phones";
                $this->db->exec($sql);
            }
            // Define the target directory where you want to move the file
            $targetDirectory = UPLOAD_PATH . 'importCustomer/';
            // Specify the path to your Excel file
            $excelFile = $targetDirectory . $data["importFile"];

            // Load the Excel file
            $spreadsheet = IOFactory::load($excelFile);

            // Get the active sheet
            $worksheet = $spreadsheet->getActiveSheet();

            // Get the highest row and column
            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();

            $colName = [];
            $colvalues = [];
            // Loop through each row
            for ($row = 1; $row <= $highestRow; $row++) {
                $countCol = 0;
                // Loop through each column
                for ($col = 'A'; $col <= $highestColumn; $col++) {
                    $cellValue = $worksheet->getCell($col . $row)->getValue();
                    // Process the cell value as needed
                    // echo "Row: $row, Column: $col, Value: $cellValue\n";

                    if ($row == 1) {
                        $colName[] = $cellValue;
                    } else {
                        $colValues[$row - 2][$colName[$countCol]] = $cellValue;
                        $countCol++;
                    }
                }
            }
            // var_dump($colName);
            // var_dump($colValues);
            $errorData = [];
            foreach ($colValues as $key => $colValue) {
                $newCustomer["name"] = $colValue[$this->importFields[0]];
                $newCustomerData = $this->newCustomer($newCustomer);
                // var_dump($newCustomerData);
                // die();
                if ($newCustomerData['status']) {
                    // insert customer address information
                    $newCustomerAddress = [];
                    $newCustomerAddress["cus_id"] = $newCustomerData["data"]["id"];
                    $newCustomerAddress["cust_type"] = 4;
                    $newCustomerAddress["address1"] = (!empty($colValue[$this->importFields[2]]) && $colValue[$this->importFields[2]] != "--") ? $colValue[$this->importFields[2]] : "";
                    $newCustomerAddress["city"] = (!empty($colValue[$this->importFields[3]]) && $colValue[$this->importFields[3]] != "--") ? $colValue[$this->importFields[3]] : "";
                    $newCustomerAddress["province"] = (!empty($colValue[$this->importFields[4]]) && $colValue[$this->importFields[4]] != "--") ? $colValue[$this->importFields[4]] : "";
                    $newCustomerAddress["postalCode"] = (!empty($colValue[$this->importFields[5]]) && $colValue[$this->importFields[5]] != "--") ? $colValue[$this->importFields[5]] : "";
                    $newCustomerAddressData = $customerAddressModel->createCustomerAddress($newCustomerAddress);

                    // insert customer contact information
                    /*if (!empty($colValue[$this->importFields[4]])) {
                        $phones = explode(",", $colValue[$this->importFields[4]]);
                        foreach ($phones as $phone) {
                            $newCustomerContact = [];
                            $newCustomerContact["cus_id"] = $newCustomerData["data"]["id"];
                            $newCustomerContact["type"] = 3;
                            $newCustomerContact["name"] = "";
                            $newCustomerContact["method"] = 4;
                            $newCustomerContact["detailed_info"] = "";
                            $newCustomerContact["phone_email"] = $phone;
                            $newCustomerContactData = $customerContactModel->createCustomerContact($newCustomerContact);
                        }
                    }*/

                    if (!empty($colValue[$this->importFields[1]]) && $colValue[$this->importFields[1]] != "--") {
                        $emails = explode(",", $colValue[$this->importFields[1]]);
                        foreach ($emails as $email) {
                            $newCustomerContact = [];
                            $newCustomerContact["cus_id"] = $newCustomerData["data"]["id"];
                            $newCustomerContact["type"] = 3;
                            $newCustomerContact["name"] = "";
                            $newCustomerContact["method"] = 1;
                            $newCustomerContact["detailed_info"] = "";
                            $newCustomerContact["phone_email"] = $email;
                            $newCustomerContactData = $customerContactModel->createCustomerContact($newCustomerContact);
                        }
                    }
                } else {
                    $errorData[] = $colValue;
                }
            }

            if (empty($errorData)) {
                return [
                    'data' => [],
                    'msg' => "",
                    'status' => true,
                ];
            } else {
                return [
                    'data' => $errorData,
                    'msg' => "Exception: Some data was not uploaded",
                    'status' => false,
                ];
            }
        } catch (Exception $e) {
            return [
                'data' => [],
                'msg' => "Exception: " . $e->getMessage(),
                'status' => false,
            ];
        }
    }

    private function newCustomer($data)
    {
        try {
            $fields = [];
            $placeholders = [];
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

            $sql = "INSERT INTO customers ($fieldList) VALUES ($placeholderList)";
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
                $selectSql = "SELECT * FROM customers WHERE id = :customerId";
                $selectStmt = $this->db->prepare($selectSql);
                // Use the appropriate key
                $selectStmt->bindValue(':customerId', $lastInsertedId);
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
}
