<?php
    /**
     * https://adventofcode.com/2017/day/8
     *
     *
     *
     * You receive a signal directly from the CPU. Because of your recent assistance with jump instructions,
     * it would like you to compute the result of a series of unusual register instructions.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("08.txt")) { // If file is missing, terminate
        die("Missing file 08.txt");
    } else {
        $input = file("08.txt"); // Save file as an array
    }
    
    
    
    /**
     * Straightforward solution:
     * 1. Split the row into an array
     * 2. Check if the condition to the right is met
     * 3. If step 2 return true, execute the part to the left
     * 4. Once all rows are completed, check the highest value in the register
     * NOTE: Don't forget to initialize all the registers to 0. And don't forget to check if the values are integers
     *      or references to a point in the register.
     *
     *
     * ** SPOILER **
     * For part 2, we need to keep track of what the highest value in the register was at any moment.
     */
    function solve($input) {
        $register = []; // The register
        $highestValueEver = 0; // The highest value every reached
        
        
        
        // Loop through every row in our input
        foreach ($input as $row) {
            $r = explode(" ", trim($row)); // Remove unwanted characters and convert string into a row
            
            // If the register that we're going to modify is not initialized, initialize it
            if (!isset($register[$r[0]])) {
                $register[$r[0]] = 0;
            }
            
            // If the amount we are about to increase/decrease with is a reference to a point in the register,
            // and that point is not yet initialized, initialize it
            if (!is_numeric($r[2]) && !isset($register[$r[2]])) {
                $register[$r[2]] = 0;
            }
            
            // If the left part of the condition is a reference to a point in the register,
            // and that point is not yet initialized, initialize it
            if (!is_numeric($r[4]) && !isset($register[$r[4]])) {
                $register[$r[4]] = 0;
            }
            
            // If the right part of the condition is a reference to a point in the register,
            // and that point is not yet initialized, initialize it
            if (!is_numeric($r[6]) && !isset($register[$r[6]])) {
                $register[$r[6]] = 0;
            }
            
            // If the left part of the condition is a reference to the register, get the value from the register.
            // Else, convert the value from the row into an integer
            $left = !is_numeric($r[4]) ? $register[$r[4]] : intval($r[4]);
            // If the right part of the condition is a reference to the register, get the value from the register.
            // Else, convert the value from the row into an integer
            $right = !is_numeric($r[6]) ? $register[$r[6]] : intval($r[6]);
            
            
            
            // Match the type of condition and check if the condition is met
            switch ($r[5]) {
                case ">": $isValid = $left > $right; break;
                case ">=": $isValid = $left >= $right; break;
                case "==": $isValid = $left == $right; break;
                case "!=": $isValid = $left != $right; break;
                case "<=": $isValid = $left <= $right; break;
                case "<": $isValid = $left < $right; break;
                default: die("Invalid op: $r[5]");
            }
            
            
            
            // If the condition is met, execute the left part
            if ($isValid) {
                // If the amount we are about to increase/decrease with is a reference to the register, get the value from the register.
                // Else, convert the value from the row into an integer
                $amount = !is_numeric($r[2]) ? $register[$r[2]] : intval($r[2]);
                
                // If we are about to add
                if ($r[1] === "inc") {
                    $register[$r[0]] += $amount;
                }
                
                // If we are about to subtract
                else {
                    $register[$r[0]] -= $amount;
                }
            }
            
            
            
            // Check if the value in the currently modified register is the greatest value in our execution so far
            $highestValueEver = max($highestValueEver, $register[$r[0]]);
        }
        
        
        
        // Return [<current highest value in the register>, <the highest value ever in the register>]
        return [max($register), $highestValueEver];
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    $res = solve($input);
    echo "Part 1: " . $res[0] . " (and" . PHP_EOL;
    
    
    
    // Solve part 2
    echo "Part 2: " . $res[1] . " (solved in " . (microtime(true) - $start) . " seconds)";
