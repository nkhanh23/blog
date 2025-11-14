<?php
class CoreModel
{
    private $connect;
    public function __construct()
    {
        $this->connect = Database::connectPDO();
    }

    public function getUserInfo()
    {
        // Lấy thông tin user
        $NameLogin = '';
        $token = getSession('tokenLogin');
        if (!empty($token)) {
            $getUserId = $this->getOne("SELECT user_id FROM token_login WHERE token='$token'");
            if (!empty($getUserId['user_id'])) {
                $user_Id = $getUserId['user_id'];
            }
            if (!empty($getUserId)) {
                $getInfo = $this->getOne("SELECT fullname, avartar FROM users WHERE id=$user_Id");
                if (!empty($getInfo)) {
                    return $getInfo;
                }
            }
        }
        return false;
    }

    // Hàm lấy tất cả dữ liệu của 1 bảng
    public function getALL($sql)
    {

        $stm = $this->connect->prepare($sql);

        $stm->execute();

        $result = $stm->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }
    // Hàm đếm số lương hàng của bảng
    public function getRows($sql)
    {
        $stm = $this->connect->prepare($sql);

        $stm->execute();

        $result = $stm->rowCount();

        return $result;
    }



    // Truy vấn 1 dòng dữ liệu của 1 bảng
    public function getOne($sql)
    {

        $stm = $this->connect->prepare($sql);
        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    // Insert dữ liệu

    public function insert($table, $data)
    {
        // 
        //     $data = [
        //     'name' => 'Khanh',
        //     'email' => 'nkhanh23005@gmail.com',
        //     'phone' => '0987654321'
        //     ];



        $keys = array_keys(($data));
        $cot = implode(',', $keys);
        $placeholder = ':' . implode(',:', $keys);

        $sql = "INSERT INTO $table ({$cot}) VALUES ({$placeholder})";
        $stm = $this->connect->prepare($sql);

        $result =  $stm->execute($data);
        return $result;
    }

    public function update($table, $data, $condition = '')
    {


        $update = '';

        foreach ($data as $key => $value) {
            $update .= $key . '=:' . $key . ',';
        }

        $update = trim($update, ',');

        if (!empty($condition)) {
            $sql = "UPDATE $table SET $update WHERE $condition";
        } else {
            $sql = "UPDATE $table SET $update";
        }
        $stm = $this->connect->prepare($sql);

        $result = $stm->execute($data);
        return $result;
    }


    public function delete($table, $condition = '')
    {

        if (!empty($condition)) {
            $sql = "DELETE FROM $table WHERE $condition";
        } else {
            $sql = "DELETE FROM $table";
        }
        $stm = $this->connect->prepare(($sql));

        $result = $stm->execute();
        return $result;
    }

    public function getLastID()
    {

        return $this->connect->lastInsertId();
    }
}
