<?php

namespace App\Tests\Logic;

use App\Logic\SequenceAnalyzer;
use PHPUnit\Framework\TestCase;

class SequenceAnalyzerTest extends TestCase
{

    public function testGetIndex(): void
    {
        $arr = [1, 2, 8, 1, 4, 5, 6, 10, 21, 74, 5, 6];
        $analyzer = new SequenceAnalyzer($arr);

        $result = $analyzer->getIndex(5);
        $this->assertEquals(10, $result);


        $arr = [1, 2, 8, 1, 4];
        $analyzer->setSequence($arr);

        $result = $analyzer->getIndex(5);
        $this->assertEquals(-1, $result);
    }

    public function testCountRepeat(): void
    {
        $arr = [1, 10, 8, 1, 4, 5, 5, 10, 21, 74, 5, 6];
        $analyzer = new SequenceAnalyzer($arr);

        $result = $analyzer->countRepeat(10);
        $this->assertEquals(2, $result);
    }
}
