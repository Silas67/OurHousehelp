<?php

use App\Http\Controllers\PaymentController;

use App\Http\Controllers\ExtraDayCostController;

use App\Http\Controllers\RegistrationPaymentController;

use App\Http\Controllers\ExtraServiceCostController;

use App\Http\Controllers\PagesController;

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ApartmentOrderController;

use App\Http\Controllers\ContactMessageController;

use App\Http\Controllers\ServiceController;

use App\Http\Controllers\PackageController;

use App\Http\Controllers\OneTimeServiceController;

use App\Http\Controllers\LiveInServiceController;

use App\Http\Controllers\ApartmentsController;

use App\Http\Controllers\AdminController;

use App\Http\Controllers\DiscountController;

use App\Http\Controllers\UserController;

use App\Http\Controllers\ApplicantController;

use App\Http\Controllers\LiveInServiceRequestController;

use App\Http\Controllers\RatingController;

use App\Http\Controllers\ConfirmationController;

use App\Http\Controllers\CouponController;

use App\Http\Controllers\FaqController;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Http;

use App\Http\Controllers\GuarantorController;

use App\Http\Controllers\Guarantor2Controller;

use App\Http\Controllers\LiveOutPaymentController;

use App\Http\Controllers\PaymentPlanController;

use App\Http\Controllers\PaymentPlanSkillController;

use App\Http\Controllers\MultipleServiceController;

use App\Http\Controllers\RequestClickController;

use App\Http\Controllers\ServiceChargeController;

use App\Http\Controllers\SettingController;

use App\Http\Controllers\SkillController;

use App\Http\Controllers\ServiceTypeController;

use App\Http\Controllers\NotificationController;

use App\Http\Controllers\LiveOutServiceRequestController;

use App\Models\Service;

use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;

use App\Http\Controllers\NewPasswordController;

use App\Http\Controllers\ServiceRequestController;

use App\Http\Controllers\BookingController;

Route::get('/clear-cache', function () {
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    return 'All caches cleared!';
});

Route::get('/payments/paymentoption', [PaymentController::class, 'paymentoption'])->name('payments.paymentoption');

Route::get('/payments/paymentoptionskilledworkers', [PaymentController::class, 'paymentoptionskilledworkers'])->name('payments.paymentoptionskilledworkers');





Route::get('/admin/adminregister', [AdminController::class, 'adminregister'])->name('admin.register');

Route::post('/admin/adminregister', [AdminController::class, 'storeAdmin'])->name('admin.store');





Route::put('/profile/update', [UserController::class, 'update'])->name('profile.update');



Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])

    ->name('password.reset');



Route::post('reset-password', [NewPasswordController::class, 'store'])

    ->name('password.update');



