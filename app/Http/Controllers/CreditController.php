<?php

// namespace App\Http\Controllers;

// use App\Models\CreditTransaction;
// use App\Models\ServiceRequest;
// use App\Models\User;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Log;
// use Unicodeveloper\Paystack\Facades\Paystack;

// class CreditController extends Controller
// {
//     protected $plans = [
//         'starter' => [
//             'name' => 'Starter',
//             'credits' => 1,
//             'price' => 10000,
//             'description' => 'Perfect for trying out the platform',
//             'tag' => null,
//         ],
//         'standard' => [
//             'name' => 'Standard',
//             'credits' => 3,
//             'price' => 28000,
//             'description' => 'Most popular — best for regular clients',
//             'tag' => 'Most Popular',
//         ],
//         'premium' => [
//             'name' => 'Premium',
//             'credits' => 6,
//             'price' => 56000,
//             'description' => 'Best value for frequent bookings',
//             'tag' => 'Best Value',
//         ],
//     ];

//     public function index()
//     {
//         $user = Auth::user();
//         $balance = $user->getCreditBalance();
//         $transactions = $user->creditTransactions()
//             ->orderByDesc('created_at')
//             ->take(10)
//             ->get();

//         return view('credits.buy', compact('balance', 'transactions'));
//     }

//     /**
//      * Initialize Paystack payment for credits
//      */
//     public function initializePayment(Request $request)
//     {
//         $request->validate([
//             'plan' => 'required|in:starter,standard,premium',
//         ]);

//         $plan = $this->plans[$request->plan];
//         $user = Auth::user();

//         // Store plan and redirect_to in session for after payment
//         session([
//             'credit_plan' => $request->plan,
//             'credit_redirect_to' => $request->input('redirect_to'),
//         ]);

//         // Amount in kobo (Paystack uses kobo for NGN)
//         $amountInKobo = $plan['price'] * 100;
//         $reference = 'CREDIT_' . strtoupper(uniqid()) . '_' . $user->id;

//         $data = [
//             'amount' => $amountInKobo,
//             'email' => $user->email,
//             'reference' => $reference,
//             'currency' => 'NGN',
//             'metadata' => json_encode([
//                 'user_id' => $user->id,
//                 'plan' => $request->plan,
//                 'credits' => $plan['credits'],
//                 'plan_name' => $plan['name'],
//                 'custom_fields' => [
//                     [
//                         'display_name' => 'Plan',
//                         'variable_name' => 'plan',
//                         'value' => $plan['name'] . ' (' . $plan['credits'] . ' credits)',
//                     ]
//                 ]
//             ]),
//             'callback_url' => route('credits.payment.callback'),
//         ];

//         session(['paystack_reference' => $reference]);

//         try {
//             return Paystack::getAuthorizationUrl($data)->redirectNow();
//         } catch (\Exception $e) {
//             Log::error('Paystack initialization failed: ' . $e->getMessage());
//             return redirect()->route('credits.index')
//                 ->with('error', 'Payment initialization failed. Please try again.');
//         }
//     }

//     /**
//      * Handle Paystack callback after payment
//      */
//     public function handleCallback(Request $request)
//     {
//         try {
//             $paymentDetails = Paystack::getPaymentData();

//             if (!$paymentDetails || $paymentDetails['data']['status'] !== 'success') {
//                 return redirect()->route('credits.index')
//                     ->with('error', 'Payment was not successful. Please try again.');
//             }

//             $data = $paymentDetails['data'];
//             $metadata = is_array($data['metadata'])
//                 ? $data['metadata']
//                 : json_decode($data['metadata'], true);

//             $userId = $metadata['user_id'];
//             $credits = $metadata['credits'];
//             $planName = $metadata['plan_name'];
//             $reference = $data['reference'];

