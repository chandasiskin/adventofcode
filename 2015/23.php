<?php
    /**
     * https://adventofcode.com/2015/day/23
     *
     *
     *
     * Little Jane Marie just got her very first computer for Christmas from some unknown benefactor.
     * It comes with instructions and an example program, but the computer itself seems to be malfunctioning.
     * She's curious what the program does, and would like you to help her run it.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("23.txt")) { // If file is missing, terminate
        die("Missing file 23.txt");
    } else {
        $input = file("23.txt"); // Save file as an array
    }
    
    
    
    /**
     * If you examine the input, you can work out that we are playing with the "Collatz conjecture" (https://en.wikipedia.org/wiki/Collatz_conjecture),
     * a.k.a. "3n + 1 problem", "the 3n + 1 conjecture", "the Ulam conjecture" (after Stanisław Ulam),
     * "Kakutani's problem" (after Shizuo Kakutani), "the Thwaites conjecture" (after Sir Bryan Thwaites),
     * "Hasse's algorithm" (after Helmut Hasse), or the "Syracuse problem".
     * The output that is asked for is "how many steps does it take to go from the number A to 1?".
     * While looking at my input below, this is what happens (the number to the left in my input is the row number):
     * 1. At the first row, we jump to row 1 + 22 = 23 if "a" is one. If not, we continue with the second row.
     * ** SPOILER **
     * 2. Since part 1 starts "a" with the value 0 and part 2 starts "a" with the value 1, the second row means:
     * if (part 1) jump to row 23, else jump to row 2
     * ** END OF SPOILER **
     * 3. Regardless of the jump, we do some adding and multiplying to the value of "a".
     * 4. If we "started" from row 2, we jump to row 22 + 19 = 41 after all the adding and multiplying.
     * 5. If we "started" from row 23, we end up on row 41 after all the adding and multiplying.
     * 6. On row 41 we have a jump out of bounds (exiting the program) if "a" is 1.
     * 7. The row after that increments the value of "b", which represents "steps it takes for "a" to go from starting value to 1".
     * 8. On row 43 we check if "a" is even. If true we jump to 47, which halves the value of "a" and then jumps back to row 41 (as stated by row 48).
     * 9. If "a" is NOT even we multiply "a" with 3 and add 1 and jump back to row 41 thanks to row 46 and row 48.
     * 10. Now we're back at instruction number 6 above. We keep looping between instruction 6-9 until "a" reaches value 1.
     *
     * In short, this program does
     * if ($a === 0)
     *     <give $a some value depending on your input>
     * else
     *     <give $a some other value depending on your input>
     *
     * while ($a !== 1) {
     *     $b++;
     *
     *     if ($a is even)
     *         $a = $a / 2;
     *     else
     *         $a = $a * 3 + 1;
     * }
     *  1 jio a, +22
     *  2 inc a
     *  3 tpl a
     *  4 tpl a
     *  5 tpl a
     *  6 inc a
     *  7 tpl a
     *  8 inc a
     *  9 tpl a
     * 10 inc a
     * 11 inc a
     * 12 tpl a
     * 13 inc a
     * 14 inc a
     * 15 tpl a
     * 16 inc a
     * 17 inc a
     * 18 tpl a
     * 19 inc a
     * 20 inc a
     * 21 tpl a
     * 22 jmp +19
     * 23 tpl a
     * 24 tpl a
     * 25 tpl a
     * 26 tpl a
     * 27 inc a
     * 28 inc a
     * 29 tpl a
     * 30 inc a
     * 31 tpl a
     * 32 inc a
     * 33 inc a
     * 34 tpl a
     * 35 inc a
     * 36 inc a
     * 37 tpl a
     * 38 inc a
     * 39 tpl a
     * 40 tpl a
     * 41 jio a, +8
     * 42 inc b
     * 43 jie a, +4
     * 44 tpl a
     * 45 inc a
     * 46 jmp +2
     * 47 hlf a
     * 48 jmp -7
     *
     *
     *
     * ** SPOILER **
     * Part 2 is how much money we can spend and still not survive.
     */
    function solve($input, $part2 = false) {
        $pos = 0; // Holds the position of the current instruction
        $length = count($input); // Instruction counter (to check if we're out of bounds)
        $memory = [ // Stores the values of "a" and "b"
            "a" => !$part2 ? 0 : 1, // If we are doing part 1, set "a" to 0, else set it to 1
            "b" => 0 // Set value of "b" to zero
        ];
        
        
        
        // Keep looping the program while we are not out of bounds
        while ($pos >= 0 && $pos < $length) {
            // Splits the instruction row at the space-character into an array (also trims unwanted characters from the row)
            $instructions = preg_split("/[ ,]+/", trim($input[$pos]));
            
            // Checks what instruction to run
            switch ($instructions[0]) {
                // If halving a value
                case "hlf":
                    $memory[$instructions[1]] /= 2;
                    $pos++; // Increment position
                    break;
                
                // If tripling a value
                case "tpl":
                    $memory[$instructions[1]] *= 3;
                    $pos++; // Increment position
                    break;
                
                // If incrementing a value
                case "inc":
                    $memory[$instructions[1]]++;
                    $pos++; // Increment position
                    break;
                
                // If doing a jump
                case "jmp":
                    $pos += intval($instructions[1]); // Increase positions by "offset"
                    break;
                
                // If doing a "jump if even"
                case "jie":
                    // If "a" is even, increase position by "offset", otherwise jump to next row
                    $pos += $memory[$instructions[1]] % 2 === 0 ? intval($instructions[2]) : 1;
                    break;
                
                // If doing a "jump if one"
                case "jio":
                    // If "a" is 1, increase position by "offset", otherwise jump to next row
                    $pos += $memory[$instructions[1]] === 1 ? intval($instructions[2]) : 1;
                    break;
                
                // If an invalid instruction has slipped into the input-file
                default: die("Invalid instruction: $input[$pos]");
            }
        }
        
        
        
        return $memory["b"];
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    echo "Part 1: " . solve($input) . " (solved in " . (microtime(true) - $start) . " seconds)" . PHP_EOL;
    
    
    
    // Solve part 2
    $part2 = true;
    $start = microtime(true);
    echo "Part 2: " . solve($input, $part2) . " (solved in " . (microtime(true) - $start) . " seconds)";
