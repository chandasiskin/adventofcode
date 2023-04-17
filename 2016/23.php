<?php
    /**
     * https://adventofcode.com/2016/day/23
     *
     *
     *
     * This is one of the top floors of the nicest tower in EBHQ. The Easter Bunny's private office is here,
     * complete with a safe hidden behind a painting, and who wouldn't hide a star in a safe behind a painting?
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
     * Part 1 is so simple that you could just run the program as it is. But that would be just too easy. Let's translate my input:
     * cpy a b
     * dec b
     * cpy a d
     * cpy 0 a
     * cpy b c
     * inc a
     * dec c
     * jnz c -2
     * dec d
     * jnz d -5
     * dec b
     * cpy b c
     * cpy c d
     * dec d
     * inc c
     * jnz d -2
     * tgl c
     * cpy -16 c
     * jnz 1 c
     * cpy 72 c
     * jnz 77 d
     * inc a
     * inc d
     * jnz d -2
     * inc c
     * jnz c -5
     *
     * translates to
     *
     *  1. $b = $a; //cpy a b
     *  2. $b--; //dec b
     *  3. do {$d = $a; //cpy a d
     *  4.      $a = 0; //cpy 0 a
     *  5.      do {$c = $b; //cpy b c
     *  6.          do {$a++; //inc a
     *  7.              $c--; //dec c
     *  8.              } while ($c !== 0); //jnz c -2
     *  9.          $d--; //dec d
     * 10.      } while ($d !== 0); //jnz d -5
     * 11.      $b--; //dec b
     * 12.      $c = $b; //cpy b c
     * 13.      $d = $c; //cpy c d
     * 14.      do {$d--; //dec d
     * 15.          $c++; //inc c
     * 16.      } while ($d !== 0); //jnz d -2
     * 17.      //!!!!!!!!!!!!!!!!!tgl c
     * 18.      $c = -16; //cpy -16 c
     * 19. } while (1 !== 0); // jnz 1 c
     * 20. cpy 72 c
     * 21. jnz 77 d
     * 22. inc a
     * 23. inc d
     * 24. jnz d -2
     * 25. inc c
     * 26. jnz c -5
     *
     * Looking at rows 3 to 19, we can see that we have ourselves an infinite loop without any chance of breaking out.
     * But don't worry, we have our "tgl c" on row 17 that will help us with that:
     * The first time we hit row 17, c will have the value of 10. This means we are altering 10 rows below of row 17, which is row 27.
     * Row 27 does not exist, so nothing happens. Next time we come to row 17, c will have the value of 8 instead.
     * This will alter row 17 + 8 = 25, turning "inc c" to "dec c". Next time we visit row 17, c will be 6,
     * meaning we will turn "inc d" to "dec d" on row 17 + 6 = 23. After that, c will be 4 by the time we hit tgl at row 17.
     * This alters row 17 + 4 = 21 from "jnz 77 d" to "cpy 77 d". After this, when we reach row 17 with c having the value of 2
     * we will look at row 17 + 2 = 19. This will alter our "while (1 !== 0)" (or "jnz 1 c") to "cpy 1 c" and thus removing our infinite loop!
     * With this information, we can change our row 17 from "tgl c" to "if (c === 2) break;" and rows 20-26 to:
     *
     * 20. $c = 72; //cpy 72 c
     * 21. do {$d = 77; //cpy 77 d
     * 22.      do {$a++; //inc a
     * 23.          $d--; //dec d
     * 24.      } while ($d !== 0); //jnz d -2
     * 25.      $c--; //dec c
     * 26. } while ($c !== 0); //jnz c -5
     *
     * If we put our focus on the first part of the code, rows 1-19 and print the register each time we hit row 17,
     * we can see a pattern. And the pattern is factorial! So, the first part of the code just calculates <input>!, that is 7!.
     *
     * The second part of the code gives the personal touch for each user, by adding a product of two numbers found on rows 20 and 21.
     *
     * In conclusion, this problem is about solving a factorial (adding the product is just a bonus).
     *
     *
     *
     * ** SPOILER **
     * In part 2 we are not calculating the result of 7! but of 12!.
     */
    function solve($input, $part2 = false) {
        $factorial = !$part2 ? 7 : 12; // Are we doing part 1 or part 2?
        $result = 1; // Holds the final result
        // Extracts the number from rows 19 and 20 and multiply them.
        // If your final answer isn't correct, check your input and enter your numbers manually.
        $product = intval(preg_replace("/[^\d]+/", "", $input[19])) *
                    intval(preg_replace("/[^\d]+/", "", $input[20]));
        
        
        
        // Loop through each number from 2 up to 7/12 (depending on which part you are solving) and multiply it to the total
        for ($i = 2; $i <= $factorial; $i++) {
            $result *= $i;
        }
        
        
        
        return $result + $product;
    }



    // Solve part 1
    $start = microtime(true);
    echo "Part 1: " . solve($input) . " (solved in " . (microtime(true) - $start) . " seconds)" . PHP_EOL;



    // Solve part 2
    $part2 = true; // Tells our function to use parts needed to solve part 2
    $start = microtime(true);
    echo "Part 2: " . solve($input, $part2) . " (solved in " . (microtime(true) - $start) . " seconds)";
