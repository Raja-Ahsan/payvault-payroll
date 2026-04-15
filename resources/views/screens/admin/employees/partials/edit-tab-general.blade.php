<div class="col-12">
    <div class="form-group mb-3">
        <label for="company_id">Company <span class="text-danger">*</span></label>
        <select name="company_id" id="company_id" class="form-control" required>
            <option value="" disabled @selected(! old('company_id'))>Select company</option>
            @foreach ($companies ?? [] as $company)
                <option value="{{ $company->id }}" @selected((string) old('company_id', $employee->company_id) === (string) $company->id)>{{ $company->company_name }}</option>
            @endforeach
        </select>
        @if (($companies ?? collect())->isEmpty())
            <small class="text-danger d-block mt-1">No companies available. Create a company first.</small>
        @endif
    </div>
</div>
<div class="col-md-4">
    <div class="form-group mb-3">
        <label for="first_name">First Name</label>
        <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name', $employee->first_name) }}" required>
    </div>
</div>
<div class="col-md-4">
    <div class="form-group mb-3">
        <label for="middle_name">Middle Name</label>
        <input type="text" class="form-control" id="middle_name" name="middle_name" value="{{ old('middle_name', $employee->middle_name) }}">
    </div>
</div>
<div class="col-md-4">
    <div class="form-group mb-3">
        <label for="last_name">Last Name</label>
        <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name', $employee->last_name) }}" required>
    </div>
</div>
<div class="col-md-4">
    <div class="form-group mb-3">
        <label for="address_1">Address 1</label>
        <input type="text" class="form-control" id="address_1" name="address_1" value="{{ old('address_1', $employee->address_1) }}" required>
    </div>
</div>
<div class="col-md-4">
    <div class="form-group mb-3">
        <label for="address_2">Address 2</label>
        <input type="text" class="form-control" id="address_2" name="address_2" value="{{ old('address_2', $employee->address_2) }}">
    </div>
</div>
<div class="col-md-4">
    <div class="form-group mb-3">
        <label for="city">City</label>
        <input type="text" class="form-control" id="city" name="city" value="{{ old('city', $employee->city) }}" required>
    </div>
</div>
<div class="col-md-4">
    <div class="form-group mb-3">
        <label for="state">State</label>
        <select name="state_id" class="form-control" id="state_id">
            @forelse ($states ?? [] as $state)
                <option value="{{ $state->id }}" @selected((string) old('state_id', $employee->state_id ?? optional($states->firstWhere('name', 'California'))->id) === (string) $state->id)>{{ $state->name }}</option>
            @empty
                <option value="" disabled selected>No states available</option>
            @endforelse
        </select>
    </div>
</div>
<div class="col-md-4">
    <div class="form-group mb-3">
        <label for="zip_code">Zip Code</label>
        <input type="text" class="form-control" id="zip_code" name="zip_code" value="{{ old('zip_code', $employee->zip_code) }}" required>
    </div>
</div>
<div class="col-md-4">
    <div class="form-group mb-3">
        <label for="ssn">Social Security Number</label>
        <input type="text" class="form-control" id="ssn" name="ssn" value="{{ old('ssn', $employee->ssn) }}" required>
    </div>
</div>
<div class="col-md-4">
    <div class="form-group mb-3">
        <label for="dob">Date Of Birth</label>
        <input type="date" class="form-control" id="dob" name="dob" value="{{ old('dob', optional($employee->dob)->format('Y-m-d')) }}">
    </div>
</div>
<div class="col-md-4">
    <div class="form-group mb-3">
        <label for="phone">Phone</label>
        <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $employee->phone) }}">
    </div>
</div>
<div class="col-md-4">
    <div class="form-group mb-3">
        <label for="fax">Fax</label>
        <input type="text" class="form-control" id="fax" name="fax" value="{{ old('fax', $employee->fax) }}">
    </div>
</div>
<div class="col-md-4">
    <div class="form-group mb-3">
        <label for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $employee->email) }}">
    </div>
</div>
<div class="col-md-4">
    <div class="form-group mb-3">
        <label for="employee_id">Employee ID</label>
        <input type="text" class="form-control" id="employee_id" name="employee_id" value="{{ old('employee_id', $employee->employee_id) }}">
    </div>
</div>
<div class="col-12">
    <div class="form-group mb-3">
        <input type="hidden" name="inactive" value="0">
        <input name="inactive" class="form-check-input" id="inactive" type="checkbox" value="1"
            @checked(old('inactive', $employee->inactive ? '1' : '0') === '1') />
        <label class="form-check-label" for="inactive">inactive</label>
    </div>
</div>
<div class="col-12">
    <div class="form-group mb-3">
        <label for="message">Memo</label>
        <textarea name="message" class="form-control text-area" id="message">{{ old('message', $employee->message) }}</textarea>
    </div>
</div>
