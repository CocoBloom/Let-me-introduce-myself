<?php

namespace App\controller;

use App\model\UserQueries;
use Framework\view\ViewTemplate;
use PDO;


class LoginHandlerController extends BaseController
{
    protected string $filename;

    public function __construct($filename)
    {
        parent::__construct();
        $this->filename = $filename;
    }

    public function run()
    {
        session_start();

        if ($this->validateLogin()) {
            header("Location:/");
        } else {
            $this->getView()->render($this->filename, []);
        }
    }

    public function validateLogin(): bool
    {
        session_start();
        $email = $_POST['email'];
        $password = $_POST['password'];
        echo $email;
        $users = UserQueries::getAllUsers($this->getConnection());

        foreach ($users as $user) {
            if ($user->email == $email && password_verify($password, $user->password)) {
                $this->setSession($email, password_hash("sessioned", PASSWORD_DEFAULT), $user->name );
                return true;
            }
        }
        return false;
    }

    public function setSession(string $email, string $token, string $name)
    {
        $_SESSION['name'] = $name;
        $_SESSION['email'] = $email;
        $_SESSION['token'] = $token;
    }

}