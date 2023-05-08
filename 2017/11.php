<?php
    /**
     * https://adventofcode.com/2017/day/11
     *
     *
     *
     * Crossing the bridge, you've barely reached the other side of the stream when a program comes up to you, clearly in distress.
     * "It's my child process," she says, "he's gotten lost in an infinite grid!"
     */



    /**
     * Get input from file
     */
    if (!is_file("11.txt")) { // If file is missing, terminate
        die("Missing file 11.txt");
    } else {
        $input = file_get_contents("11.txt"); // Save file as a string
    }



    /**
     * A simple "traverse the map according to our input a see where you end up". The trick is that the grid doesn't consist of
     * squares bet hexagons (6 sides). The only adjustment we need to do is the distance we move each step.
     * When moving one step up, we can do it in three ways:
     * 1. Just move one step up
     * 2. Move "ne", then "nw"
     * 3. Move "nw", then "ne"
     * Since we end up in the same place, we need to adjust the step-count whether we move up in one step or two steps.
     * We adjust this by increasing the y-coordinate with 2 instead of 1 whenever we move up in one step (and decrease by 2
     * when moving down in one step).
     * When calculating the final distance, we need to take into account of this increasing/decreasing by 2 by simply dividing the
     * result by 2.
     *
     *
     *
     * ** SPOILER **
     * For part 2 we need to keep track of the furthest distance we ever reach from our starting point
     */
    function solve($input, $part2 = false) {
        $movements = explode(",", trim($input)); // Remove unwanted characters from input and convert input into an array
        $pos = [0, 0]; // Set starting position
        $furthest = 0; // Holds the largest distance from starting position
        
        
        
        // Loop through each movement from input
        foreach ($movements as $mov) {
            // Determine where to move
            switch ($mov) {
                case "ne": $d = [ 1, 1]; break; // If moving northeast, increase x- and y-coordinate by 1
                case "se": $d = [ 1,-1]; break; // If moving southeast, increase x-coordinate by 1 and decrease y-coordinate by 1
                case "s":  $d = [ 0,-2]; break; // If moving south, decrease y-coordinate by 2
                case "sw": $d = [-1,-1]; break; // If moving southwest, decrease x- and y-coordinate by 1
                case "nw": $d = [-1, 1]; break; // If moving northwest, decrease x-coordinate by 1 and increase y-coordinate by 1
                case "n":  $d = [ 0, 2]; break; // If moving north, increase y-coordinate by 2
                default: die("Invalid: $mov"); // If an invalid input has occurred
            }
            
            
            
            $pos[0] += $d[0]; // Update x-coordinate
            $pos[1] += $d[1]; // Update y-coordinate
            
            
            
            $furthest = max($furthest, (abs($pos[0]) + abs($pos[1])) / 2); // If a new furthest distance achieved, update.
        }
        
        
        
        // Return the distance from current position and the furthest distance achieved
        return [(abs($pos[0]) + abs($pos[1])) / 2, $furthest];
    }



    // Solve part 1
    $start = microtime(true);
    $res = solve($input);
    echo "Part 1: " . $res[0] . " and" . PHP_EOL;



    // Solve part 2
    echo "Part 2: " . $res[1] . " (solved in " . (microtime(true) - $start) . " seconds)";
