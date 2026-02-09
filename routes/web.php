<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\Admin\SharesController;
use App\Http\Controllers\Admin\SavingsController;
use App\Http\Controllers\Admin\WithdrawController;
use App\Http\Controllers\Admin\LoansController;
use App\Http\Controllers\Admin\LoanPaymentController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Models\User;
use Dompdf\Dompdf;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MemberContributionsController;
use App\Http\Controllers\HomeController;

Auth::routes();



Route::post('/admin/update-remittances', function () {
    return response()->json(['hit' => true]);
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/faqs', function () {
    return view('faqs');
})->name('faqs');

Route::get('/home', function () {
    return redirect('/'); // Or point to any other valid route
})->name('home');
Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

Route::get('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

Route::post('/login', [LoginController::class, 'login'])->name('login');


// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('/dashboard', [HomeController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/dashboard', [HomeController::class, 'index'])->middleware(['auth'])->name('dashboard');

Route::middleware(['auth', IsAdmin::class])->group(function () {

    Route::get('/admin/members', [AdminController::class, 'viewMembers'])->name('admin.members');

    Route::get('/admin/members/{id}/edit', [AdminController::class, 'editMember'])->name('admin.edit-member');

    // ✅ keep only ONE update route + name
    Route::patch('/admin/members/{id}', [AdminController::class, 'updateMember'])->name('admin.update-member');

    Route::get('/admin/new-members', [AdminController::class, 'newMembers'])->name('admin.new-members');
    Route::patch('/admin/approve-member/{id}', [AdminController::class, 'approveMember'])->name('admin.approve-member');
    Route::patch('/admin/disapprove-member/{id}', [AdminController::class, 'disapproveMember'])->name('admin.disapprove-member');

    Route::delete('/admin/delete-member/{id}', [AdminController::class, 'deleteMember'])->name('admin.delete-member');
});

// Admin routes with middleware applied directly
Route::middleware(['auth', IsAdmin::class])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});

//Shares
Route::middleware(['auth', IsAdmin::class])->group(function () {
    Route::get('/admin/shares', [SharesController::class, 'controllerShares'])->name('admin.shares');
    Route::post('/admin/shares/add', [SharesController::class, 'addShares'])->name('admin.shares.add');
});
Route::middleware(['auth', IsAdmin::class])->group(function () {
    Route::get('/admin/shares', [SharesController::class, 'controllerShares'])->name('admin.shares');
    Route::post('/admin/bulk-add-shares', [SharesController::class, 'bulkAddShares'])->name('admin.bulk-add-shares');
});


//Savings
Route::middleware(['auth', IsAdmin::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Main page
        Route::get('/savings', [SavingsController::class, 'index'])->name('savings');

        // AJAX partial for live search + pagination
        Route::get('/savings/partial', [SavingsController::class, 'partial'])->name('savings.partial');

        // Bulk add
        Route::post('/bulk-add-savings', [SavingsController::class, 'bulkAddSavings'])->name('bulk-add-savings');

        // Update remittances (modal)
        Route::post('/update-saving-remittances', [SavingsController::class, 'updateSavingsRemittances'])
            ->name('update-saving-remittances');
    });

//Start Loans
Route::middleware(['auth', IsAdmin::class])->group(function () {
    Route::get('/admin/loans', [LoansController::class, 'controllerLoans'])->name('admin.loans');
    // Route::post('/admin/savings/add', [SavingsController::class, 'addSavings'])->name('admin.savings.add');
});
Route::middleware(['auth', IsAdmin::class])->group(function () {
    Route::get('/admin/savings', [SavingsController::class, 'controllerSavings'])->name('admin.savings');
    Route::post('/admin/bulk-add-savings', [SavingsController::class, 'bulkAddSavings'])->name('admin.bulk-add-savings');
});



//Withdraw
Route::middleware(['auth', IsAdmin::class])->group(function () {
    Route::get('/admin/withdraw', [\App\Http\Controllers\Admin\WithdrawController::class, 'controllerWithdraw'])->name('admin.withdraw');
    Route::post('/admin/bulk-add-withdraw', [\App\Http\Controllers\Admin\WithdrawController::class, 'bulkAddWithdraw'])->name('admin.bulk-add-withdraw');

    // optional fetch/update used by the modal
    Route::get('/admin/get-withdrawals/{employee_id}/{search}', [\App\Http\Controllers\Admin\WithdrawController::class, 'getWithdrawals']);
    Route::post('/admin/update-withdrawals', [\App\Http\Controllers\Admin\WithdrawController::class, 'updateWithdrawals']);
});

