<?php
    /**
     * https://adventofcode.com/2015/day/5
     *
     *
     *
     * Santa needs help figuring out which strings in his text file are naughty or nice.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("05.txt")) { // If file is missing, terminate
        die("Missing file 05.txt");
    } else {
        $input = file("05.txt"); // Save file as an array
    }
    
    
    
    /**
     * In part 1 we need to check every string in the input and compare it to a set of rules.
     * If all rules are met, the string is nice, otherwise it's naughty.
     * The rules are:
     * - It contains at least three vowels (aeiou only)
     * - It contains at least one letter that appears twice in a row
     * - It does not contain the strings ab, cd, pq, or xy
     *
     * We solve it by running a regex on every string
     *
     *
     *
     * ** SPOILER **
     * Part 2 has the same idea as part 1, except it has its own rules:
     * - It contains a pair of any two letters that appears at least twice in the string without overlapping
     * - It contains at least one letter which repeats with exactly one letter between them
     */
    function solve($input, $part2 = false) {
        $niceStringCounter = 0; // Keep track of how many nice strings we have
        
        
        
        // Loop through each string in input
        foreach ($input as $string) {
            if (!$part2) { // Part 1 and part 2 have different set of rules
                if (preg_match_all("/[aeiou]/i", $string) >= 3 // Does string meet the "three vowels rule"?
                && preg_match("/(.)\\1/i", $string) // Does string meet the "two similar letters in a row"?
                && !preg_match("/ab|cd|pq|xy/i", $string)) { // Does string mee the "do not contain"?
                    $niceStringCounter++;
                }
            } else {
                if (preg_match("/(..).*\\1/i", $string) // Does string meet "pair of double letter"?
                && preg_match("/(.).\\1/i", $string)) { // Does string meet "repeating letter"?
                    $niceStringCounter++;
                }
            }
        }
        
        
        
        return $niceStringCounter;
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    echo "Part 1: " . solve($input) . " (solved in " . (microtime(true) - $start) . " seconds)" . PHP_EOL;
    
    
    
    // Solve part 2
    $part2 = true;
    $start = microtime(true);
    echo "Part 2: " . solve($input, $part2) . " (solved in " . (microtime(true) - $start) . " seconds)";
