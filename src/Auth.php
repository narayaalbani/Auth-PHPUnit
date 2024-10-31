<?php

namespace App;

class Auth
{
    private $conn;

    public function __construct()
    {
        $this->conn = mysqli_connect("localhost", "root", "", "mechaban");
    }

    public function __destruct()
    {
        mysqli_close($this->conn);
    }

    public function login($email, $password)
    {
        $email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
        $password = htmlspecialchars($password, ENT_QUOTES, 'UTF-8');

        $response = [];

        $query = "SELECT * FROM account WHERE email = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            if ($password !== $row["password"]) {
                $response["status"] = false;
                $response["message"] = "Password salah";
            } else {
                $response["status"] = true;
                $response["data"] = [
                    'name' => $row["name"],
                    'role' => $row["role"]
                ];
            }
        } else {
            $response["status"] = false;
            $response["message"] = "Email belum terdaftar";
        }

        return json_encode($response, JSON_PRETTY_PRINT);
    }

    public function register($email, $name, $no_hp, $password, $confirm_password, $role)
    {
        $email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
        $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        $no_hp = htmlspecialchars($no_hp, ENT_QUOTES, 'UTF-8');
        $password = htmlspecialchars($password, ENT_QUOTES, 'UTF-8');
        $confirm_password = htmlspecialchars($confirm_password, ENT_QUOTES, 'UTF-8');
        $role = htmlspecialchars($role, ENT_QUOTES, 'UTF-8');

        $response = [];

        $query = "SELECT email FROM account WHERE email = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($password != $confirm_password) {
            $response["status"] = false;
            $response["message"] = "Password tidak sama";
        } else if (mysqli_fetch_assoc($result)) {
            $response["status"] = false;
            $response["message"] = "Email telah terdaftar";
        } else {
            $sql = "INSERT INTO account (email, name, no_hp, password, role) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($this->conn, $sql);
            mysqli_stmt_bind_param($stmt, "sssss", $email, $name, $no_hp, $password, $role);
            mysqli_stmt_execute($stmt);
            $response["status"] = true;
        }

        return json_encode($response, JSON_PRETTY_PRINT);
    }
}
