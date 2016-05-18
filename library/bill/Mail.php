<?php

namespace app\library\bill;

class Mail
{
    private $_username = 'your@mail.com';
    private $_password = 'your_password';
    private $_host = 'smtp.mail.com';
    private $_port = 25;
    private $_receiver = 'receive@mail.com';
    private $_bill_server_host = 'production.host';

    public function send($title, $body, $receiver = null, $attachment = null, $charset = 'utf-8')
    {
        $server_host = $_SERVER['HTTP_HOST'];
        if ($server_host != $this->_bill_server_host)
        {
            return true;
        }

        $config = [
            'auth'=>'login',
            'username'=> $this->_username,
            'password'=> $this->_password,
            //'ssl' => 'ssl',
            'port' => $this->_port
        ];
        //TODO

        return true;
    }
} 