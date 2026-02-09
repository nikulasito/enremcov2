<table class="table table-striped">
    <thead>
        <tr>
            <th>No.</th>
            <th>Member ID</th>
            <th>Name</th>
            <th>Office</th>
            <th>Status</th>
            <th>Date Approved</th>
            <th>Date Inactive</th>
            <th>Date Reactive</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($members as $index => $member)
            <tr data-member-id="{{ $member->id }}">
                <td>{{ ($members->currentPage() - 1) * $members->perPage() + $index + 1 }}</td>
                <td>{{ $member->employee_ID }}</td>
                <td>{{ $member->name }}</td>
                <td>{{ $member->office }}</td>
                <td class="status">
                    <span style="color: {{ $member->status == 'Active' ? 'green' : 'red' }}">
                        {{ $member->status }}
                    </span>
                </td>
                <td>{{ $member->date_approved ? \Carbon\Carbon::parse($member->date_approved)->format('Y-m-d') : 'N/A' }}</td>
                <td>{{ $member->date_inactive ? \Carbon\Carbon::parse($member->date_inactive)->format('Y-m-d') : 'N/A' }}</td>
                <td>{{ $member->date_reactive ? \Carbon\Carbon::parse($member->date_reactive)->format('Y-m-d') : 'N/A' }}</td>
                <td>
                    <button
                        class="btn btn-primary btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#updateMemberModal"
                        data-url="{{ route('admin.edit-member', $member->id) }}">
                        Update
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center">No members found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
