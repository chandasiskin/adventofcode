<?php
    namespace adventOfCode;
    
    class Day24 {
        private array $input;
        private float $startTime;
        private array $values = [];
        private array $connections = [];
        private array $valuesStartingWithZ = [];

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
            $isInitialValue = true;

            foreach ($this->input as $row) {
                if ($row === "") {
                    $isInitialValue = false;

                    continue;
                }

                if ($isInitialValue) {
                    list($key, $value) = explode(": ", $row);
                    $this->values[$key] = (int)$value;
                } else {
                    $this->connections[] = str_replace(" ->", "", $row);
                }
            }
        }

        private function solve1(): int {
            $sum = 0;
            $values = $this->values;
            $connections = $this->connections;

            while (!empty($connections)) {
                $tmp = [];

                foreach ($connections as $row) {
                    list($input_1, $gate, $input_2, $output) = explode(" ", $row);

                    if (isset($values[$input_1], $values[$input_2])) {
                        if ($gate === "AND") {
                            $values[$output] = $values[$input_1] & $values[$input_2];
                        } elseif ($gate === "OR") {
                            $values[$output] = $values[$input_1] | $values[$input_2];
                        } elseif ($gate === "XOR") {
                            $values[$output] = $values[$input_1] ^ $values[$input_2];
                        } else {
                            die("Invalid gate: $gate");
                        }

                        if ($output[0] === "z") {
                            $this->valuesStartingWithZ[$output] = $values[$output];
                        }
                    } else {
                        $tmp[] = $row;
                    }
                }

                $connections = $tmp;
            }

            krsort($this->valuesStartingWithZ);

            return bindec(implode($this->valuesStartingWithZ));
        }
        
        private function solve2(): string {
            return "";
        }
    }
    
    $path = "puzzle_input.txt";
    (new Day24($path))->solve();
