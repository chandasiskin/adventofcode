<?php
    namespace adventOfCode;
    
    class Day14 {
        private array $input;
        private float $startTime;
        private array $robots = [];
        private int $height = 103;
        private int $width = 101;
        
        public function __construct(string $path) {
            $this->startTime = microtime(true);
            
            $this->input = array_map("trim", file($path));
        }
        
        public function solve(): void {
            $this->prepare();
            $sol = [$this->solve1(), $this->solve2()];
            
            $elapsedTime = microtime(true) - $this->startTime;
            $elapsedTimeMicroSeconds = $elapsedTime * 1000;
            
            echo
                "Part 1: {$sol[0]}" . PHP_EOL .
                "Part 2: {$sol[1]}" . PHP_EOL .
                "Time: {$elapsedTimeMicroSeconds}ms";
        }
        
        private function prepare(): void {
            foreach ($this->input as $row) {
                preg_match_all("/-?\d+/", $row, $matches);
                
                $this->robots[] = [
                    "x" => intval($matches[0][0]),
                    "y" => intval($matches[0][1]),
                    "dx" => intval($matches[0][2]),
                    "dy" => intval($matches[0][3])
                ];
            }
        }
        
        private function calculateSafetyFactor(): int {
            $quadrants = array_fill(0, 4, 0);
            
            foreach ($this->robots as $robot) {
                // Top-left
                if ($robot["x"] < intval($this->width / 2) && $robot["y"] < intval($this->height / 2)) {
                    $quadrants[0]++;
                }
                
                // Top-right
                elseif ($robot["x"] > intval($this->width / 2) && $robot["y"] < intval($this->height / 2)) {
                    $quadrants[1]++;
                }
                
                // Bottom-right
                elseif ($robot["x"] > intval($this->width / 2) && $robot["y"] > intval($this->height / 2)) {
                    $quadrants[2]++;
                }
                
                // Bottom-left
                elseif ($robot["x"] < intval($this->width / 2) && $robot["y"] > intval($this->height / 2)) {
                    $quadrants[3]++;
                }
            }
            
            return array_product($quadrants);
        }
        
        private function solve1(): int {
            $seconds = 100;
            
            foreach ($this->robots as &$robot) {
                $robot["x"] = ((($robot["x"] + $robot["dx"] * $seconds) % $this->width) + $this->width) % $this->width;
                $robot["y"] = ((($robot["y"] + $robot["dy"] * $seconds) % $this->height) + $this->height) % $this->height;
            } unset($robot);
            
            return $this->calculateSafetyFactor();
        }
        
        private function solve2(): int {
            $needle = str_repeat("X", 16);
            $emptyGrid = array_fill(0, $this->height, str_repeat(".", $this->width));
            
            for ($i = 101; true; $i++) {
                $grid = $emptyGrid;
                
                foreach ($this->robots as &$robot) {
                    $robot["x"] = ((($robot["x"] + $robot["dx"]) % $this->width) + $this->width) % $this->width;
                    $robot["y"] = ((($robot["y"] + $robot["dy"]) % $this->height) + $this->height) % $this->height;
                    $grid[$robot["y"]][$robot["x"]] = "X";
                } unset($robot);
                
                for ($y = 0; $y < $this->height; $y++) {
                    if (strpos($grid[$y], $needle) !== false) {
                        return $i;
                    }
                }
            }
        }
    }
    
    $path = "puzzle_input.txt";
    (new Day14($path))->solve();
