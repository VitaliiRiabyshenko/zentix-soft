@extends('contacts::layouts.app')

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @php
        $phones = old('phones', [['value' => '']]);
    @endphp

    <div class="card mb-4">
        <div class="card-body">
            <h4 id="form-title">Add New Contact</h4>
            <form id="contact-form" action="{{ route('contacts.store') }}" method="POST">
                @csrf
                <input type="hidden" name="contact_id" id="contact_id">

                <div class="row mb-3">
                    <div class="col-md-4 mb-2 mb-md-0">
                        <input type="text" name="first_name" id="first_name"
                            class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}"
                            placeholder="First Name" required>
                        @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="last_name" id="last_name"
                            class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}"
                            placeholder="Last Name" required>
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div id="phones-wrapper">
                    @foreach ($phones as $index => $phone)
                        <div class="row mb-2 phone-input">
                            <div class="col-md-8 mb-2 mb-md-0">
                                <input type="hidden" name="phones[{{ $index }}][id]"
                                    value="{{ $phone['id'] ?? '' }}">
                                <input type="text" name="phones[{{ $index }}][value]"
                                    class="form-control phone-field @error('phones.' . $index . '.value') is-invalid @enderror"
                                    placeholder="+123..." value="{{ $phone['value'] ?? '' }}" required>
                                @error('phones.' . $index . '.value')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 d-flex gap-1">
                                <button type="button" class="btn btn-danger remove-phone">-</button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="button" class="btn btn-secondary add-phone mb-3">+ Add Phone</button>

                <div>
                    <button type="submit" class="btn btn-primary" id="submit-btn">Add Contact</button>
                    <button type="button" class="btn btn-secondary d-none" id="cancel-btn">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <table class="table table-bordered table-striped mt-4">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Phones</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($contacts as $contact)
                <tr>
                    <td>{{ $contact->id }}</td>
                    <td>{{ $contact->first_name }}</td>
                    <td>{{ $contact->last_name }}</td>
                    <td>{{ $contact->phones->pluck('value')->implode(', ') }}</td>
                    <td>
                        <button type="button" class="btn btn-primary btn-sm edit-contact"
                            data-contact='@json($contact)'>
                            Edit
                        </button>
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                            data-bs-target="#deleteModal-{{ $contact->id }}">
                            Delete
                        </button>
                        <div class="modal fade" id="deleteModal-{{ $contact->id }}" tabindex="-1"
                            aria-labelledby="deleteModalLabel-{{ $contact->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Confirm Delete</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete contact <strong>{{ $contact->first_name }}
                                            {{ $contact->last_name }}</strong>?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <form action="{{ route('contacts.destroy', $contact->id) }}" method="POST"
                                            style="display:inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No contacts found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {!! $contacts->links() !!}
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/imask"></script>
    <script src="https://cdn.jsdelivr.net/npm/libphonenumber-js@1.10.23/bundle/libphonenumber-max.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const phonesWrapper = document.getElementById('phones-wrapper');
            const addPhoneBtn = document.querySelector('.add-phone');
            const cancelBtn = document.getElementById('cancel-btn');
            const submitBtn = document.getElementById('submit-btn');
            const formTitle = document.getElementById('form-title');
            const form = document.getElementById('contact-form');
            let phoneIndex = {{ count($phones) }};

            const applyMask = (input) => {
                IMask(input, {
                    mask: '+{0}000000000000000',
                    lazy: true,
                    overwrite: true
                });
            };

            document.querySelectorAll('.phone-field').forEach(input => {
                applyMask(input);
            });

            addPhoneBtn.addEventListener('click', function() {
                const phoneInput = document.createElement('div');
                phoneInput.classList.add('row', 'mb-2', 'phone-input');
                phoneInput.innerHTML = `
                    <div class="col-md-8 mb-2 mb-md-0">
                        <input type="hidden" name="phones[${phoneIndex}][id]" value="">
                        <input type="text" name="phones[${phoneIndex}][value]" class="form-control phone-field" placeholder="+123..." required>
                    </div>
                    <div class="col-md-4 d-flex gap-1">
                        <button type="button" class="btn btn-danger remove-phone">-</button>
                    </div>
                `;
                phonesWrapper.appendChild(phoneInput);
                applyMask(phoneInput.querySelector('.phone-field'));
                phoneIndex++;
            });

            phonesWrapper.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-phone')) {
                    const phoneInputs = document.querySelectorAll('.phone-input');
                    if (phoneInputs.length > 1) {
                        e.target.closest('.phone-input').remove();
                    }
                }
            });

            const clearClientErrors = () => {
                document.querySelectorAll('.invalid-feedback-js').forEach(el => el.remove());
                document.querySelectorAll('.phone-field').forEach(input => {
                    if (!input.closest('.phone-input').querySelector('.invalid-feedback')) {
                        input.classList.remove('is-invalid');
                    }
                });
            };

            form.addEventListener('submit', function(e) {
                clearClientErrors();
                const phoneInputs = document.querySelectorAll('.phone-field');
                let isValid = true;

                phoneInputs.forEach(input => {
                    const parent = input.closest('.phone-input');
                    let cleanedValue = input.value.replace(/[^+\d]/g, '');

                    if (cleanedValue.length < 4) {
                        if (!parent.querySelector('.invalid-feedback')) {
                            input.classList.add('is-invalid');
                            const errorMsg = document.createElement('div');
                            errorMsg.classList.add('invalid-feedback-js', 'text-danger', 'mt-1');
                            errorMsg.innerText = 'Phone number is too short';
                            parent.appendChild(errorMsg);
                        }
                        isValid = false;
                        return;
                    }

                    try {
                        const phoneNumber = libphonenumber.parsePhoneNumberFromString(cleanedValue);
                        if (!phoneNumber || !phoneNumber.isValid()) {
                            throw new Error('Invalid');
                        }
                        input.value = cleanedValue;
                    } catch (err) {
                        if (!parent.querySelector('.invalid-feedback')) {
                            input.classList.add('is-invalid');
                            const errorMsg = document.createElement('div');
                            errorMsg.classList.add('invalid-feedback-js', 'text-danger', 'mt-1');
                            errorMsg.innerText = 'Invalid international phone number';
                            parent.appendChild(errorMsg);
                        }
                        isValid = false;
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                }
            });

            document.querySelectorAll('.edit-contact').forEach(button => {
                button.addEventListener('click', function() {
                    const contact = JSON.parse(this.getAttribute('data-contact'));
                    formTitle.innerText = 'Edit Contact #' + contact.id;
                    form.action = '/contacts/' + contact.id;

                    if (!document.getElementById('_method')) {
                        const method = document.createElement('input');
                        method.type = 'hidden';
                        method.name = '_method';
                        method.id = '_method';
                        method.value = 'PUT';
                        form.prepend(method);
                    }

                    document.getElementById('first_name').value = contact.first_name;
                    document.getElementById('last_name').value = contact.last_name;

                    phonesWrapper.innerHTML = '';
                    contact.phones.forEach((phone, idx) => {
                        const phoneInput = document.createElement('div');
                        phoneInput.classList.add('row', 'mb-2', 'phone-input');
                        phoneInput.innerHTML = `
                            <div class="col-md-8 mb-2 mb-md-0">
                                <input type="hidden" name="phones[${idx}][id]" value="${phone.id}">
                                <input type="text" name="phones[${idx}][value]" class="form-control phone-field" value="${phone.value}" placeholder="+123..." required>
                            </div>
                            <div class="col-md-4 d-flex gap-1">
                                <button type="button" class="btn btn-danger remove-phone">-</button>
                            </div>
                        `;
                        phonesWrapper.appendChild(phoneInput);
                        applyMask(phoneInput.querySelector('.phone-field'));
                    });

                    submitBtn.innerText = 'Update Contact';
                    cancelBtn.classList.remove('d-none');
                });
            });

            cancelBtn.addEventListener('click', function() {
                formTitle.innerText = 'Add New Contact';
                form.action = '{{ route('contacts.store') }}';
                const methodInput = document.getElementById('_method');
                if (methodInput) {
                    methodInput.remove();
                }
                form.reset();
                phonesWrapper.innerHTML = '';
                phoneIndex = 0;
                addPhoneBtn.click();
                submitBtn.innerText = 'Add Contact';
                this.classList.add('d-none');
            });

            if (document.querySelector('.alert-success')) {
                cancelBtn.click();
                setTimeout(() => {
                    document.querySelector('.alert-success').style.display = 'none';
                }, 3000);
            }

            phonesWrapper.addEventListener('input', function(e) {
                if (e.target.classList.contains('phone-field')) {
                    const input = e.target;
                    const parent = input.closest('.phone-input');
                    const cleanedValue = input.value.replace(/[^+\d]/g, '');

                    parent.querySelectorAll('.invalid-feedback-unique').forEach(el => el.remove());
                    input.classList.remove('is-invalid');

                    if (cleanedValue.length >= 7) {
                        const phoneId = parent.querySelector('input[name$="[id]"]')?.value || null;

                        fetch('{{ route('phone-unique') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    phone: cleanedValue,
                                    phone_id: phoneId
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                parent.querySelectorAll('.invalid-feedback-unique').forEach(el => el
                                    .remove());
                                input.classList.remove('is-invalid');

                                if (!data.success) {
                                    input.classList.add('is-invalid');
                                    const errorMsg = document.createElement('div');
                                    errorMsg.classList.add('invalid-feedback-unique', 'text-danger',
                                        'mt-1');
                                    errorMsg.innerText = data.message ||
                                        'This phone number is already taken';
                                    parent.appendChild(errorMsg);
                                }
                            })
                            .catch(() => {
                            });
                    }
                }
            });
        });
    </script>
@endpush
