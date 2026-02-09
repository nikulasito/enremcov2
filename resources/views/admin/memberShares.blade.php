<x-admin-layout>
    <h2>View Members</h2>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <!-- Search Field and Filters -->
    <div class="mb-4 d-flex align-items-center justify-content-between">
        <input
            type="text"
            id="searchInput"
            class="form-control w-50"
            placeholder="Search members..."
        />

        <div class="mt-3">
            <select id="statusFilter" class="form-select">
                <option value="">Filter by Status</option>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
        </div>

        <div class="mt-3">
            <select id="officeFilter" class="form-select">
                <option value="">Filter by Office</option>
                <!-- Office options will be populated dynamically -->
            </select>
        </div>

        <div class="mt-3">
            <button id="clearFilters" class="btn btn-secondary">Clear Filters</button>
        </div>
    </div>

    <!-- Members Table -->
    <div id="membersTable">
        <table class="table table-striped">
            <thead>
                <tr>
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
                @foreach($members as $member)
                    <tr>
                        <td>{{ $member->name }}</td>
                        <td>{{ $member->office }}</td>
                        <td>
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
                            <!-- @if($member->status == 'Active')
                                <form action="{{ route('admin.inactivate-member', $member->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-warning btn-sm">Mark as Inactive</button>
                                </form>
                            @else
                                <form action="{{ route('admin.mark-as-active', $member->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success btn-sm">Mark as Active</button>
                                </form>
                            @endif -->
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

<!--MODAL-->
<div class="modal fade" id="updateMemberModal" tabindex="-1" aria-labelledby="updateMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h5 class="modal-title m-0 font-weight-bold text-primary" id="updateMemberModalLabel">Update Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="updateMemberModalBody">
                <p>Loading...</p>
            </div>
        </div>
    </div>
</div>
<script>


//fetch content dynamically
document.addEventListener('DOMContentLoaded', () => {
        const updateModal = document.getElementById('updateMemberModal');

        updateModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget; // Button that triggered the modal
            const url = button.getAttribute('data-url'); // URL for the update form
            const modalBody = document.getElementById('updateMemberModalBody');

            // Show loading state
            modalBody.innerHTML = '<p>Loading...</p>';

            // Fetch the content from the server
            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(html => {
                    modalBody.innerHTML = html; // Insert the fetched form into the modal body
                })
                .catch(error => {
                    modalBody.innerHTML = `<p class="text-danger">Error loading content: ${error.message}</p>`;
                });
        });
    });









    let members = @json($members); // Get members data from backend

// Populate Office Filter Based on Selected Status
function populateOfficeFilter(selectedStatus = "") {
    const officeFilter = document.getElementById("officeFilter");
    officeFilter.innerHTML = '<option value="">Filter by Office</option>'; // Reset options

    // Get unique offices from members whose status matches the selected one
    const offices = [...new Set(
        members
            .filter(member => !selectedStatus || member.status === selectedStatus)
            .map(member => member.office)
    )];

    // Populate office filter dropdown
    offices.forEach(office => {
        const option = document.createElement("option");
        option.value = office;
        option.textContent = office;
        officeFilter.appendChild(option);
    });
}

// Filter and Display Members Based on Status and Office
function filterAndDisplay() {
    const query = document.getElementById("searchInput").value.toLowerCase();
    const statusFilter = document.getElementById("statusFilter").value;
    const officeFilter = document.getElementById("officeFilter").value;

    const filteredMembers = members.filter(member => {
        const matchesQuery = 
            member.name.toLowerCase().includes(query) ||
            (member.email && member.email.toLowerCase().includes(query));

        const matchesStatus = !statusFilter || member.status === statusFilter;
        const matchesOffice = !officeFilter || member.office === officeFilter;

        return matchesQuery && matchesStatus && matchesOffice;
    });

    const tbody = document.querySelector("#membersTable tbody");
    tbody.innerHTML = "";

        const formatDate = (date) => {
        if (!date) return 'N/A';
        const options = { year: 'numeric', month: '2-digit', day: '2-digit' };
        return new Intl.DateTimeFormat('en-US', options).format(new Date(date));
    };

    filteredMembers.forEach(member => {
        const row = `
            <tr>
                <td>${member.name}</td>
                <td>${member.office}</td>
                <td>
                    <span style="color: ${member.status === "Active" ? "green" : "red"}">
                        ${member.status}
                    </span>
                </td>
                <td>${formatDate(member.date_approved)}</td>
                <td>${formatDate(member.date_inactive)}</td>
                <td>${formatDate(member.date_reactive)}</td>
                <td>
                    <button class="btn btn-primary btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#updateMemberModal"
                        data-url="{{ route('admin.edit-member', $member->id) }}">
                        Update
                    </button>
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
}

// Reset Filters and Reload Members
document.getElementById("clearFilters").addEventListener("click", function () {
    document.getElementById("searchInput").value = "";
    document.getElementById("statusFilter").value = "";
    document.getElementById("officeFilter").value = "";
    populateOfficeFilter();
    filterAndDisplay();
});

// Event Listeners for Filters
document.getElementById("searchInput").addEventListener("input", filterAndDisplay);
document.getElementById("statusFilter").addEventListener("change", function() {
    populateOfficeFilter(this.value); // Update office dropdown when status is selected
    filterAndDisplay(); // Update table
});
document.getElementById("officeFilter").addEventListener("change", filterAndDisplay);

// Populate Office Filter on Page Load
populateOfficeFilter();

</script>
</x-admin-layout>