Route::middleware(['admin', 'auth'])->group(function () {

    // admin section .......................................................................

    Route::get('/admin/cost-calculator', [AdminController::class, 'costCalculator'])->name('admin.cost-calculator');





    Route::get('/faq/index', [FaqController::class, 'index'])->name('admin.faqs.index');

    Route::post('/faq/store', [FaqController::class, 'store'])->name('admin.faqs.store');

    Route::get('/faq/{id}', [FaqController::class, 'show'])->name('admin.faqs.show');

    Route::put('/faq/{id}', [FaqController::class, 'update'])->name('admin.faqs.update');

    Route::delete('/faq/{id}', [FaqController::class, 'destroy'])->name('admin.faqs.destroy');





    Route::delete('/admin/payment-plans/{id}', [PaymentPlanController::class, 'paymentplansdestroy'])->name('paymentplan.paymentplansdestroy')->middleware('auth');

    Route::post('/admin/payment-plans', [PaymentPlanController::class, 'paymentplanstore'])->name('admin.payment_plans.paymentplanstore');

    Route::post('/admin/update-payment-plans/{id}', [PaymentPlanController::class, 'paymentplanupdate'])->name('admin.payment_plans.paymentplanupdate');

    Route::get('/admin/allpaymentplan', [PaymentPlanController::class, 'allpaymentplan'])->name('payments.allpaymentplan');

    Route::get('/admin/paymentplan', [PaymentPlanController::class, 'paymentplan'])->name('payments.paymentplan');



    Route::get('/admin/allpayments', [AdminController::class, 'allpayments'])->name('payments.allpayments');



    Route::get('/admin/paymentplanskills', [PaymentPlanSkillController::class, 'paymentplanskills'])->name('payments.paymentplanskills');

    Route::get('/admin/allpaymentplanskills', [PaymentPlanSkillController::class, 'allpaymentplanskills'])->name('payments.allpaymentplanskills');

    Route::post('/admin/payment-plans-skills', [PaymentPlanSkillController::class, 'paymentplanstoreskills'])->name('admin.payment_plans.paymentplanstoreskills');

    Route::post('/admin/update-payment-plans-skill/{id}', [PaymentPlanSkillController::class, 'paymentplanupdateskills'])->name('admin.payment_plans.paymentplanupdateskill');

    Route::delete('/admin/payment-plans-skills/{id}', [PaymentPlanSkillController::class, 'paymentplanDestroyskills'])->name('paymentplan.paymentplanDestroyskills')->middleware('auth');







    Route::post('/admin/create-househelp', [AdminController::class, 'createHouseHelp'])->name('admin.create.househelp')->middleware('auth');



    Route::get('/admin/newregistration', [AdminController::class, 'newregistration'])->name('newregistration')->middleware('auth');

    Route::get('/admin/admindashboard', [AdminController::class, 'admindashboard'])->name('admindashboard')->middleware('auth');

    Route::get('/admin/orders/search', [ServiceController::class, 'searchOrders']);





    Route::get('/admin/orderAdminDetail/{id}', [AdminController::class, 'orderAdminDetail'])->name('orderAdminDetail')->middleware('auth');

    Route::get('/admin/pendingOrder', [AdminController::class, 'pendingOrder'])->name('pendingOrder')->middleware('auth');

    Route::get('/admin/confirmedOrder', [AdminController::class, 'confirmedOrder'])->name('confirmedOrder')->middleware('auth');

    Route::get('/admin/activeOrder', [AdminController::class, 'activeOrder'])->name('activeOrder')->middleware('auth');

    Route::get('/admin/allOrders', [AdminController::class, 'allOrders'])->name('allOrders')->middleware('auth');

    Route::delete('/admin/order/{id}', [AdminController::class, 'deleteOrder'])->name('deleteOrder')->middleware('auth');



    // confirmation pending cancel ................................

    Route::get('/confirmation/{id}', [ConfirmationController::class, 'show'])->name('confirmation.show')->middleware('auth');

    Route::post('/admin/confirmation/{confirmation}/update', [ConfirmationController::class, 'updateSwitch'])->name('confirmation.updateSwitch')->middleware('auth');

    Route::post('/admin/service-request/{serviceRequest}/update-status', [ServiceController::class, 'updateStatus'])->middleware('auth');



    // registration

    // Route::get('payments', [RegistrationPaymentController::class, 'registrationPayments'])->name('admin.payments.registrationPayments');

    // Route::get('payments/{id}', [RegistrationPaymentController::class, 'showRegistrationPayments'])->name('admin.payments.showRegistrationPayments');

    // Route::post('payments/{id}/complete', [RegistrationPaymentController::class, 'markCompleted'])->name('admin.payments.complete');

    // Route::post('payments/{id}/fail', [RegistrationPaymentController::class, 'markFailed'])->name('admin.payments.fail');

    // Route::post('payments/{id}/refund', [RegistrationPaymentController::class, 'markRefunded'])->name('admin.payments.refund');





    // Admin Payment Management Routes

    // Route::prefix('admin/payments')->name('admin.payments.')->group(function () {

    // Main payments page

    Route::get('payments', [RegistrationPaymentController::class, 'registrationPayments'])->name('admin.payments.registrationPayments');



    // Payment details and actions

    Route::get('/{id}/details', [RegistrationPaymentController::class, 'paymentDetails'])->name('details');

    Route::post('/{id}/approve', [RegistrationPaymentController::class, 'approvePayment'])->name('approve');

    Route::post('/{id}/reject', [RegistrationPaymentController::class, 'rejectPayment'])->name('reject');

    Route::post('/{id}/refund', [RegistrationPaymentController::class, 'refundPayment'])->name('refund');

    Route::post('/{id}/send-receipt', [RegistrationPaymentController::class, 'sendReceipt'])->name('send-receipt');

    Route::get('/{id}/edit-data', [RegistrationPaymentController::class, 'getEditData'])->name('edit-data');

    Route::post('/{id}/update', [RegistrationPaymentController::class, 'updatePayment'])->name('update');

    Route::get('/{id}/amount', [RegistrationPaymentController::class, 'getPaymentAmount'])->name('amount');



    // Filter and export

    Route::get('/filter', [RegistrationPaymentController::class, 'filterPayments'])->name('filter');

    Route::get('/export/{format}', [RegistrationPaymentController::class, 'exportPayments'])->name('export');



    // Legacy routes (keep for backward compatibility)

    Route::get('/list', [RegistrationPaymentController::class, 'registrationPayments'])->name('registrationPayments');

    Route::get('/view/{id}', [RegistrationPaymentController::class, 'showRegistrationPayments'])->name('showRegistrationPayments');

    Route::post('/{id}/complete', [RegistrationPaymentController::class, 'markCompleted'])->name('complete');

    Route::post('/{id}/fail', [RegistrationPaymentController::class, 'markFailed'])->name('fail');

    Route::post('/{id}/refund-old', [RegistrationPaymentController::class, 'markRefunded'])->name('refund-old');

    // });



    // Assign House helps to clients

    Route::get('/admin/active/{id}/assign-house-help', [ConfirmationController::class, 'showAssignForm'])->name('show.assignForm')->middleware('auth');

    Route::post('/admin/confirmation/{id}/update', [ConfirmationController::class, 'update'])->name('confirmation.update')->middleware('auth');

    Route::post('/admin/confirmation/{id}/assign-house-help', [ConfirmationController::class, 'assignHouseHelp'])->name('active.assign')->middleware('auth');



    Route::post('/admin/service-request/{serviceRequest}/update-active', [ServiceController::class, 'updateactive'])->middleware('auth');









    Route::post('admin/packages', [PackageController::class, 'store'])->name('packages.store')->middleware('auth');

    Route::post('/packages/update/{id}', [PackageController::class, 'update'])->name('packages.update')->middleware('auth');

    Route::delete('/packages/{id}', [PackageController::class, 'destroy'])->name('packages.destroy')->middleware('auth');



    Route::get('/admin/newonetimeservice', [PagesController::class, 'newonetimeservice'])->name('newonetimeservice')->middleware('auth');

    Route::post('admin/newonetimeservices', [OneTimeServiceController::class, 'newonetimeservicestore'])->name('newonetimeservice.store')->middleware('auth');

    Route::get('/admin/onetimeservice', [OneTimeServiceController::class, 'onetimeservice'])->name('onetimeservice')->middleware('auth');

    Route::post('/one-time-service/{id}/update', [OneTimeServiceController::class, 'update'])->name('one-time-service.update')->middleware('auth');

    Route::delete('/apartment/{id}', [OneTimeServiceController::class, 'destroy'])->name('onetimeservice.destroy')->middleware('auth');



    Route::get('/admin/newliveinservice', [PagesController::class, 'newliveinservice'])->name('newliveinservice')->middleware('auth');

    Route::post('admin/newliveinservices', [LiveInServiceController::class, 'newliveinservicestore'])->name('newliveinservice.store')->middleware('auth');

    Route::get('/admin/liveinservice', [LiveInServiceController::class, 'liveinservice'])->name('liveinservice')->middleware('auth');

    Route::post('/live-in-service/{id}/update', [LiveInServiceController::class, 'update'])->name('live-in-service.update')->middleware('auth');

    Route::delete('/service/{id}', [LiveInServiceController::class, 'destroy'])->name('liveinservice.destroy')->middleware('auth');



    //  packages .......................................

    Route::get('/admin/newpackage', [PagesController::class, 'newpackage'])->name('newpackage')->middleware('auth');

    Route::get('/admin/allpackages', [PagesController::class, 'allpackages'])->name('allpackages')->middleware('auth');



    Route::get('/admin/newapartments', [ApartmentsController::class, 'newapartments'])->name('newapartments')->middleware('auth');

    Route::post('/apartments', [ApartmentsController::class, 'newapartmentsstore'])->name('newapartmentsstore')->middleware('auth');

    Route::get('/admin/apartments', [ApartmentsController::class, 'apartments'])->name('apartments')->middleware('auth');

    Route::delete('/apartments/{id}', [ApartmentsController::class, 'destroy'])->name('apartments.destroy')->middleware('auth');

    Route::post('/apartments/update/{id}', [ApartmentsController::class, 'update'])->name('apartments.update')->middleware('auth');



    Route::get('/admin/newdiscount', [DiscountController::class, 'newdiscount'])->name('newdiscount')->middleware('auth');

    Route::post('admin/postdiscount', [DiscountController::class, 'postdiscount'])->name('postdiscount.store')->middleware('auth');

    Route::get('/admin/discount', [DiscountController::class, 'discount'])->name('discount')->middleware('auth');

    Route::post('/discount/update/{id}', [DiscountController::class, 'update'])->name('discount.update')->middleware('auth');

    Route::delete('/discount/{id}', [DiscountController::class, 'destroy'])->name('discount.destroy')->middleware('auth');



    Route::get('/admin/staffLiveOut', [AdminController::class, 'staffLiveOut'])->name('staffLiveIn')->middleware('auth');

    Route::get('/admin/applicants', [AdminController::class, 'applicantshousehelps'])->name('adminapplicants')->middleware('auth');



    Route::put('/admin/users/{id}/approve', [AdminController::class, 'approveUser'])->name('admin.approve.user')->middleware('auth');

    Route::put('/admin/users/{id}/unapprove', [AdminController::class, 'unapproveUser'])->name('admin.unapprove.user')->middleware('auth');

    Route::put('/admin/users/{id}/archieve', [AdminController::class, 'archieveUser'])->name('admin.archieve.user')->middleware('auth');

    Route::put('/admin/order/{id}/archieve', [AdminController::class, 'archieveOrder'])->name('admin.archieve.order')->middleware('auth');

    Route::get('/{user}/edit', [AdminController::class, 'editHouseHelp'])->name('admin.HouseHelp.edit');

    Route::put('/{user}', [AdminController::class, 'updateHouseHelp'])->name('admin.HouseHelp.update');









    Route::get('/payments/success', [PaymentController::class, 'paymentSuccess'])->name('payments.success');

    Route::post('/payments/{payment}/approve', [PaymentController::class, 'approve'])->name('payments.approve');

    Route::post('/payments/{payment}/cancel', [PaymentController::class, 'cancel'])->name('payments.cancel');

    Route::post('/payments/{id}/unapprove', [PaymentController::class, 'unapprove'])->name('payments.unapprove');







    Route::get('/admin/skilledworkerslist', [AdminController::class, 'applicantsskilledworkers'])->name('adminskilledworkers')->middleware('auth');

    Route::get('/admin/clients', [AdminController::class, 'clients'])->name('adminclients')->middleware('auth');

    Route::get('/admin/staffs', [AdminController::class, 'staffs'])->name('adminstaffs')->middleware('auth');

    Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete')->middleware('auth');

    Route::post('/admin/users/{id}/suspend', [AdminController::class, 'suspendUser'])->name('users.suspend')->middleware('auth');

    Route::patch('/admin/users/{id}/lift-suspension', [AdminController::class, 'liftSuspension'])->name('users.lift-suspension')->middleware('auth');



    Route::get('/admin/users/search', [AdminController::class, 'searchUsers'])->name('admin.users.search')->middleware('auth');

    Route::post('/admin/update-role/{id}', [AdminController::class, 'updateUserRole'])->name('admin.updateRole')->middleware('auth');



    Route::post('/admin/contactmessage/{id}/mark-as-read', [ContactMessageController::class, 'markAsRead'])->name('contactmessage.markAsRead');

    Route::post('contact-messages/{contactMessageId}/reply', [ContactMessageController::class, 'storeReply'])->name('contactmessages.reply');



    Route::get('/admin/contactmessage', [ContactMessageController::class, 'contactmessage'])->name('contactmessage')->middleware('auth');

    Route::post('/contactmessage/markAsRead/{id}', [ContactMessageController::class, 'markAsReadOnClickAdmin']);



    //  Multiple Services .......................................

    Route::post('/multipleservice/store', [multipleServiceController::class, 'store'])->name('multipleservice.store');

    Route::get('/admin/multipleServices', [ServiceController::class, 'createultipleservice'])->name('admin.multipleServices');

    Route::get('/admin/manageMultipleServices', [multipleServiceController::class, 'manageMultipleServices'])->name('admin.manageMultipleServices');



    Route::get('multipleservice/{id}/edit', [MultipleServiceController::class, 'edit'])->name('multipleservice.edit');

    Route::put('multipleservice/{id}', [MultipleServiceController::class, 'update'])->name('multipleservice.update');

    Route::delete('multipleservice/{id}', [MultipleServiceController::class, 'destroy'])->name('multipleservice.destroy');





    Route::get('/admin/allservices', [ServiceController::class, 'allservices'])->name('admin.allservices');

    Route::get('/admin/newservice', [ServiceController::class, 'createservice'])->name('admin.services.create');



    Route::get('/service-types', [ServiceTypeController::class, 'index'])->name('admin.service-types.index');

    Route::patch('/service-types/{id}', [ServiceTypeController::class, 'update'])->name('admin.service-types.update');



    Route::get('admin/service-charge', [ServiceChargeController::class, 'index'])->name('admin.service_charge.index');

    Route::put('admin/service-charge', [ServiceChargeController::class, 'update'])->name('admin.service_charge.update');



    Route::get('admin/extra-service-cost', [ExtraServiceCostController::class, 'index'])->name('admin.extra-service-cost.index');

    Route::post('admin/extra-service-cost', [ExtraServiceCostController::class, 'store'])->name('admin.extra-service-cost.store');



    Route::get('admin/extra-day-costs', [ExtraDayCostController::class, 'index'])->name('admin.extra-day-costs.index');

    Route::post('admin/extra-day-costs/create-or-update', [ExtraDayCostController::class, 'createOrUpdate'])->name('admin.extra-day-costs.createOrUpdate');



    Route::get('/admin/newsskill', [SkillController::class, 'createskill'])->name('admin.skill.create');

    Route::post('/admin/skills', [SkillController::class, 'skillstore'])->name('admin.skills.store');

    Route::get('/admin/allskill', [SkillController::class, 'allskill'])->name('admin.allskill');

    Route::put('/admin/skill/{skill}', [SkillController::class, 'updateskill'])->name('admin.skill.update');

    Route::delete('/admin/skill/{skill}', [SkillController::class, 'destroyskill'])->name('admin.skill.delete');





    Route::post('/admin/services', [ServiceController::class, 'servicestore'])->name('admin.services.store');

    Route::get('/admin/services/{service}/edit', [ServiceController::class, 'editservice'])->name('admin.services.edit');

    Route::put('/admin/services/{service}', [ServiceController::class, 'updateservice'])->name('admin.services.update');

    Route::get('/admin/services/{id}/edit', [ServiceController::class, 'edit'])->name('admin.services.edit2');

    Route::delete('/admin/services/{id}', [ServiceController::class, 'servicedestroy'])->name('admin.services.delete');





    Route::put('/services/{service}', [ServiceController::class, 'serviceupdate'])->name('services.update');

    Route::delete('/services/{service}', [ServiceController::class, 'servicedestroy'])->name('services.destroy');



    // settings

    Route::get('/admin/settings', [SettingController::class, 'settings'])->name('admin.settings');



    // Handle form submission

    Route::post('liveout-bonus', [SettingController::class, 'updateLiveoutBonus'])

        ->name('liveout_bonus.update'); // located inside admin and auth middelware

});