//             // Prevent duplicate credit addition
//             $alreadyProcessed = CreditTransaction::where('reference', $reference)->exists();
//             if ($alreadyProcessed) {
//                 return redirect()->route('credits.index')
//                     ->with('info', 'This payment has already been processed.');
//             }

//             $user = User::findOrFail($userId);

//             // Add credits
//             $user->addCredits(
//                 $credits,
//                 'Purchased ' . $planName . ' plan (' . $credits . ' credits) via Paystack',
//                 $reference
//             );

//             Log::info('Credits added via Paystack', [
//                 'user_id' => $userId,
//                 'credits' => $credits,
//                 'reference' => $reference,
//             ]);

//             session()->forget(['credit_plan', 'credit_redirect_to', 'credit_intended_url', 'paystack_reference']);

//             // If they have a pending service request waiting — go back to shortlist


//             // Otherwise send them to start a new booking
//             return redirect()->route('booking.service-selection')
//                 ->with('success', $credits . ' credit(s) added! You can now start your booking.');
//         } catch (\Exception $e) {
//             Log::error('Paystack callback failed: ' . $e->getMessage());
//             return redirect()->route('credits.index')
//                 ->with('error', 'Payment verification failed. Please contact support if credits were not added.');
//         }
//     }

//     /**
//      * Handle Paystack webhook (server-to-server backup)
//      */
//     public function handleWebhook(Request $request)
//     {
//         $paystackSecretKey = config('paystack.secretKey');
//         $signature = $request->header('x-paystack-signature');
//         $payload = $request->getContent();

//         if ($signature !== hash_hmac('sha512', $payload, $paystackSecretKey)) {
//             Log::warning('Invalid Paystack webhook signature');
//             return response()->json(['status' => 'invalid signature'], 400);
//         }

//         $event = json_decode($payload, true);

//         if ($event['event'] === 'charge.success') {
//             $data = $event['data'];
//             $metadata = $data['metadata'] ?? [];
//             $reference = $data['reference'];

//             if (!str_starts_with($reference, 'CREDIT_')) {
//                 return response()->json(['status' => 'ignored']);
//             }

//             $alreadyProcessed = CreditTransaction::where('reference', $reference)->exists();
//             if ($alreadyProcessed) {
//                 return response()->json(['status' => 'already processed']);
//             }

//             $userId = $metadata['user_id'] ?? null;
//             $credits = $metadata['credits'] ?? 0;
//             $planName = $metadata['plan_name'] ?? 'Unknown';

//             if ($userId && $credits > 0) {
//                 $user = User::find($userId);
//                 if ($user) {
//                     $user->addCredits(
//                         $credits,
//                         'Purchased ' . $planName . ' plan via Paystack (webhook)',
//                         $reference
//                     );
//                     Log::info('Credits added via webhook', [
//                         'user_id' => $userId,
//                         'credits' => $credits,
//                         'reference' => $reference,
//                     ]);
//                 }
//             }
//         }

//         return response()->json(['status' => 'success']);
//     }

//     public function adminIndex()
//     {
//         $transactions = CreditTransaction::with('user')
//             ->orderByDesc('created_at')
//             ->paginate(20);

//         $totalCreditsIssued = CreditTransaction::where('type', 'purchase')->sum('amount');
//         $totalCreditsUsed = CreditTransaction::where('type', 'debit')->sum('amount');

//         return view('admin.credits', compact('transactions', 'totalCreditsIssued', 'totalCreditsUsed'));
//     }

//     public function adminAdjust(Request $request)
//     {
//         $request->validate([
//             'user_id' => 'required|exists:users,id',
//             'amount' => 'required|integer|min:1',
//             'type' => 'required|in:add,remove',
//             'reason' => 'required|string|max:255',
//         ]);

//         $user = User::findOrFail($request->user_id);

