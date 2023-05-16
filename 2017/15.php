<?php
    /**
     * https://adventofcode.com/2017/day/15
     *
     *
     *
     * Here, you encounter a pair of dueling generators. The generators, called generator A and generator B,
     * are trying to agree on a sequence of numbers. However, one of them is malfunctioning, and so the sequences don't always match.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("15.txt")) { // If file is missing, terminate
        die("Missing file 15.txt");
    } else {
        $input = file("15.txt"); // Save file as an array
    }
    
    
    
    /**
     * The problem to solve is pretty straightforward: we have two generators with their own starting values, and for each
     * round we multiply the current value with a factor (different for the two generators), divide with a common number
     * and keep the remainder. We turn the integers from generator A and B into binary and compare the 16 least significant
     * numbers. If they match, we increment our counter.
     *
     *
     * ** SPOILER **
     * For part 2, the integer produced by generator A needs to be divisible with 4. If not, generate a new integer.
     * The same goes for generator B, except it needs to be divisible by 8.
     */
    function solve($input, $part2 = false) {
        $valueGeneratorA = intval(preg_replace("/[^\d]+/", "", $input[0])); // Starting value for generator A
        $factorGeneratorA = 16807; // Factor to multiply for generator A
        $valueGeneratorB = intval(preg_replace("/[^\d]+/", "", $input[1])); // Starting value for generator B
        $factorGeneratorB = 48271; // Factor to multiply for generator B
        $divider = 2147483647; // The common divider for both generators
        $matches = 0; // The number of matching binaries
        $numberOfPairs = !$part2 ? 40000000 : 5000000; // If we are doing part 1, loop 40 000 000 times.
                                                        // If we are doing part 2, loop 5 000 000 times.
        
        
        
        for ($i = 0; $i < $numberOfPairs; $i++) {
            // Keep creating new integers for generator A until the requirement is met
            do {
                $valueGeneratorA = ($valueGeneratorA * $factorGeneratorA) % $divider; // Get the next value for generator A
                
                
                
                // If we are doing part 1, requirement met
                if (!$part2) {
                    break;
                }
                
                // If we are doing part 2, check if the value is divisible by 4
                elseif ($valueGeneratorA % 4 === 0) {
                    break;
                }
            } while (true);
            
            // Keep creating new integers for generator B until the requirement is met
            do {
                $valueGeneratorB = ($valueGeneratorB * $factorGeneratorB) % $divider; // Get the next value for generator B
                
                
                
                // If we are doing part 1, requirement met
                if (!$part2) {
                    break;
                }
                
                // If we are doing part 2, check if the value is divisible by 8
                elseif ($valueGeneratorB % 8 === 0) {
                    break;
                }
            } while (true);
            
            
            
            // Compare binaries. If they match, increment counter
            if (isBinaryEqual($valueGeneratorA, $valueGeneratorB)) {
                $matches++;
            }
        }
        
        
        
        return $matches;
    }
    
    
    
    /**
     * @param $a int The first integer
     * @param $b int The second integer
     * @return bool Return true if they match
     */
    function isBinaryEqual($a, $b) {
        // Remove all but the 16 least significant binaries from both integers and compare the results
        return decbin($a & 0xFFFF) == decbin($b & 0XFFFF);
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    echo "Part 1: " . solve($input) . " (solved in " . (microtime(true) - $start) . " seconds)" . PHP_EOL;
    
    
    
    // Solve part 2
    $part2 = true; // Tells our function to use parts needed to solve part 2
    $start = microtime(true);
    echo "Part 2: " . solve($input, $part2) . " (solved in " . (microtime(true) - $start) . " seconds)";
