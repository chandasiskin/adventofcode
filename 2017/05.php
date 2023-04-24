<?php
    /**
     * https://adventofcode.com/2017/day/5
     *
     *
     *
     * An urgent interrupt arrives from the CPU: it's trapped in a maze of jump instructions,
     * and it would like assistance from any programs with spare cycles to help find the exit.
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
     * Today's puzzle doesn't have the most efficient code, but it's short and solves both parts the same way.
     * We have a list of numbers. We start on the first row and move the amount of steps the row says.
     * We increment the value on the row we currently jumped from and keep doing this until we jump out of the list.
     *
     *
     *
     * ** SPOILER **
     * Part 2 differs in the way that we don't increment the row value if the value is larger than 2. We instead decrement it.
     */
    function solve($input, $part2 = false) {
        $input = array_map("intval", $input); // Remove unwanted characters from our input
        $pos = 0; // Set our starting row
        $steps = 0; // Holds the amount of steps we take before jumping out of the list



        // Initiate the looping
        do {
            $newPos = $pos + $input[$pos]; // Store the new position after we execute our jump

            // If we are doint part 1, increment the row-value
            if (!$part2) {
                $input[$pos]++;
            }

            // If we are doing part 2, increment the row value if the current row value is below 3. Otherwise, decrement it
            else {
                $input[$pos] += $input[$pos] >= 3 ? -1 : 1;
            }

            $pos = $newPos; // Update our new position



            $steps++; // Increment step-count
        } while (isset($input[$pos])); // Keep looping as long as we are within the bounds of the list



        return $steps;
    }



    // Solve part 1
    $start = microtime(true);
    echo "Part 1: " . solve($input) . " (solved in " . (microtime(true) - $start) . " seconds)" . PHP_EOL;



    // Solve part 2
    $part2 = true; // Tells our function to use parts needed to solve part 2
    $start = microtime(true);
    echo "Part 2: " . solve($input, $part2) . " (solved in " . (microtime(true) - $start) . " seconds)";
