<?php

namespace backend\services;


use Yii;

class MailService
{
    /**
     * Отправка письма по заявке
     * @param string $email
     * @param int $requestId
     * @param string $comment
     * @return void
     */
    public static function sendMail(string $email, int $requestId, string $comment): void
    {
        $message = Yii::$app->mailer->compose();
        $message->setFrom('no-replay@yiitest.com');
        $message->setTo($email);
        $message->setSubject("Заявка № $requestId решена.");
        $message->setTextBody("Заявка № $requestId решена. Комментарий: $comment");
        $message->send();
    }

}