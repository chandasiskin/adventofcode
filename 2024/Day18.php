<?php
    namespace adventOfCode;
    
    class Day18 {
        private array $input;
        private float $startTime;
        private int $height = 70;
        private int $width = 70;
        private array $map;
        private array $start;
        private array $goal;
        private array $visited;
        
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
            $this->map = array_fill(0, $this->height + 1, array_fill(0, $this->width + 1, 0));
            $this->start = [0, 0];
            $this->goal = [$this->width, $this->height];
            $this->visited = array_fill(0, $this->height + 1, array_fill(0, $this->width + 1, 0));
            $this->visited[0][0] = 1;
        }
        
        private function count_shortest_path(): int {
            $query = [$this->start];
            $stepCounter = 0;
            $visited = $this->visited;
            
            do {
                $tmp = [];
                
                foreach ($query as $q) {
                    list($x, $y) = $q;
                    
                    if ($x === $this->goal[0] && $y === $this->goal[1]) {
                        return $stepCounter;
                    }
                    
                    // North
                    if ($y > 0 && $this->map[$y - 1][$x] === 0 && $visited[$y - 1][$x] === 0) {
                        $visited[$y - 1][$x] = 1;
                        $tmp[] = [$x, $y - 1];
                    }
                    
                    // South
                    if ($y < $this->height && $this->map[$y + 1][$x] === 0 && $visited[$y + 1][$x] === 0) {
                        $visited[$y + 1][$x] = 1;
                        $tmp[] = [$x, $y + 1];
                    }
                    
                    // West
                    if ($x > 0 && $this->map[$y][$x - 1] === 0 && $visited[$y][$x - 1] === 0) {
                        $visited[$y][$x - 1] = 1;
                        $tmp[] = [$x - 1, $y];
                    }
                    
                    // East
                    if ($x < $this->width && $this->map[$y][$x + 1] === 0 && $visited[$y][$x + 1] === 0) {
                        $visited[$y][$x + 1] = 1;
                        $tmp[] = [$x + 1, $y];
                    }
                }
                
                $query = $tmp;
                $stepCounter++;
            } while (!empty($query));
            
            return -1;
        }
        
        private function solve1(): int {
            for ($i = 0; $i < 1024; $i++) {
                list($x, $y) = array_map("intval", explode(",", $this->input[$i]));
                
                $this->map[$y][$x] = 1;
            }
            
            return $this->count_shortest_path();
        }
        
        private function solve2(): string {
            $max = count($this->input);
            
            for ($i = 1024; $i < $max; $i++) {
                list($x, $y) = array_map("intval", explode(",", $this->input[$i]));
                
                $this->map[$y][$x] = 1;
                
                if ($this->count_shortest_path() === -1) {
                    return "$x,$y";
                }
            }
            
            return "";
        }
    }
    
    $path = "puzzle_input.txt";
    (new Day18($path))->solve();
