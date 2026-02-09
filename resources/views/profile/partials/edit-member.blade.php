<x-admin-layout>
    <h2>Update Member</h2>

    <form method="POST" action="{{ route('admin.update-member', $member->id) }}">
        @csrf
        @method('PATCH')

        <div class="mb-3 member-details">
            <div class="profile-container">
                <div class="row">
                    <div class="profile-left col-md-auto">
                        <div class="profile-photo">
                            <img src="{{ asset('storage/' . $member->photo) }}" alt="User Photo">
                        </div>
                    </div>
                    <div class="profile-right col-5">
                        <div class="personal-data">
                            <h4>Personal Data</h4>
                            <ul>
                                <li><strong>Name:</strong> {{ $member->name }}</li>
                                <li><strong>Email:</strong> {{ $member->email }}</li>
                                <li><strong>Position:</strong> {{ $member->position }}</li>
                                <li><strong>Address:</strong> {{ $member->address }}</li>
                                <li><strong>Sex:</strong> {{ $member->sex }}</li>
                                <li><strong>Marital Status:</strong> {{ $member->marital_status }}</li>
                                <li><strong>Office:</strong> {{ $member->office }}</li>
                                <li><strong>Contact No.:</strong> {{ $member->contact_no }}</li>
                                <li><strong>Annual Income:</strong> {{ $member->annual_income }}</li>
                                <li><strong>Beneficiary/ies:</strong> {{ $member->beneficiaries }}</li>
                                <li><strong>Birthdate:</strong> {{ $member->birthdate }}</li>
                                <li><strong>Educational Attainment:</strong> {{ $member->education }}</li>
                                <li><strong>Empoyee ID:</strong> {{ $member->employee_ID }}</li>
                                <li><strong>Membership Date:</strong> {{ $member->membership_date }}</li>
                                <li><strong>Username:</strong> {{ $member->username }}</li>
                                <li><strong>Status:</strong>
                                    <span
                                        class="font-semibold {{ auth()->user()->status === 'Active' ? 'text-green-500' : 'text-red-500' }}">
                                        {{ auth()->user()->status }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="contribution-details col">
                        <h4>Contribution Details</h4>
                        <ul>
                            <li><strong>Shares:</strong> {{ $member->shares }}</li>
                            <li><strong>Savings:</strong> {{ $member->savings }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="shares" class="form-label">Shares</label>
                <input type="number" name="shares" class="form-control" id="shares" value="{{ $member->shares }}">
            </div>

            <div class="mb-3">
                <label for="savings" class="form-label">Savings</label>
                <input type="number" name="savings" class="form-control" id="savings" value="{{ $member->savings }}">
            </div>

            <div class="mb-3">
                <label for="membership_date" class="form-label">Date of Membership</label>
                <input type="date" name="membership_date" class="form-control" id="membership_date"
                    value="{{ $member->membership_date ?? '' }}">
            </div>

            <!-- Submit button to save changes -->
            <button type="submit" class="btn btn-primary">Update</button>

            <!-- Cancel button to go back to the members page -->
            <a href="{{ route('admin.members') }}" class="btn btn-secondary">Cancel</a>
    </form>
</x-admin-layout>