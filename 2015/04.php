<?php
    /**
     * https://adventofcode.com/2015/day/4
     *
     *
     *
     * Santa is looking for a secret key with the help of md5.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("04.txt")) { // If file is missing, terminate
        die("Missing file 04.txt");
    } else {
        $input = file_get_contents("04.txt"); // Save file as a string
    }
    
    
    
    /**
     * In part 1 we need to use our secret key combined with an integer to get a result that starts with 5 zeros (00000).
     * To do this we just take your secret key, add an integer to the end, do md5 and check for 5 zeros. If found, return current integer
     *
     *
     * ** SPOILER **
     * Part 2 is to find the result that starts with 6 zeros (000000)
     */
    function solve($input) {
        $int = 1; // Starting integer
        $res = [5 => null, 6 => null]; // Store how many steps it takes to find solution for both part 1 and 2
        
        
        
        // Keep looping until we found both results
        while (true) {
            $md5 = md5("$input$int"); // Get result of md5(<secret key><integer>)
            
            
            
            // Does it begin with 5 leading zeroes?
            if (substr($md5, 0, 5) === "00000") {
                // Do we already have a result for 5 leading zeroes?
                if (is_null($res[5])) {
                    $res[5] = $int; // Save current integer for part 1
                }
                
                // If we have 5 leading zeros, is the 6:th character also a zero?
                if ($md5[5] === "0") {
                    $res[6] = $int; // Save current integer for part 2
                    
                    
                    
                    // If we reached this point, we have completed both part 1 and 2, so we exit
                    return $res;
                }
            }
            
            
            
            $int++; // Increase to next integer
        }
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    $res = solve($input);
    echo "Part 1: $res[5] and" . PHP_EOL;
    
    
    
    // Solve part 2
    echo "Part 2: $res[6] (solved in " . (microtime(true) - $start) . " seconds)";
