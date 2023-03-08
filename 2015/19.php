<?php
    /**
     * https://adventofcode.com/2015/day/19
     *
     *
     *
     * Rudolph the Red-Nosed Reindeer is sick! His nose isn't shining very brightly, and he needs medicine.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("19.txt")) { // If file is missing, terminate
        die("Missing file 19.txt");
    } else {
        $input = file("19.txt"); // Save file as an array
    }
    
    
    
    /**
     * We need to try every single replacement and calculate all the unique results we get.
     * For that, we loop through each "atom", replace it, and store it in an array.
     * Once done, we count the elements in said array.
     *
     *
     *
     * ** SPOILER **
     * Solutions for part 2:
     *
     * Credits to https://www.reddit.com/r/adventofcode/comments/3xflz8/comment/cy4etju/
     *
     * First insight
     * There are only two types of productions:
     * e => XX and X => XX (X is not Rn, Y, or Ar)
     * X => X Rn X Ar | X Rn X Y X Ar | X Rn X Y X Y X Ar
     *
     * Second insight
     * You can think of Rn Y Ar as the characters ( , ):
     * X => X(X) | X(X,X) | X(X,X,X)
     * Whenever there are two adjacent "elements" in your "molecule", you apply the first production. This reduces your molecule length by 1 each time.
     * And whenever you have T(T) T(T,T) or T(T,T,T) (T is a literal token such as "Mg", i.e. not a nonterminal like "TiTiCaCa"),
     * you apply the second production. This reduces your molecule length by 3, 5, or 7.
     *
     * Third insight
     * Repeatedly applying X => XX until you arrive at a single token takes count(tokens) - 1 steps:
     * ABCDE => XCDE => XDE => XE => X
     * count("ABCDE") = 5
     * 5 - 1 = 4 steps
     * Applying X => X(X) is similar to X => XX, except you get the () for free. This can be expressed as count(tokens) - count("(" or ")") - 1.
     * A(B(C(D(E)))) => A(B(C(X))) => A(B(X)) => A(X) => X
     * count("A(B(C(D(E))))") = 13
     * count("(((())))") = 8
     * 13 - 8 - 1 = 4 steps
     *
     * You can generalize to X => X(X,X) by noting that each , reduces the length by two (,X). The new formula is count(tokens) - count("(" or ")") - 2*count(",") - 1.
     * A(B(C,D),E(F,G)) => A(B(C,D),X) => A(X,X) => X
     * count("A(B(C,D),E(F,G))") = 16
     * count("(()())") = 6
     * count(",,,") = 3
     * 16 - 6 - 2*3 - 1 = 3 steps
     * This final formula works for all of the production types (for X => XX, the (,) counts are zero by definition.)
     *
     * The solution
     * <elements in total> - <Rn in total> - <Ar in total> - 2 * <Y in total>
     */
    function solve($input) {
        $replacements = []; // Holds all the replacements
        $molecule = end($input); // The molecule
        $replacementCounter = []; // Stores all the unique combinations
        
        
        
        // Build the replacements-array
        for ($i = 0, $max = count($input) - 2; $i < $max; $i++) {
            // Remove unwanted characters from current input-row and split it into an array
            list($from, $to) = explode(" => ", trim($input[$i]));
            $replacements[$from][$to] = 1; // Store the replacement in the array
            
            
            
            $pos = 0; // Start in the beginning of the molecule
            
            // Loop through each "atom" and look for the current "atom" to replace
            while (strpos($molecule, $from, $pos) !== false) {
                $pos = strpos($molecule, $from, $pos); // Get the next "atom" starting from pos "$pos"
                // Replace the current atom and store it in the array.
                // Storing it as a key in the array, remove the hassle of duplicates
                $replacementCounter[substr($molecule, 0, $pos) . $to . substr($molecule, $pos + strlen($from))] = 1;
                
                $pos++; // Increment current pos
            }
        }
        
        
        
        $moleculaCount = preg_match_all("/[A-Z]/", $molecule); // Count total amount of "atoms"
        $RnCount = substr_count($molecule, "Rn"); // Count the amount of "Rn-atoms"
        $ArCount = substr_count($molecule, "Ar"); // Count the amount of "Ar-atoms"
        $YCount = substr_count($molecule, "Y"); // Count the amount of "Y-atoms"
        $res2 = $moleculaCount - $RnCount - $ArCount - 2 * $YCount - 1; // Calculate the answer to part 2
        
        
        
        return [count($replacementCounter), $res2];
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    $res = solve($input);
    echo "Part 1: " . $res[0] . " and" . PHP_EOL;
    
    
    
    // Solve part 2
    echo "Part 2: " . $res[1] . " (solved in " . (microtime(true) - $start) . " seconds)";