Route::get('/download/withdraw-template', [\App\Http\Controllers\Admin\WithdrawController::class, 'downloadTemplate']);
Route::post('/admin/upload-withdraw-template', [\App\Http\Controllers\Admin\WithdrawController::class, 'uploadWithdrawTemplate'])->name('withdraw.upload');

Route::middleware(['auth', IsAdmin::class])->group(function () {
    Route::get('/admin/withdraw', [WithdrawController::class, 'controllerWithdraw'])->name('admin.withdraw');
    Route::get('/admin/withdraw/partial', [WithdrawController::class, 'partial'])->name('admin.withdraw.partial');
});






Route::get('/admin/download-members', [MemberController::class, 'downloadMembers'])->name('admin.download-members');
Route::patch('/admin/loans/update/{id}', [LoansController::class, 'updateLoan'])->name('admin.update-loan');
Route::get('/admin/loans', [LoansController::class, 'controllerLoans'])->name('admin.loans');
Route::middleware(['auth', IsAdmin::class])->group(function () {
    Route::get('/admin/loans', [LoansController::class, 'index'])->name('admin.loans');
    Route::post('/admin/loans/store', [LoansController::class, 'store'])->name('admin.store-loan');
    Route::get('/admin/get-user-details/{employee_id}', [LoansController::class, 'getUserDetails']);
});
Route::patch('/admin/loans/update/{loanId}', [LoansController::class, 'updateLoan'])
    ->name('admin.loans.update');
Route::get('/admin/get-co-maker/{name}', [LoansController::class, 'getCoMakerDetails']);
Route::patch('/admin/loans/update', [LoansController::class, 'updateLoan'])->name('admin.update-loan');
Route::get('/admin/loan-payments', [LoanPaymentController::class, 'loanPayments'])->name('admin.loan-payments');
Route::post('/admin/loan-payments/store', [LoanPaymentController::class, 'storeLoanPayment'])->name('admin.store-loan-payment');
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::post('loan-payments/store-bulk', [LoanPaymentController::class, 'storeBulkLoanPayments'])
        ->name('admin.loan-payments.store-bulk');
});
Route::post('/admin/loan-payments/update', [LoanPaymentController::class, 'updateLoanPayment'])->name('admin.loan-payments.update');
Route::get('/admin/loan-payments/remittance/{remittanceNo}/{loanId}', [LoanPaymentController::class, 'getByRemittance']);
Route::get('/admin/loan-payments/view/{loanId}', [LoanPaymentController::class, 'viewLoan'])
    ->name('admin.loan-payments.view');
Route::get('/download/loans-template', [LoansController::class, 'downloadTemplate']);
Route::post('/admin/upload-loans-template', [LoansController::class, 'uploadLoanTemplate'])->name('admin.upload-loans-template');

Route::middleware(['auth', IsAdmin::class])->group(function () {
    // Include this line:
    Route::post('/admin/update-remittances', [SharesController::class, 'updateRemittances'])->name('admin.update-remittances');
    Route::get('/admin/get-savings/{employee_id}/{search}', [MemberContributionsController::class, 'getSavingsSearch']);
    Route::post('/admin/update-saving-remittances', [SavingsController::class, 'updateSavingsRemittances'])->name('admin.update-saving-remittances');

});

Route::middleware(['auth', IsAdmin::class])->group(function () {
    Route::get('/admin/shares', [SharesController::class, 'controllerShares'])->name('admin.shares');
    Route::get('/admin/shares/partial', [SharesController::class, 'partial'])->name('admin.shares.partial');
});



//End Loans



//Member Contributions
Route::get('/member/contributions', [MemberContributionsController::class, 'index'])
    ->middleware(['auth']) // Ensures only logged-in users can access
    ->name('member.contributions');

Route::get('/admin/get-contributions/{employeeID}/{year}', [MemberContributionsController::class, 'getContributions']);
Route::get('/admin/get-savings/{employee_id}/{year}', [MemberContributionsController::class, 'getSavings']);



