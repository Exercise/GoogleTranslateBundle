<?php
namespace Exercise\GTranslateBundle;

use Guzzle\Http\Client;
use Guzzle\Common\Collection;
use Exercise\GTranslateBundle\ParametersEscaper;

class Translator
{
    protected $apiKey;

    protected $parametersEscaper;

    protected $client;

    private $environment;

    public function __construct($apiKey, ParametersEscaper $parametersEscaper, $environment)
    {
        $this->apiKey = $apiKey;
        $this->parametersEscaper = $parametersEscaper;
        $this->environment = $environment;

        $headers = new Collection();
        $headers->add('X-HTTP-Method-Override', 'GET');

        $this->client = new Client();
        $this->client->setDefaultHeaders($headers);
    }

    /**
     * @param $string
     * @param $langFrom
     * @param $langTo
     * @return string translated from langFrom to langTo
     */
    public function translateString($string, $langFrom, $langTo)
    {
        return $this->environment != 'test'
            ? $this->translateStringProd($string, $langFrom, $langTo)
            : $this->translateStringTest($string, $langFrom, $langTo)
        ;
    }

    private function translateStringProd($string, $langFrom, $langTo)
    {
        $stringEscaped = $this->parametersEscaper->escapeParameters($string);

        $postBody = array(
            'key'    => $this->apiKey,
            'q'      => $stringEscaped,
            'source' => $langFrom,
            'target' => $langTo,
        );

        $request = $this->client->post('https://www.googleapis.com/language/translate/v2', null, $postBody);
        $response = $request->send();

        $responseArray = $response->json();
        $translatedString = $responseArray['data']['translations']['0']['translatedText'];

        $string = $this->parametersEscaper->unEscapeParameters($translatedString);

        return $string;
    }

    /**
     * This function use only for test.
     * You can't use it for translate.
     * For more info visit https://developers.google.com/translate/v2/faq
     *
     * @param $string
     * @param $langFrom
     * @param $langTo
     * @return string translated from langFrom to langTo
     */
    private function translateStringTest($string, $langFrom, $langTo)
    {
        $stringEscaped = $this->parametersEscaper->escapeParameters($string);
        $url = sprintf('http://translate.google.ru/translate_a/t?client=x&text=%s&hl=%s&sl=%s&tl=%s&ie=UTF-8&oe=UTF-8&multires=1&otf=2&trs=1&ssel=3&tsel=6&sc=1', urlencode($stringEscaped), $langFrom, $langFrom, $langTo);

        $request = $this->client->get($url);
        $response = $request->send();

        $responseArray = $response->json();
        $translatedString = $responseArray['sentences']['0']['trans'];

        $string = $this->parametersEscaper->unEscapeParameters($translatedString);

        return $string;
    }
}