// In web.php



Route::middleware(['auth'])->group(function () {

    // Service request confirmation page

    Route::get('/service-request/confirmation/{id}', [PagesController::class, 'confirmation'])

        ->name('service-request.confirmation');



    // Payment page for service request

    Route::get('/service-request/payment/{id}', [PagesController::class, 'showPaymentPage'])

        ->name('service-request.payment');



    // Process payment for service request

    Route::post('/service-request/payment/{id}', [PagesController::class, 'processPayment'])

        ->name('service-request.payment.process');



    // View payment receipt

    Route::get('/service-request/payment/receipt/{id}', [PagesController::class, 'showPaymentReceipt'])

        ->name('service-request.payment.receipt');





    Route::post('/save-partial-registration', [PagesController::class, 'savePartialRegistration'])

        ->name('save.partial.registration');



    Route::get('/registration-complete', [PagesController::class, 'registrationComplete'])
        ->name('service-request.registration-complete');



    // Credit Routes
    Route::get('/credits', [App\Http\Controllers\CreditController::class, 'index'])->name('credits.index');
    Route::get('/credits/balance', [App\Http\Controllers\CreditController::class, 'balance'])->name('credits.balance');
    Route::post('/credits/receipt', [App\Http\Controllers\CreditController::class, 'submitReceipt'])->name('credits.submit-receipt');

    // Paystack payment
    Route::post('/credits/pay', [App\Http\Controllers\CreditController::class, 'initializePayment'])->name('credits.pay');
    Route::get('/credits/callback', [App\Http\Controllers\CreditController::class, 'handleCallback'])->name('credits.payment.callback');

    // Credits (client)
    Route::post('/credits/webhook', [App\Http\Controllers\CreditController::class, 'handleWebhook'])->name('credits.webhook')->withoutMiddleware(['auth']);
    Route::post('/credits/receipt', [App\Http\Controllers\CreditController::class, 'submitReceipt'])->name('credits.submit-receipt');

    // Push notification subscription routes (auth required)
    Route::post('/push/subscribe',   [App\Http\Controllers\PushSubscriptionController::class, 'subscribe'])->name('push.subscribe');
    Route::post('/push/unsubscribe', [App\Http\Controllers\PushSubscriptionController::class, 'unsubscribe'])->name('push.unsubscribe');

    Route::get('/verify-bank-account', [UserController::class, 'verifyBankAccount'])->name('bank.verify')->middleware('auth');
});

