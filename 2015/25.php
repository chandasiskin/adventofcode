<?php
    /**
     * https://adventofcode.com/2015/day/25
     *
     *
     *
     * Merry Christmas! Santa is booting up his weather machine; looks like you might get a white Christmas after all.
     * The weather machine beeps! On the console of the machine is a copy protection message asking you to enter a code from the instruction manual.
     * Apparently, it refuses to run unless you give it that code.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("25.txt")) { // If file is missing, terminate
        die("Missing file 25.txt");
    } else {
        $input = file_get_contents("25.txt"); // Save file as a string
    }
    
    
    
    /**
     * We need to calculate the value at the row and column mentioned in the input-file.
     * To get that value at those coordinates, we need to calculate every value up to that point.
     * The problem is that the path to said coordinates isn't straight, but diagonal, from bottom to top-right.
     * Each column is represented by an x-coordinate and each row is represented by a y-coordinate.
     * Every diagonal starts at the left-most column, x = 1. The first number is at x = 1 and y = n (where n > 0).
     * For this example, lets say that n = 4. So, the first number in order goes to pos [4,1] (x = 1 and y = 4).
     * The number after that goes to [3,2], the next goes to [2,3] and the last, to complete the diagonal, goes to [1,4].
     * It would look like this (where the numbers on the top represents the column-number [x-coordinate], the numbers
     * to the left represents the row-number [y-coordinate] and the letters represents the value on that coordinate):
     *    1 2 3 4
     *   ________
     * 1| . . . d
     * 2| . . c
     * 3| . b
     * 4| a
     *
     * To follow the pattern we keep incrementing the x-coordinate and decrement the y-coordinate after every value-calculation
     * until we reach y = 1. When that happens, we reset x-coordinate back to 1 and set y-coordinate to one larger than the previous starting value.
     * This means, if we started at [4,1], once we reach [1,4], the next starting point will be at [4+1,1] = [5,1].
     * The value is calculated by starting with '20151125' and for every new coordinate we multiply the current value by '252533',
     * divide the result by '33554393' and store the remainder as the new value.
     */
    function solve($input) {
        preg_match_all("/\d+/", $input, $matches); // Get the coordinates
        $coords = array_map("intval", $matches[0]); // Turn coordinates into integers [<y-coordinate>,<x-coordinate]
        $value = 20151125; // Starting value
        $y = $x = 1; // Coordinates of the starting value
        $yMax = $y + 1; // Next row number when a new diagonal starts
        
        
        
        // Loop until we find our coordinates
        while ($y !== $coords[0] || $x !== $coords[1]) {
            $y--; // Jump to row above
            $x++; // Jump to column to the right
            $value = ($value * 252533) % 33554393; // Calculate the new value
            
            // If we reach the top row, start a new diagonal below the last starting row
            if ($y < 1) {
                $y = $yMax; // Set the new starting row
                $yMax++; // Increase the next starting row
                $x = 1; // Set starting column to the left-most
            }
        }
        
        
        
        return $value;
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    $res = solve($input);
    echo "Part 1: " . $res . " (solved in " . (microtime(true) - $start) . " seconds)";
