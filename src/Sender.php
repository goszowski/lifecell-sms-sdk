<?php
namespace Sgroshi\Sms;

use Sgroshi\Sms\Gate;

class Sender extends Gate {

    /**
     * Sender конструктор.
     *
     * @param $host Хост sms-gate
     * @param $access_token Токен доступу
     * @param $operator Оператор
     * @return void
     */
    public function __construct(string $host, string $access_token, string $operator = null)
    {
        parent::__construct($host . '/ip2sms/' . ($operator ?? null), $access_token);
    }

    /**
     * Відправка одиничного SMS повідомлення
     *
     * @param $to Номер телефону отримувача
     * @param $message Текст повідомлення
     * @return string
     */
    public function single(int $to, string $message)
    {
        $xml = $this->loadStub('sender.single');

        $xml = str_replace('%to%', $to, $xml);
        $xml = str_replace('%message%', $message, $xml);

        return $this->sendRequest($xml);
    }

    /**
     * Відправка масового SMS повідомлення декільком отримувачам
     *
     * @param $to Номери телефонів отримувачів
     * @param $message Текст повідомлення
     * @return string
     */
    public function bulk(array $to, string $message)
    {
        $xml = $this->loadStub('sender.bulk');

        $toStr = '';

        foreach($to as $phone)
        {
            $toStr .= '<to>' . $phone . '</to>';
        }

        $xml = str_replace('%uniq_key%', time().mt_rand(100, 999), $xml);
        $xml = str_replace('%to%', $toStr, $xml);
        $xml = str_replace('%message%', $message, $xml);

        return $this->sendRequest($xml);
    }

    /**
     * Відправка індивідульного SMS повідомлення декільком отримувачам
     *
     * @param $data Масив в форматі номер_телефону=>текст_повідомлення
     * @return string
     */
    public function individual(array $data)
    {
        $xml = $this->loadStub('sender.individual');

        $toBodyStr = '';

        foreach($data as $phone=>$message)
        {
            $toBodyStr .= '<to>' . $phone . '</to><body content-type="text/plain">' . $message . '</body>';
        }

        $xml = str_replace('%uniq_key%', time().mt_rand(100, 999), $xml);
        $xml = str_replace('%to_body%', $toBodyStr, $xml);

        return $this->sendRequest($xml);
    }
}