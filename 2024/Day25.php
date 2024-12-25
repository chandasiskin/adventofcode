<?php
    namespace adventOfCode;
    
    class Day25 {
        private array $input;
        private float $startTime;
        private array $locks = [];
        private array $keys = [];

        public function __construct(string $path) {
            $this->startTime = microtime(true);
            
            $this->input = array_map("trim", file($path));
        }
        
        public function solve(): void {
            $this->prepare();
            $sol = $this->solve1();
            
            $elapsedTime = microtime(true) - $this->startTime;
            $elapsedTimeMicroSeconds = $elapsedTime * 1000;
            
            echo
                "Part 1: $sol" . PHP_EOL .
                "Time: {$elapsedTimeMicroSeconds}ms";
        }
        
        private function prepare(): void {
            $rows = count($this->input);

            for ($column = 0; $column < 5; $column++) {
                $lockCounter = 0;
                $keyCounter = 0;

                for ($row = 0; $row < $rows; $row += 8) {
                    // Is lock?
                    if ($this->input[$row] === "#####") {
                        $this->locks[$lockCounter] = $this->locks[$lockCounter] ?? "";
                        $counter = 0;

                        for ($i = 1; $i < 7; $i++) {
                            if ($this->input[$row + $i][$column] === ".") {
                                break;
                            }

                            $counter++;
                        }

                        $this->locks[$lockCounter] .= $counter;
                        $lockCounter++;
                    }

                    // Is key
                    else {
                        $this->keys[$keyCounter] = $this->keys[$keyCounter] ?? "";
                        $counter = 0;

                        for ($i = 5; $i > 0; $i--) {
                            if ($this->input[$row + $i][$column] === ".") {
                                break;
                            }

                            $counter++;
                        }

                        $this->keys[$keyCounter] .= $counter;
                        $keyCounter++;
                    }
                }
            }
        }

        private function solve1(): int {
            $uniquePairs = 0;

            foreach ($this->keys as $key) {
                foreach ($this->locks as $lock) {
                    for ($column = 0; $column < 5; $column++) {
                        if ($lock[$column] + $key[$column] > 5) {
                            continue 2;
                        }
                    }

                    $uniquePairs++;
                }
            }

            return $uniquePairs;
        }
    }
    
    $path = "puzzle_input.txt";
    (new Day25($path))->solve();
