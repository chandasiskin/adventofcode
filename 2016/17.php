<?php
    /**
     * https://adventofcode.com/2016/day/17
     *
     *
     *
     * You're trying to access a secure vault protected by a 4x4 grid of small rooms connected by doors.
     * You start in the top-left room, and you can access the vault once you reach the bottom-right room.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("17.txt")) { // If file is missing, terminate
        die("Missing file 17.txt");
    } else {
        $input = file_get_contents("17.txt"); // Save file as a string
    }
    
    
    
    /**
     * Nothing fancy or special about this solution.
     * We start at position [0, 0] and our goal is at position [3, 3].
     * Before every move, we hash our unique passcode together with our movement up to that point.
     * The first four characters in our hash represent the doors in this order: UP, DOWN, LEFT, RIGHT.
     * If the character in the hash is in the range of b-f, the door in that direction is open. Otherwise, it's closed.
     * Ex. a hash of "5fae" means that UP (5) is closed, DOWN (f) is open, LEFT (a) is closed and RIGHT (e) is open.
     * We are looking for the shortest path from start to goal.
     *
     *
     *
     * ** SPOILER **
     * For part 2, we are looking for the longest path from start to goal. To find that path, we just keep running our
     * BFS-loop until we have no more options to follow. And for every successful path, we store the highest step count.
     */
    function solve($input) {
        $passcode = trim($input); // Removes unwanted characters from our passcode
        $query = [[0, 0, "", 0]]; // Our starting location, with no path-history and zero steps
        $height = 4; // Height of the map
        $width = 4; // Width of the map
        $result = []; // Holds the end result, index 0 for path in part 1 and index 1 for step-count in part 2
        
        
        
        // Keep looping as long as there are possible moves to make
        do {
            $tmp = []; // Store the moves we are going to make in the next round
            
            
            
            // Loop through all current moves
            foreach ($query as $q) {
                // Have we reached our goal?
                if ($q[0] === $width - 1 && $q[1] === $height - 1) {
                    // Is this the first time?
                    if ($result == []) {
                        $result[] = $q[2]; // Store current path
                    }
                    
                    // If we have reached our goal before, store the current step-count into the second slot
                    else {
                        $result[1] = $q[3];
                    }
                    
                    
                    
                    continue;
                }
                
                
                
                $hash = md5($passcode . $q[2]); // Generate our hash with our passcode and current path
                
                
                
                // Can we move up?
                if ($q[1] - 1 >= 0 && isOpen($hash[0])) {
                    // Insert a move up in the array of upcoming moves
                    $tmp[] = [$q[0], $q[1] - 1, $q[2] . "U", $q[3] + 1]; // X-coordinate, y-coordinate, current path, step-count
                }
                
                // Can we move down?
                if ($q[1] + 1 < $height && isOpen($hash[1])) {
                    // Insert a move down in the array of upcoming moves
                    $tmp[] = [$q[0], $q[1] + 1, $q[2] . "D", $q[3] + 1]; // X-coordinate, y-coordinate, current path, step-count
                }
                
                // Can we move left?
                if ($q[0] - 1 >= 0 && isOpen($hash[2])) {
                    // Insert a move left in the array of upcoming moves
                    $tmp[] = [$q[0] - 1, $q[1], $q[2] . "L", $q[3] + 1]; // X-coordinate, y-coordinate, current path, step-count
                }
    
                // Can we move right?
                if ($q[0] + 1 < $width && isOpen($hash[3])) {
                    // Insert a move right in the array of upcoming moves
                    $tmp[] = [$q[0] + 1, $q[1], $q[2] . "R", $q[3] + 1]; // X-coordinate, y-coordinate, current path, step-count
                }
            }
            
            
            
            $query = $tmp; // Overwrite current moves with the new ones
        } while ($query != []);
        
        
        
        return $result;
    }
    
    
    /**
     * @param $char string A hexadecimal character to check
     * @return bool Returns true if $char is in range [b-f], otherwise false
     */
    function isOpen($char) {
        if (is_numeric($char) || $char === "a") {
            return false;
        }
        
        
        
        return true;
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    $res = solve($input);
    echo "Part 1: " . $res[0] . " and" . PHP_EOL;
    
    
    
    // Solve part 2
    echo "Part 2: " . $res[1] . " (solved in " . (microtime(true) - $start) . " seconds)";
