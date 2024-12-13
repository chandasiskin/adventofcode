<?php
    namespace adventOfCode;
    
    class Day13 {
        private array $input;
        private float $startTime;
        private array $machines;
        
        public function __construct(string $path) {
            $this->startTime = microtime(true);
            
            $this->input = array_map("trim", file($path));
        }
        
        public function solve(): void {
            $this->prepare();
            $sol = $this->solveBoth();
            
            $elapsedTime = microtime(true) - $this->startTime;
            $elapsedTimeMicroSeconds = $elapsedTime * 1000;
            
            echo
                "Part 1: {$sol[0]}" . PHP_EOL .
                "Part 2: {$sol[1]}" . PHP_EOL .
                "Time: {$elapsedTimeMicroSeconds}ms";
        }
        
        private function prepare(): void {
            $max = count($this->input);
            
            for ($i = 0; $i < $max; $i += 4) {
                $index = intval($i / 4);
                $this->machines[$index][] = preg_split("/[^\d]+/", substr($this->input[$i], 12));
                $this->machines[$index][] = preg_split("/[^\d]+/", substr($this->input[$i + 1], 12));
                $this->machines[$index][] = preg_split("/[^\d]+/", substr($this->input[$i + 2], 9));
            }
        }
        
        private function solveBoth(): array {
            $tokens_part_1 = 0;
            $tokens_part_2 = 0;
            $adder = 10000000000000;
            
            foreach ($this->machines as $key => $machine) {
                $button_b1 = ($machine[0][0] * $machine[2][1] - $machine[0][1] * $machine[2][0]) / ($machine[0][0] * $machine[1][1] - $machine[0][1] * $machine[1][0]);
                $button_a1 = ($machine[2][0] - $machine[1][0] * $button_b1) / $machine[0][0];
                $button_b2 = ($machine[0][0] * ($machine[2][1] + $adder) - $machine[0][1] * ($machine[2][0] + $adder)) / ($machine[0][0] * $machine[1][1] - $machine[0][1] * $machine[1][0]);
                $button_a2 = ($machine[2][0] + $adder - $machine[1][0] * $button_b2) / $machine[0][0];
                
                if (is_int($button_a1) && is_int($button_b1)) {
                    $tokens_part_1 += 3 * $button_a1 + $button_b1;
                }
                
                if (is_int($button_a2) && is_int($button_b2)) {
                    $tokens_part_2 += 3 * $button_a2 + $button_b2;
                }
            }
            
            return [$tokens_part_1, $tokens_part_2];
        }
    }
    
    $path = "puzzle_input.txt";
    (new Day13($path))->solve();