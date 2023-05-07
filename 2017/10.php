<?php
    /**
     * https://adventofcode.com/2017/day/10
     *
     *
     *
     * You come across some programs that are trying to implement a software emulation of a hash based on knot-tying.
     * The hash these programs are implementing isn't very strong, but you decide to help them anyway.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("10.txt")) { // If file is missing, terminate
        die("Missing file 10.txt");
    } else {
        $input = file_get_contents("10.txt"); // Save file as a string
    }
    
    
    
    /**
     * For part 1, we need to create an array with values from 0 to 255 and reverse parts of the array corresponding to our input.
     * The easy way is to make an array where the array-key represents the position of the value. But when cutting, sorting and inserting
     * parts from and into an array is expensive. Instead, we create one array where the keys represent the numbers in the list and
     * the value of the array represent the position of that number. We also create another array where the key represents the numbers
     * position and the value of the array represents the number in the list. This way, when we need to find the value at position X
     * we look in one array and when we want to find the position of value Y we look in the other array. Whenever we alter one of the arrays
     * we update the other.
     *
     * The reverse function is simple: update the position of the first value to the position of the last value, update the position of the
     * second value to the position of the second last value, and so on. The position of the first value is at <the first position> while
     * the position of the last value is at <the first position + length of the sub-array to reverse>. The second position is at
     * <the first position + 1> and the second to last is at <the first position + length of the sub-array to reverse - 1>.
     * Whenever looking for the last position, we need to make sure we stay in bounds. This is achieved by doing modulo with the size of the sub-array.
     *
     * The length of the sub-array to reverse is retrieved from our input. After a reverse is done, we increase our current position
     * by the length we just reversed along with the value of our skip. <current position>'s default value is 0, just as our skip.
     * Skip is increment by 1 after the whole length has been reversed.
     *
     *
     *
     * ** SPOILER **
     * For part 2, I thought the explanation was a little unclear, so I'll try to explain it better:
     * Starting with our input, we no longer have a set of integers separated by commas (","), but instead we interpret it as a string of any characters
     * (which just happens to be integers and commas). For each character (both numbers and commas) we get their corresponding ascii-value and use
     * the result as our input. This means, if our input is "1,2,3" it would result in "49 44 50 44 51". Once we have transformed our input into
     * ascii, we add five more numbers (already ascii-converted) to the input. These five values are "17 31 73 47 23".
     * For example, if our input is "1,2,3" our result would be "49 44 50 44 51 17 31 73 47 23". We use this as our new lengths, and run through them
     * not once, but 64 times.
     *
     * After our list is scrambled 64 times, we take the 16 first values and do XOR (https://en.wikipedia.org/wiki/Bitwise_operation#XOR)
     * on them. This results in a number in the range of 0 and 255. We convert this decimal into a hexadecimal value (with a leading 0 if the
     * resulting hex is only one character long).
     * We then move on to the next 16 numbers in our list and do the same thing. Once all 256 entries have been converted we've ended up
     * with a 32-character long hash.
     */
    function solve($input, $part2 = false) {
        $currentPosition = 0; // Where to start reversing
        $skipSize = 0; // How much to increment <current position>
        $listSize = 256; // The amount of entries in our list
        $valueInKeys = array_fill(0, $listSize, 0); // Create a list of size <lise size> where the array-key represents
                                                                    // the number in the list and the array-value represents
                                                                    // its position in the list.
        $posInKeys = []; // Prepares a list where the array-key represents the position of the lists number
                        // and the array-value represents the number at that position
        
        

        // Update current numbers positions (0 at positions 0, 1 at positions 1, a.s.o.)
        foreach ($valueInKeys as $key => &$value) {
            $value = $key;
        } unset($value);
        


        // If we are doing part 1
        if (!$part2) {
            $input = explode(",", trim($input)); // Separate the integers from each other



            // Loop through every integer in our input
            foreach ($input as $length) {
                reverse($valueInKeys, $currentPosition, $length); // Call function to reverse the list starting at
                                                                    // position <current position> and reverse <length>
                                                                    // amount of numbers

                $currentPosition = ($currentPosition + $length + $skipSize) % $listSize; // Update current position by adding
                                                                                        // length of the list just reversed
                                                                                        // and the size of our skip. Use modulo
                                                                                        // to keep current position on bounds
                $skipSize++; // Increment value of the skip
            }



            $posInKeys = array_flip($valueInKeys); // Update one of the arrays with the positions and numbers from the other



            return $posInKeys[0] * $posInKeys[1];
        }

        // If we are doing part 2
        else {
            $extra = explode(", ", "17, 31, 73, 47, 23"); // Create an array of the extra numbers to add to our input
            $extra = array_map("chr", $extra); // Convert them FROM their ascii-value
            $input = trim($input) . implode("", $extra); // Insert them into our input
            $input = array_map("ord", str_split($input)); // Create an array from our input and convert all characters INTO their ascii-value
            $hash = ""; // Holds our final hash
            $posInKeys = []; // Prepare the other array holding our numbers



            // Run through our input a total of 64 times
            for ($i = 0; $i < 64; $i++) {
                // Loop through every ascii-value in our input
                foreach ($input as $length) {
                    reverse($valueInKeys, $currentPosition, $length); // Call function to reverse the list starting at
                                                                        // position <current position> and reverse <length>
                                                                        // amount of numbers

                    $currentPosition = ($currentPosition + $length + $skipSize) % $listSize; // Update current position by adding
                                                                                            // length of the list just reversed
                                                                                            // and the size of our skip. Use modulo
                                                                                            // to keep current position on bounds
                    $skipSize++; // Increment value of the skip
                }
            }

            $posInKeys = array_flip($valueInKeys); // Update one of the arrays with the positions and numbers from the other



            // Divide our list into 16 blocks and loop through each
            for ($i = 0; $i < 16; $i++) {
                $xor = 0;



                // Divide each block into 16 entries and loop through each
                for ($j = 0; $j < 16; $j++) {
                    $pos = 16 * $i + $j; // Get position of current entry in current block

                    $xor = $xor ^ $posInKeys[$pos]; // XOR that entry
                }



                $hash .= $xor < 16 ? "0" : ""; // If our resulting XOR is smaller than 16, the resulting hex will only be
                                                // 1 character long. So we append a "0" to it
                $hash .= dechex($xor); // Convert the integer from our XOR into a hex and store it in the grand hash
            }
        }



        return $hash;
    }


    /**
     * @param $valueInKeys array The list of numbers
     * @param $currentPosition int Where to start reversing
     * @param $length int How many numbers to reverse
     * @return void
     */
    function reverse(&$valueInKeys, $currentPosition, $length) {
        $posInKeys = array_flip($valueInKeys); // Update the other list of numbers with the first list
        $listSize = count($valueInKeys); // Get the amount of numbers in our list



        // Loop through the submitted amount of numbers
        for ($i = 0; $i < $length; $i++) {
            $currentValue = $posInKeys[($i + $currentPosition) % $listSize]; // Get the value at current position
                                                                            // (modulo to stay in bounds)
            $valueInKeys[$currentValue] =
                ($currentPosition + ($length - 1) - $i + $listSize) % $listSize; // Update position of current value according
                                                                                // to the reverse-rules
        }
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    echo "Part 1: " . solve($input) . " (solved in " . (microtime(true) - $start) . " seconds)" . PHP_EOL;
    
    
    
    // Solve part 2
    $part2 = true; // Tells our function to use parts needed to solve part 2
    $start = microtime(true);
    echo "Part 2: " . solve($input, $part2) . " (solved in " . (microtime(true) - $start) . " seconds)";
