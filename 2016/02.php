<?php
    /**
     * https://adventofcode.com/2016/day/2
     *
     *
     *
     * You arrive at Easter Bunny Headquarters under cover of darkness.
     * However, you left in such a rush that you forgot to use the bathroom!
     * Fancy office buildings like this one usually have keypad locks on their bathrooms, so you search the front desk for the code.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("02.txt")) { // If file is missing, terminate
        die("Missing file 02.txt");
    } else {
        $input = file("02.txt"); // Save file as an array
    }
    
    
    
    /**
     * This task is easy as pie: we have a 3x3 keypad (which we store in a 2D-array), we start in the middle (x = 1, y = 1),
     * and we move horizontal and vertical according to our input.
     * If we are about to move out of bounds, we just stay put. Once the first row in our input is finished, we have our first digit.
     * When continuing on the next input-row, we just keep moving from the last position on the keypad.
     * Once all input-rows are done, we have our final code.
     * NOTE: it doesn't matter how we set the directions, if right is positive or negative.
     * Here, we do right as positive x-coordinate and down as positive y-coordinate.
     *
     *
     *
     * ** SPOILER **
     * Same as part 1, except the keypad looks different.
     */
    function solve($input, $part2 = false) {
        // If we are doing part 1
        if (!$part2) {
            $keypad = [
                [1,2,3], // First row, pos 0, 1 and 2
                [4,5,6], // Second row, pos 0, 1 and 2
                [7,8,9] // Third row, pos 0, 1 and 2
            ];
            $pos = [1,1]; // Starting pos
        }
        
        // If we are doing part 2
        else {
            $keypad = [
                [2 => 1],                           //     1            first row, pos 2
                [1 => 2, 2 => 3, 3 => 4],           //   2 3 4          second row, pos 1, 2 and 3
                [5,6,7,8,9],                        // 5 6 7 8 9        third row, pos 0, 1, 2, 3 and 4
                [1 => "A", 2 => "B", 3 => "C"],     //   A B C          fourth row, pos 1, 2 and 3
                [2 => "D"]                          //     D            fifth row, pos 2
            ];
            $pos = [2,1]; // Starting pos
        }
        $code = ""; // Holds our final code
        $instructions = array_map("trim", $input); // Trim the input from unwanted characters
        
        
        
        // Loop through each row in the input-file
        foreach ($instructions as $row) {
            $len = strlen($row); // Get row length
            
            // Loop through each character in current row
            for ($i = 0; $i < $len; $i++) {
                $dx = $dy = 0; // Reset horizontal (dx) and vertical (dy) movement
                
                // In what direction to move
                switch ($row[$i]) {
                    // Up?
                    case "U":
                        $dy = -1; // Set vertical movement to negative
                        break;
                    
                    // Down?
                    case "D":
                        $dy = 1; // Set vertical movement to positive
                        break;
                        
                    // Left?
                    case "L":
                        $dx = -1; // Set horizontal movement to negative
                        break;
                        
                    // Right?
                    case "R":
                        $dx = 1; // Set horizontal movement to positive
                        break;
                        
                    // If we run into an illegal character
                    default:
                        die("Invalid direction: $row[$i]");
                }
                
                
                
                // If we are moving to a position in bounds
                if (isset($keypad[$pos[1] + $dy][$pos[0] + $dx])) {
                    $pos[0] += $dx; // Update horizontal position
                    $pos[1] += $dy; // Update vertical position
                }
            }
            
            
            
            // Movement stopped, add current number to final code
            $code .= $keypad[$pos[1]][$pos[0]];
        }
        
        
        
        return $code;
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    echo "Part 1: " . solve($input) . " (solved in " . (microtime(true) - $start) . " seconds)" . PHP_EOL;
    
    
    
    // Solve part 2
    $part2 = true; // Tells our function to use parts needed to solve part 2
    $start = microtime(true);
    echo "Part 2: " . solve($input, $part2) . " (solved in " . (microtime(true) - $start) . " seconds)";
