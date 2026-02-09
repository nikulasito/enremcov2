<x-admin-layout>
    <h2>Update Member</h2>

    <form method="POST" action="{{ route('admin.update-member', $member->id) }}">
        @csrf
        @method('PATCH')

        <div class="mb-3 member-name">
            <label for="name" class="form-label">Name: </label>
            <span>{{ $member->name }}</span>
        </div>

        <div class="mb-3">
            <label for="shares" class="form-label">Shares</label>
            <input type="number" name="shares" class="form-control" id="shares" value="{{ $member->shares }}" step="any" min="0" required>
        </div>

        <div class="mb-3">
            <label for="savings" class="form-label">Savings</label>
            <input type="number" name="savings" class="form-control" id="savings" value="{{ $member->savings }}" step="any" min="0" required>
        </div>

        <div class="mb-3">
            <label for="membership_date" class="form-label">Date of Membership</label>
            <input type="date" name="membership_date" class="form-control" id="membership_date" value="{{ $member->membership_date ?? '' }}" required>
        </div>

        <!-- Submit button to save changes -->
        <button type="submit" class="btn btn-primary">Update</button>

        <!-- Cancel button to go back to the members page -->
        <a href="{{ route('admin.members') }}" class="btn btn-secondary">Cancel</a>
    </form>
</x-admin-layout>
