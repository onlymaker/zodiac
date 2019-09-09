<?php

namespace admin;

class Login
{
    function get()
    {
        echo \Template::instance()->render('admin/login.html');
    }

    function post(\Base $f3)
    {
        $logger = $f3->get('LOGGER');
        $username = $_POST['username'];
        $password = $_POST['password'];

        $logger->write("Receive login request: $username");

        if ($this->validate($username, $password)) {
            $logger->write("User $username login success");
            $f3->set('SESSION.AUTHENTICATION', $username);
            $f3->set('SESSION.AUTHORIZATION', 'admin');
            echo 'success';
        } else {
            $logger->write("User $username login failure");
            echo 'Login error';
        }
    }

    function validate($username, $password)
    {
        $f3 = \Base::instance();
        $users = $f3->get('ADMIN.USERNAME');
        $key = array_search($username, $users);
        return $key !== false && md5($f3->get('ADMIN.PASSWORD')[$key]) == $password;
    }
}
