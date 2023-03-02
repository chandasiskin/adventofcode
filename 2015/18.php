<?php
    /**
     * https://adventofcode.com/2015/day/18
     *
     *
     *
     * After the million lights incident (puzzle 6), the fire code has gotten stricter.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("18.txt")) { // If file is missing, terminate
        die("Missing file 18.txt");
    } else {
        $input = file("18.txt"); // Save file as an array
    }
    
    
    
    /**
     * Here, we are playing "Conway's Game of Life". The rules are "the next state of the current position is dependent
     * of the current state of its 8 neighbours (vertical, horizontal, diagonal).
     * Even though the edges have fewer neighbours (you could also say that some neighbours are always off), the rules below still apply:
     * - If a light is on and has 2 or 3 neighbours that also are on, the current light stays on. Otherwise, it turns off.
     * - If a light is off and has exactly 3 neighbours that are on, the current light turns on. Otherwise, is stays off.
     * All lights turn/stays on/off at the same time.".
     *
     * To play the game we:
     * 1. Store the current lights states in an array (saving them as $array[<y-coordinate>][<x-coordinate>] makes it faster
     * when checking state).
     * 2. Create a new, empty lights-array. This will be our next-state-array
     * 3. Loop through each coordinate in the current-state-array.
     * 4. Count it's lit neighbours (there's no need to keep counting after 4 lit neighbours, so we just break out of the loop).
     * 5. We compare the current state of the light and its lit neighbours and keep/turn it on/off accordingly (since a light
     * will stay on or turn on if is has 3 neighbours, it doesn't matter what state it's currently in. The next state will
     * be "lit").
     * 6. We store the next state in the next-state-array
     * 7. When all positions have been checked, we overwrite our current-state-array with next-state-array.
     * 8. If we haven't played enough rounds, repeat from step 2.
     *
     *
     *
     * ** SPOILER **
     * Only difference between part 1 and part 2 is that in part 2 all the 4 corner-lights will always stay lit.
     */
    function solve($input, $part2 = false) {
        $lights = []; // This will hold the current state of lights
        $steps = 100; // How many rounds we are playing
        $width = strlen(trim($input[0])); // Width of the board (trim removes unwanted newline-characters)
        $height = count($input); // Height of the board
        
        
        
        // Populate the current-state-array
        foreach ($input as $y => $row) { // Loop through each row
            for ($x = 0; $x < $width; $x++) { // Loop through each character in current row
                if ($row[$x] === "#") { // If the light is on, save it in array
                    $lights[$y][$x] = "#";
                }
            }
        }
    
    
        
        // If playing part 2, turn all 4 corner-lights on
        if ($part2) {
            $lights[0][0] = "#"; // Up-left
            $lights[0][$width - 1] = "#"; // Up-right
            $lights[$height - 1][0] = "#"; // Down-left
            $lights[$height - 1][$width - 1] = "#"; // Down-right
        }
    
    
    
        // Keep playing the game
        for ($step = 0; $step < $steps; $step++) {
            $nextLights = []; // Create an empty array holding the next-state of each light
            
            
            
            for ($y = 0; $y < $height; $y++) { // Loop through each row
                for ($x = 0; $x < $width; $x++) { // Loop through each column
                    if ($part2) { // If we are playing part 2, force the 4 corner lights to stay on
                        if (($y === 0 && ($x === 0 || $x === $width - 1))
                        || ($y === $height - 1 && ($x === 0 || $x === $width - 1))) {
                            $nextLights[$y][$x] = "#";
                            
                            continue;
                        }
                    }
                    
                    
                    
                    $litNeighbours = 0; // Holds the amount of neighbours that are lit
                    
                    for ($a = $y - 1; $a <= $y + 1; $a++) { // Check neighbours from 1 column to the left to 1 column to the right
                        for ($b = $x - 1; $b <= $x + 1; $b++) { // Check neighbours from 1 row above to 1 row below
                            if ($a === $y && $b === $x) { // The middle is not a neighbour
                                continue;
                            }
                            
                            if (isset($lights[$a][$b])) { // If a neighbours is on
                                if (++$litNeighbours > 3) { // If more than 3 neighbours are on, stop counting
                                    break 2;
                                }
                            }
                        }
                    }
                    
                    
                    
                    if ($litNeighbours === 3) { // If exactly three neighbours are lit, the next state will always be "on", regardless of its current state
                        $nextLights[$y][$x] = "#";
                    } elseif (isset($lights[$y][$x]) && $litNeighbours === 2) { // If the light is currently lit and it has exactly 2 neighbours, it will stay on
                        $nextLights[$y][$x] = "#";
                    } // Otherwise, the light will turn/stay off
                }
            }
            
            
            
            $lights = $nextLights; // Overwrite current-state-array with next-state-array
        }
    
    
    
        $lightsOn = 0; // Holds data about how many lights are turned on
        
        foreach ($lights as $row) { // Loop through each row
            $lightsOn += count($row); // Count how many lights are lit on that row
        }
        
        
        
        return $lightsOn;
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    echo "Part 1: " . solve($input) . " (solved in " . (microtime(true) - $start) . " seconds)" . PHP_EOL;
    
    
    
    // Solve part 2
    $part2 = true;
    $start = microtime(true);
    echo "Part 2: " . solve($input, $part2) . " (solved in " . (microtime(true) - $start) . " seconds)";
