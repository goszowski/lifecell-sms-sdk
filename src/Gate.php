<?php 
namespace Sgroshi\Sms;

use SimpleXMLElement;
use Exception;

abstract class Gate {

	/**
     * Хост sms-gate
     *
     * @var string
     */
    protected $host;

    /**
     * Токен доступу
     *
     * @var string
     */
    protected $access_token;

    /**
     * SmsGate конструктор.
     *
     * @param $host Хост sms-gate
     * @param $access_token Токен доступу
     * @return void
     */
    public function __construct(string $host, string $access_token)
    {
        $this->host = $host;
        $this->access_token = $access_token;
    }

	protected function loadStub(string $name)
    {
    	$name = str_replace('.', '/', $name);
        return file_get_contents(__DIR__ . '/stubs/' . $name . '.stub');
    }

	/**
     * Відправка запиту на sms-gate
     *
     * @param $xml Підготовлений XML
     * @return string
     */
    protected function sendRequest(string $xml)
    {
        $curl = curl_init($this->host);

        curl_setopt ($curl, CURLOPT_HTTPHEADER, [
            "Content-Type: text/xml", 
            "Authorization: Bearer " . $this->access_token
        ]);

        curl_setopt($curl, CURLOPT_POST, true);

        curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);

        //Tell cURL that we want the response to be returned as
        //a string instead of being dumped to the output.
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

        $result = curl_exec($curl);

        if(curl_errno($curl)){
            throw new Exception(curl_error($curl));
        }

        curl_close($curl);

        $response = json_decode(json_encode(new SimpleXMLElement($result)), true);

        foreach($response['@attributes'] as $key=>$value)
        {
            $response[$key] = $value;
        }

        unset($response['@attributes']);

        return $response;
    }

}