<?php

namespace App\helpers;

class Helpers {
    public function get_function_fake_generator(string $type)
    {
        $typesRelated = [
            "first name" => fake()->firstName(),
            "phone number" => fake()->phoneNumber(),
            "number" => fake()->randomDigit(),
            "address" => fake()->address()
        ];

        return $typesRelated[$type];
    }

}
