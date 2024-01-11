<?php
class NotificationModel extends Model
{
    private $tableFields = array(
        "id", "uname", "message", "ndate", "status_all"
    );

    public function addNotification($option)
    {
        try {
            $fields = [];
            $placeholders = [];
            $data['message'] = $option['message'];
            $data['uname'] = $this->user_name;
            $data['ndate'] = date('Y-m-d');
            $data['status_all'] = 1;

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

            $sql = "INSERT INTO notifications ($fieldList) VALUES ($placeholderList)";
            // Assume $pdo is your PDO connection object
            $stmt = $this->db->prepare($sql);
            foreach ($data as $key => $item) {
                if (in_array($key, $this->tableFields)) {
                    $stmt->bindValue(':' . $key, $item);
                }
            }

            if ($stmt->execute()) {
                return [
                    'msg' => "You data has been saved successfully!",
                    'status' => true,
                ];
            } else {
                return [
                    'msg' => "You data has not been saved successfully!",
                    'status' => false,
                ];
            }
        } catch (PDOException $e) {
            return [
                'msg' => "PDO Error: " . $e->getMessage(),
                'status' => false,
            ];
        }
    }
}