// Paystack webhook — no auth needed, called by Paystack servers
// Route::post('/credits/webhook', [App\Http\Controllers\CreditController::class, 'handleWebhook'])->name('credits.webhook');

Route::get('/test-paystack', function () {
    try {
        $res = Http::get('https://api.paystack.co/');
        return $res->body();
    } catch (\Exception $e) {
        return $e->getMessage();
    }
});

// Notification routes
Route::middleware(['auth'])->group(function () {

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');

    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
});

// Add this route for checking auth status

Route::get('/check-auth', function () {

    return response()->json([

        'authenticated' => Auth::check(),

        'user_id' => Auth::id(),

        'user_email' => Auth::check() ? Auth::user()->email : null

    ]);
})->name('check.auth');



Route::post('/liveouts/registerSkills', [SkillController::class, 'storeSkilledWorkers'])->name('applicants.registerSkilledWorkers');

// Route::get('/skills/autocomplete', function (Request $request) {

//     $term = strtolower($request->get('term'));



//     $skills = Service::whereRaw('LOWER(service_name) like ?', ['%' . $term . '%'])

//         ->limit(10)

//         ->get();



//     $results = [];

//     foreach ($skills as $skill) {

//         $results[] = [

//             'id' => $skill->id,

//             'label' => $skill->service_name,

//             'value' => $skill->service_name

//         ];

//     }



//     return response()->json($results);

// })->name('skills.autocomplete');

Route::get('/skills/autocomplete', [SkillController::class, 'autocomplete'])->name('skills.autocomplete');



Route::get('/skilled', [SkillController::class, 'applyasaskillworker'])->name('applyasaskillworker');

// Route::get('/apply-as-skilled-worker', [SkillController::class, 'applyasaskillworker'])->name('applyasaskillworker');

Route::get('/ourSkilledWorkerServices', [SkillController::class, 'ourSkilledWorkerServices'])->name('ourSkilledWorkerServices');





// contact message

Route::post('/contact-message', [ContactMessageController::class, 'storemessage'])->name('contact-message.store');





// Legacy Service Request redirects -> New Booking Flow

Route::get('/service-requests/personal-info', function () {

    return redirect()->route('booking.service-selection');
})->name('service-request.personal-info');



