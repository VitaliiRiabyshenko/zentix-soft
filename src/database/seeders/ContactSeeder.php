<?php

namespace Vitaliiriabyshenko\Contacts\Database\Seeders;

use Illuminate\Database\Seeder;
use Vitaliiriabyshenko\Contacts\Models\Contact;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        Contact::factory()
            ->count(20)
            ->create()
            ->each(function ($contact) {
                $phonesCount = rand(1, 5);
                for ($i = 0; $i < $phonesCount; $i++) {
                    $contact->phones()->create([
                        'value' => fake()->unique()->phoneNumber(),
                    ]);
                }
            });
    }
}
