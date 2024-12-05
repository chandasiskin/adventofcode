<?php
    namespace adventOfCode;
    
    class Day05 {
    private array $input;
    private array $rules;
    private array $updates;
    private array $incorrectly_ordered_updates;
    private float $startTime;
    
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
        $prepareRules = true;
   
        foreach ($this->input as $row) {
            if ($row === "") {
                $prepareRules = false;
                continue;
            }
   
            if ($prepareRules) {
                list($before, $after) = array_map("intval", explode("|", $row));
                    $this->rules[$before][$after] = 1;
            } else {
                    $this->updates[] = array_map("intval", explode(",", $row));
            }
        }
    }
    
    private function order_update(array $update): array {
        usort($update, function($a, $b) {
            if (isset($this->rules[$a][$b])) {
                return -1;
            }
            
            if (isset($this->rules[$b][$a])) {
                return 1;
            }
            
            return 0;
        });
        
        return $update;
    }
    
    private function solveBoth(): array {
            $middle_page_sum = 0;
        $incorrect_middle_page_sum = 0;
        
        foreach ($this->updates as $update) {
                    $max = count($update);
            
            for ($page = 0; $page < $max - 1; $page++) {
                for ($nextPage = $page + 1; $nextPage < $max; $nextPage++) {
                    if (!isset($this->rules[$update[$page]][$update[$nextPage]])) {
                            $incorrectly_ordered_updates[] = $update;
                            continue 3;
                    }
                }
            }
            
            $middle_page_sum += $update[$max / 2];
        }
        
        foreach ($incorrectly_ordered_updates as $update) {
            $ordered_update = $this->order_update($update);
            $middle_page_index = count($ordered_update) / 2;
            
            $incorrect_middle_page_sum += $ordered_update[$middle_page_index];
        }
        
        return [$middle_page_sum, $incorrect_middle_page_sum];
    }
}
    
$path = "puzzle_input.txt";
(new Day05($path))->solve();