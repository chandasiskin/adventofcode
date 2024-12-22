<?php
    namespace adventOfCode;
    
    class Day22 {
        private array $input;
        private float $startTime;
        private int $EVOLUTIONS = 2000;
        private int $PRUNE_VALUE = 16777216;

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

        }

        private function calculateNextSecretNumber(int $secretNumber): int {
            $result = $secretNumber * 64;
            $secretNumber ^= $result;
            $secretNumber %= 16777216;

            $result = (int) ($secretNumber / 32);
            $secretNumber ^= $result;
            $secretNumber %= 16777216;

            $result = $secretNumber * 2048;
            $secretNumber ^= $result;
            $secretNumber %= 16777216;

            return $secretNumber;
        }

        private function solve1(): int {
            $sum = 0;

            foreach ($this->input as $secretNumber) {
                for ($i = 0; $i < $this->EVOLUTIONS; $i++) {
                    $secretNumber = $this->calculateNextSecretNumber($secretNumber);
                }

                $sum += $secretNumber;
            }

            return $sum;
        }
        
        private function solve2(): string {
            return "0";
        }
    }
    
    $path = "puzzle_input.txt";
    (new Day22($path))->solve();
