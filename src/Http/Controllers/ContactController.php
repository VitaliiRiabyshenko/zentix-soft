<?php

namespace Vitaliiriabyshenko\Contacts\Http\Controllers;

use Vitaliiriabyshenko\Contacts\Http\Services\ContactService;
use Vitaliiriabyshenko\Contacts\Http\Requests\Contacts\FilterRequest;
use Vitaliiriabyshenko\Contacts\Http\Requests\Contacts\ContactRequest;
use Vitaliiriabyshenko\Contacts\Http\Requests\Contacts\PhoneUniqueRequest;

class ContactController extends Controller
{
    protected ContactService $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    public function index(FilterRequest $request)
    {
        $contacts = $this->contactService->getContacts($request->validated());

        return view('contacts::index', ['contacts' => $contacts]);
    }

    public function store(ContactRequest $request)
    {
        $this->contactService->storeContact($request->validated());

        return redirect()->route('contacts.index')->with('success', 'Contact successfully created!');
    }

    public function update(ContactRequest $request, $id)
    {        
        $this->contactService->updateContact($id, $request->validated());

        return redirect()->route('contacts.index')->with('success', 'Contact successfully updated!');
    }

    public function destroy($id)
    {
        $this->contactService->deleteContact($id);

        return redirect()->route('contacts.index')->with('success', 'Contact successfully deleted!');
    }

    public function checkUniquePhone(PhoneUniqueRequest $request)
    {
        return response()->json(['success' => true]);
    }
}