Route::get('/service-requests/service-request', function () {

    return redirect()->route('booking.service-selection');
})->name('service-request.create');



// Keep POST routes for old flow (form submissions from existing bookmarks/cached pages)

Route::post('/service-requests/personal-info', [ServiceRequestController::class, 'savePersonalInfo'])->name('service-request.save-personal-info');

Route::post('/service-requests/create', [ServiceRequestController::class, 'saveServiceRequest'])->name('service-request.save');



// Protected routes (require auth)

Route::middleware(['client', 'auth', 'check.credits'])->group(function () {

    // Registration

    Route::get('/service-requests/registration', [ServiceRequestController::class, 'showRegistration'])->name('service-request.registration');

    // Route::post('/service-requests/registration', [ServiceRequestController::class, 'saveRegistration'])->name('service-request.save-registration');

    Route::post('/service-requests/registration/{user}', [ServiceRequestController::class, 'saveRegistration'])->name('service-request.save-registration');



    // routes/web.php or routes/admin.php

    Route::post('/contactmessage/markAsReadOnClick/{id}', [ContactMessageController::class, 'markAsReadOnClick'])->name('contactmessage.markAsReadOnClick');

    Route::post('/admin/reply/{id}/mark-as-read', [ContactMessageController::class, 'markAsReadReply'])->name('contactmessage.markAsReadReply');

    Route::get('messages', [ContactMessageController::class, 'clientmessage'])->name('clientmessage');

    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard')->middleware('auth');

    Route::get('/bookings', [UserController::class, 'bookings'])->name('bookings')->middleware('auth');

    Route::get('/registerandpayment', [RegistrationPaymentController::class, 'registerandpayment'])->name('registerandpayment')->middleware('auth');



    Route::post('/registerandpayment/process', [RegistrationPaymentController::class, 'processRegistration'])->name('registerandpayment.process');

    Route::post('/registerandpayment/upload-receipt', [RegistrationPaymentController::class, 'uploadReceipt'])->name('registerandpayment.upload-receipt');

    Route::post('/registerandpayment/cancel/{id}', [RegistrationPaymentController::class, 'cancelRegistration'])->name('registerandpayment.cancel');



    Route::get('/booking/cancel/{id}', [UserController::class, 'cancelBooking'])->name('booking.cancel')->middleware('auth');

    Route::post('/booking/status/update/{id}', [UserController::class, 'updateStatus'])->name('booking.updateStatus')->middleware('auth');

    Route::get('/househelps', [UserController::class, 'househelps'])->name('househelps')->middleware('auth');

    Route::get('/paymenthistory', [UserController::class, 'paymenthistory'])->name('paymenthistory')->middleware('auth');

    Route::get('/payments/makepayment', [UserController::class, 'makepayment'])->name('payments.makepayment')->middleware('auth');

    Route::get('/profile', [UserController::class, 'clientprofile'])->name('clientprofile')->middleware('auth');

    Route::get('/premium', [UserController::class, 'premium'])->name('premium')->middleware('auth');

    Route::get('/househelprequest', [UserController::class, 'househelprequest'])->name('liveinjobrequest')->middleware('auth');

    Route::post('/liveinservice/request', [LiveInServiceRequestController::class, 'store'])->name('liveinservice.request');

    Route::get('/liveinservice/cancel/{id}', [LiveInServiceRequestController::class, 'cancelLiveinserviceBooking'])->name('liveinservice.cancel')->middleware('auth');



    Route::get('/payments/accountdetail/{plan_id}', [PaymentController::class, 'accountdetail'])->name('payments.accountdetail');

    Route::get('/payments/accountdetailskill/{plan_id}', [PaymentController::class, 'accountdetailskill'])->name('payments.accountdetailskill');

    Route::post('/handle-payment-skill', [PaymentController::class, 'handlePaymentSkill'])->name('payments.handleskills');

    Route::post('/handle-payment', [PaymentController::class, 'handlePayment'])->name('payments.handle');

    Route::post('/payments/paystack/callback', [PaymentController::class, 'handlePaystackCallback'])
        ->name('payments.paystack.callback')
        ->middleware('auth');
});







Route::post('/requests/{id}/cancel', [LiveInServiceRequestController::class, 'cancelRequest'])->name('requests.cancel');

Route::post('/requests/{id}/accept', [LiveInServiceRequestController::class, 'acceptRequest'])->name('requests.accept');

Route::post('/requests/{id}/reject', [LiveInServiceRequestController::class, 'rejectRequest'])->name('requests.reject');



Route::get('/debug-salary/{id}', function ($id) {
    $sr = \App\Models\ServiceRequest::find($id);

    $serviceNames = is_array($sr->serviceName)
        ? $sr->serviceName
        : (json_decode($sr->serviceName, true) ?? []);

    $serviceIds = \App\Models\Service::whereIn('service_name', $serviceNames)
        ->pluck('id', 'service_name')
        ->toArray();

    $packageExact = \App\Models\Package::where('name', $sr->package)->first();
    $packageByDays = \App\Models\Package::where('days_per_week', $sr->days_per_week)->first();

    $salaryService = new \App\Services\StaffSalaryService();
    $result = $salaryService->calculateFromServiceRequest($sr);

    return response()->json([
        'service_request_id' => $sr->id,
        'package_stored' => $sr->package,
        'days_per_week' => $sr->days_per_week,
        'service_names' => $serviceNames,
        'service_ids_found' => $serviceIds,
        'package_exact_match' => $packageExact ? ['id' => $packageExact->id, 'name' => $packageExact->name] : null,
        'package_days_match' => $packageByDays ? ['id' => $packageByDays->id, 'name' => $packageByDays->name] : null,
        'salary_result' => $result,
    ]);
})->middleware('auth');



// Applicant dashboard .............................................

