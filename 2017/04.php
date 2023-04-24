<?php
    /**
     * https://adventofcode.com/2017/day/4
     *
     *
     *
     * A new system policy has been put in place that requires all accounts to use a passphrase instead of simply a password.
     * A passphrase consists of a series of words (lowercase letters) separated by spaces.
     *
     * To ensure security, a valid passphrase must contain no duplicate words.
     */



    /**
     * Get input from file
     */
    if (!is_file("04.txt")) { // If file is missing, terminate
        die("Missing file 04.txt");
    } else {
        $input = file("04.txt"); // Save file as an array
    }



    /**
     * To solve today's challenge, we check for any duplicate words on each row. If a duplicate is found, that row is considered invalid.
     * We are going to turn every input row into an array, splitting at the space-character.
     * We then compare every word to each other, and if we find a match, we have an invalid row and move on to the next one.
     *
     *
     *
     * ** SPOILER **
     * The "turning the row into an array" is mostly for part 2. Because, in part 2, we need to not only compare exact matches
     * but also anagrams. That's easily achieved by sorting all the letters in every word.
     */
    function solve($input, $part2 = false) {
        $passphrases = array_map("trim", $input); // Remove unwanted characters from our input
        $valid = 0; // Count the valid rows



        // Loop through every row in our input
        foreach ($passphrases as $phrase) {
            $phrase = explode(" ", $phrase); // Turn row into an array
            $max = count($phrase); // Get the amount of characters



            // Loop through each word
            foreach ($phrase as &$p) {
                $p = str_split($p); // Turn word into an array of characters

                // If we are doing part 2, sort the characters
                if ($part2) {
                    sort($p);
                }
            } unset($p);



            // Loop through each word
            for ($i = 0; $i < $max - 1; $i++) {
                // Loop through every other word
                for ($j = $i + 1; $j < $max; $j++) {
                    // Compare the two currently selected words. If they match, the row is considered invalid
                    if ($phrase[$i] == $phrase[$j]) {
                        continue 3;
                    }
                }
            }



            // If we reach this point, we have a valid row
            $valid++;
        }



        return $valid;
    }



    // Solve part 1
    $start = microtime(true);
    echo "Part 1: " . solve($input) . " (solved in " . (microtime(true) - $start) . " seconds)" . PHP_EOL;



    // Solve part 2
    $part2 = true; // Tells our function to use parts needed to solve part 2
    $start = microtime(true);
    echo "Part 2: " . solve($input, $part2) . " (solved in " . (microtime(true) - $start) . " seconds)";
