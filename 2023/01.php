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
     * Same as part 1, except a number can also be written as text, i.e. "one", "two", and so on
     */
    function solve($input, $part2 = false) {
        $numbers = [
            "one" => 1, "two" => 2, "three" => 3, "four" => 4, "five" => 5,
            "six" => 6, "seven" => 7, "eight" => 8, "nine" => 9
        ]; // Set the value of each number
        $res = 0; // The grand total
        
        
        
        // Loop through each input-row
        foreach ($input as $str) {
            $tmp = $str; // Copy current row
            
            
            
            // If doing part 2, replace all string-numbers with "ABC", where
            // A = The first letter of the string-number
            // B = The value of the string number ("one" = 1, "five" = 5)
            // C = The last letter of the string number.
            // For example, "one" would end up as "o1e", "three" would turn into "t3e".
            // This is done because when a string like "eightwo" is sent to regex, it will not find both "eight" and "two",
            // which is what we want. By turning "eightwo" first to "e8two" and then "e8t2o", that issue is fixed.
            if ($part2) {
                // Loop through each key in $numbers and replace according to the pattern mentioned above
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
