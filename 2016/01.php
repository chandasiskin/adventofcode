<?php
    /**
     * https://adventofcode.com/2016/day/1
     *
     *
     *
     * You're airdropped near Easter Bunny Headquarters in a city somewhere."Near", unfortunately,
     * is as close as you can get - the instructions on the Easter Bunny Recruiting Document the Elves intercepted start here,
     * and nobody had time to work them out further.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("01.txt")) { // If file is missing, terminate
        die("Missing file 01.txt");
    } else {
        $input = file_get_contents("01.txt"); // Save file as a string
    }
    
    
    
    /**
     * We start at pos [0,0] facing NORTH. For every instruction we either turn RIGHT or LEFT and walk n-steps in that direction.
     * There needs to be a variable storing what direction we are facing,
     * and we need to keep track of what the new direction will be after we make a turn (the new direction will depend on where we are currently facing).
     * Once we're out of instructions, calculate the distance by getting the "Manhattan distance" from our end-point to our starting-point.
     *
     *
     * ** SPOILER **
     * We abort the instructions as soon as we hit a point where we have been before. So we need to keep track of where we have been.
     */
    function solve($input, $part2 = false) {
        $instructions = explode(", ", $input); // Convert all instructions into an array
        $pos = [0, 0]; // Starting positions (x = 0, y = 0)
        $dir = "N"; // What direction we are facing
        
        
        
        // Loop through all instructions
        foreach ($instructions as $inst) {
            $turn = $inst[0]; // What direction to turn towards
            $steps = intval(substr($inst, 1)); // How many steps to take
            
            
            
            if ($dir === "N") { // If we are initially facing NORTH
                if ($turn === "L") { // Turn LEFT
                    $dir = "W"; // Face WEST
                } else { // Turn RIGHT
                    $dir = "E"; // Face EAST
                }
            } elseif ($dir === "E") { // If we are initially facing EAST
                if ($turn === "L") { // Turn LEFT
                    $dir = "N"; // Face NORTH
                } else { // Turn RIGHT
                    $dir = "S"; // Face SOUTH
                }
            } elseif ($dir === "S") { // If we are initially facing SOUTH
                if ($turn === "L") { // Turn LEFT
                    $dir = "E"; // Face EAST
                } else { // Turn RIGHT
                    $dir = "W"; // Face WEST
                }
            } else { // If we are initially facing WEST
                if ($turn === "L") { // Turn LEFT
                    $dir = "S"; // Face SOUTH
                } else { // Turn RIGHT
                    $dir = "N"; // Face NORTH
                }
            }
            
            
            
            if ($dir === "N") { // If we are to walk NORTH
                // Move right amount of steps in that direction
                for ($y = $pos[1] - 1; $y >= $pos[1] - $steps; $y--) {
                    // If we are doing part 2, check if we've been at that position before
                    if ($part2 && isset($map[$y][$pos[0]])) {
                        return abs($pos[0]) + abs($y); // Return the Manhattan distance
                    }
                    
                    $map[$y][$pos[0]] = 1; // Update map
                }
                
                $pos[1] = $y + 1; // Update our new position (the +1 is to counter the last $y-- from the for-loop)
            } elseif ($dir === "S") { // If we are to walk SOUTH
                // Move right amount of steps in that direction
                for ($y = $pos[1] + 1; $y <= $pos[1] + $steps; $y++) {
                    // If we are doing part 2, check if we've been at that position before
                    if ($part2 && isset($map[$y][$pos[0]])) {
                        return abs($pos[0]) + abs($y); // Return the Manhattan distance
                    }
    
                    $map[$y][$pos[0]] = 1; // Update map
                }
    
                $pos[1] = $y - 1; // Update our new position (the -1 is to counter the last $y++ from the for-loop)
            } elseif ($dir === "E") { // If we are to walk EAST
                // Move right amount of steps in that direction
                for ($x = $pos[0] + 1; $x <= $pos[0] + $steps; $x++) {
                    // If we are doing part 2, check if we've been at that position before
                    if ($part2 && isset($map[$pos[1]][$x])) {
                        return abs($x) + abs($pos[1]); // Return the Manhattan distance
                    }
    
                    $map[$pos[1]][$x] = 1; // Update map
                }
    
                $pos[0] = $x - 1; // Update our new position (the -1 is to counter the last $x++ from the for-loop)
            } else { // If we are to walk WEST
                // Move right amount of steps in that direction
                for ($x = $pos[0] - 1; $x >= $pos[0] - $steps; $x--) {
                    // If we are doing part 2, check if we've been at that position before
                    if ($part2 && isset($map[$pos[1]][$x])) {
                        return abs($x) + abs($pos[1]); // Return the Manhattan distance
                    }
    
                    $map[$pos[1]][$x] = 1; // Update map
                }
    
                $pos[0] = $x + 1; // Update our new position (the +1 is to counter the last $x-- from the for-loop)
            }
        }
        
        
        
        return abs($pos[0]) + abs($pos[1]);
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    echo "Part 1: " . solve($input) . " (solved in " . (microtime(true) - $start) . " seconds)" . PHP_EOL;
    
    
    
    // Solve part 2
    $part2 = true; // Tells our function to use parts needed to solve part 2
    $start = microtime(true);
    echo "Part 2: " . solve($input, $part2) . " (solved in " . (microtime(true) - $start) . " seconds)";
