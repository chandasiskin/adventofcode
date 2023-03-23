<?php
    /**
     * https://adventofcode.com/2016/day/6
     *
     *
     *
     * Something is jamming your communications with Santa.
     * Fortunately, your signal is only partially jammed,
     * and protocol in situations like this is to switch to a simple repetition code to get the message through.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("06.txt")) { // If file is missing, terminate
        die("Missing file 06.txt");
    } else {
        $input = file("06.txt"); // Save file as an array
    }
    
    
    
    /**
     * For this task, we loop through each row, store each character in corresponding column-array and keep character count.
     * For part 1, we get the character with the highest count.
     *
     *
     *
     * ** SPOILER **
     * For part 2, we get the character with the lowest count.
     */
    function solve($input) {
        $columns = []; // Holds the character-count for each column
        $result = ["", ""]; // Holds results for part 1 and 2
        $input = array_map("trim", $input); // Removes unwanted characters from every row
        
        
        
        foreach ($input as $row) { // Loop through each row
            $len = strlen($row); // Get current row length
            for ($c = 0; $c < $len; $c++) { // Loop through each character in current row
                $columns[$c][$row[$c]] = $columns[$c][$row[$c]] ?? 0; // If current character-count is not set, set it to 0
                $columns[$c][$row[$c]]++; // Increase the character-count
            }
        }
        
        
        
        foreach ($columns as $arr) { // Loop through each column
            asort($arr); // Sort column by value, ascending
            
            reset($arr); // Set internal pointer to the first element
            $result[0] .= key($arr); // Get the key (the character) for that element and store it
            
            end($arr); // Set internal pointer to the last element
            $result[1] .= key($arr); // Get the key (the character) for that element and store it
        }
        
        
        
        return $result;
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    $res = solve($input);
    echo "Part 1: " . $res[0] . " and" . PHP_EOL;
    
    
    
    // Solve part 2
    echo "Part 2: " . $res[1] . " (solved in " . (microtime(true) - $start) . " seconds)";
