<?php
    namespace adventOfCode;
    
    class Day15 {
        private array $input;
        private float $startTime;
        private array $map = [];
        private int $height;
        private int $width;
        private string $movements = "";
        private array $position;
        
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
            $writeToMap = true;
            
            foreach ($this->input as $y => $row) {
                if ($row === "") {
                    $writeToMap = false;
                    
                    continue;
                }
                
                if ($writeToMap) {
                    if (!isset($this->position)) {
                        if (($x = strpos($row, "@")) !== false) {
                            $this->position = ["x" => $x, "y" => $y];
                            $row[$x] = ".";
                        }
                    }
                    
                    $this->map[] = $row;
                } else {
                    $this->movements .= $row;
                }
            }
            
            $this->height = count($this->map);
            $this->width = strlen($this->map[0]);
        }
        
        private function moveUp(): void {
            $x = $this->position["x"];
            $y = $this->position["y"];
            
            if ($this->map[$y - 1][$x] === "#") {
                return;
            }
            
            if ($this->map[$y - 1][$x] === "O") {
                for ($newY = $y - 2; $newY >= 0; $newY--) {
                    if ($this->map[$newY][$x] === "#") {
                        return;
                    }
                    
                    if ($this->map[$newY][$x] === ".") {
                        $row = $this->map[$newY];
                        $row[$x] = "O";
                        $this->map[$newY] = $row;
                        
                        $row = $this->map[$y - 1];
                        $row[$x] = ".";
                        $this->map[$y - 1] = $row;
                        
                        break;
                    }
                }
            }
            
            $this->position["y"]--;
        }
        
        private function moveDown(): void {
            $x = $this->position["x"];
            $y = $this->position["y"];
            
            if ($this->map[$y + 1][$x] === "#") {
                return;
            }
            
            if ($this->map[$y + 1][$x] === "O") {
                for ($newY = $y + 2; $newY < $this->height; $newY++) {
                    if ($this->map[$newY][$x] === "#") {
                        return;
                    }
                    
                    if ($this->map[$newY][$x] === ".") {
                        $row = $this->map[$newY];
                        $row[$x] = "O";
                        $this->map[$newY] = $row;
                        
                        $row = $this->map[$y + 1];
                        $row[$x] = ".";
                        $this->map[$y + 1] = $row;
                        
                        break;
                    }
                }
            }
            
            $this->position["y"]++;
        }
        
        private function moveLeft(): void {
            $x = $this->position["x"];
            $y = $this->position["y"];
            
            if ($this->map[$y][$x - 1] === "#") {
                return;
            }
            
            if ($this->map[$y][$x - 1] === "O") {
                for ($newX = $x - 2; $newX >= 0; $newX--) {
                    if ($this->map[$y][$newX] === "#") {
                        return;
                    }
                    
                    if ($this->map[$y][$newX] === ".") {
                        $row = $this->map[$y];
                        $row[$newX] = "O";
                        $row[$x - 1] = ".";
                        $this->map[$y] = $row;
                        
                        break;
                    }
                }
            }
            
            $this->position["x"]--;
        }
        
        private function moveRight(): void {
            $x = $this->position["x"];
            $y = $this->position["y"];
            
            if ($this->map[$y][$x + 1] === "#") {
                return;
            }
            
            if ($this->map[$y][$x + 1] === "O") {
                for ($newX = $x + 2; $newX < $this->width; $newX++) {
                    if ($this->map[$y][$newX] === "#") {
                        return;
                    }
                    
                    if ($this->map[$y][$newX] === ".") {
                        $row = $this->map[$y];
                        $row[$newX] = "O";
                        $row[$x + 1] = ".";
                        $this->map[$y] = $row;
                        
                        break;
                    }
                }
            }
            
            $this->position["x"]++;
        }
        
        private function solve1(): int {
            $length = strlen($this->movements);
            //print_r($this->map);echo implode(",", $this->position);
            for ($i = 0; $i < $length; $i++) {
                switch ($this->movements[$i]) {
                    case "^": $this->moveUp(); break;
                    case "v": $this->moveDown(); break;
                    case "<": $this->moveLeft(); break;
                    case ">": $this->moveRight(); break;
                    default: die("Invalid move: " . $this->movements[$i]);
                }
                //print_r($this->map);echo $this->movements[$i];echo implode(",", $this->position);
            }
            
            $sum = 0;
            
            for ($y = 1; $y < $this->height - 1; $y++) {
                for ($x = 1; $x < $this->width - 1; $x++) {
                    if ($this->map[$y][$x] === "O") {
                        $sum += 100 * $y + $x;
                    }
                }
            }
            
            return $sum;
        }
        
        private function solve2(): int {
            return 0;
        }
    }
    
    $path = "puzzle_input.txt";
    (new Day15($path))->solve();
