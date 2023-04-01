<?php
    /**
     * https://adventofcode.com/2016/day/18
     *
     *
     *
     * As you enter this room, you hear a loud click!
     * Some of the tiles in the floor here seem to be pressure plates for traps,
     * and the trap you just triggered has run out of... whatever it tried to do to you.
     * You doubt you'll be so lucky next time.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("18.txt")) { // If file is missing, terminate
        die("Missing file 18.txt");
    } else {
        $input = file_get_contents("18.txt"); // Save file as a string
    }
    
    
    
    /**
     * Another code that doesn't complete in less than a second *sob*.
     * Really, really straight forward code this time:
     * Check the characters in the row above to determine if the certain tile on the next row is safe or a trap.
     * Do this for 40 rows and count the amount of safe tiles.
     *
     *
     *
     * ** SPOILER **
     * For part 2, we do a total of 400.000 rows, still count the safe ones.
     */
    function solve($input, $part2 = false) {
        $currentRow = trim($input); // Removes unwanted characters from the first row
        $rowLen = strlen($currentRow); // Get the width of the grid
        $safeTiles = substr_count($currentRow, "."); // Count safe tiles on the first row
        $rowCounter = !$part2 ? 40 : 400000; // If we are doing part 1, count safe tiles in 40 rows. If part 2, we do 400.000 rows
        
        
        
        // Loop through all rows
        for ($row = 1; $row < $rowCounter; $row++) {
            $previousRow = $currentRow; // Copy the current row to previous row
            $currentRow = ""; // Reset current row
            
            
            
            // Loop through each character in the previous row
            for ($c = 0; $c < $rowLen; $c++) {
                // If top-left tile is out of bounds or a safe tile
                if ($c - 1 < 0 || $previousRow[$c - 1] === ".") {
                    // If top right tile is a trap
                    if (isset($previousRow[$c + 1]) && $previousRow[$c + 1] === "^") {
                        $currentRow .= "^"; // The tile on the current row is a trap
                        
                        continue;
                    }
                }
                
                // If top-left tile is a trap
                if ($c - 1 >= 0 && $previousRow[$c - 1] === "^") {
                    // If top-right tile is safe
                    if (!isset($previousRow[$c + 1]) || $previousRow[$c + 1] === ".") {
                        $currentRow .= "^"; // The tile on the current row is a trap
                        
                        continue;
                    }
                }
                
                
                
                $currentRow .= "."; // If we reach this point, we have a safe tile on our hands. Store it!
                $safeTiles++; // Increment safe tile counter
            }
        }
        
        
        
        return $safeTiles;
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    echo "Part 1: " . solve($input) . " (solved in " . (microtime(true) - $start) . " seconds)" . PHP_EOL;
    
    
    
    // Solve part 2
    $part2 = true; // Tells our function to use parts needed to solve part 2
    $start = microtime(true);
    echo "Part 2: " . solve($input, $part2) . " (solved in " . (microtime(true) - $start) . " seconds)";
