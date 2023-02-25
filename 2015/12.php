<?php
    /**
     * https://adventofcode.com/2015/day/12
     *
     *
     *
     * Santa's Accounting-Elves need help balancing the books after a recent order.
     * Unfortunately, their accounting software uses a peculiar storage format.
     * That's where you come in.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("12.txt")) { // If file is missing, terminate
        die("Missing file 12.txt");
    } else {
        $input = file_get_contents("12.txt"); // Save file as a string
    }
    
    
    
    /**
     * In part 1 we need to find all the numbers and sum them up.
     * For this we use regex to find all numbers, store the matches in an array and sum up the array. Boom, one star!
     *
     *
     *
     * ** SPOILER **
     * For part two we have to do the same as for part 1, except we remove all objects ({...}) and it's sub-objects
     * and/or sub-arrays if the word "red" is present.
     * We'll solve this one array/object at a time.
     */
    function solve($input) {
        // Part 1
        preg_match_all("/-?\d+/", $input, $matches); // Find all digits
        $result1 = array_sum($matches[0]); // Sum the digits
        
        
        
        // Part 2
        // Keep looping as long as we find an array or object
        while (preg_match("/[\[{]+/", $input)) {
            // Search for an array [...] that contains no sub-arrays or sub-objects
            // and store the first match in an array ($matches)
            if (preg_match("/\[[^\[{]*?]/", $input, $matches)) {
                // Find all digits in the array
                preg_match_all("/-?\d+/", $matches[0], $matches2);
                // Calculate the sum of the array
                $sum = array_sum($matches2[0]);
                // Replace the found array with the sum (ex. '["a":2,"b":3]' becomes '6')
                $input = str_replace($matches[0], "$sum", $input);
            }
        
            // Search for an object {...} that contains no sub-arrays or sub-objects
            // and store the first match in an array ($matches)
            if (preg_match("/\{[^\[{]*?}/", $input, $matches)) {
                // Does the object contain the word "red"?
                if (str_contains($matches[0], "red")) {
                    // Delete the object
                    $input = str_replace($matches[0], "", $input);
                } else {
                    // Find all digits in the object
                    preg_match_all("/-?\d+/", $matches[0], $matches2);
                    // Calculate the sum of the object
                    $sum = array_sum($matches2[0]);
                    // Replace the found object with the sum (ex. '{"a":2,"b":3}' becomes '6')
                    $input = str_replace($matches[0], "$sum", $input);
                }
            }
        }
        
        $result2 = $input;
    
    
    
    
        return [$result1, $result2];
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    $res = solve($input);
    echo "Part 1: " . $res[0] . " and" . PHP_EOL;
    
    
    
    // Solve part 2
    echo "Part 2: " . $res[1] . " (solved in " . (microtime(true) - $start) . " seconds)";
