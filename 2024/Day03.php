<?php
    namespace adventOfCode;
    
    class Day03 {
        private string $input;
        private float $startTime;
        private array $positions;
        
        public function __construct(string $path) {
            $this->startTime = microtime(true);
            
            $this->input = trim(file_get_contents($path));
        }
        
        public function solve(): void {
            $this->findDo();
            $this->findDont();
            $this->findMul();
            
            ksort($this->positions);
            
            $sol = $this->solveBoth();
            
            $elapsedTime = microtime(true) - $this->startTime;
            $elapsedTimeMicroSeconds = $elapsedTime * 1000;
            
            echo
                "Part 1: {$sol[0]}" . PHP_EOL .
                "Part 2: {$sol[1]}" . PHP_EOL .
                "Time: {$elapsedTimeMicroSeconds}ms";
        }
        
        private function findDo(): void {
            $str = "do()";
            $pos = strpos($this->input, $str);
            
            while ($pos !== false) {
                $this->positions[$pos] = "do";
                
                $pos = strpos($this->input, $str, $pos + 1);
            }
        }
        
        private function findDont(): void {
            $str = "don't()";
            $pos = strpos($this->input, $str);
            
            while ($pos !== false) {
                $this->positions[$pos] = "dont";
                
                $pos = strpos($this->input, $str, $pos + 1);
            }
        }
        
        private function findMul(): void {
            preg_match_all("/mul\(\d{1,3},\d{1,3}\)/", $this->input, $matches);
            
            foreach ($matches[0] as $match) {
                $pos = strpos($this->input, $match);
                
                while ($pos !== false) {
                    preg_match_all("/\d+/", $match, $m);
                    
                    $this->positions[$pos] = intval($m[0][0]) * intval($m[0][1]);
                    
                    $pos = strpos($this->input, $match, $pos + 1);
                }
            }
        }

        private function solveBoth(): array {
            $result_part_1 = 0;
            $result_part_2 = 0;
            $enabled = true;
            
            foreach ($this->positions as $instruction) {
                if ($instruction === "do") {
                    $enabled = true;
                } else if ($instruction === "dont") {
                    $enabled = false;
                } else {
                    $result_part_1 += $instruction;
                    $result_part_2 += $enabled ? $instruction : 0;
                }
            }
            
            return [$result_part_1, $result_part_2];
        }
    }
    
    $path = "puzzle_input.txt";
    $day = new Day03($path);
    $day->solve();