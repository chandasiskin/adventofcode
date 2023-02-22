<?php
    /**
     * https://adventofcode.com/2015/day/8
     *
     *
     *
     * This year, Santa brought little Bobby Tables a set of wires and bitwise logic gates! Unfortunately,
     * little Bobby is a little under the recommended age range, and he needs help assembling the circuit.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("08.txt")) { // If file is missing, terminate
        die("Missing file 08.txt");
    } else {
        $input = file("08.txt"); // Save file as a string
    }
    
    
    
    /**
     * In part 1, we take the string in each row and calculate the displayed length and the actual length (the one stored in memory).
     * The actual length is just strlen(<row>). The displayed length is trickier. A "\x27" is really only one character, since
     * "\x" means "hexadecimal notation on the way" and "27" just represents the hexadecimal code. \x27 is just an apostrophe,
     * so just one character.
     * Things to look for is:
     * - \" which is "
     * - \\ which is \
     * - \xNN which is a hexadecimal representation of some character (NOTE: just ONE character)
     * So, whenever we see a \\, \" or \xNN, that counts as just one character on the displayed version of the string
     *
     *
     *
     * ** SPOILER **
     * For part 2, we need to re-encode each string, meaning '\' becomes '\\' and '"' becomes '\"' and count the difference
     * between the re-encoded string and the original string.
     * NOTE: since the quote character in the beginning and end gets encoded to '\"', we need to add new quote symbols
     * to the beginning and end.
     */
    function solve($input) {
        $resultPart1 = 0; // Store the total difference of actual string and displayed string in part 1
        $resultPart2 = 0; // Store the total difference of actual string and re-encoded string in part 2
        
        
        //var_dump(preg_replace(["/\\\\\\\\/", "/\\\\\"/", "/\\\x[0-9a-f]{2}/"], "_", trim($input[4])));die();
        foreach ($input as $row) {
            $row = trim($row); // Remove unwanted newline-characters
            $actualLength = strlen($row); // Count the actual length
            
            // Remove all special characters and replace them with a random character ("." this time) and count the length of the string
            $displayedLength = strlen(preg_replace(["/\\\\\\\\/", "/\\\\\"/", "/\\\x[0-9a-f]{2}/"], "_", $row));
            $displayedLength -= 2; // Do not count the two quote characters in the beginning and end
    
            // Find all special characters and re-encode them (in our case, replace '\' and '"' with "..")
            $reencodedLength = strlen(preg_replace(["/\\\/", "/\"/"], "..", $row));
            $reencodedLength += 2; // Count the extra quote symbols added in the beginning and end
            
            $resultPart1 += $actualLength - $displayedLength; // Add the difference between the actual length and the displayed length
            $resultPart2 += $reencodedLength - $actualLength; // Add the difference between the re-encoded length and the actual length
        }
        
        
        
        return [$resultPart1, $resultPart2];
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    $res = solve($input);
    echo "Part 1: " . $res[0]. " and" . PHP_EOL;
    
    
    
    // Solve part 2
    echo "Part 2: " . $res[1] . " (solved in " . (microtime(true) - $start) . " seconds)";
