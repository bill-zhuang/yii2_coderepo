<?php

namespace app\library\bill;

class Mail
{
    private $_username = 'your@mail.com';
    private $_receiver = 'receive@mail.com';
    private $_bill_server_host = 'production.host';

    public function send($title, $body, $receiver = null, $attachment = null)
    {
        $server_host = $_SERVER['HTTP_HOST'];
        if ($server_host != $this->_bill_server_host)
        {
            return true;
        }

        $env = Util::isProductionEnv() ? 'product' : 'alpha';
        $title = '(' . \Yii::$app->user->identity->name . '-' . $env . ')' . $title;
        $title = '=?UTF-8?B?' . base64_encode($title) . '?=';
        $receivers = $this->_initReceivers($receiver);
        \Yii::$app->mailer->compose()
            ->setFrom($this->_username)
            ->setTo($receivers)
            ->setSubject($title)
            ->setTextBody($body)
            ->attach($attachment)
            ->send();

        return true;
    }

    private function _initReceivers($receiver)
    {
        if (is_array($receiver) && count($receiver) == 0) {
            return [$this->_receiver];
        }

        if ($receiver == null) {
            return [$this->_receiver];
        }

        return $receiver;
    }
} 