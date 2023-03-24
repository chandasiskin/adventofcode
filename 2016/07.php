<?php
    /**
     * https://adventofcode.com/2016/day/7
     *
     *
     *
     * While snooping around the local network of EBHQ, you compile a list of IP addresses (they're IPv7, of course; IPv6 is much too limited).
     * You'd like to figure out which IPs support TLS (transport-layer snooping).
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("07.txt")) { // If file is missing, terminate
        die("Missing file 07.txt");
    } else {
        $input = file("07.txt"); // Save file as an array
    }
    
    
    
    /**
     * I had a hard time writing code that's understandable and easy to follow that solves both part 1 and 2 at the same time,
     * so I decided not to. But they both do share a lot of similarities. They both:
     * - Loop through all the input-rows (obviously)
     * - Split every input-row into an array separating the non-bracket strings from the bracket-strings
     * - Check every character looking for the valid character combination
     *
     * For part 1, when looking through every character for the valid combination, we store a boolean if a valid combination is found,
     * to prevent looking further in any non-bracket strings. We also look for an illegal combination in any of the bracket-strings,
     * and if found we just abort and move on to the next row.
     * If a legal and no illegal combination is found, we increment the result-counter.
     *
     *
     *
     * ** SPOILER **
     * The difference in part 1 and 2, besides what counts as a valid combination,
     * is that we first loop through each character in every non-bracket string. If a valid combination is found,
     * we search for its counterpart in every bracket-string. If a match is found, increment the result-counter and move on to the next input-row.
     * If no match is found, return to the non-bracket string and keep looking for the next valid combinations.
     * If found, check the bracket-strings once again. If we run through all the non-bracket strings without finding a valid combination,
     * or we find no match in the bracket-strings, move on without increment the result-counter.
     */
    function solve($input, $part2 = false) {
        // Part 1
        if (!$part2) {
            $tlsSupport = 0; // Keeps count of valid rows
            
            
            
            // Loop through each row
            foreach ($input as $row) {
                $row = trim($row); // Remove unwanted characters
                // Split row into an array to separate non-bracket strings from bracket-strings
                preg_match_all("/[^\[\]]+/", $row, $matches);
                $legalMatchFound = false; // If a valid non-bracket combination is found
        
                
                
                foreach ($matches[0] as $key => $match) { // Loop through each non-bracket and bracket-string
                    if ($key % 2 === 0) { // If key is even, it means it's a non-bracket string
                        if (!$legalMatchFound) { // If a valid combination hasn't been found already
                            $len = strlen($match); // Get the length of the string
                            
                            for ($c = 0; $c < $len - 3; $c++) { // Loop through each character in the string (except the last 3)
                                if ($match[$c] === $match[$c + 3] // If current character matches the character 3 places from here (a__a)
                                    && $match[$c + 1] === $match[$c + 2] // If the next character matches the character after that (_aa_)
                                    && $match[$c] !== $match[$c + 1]) { // If current character differs from the next (ab__)
                                    $legalMatchFound = true; // A valid combination is found
                            
                                    continue 2; // Skip to next non-bracket string
                                }
                            }
                        }
                    }
                    
                    // If key is odd, we are working with a bracket-string
                    else {
                        $len = strlen($match); // Get the length of the string
                        
                        for ($c = 0; $c < $len - 3; $c++) { // Loop through each character in the string (except the last 3)
                            if ($match[$c] === $match[$c + 3] // If current character matches the character 3 places from here (a__a)
                                && $match[$c + 1] === $match[$c + 2] // If the next character matches the character after that (_aa_)
                                && $match[$c] !== $match[$c + 1]) { // If current character differs from the next (ab__)
                                continue 3;  // An illegal combination is found. Abort and continue with the next input-row
                            }
                        }
                    }
                }
        
                
                
                // If we reach this point, we have not found any illegal combinations. But have we found a legal?
                // If yes, increment counter
                if ($legalMatchFound) {
                    $tlsSupport++;
                }
            }
            
            
            
            return $tlsSupport;
        }
        
        // Part 2
        else {
            $sslSupport = 0; // Keeps count of valid rows
    
    
    
            // Loop through each row
            foreach ($input as $row) {
                $row = trim($row); // Remove unwanted characters
                // Split row into an array to separate non-bracket strings from bracket-strings
                preg_match_all("/[^\[\]]+/", $row, $matches);
    
    
    
                $max = count($matches[0]); // Counts the total amount of all non-bracket and bracket string
                
                // Loop through each non-bracket string (starting from 0 every other is a non-bracket string)
                for ($i = 0; $i < $max; $i += 2) {
                    $match = $matches[0][$i]; // Store the current non-bracket string for easier and clearer access
                    $len = strlen($match); // Get the length of the string
                    
                    // Loop through each character in current string
                    for ($c = 0; $c < $len - 2; $c++) {
                        if ($match[$c] === $match[$c + 2] // If current character matches the one 2 positions away (a_a)
                        && $match[$c] !== $match[$c + 1]) { // If current character differs from the next one (ab_)
                            // Loop through each bracket-string (starting from 1 every other is a bracket-string)
                            for ($j = 1; $j < $max; $j += 2) {
                                // If we find a combination of <second character><first character><second character>
                                // that matches from the non-bracket string, we have a valid row
                                if (strpos($matches[0][$j], $match[$c + 1] . $match[$c] . $match[$c + 1]) !== false) {
                                    $sslSupport++; // Increment counter
                                    
                                    continue 4; // Jump to next input-row
                                }
                            }
                        }
                    }
                }
            }
            
            
            
            return $sslSupport;
        }
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    echo "Part 1: " . solve($input) . " (solved in " . (microtime(true) - $start) . " seconds)" . PHP_EOL;
    
    
    
    // Solve part 2
    $part2 = true; // Tells our function to use parts needed to solve part 2
    $start = microtime(true);
    echo "Part 2: " . solve($input, $part2) . " (solved in " . (microtime(true) - $start) . " seconds)";
