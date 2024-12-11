<?php
    namespace adventOfCode;
    
    class Day10 {
        private array $input;
        private float $startTime;
        private array $starting_positions;
        private array $ending_positions;
        private int $height;
        private int $width;
        
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
            $this->height = count($this->input);
            $this->width = strlen($this->input[0]);
            
            foreach ($this->input as $y => $row) {
                for ($x = 0; $x < $this->width; $x++) {
                    if ($row[$x] === "0") {
                        $this->starting_positions[] = ["x" => $x, "y" => $y];
                    } elseif ($row[$x] === "9") {
                        $this->ending_positions[$y][$x] = 1;
                    }
                }
            }
        }
        
        private function solveBoth(): array {
            $score_1 = 0;
            $score_2 = 0;
            
            foreach ($this->starting_positions as $position) {
                $stack = [[$position["x"], $position["y"], 0]];
                $visited_endpoints = [];
                
                do {
                    list($x, $y, $n) = array_pop($stack);
                    
                    if ($n === 9) {
                        if (!isset($visited_endpoints[$y][$x])) {
                            $visited_endpoints[$y][$x] = 1;
                            $score_1++;
                        }
                        
                        $score_2++;
                        
                        continue;
                    }
                    
                    // North
                    if ($y > 0 && intval($this->input[$y - 1][$x]) === $n + 1) {
                        $stack[] = [$x, $y - 1, $n + 1];
                    }
                    
                    // South
                    if ($y < $this->height - 1 && intval($this->input[$y + 1][$x]) === $n + 1) {
                        $stack[] = [$x, $y + 1, $n + 1];
                    }
                    
                    // West
                    if ($x > 0 && intval($this->input[$y][$x - 1]) === $n + 1) {
                        $stack[] = [$x - 1, $y, $n + 1];
                    }
                    
                    // East
                    if ($x < $this->width - 1 && intval($this->input[$y][$x + 1]) === $n + 1) {
                        $stack[] = [$x + 1, $y, $n + 1];
                    }
                } while (!empty($stack));
            }
            
            return [$score_1, $score_2];
        }
    }
    
    $path = "puzzle_input.txt";
    (new Day10($path))->solve();