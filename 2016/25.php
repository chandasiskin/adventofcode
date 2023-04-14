<?php
    /**
     * https://adventofcode.com/2016/day/25
     *
     *
     *
     * You open the door and find yourself on the roof. The city sprawls away from you for miles and miles.
     *
     * There's not much time now - it's already Christmas, but you're nowhere near the North Pole,
     * much too far to deliver these stars to the sleigh in time.
     *
     * However, maybe the huge antenna up here can offer a solution. After all, the sleigh doesn't need the stars,
     * exactly; it needs the timing data they provide, and you happen to have a massive signal generator right here.
     */
    
    
    
    /**
     * Get input from file
     */
    if (!is_file("25.txt")) { // If file is missing, terminate
        die("Missing file 25.txt");
    } else {
        $input = file("25.txt"); // Save file as an array
    }
    
    
    
    /**
     * This is another of those tasks where things gets much easier if we figure out what the input does.
     * We send an input into the "code" via "register A" and the output we want is a never ending alternation of zeros and ones (0101010101.....).
     * So let's get to it:
     * cpy a d
     * cpy 4 c
     * cpy 643 b
     * inc d
     * dec b
     * jnz b -2
     * dec c
     * jnz c -5
     * cpy d a
     * jnz 0 0
     * cpy a b
     * cpy 0 a
     * cpy 2 c
     * jnz b 2
     * jnz 1 6
     * dec b
     * dec c
     * jnz c -4
     * inc a
     * jnz 1 -7
     * cpy 2 b
     * jnz c 2
     * jnz 1 4
     * dec b
     * dec c
     * jnz 1 -4
     * jnz 0 0
     * out b
     * jnz a -19
     * jnz 1 -21
     *
     * translates to
     *
     * $d = $a; //cpy a d
     * $c = 4; //cpy 4 c
     * do {$b = 643; //cpy 643 b
     *      do {$d++; //inc d
     *          $b--; //dec b
     *      } while ($b !== 0); //jnz b -2
     *      $c--; //dec c
     * } while ($c !== 0); //jnz c -5
     * do {$a = $d; //cpy d a
     *      do {//jnz 0 0
     *          $b = $a; //cpy a b
     *          $a = 0; //cpy 0 a
     *          do {$c = 2; //cpy 2 c
     *              do {//jnz b 2
     *                  if ($b === 0) {break 2;}//jnz 1 6
     *                  $b--; //dec b
     *                  $c--; //dec c
     *              } while ($c !== 0); //jnz c -4
     *              $a++; //inc a
     *          } while (1 !== 0); //jnz 1 -7
     *          $b = 2; //cpy 2 b
     *          do {jnz c 2
     *              jnz 1 4
     *              $b--; //dec b
     *              $c--; //dec c
     *          } while (1 !== 0); //jnz 1 -4
     *          //jnz 0 0
     *          echo $b; //out b
     *      } while ($a !== 0); //jnz a -19
     * } while (1 !== 0); //jnz 1 -21
     *
     * In plain english the first 8 rows just adds the value in register A to the product of the two values we get in line 2 and 3 and stores the result in register D. So
     * $d = $a; //cpy a d
     * $c = 4; //cpy 4 c
     * do {$b = 643; //cpy 643 b
     *      do {$d++; //inc d
     *          $b--; //dec b
     *      } while ($b !== 0); //jnz b -2
     *      $c--; //dec c
     * } while ($c !== 0); //jnz c -5
     *
     * can be rewritten as
     * $d = $a + 4 * 643;
     * NOTE: 4 and 643 are my inputs. You should check yours.
     * Register A is unchanged and in the end registers B and C are reset back to 0.
     *
     * The do-while-loop we enter next is an infinite loop. Its task is to keep echoing the output signal forever.
     * Before entering the next loop it copies the value in register D (which we calculated earlier) to register A.
     * The next do-while-loop then takes the value in register A and divides it with two.
     * If there is no remainder in the result, the output signal is 0. If there is a remainder,
     * the output signal is 1. It also rounds down the result.
     * Once the value in register A reaches 0 (or actually 0.5 since it rounds down that to 0) the loop ends,
     * it copies the base value in register D to register A and starts dividing all over again.
     * This gives us two things:
     * 1. We no longer need an infinite loop. We need to find a signal that alternates until register A reaches the value of 0.
     * 2. We need to find a positive integer that, when divided by 2 (and rounded down when necessary),
     *      has a remainder every other time. In other words, every other division we end up with an even number and every
     *      other an odd number.
     *
     * After some testing, the lowest positive integer that matches that criteria is 2730. It takes 12 divisions for 2730 to reach 0.
     * This means that we only have to check the first 12 outputs for alternation. Or even better,
     * we only have to find the value that gets us from 4 * 643 = 2572 all the way to 2730.
     * For my input, that's 2730 - 2572 = 158. And we have our final star!
     *
     *
     *
     * NOTE! If the result is incorrect, control your input and manually insert the correct values into the two variables below.
     * The two values can be found in the beginning of your input.
     */
    function solve($input) {
        $inputOne = intval(preg_replace("/[^\d]+/", "", trim($input[1]))); // Get the first number to multiply
        $inputTwo = intval(preg_replace("/[^\d]+/", "", trim($input[2]))); // Get the second number to multiply
        
        
        
        return 2730 - ($inputOne * $inputTwo);
    }
    
    
    
    // Solve part 1
    $start = microtime(true);
    echo "Part 1: " . solve($input) . " (solved in " . (microtime(true) - $start) . " seconds)" . PHP_EOL;
    