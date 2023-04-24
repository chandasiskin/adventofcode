<?php
    /**
     * https://adventofcode.com/2017/day/3
     *
     *
     *
     * You come across an experimental new kind of memory stored on an infinite two-dimensional grid.
     *
     * Each square on the grid is allocated in a spiral pattern starting at a location marked 1 and then counting up while spiraling outward.
     */



    /**
     * Get input from file
     */
    if (!is_file("03.txt")) { // If file is missing, terminate
        die("Missing file 03.txt");
    } else {
        $input = file_get_contents("03.txt"); // Save file as a string
    }



    /**
     * For part 1 we are filling an array with values in a spiral order:
     * 
     * 17  16  15  14  13
     * 18   5   4   3  12
     * 19   6   1   2  11
     * 20   7   8   9  10
     * 21  22  23---> ...
     * 
     * We store our current position as an array with an x- and y-coordinate.
     * We also need to keep track of what direction we are moving next.
     * To determine when to change direction we simply check if a certain neighbour coordinate has a value in it or not.
     * This means that when we are moving right, we keep checking for a value right above us. If there is no value set, we start moving up.
     * When moving up, we check the neighbour to our left. Moving left checks the neighbour below. Moving down controls the neighbour to the right.
     * To populate the grid of numbers, we use a simple for-loop that keeps track of what number to store next.
     * When saving is complete, we check what direction to move next and execute the move (and also update the direction if necessary).
     * 
     *
     *
     * ** SPOILER **
     * For part 2, we don't just increment the value to be stored. Instead,
     * we calculate the sum of all the eight neighbours and store the result. Once the sum exceeds our input, we are done! 
     */
    function solve($input, $part2 = false) {
        $input = intval(trim($input)); // Remove unwanted characters and convert input into an integer
        $grid = [0 => [0 => 1]]; // Store the first at position [0, 0]
        $pos = [1, 0]; // Set current position just right of our starting position ([1, 0])
        $dir = "R"; // Set our current direction


        
        // Loop through every number from 2 (since we already have 1 stored) up to our input-number
        for ($n = 2; $n < $input; $n++) {
            // If we are doing part 2
            if ($part2) {
                $neighbourSum = getNeighbourSum($grid, $pos); // Get the sum of our neighbouring values
                
                // If the sum exceeds our input, return the sum
                if ($neighbourSum > $input) {
                    return $neighbourSum;
                }
            }
            
            // If we are doing part 1, store the current number.
            // If we are doing part 2, store the sum of the neighbours
            $grid[$pos[1]][$pos[0]] = !$part2 ? $n : $neighbourSum;


            
            // If we are currently moving right
            if ($dir === "R") {
                // If the value above us is set, keep moving right
                if (isset($grid[$pos[1] - 1][$pos[0]])) {
                    $pos[0]++;
                }

                // Otherwise move up and update direction to up
                else {
                    $pos[1]--;
                    $dir = "U";
                }
            }

            // If we are currently moving up
            elseif ($dir === "U") {
                // If the value to the left is set, keep moving up
                if (isset($grid[$pos[1]][$pos[0] - 1])) {
                    $pos[1]--;
                }

                // Otherwise move left and update direction to left
                else {
                    $pos[0]--;
                    $dir = "L";
                }
            }

            // If we are currently moving left
            elseif ($dir === "L") {
                // If the value below us is set, keep moving left
                if (isset($grid[$pos[1] + 1][$pos[0]])) {
                    $pos[0]--;
                }

                // Otherwise move down and update direction to down
                else {
                    $pos[1]++;
                    $dir = "D";
                }
            }

            // If we are currently moving down
            elseif ($dir === "D") {
                // If the value to the right is set, keep moving down
                if (isset($grid[$pos[1]][$pos[0] + 1])) {
                    $pos[1]++;
                }

                // Otherwise move right and update direction to right
                else {
                    $pos[0]++;
                    $dir = "R";
                }
            }
        }



        // Calculate the manhattan distance from our current position and return it
        return abs($pos[0]) + abs($pos[1]);
    }


    /**
     * @param $grid array The grid with all the values
     * @param $pos array Our current position ([<x-coordinate>, <y-coordinate>])
     * @return int Return the sum of all the neighbouring values
     */
    function getNeighbourSum($grid, $pos) {
        $sum = 0; // Initiate the total sum



        // Loop from the row above to the row below
        for ($y = $pos[1] - 1; $y <= $pos[1] + 1; $y++) {
            // Loop through the column to the left to the column to the right
            for ($x = $pos[0] - 1; $x <= $pos[0] + 1; $x++) {
                // If the x- and y-values point at our current position, skip to next
                if ($y === $pos[1] && $x === $pos[0]) {
                    continue;
                }



                // If the value of the neighbour is set, add it to the total. Otherwise, add 0
                $sum += $grid[$y][$x] ?? 0;
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
