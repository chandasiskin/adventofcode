<?php
    /**
     * https://adventofcode.com/2016/day/3
     *
     *
     *
     * Now that you can think clearly, you move deeper into the labyrinth of hallways and office furniture that makes up this part of Easter Bunny HQ.
     * This must be a graphic design department; the walls are covered in specifications for triangles.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("03.txt")) { // If file is missing, terminate
        die("Missing file 03.txt");
    } else {
        $input = file("03.txt"); // Save file as an array
    }
    
    
    
    /**
     * Each row in our input contains three numbers: one for each side of a triangle.
     * Our task is to check if it's a valid triangle (the sum of any two sides must be larger than the remaining side).
     * We split each input-row into an array holding the values of the three sides.
     * After that is done, we loop through each row and check if it's a valid triangle. If so, we increment our counter.
     *
     *
     *
     * ** SPOILER **
     * Part two states that it's not every row that holds the values of the three sides, but every column.
     * That means that we loop through the input column wise, not row wise.
     * Starting with the left most column, we check the values of the first three rows.
     * After that we continue on the same column, except we check values on row 4, 5 and 6.
     * We keep checking three rows at a time until we get to the bottom row. Then we do the same thing all over again,
     * except with the next column.
     */
    function solve($input, $part2 = false) {
        $possibleTriangles = 0; // Holds the amount of valid triangles
        $sides = []; // Builds the triangle sides as $arr[<input-row>][<side 1>, <side 2>, <side 3>]
        
        
        
        // Loop through each input-row
        foreach ($input as $row) {
            // 1. Trim the current row from unwanted characters
            // 2. Split the result into an array at every "non-digit-character"
            // 3. Convert all resulting strings to integers
            $sides[] = array_map("intval", preg_split("/[^\d]+/", trim($row)));
        }
        
        
        
        if (!$part2) { // If we are doing part 1
            foreach ($sides as $side) { // Loop through each row
                // If any combination of <side a> + <side b> is equal or smaller than <side c>
                // we have an invalid triangle. Continue to the next one.
                if ($side[0] + $side[1] <= $side[2]
                || $side[0] + $side[2] <= $side[1]
                || $side[1] + $side[2] <= $side[0]) {
                    continue;
                }
                
                // If we reach this point, we have ourselves a valid triangle
                $possibleTriangles++;
            }
        }
        
        // If we are doing part 2
        else {
            $max = count($sides); // Count the amount of rows
            // Loop through each column
            for ($column = 0; $column < 3; $column++) {
                // Loop through every third row
                for ($row = 0; $row < $max; $row += 3) {
                    // If any combination of <side a> + <side b> is equal or smaller than <side c>
                    // we have an invalid triangle. Continue to the next one.
                    if ($sides[$row][$column] + $sides[$row + 1][$column] <= $sides[$row + 2][$column]
                    || $sides[$row][$column] + $sides[$row + 2][$column] <= $sides[$row + 1][$column]
                    || $sides[$row + 1][$column] + $sides[$row + 2][$column] <= $sides[$row][$column]) {
                        continue;
                    }
    
                    // If we reach this point, we have ourselves a valid triangle
                    $possibleTriangles++;
                }
            }
        }
        
        
        
        return $possibleTriangles;
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    echo "Part 1: " . solve($input) . " (solved in " . (microtime(true) - $start) . " seconds)" . PHP_EOL;
    
    
    
    // Solve part 2
    $part2 = true; // Tells our function to use parts needed to solve part 2
    $start = microtime(true);
    echo "Part 2: " . solve($input, $part2) . " (solved in " . (microtime(true) - $start) . " seconds)";
