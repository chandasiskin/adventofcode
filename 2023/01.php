<?php
    /**
     * https://adventofcode.com/2023/day/1
     *
     *
     *
     * Something is wrong with global snow production, and you've been selected to take a look.
     * The Elves have even given you a map; on it, they've used stars to mark the top fifty locations that are likely
     * to be having problems.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("01.txt")) { // If file is missing, terminate
        die("Missing file 01.txt");
    } else {
        $input = file("01.txt"); // Save file as an array
    }
    
    
    
    /**
     * Part 1 is to find the first, and the last number (can be the same number if row only contains one number)
     * merge these (not add) and add the result to a total.
     *
     *
     * ** SPOILER **
     * In part 2 we need to calculate how many moves it takes for Santa to reach the basement (floor -1) for the first time.
     * Note: Completed moves is current index + 1
     */
    function solve($input, $part2 = false) {
        $numbers = [
            "one" => 1, "two" => 2, "three" => 3, "four" => 4, "five" => 5,
            "six" => 6, "seven" => 7, "eight" => 8, "nine" => 9
        ];
        $res = 0;
        
        
        
        // Loop through each row
        foreach ($input as $str) {
            $tmp = $str;
            
            
            
            if ($part2) {
                foreach ($numbers as $k => $v) {
                    $tmp = str_replace($k, $k[0] . $v . $k[strlen($k) - 1], $tmp);
                }
            }
            
            // Match all single numbers and store them
            preg_match_all("/\d/", $tmp, $matches);
            
            
            // Take the first digit and "set it as tens" and add it to the final result
            if (is_numeric($matches[0][0])) {
                $res += $matches[0][0] * 10;
            }
            
            // Take the last number and add it to the final result
            $lastDigit = array_pop($matches[0]);
            if (is_numeric($lastDigit)) {
                $res += $lastDigit;
            }
        }
        
        
        
        return $res;
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    echo "Part 1: " . solve($input) . " (solved in " . (microtime(true) - $start) . " seconds)<br />";
    
    
    
    // Solve part 2
    $part2 = true; // Tells our function to use parts needed to solve part 2
    $start = microtime(true);
    echo "Part 2: " . solve($input, $part2) . " (solved in " . (microtime(true) - $start) . " seconds)";
    //33728 is too low