<?php
    /**
     * https://adventofcode.com/2016/day/9
     *
     *
     *
     * Wandering around a secure area, you come across a data-link port to a new part of the network.
     * After briefly scanning it for interesting files, you find one file in particular that catches your attention.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("09.txt")) { // If file is missing, terminate
        die("Missing file 09.txt");
    } else {
        $input = file_get_contents("09.txt"); // Save file as a string
    }
    
    
    
    /**
     * We have a very long input-string with "markers". A marker starts with an opening parenthesis "(" and ends with a closing parenthesis ")".
     * The number to left of the "x" is how many characters AFTER the marker to repeat, and the number to the right of the "x"
     * states how many times to repeat said characters. "(4x3)ABCDE" means "repeat the first four characters after the marker (ABCD)
     * 3 times. This result is ABCD_ABCD_ABCD_E (the underscores are there for better readability, not counter in the final result).
     *
     * For part 1, we treat markers within markers as regular characters, meaning (6x2)(1x3)AB results in (1x3)A_(1x3)A_B.
     * To solve this, we loop through each character in our input-string and look for the starting character for a marker: a "(".
     * When this is found, we retrieve the numbers within the marker, multiply the "amount-of-characters" with "amount-of-repeats"
     * to get the total character count. After that is done, we jump forward in our original string to where the marker and its
     * associated characters end: <marker-length> + <amount-of-characters>.
     *
     *
     * ** SPOILER **
     * For part 2, we DO NOT skip markers within markers. If we look at the same example as in part 1, this time
     * (6x2)(1x3)AB will result in (A_A_A)_(A_A_A)_B (parenthesis and underscores only for readability).
     * After the first marker we get (1x3)A_(1x3)A_B and after solving the remaining markers we get to the end result.
     *
     * To solve this, we have the same approach as on part 1. Except when we find a marker. When that happens,
     * we use the marker-information to create a new, temporary string from the original. This temporary string contains
     * all the characters that is associated with the current marker. We use this new string as our "input" when we call
     * the same function again.
     */
    function solve($input, $part2 = false) {
        $characters = trim($input); // Remove unwanted characters
        
        
        
        return findSolution($characters, $part2);
    }
    
    
    
    function findSolution($input, $part2) {
        $sum = 0; // Holds the total character-count
        $len = strlen($input); // Get the length of the input-string
        
        
        
        // Loop through every character in the string
        for ($c = 0; $c < $len; $c++) {
            // If we find the beginning of a marker
            if ($input[$c] === "(") {
                // Extract the marker starting from the next character until the end of the marker
                $marker = substr($input, $c + 1, strpos(substr($input, $c + 1), ")"));
                // Store the first value as <amount of characters> and the second value as <repeat-count>
                // and force them as integers
                list($characterCount, $repeats) = array_map("intval", explode("x", $marker));
                
                
                
                // If we are doing part 1
                if (!$part2) {
                    $sum += $characterCount * $repeats; // Multiply <amount of characters> with <amount of repeats>
                }
                
                // If we are doing part 2
                else {
                    // Create a new, temporary string from the characters associated with the current marker (sub-markers included)
                    // by extracting from the first character after the marker and <amount of characters> forward.
                    $substr = substr($input, $c + strlen("({$characterCount}x{$repeats})"), $characterCount);
                    $sum += findSolution($substr, $part2) * $repeats; // Call same function again with this new string
                }
                
                
                
                // Jump to the character after the marker and the amount of characters stated in the marker
                $c += strlen("{$characterCount}x{$repeats})") + $characterCount;
            }
            
            // If we found just an ordinary character
            else {
                $sum++; // Increment total character count
            }
        }
        
        
        
        return $sum;
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    echo "Part 1: " . solve($input) . " (solved in " . (microtime(true) - $start) . " seconds)" . PHP_EOL;
    
    
    
    // Solve part 2
    $part2 = true; // Tells our function to use parts needed to solve part 2
    $start = microtime(true);
    echo "Part 2: " . solve($input, $part2) . " (solved in " . (microtime(true) - $start) . " seconds)";
