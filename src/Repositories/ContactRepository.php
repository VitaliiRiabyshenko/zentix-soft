<?php

namespace Vitaliiriabyshenko\Contacts\Repositories;

use Vitaliiriabyshenko\Contacts\Models\Contact;

class ContactRepository implements ContactRepositoryInterface
{
    public function getContacts(?array $data)
    {
        return Contact::with('phones')->paginate($data['per_page'] ?? 10, ['*'], 'page', $data['page'] ?? 1);
    }

    public function storeContact(array $data)
    {
        $contact = Contact::create([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
        ]);

        if (isset($data['phones']) && $data['phones']) {
            foreach ($data['phones'] as $phone) {
                $contact->phones()->create(['value' => $phone['value']]);
            }
        }

        return $contact;
    }

    public function updateContact(int $id, array $data)
    {
        $contact = Contact::findOrFail($id);

        $contact->update([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
        ]);

        $oldPhoneIds = $contact->phones()->pluck('id')->toArray();
        $newPhoneIds = collect($data['phones'])->pluck('id')->filter()->toArray();

        $phonesToDelete = array_diff($oldPhoneIds, $newPhoneIds);
        if ($phonesToDelete) {
            $contact->phones()->whereIn('id', $phonesToDelete)->delete();
        }

        foreach ($data['phones'] as $phone) {
            if (isset($phone['id'])) {
                $contact->phones()->where('id', $phone['id'])->update([
                    'value' => $phone['value'],
                ]);
            } else {
                $contact->phones()->create([
                    'value' => $phone['value'],
                ]);
            }
        }
    }

    public function deleteContact(int $id)
    {
        $contact = Contact::findOrFail($id);
        $contact->phones()->delete();
        return $contact->delete();
    }
}