Route::middleware(['applicant'])->group(function () {

    Route::get('/applicantdashboard', [ApplicantController::class, 'applicantdashboard'])->name('applicantdashboard')->middleware('auth', 'check.applicant.completion');

    Route::get('/applicantpositions', [ApplicantController::class, 'receivedliveInServiceRequests'])->name('applicant.positions')->middleware('auth', 'check.applicant.completion');

    Route::get('/specialDuties', [ApplicantController::class, 'specialDuties'])->name('applicant.specialDuties')->middleware('auth', 'check.applicant.completion');

    Route::get('/applicantprofile/{id}/profile', [ApplicantController::class, 'applicantprofile'])->name('applicantprofile')->middleware('auth', 'check.applicant.completion');

    Route::get('/applicant/terms', [ApplicantController::class, 'applicantterms'])->name('applicantterms')->middleware('auth', 'check.applicant.completion');

    Route::get('/applicant/{user_id}/{name}/speciality', [ApplicantController::class, 'applicantspeciality'])->name('applicantspeciality')->middleware('auth');

    Route::post('/user/{id}/update-specialties', [UserController::class, 'specialtiesupdate'])->name('user.specialtiesupdate');

    Route::put('/account/update', [ApplicantController::class, 'accountupdate'])->name('account.update')->middleware('auth');



    // Live-Out Service Request offers

    Route::get('/liveout-service-requests', [LiveOutServiceRequestController::class, 'index'])->name('applicant.liveOutServiceRequests')->middleware('auth', 'check.applicant.completion');

    Route::post('/liveout-service-requests/{id}/accept', [LiveOutServiceRequestController::class, 'accept'])->name('liveout.requests.accept')->middleware('auth');

    Route::post('/liveout-service-requests/{id}/decline', [LiveOutServiceRequestController::class, 'decline'])->name('liveout.requests.decline')->middleware('auth');
});



Route::get('/applicant/{id}', [ApplicantController::class, 'showapplicant'])->name('applicant.show');



Route::get('/applicant/privacy', [ApplicantController::class, 'applicantprivacy'])->name('applicantprivacy')->middleware('auth', 'check.applicant.completion');

Route::post('/applicantprofile', [ApplicantController::class, 'update'])->name('applicantprofile.update');

Route::post('/applicant/{id}/rate', [RatingController::class, 'storeRating'])->name('applicant.rate')->middleware('auth');



Route::get('/guarantors/{user}/archived', [GuarantorController::class, 'showArchivedGuarantors'])->name('guarantors.archived');



// Route to show the guarantors form

Route::get('/applicant/{user_id}/{name}/guarantors', [GuarantorController::class, 'showForm'])->name('applicant.guarantors')->middleware('auth')->middleware('auth');

Route::get('/applicant/{user_id}/{name}/guarantors_two', [GuarantorController::class, 'showFormguarantors_two'])->name('applicant.guarantors_two')->middleware('auth')->middleware('auth');



// Route to handle form submission

Route::post('/submit-guarantor1', [GuarantorController::class, 'submitGuarantor1'])->name('applicant.submitGuarantor1')->middleware('auth');

Route::post('/submit-guarantor2', [Guarantor2Controller::class, 'submitGuarantor2'])->name('applicant.submitGuarantor2')->middleware('auth');





// Route for Guarantor 1 PDF download

Route::get('/applicant/{user_id}/guarantor1/download', [GuarantorController::class, 'downloadGuarantor1Pdf'])->name('applicant.downloadGuarantor1Pdf')->middleware('auth');

// Route for Guarantor 2 PDF download

Route::get('/applicant/{user_id}/guarantor2/download', [Guarantor2Controller::class, 'downloadGuarantor2Pdf'])->name('applicant.downloadGuarantor2Pdf')->middleware('auth');





Route::get('/our_terms_and_conditions', [PagesController::class, 'showTermsCondition'])->name('our.terms.conditions');

Route::get('/terms_and_conditions', [PagesController::class, 'showTerms'])->name('terms.conditions')->middleware('auth');

Route::post('/terms-and-conditions/accept', [PagesController::class, 'acceptTerms'])->name('terms.accept')->middleware('auth');



Route::post('/applicant_type/accept', [PagesController::class, 'storeapplicanttype'])

    ->name('applicant.type')

    ->middleware('auth');



Route::get('/here', [PagesController::class, 'here'])->name('here')->middleware('auth');





Route::post('/users/{id}/toggle-availability', [UserController::class, 'toggleAvailability'])->name('toggle.availability');

Route::post('/user/update-availability', [UserController::class, 'updateAvailability'])->name('user.update-availability');

















// user section .....................................................



Route::get('/', function () {

    return view('welcome');
})->name('home');



Route::get('/welcome-index', function () {

    return view('welcome-index');
})->name('welcome-index');





Route::get('/get-started', [PagesController::class, 'getstarted'])->name('get-started');





Route::get('/packages', [PagesController::class, 'packages'])->name('packages');

Route::get('/one-time', [PagesController::class, 'onetime'])->name('onetime');

Route::get('/live-in', [PagesController::class, 'livein'])->name('livein');





Route::post('/apartment-orders', [ApartmentOrderController::class, 'store'])->name('apartment-orders.store');

Route::get('/receipt', function () {

    // Access the flashed session data

    $order = session('order');



    // Check if the order exists, otherwise redirect back with an error

    if (!$order) {

        return redirect()->back()->with('error', 'No order found!');
    }



    return view('receipt', compact('order'));
})->name('receipt');







Route::get('/ourLiveInServices', [PagesController::class, 'ourLiveInServices'])->name('ourLiveInServices');

Route::get('/premiumLiveOut', [PagesController::class, 'premiumLiveOut'])->name('premiumLiveOut');





Route::get('/single', [PagesController::class, 'single'])->name('single');

Route::post('/get-discount', [PagesController::class, 'getDiscount'])->name('get-discount');





// Optional: Route to confirm or process the subscription

Route::post('/service/subscribe/{id}', [ServiceController::class, 'processSubscription'])->name('service.subscribe.process');





// Route to handle service subscription

Route::get('/service/subscribe/{id}', [ServiceController::class, 'subscribe'])->name('service.subscribe');



Route::get('/multiple', [ServiceController::class, 'multiple'])->name('multiple');



Route::post('/apply-serviceRequest', [ServiceController::class, 'serviceRequest'])->name('apply-serviceRequest');

Route::get('/orderreceipt/{serviceRequest}', [ServiceController::class, 'showOrderReceipt'])->name('orderreceipt');







// single services .............................................................

Route::get('/single/NannyAndHousekeeping', [PagesController::class, 'nanny'])->name('nanny');

Route::get('/single/CookAndErrands', [PagesController::class, 'cook'])->name('cook');

