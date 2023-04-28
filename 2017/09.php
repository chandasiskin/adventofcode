<?php
    /**
     * https://adventofcode.com/2017/day/9
     *
     *
     *
     * A large stream blocks your path. According to the locals, it's not safe to cross the stream at the moment because it's full of garbage.
     * You look down at the stream; rather than water, you discover that it's a stream of characters.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("09.txt")) { // If file is missing, terminate
        die("Missing file 09.txt");
    } else {
        $input = file_get_contents("09.txt"); // Save file as a string
    }
    
    
    
    /**
     * For this task we have a very long string where we need to find certain "groups".
     * A group is a set of characters (any type of characters) that are surrounded by curly brackets ("{}").
     * A group can contain other groups. The outermost group is worth one point. Any groups directly inside is worth two points,
     * any groups inside these two-point-groups is worth three points, and so forth.
     * We need to calculate how many points all the groups are worth together.
     * This is achieved by looping through every character. Everytime we hit the "{"-character we know we are entering a new group,
     * so we increment the current group-value and each time we hit a "}"-character we decrement the current group-value.
     * Also, when we hit that "}"-character we have exited a group, so we need to add that groups value into the total.
     * NOTE: There is a special character, "!", that cancels the character coming right after it.
     *      "!}" would cancel the curly bracket, meaning the current group will NOT be closed.
     *      "!!" the first "!" cancels the second "!".
     *      To cope with these cancellations, we simply skip the next character whenever we encounter a "!",
     *      regardless of what that next character is.
     *
     *
     *
     * ** SPOILER **
     * For part 2, we need to find all the characters that are inside the tag-characters ("<>"). Those are considered as garbage.
     * Those garbage-characters (exclude the surrounding tag-characters) are counted and added up to a total and that total is our answer for part 2.
     * Same as in part 2, the "!"-character cancels the upcoming character. But, different from part 1, garbage can't be nested.
     * "<<<a>>>" does not mean nested garbage, but instead a garbage containing the characters "<<a" and then som random characters (">>")
     * that just happens to be same characters as the closing tag for a garbage.
     */
    function solve($input) {
        $stream = trim($input); // Remove unwanted characters from the input
        $sumPoints = 0; // Holds the total amount of points for all the groups
        $pointLevel = 0; // Holds the group-level we are currently at
        $sumCharacters = 0; // Holds the amount of garbage-characters we've found
        $len = strlen($stream); // The length of the input


        
        // Loop through every character
        for ($i = 0; $i < $len; $i++) {
            // If we encounter a garbage-starting character
            if ($stream[$i] === "<") {
                // Start a new loop from the next character counting all the characters in current garbage
                for ($j = $i + 1; $j < $len; $j++) {
                    // If we encounter a cancelling-character
                    if ($stream[$j] === "!") {
                        $j++; // Skip the next character
                    }
                    
                    // If we encounter a garbage-closing character
                    elseif ($stream[$j] === ">") {
                        $i = $j; // Update the original character position to continue character check after current garbage

                        continue 2; // Exit garbage-loop
                    }

                    // If we encounter any other character
                    else {
                        $sumCharacters++; // Increment total garbage-character count
                    }
                }
            }

            // If we encounter a group-starting character
            elseif ($stream[$i] === "{") {
                $pointLevel++; // Increment the current group-level
            }
            
            // If we encounter a group-closing character
            elseif ($stream[$i] === "}") {
                $sumPoints += $pointLevel; // Update total group-points
                $pointLevel--; // Decrement current group-level
            }
        }


        
        // Return the total points of all groups and total character count in all garbage's
        return [$sumPoints, $sumCharacters];
    }



    // Solve part 1
    $start = microtime(true);
    $res = solve($input);
    echo "Part 1: " . $res[0] . " and" . PHP_EOL;
    
    
    
    // Solve part 2
    echo "Part 2: " . $res[1] . " (solved in " . (microtime(true) - $start) . " seconds)";
