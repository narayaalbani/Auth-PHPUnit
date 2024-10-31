<?php

use PHPUnit\Framework\TestCase;
use App\Auth;

class AuthTest extends TestCase
{
    private $auth;

    protected function setUp(): void
    {
        $this->auth = new Auth();
    }

    protected function tearDown(): void
    {
        unset($this->auth);
    }

    // Test login dengan kredensial valid
    public function testLoginSuksesDenganCustomer()
    {
        // Data user yang valid
        $email = 'sigit@g.com';
        $password = 'Asd123@#';

        // Simulasikan login
        $response = $this->auth->login($email, $password);
        $responseData = json_decode($response, true);

        // Assert bahwa login berhasil
        $this->assertTrue($responseData['status']);
        $this->assertEquals('Sigit', $responseData['data']['name']);
        $this->assertEquals('customer', $responseData['data']['role']);
    }

    // Test login dengan kredensial valid
    public function testLoginSuksesDenganAdmin()
    {
        // Data user yang valid
        $email = 'admin@g.com';
        $password = 'Asd123@#';

        // Simulasikan login
        $response = $this->auth->login($email, $password);
        $responseData = json_decode($response, true);

        // Assert bahwa login berhasil
        $this->assertTrue($responseData['status']);
        $this->assertEquals('Admin', $responseData['data']['name']);
        $this->assertEquals('admin', $responseData['data']['role']);
    }

    // Test login dengan email yang tidak terdaftar
    public function testLoginDenganEmailTidakTerdaftar()
    {
        // Email tidak terdaftar
        $email = 'notregistered@example.com';
        $password = 'random_password';

        $response = $this->auth->login($email, $password);
        $responseData = json_decode($response, true);

        // Assert bahwa login gagal
        $this->assertFalse($responseData['status']);
        $this->assertEquals('Email belum terdaftar', $responseData['message']);
    }

    // Test login dengan password yang salah
    public function testLoginDenganPasswordSalah()
    {
        // Email terdaftar dengan password yang salah
        $email = 'sigit@g.com';
        $password = 'wrong_password';

        $response = $this->auth->login($email, $password);
        $responseData = json_decode($response, true);

        // Assert bahwa login gagal
        $this->assertFalse($responseData['status']);
        $this->assertEquals('Password salah', $responseData['message']);
    }

    // Test register dengan data yang valid
    public function testRegisterSuksesDenganCustomer()
    {
        // Data register
        $email = 'customer@example.com';
        $name = 'Customer';
        $no_hp = '081234567890';
        $password = 'new_password';
        $confirm_password = 'new_password';
        $role = 'customer';

        $response = $this->auth->register($email, $name, $no_hp, $password, $confirm_password, $role);
        $responseData = json_decode($response, true);

        // Assert bahwa register berhasil
        $this->assertTrue($responseData['status']);
    }

    // Test register dengan data yang valid
    public function testRegisterSuksesDenganAdmin()
    {
        // Data register
        $email = 'admin@example.com';
        $name = 'Admin';
        $no_hp = '081234567890';
        $password = 'new_password';
        $confirm_password = 'new_password';
        $role = 'admin';

        $response = $this->auth->register($email, $name, $no_hp, $password, $confirm_password, $role);
        $responseData = json_decode($response, true);

        // Assert bahwa register berhasil
        $this->assertTrue($responseData['status']);
    }

    // Test register dengan email yang sudah terdaftar
    public function testRegisterDenganEmailSudahTerdaftar()
    {
        // Email yang sudah ada
        $email = 'sigit@g.com';
        $name = 'Test User';
        $no_hp = '081234567890';
        $password = 'password';
        $confirm_password = 'password';
        $role = "customer";

        $response = $this->auth->register($email, $name, $no_hp, $password, $confirm_password, $role);
        $responseData = json_decode($response, true);

        // Assert bahwa register gagal karena email sudah terdaftar
        $this->assertFalse($responseData['status']);
        $this->assertEquals('Email telah terdaftar', $responseData['message']);
    }

    // Test register dengan password yang tidak cocok
    public function testRegisterDenganPasswordDanKonfirmasiPasswordTidakSama()
    {
        // Password dan konfirmasi password tidak cocok
        $email = 'sigit@g.com';
        $name = 'New User';
        $no_hp = '081234567890';
        $password = 'new_password';
        $confirm_password = 'different_password';
        $role = 'customer';

        $response = $this->auth->register($email, $name, $no_hp, $password, $confirm_password, $role);
        $responseData = json_decode($response, true);

        // Assert bahwa register gagal karena password tidak cocok
        $this->assertFalse($responseData['status']);
        $this->assertEquals('Password tidak sama', $responseData['message']);
    }
}