Route::get('/single/LaundryAnd', [PagesController::class, 'laundry'])->name('laundry');

Route::get('/single/ErrandAndHousekeeping', [PagesController::class, 'errand'])->name('errand');

Route::get('/single/CleaningAndHousekeeping', [PagesController::class, 'cleaning'])->name('cleaning');







Route::patch('/users/{id}/update-status', [UserController::class, 'updateUserStatus'])->name('users.updateUserStatus');

Route::get('/users', [UserController::class, 'index'])->name('users.index');




Route::get('/apply_as_househelp', [UserController::class, 'applyhousehelp'])->name('applyashousehelp');

Route::get('/apply', [UserController::class, 'househelpapply'])->name('househelpapply');

// storeliveouts (old full form with guarantors)
Route::post('/liveouts/register-full', [UserController::class, 'storeliveouts'])->name('applicants.register');

// storeliveins (new simplified form)
Route::post('/liveouts/register', [UserController::class, 'storeliveins'])->name('applicants.registerlivein');




// Route to show the service request form

Route::get('/services/{id}/request', [ServiceController::class, 'requestForm'])->name('services.request');



// Route to handle the form submission

Route::post('/services/{id}/request', [ServiceController::class, 'submitRequest'])->name('services.submitRequest');

Route::get('/check-availability', [UserController::class, 'checkAvailability']);

Route::middleware('throttle:10,1')->group(function () {

    Route::get('/check-availability', [UserController::class, 'checkAvailability']);
});



Route::get('/select-package', [ServiceController::class, 'selectPackage'])->name('select-package');

Route::get('/selected-package/{packageName}', [ServiceController::class, 'selectedPackage'])->name('selected-package');

Route::post('/process-package', [ServiceController::class, 'processPackage'])->name('processpackage');





Route::get('/packages/{package}/services', function ($packageId) {

    $services = service::whereHas('servicePackageCosts', function ($query) use ($packageId) {

        $query->where('id', $packageId);
    })->get();



    return response()->json(['services' => $services]);
})->name('packages.services');  // Give the route a name



Route::get('/maintenance', [PagesController::class, 'maintenance'])->name('maintenance');

Route::get('/coming-soon', [PagesController::class, 'comingsoon'])->name('comingsoon');

Route::get('/faq', [FaqController::class, 'publicIndex'])->name('faq');







Route::post('/save-user-data', [UserController::class, 'saveUserData'])->name('saveUserData');



Route::get('/services/multipleSelectedServices', [ServiceController::class, 'multipleSelectedServices'])->name('multipleSelectedServices');

Route::post('/service-request', [ServiceController::class, 'multipleSelectedServicesStore'])->name('service_requests.store');

Route::get('/fetch-related-services/{id}', [ServiceController::class, 'fetchservices'])->name('fetchservices2');













Route::post('/request-click', [RequestClickController::class, 'store'])->name('request.click');





Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');





// coupon routes .............................................

Route::post('/validate-coupon', [CouponController::class, 'validateCoupon'])->name('validate.coupon');



Route::middleware('verify.coupon')->group(function () {

    Route::get('/skilled-worker-services', [CouponController::class, 'showSkilledWorkerServices'])->name('couponSkilledWorkerServices');

    Route::post('/unlock-worker/{id}', [CouponController::class, 'unlockWorker'])->name('unlock.worker');
});





// terms and conditions

Route::get('/t&c', [PagesController::class, 'showAllTermsCondition'])->name('t&c');





// Live-out payment routes

Route::middleware(['auth'])->prefix('live-out-payment')->group(function () {

    Route::get('/{serviceRequest}', [LiveOutPaymentController::class, 'showPaymentForm'])

        ->name('live-out-payment.form');

    Route::post('/process/{serviceRequest}', [LiveOutPaymentController::class, 'processPayment'])

        ->name('live-out-payment.process');

    Route::get('/success/{serviceRequest}', [LiveOutPaymentController::class, 'paymentSuccess'])

        ->name('live-out-payment.success');

    Route::get('/failure/{serviceRequest}', [LiveOutPaymentController::class, 'paymentFailure'])

        ->name('live-out-payment.failure');
});



// Payment callback/webhook (no auth - called by payment gateway)

Route::post('/live-out-payment/callback', [LiveOutPaymentController::class, 'paymentCallback'])

    ->name('live-out-payment.callback');





// =====================================================

// CLIENT BOOKING FLOW ROUTES

// =====================================================

