<?php
    /**
     * https://adventofcode.com/2015/day/6
     *
     *
     *
     * Because your neighbors keep defeating you in the holiday house decorating contest year after year, you've decided to deploy one million lights in a 1000x1000 grid.
     * Furthermore, because you've been especially nice this year, Santa has mailed you instructions on how to display the ideal lighting configuration.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("06.txt")) { // If file is missing, terminate
        die("Missing file 06.txt");
    } else {
        $input = file("06.txt"); // Save file as a string
    }
    
    
    
    /**
     * For part 1 we need to build a map of lights that we are going to either turn on, turn off or toggle.
     * We will build a 2D-array such that $map[<y-coordinate of lamp>][<x-coordinate of lamp>]
     * and for every instruction we will run through each y- and x-coordinate and do something with the lights.
     * If instruction turns on a lamp we store a "1" on every affected coordinate,
     * else we unset that coordinate from the array. This makes it easier to count the amount of lights on.
     *
     *
     *
     * ** SPOILER **
     * Part 2 changes the rules a little. "Turn on" means "increase brightness by 1",
     * "turn off" means "decrease brightness by 1 (to a minimum of zero)" and "toggle" means "increase brightness by 2"
     */
    function solve($input, $part2 = false) {
        $lights = []; // Initiate the light-grid
        $res = 0; // Store the result for part 1 or 2
        
        
        
        // Loop through instructions
        foreach ($input as $instruction) {
            // We see what we need to do (turn on/turn off/toggle) and from where to where. We store the information in $matches
            preg_match_all("/toggle|on|off|\d+/i", $instruction, $matches);
    
    
    
            $xFrom = intval($matches[0][1]); // Starting x
            $yFrom = intval($matches[0][2]); // Starting y
            $xTo = intval($matches[0][3]); // Ending x
            $yTo = intval($matches[0][4]); // Ending y
            
            
            
            // Do we turn on?
            if ($matches[0][0] === "on") {
                for ($y = $yFrom; $y <= $yTo; $y++) { // Loop through every row
                    for ($x = $xFrom; $x <= $xTo; $x++) { // Loop through every column
                        // In part 1 we just turn the lights on and off
                        if (!$part2) {
                            if (!isset($lights[$y][$x])) {
                                $lights[$y][$x] = 1; // Set light to ON
                                $res++; // Increase light count
                            }
                        }
                        
                        // In part 2 we adjust the brightness level with -1, +1 or +2
                        else {
                            // If light is not already turned on, turn it on
                            if (!isset($lights[$y][$x])) {
                                $lights[$y][$x] = 0;
                            }
                            
                            $lights[$y][$x]++; // Increase brightness by one
                            $res++; // Increase total brightness level
                        }
                    }
                }
            }
            
            // Do we turn off?
            elseif ($matches[0][0] === "off") {
                for ($y = $yFrom; $y <= $yTo; $y++) {// Loop through every row
                    for ($x = $xFrom; $x <= $xTo; $x++) {// Loop through every column
                        // In part 1 we just turn the lights on and off
                        if (!$part2) {
                            if (isset($lights[$y][$x])) {
                                unset($lights[$y][$x]); // Set light to OFF
                                $res--; // Decrement light count
                            }
                        }
    
                        // In part 2 we adjust the brightness level with -1, +1 or +2
                        else {
                            // If light is not already turned on, do nothing
                            if (!isset($lights[$y][$x])) {
                                continue;
                            }
                            
                            // If light is on, but decreasing the brightness to zero
                            if ($lights[$y][$x] === 1) {
                                unset($lights[$y][$x]);
                                $res--; // Decrease total brightness level
                                
                                continue;
                            }
                            
                            $lights[$y][$x]--; // Decrease brightness by one
                            $res--; // Decrease total brightness level
                        }
                    }
                }
            }
            
            // Do we toggle?
            elseif ($matches[0][0] === "toggle") {
                for ($y = $yFrom; $y <= $yTo; $y++) {// Loop through every row
                    for ($x = $xFrom; $x <= $xTo; $x++) {// Loop through every column
                        // In part 1 we just turn the lights on and off
                        if (!$part2) {
                            // If light is ON
                            if (isset($lights[$y][$x])) {
                                unset($lights[$y][$x]); // Set light to OFF
                                $res--; // Decrement light count
                            } else {
                                $lights[$y][$x] = 1; // Set light to ON
                                $res++; // Increment light count
                            }
                        }
    
                        // In part 2 we adjust the brightness level with -1, +1 or +2
                        else {
                            // If light is not already turned on, turn it on
                            if (!isset($lights[$y][$x])) {
                                $lights[$y][$x] = 0;
                            }
        
                            $lights[$y][$x] += 2; // Increase brightness by two
                            $res += 2; // Increase total brightness level
                        }
                    }
                }
            }
            
            // We got a bad instruction
            else {
                die("Bad instruction: {$matches[0][0]}");
            }
        }
        
        
        
        return $res;
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    echo "Part 1: " . solve($input) . " (solved in " . (microtime(true) - $start) . " seconds)" . PHP_EOL;
    
    
    
    // Solve part 2
    $part2 = true;
    $start = microtime(true);
    echo "Part 2: " . solve($input, $part2) . " (solved in " . (microtime(true) - $start) . " seconds)";
