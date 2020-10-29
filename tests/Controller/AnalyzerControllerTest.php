<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AnalyzerControllerTest extends WebTestCase
{

    public function testSetSeparator(): void
    {
        // Создаём экземпляр клиента
        $client = static::createClient();
        $header = [
          'CONTENT_TYPE' => 'application/json'
        ];
        $content = '{
                        "user_name":"testUser",
                        "auth_code":"ds23gkfj41sz6t5ghsdt4r135hr4",
                        "number":1,
                        "sequence":[
                          1, 2, 4, 1, 14, 5, 5, 2
                        ]
                    }';
        // Отправляем запрос
        $client->request('POST', '/setseparator', [], [], $header, $content);
        // Сравниваем ответ
        $this->assertEquals('{"index":6}', $client->getResponse()->getContent());
        
    }

}