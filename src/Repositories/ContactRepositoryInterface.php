<?php

namespace Vitaliiriabyshenko\Contacts\Repositories;

interface ContactRepositoryInterface
{
    public function getContacts(array|null $data);

    public function storeContact(array $data);

    public function updateContact(int $id, array $data);

    public function deleteContact(int $id);
}
