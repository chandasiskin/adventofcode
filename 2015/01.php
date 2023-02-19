<?php
    /**
     * https://adventofcode.com/2015/day/1
     *
     *
     *
     * Santa starts at floor 0, and moves up a floor when input shows an opening parenthesis "(" and moves down a floor when input shows a closing parenthesis ")".
     * The building has unlimited floors both up and down.
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
     * Part 1 is to calculate what floor he ends up on when all moves are made.
     * We do this by starting at floor 0, loop through each character in input and increment the floor by one for each "(" and decrement for each ")".
     *
     *
     * ** SPOILER **
     * In part 2 we need to calculate how many moves it takes for Santa to reach the basement (floor -1) for the first time.
     * Note: Completed moves is current index + 1
     */
    function solve($input, $part2 = false) {
        $moveCount = strlen($input); // Get move count
        $floor = 0; // Set starting floor
        
        
        
        // Loop through all moves and move Santa according
        for ($i = 0; $i < $moveCount; $i++) {
            // If Santa moves up
            if ($input[$i] === "(") {
                $floor++;
            }
            
            // If Santa moves down
            elseif ($input[$i] === ")") {
                $floor--;
            }
            
            // If an illegal character is found in input-file
            else {
                die("Invalid character: $input[$i]");
            }
            
            
            
            // If we are trying to solve part 2, where we are looking for when Santa reaches the basement (floor -1) for the first time
            if ($part2 && $floor === -1) {
                return $i + 1; // Return current move count, which is current index + 1
            }
        }
        
        
        
        return $floor; // Return the floor Santa ends up on
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    echo "Part 1: " . solve($input) . " (solved in " . (microtime(true) - $start) . " seconds)" . PHP_EOL;
    
    
    
    // Solve part 2
    $part2 = true; // Tells our function to use parts needed to solve part 2
    $start = microtime(true);
    echo "Part 2: " . solve($input, $part2) . " (solved in " . (microtime(true) - $start) . " seconds)";
