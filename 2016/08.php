<?php
    /**
     * https://adventofcode.com/2016/day/8
     *
     *
     *
     * You come across a door implementing what you can only assume is an implementation of two-factor authentication after a long game of requirements telephone.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("08.txt")) { // If file is missing, terminate
        die("Missing file 08.txt");
    } else {
        $input = file("08.txt"); // Save file as an array
    }
    
    
    
    /**
     * We have a 50x6 sized display, with all lights off. For each instruction we read from our input-file we either:
     * - rect AxB
     *      turn all lights on in a rectangle from top left corner down B amount of rows and right A amount of columns
     * - rotate row y=A by B
     *      move all lights at row A B-steps to the right. If a move results out of bounds, we jump back all the way to the left
     * - rotate column x=A by B
     *      move all lights at column A B-steps down. If a move results out of bounds, we jump back to the top
     * Once all instructions have been done, we count the amount of lights that are on.
     *
     *
     *
     * ** SPOILER **
     * For part 2, we just display the message all the lights form.
     */
    function solve($input) {
        $grid = []; // Holds all the lights
        $width = 50; // Width of the grid
        $height = 6; // Height of the grid
        for ($y = 0; $y < $height; $y++) { // Loop through each row in the grid
            for ($x = 0; $x < $width; $x++) { // Loop through each column on current row
                $grid[$y][$x] = 0; // Set light to 0 (off)
            }
        }
        $instructions = array_map("trim", $input); // Remove unwanted characters from all the input-rows
        
        
        
        // Loop through every instruction
        foreach ($instructions as $instruction) {
            $inst = explode( " ", $instruction); // Turn instruction-string into an array by splitting at every space-character
            
            
            
            // What does the second element of the instruction-array say?
            switch ($inst[1]) {
                case "row": // If it says row, it means we are moving lights to the right
                    $y = intval(substr($inst[2], 2)); // Get row number
                    $steps = intval($inst[4]); // Get step-amount
                    $newRow = array_fill(0, $width, 0); // Create a new, lights off, temporary row
                    
                    // Loop through each light on that row
                    for ($x = 0; $x < $width; $x++) {
                        if ($grid[$y][$x] === 1) { // If light is on
                            // Move the light <current pos> + <step-count> places to the right.
                            // Modulo the result to keep it in bounds.
                            // Store the updated light in the temporary row
                            $newRow[($x + $steps) % $width] = 1;
                        }
                    }
                    
                    $grid[$y] = $newRow; // Replace the real row with the updated, temporary row
                    break;
                    
                case "column": // If it says column, it means we are moving lights down
                    $x = intval(substr($inst[2], 2)); // Get column number
                    $steps = intval($inst[4]); // Get step-amount
                    $newColumn = array_fill(0, $height, 0); // Create a new, lights off, temporary column
                    
                    // Loop through each light on that column
                    for ($y = 0; $y < $height; $y++) {
                        if ($grid[$y][$x] === 1) { // If light is on
                            // Move the light <current pos> + <step-count> places down.
                            // Modulo the result to keep it in bounds.
                            // Store the updated light in the temporary column
                            $newColumn[($y + $steps) % $height] = 1;
                        }
                    }
                    
                    // Replace the real column with the updated, temporary column
                    foreach ($newColumn as $y => $value) {
                        $grid[$y][$x] = $value;
                    }
                    break;
                    
                default: // If it says neither "row" nor "column", it says "rect, which means turn lights in a rectangle
                        // down and right from the top-left corner
                    preg_match_all("/\d+/", $inst[1], $matches); // Get the width and height of the rectangle
                    $maxX = intval($matches[0][0]); // Force the width into an integer
                    $maxY = intval($matches[0][1]); // Force the height into an integer
                    
                    // Loop from first row down <height> amount of rows
                    for ($y = 0; $y < $maxY; $y++) {
                        // Loop from first column <width> steps to the right
                        for ($x = 0; $x < $maxX; $x++) {
                            $grid[$y][$x] = 1; // Turn the lights on
                        }
                    }
            }
        }
        
        
        
        $sum = 0; // Holds the total light-is-on-count
        
        // Loop through each row
        foreach ($grid as $row) {
            // Count all the lights that are on, on that current row, by counting all the keys that returns the value 1
            $sum += count(array_keys($row, 1, true));
        }
        
        
        
        $gridAsString = printGrid($grid); // Return the grid as a string
        
        
        
        return [$sum, $gridAsString];
    }
    
    
    
    function printGrid($grid) {
        $width = count($grid[0]); // Get width of the grid
        $height = count($grid); // Get height of the grid
        $res = PHP_EOL; // The resulting string, starting with a newline-character
        
        
        
        // Loop through each row
        for ($y = 0; $y < $height; $y++) {
            // Loop through each column
            for ($x = 0; $x < $width; $x++) {
                // If the light is on, store a "#" in the resulting string. Otherwise, store a non-breakable space-character
                $res .= $grid[$y][$x] === 1 ? "#" : " ";
            }
            
            $res .= PHP_EOL; // Add a newline-character
        }
        
        
        
        return $res;
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    $res = solve($input);
    echo "Part 1: " . $res[0] . " (solved in " . (microtime(true) - $start) . " seconds)" . PHP_EOL;
    
    
    
    // Solve part 2
    echo "Part 2: " . $res[1];
