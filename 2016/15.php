<?php
    /**
     * https://adventofcode.com/2016/day/15
     *
     *
     *
     * The halls open into an interior plaza containing a large kinetic sculpture.
     * The sculpture is in a sealed enclosure and seems to involve a set of identical spherical capsules that are carried to the top
     * and allowed to bounce through the maze of spinning pieces.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("15.txt")) { // If file is missing, terminate
        die("Missing file 15.txt");
    } else {
        $input = file("15.txt"); // Save file as an array
    }
    
    
    
    /**
     * For this problem, some amount of wheels is spinning simultaneously, and we need to find the right moment to release a ball through them all.
     * Once the ball is released its goal is to fall through all discs in one go. The discs rotate one step every second,
     * and the fall takes one second of fall time to reach the next disc.
     *
     * For the ball to fall through the disc, lets say that the disc needs to be at position 0 rotation-wise.
     * That means that the first disc needs to be a 1 step away from position 0, because it takes the ball one second to reach the first disc.
     * The second disc needs to be 2 steps away from position 0 because it takes the ball 2 seconds to reach the second disc.
     * This pattern continues through all the discs.
     *
     * To solve this, we simply start at time = 0 and drop the ball. Once the ball reaches a disc,
     * we count the amount of steps that disc has taken since the ball was dropped. We use modulo to keep track of the current position of the disc.
     * If we collide with a disc, we just reset at start from the top increment time with one.
     *
     *
     *
     * ** SPOILER **
     * For part 2 we just add another disc at the bottom.
     */
    function solve($input, $part2 = false) {
        // If we are doing part 2, add another disc at the bottom
        if ($part2) {
            $input[] = "Disc #7 has 11 positions; at time=0, it is at position 0."; // Add a disc to the input
        }
        $discs = []; // Holds all the discs with information about current position and amount of possible positions
        
        
        
        // Loop through the input-file and save all disc-data
        foreach ($input as $index => $row) {
            $disc = explode(" ", trim($row)); // Remove unwanted characters and split a string into an array at every space-character
            
            $discs[$index + 1] = [ // The key represents the disc placement. First disc has key = 1, second key = 2, and so on.
                "positions" => intval($disc[3]), // Stores the amount of positions the disc can have (used for modulo)
                "currentPosition" => intval(substr($disc[11], 0, -1)) // Holds the starting position of the disc
            ];
        }
        
        
        
        // Start timer at 0 and increment for each try
        for ($time = 0; ; $time++) {
            // Loop through every disc to check if it's aligned correctly
            foreach ($discs as $index => $disc) {
                // Take the time when we released the ball ($time),
                // add the amount of seconds it takes the ball to reach current disc ($index),
                // add the starting position of the disc ($disc["currentPosition"]).
                // Modulo everything with the amount of positions the disc can have.
                // If the result is not 0, the disc is positioned wrong and the ball will not fall through.
                // Reset and increment time
                if (($time + $index + $disc["currentPosition"]) % $disc["positions"] !== 0) {
                    continue 2;
                }
            }
            
            
            
            // If we reach this point, the ball has fallen through all discs and we have found our release time
            break;
        }
        
        
        
        return $time;
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    echo "Part 1: " . solve($input) . " (solved in " . (microtime(true) - $start) . " seconds)" . PHP_EOL;
    
    
    
    // Solve part 2
    $part2 = true; // Tells our function to use parts needed to solve part 2
    $start = microtime(true);
    echo "Part 2: " . solve($input, $part2) . " (solved in " . (microtime(true) - $start) . " seconds)";