//         if ($request->type === 'add') {
//             $user->addCredits($request->amount, 'Admin adjustment: ' . $request->reason);
//             $message = 'Added ' . $request->amount . ' credit(s) to ' . $user->name;
//         } else {
//             if ($user->getCreditBalance() < $request->amount) {
//                 return redirect()->back()->with('error', 'User does not have enough credits to remove.');
//             }
//             $user->deductCredit($request->amount);
//             $message = 'Removed ' . $request->amount . ' credit(s) from ' . $user->name;
//         }

//         return redirect()->back()->with('success', $message);
//     }

//     public function balance()
//     {
//         return response()->json([
//             'balance' => Auth::user()->getCreditBalance()
//         ]);
//     }
// }

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\CreditTransaction;
use App\Models\User;
use App\Notifications\CreditPaymentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class CreditController extends Controller
{
    /**
     * Show the credits purchase page
     */
    public function index()
    {
        // Check database instead of session — so admin approval clears it automatically
        $receiptPending = DB::table('credit_receipts')
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->exists();

        return view('credits.buy', compact('receiptPending'));
    }

    /**
     * Get current credit balance (AJAX)
     */
    public function balance()
    {
        return response()->json([
            'balance' => Auth::user()->getCreditBalance(),
        ]);
    }

    /**
     * Client submits bank transfer receipt
     * Fix #1: Store in private disk (not public)
     * Fix #2: Prevent duplicate submissions via session flag
     * Fix #3: Server-side file type + size validation
     */
    public function submitReceipt(Request $request)
    {
        // Check database first — if no pending receipt, clear the session flag
        $hasPendingReceipt = \DB::table('credit_receipts')
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->exists();

        if (!$hasPendingReceipt) {
            session()->forget('receipt_submitted_' . Auth::id());
        }

        // Block duplicate submissions only if DB shows pending
        if ($hasPendingReceipt) {
            return redirect()->route('credits.index')
                ->with('error', 'You have already submitted a receipt. Please wait for admin to process it, or WhatsApp us on 08126237436.');
        }

        // Fix #3: Full server-side validation
        $request->validate([
            'credits'     => 'required|in:1,3,6',
            'amount_paid' => 'required|string|max:20',
            'receipt'     => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'note'        => 'nullable|string|max:255',
        ]);

        $client = Auth::user();

        // Fix #1: Store privately — NOT in public disk
        $receiptPath = $request->file('receipt')
            ->store('credit-receipts/' . $client->id, 'local');

        $creditAmounts = [1 => '5,000', 3 => '12,000', 6 => '20,000'];
        $credits = (int) $request->credits;
        $amount  = $creditAmounts[$credits] ?? $request->amount_paid;

        DB::table('credit_receipts')->insert([
            'user_id'      => $client->id,
            'credits'      => $credits,
            'amount_paid'  => $request->amount_paid,
            'receipt_path' => $receiptPath,
            'note'         => $request->note,
            'status'       => 'pending',
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        // Fix #2: Set session flag to prevent re-submission
        session(['receipt_submitted_' . $client->id => true]);

        // Notify all admins
        try {
            $admins = User::where('user_role', '>=', 1)->get();
            if ($admins->count() > 0) {
                Notification::send($admins, new CreditPaymentNotification(
                    $client,
                    $credits,
                    $amount,
                    $receiptPath
                ));
            }
        } catch (\Exception $e) {
            Log::error('Failed to notify admins of credit payment: ' . $e->getMessage());
        }

        Log::info('Credit receipt submitted (private storage)', [
            'user_id'      => $client->id,
            'user_name'    => $client->name,
            'credits'      => $credits,
            'amount'       => $amount,
            'receipt_path' => $receiptPath,
            'note'         => $request->note,
        ]);

        return redirect()->route('credits.index')
            ->with('success', 'Receipt submitted! Our team will verify your payment and add your credits within 30 minutes. WhatsApp us on 08126237436 for faster processing.');
    }

    /**
     * Admin views a private receipt (Fix #1: serve via controller not public URL)
     */
    public function viewReceipt(Request $request)
    {
        $path = $request->get('path');

        // Security: only allow credit-receipts paths
        if (!$path || !str_starts_with($path, 'credit-receipts/')) {
            abort(403, 'Unauthorized.');
        }

        if (!Storage::disk('local')->exists($path)) {
            abort(404, 'Receipt not found.');
        }

        return Storage::disk('local')->download($path);
    }

    // ─────────────────────────────────────────────
    // PAYSTACK — disabled until approved
    // Enable by setting PAYSTACK_ENABLED=true in .env
    // ─────────────────────────────────────────────

    public function initializePayment(Request $request)
    {
        if (!config('app.paystack_enabled', false)) {
            return redirect()->route('credits.index')
                ->with('error', 'Card payments are not available yet. Please use bank transfer.');
        }

        $request->validate([
            'plan' => 'required|in:starter,standard,premium',
        ]);

        $plans = [
            'starter'  => ['credits' => 1, 'amount' => 500000,  'name' => 'Starter Plan'],
            'standard' => ['credits' => 3, 'amount' => 1200000, 'name' => 'Standard Plan'],
            'premium'  => ['credits' => 6, 'amount' => 2000000, 'name' => 'Premium Plan'],
        ];

        $plan = $plans[$request->plan];
        $user = Auth::user();

        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Bearer ' . config('paystack.secretKey'),
                'Content-Type'  => 'application/json',
            ])->post('https://api.paystack.co/transaction/initialize', [
                'email'        => $user->email,
                'amount'       => $plan['amount'],
                'callback_url' => route('credits.payment.callback'),
                'metadata'     => [
                    'user_id'   => $user->id,
                    'credits'   => $plan['credits'],
                    'plan_name' => $plan['name'],
                ],
            ]);

            $data = $response->json();

            if ($response->successful() && $data['status']) {
                return redirect($data['data']['authorization_url']);
            }

            return redirect()->route('credits.index')
                ->with('error', 'Payment initialization failed. Please try bank transfer.');
        } catch (\Exception $e) {
            Log::error('Paystack init failed: ' . $e->getMessage());
            return redirect()->route('credits.index')
                ->with('error', 'Payment service unavailable. Please use bank transfer.');
        }
    }

    public function handleCallback(Request $request)
    {
        if (!config('app.paystack_enabled', false)) {
            return redirect()->route('credits.index')
                ->with('error', 'Card payments are not available yet.');
        }

        try {
            $paymentDetails = \Unicodeveloper\Paystack\Facades\Paystack::getPaymentData();

            if (!$paymentDetails || $paymentDetails['data']['status'] !== 'success') {
                return redirect()->route('credits.index')
                    ->with('error', 'Payment was not successful. Please try again.');
            }

            $data      = $paymentDetails['data'];
            $metadata  = is_array($data['metadata'])
                ? $data['metadata']
                : json_decode($data['metadata'], true);

            $userId    = $metadata['user_id'];
            $credits   = $metadata['credits'];
            $planName  = $metadata['plan_name'];
            $reference = $data['reference'];

            if (CreditTransaction::where('reference', $reference)->exists()) {
                return redirect()->route('credits.index')
                    ->with('info', 'This payment has already been processed.');
            }

            $user = User::findOrFail($userId);
            $user->addCredits(
                $credits,
                'Purchased ' . $planName . ' (' . $credits . ' credits) via Paystack',
                $reference
            );

            return redirect()->route('booking.service-selection')
                ->with('success', $credits . ' credit(s) added! You can now start your booking.');
        } catch (\Exception $e) {
            Log::error('Paystack callback failed: ' . $e->getMessage());
            return redirect()->route('credits.index')
                ->with('error', 'Payment verification failed. Contact support if credits were not added.');
        }
    }

    public function handleWebhook(Request $request)
    {
        if (!config('app.paystack_enabled', false)) {
            return response()->json(['status' => 'disabled']);
        }

        $payload   = $request->getContent();
        $signature = $request->header('x-paystack-signature');
        $secret    = config('paystack.secretKey');

        if (hash_hmac('sha512', $payload, $secret) !== $signature) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $event = json_decode($payload, true);

        if (($event['event'] ?? '') === 'charge.success') {
            $data      = $event['data'];
            $metadata  = $data['metadata'];
            $reference = $data['reference'];

            if (!CreditTransaction::where('reference', $reference)->exists()) {
                $user = User::find($metadata['user_id']);
                if ($user) {
                    $user->addCredits(
                        $metadata['credits'],
                        'Webhook: ' . $metadata['plan_name'],
                        $reference
                    );
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }

    // ─────────────────────────────────────────────
    // ADMIN — Manual credit management
    // ─────────────────────────────────────────────

    public function adminIndex()
    {
        $recentTransactions = CreditTransaction::with('user')
            ->where('type', 'purchase')
            ->latest()
            ->take(20)
            ->get();

        $clients = User::where('account_type', 'client')
            ->orderBy('name')
            ->get();

        $totalCreditsAdded = CreditTransaction::where('type', 'purchase')->sum('amount');
        $totalClients      = Credit::where('balance', '>', 0)->count();
        $todayCredits      = CreditTransaction::where('type', 'purchase')
            ->whereDate('created_at', today())
            ->sum('amount');

        $pendingReceipts = DB::table('credit_receipts')
            ->join('users', 'credit_receipts.user_id', '=', 'users.id')
            ->where('credit_receipts.status', 'pending')
            ->select('credit_receipts.*', 'users.name as client_name', 'users.last_name', 'users.email', 'users.phone')
            ->orderByDesc('credit_receipts.created_at')
            ->get();

        return view('admin.credits', compact(
            'recentTransactions',
            'clients',
            'totalCreditsAdded',
            'totalClients',
            'todayCredits',
            'pendingReceipts'
        ));
    }

    public function searchClients(Request $request)
    {
        $q = $request->get('q', '');

        $clients = User::where('account_type', 'client')
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%");
            })
            ->take(10)
            ->get()
            ->map(fn($u) => [
                'id'      => $u->id,
                'name'    => trim($u->name . ' ' . $u->last_name),
                'email'   => $u->email,
                'balance' => $u->getCreditBalance(),
            ]);

        return response()->json($clients);
    }

    /**
     * Admin manually adds credits — also clears client's pending session
     */
    public function adminAdd(Request $request)
    {
        $request->validate([
            'user_id'   => 'required|exists:users,id',
            'credits'   => 'required|integer|min:1|max:50',
            'reference' => 'nullable|string|max:255',
        ]);

        $user  = User::findOrFail($request->user_id);
        $admin = Auth::user();

        $description = $request->reference
            ? "Admin added {$request->credits} credit(s) — {$request->reference}"
            : "Admin added {$request->credits} credit(s) — Added by {$admin->name}";

        $user->addCredits(
            (int) $request->credits,
            $description,
            'ADMIN-' . strtoupper(substr(md5(uniqid()), 0, 8))
        );

        // Mark receipt as approved if one exists
        DB::table('credit_receipts')
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->update([
                'status'       => 'approved',
                'processed_by' => $admin->id,
                'processed_at' => now(),
                'updated_at'   => now(),
            ]);

        // Fix #2: Clear the client's receipt_submitted session so they can submit again if needed
        // This runs server-side so it won't clear their browser session directly,
        // but we log it so admin knows credits were added
        Log::info('Admin manually added credits', [
            'admin_id'   => $admin->id,
            'admin_name' => $admin->name,
            'user_id'    => $user->id,
            'user_name'  => $user->name,
            'credits'    => $request->credits,
            'reference'  => $request->reference,
        ]);


        return redirect()->route('admin.credits')
            ->with('success', "{$request->credits} credit(s) added to {$user->name}. New balance: {$user->getCreditBalance()} credits.");
    }
}
