<?php
    /**
     * https://adventofcode.com/2016/day/21
     *
     *
     *
     * The computer system you're breaking into uses a weird scrambling function to store its passwords.
     * It shouldn't be much trouble to create your own scrambled password so you can add it to the system;
     * you just have to implement the scrambler.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("21.txt")) { // If file is missing, terminate
        die("Missing file 21.txt");
    } else {
        $input = file("21.txt"); // Save file as an array
    }
    
    
    
    /**
     * Part 1 is pretty straightforward: take a password and scramble it according to the rules. The rules are:
     * swap position X with position Y = swap the letters at position X and Y
     * swap letter X with letter Y = same as above, except you have to figure out what positions the letters X and Y are before swapping
     * rotate left/right X steps = move all letters X steps to left/right (letters that go out of bounds rotate back to the other end)
     * reverse positions X through Y = reverse all letters starting from positions X and end at position Y
     * move position X to position Y = remove letter at position X and move it to position Y
     * rotate based on position of letter X = move letters to the right based on the positions of letter X. We move all letters once
     *      plus a number of times equal to the position of X and, if the positions is greater than 3, one more time. We move them to the right.
     *
     *
     *
     * ** SPOILER **
     * Part 2 is to reverse the process of part 1. Firstly, we need to reverse the order we do the instructions
     * (took me a few attempts before figuring this part out). Secondly, we need to rewrite a few of the rules:
     * swap position X with position Y = same as before (makes no difference if we swap X with Y or Y with X)
     * swap letter X with letter Y = same as above
     * rotate left/right X steps = if it says "rotate left" we just reverse it to "rotate right"
     * reverse positions X through Y = we just un-reverse, meaning we do a regular reverse
     * move position X to position Y = here we swap X with Y and do the same as earlier
     * rotate based on position of letter X = this one is the tricky part. Let's draw a table where we see how the letters are moving:
     *
     * Old pos | New pos | Pos with modular
     * ------------------------------------
     *    0    |    1    |        1
     *    1    |    3    |        3
     *    2    |    5    |        5
     *    3    |    7    |        7
     *    4    |    10   |        2
     *    5    |    12   |        4
     *    6    |    14   |        6
     *    7    |    16   |        0
     *
     * With this table we can see that regardless of the starting position, we end up in a unique positions,
     * meaning you can only end up at positions Y from position X. So, to reverse the rule we need rotate according to this:
     *
     * Old pos | New pos | Steps
     * -------------------------
     *    0    |    7    |   7
     *    1    |    0    |   -1
     *    2    |    4    |   2
     *    3    |    1    |   -2
     *    4    |    5    |   1
     *    5    |    2    |   -3
     *    6    |    6    |   0
     *    7    |    3    |   -4
     *
     * This isn't a large table, so we could just hardcode it. But, if we look closer we can find two patterns:
     * one for the even numbers and one for the odd numbers (with 0 being a special-case).
     * First, we need to rewrite 0 as 8 (still the same position as 8 % 8 equals 0).
     * From there, the even numbers are:
     *
     * Old pos | New pos | Steps
     * -------------------------
     *    2    |    4    |   2
     *    4    |    5    |   1
     *    6    |    6    |   0
     *    8    |    7    |   -1
     *
     * The pattern for the step count is: 3 - (<old pos> / 2)
     * For the odd numbers, the table looks like:
     *
     * Old pos | New pos | Steps
     * -------------------------
     *    1    |    0    |   -1
     *    3    |    1    |   -2
     *    5    |    2    |   -3
     *    7    |    3    |   -4
     *
     * Here the pattern is -roundUp(<old pos> / 2)
     * In summary, this translates to:
     * 1. If index is 0, rewrite it to 8.
     * 2a. If index is odd, the amount of right steps needed is -roundUp(<old pos> / 2)
     * 2b. If index is even, the amount of right steps needed is 3- (<old pos> / 2)
     */
    function solve($input, $part2 = false) {
        $password = !$part2 ? "abcdefgh" : "fbgdceah"; // If we are doing part 1 or part 2
        
        // If we are doing part 2, reverse the input-array
        if ($part2) {
            $input = array_reverse($input);
        }
        
        
        
        // Run through each rule in the input-list
        foreach ($input as $rule) {
            $inst = explode(" ", trim($rule)); // Trim unwanted characters from the string and explode it at space-characters
            
            
            
            // Check what rule to apply
            switch ($inst[0].$inst[1]) {
                // Swap two letters based on their position
                case "swapposition": swapPosition(intval($inst[2]), intval($inst[5]), $password); break;
                // Swap two letters based on the letter
                case "swapletter": swapLetter($inst[2], $inst[5], $password); break;
                // Rotate to the right
                case "rotateright":
                // Rotate to the left
                case "rotateleft": rotate(intval($inst[2]), $inst[1], $password, $part2); break;
                // Rotate based on the positions of a letter
                case "rotatebased": rotateBased($inst[6], $password, $part2); break;
                // Reverse part of the password
                case "reversepositions": reverse(intval($inst[2]), intval($inst[4]), $password); break;
                // Move a letter from one positions to another
                case "moveposition": move(intval($inst[2]), intval($inst[5]), $password, $part2); break;
                // If an illegal rule was encountered
                default: die("Illegal command: " . trim($rule));
            }
        }
        
        
        
        return $password;
    }
    
    
    /**
     * @param $a int Position of first letter
     * @param $b int Position of second letter
     * @param $password string The password
     * @return void
     */
    function swapPosition($a, $b, &$password) {
        $tmpLetter = $password[$a]; // Store the first letter in a temp-variable
        $password[$a] = $password[$b]; // Overwrite the first letter with the second letter
        $password[$b] = $tmpLetter; // Overwrite the second letter with the temp-variable
    }
    
    
    /**
     * @param $a string The first letter
     * @param $b string The second letter
     * @param $password string The password
     * @return void
     */
    function swapLetter($a, $b, &$password) {
        $posA = strpos($password, $a); // Get positions of the first letter
        $posB = strpos($password, $b); // Get positions of the second letter
        
        swapPosition($posA, $posB, $password); // Call function to swap letter based on position
    }
    
    
    /**
     * @param $steps int Amount of moves to make
     * @param $dir string Rotate to left or right
     * @param $password string The password
     * @param $unscramble boolean If we are doing part 1 or part 2
     * @return void
     */
    function rotate($steps, $dir, &$password, $unscramble = false) {
        $len = strlen($password); // Get length of the password
        $newPassword = ""; // Initiate the new password
        // If we are moving left, set step-count to negative
        if ($dir === "left") {
            $steps *= -1;
        }
        
        // If we are doing part 2, reverse direction
        if ($unscramble) {
            $steps *= -1;
        }
        
        
        
        // Loop through each character
        for ($i = 0; $i < $len; $i++) {
            // Calculate the new position by taking the current position, adding the amount of steps to move,
            // and doing some magic with the modulo to keep the new position in bounds
            $newPosition = ($i + $steps + 2 * $len) % $len;
            // Store current character in the new password at the new position
            $newPassword[$newPosition] = $password[$i];
        }
        
        
        
        // Overwrite the old password with the new one
        $password = $newPassword;
    }
    
    
    /**
     * @param $letter string The letter to search for
     * @param $password string The password
     * @param $unscramble boolean If we are doing part 1 or part 2
     * @return void
     */
    function rotateBased($letter, &$password, $unscramble = false) {
        $pos = strpos($password, $letter); // Get position of letter
        // If we are doing part 1
        if (!$unscramble) {
            // The amount of steps to move is 1 plus the value of the position of the letter plus 1 if the letter-position is greater than 3
            $steps = 1 + $pos + ($pos >= 4 ? 1 : 0);
        }
        
        // If we are doing part 2
        else {
            $pos = $pos === 0 ? strlen($password) : $pos; // If letter-position is 0, rewrite it to 8. Otherwise, keep the old value
            // If the position is even, set step count to 3 - <pos> / 2.
            // If the position is odd, set step count to -roundUp(<pos> / 2)
            // NOTE: see comment for part 2 at the top of this file for clarification
            $steps = $pos % 2 === 0 ? 3 - $pos / 2 : -ceil($pos / 2);
        }
        
        
        
        rotate($steps, "right", $password); // Call function to move letters to the right
    }
    
    
    /**
     * @param $start int From what positions to start reversing
     * @param $end int At what position the reversing ends
     * @param $password string The password to reverse
     * @return void
     */
    function reverse($start, $end, &$password) {
        $password =
            substr($password, 0, $start) . // Do nothing with the part up till <start>
            strrev(substr($password, $start, $end - $start + 1)) . // Reverse the part from <start> and <amount = end - start + 1> characters forward
            substr($password, $end + 1); // Do nothing with the part after <end>
    }
    
    
    /**
     * @param $from int Position to move from
     * @param $to int Positions to move to
     * @param $password string The password
     * @param $unscramble boolean If we are doing part 1 or part 2
     * @return void
     */
    function move($from, $to, &$password, $unscramble = false) {
        $newPassword = ""; // Holds the new password
        $len = strlen($password); // Get the password length
        
        // If we are doing part 2 switch <to> and <from>
        if ($unscramble) {
            $tmp = $from;
            $from = $to;
            $to = $tmp;
        }
        
        
        
        // Loop through all letter
        for ($i = 0; $i < $len; $i++) {
            // If we hit the position to move from, keep going to the next letter
            if ($i === $from) {
                continue;
            }
            
            // If we hit the position to move to
            if ($i === $to) {
                // If we are moving forward (value of <to> is greater than value of <from>)
                if ($from < $to) {
                    $newPassword .= $password[$i]; // First store the letter at that position
                    $newPassword .= $password[$from]; // THEN store the letter we wish to move
                    
                    continue;
                }
                
                // If we are moving backward (value of <to> is smaller than the value of <from>)
                else {
                    $newPassword .= $password[$from]; // First store the letter we wish to move
                    $newPassword .= $password[$i]; // THEN store the letter next in order
                    
                    continue;
                }
            }
            
            
            
            $newPassword .= $password[$i]; // Store the next letter in order
        }
        
        
        
        $password = $newPassword; // Rewrite the old password with the new one
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    echo "Part 1: " . solve($input) . " (solved in " . (microtime(true) - $start) . " seconds)" . PHP_EOL;
    
    
    
    // Solve part 2
    $part2 = true; // Tells our function to use parts needed to solve part 2
    $start = microtime(true);
    echo "Part 2: " . solve($input, $part2) . " (solved in " . (microtime(true) - $start) . " seconds)";
