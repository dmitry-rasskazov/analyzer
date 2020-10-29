<?php
// Методы анализа последовательностей

namespace App\Logic;


class SequenceAnalyzer 
{
    private array $sequence;

    function __construct($sequence = [])
    {
        $this->setSequence($sequence);
    }

    /**
     * @param  int $number
     * @return int
     */
    public function getIndex(int $number = null): int
    {

        $count = $this->countRepeat($number); // Количество вхождений числа в массив
        $len   = count($this->sequence);      // Длина массива

        // Если заданных чисел в массиве нет, или массив состоит только из заданных чисел, возвращаем -1
        if($count == 0 || $count == $len || $len == 0) {
            return -1;
        }
        
        for($repeat = 0, $i = 0; $i < $len; $i++) {
            // Количество оставшихся элементов, не равных number
            $result = $len - $i - ($count - $repeat);
            // Возвращаем инжекс, если количество равных и неравных элементов по обе стороны от разделителя равны
            if($repeat == $result && $result != 0) {
                return $i;
            }  

            if($this->sequence[$i] == $number) {
                $repeat++;
            }
            
        }
        return -1;
    }

    /**
     * @param  array $sequence
     * @return SequenceAnalyzer
     */
    public function setSequence(array $sequence): SequenceAnalyzer
    {
        $this->sequence = $sequence;
        return $this;
    }

    /**
     * @param  void
     * @return array
     */
    public function getSequence(): array
    {
        return $this->sequence;
    }

    /**
     * Количество повторений числа в последовательности
     * @param  int $num
     * @return int
     */
    public function countRepeat(int $num): int
    {
        $count = 0;
        foreach($this->sequence as $item) {
          if($num == $item) {
            $count++;
          }
        }

        return $count;
    }
}