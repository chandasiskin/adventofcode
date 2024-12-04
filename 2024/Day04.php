<?php
    namespace adventOfCode;
    
    class Day04 {
        private array $input;
        private float $startTime;
        
        public function __construct(string $path) {
            $this->startTime = microtime(true);
            
            $this->input = array_map("trim", file($path));
        }
        
        public function solve(): void {
            $sol = $this->solveBoth();
            
            $elapsedTime = microtime(true) - $this->startTime;
            $elapsedTimeMicroSeconds = $elapsedTime * 1000;
            
            echo
                "Part 1: {$sol[0]}" . PHP_EOL .
                "Part 2: {$sol[1]}" . PHP_EOL .
                "Time: {$elapsedTimeMicroSeconds}ms";
        }
        
        private function search_for_xmas(int $x, int $y): int {
            $xmas_counter = 0;
            
            // North
            if (isset($this->input[$y - 3]) && $this->input[$y - 1][$x] === "M" &&
                $this->input[$y - 2][$x] === "A" && $this->input[$y - 3][$x] === "S") {
                $xmas_counter++;
            }
            
            // Northeast
            if (isset($this->input[$y - 3][$x + 3]) && $this->input[$y - 1][$x + 1] === "M" &&
                $this->input[$y - 2][$x + 2] === "A" && $this->input[$y - 3][$x + 3] === "S") {
                $xmas_counter++;
            }
            
            // East
            if (isset($this->input[$y][$x + 3]) && $this->input[$y][$x + 1] === "M" &&
                $this->input[$y][$x + 2] === "A" && $this->input[$y][$x + 3] === "S") {
                $xmas_counter++;
            }
            
            // Southeast
            if (isset($this->input[$y + 3][$x + 3]) && $this->input[$y + 1][$x + 1] === "M" &&
                $this->input[$y + 2][$x + 2] === "A" && $this->input[$y + 3][$x + 3] === "S") {
                $xmas_counter++;
            }
            
            // South
            if (isset($this->input[$y + 3]) && $this->input[$y + 1][$x] === "M" &&
                $this->input[$y + 2][$x] === "A" && $this->input[$y + 3][$x] === "S") {
                $xmas_counter++;
            }
            
            // Southwest
            if (isset($this->input[$y + 3][$x - 3]) && $x > 2 && $this->input[$y + 1][$x - 1] === "M" &&
                $this->input[$y + 2][$x - 2] === "A" && $this->input[$y + 3][$x - 3] === "S") {
                $xmas_counter++;
            }
            
            // West
            if (isset($this->input[$y][$x - 3]) && $x > 2 && $this->input[$y][$x - 1] === "M" &&
                $this->input[$y][$x - 2] === "A" && $this->input[$y][$x - 3] === "S") {
                $xmas_counter++;
            }
            
            // Northwest
            if (isset($this->input[$y - 3][$x - 3]) && $x > 2 && $this->input[$y - 1][$x - 1] === "M" &&
                $this->input[$y - 2][$x - 2] === "A" && $this->input[$y - 3][$x - 3] === "S") {
                $xmas_counter++;
            }
            
            return $xmas_counter;
        }
        
        private function search_for_x_mas($x, $y): int {
            $x_mas_counter = 0;
            
            if ($y > 0 && $x > 0 && isset($this->input[$y + 1][$x + 1])) {
                // Northwest & southeast
                if (($this->input[$y - 1][$x - 1] === "M" && $this->input[$y + 1][$x + 1] === "S") ||
                    ($this->input[$y - 1][$x - 1] === "S" && $this->input[$y + 1][$x + 1] === "M")) {
                    // Northeast & southwest
                    if (($this->input[$y - 1][$x + 1] === "M" && $this->input[$y + 1][$x - 1] === "S") ||
                        ($this->input[$y - 1][$x + 1] === "S" && $this->input[$y + 1][$x - 1] === "M")) {
                        $x_mas_counter++;
                    }
                }
            }
            
            return $x_mas_counter;
        }
        
        private function solveBoth(): array {
            $xmas_counter = 0;
            $x_mas_counter = 0;
            $width = strlen($this->input[0]);
            $height = count($this->input);
            
            for ($y = 0; $y < $height; $y++) {
                for ($x = 0; $x < $width; $x++) {
                    if ($this->input[$y][$x] === "X") {
                        $xmas_counter += $this->search_for_xmas($x, $y);
                    } else if ($this->input[$y][$x] === "A") {
                        $x_mas_counter += $this->search_for_x_mas($x, $y);
                    }
                }
            }
            
            return [$xmas_counter, $x_mas_counter];
        }
    }
    
    $path = "puzzle_input.txt";
    (new Day04($path))->solve();