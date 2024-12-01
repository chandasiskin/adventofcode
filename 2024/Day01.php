<?php
    namespace adventOfCode;
    
    class Day01 {
        private string $input;
        private array $leftList;
        private array $rightList;
        private float $startTime;
        
        public function __construct(string $path) {
            $this->startTime = microtime(true);
            
            $this->input = trim(file_get_contents($path));
        }
        
        public function solve(): void {
            $this->populateLists();
            
            $sol1 = $this->solve1();
            $sol2 = $this->solve2();
            
            $elapsedTime = microtime(true) - $this->startTime;
            $elapsedTimeMicroSeconds = $elapsedTime * 1000;
            
            echo
                "Part 1: {$sol1}" . PHP_EOL .
                "Part 2: {$sol2}" . PHP_EOL .
                "Time: {$elapsedTimeMicroSeconds}ms";
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
    
    $path = "puzzle_input.txt";
    $day = new Day01($path);
    $day->solve();