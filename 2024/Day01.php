<?php
    namespace adventOfCode;
    
    class Day01 {
        private string $input;
        private array $leftList;
        private array $rightList;
        
        public function __construct(string $input) {
            $this->input = $input;
        }
        
        public function solve(): string {
            $this->populateLists();
            
            return $this->solve1() . PHP_EOL . $this->solve2();
        }
        
        private function populateLists(): void {
            $list = preg_split("/[^\d]+/", $this->input);
            $listLength = count($list);
            $this->leftList = array_fill(0, $listLength / 2, 0);
            $this->rightList = array_fill(0, $listLength / 2, 0);
            
            for ($i = 0; $i < $listLength; $i++) {
                $index = intval($i / 2);
                
                if ($i % 2 === 0) {
                    $this->leftList[$index] = intval($list[$i]);
                } else {
                    $this->rightList[$index] = intval($list[$i]);
                }
            }
            
            sort($this->leftList);
            sort($this->rightList);
        }
        
        private function solve1(): int {
            $totalDistance = 0;
            
            foreach ($this->leftList as $key => $value) {
                $totalDistance += abs($value - $this->rightList[$key]);
            }
            
            return $totalDistance;
        }
        
        private function solve2(): int {
            $similarityScore = 0;
            $counted_values = array_count_values($this->rightList);
            
            foreach ($this->leftList as $value) {
                $similarityScore += $value * ($counted_values[$value] ?? 0);
            }
            
            return $similarityScore;
        }
    }
    
    $startTime = microtime(true);
    $day = new Day01(trim(file_get_contents("puzzle_input.txt")));
    $res = $day->solve();
    $elapsedTime = (microtime(true) - $startTime) * 1000;
    
    echo $res . PHP_EOL . "{$elapsedTime}ms";