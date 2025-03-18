<?php

namespace Vitaliiriabyshenko\Contacts\Http\Services;

use Vitaliiriabyshenko\Contacts\Repositories\ContactRepositoryInterface;

class ContactService
{
    protected ContactRepositoryInterface $repository;

    public function __construct(ContactRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getContacts(?array $data)
    {
        return $this->repository->getContacts($data);
    }

    public function storeContact(array $data)
    {
        return $this->repository->storeContact($data);
    }

    public function updateContact(int $id, array $data)
    {
        return $this->repository->updateContact($id, $data);
    }

    public function deleteContact(int $id)
    {
        return $this->repository->deleteContact($id);
    }
}