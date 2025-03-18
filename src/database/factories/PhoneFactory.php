<?php

namespace Vitaliiriabyshenko\Contacts\Database\Factories;

use Vitaliiriabyshenko\Contacts\Models\Phone;
use Vitaliiriabyshenko\Contacts\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

class PhoneFactory extends Factory
{
    protected $model = Phone::class;

    public function definition(): array
    {
        return [
            'contact_id' => Contact::factory(),
            'value'      => $this->faker->unique()->phoneNumber(),
        ];
    }
}