Route::middleware(['client', 'auth', 'prevent-back'])->prefix('booking')->name('booking.')->group(function () {

    // Step 2: Service Selection

    Route::get('/service-selection', [BookingController::class, 'showServiceSelection'])

        ->name('service-selection');

    Route::post('/service-selection', [BookingController::class, 'saveServiceSelection'])

        ->name('save-service-selection');



    // AJAX: Real-time cost calculation

    Route::post('/calculate-cost', [BookingController::class, 'calculateCost'])

        ->name('calculate-cost');



    // Step 3: Staff Matching

    Route::get('/staff-matching/{serviceRequest}', [BookingController::class, 'showStaffMatching'])

        ->name('staff-matching');




    // Step 4: Shortlist

    Route::get('/shortlist/{serviceRequest}', [BookingController::class, 'showShortlist'])

        ->name('shortlist');

    Route::post('/shortlist/{serviceRequest}/select', [BookingController::class, 'selectStaff'])

        ->name('select-staff');

    // Step 5: Staff Profile

    Route::get('/staff-profile/{serviceRequest}/{shortlistId}', [BookingController::class, 'showStaffProfile'])
        ->name('staff-profile');

    // Route to confirm staff selection and proceed to next step
    Route::post('/confirm-staff/{serviceRequest}/{shortlistId}', [BookingController::class, 'confirmStaffSelection'])
        ->name('confirm-staff');

    Route::post('/cancel-order/{serviceRequest}', [BookingController::class, 'cancelOrder'])
        ->name('cancel-order');

    // Step 6: Trust Assurance

    // Route::get('/trust-assurance/{serviceRequest}', [BookingController::class, 'showTrustAssurance'])

    //     ->name('trust-assurance');

    // Route::post('/trust-assurance/{serviceRequest}/call-request', [BookingController::class, 'requestCallWithStaff'])

    //     ->name('request-call');



    // Step 7: Management Plans

    Route::get('/management-plans/{serviceRequest}', [BookingController::class, 'showManagementPlans'])

        ->name('management-plans');

    Route::post('/management-plans/{serviceRequest}', [BookingController::class, 'selectManagementPlan'])

        ->name('select-plan');



    // Step 8: Payment

    Route::get('/payment/{serviceRequest}', [BookingController::class, 'showPayment'])

        ->name('payment');

    Route::post('/payment/{serviceRequest}', [BookingController::class, 'processPayment'])

        ->name('process-payment');

    Route::post('/payment/{serviceRequest}/paystack-callback', [BookingController::class, 'handlePaystackCallback'])

        ->name('paystack-callback');

    Route::post('/payment/{serviceRequest}/whatsapp', [BookingController::class, 'uploadWhatsAppReceipt'])

        ->name('whatsapp-receipt');

    Route::get('/payment-success/{serviceRequest}', [BookingController::class, 'showPaymentSuccess'])

        ->name('payment-success');



    // Step 9: Staff Details                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            

    Route::get('/booking/staff-profile/{serviceRequest}', [BookingController::class, 'showStaffDetails']);


    // Step 0: Deployment

    Route::get('/deployment/{serviceRequest}', [BookingController::class, 'showDeployment'])

        ->name('deployment');



    // Urgent Request

    Route::get('/urgent-request', [BookingController::class, 'showUrgentRequest'])

        ->name('urgent-request');

    Route::post('/urgent-request', [BookingController::class, 'submitUrgentRequest'])

        ->name('submit-urgent-request');



    // Replacement Request

    Route::post('/replacement/{serviceRequest}', [BookingController::class, 'requestReplacement'])

        ->name('request-replacement');
});




// =====================================================

// STAFF (APPLICANT) SHORTLIST ROUTES

// =====================================================

Route::middleware(['auth'])->group(function () {

    Route::get('/staff-shortlist-offers', [LiveOutServiceRequestController::class, 'shortlistOffers'])

        ->name('applicant.shortlistOffers');

    Route::post('/shortlist-offer/{id}/accept', [LiveOutServiceRequestController::class, 'acceptShortlistOffer'])

        ->name('shortlist.accept');

    Route::post('/shortlist-offer/{id}/decline', [LiveOutServiceRequestController::class, 'declineShortlistOffer'])

        ->name('shortlist.decline');
});



// =====================================================

// ADMIN BOOKING MANAGEMENT ROUTES

// =====================================================

Route::middleware(['admin', 'auth'])->group(function () {

    Route::get('/admin/bookings', [AdminController::class, 'bookingsDashboard'])

        ->name('admin.bookings');

    Route::get('/admin/bookings/{id}', [AdminController::class, 'bookingDetail'])

        ->name('admin.booking.detail');

    Route::post('/admin/bookings/{id}/approve-payment', [AdminController::class, 'approveBookingPayment'])

        ->name('admin.booking.approve-payment');

    Route::post('/admin/bookings/{id}/release-staff', [AdminController::class, 'releaseStaffDetails'])

        ->name('admin.booking.release-staff');



    // Management Plans CRUD

    Route::get('/admin/management-plans', [AdminController::class, 'manageManagementPlans'])

        ->name('admin.management-plans');

    Route::post('/admin/management-plans', [AdminController::class, 'storeManagementPlan'])

        ->name('admin.management-plans.store');

    Route::put('/admin/management-plans/{id}', [AdminController::class, 'updateManagementPlan'])

        ->name('admin.management-plans.update');



    // Mark as Deployed

    Route::post('/admin/bookings/{id}/mark-deployed', [AdminController::class, 'markAsDeployed'])

        ->name('admin.booking.mark-deployed');



    // Location Management

    Route::get('/admin/locations', [AdminController::class, 'locations'])

        ->name('admin.locations');

    Route::post('/admin/locations', [AdminController::class, 'storeLocation'])

        ->name('admin.locations.store');

    Route::put('/admin/locations/{id}', [AdminController::class, 'updateLocation'])

        ->name('admin.locations.update');

    Route::delete('/admin/locations/{id}', [AdminController::class, 'destroyLocation'])

        ->name('admin.locations.destroy');



    // Replacement Requests

    Route::get('/admin/replacements', [AdminController::class, 'manageReplacements'])

        ->name('admin.replacements');

    Route::post('/admin/replacements/{id}/update-status', [AdminController::class, 'updateReplacementStatus'])

        ->name('admin.replacements.update-status');



    // Job Offer Routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/job-offers/{id}/accept', [App\Http\Controllers\JobOfferController::class, 'accept'])->name('job-offer.accept');
        Route::get('/job-offers/{id}/decline', [App\Http\Controllers\JobOfferController::class, 'decline'])->name('job-offer.decline');
    });

    // Pricing Management Routes
    Route::get('/admin/pricing', [App\Http\Controllers\PricingController::class, 'index'])->name('admin.pricing');
    Route::put('/admin/pricing/service-charge', [App\Http\Controllers\PricingController::class, 'updateServiceCharge'])->name('admin.pricing.update-service-charge');
    Route::put('/admin/pricing/management-plan/{id}', [App\Http\Controllers\PricingController::class, 'updateManagementPlan'])->name('admin.pricing.update-management-plan');
    Route::put('/admin/pricing/service/{id}', [App\Http\Controllers\PricingController::class, 'updateService'])->name('admin.pricing.update-service');
    Route::put('/admin/pricing/package/{id}', [App\Http\Controllers\PricingController::class, 'updatePackage'])->name('admin.pricing.update-package');

    // Credit Management (admin only)
    Route::get('/admin/credits', [App\Http\Controllers\CreditController::class, 'adminIndex'])->name('admin.credits');
    Route::get('/admin/credits/search', [App\Http\Controllers\CreditController::class, 'searchClients'])->name('admin.credits.search');
    Route::post('/admin/credits/add', [App\Http\Controllers\CreditController::class, 'adminAdd'])->name('admin.credits.add');
    Route::get('/admin/credits/receipt', [App\Http\Controllers\CreditController::class, 'viewReceipt'])->name('admin.credits.view-receipt');
});
