<?php
    namespace adventOfCode;
    
    class Day02
    {
        private string $input;
        private float $startTime;
        private array $reports;
        
        public function __construct(string $path)
        {
            $this->startTime = microtime(true);
            
            $this->input = trim(file_get_contents($path));
        }
        
        public function solve(): void
        {
            $this->buildReports();
            
            $sol = $this->solveBoth();
            
            $elapsedTime = microtime(true) - $this->startTime;
            $elapsedTimeMicroSeconds = $elapsedTime * 1000;
            
            echo
                "Part 1: {$sol[0]}" . PHP_EOL .
                "Part 2: {$sol[1]}" . PHP_EOL .
                "Time: {$elapsedTimeMicroSeconds}ms";
        }
        
        private function buildReports(): void
        {
            $reports = preg_split("/[\r\n]+/", $this->input);
            
            $this->reports = array_fill(0, count($reports), []);
            
            foreach ($reports as $key => $report) {
                $levels = explode(" ", $report);
                
                $this->reports[$key] = array_map("intval", $levels);
            }
        }
        
        private function isSafe(array $levels): bool
        {
            if ($levels[0] === $levels[1]) {
                return false;
            }
            
            $increasing = $levels[0] < $levels[1];
            $max = count($levels) - 1;
            
            for ($i = 0; $i < $max; $i++) {
                if ($levels[$i] === $levels[$i + 1]) {
                    return false;
                }
                
                if ($levels[$i] < $levels[$i + 1] !== $increasing) {
                    return false;
                }
                
                if (abs($levels[$i] - $levels[$i + 1]) > 3) {
                    return false;
                }
            }
            
            return true;
        }
        
        private function solveBoth(): array
        {
            $safeReports_part_1 = 0;
            $safeReports_part_2 = 0;
            
            foreach ($this->reports as $levels) {
                if ($this->isSafe($levels)) {
                    $safeReports_part_1++;
                    $safeReports_part_2++;
                } else {
                    $length = count($levels);
                    
                    for ($i = 0; $i < $length; $i++) {
                        $tmp = $levels;
                        array_splice($tmp, $i, 1);
                        
                        if ($this->isSafe($tmp)) {
                            $safeReports_part_2++;
                            break;
                        }
                    }
                }
            }
            
            return [$safeReports_part_1, $safeReports_part_2];
        }
    }
    
    $path = "puzzle_input.txt";
    $day = new Day02($path);
    $day->solve();