<?php
namespace Sgroshi\Sms;

use Sgroshi\Sms\Gate;

class Status extends Gate {

    /**
     * Status конструктор.
     *
     * @param $host Хост sms-gate
     * @param $access_token Токен доступу
     * @param $operator Оператор
     * @return void
     */
    public function __construct(string $host, string $access_token, string $operator = null)
    {
        parent::__construct($host . '/ip2sms-request/', $access_token);
    }

    /**
     * Перевірка статусу одиночного SMS повідомлення
     *
     * @param $id Ідентифікатор повідомлення
     * @return string
     */
    public function checkSingle(int $id)
    {
        $xml = $this->loadStub('status.single');

        $xml = str_replace('%id%', $id, $xml);

        return $this->sendRequest($xml);
    }

    /**
     * Перевірка статусу групи SMS повідомленнь
     *
     * @param $id Ідентифікатор групи повідомлень
     * @return string
     */
    public function checkGroup(int $group_id)
    {
        $xml = $this->loadStub('status.group');

        $xml = str_replace('%group_id%', $group_id, $xml);

        return $this->sendRequest($xml); 
    }
}