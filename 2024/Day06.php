<?php
    namespace adventOfCode;
    
    class Day06 {
    private array $input;
    private float $startTime;
    private array $startingPos;
    private array $initialRoute;
    private array $visitedNodes;
    private int $mapHeight;
    private int $mapWidth;
    
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
        $this->mapHeight = count($this->input);
        $this->mapWidth = strlen($this->input[0]);
        
        foreach ($this->input as $y => $row) {
            
            if (($pos = strpos($row, "^")) !== false) {
                $this->startingPos = ["x" => $pos, "y" => $y, "dx" => 0, "dy" => -1];
                
                break;
            }
        }
        
        $this->getInitialRoute();
    }
    
    private function guardTurnsRight(array &$guard):void {
        $newDx = $guard["dy"] * -1;
        $newDy = $guard["dx"];
        
        $guard["dx"] = $newDx;
        $guard["dy"] = $newDy;
    }
    
    private function guardMoves(array $guard): ?array {
        $visited = array_fill(
            0, $this->mapHeight, array_fill(
                0, $this->mapWidth, [
                    "(0,-1)" => 0,
                    "(1,0)" => 0,
                    "(0,1)" => 0,
                    "(-1,0)" => 0
                ]
            )
        );
        
        $visited[$guard["y"]][$guard["x"]]["({$guard["dx"]},{$guard["dy"]})"] = 1;
        
        while (true) {
            $newX = $guard["x"] + $guard["dx"];
            $newY = $guard["y"] + $guard["dy"];
            
            if ($newX < 0 || $newX >= $this->mapWidth || $newY < 0 || $newY >= $this->mapHeight) {
                return $visited;
            }
            
            if ($visited[$newY][$newX]["({$guard["dx"]},{$guard["dy"]})"] === 1) {
                
                return null;
            }
            
            if ($this->input[$newY][$newX] === "#") {
                $this->guardTurnsRight($guard);
                
                $visited[$guard["y"]][$guard["x"]]["({$guard["dx"]},{$guard["dy"]})"] = 1;
                
                continue;
            }
            
            $guard["x"] = $newX;
            $guard["y"] = $newY;
            
            $visited[$newY][$newX]["({$guard["dx"]},{$guard["dy"]})"] = 1;
        }
    }
    
    private function getInitialRoute(): void {
        $initialRoute = $this->guardMoves($this->startingPos);
        $tmp = $initialRoute;
        
        foreach ($initialRoute as $y => $row) {
            foreach ($row as $x => $col) {
                if (array_count_values($col)[0] === 4) {
                    $tmp[$y][$x] = 0;
                } else {
                    $tmp[$y][$x] = 1;
                }
            }
        }
        
        $this->initialRoute = $tmp;
    }
    
    private function solve1(): int {
        $positionCounter = 0;
        
        foreach ($this->initialRoute as $row) {
            foreach ($row as $col) {
                $positionCounter += $col;
            }
        }
        
        return $positionCounter;
    }
    
    private function solve2(): int {
        $this->initialRoute[$this->startingPos["y"]][$this->startingPos["x"]] = 0;
        $default_input = $this->input;
        $infinite_loop_counter = 0;
        
        foreach ($this->initialRoute as $y => $row) {
            foreach ($row as $x => $col) {
                if ($col === 1) {
                    $this->input = $default_input;
                    $this->input[$y][$x] = "#";
                    
                    if ($this->guardMoves($this->startingPos) === null) {
                        $infinite_loop_counter++;
                    }
                }
            }
        }
        
        return $infinite_loop_counter;
    }
}
    
$path = "puzzle_input.txt";
(new Day06($path))->solve();