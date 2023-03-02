<?php
    /**
     * https://adventofcode.com/2015/day/16
     *
     *
     *
     * Your Aunt Sue has given you a wonderful gift, and you'd like to send her a thank-you card.
     * However, there's a small problem: she signed it "From, Aunt Sue" and you have 500 aunts named "Sue".
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("16.txt")) { // If file is missing, terminate
        die("Missing file 16.txt");
    } else {
        $input = file("16.txt"); // Save file as an array
    }
    
    
    
    /**
     * We have some properties of our gift-sending-aunt that we gathered from the wrapping paper.
     * We also have a list of properties of all the 500 aunts. Some properties may, or may not, be missing.
     * Properties that are not found on the list, does NOT equal to 0. They just remain unknown.
     * For part 1, we loop through the list and compare it to the properties we got from the wrapping paper.
     * If any property from the list doesn't match with a property from the wrapping paper, we move on to the next aunt on the list.
     *
     *
     *
     * ** SPOILER **
     * For part 2, we need to adjust our property-check. If we hit the "cats" or "trees" property,
     * we compare if the value from the wrapping paper is smaller than on the list. If it's not, we move on to the next aunt.
     * When we hit "pomeranians" or "goldfish" we compare if the value from the wrapping paper is larger than on the list.
     * If not, we move on.
     */
    function solve($input, $part2 = false) {
        // Store the property-values from the wrapping paper
        $mfcsam = [
            "children" => 3,
            "cats" => 7,
            "samoyeds" => 2,
            "pomeranians" => 3,
            "akitas" => 0,
            "vizslas" => 0,
            "goldfish" => 5,
            "trees" => 3,
            "cars" => 2,
            "perfumes" => 1
        ];
        
        
        
        // Loop through each aunt on the list
        foreach ($input as $row) {
            // Turn string of properties into an array of properties
            $tmp = preg_split("/[\s:,]+/", $row);
            
            
            
            // Loop through each property. We start at 2, because 0 is the id-property and 1 is the value of id
            for ($i = 2, $max = count($tmp) - 1; $i < $max; $i += 2) {
                // If we are doing part 2, with the ranges
                if ($part2) {
                    if ($tmp[$i] === "cats" || $tmp[$i] === "trees") { // If we hit the "cats" or "trees" property
                        if ($mfcsam[$tmp[$i]] >= intval($tmp[$i + 1])) { // If it's smaller than on the wrapping paper: move on
                            continue 2;
                        }
                    } elseif ($tmp[$i] === "pomeranians" || $tmp[$i] === "goldfish") { // If we hit the "pomeranians" or "goldfish" property
                        if ($mfcsam[$tmp[$i]] <= intval($tmp[$i + 1])) { // If it's larger than on the wrapping paper, move on
                            continue 2;
                        }
                    } elseif ($mfcsam[$tmp[$i]] !== intval($tmp[$i + 1])) { // Else, check if property-values are equal. If not, move on
                        continue 2;
                    }
                }
                
                
                
                // If we are doing part 1
                else {
                    if ($mfcsam[$tmp[$i]] !== intval($tmp[$i + 1])) { // Check if property values are the same. If not, move on
                        continue 2;
                    }
                }
           }
            
            
            
            return $tmp[1];
        }
        
        
        
        // If we reach this point, we haven't gotten any match on any aunt
        die("No aunt Sue found");
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    echo "Part 1: " . solve($input) . " (solved in " . (microtime(true) - $start) . " seconds)" . PHP_EOL;
    
    
    
    // Solve part 2
    $part2 = true;
    $start = microtime(true);
    echo "Part 2: " . solve($input, $part2) . " (solved in " . (microtime(true) - $start) . " seconds)";
