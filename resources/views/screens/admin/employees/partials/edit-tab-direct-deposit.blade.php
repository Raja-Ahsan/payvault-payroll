@php
    $detail = $employee->detail;
@endphp
<div class="col-12">
    <div class="toggle-input-wrapper">
        <input type="checkbox"
            name="include_in_direct_deposit"
            value="1"
            class="form-check-input toggle-checkbox mt-0"
            id="include_in_direct_deposit"
            @checked(old('include_in_direct_deposit', $detail?->include_in_direct_deposit))>
        <label for="include_in_direct_deposit">Include In Direct Deposit Process</label>

        <fieldset {{ old('include_in_direct_deposit', $detail?->include_in_direct_deposit) ? '' : 'disabled' }} class="toggle-input border border-secondary rounded-3 px-3 pb-3 mb-3 col-12">
            <legend class="float-none w-auto px-2 mb-2 fs-6">Banking Information</legend>
            <div class="row">
                <div class="col-12 mb-3">
                    <label for="account_type">Account Type</label>
                    <select name="account_type" class="form-control" id="account_type">
                        <option value="checking" @selected(old('account_type', $detail?->account_type ?? 'checking') === 'checking')>Checking</option>
                        <option value="savings" @selected(old('account_type', $detail?->account_type ?? 'checking') === 'savings')>Savings</option>
                    </select>
                </div>
                <div class="col-12 mb-3">
                    <label for="bank_routing_number">Bank Routing Number</label>
                    <input type="text"
                        name="bank_routing_number"
                        id="bank_routing_number"
                        class="form-control"
                        value="{{ old('bank_routing_number', $detail?->bank_routing_number) }}"
                        inputmode="numeric"
                        autocomplete="off"
                        minlength="9"
                        maxlength="9"
                        pattern="[0-9]{9}"
                        title="Enter all 9 digits of the routing number"
                        data-minlength-message="Routing number must be 9 digits"
                        oninput="this.value = this.value.replace(/\D/g, '').slice(0, 9)">
                </div>
                <div class="col-12 mb-3">
                    <label for="account_number">Account Number</label>
                    <input type="text"
                        name="account_number"
                        id="account_number"
                        class="form-control"
                        value="{{ old('account_number', $detail?->account_number) }}"
                        minlength="23"
                        maxlength="23"
                        inputmode="numeric"
                        pattern="[0-9]{23}"
                        data-minlength-message="Account number must be 23 digits"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>
            </div>
        </fieldset>
    </div>
</div>