Route::get('/download/shares-template', [SharesController::class, 'downloadTemplate']);
Route::post('/upload/shares-template', [SharesController::class, 'uploadTemplate']);

Route::post('/admin/upload-shares-template', [SharesController::class, 'uploadSharesTemplate'])->name('shares.upload');

Route::get('/download/savings-template', [SavingsController::class, 'downloadTemplate']);
Route::post('/upload/savings-template', [SavingsController::class, 'uploadTemplate']);

Route::post('/admin/upload-savings-template', [SavingsController::class, 'uploadSavingsTemplate'])->name('savings.upload');




// Route::patch('/admin/members/update/{id}', [AdminController::class, 'updateMemberStatus'])
//     ->name('admin.update-member')->middleware('auth');


//Route for Ajax Pages
// Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
// Route::get('/admin/new-members', [AdminController::class, 'newMembers']);
// Route::get('/admin/view-members', [AdminController::class, 'viewMembers']);
//Route::get('/admin/shares', [AdminController::class, 'shares']);
//Route::get('/admin/shares', [SharesController::class, 'index'])->name('admin.shares');
// Route::get('/admin/savings', [AdminController::class, 'viewSavings'])->name('admin.savings');
// Route::get('/admin/withdrawals', [AdminController::class, 'viewWithdrawals'])->name('admin.withdrawals');

// Route::get('/admin/savings', [AdminController::class, 'savings']);
// Route::get('/admin/loans', [AdminController::class, 'loans']);


Route::get('/admin/new-members', [AdminController::class, 'newMembers'])->name('admin.new-members');
Route::patch('/admin/approve-member/{id}', [AdminController::class, 'approveMember'])->name('admin.approve-member');

//Route::get('/admin/edit-member/{id}', [AdminController::class, 'editMember'])->name('admin.edit-member');
Route::get('/admin/members/{id}/edit', [AdminController::class, 'editMember'])->name('admin.edit-member');

Route::delete('/admin/delete-member/{id}', [AdminController::class, 'deleteMember'])->name('admin.delete-member');

Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');

Route::middleware(['guest'])->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
});


// return redirect()->route('register')->with('registered', true);


// Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');
Route::get('/user/preview/{id}', [RegisteredUserController::class, 'preview'])->name('user.preview');


// Route::get('/profile', [ProfileController::class, 'show'])->middleware(['auth', 'verified'])->name('profile');
Route::get('/profile', [ProfileController::class, 'show'])->middleware(['auth'])->name('profile');

Route::get('/member/profile', [MemberController::class, 'show'])
    ->middleware(['auth'])
    ->name('member.profile');
Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');

Route::get('/profile/changepassword', function () {
    return view('profile.changepassword');
})->middleware(['auth'])->name('password.edit');

Route::put('/profile/changepassword', [ProfileController::class, 'changepassword'])
    ->middleware(['auth'])
    ->name('password.update');


// Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
//     ->middleware('guest')
//     ->name('password.request');

// Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
//     ->middleware('guest')
//     ->name('password.email');



// Show "Forgot Password" page
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->middleware('guest')
    ->name('password.request');

// Handle form submission to send reset email
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.update');


Route::match(['PUT', 'PATCH'], '/profile/update', [ProfileController::class, 'update'])->name('profile.update');

Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

Route::put('admin/inactivate-member/{id}', [AdminController::class, 'inactivateMember'])->name('admin.inactivate-member');
Route::put('/admin/mark-as-active/{id}', [AdminController::class, 'markAsActive'])->name('admin.mark-as-active');



// Add this route for verification
Route::get('/verify-email/{token}', [VerificationController::class, 'verifyEmail'])->name('email.verify');

Route::get('/verify-email-info', function () {
    return view('auth.verify_modal');
});


Route::patch('/admin/disapprove-member/{id}', [AdminController::class, 'disapproveMember'])->name('admin.disapprove-member');



// Email verification

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/dashboard'); // Or any desired page
})->middleware(['auth', 'signed'])->name('verification.verify');


Route::get('/user/pdf/{id}', [RegisteredUserController::class, 'generatePdf'])->name('user.pdf');

Route::get('/admin/get-user-details/{id}', [AdminController::class, 'getUserDetails']);




require __DIR__ . '/auth.php';

