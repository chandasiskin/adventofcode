<?php
    /**
     * https://adventofcode.com/2016/day/5
     *
     *
     *
     * You are faced with a security door designed by Easter Bunny engineers that seem to have acquired most of their security knowledge by watching hacking movies.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("05.txt")) { // If file is missing, terminate
        die("Missing file 05.txt");
    } else {
        $input = file_get_contents("05.txt"); // Save file as a string
    }
    
    
    
    /**
     * The solution is pretty short and straight-forward, but the execution time is more demanding.
     * We use our input string together with an incrementing integer, create a md5-hash with these and check if it starts with 5 zeros.
     * If it does, store the 6:th character and generate the next hash. When we have found 8 of these, we're done.
     *
     *
     *
     * ** SPOILER **
     * This time, the 6:th character determines the positions of the 7:th character in the password.
     * This means, if the 6:th hashed character is a 4, the 7:th hashed character goes to position 5 in the password
     * (remember that indexing starts at 0). If the 6:th hashed character is out of bounds of our 8 character long password,
     * or is illegal, skip it. If there already is a character at that position, skip it.
     */
    function solve($input) {
        $salt = trim($input); // Trim our input from unwanted characters
        $i = 0; // Current salt-index
        $pwd = ["", "________"]; // Holds the passwords for part 1 and part 2
        
        
        
        // Start looping
        do {
            $md5 = md5($salt . $i); // Get the md5-hash from our input and current integer
            
            
            
            // If our hash starts with 5 zeros
            if (substr($md5, 0, 5) === "00000") {
                if (strlen($pwd[0]) < 8) { // Do we have 8 characters for our first password?
                    $pwd[0] .= $md5[5]; // Insert 6:th character to our part 1 password
                }
                
                // If the 6:th character is a number, is in bounds as is not yet set in the password, set it
                if (is_numeric($md5[5]) && isset($pwd[1][$md5[5]]) && $pwd[1][$md5[5]] === "_") {
                    // Store the 7:th hash-character at the positions of the 6:th hash-character in the password for part 2
                    $pwd[1][$md5[5]] = $md5[6];
                }
            }
            
            
            
            $i++; // Increment our salt-integer
        } while (strpos($pwd[1], "_") !== false); // If we still have unset characters in our part 2 password
        
        
        
        return $pwd;
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    $res = solve($input);
    echo "Part 1: " . $res[0] . " and" . PHP_EOL;
    
    
    
    // Solve part 2
    echo "Part 2: " . $res[1] . " (solved in " . (microtime(true) - $start) . " seconds)";
