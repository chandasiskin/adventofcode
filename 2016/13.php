<?php
    /**
     * https://adventofcode.com/2016/day/13
     *
     *
     *
     * You arrive at the first floor of this new building to discover a much less welcoming environment than the shiny atrium of the last one.
     * Instead, you are in a maze of twisty little cubicles, all alike.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("13.txt")) { // If file is missing, terminate
        die("Missing file 13.txt");
    } else {
        $input = file_get_contents("13.txt"); // Save file as a string
    }
    
    
    
    /**
     * Not a very difficult problem. We start by defining a function, isWalkable(), that returns true if you step on that coordinate
     * and false if not. With that, we start at pos [1, 1] and do a BFS (breath-first-search). This search means
     * that we keep looking for our goal by trying all paths and the second we find it, we stop.
     * We need to keep track of all the places we have been to, where we currently are and how many steps we've taken so far.
     * When doing a BFS, we start at a given point and insert that point into our query-array. Then we loop through our query-array
     * and see what directions we can go from every entry. If a move is possible (due to not hitting a wall, and it's a spot
     * we've not visited before) we insert this into our next-round-query.
     *
     *
     *
     * ** SPOILER **
     * We need to keep track of every location we visit when the step-counter is less or equal to 50.
     */
    function solve($input) {
        $favouriteNumber = intval($input); // Our unique number to generate the map
        $query = [[1, 1]]; // Set our starting position as the first and, so far, only entry
        $visited = [1 => [1 => 1]]; // Set starting position as "already visited"
        $goal = [31, 39]; // What coordinates we are aiming for
        $steps = 0; // How many steps we've taken so far
        $locationCount = 0; // How many unique places we've been to with a step-counter of less or equal to 50
        
        
        
        // Keep looping our query until we reach our goal
        do {
            $tmp = []; // Resets the next-round-query-array
            
            
            
            // Loop through each position in the query
            foreach ($query as $q) {
                // Have we reached our goal?
                if ($q == $goal) {
                    break 2; // Exit all loops
                }
                
    
    
                // Can we move up?
                if (!isset($visited[$q[1] - 1][$q[0]]) // Have we been here before?
                    && isWalkable($favouriteNumber, $q[0], $q[1] - 1)) { // Can we walk there?
                    $tmp[] = [$q[0], $q[1] - 1]; // Store new position
                    $visited[$q[1] - 1][$q[0]] = 1; // Set new position as visited
                    
                    // Are we still under 50 steps?
                    if ($steps <= 50) {
                        $locationCount++; // Increase unique location counter
                    }
                }
                
                
                
                // Can we move down?
                if (!isset($visited[$q[1] + 1][$q[0]]) // Have we been here before?
                    && isWalkable($favouriteNumber, $q[0], $q[1] + 1)) { // Can we walk there?
                    $tmp[] = [$q[0], $q[1] + 1]; // Store new position
                    $visited[$q[1] + 1][$q[0]] = 1; // Set new position as visited
    
                    // Are we still under 50 steps?
                    if ($steps <= 50) {
                        $locationCount++; // Increase unique location counter
                    }
                }
    
    
    
                // Can we move left?
                if (!isset($visited[$q[1]][$q[0] - 1]) // Have we been here before?
                    && isWalkable($favouriteNumber, $q[0] - 1, $q[1])) { // Can we walk there?
                    $tmp[] = [$q[0] - 1, $q[1]]; // Store new position
                    $visited[$q[1]][$q[0] - 1] = 1; // Set new position as visited
    
                    // Are we still under 50 steps?
                    if ($steps <= 50) {
                        $locationCount++; // Increase unique location counter
                    }
                }
    
    
    
                // Can we move right?
                if (!isset($visited[$q[1]][$q[0] + 1]) // Have we been here before?
                    && isWalkable($favouriteNumber, $q[0] + 1, $q[1])) { // Can we walk there?
                    $tmp[] = [$q[0] + 1, $q[1]]; // Store new position
                    $visited[$q[1]][$q[0] + 1] = 1; // Set new position as visited
    
                    // Are we still under 50 steps?
                    if ($steps <= 50) {
                        $locationCount++; // Increase unique location counter
                    }
                }
            }
            
            
            
            $steps++; // Increase step-counter
            $query = $tmp; // Overwrite current positions with the new ones
        } while ($query != []);
    
    
    
        return [$steps, $locationCount];
    }
    
    
    /**
     * @param $favouriteNumber int Our unique map-making-integer (received from the input-file)
     * @param $x int X-coordinate
     * @param $y int Y-coordinate
     * @return bool Return true if position is not a wall, otherwise false
     */
    function isWalkable($favouriteNumber, $x, $y) {
        // If we are out of bounds
        if ($y < 0 || $x < 0) {
            return false;
        }
        
        
        
        // Generating our tile
        $result = ($x * $x) + (3 * $x) + (2 * $x * $y) + ($y) + ($y * $y); // Do some adding and multiplication
        $result += $favouriteNumber; // Add our unique integer
        $binary = decbin($result); // Convert it into binary
        $countBits = substr_count($binary, "1"); // Count the ones in our binary-string
    
        
        
        // If the amount of ones is even, return true
        if ($countBits % 2 === 0) {
            return true;
        }
        
        
        
        return false;
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    $res = solve($input);
    echo "Part 1: " . $res[0] . " and" . PHP_EOL;
    
    
    
    // Solve part 2
    echo "Part 2: " . $res[1] . " (solved in " . (microtime(true) - $start) . " seconds)";
