<?php
    /**
     * https://adventofcode.com/2015/day/10
     *
     *
     *
     * Today, the Elves are playing a game called look-and-say.
     * They take turns making sequences by reading aloud the previous sequence and using that reading as the next sequence.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("10.txt")) { // If file is missing, terminate
        die("Missing file 10.txt");
    } else {
        $input = file("10.txt"); // Save file as a string
    }
    
    
    
    /**
     * In part 1 we need to play 40 rounds of the game "look-and-say". The rules are very simple:
     * You take the current number, replace each run of digits with the amount of said digits and then the digit itself.
     * For example: 111 is 3 copies of 1. So the next number is 31. The number after 31 is 1311 (1 copy of 3, 1 copy of 1).
     * Another example: 1 becomes 11 (1 copy of 1). 11 becomes 21 (2 copies of 1). 21 becomes 1211 (1 copy of 2, 1 copy of 1)
     * 1211 becomes 111221 (1 copy of 1, 1 copy of 2, 2 copies of 1).
     * To find the solution we need to run this 40 steps.
     *
     *
     *
     *
     *
     * ** SPOILER **
     * For part 2, we do exactly the same as part 1. The only difference is that we want the LONGEST distance.
     */
    function solve($input) {
        $loopCount = 50; // How many rounds we are playing
        $res = []; // Storing the result
        
        
        
        // Play a round
        for ($i = 0; $i < $loopCount; $i++) {
            // If we have played 40 rounds, we are done with part 1
            if ($i === 40) {
                $res[] = strlen($input); // Store the result of part 1
            }
            
            
            
            $newInput = ""; // Holds the next number-series
            $copiesOf = 1; // Start every new round with this many copies of the first number
            $strlen = strlen($input); // How many digits are we to loop through
            
            
            
            // Loop through all digits
            for ($j = 1; $j < $strlen; $j++) { // Note that we are starting on the second digit, not the first
                // If the current digit is the same as the previous
                if ($input[$j] === $input[$j - 1]) {
                    $copiesOf++; // Increase "copies of"
                }
                
                // Otherwise append the current "copy of digit" and the digit itself to the new number-series
                else {
                    $newInput .= $copiesOf . $input[$j - 1];
                    $copiesOf = 1; // Reset "copies of" to 1
                }
            }
            
            // Append the last number/series of number from the current number-series to the new number-series
            $newInput .= $copiesOf . $input[$j - 1];
            
            
            
            $input = $newInput; // Overwrite the old number-series with the new series
        }
        
        $res[] = strlen($input); // Store the result of part 2
        
        
        
        return $res;
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    $res = solve($input[0]);
    echo "Part 1: " . $res[0] . " and" . PHP_EOL;
    
    
    
    // Solve part 2
    echo "Part 2: " . $res[1] . " (solved in " . (microtime(true) - $start) . " seconds)";
