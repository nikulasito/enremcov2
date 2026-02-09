<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">📌 My Details</div>
            <div class="card-body">
                <p><strong>SS Number Status:</strong> Active</p>
                <p><strong>Document Compliance:</strong> Submitted</p>
                <p><strong>Membership Status:</strong> Permanent</p>
                <p><strong>Prior Registration:</strong> No</p>
                <p><strong>Date of SS Number Issuance:</strong> -</p>
                <p><strong>Sex:</strong> {{ auth()->user()->sex }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">🏠 Address</div>
            <div class="card-body">
                <p><strong>Local Home Address:</strong> {{ auth()->user()->address }}</p>
                <p><strong>Local Mailing Address:</strong> {{ auth()->user()->address }}</p>
                <p><strong>Foreign Home Address:</strong> -</p>
            </div>
        </div>
    </div>
</div>
