<?php
    /**
     * https://adventofcode.com/2023/day/4
     *
     *
     *
     * The gondola takes you up.
     * Strangely, though, the ground doesn't seem to be coming with you; you're not climbing a mountain.
     * As the circle of Snow Island recedes below you, an entire new landmass suddenly appears above you!
     * The gondola carries you to the surface of the new island and lurches into the station.
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
     * Part 1 is to loop through each number to the right of the pipe-character on every row
     * and count the number of matches to the left of the pipe-character on the same row.
     * The score for a card is 2^<number of matches - 1>, or 0 if no matches were found.
     * The total score is the answer to the first assignment.
     *
     *
     *
     * ** SPOILER **
     * For part 2, instead of counting points, the number of matches gives the elf the same number of new playing cards.
     * If the elf has three matches on card 1, the elf gets one new card 2, one new card 3 and one new card 4.
     * A total of three new cards.
     * If the elf has two matches on card 10, the elf gets a new card 11 and a new card 12 for a total of two new cards.
     * Once all, both original and new cards have been scratched, the grand total card amount is returned.
     */
    function solve($input, $part2 = false) {
        $total_points = 0; // Keeps score for part 1
        $cards = array_fill(1, count($input), 1); // Keeps card count for part 2
        
        
        
        // Loop through each row
        foreach ($input as $id => $row) {
            // Replace all multi-spaces with a single space
            $trimmed = preg_replace("/\s+/", " ", $row);
            // Extract the winning numbers from the left and the elfs numbers from the right
            $splitted = preg_split("/Card +\d+: | \| /", $trimmed);
            
            // Create an array of all the numbers and flip values and keys, to make it easier to check if a number exists
            $winning_numbers = array_flip(explode(" ", $splitted[1]));
            // Create an array of all the elf's numbers
            $elf_numbers = explode(" ", $splitted[2]);
            
            // Keep track of how many numbers match
            $matching_numbers = 0;
            
            // Loop through every elf's number
            foreach ($elf_numbers as $nr) {
                // If it is a winning number, increment matches
                if (isset($winning_numbers[$nr])) {
                    $matching_numbers++;
                }
            }
            
            // If any matches were found...
            if ($matching_numbers > 0) {
                // ... calculate the cards total points and add this to the grand total
                $total_points += pow(2, $matching_numbers - 1);
                
                // ... increase the number of cards "matches"-number of times forward.
                // If the elf has, for example, 3 of card 10, and card 10 has 5 matches,
                // all 3 of the elf's card 10 has 5 matches, for a total of 15 new cards.
                for ($i = 0; $i < $matching_numbers; $i++) {
                    $cards[$id + 2 + $i] += $cards[$id + 1];
                }
            }
        }
        
        
        
        return [$total_points, array_sum($cards)];
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    $res = solve($input);
    echo "Part 1: " . $res[0] . " and<br />";
    
    
    
    // Solve part 2
    echo "Part 2: " . $res[1] . " (solved in " . (microtime(true) - $start) . " seconds)";