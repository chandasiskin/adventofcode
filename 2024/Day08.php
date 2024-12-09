<?php
    namespace adventOfCode;
    
    class Day08 {
        private array $input;
        private float $startTime;
        private array $antennas;
        private array $antinodes_1;
        private array $antinodes_2;
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
            $this->width = strlen($this->input[0]);
            $this->height = count($this->input);
            
            $this->antinodes_1 = $this->antinodes_2 =
                array_fill(0, $this->height, array_fill(0, $this->width, 0));
            
            foreach ($this->input as $y => $row) {
                for ($x = 0; $x < $this->width; $x++) {
                    if ($row[$x] !== ".") {
                        $this->antennas[$row[$x]][] = ["x" => $x, "y" => $y];
                    }
                }
            }
        }
        
        private function isCoordinateInBounds($x, $y): bool {
            if ($x >= 0 && $x < $this->width && $y >= 0 && $y < $this->height) {
                return true;
            }
            
            return false;
        }
        
        private function solveBoth(): array {
            foreach ($this->antennas as $antenna) {
                $count = count($antenna);
                
                for ($i = 0; $i < $count - 1; $i++) {
                    for ($j = $i + 1; $j < $count; $j++) {
                        $dx = $antenna[$i]["x"] - $antenna[$j]["x"];
                        $dy = $antenna[$i]["y"] - $antenna[$j]["y"];
                        
                        $newX = $antenna[$i]["x"] + $dx;
                        $newY = $antenna[$i]["y"] + $dy;
                        
                        if ($this->isCoordinateInBounds($newX, $newY)) {
                            $this->antinodes_1[$newY][$newX] = 1;
                        }
                        
                        $newX = $antenna[$j]["x"] - $dx;
                        $newY = $antenna[$j]["y"] - $dy;
                        
                        if ($this->isCoordinateInBounds($newX, $newY)) {
                            $this->antinodes_1[$newY][$newX] = 1;
                        }
                        
                        $newX = $antenna[$i]["x"];
                        $newY = $antenna[$i]["y"];
                        
                        while ($this->isCoordinateInBounds($newX, $newY)) {
                            $this->antinodes_2[$newY][$newX] = 1;
                            
                            $newX += $dx;
                            $newY += $dy;
                        }
                        
                        $newX = $antenna[$i]["x"];
                        $newY = $antenna[$i]["y"];
                        
                        while ($this->isCoordinateInBounds($newX, $newY)) {
                            $this->antinodes_2[$newY][$newX] = 1;
                            
                            $newX -= $dx;
                            $newY -= $dy;
                        }
                    }
                }
            }
            
            $antinode_1_counter = 0;
            $antinode_2_counter = 0;
            
            $max = count($this->antinodes_1);
            for ($i = 0; $i < $max; $i++) {
                $antinode_1_counter += array_sum($this->antinodes_1[$i]);
                $antinode_2_counter += array_sum($this->antinodes_2[$i]);
            }
            
            return [$antinode_1_counter, $antinode_2_counter];
        }
    }
    
$path = "puzzle_input.txt";
(new Day08($path))->solve();