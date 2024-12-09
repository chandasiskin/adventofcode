<?php
    namespace adventOfCode;
    
    class Day07 {
        private array $input;
        private float $startTime;
        private array $equations;
        
        public function __construct(string $path) {
            $this->startTime = microtime(true);
            
            $this->input = array_map("trim", file($path));
        }
        
        public function solve(): void {
            $this->prepare();
            $sol = [$this->solve1(2), $this->solve2()];
            
            $elapsedTime = microtime(true) - $this->startTime;
            $elapsedTimeMicroSeconds = $elapsedTime * 1000;
            
            echo
                "Part 1: {$sol[0]}" . PHP_EOL .
                "Part 2: {$sol[1]}" . PHP_EOL .
                "Time: {$elapsedTimeMicroSeconds}ms";
        }
        
        private function prepare(): void {
            $this->equations = array_fill(0, count($this->input), []);
            
            foreach ($this->input as $i => $row) {
                $splitted = array_map("intval", preg_split("/[: ]+/", $row));
                
                $this->equations[$i] = $splitted;
            }
        }
        
        private function solve1(int $possible_operations): int {
            $total_calibration_result = 0;
            
            foreach ($this->equations as $equation) {
                $test_value = $equation[0];
                $size = count($equation);
                $combinations = pow($possible_operations, $size - 2);
                $padding = $size - 2;
                
                for ($combination = 0; $combination < $combinations; $combination++) {
                    $calibration_result = $equation[1];
                    $bin = str_pad(base_convert($combination, 10, $possible_operations), $padding, "0", STR_PAD_LEFT);
                    
                    for ($i = 2; $i < $size; $i++) {
                        switch ($bin[$i - 2]) {
                            case "0": $calibration_result += $equation[$i]; break;
                            case "1": $calibration_result *= $equation[$i]; break;
                            case "2":
                                $calibration_result *= pow(10, strlen((string)$equation[$i]));
                                $calibration_result += $equation[$i];
                                break;
                            default: die("Invalid option: " . $bin[$i - 2]);
                        }
                        
                        if ($calibration_result > $test_value) {
                            continue 2;
                        }
                    }
                    
                    if ($calibration_result === $test_value) {
                        $total_calibration_result += $test_value;
                        
                        continue 2;
                    }
                }
            }
            
            return $total_calibration_result;
        }
        
        private function solve2(): int {
            return $this->solve1(3);
        }
    }
    
$path = "puzzle_input.txt";
(new Day07($path))->solve();