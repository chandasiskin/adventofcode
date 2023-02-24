<?php
    /**
     * https://adventofcode.com/2015/day/7
     *
     *
     *
     * This year, Santa brought little Bobby Tables a set of wires and bitwise logic gates! Unfortunately,
     * little Bobby is a little under the recommended age range, and he needs help assembling the circuit.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("07.txt")) { // If file is missing, terminate
        die("Missing file 07.txt");
    } else {
        $input = file("07.txt"); // Save file as an array
    }
    
    
    
    /**
     * In part 1 we need to find out what value "a" gets after all is done.
     * We will keep looping through the input and remove rows as we go along.
     * If there are unknown variables on the row, we keep it.
     * If all variables are known, we calculate and store the result in another array
     * It's important to note that the highest allowed number is a 16-bit number (65535 in decimal, 1111 1111 1111 1111 in binary).
     * That means we need to keep our eyes open during "NOT" and "LSHIFT" operations because PHP, as default, works with 32-bit numbers.
     * This means that "NOT 10 (2 in decimal)" doesn't result in "01 (1 in binary)" as excepted, but instead
     * "1111 1111 1111 1111 1111 1111 1111 1101 (4294967293 in decimal)". This is because "10 (2 in decimal)" is treated as
     * "0000 0000 0000 0000 0000 0000 0000 0010". PHP doesn't display the leading zeroes when working with binary.
     * We control this by applying an AND with 16 ones after the first operation. This means that
     * NOT "0000 0000 0000 0000 0000 0000 0000 0010" results in "1111 1111 1111 1111 1111 1111 1111 1101" and when we do
     * "1111 1111 1111 1111 1111 1111 1111 1101 AND 1111 1111 1111 1111" (which really is 0000 0000 0000 0000 1111 1111 1111 1111)
     * we get 0000 0000 0000 0000 1111 1111 1111 1101, or 1111 1111 1111 1101.
     *
     *
     *
     * ** SPOILER **
     * For part 2, we need to get the result from part 1, store it in memory place "b" and reset all the other values.
     * We also need to skip the line that says "x -> b" in part two, otherwise it will overwrite the starting value of "b"
     */
    function solve($input, $part2 = false) {
        $memory = []; // Here we store all the results
        // If we are doing part 2, we need to change the starting value of wire B with the result from part 1
        if ($part2) {
            $memory["b"] = $part2;
        }
        $carrySize = 65535; // Highest allowed number
        $originalInput = $input; // We need to store the original input for usage in part 2
        
        
        
        // Keep looping through input until it's empty
        do {
            $reducedInput = []; // Initiate the reduced input to where we copy all the rows we wish to keep from the old input
            
            
            
            // Loop through all the data in the input-array
            foreach ($input as $row) {
                $row = trim($row); // Remove unwanted space-characters from the beginning and the end
                $op = explode(" ", $row); // Create an array by separating the current row at every space-character
                
                
                
                // If it's a SET
                if ($op[1] === "->") {
                    // If we run part 2, we need to skip the line that says "x -> b"
                    if ($part2 && $op[2] === "b") {
                        continue;
                    }
                    
                    if (is_numeric($op[0]) || isset($memory[$op[0]])) { // If the first part is a number or stored in memory
                        $a = is_numeric($op[0]) ? intval($op[0]) : $memory[$op[0]];
                        $memory[$op[2]] = $a; // Store value in memory
                        
                        continue;
                    }
                }
    
                // If it's an AND
                if ($op[1] === "AND") {
                    if ((is_numeric($op[0]) || isset($memory[$op[0]])) // If the first part is a number or stored in memory
                        && (is_numeric($op[2]) || isset($memory[$op[2]]))) { // If the second part is a number or stored in memory
                        $a = is_numeric($op[0]) ? intval($op[0]) : $memory[$op[0]];
                        $b = is_numeric($op[2]) ? intval($op[2]) : $memory[$op[2]];
                        $memory[$op[4]] = $a & $b; // Do a bitwise AND and store in memory
                        
                        continue;
                    }
                }
    
                // If it's an OR
                if ($op[1] === "OR") {
                    if ((is_numeric($op[0]) || isset($memory[$op[0]])) // If the first part is a number or stored in memory
                        && (is_numeric($op[2]) || isset($memory[$op[2]]))) { // If the second part is a number or stored in memory
                        $a = is_numeric($op[0]) ? intval($op[0]) : $memory[$op[0]];
                        $b = is_numeric($op[2]) ? intval($op[2]) : $memory[$op[2]];
                        $memory[$op[4]] = $a | $b; // Do a bitwise OR and store in memory
                        
                        continue;
                    }
                }
    
                // If it's a LSHIFT
                if ($op[1] === "LSHIFT") {
                    if ((is_numeric($op[0]) || isset($memory[$op[0]])) // If the first part is a number or stored in memory
                        && (is_numeric($op[2]) || isset($memory[$op[2]]))) { // If the second part is a number or stored in memory
                        $a = is_numeric($op[0]) ? intval($op[0]) : $memory[$op[0]];
                        $b = is_numeric($op[2]) ? intval($op[2]) : $memory[$op[2]];
                        $memory[$op[4]] = ($a << $b) & $carrySize; // Do a bitwise LEFT SHIFT and store in memory
                        
                        continue;
                    }
                }
                
                // If it's a RSHIFT
                if ($op[1] === "RSHIFT") {
                    if ((is_numeric($op[0]) || isset($memory[$op[0]])) // If the first part is a number or stored in memory
                        && (is_numeric($op[2]) || isset($memory[$op[2]]))) { // If the second part is a number or stored in memory
                        $a = is_numeric($op[0]) ? intval($op[0]) : $memory[$op[0]];
                        $b = is_numeric($op[2]) ? intval($op[2]) : $memory[$op[2]];
                        $memory[$op[4]] = $a >> $b;// Do a bitwise RIGHT SHIFT and store in memory
                        
                        continue;
                    }
                }
    
                // If it's a NOT
                if ($op[0] === "NOT") {
                    if (is_numeric($op[1]) || isset($memory[$op[1]])) { // If it is a number or stored in memory
                        $a = is_numeric($op[1]) ? intval($op[1]) : $memory[$op[1]];
                        $memory[$op[3]] = (~$a) & $carrySize;// Do a bitwise NOT and store in memory
                        
                        continue;
                    }
                }
                
                
                
                // If we reach this point, we haven't done any operation, meaning there are unknown variables in the current row.
                // We copy current row to the reduced input
                $reducedInput[] = $row;
            }
            
            
            
            $input = $reducedInput; // Overwrite old input with the new, reduced one
        } while ($input != []);
        
        
        
        
        // If we are running part 1, we call the function itself with the original input and the result from part 1
        if (!$part2) {
            $res = solve($originalInput, $memory["a"]);
        }
        
        // If we are running part 2, we just return the result
        else {
            return $memory["a"];
        }
        
        
        
        // When we reach this point, we have the result from both part 1 and part 2
        return [$memory["a"], $res];
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    $res = solve($input);
    echo "Part 1: " . $res[0] . " and" . PHP_EOL;
    
    
    
    // Solve part 2
    echo "Part 2: " . $res[1] . " (solved in " . (microtime(true) - $start) . " seconds)";
