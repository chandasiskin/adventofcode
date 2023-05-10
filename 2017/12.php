<?php
    /**
     * https://adventofcode.com/2017/day/12
     *
     *
     *
     * Walking along the memory banks of the stream, you find a small village that is experiencing a little confusion:
     * some programs can't communicate with each other.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("12.txt")) { // If file is missing, terminate
        die("Missing file 12.txt");
    } else {
        $input = file("12.txt"); // Save file as an array
    }
    
    
    
    /**
     * We have a bunch of numbers that are connected directly or indirectly to other numbers in the same group.
     * Our task is to find the group that has the number "0" in it.
     * For this, we are going to start looking what numbers are connect to "0", store these and look what numbers are
     * connected to the recently stored ones. Once we run out of new numbers, we have our group.
     *
     *
     *
     * ** SPOILER **
     * For part 2, we don't exit when finishing group "0". We jump to the next ungrouped number.
     * To keep track of what numbers haven't been grouped yet, we remove the grouped ones from our original list.
     */
    function solve($input) {
        $sortedGroups = []; // Holds the grouped numbers
        $unsortedGroups = []; // Holds the ungrouped numbers
        
        
        
        // Loop through each row in our input
        foreach ($input as $row) {
            $row = trim($row); // Remove unwanted characters
            $strpos = strpos($row, "> ") + 2; // Find where the connected numbers start at
            $substr = substr($row, $strpos); // Remove the beginning of the row
            $unsortedGroups[] = array_map("intval", explode(", ", $substr)); // Convert connected numbers into an array
        }
        
        
        
        // Keep looping as long as we have ungrouped numbers
        while ($unsortedGroups != []) {
            reset($unsortedGroups); // Move to the first number in our list
            $id = key($unsortedGroups); // Get the current number
            $sortedGroups[$id] = array_flip($unsortedGroups[$id]); // Store the connected numbers
            $query = $unsortedGroups[$id]; // Insert the connected numbers into the query-list
            unset($unsortedGroups[$id]); // Remove current number from our list


            
            // Keep looping as long as we have numbers in our query
            do {
                $tmp = []; // Holds the numbers for the next round


                
                // Loop through every number in the query
                foreach ($query as $q) {
                    // If the number is found in our original list, the number has not been grouped yet
                    if (isset($unsortedGroups[$q])) {
                        // Loop through every connected number
                        foreach ($unsortedGroups[$q] as $n) {
                            $sortedGroups[$id][$n] = 1; // Store the connected number in the current group
                            $tmp[] = $n; // Insert the connected number to our upcoming query-list
                        }

                        unset($unsortedGroups[$q]); // Remove current number from our original list
                    }
                }



                $query = $tmp; // Overwrite current query with the upcoming
            } while ($query != []);
        }



        // For part 1, return the amount of numbers in group "0".
        // For part 2, return the amount of groups.
        return [count($sortedGroups[0]), count($sortedGroups)];
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    $res = solve($input);
    echo "Part 1: " . $res[0] . " and" . PHP_EOL;
    
    
    
    // Solve part 2
    echo "Part 2: " . $res[1] . " (solved in " . (microtime(true) - $start) . " seconds)";
