<?php
    /**
     * https://adventofcode.com/2017/day/1
     *
     *
     *
     * You're standing in a room with "digitization quarantine" written in LEDs along one wall.
     * The only door is locked, but it includes a small interface. "Restricted Area - Strictly No Digitized Users Allowed."
     *
     * It goes on to explain that you may only leave by solving a captcha to prove you're not a human.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("01.txt")) { // If file is missing, terminate
        die("Missing file 01.txt");
    } else {
        $input = file_get_contents("01.txt"); // Save file as a string
    }
    
    
    
    /**
     * We get a bunch of numbers from our input. We need to loop through each of them and compare the current number to the next one.
     * If they match, add the number to the final result (not both, but any one of them).
     *
     *
     * ** SPOILER **
     * For part 2, instead of checking the next number, we need to check the number that is exactly <length of the list> / 2 steps away.
     * Still adding one of the numbers to the total in case of match.
     */
    function solve($input, $part2 = false) {
        $result = 0; // Holds the total sum
        $list = trim($input); // Removes unwanted characters from the input
        $length = strlen($list); // Gets the length of the list
        
        
        
        // If we are doing part 1, compare current number to the number one step away
        // If we are doing part 2, compare current number to the number <length of the list> / 2 steps away
        $nextNumber = !$part2 ? 1 : $length / 2;
        
        
        
        // Loop through each number
        for ($i = 0; $i < $length; $i++) {
            // Compare current number to the next number (different for part 1 and 2)
            if ($list[$i] === $list[($nextNumber + $i) % $length]) {
                $result += $list[$i]; // If they match, add the first number to the total
            }
        }
        
        
        
        return $result;
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    echo "Part 1: " . solve($input) . " (solved in " . (microtime(true) - $start) . " seconds)" . PHP_EOL;
    
    
    
    // Solve part 2
    $part2 = true; // Tells our function to use parts needed to solve part 2
    $start = microtime(true);
    echo "Part 2: " . solve($input, $part2) . " (solved in " . (microtime(true) - $start) . " seconds)";
