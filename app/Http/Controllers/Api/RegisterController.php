<?php

namespace App\Http\Controllers\Api;

use App\Actions\Fortify\PasswordValidationRules;
use App\Http\Controllers\Controller;
use App\Mail\WelcomeEmail;
use App\Models\Guarantor;
use App\Models\Guarantor2;
use App\Models\User;
use App\Notifications\NewClientRegisteredNotification;
use App\Notifications\NewStaffRegisteredNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class RegisterController extends Controller
{
    use PasswordValidationRules;

    public function client(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => bcrypt($validated['password']),
            'account_type' => 'client',
        ]);

        $this->notifyAdminsAndWelcome($user);

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user]);
    }

    public function staff(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'applicant_type' => 'required|string|in:live-in,live-out,semi-live-in',
            'phone' => 'required|string|size:11|unique:users',
            'gender' => 'required|string|in:male,female',
            'dob' => ['required', 'date', 'before_or_equal:' . now()->subYears(18)->format('Y-m-d')],
            'state_of_origin' => 'required|string|max:255',
            'state_of_residence' => 'required|string|max:255',
            'lga_of_origin' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'country_of_residence' => 'required|string|max:255',
            'years_of_experience' => 'required|string',
            'profile_photo_path' => 'required|image|mimes:jpg,png,jpeg|max:10240',
            'address' => 'required|string|max:2048',
            'landmark' => 'required|string|max:255',
            'nin' => 'required|string|unique:users|size:11',
            'id_card' => 'nullable|image|mimes:jpg,png,jpeg|max:5120',
            'bio' => 'required|string',
            'specialties' => 'required|array',
            'password' => 'required|string|min:8|confirmed',
            'religion' => 'required|string|max:100',
            'marital_status' => 'required|in:single,married,divorced,widowed',
            'qualification' => 'required|string|max:100',

            'guarantor1_first_name' => 'required|string|max:255',
            'guarantor1_last_name' => 'required|string|max:255',
            'guarantor1_email' => 'required|email|max:255',
            'guarantor1_phone' => 'required|string|digits:11',
            'guarantor1_nin' => 'required|string|digits:11',
            'guarantor1_relationship' => 'required|string|max:100',
            'guarantor1_occupation' => 'required|string|max:255',
            'guarantor1_address' => 'required|string|max:500',
            'guarantor1_passport_path' => 'required|image|mimes:jpeg,png,jpg|max:10240',
            'guarantor1_signature_path' => 'required|image|mimes:jpeg,png,jpg|max:10240',

            'guarantor2_first_name' => 'required|string|max:255',
            'guarantor2_last_name' => 'required|string|max:255',
            'guarantor2_email' => 'required|email|max:255',
            'guarantor2_phone' => 'required|string|digits:11',
            'guarantor2_nin' => 'required|string|digits:11',
            'guarantor2_relationship' => 'required|string|max:100',
            'guarantor2_occupation' => 'required|string|max:255',
            'guarantor2_address' => 'required|string|max:500',
            'guarantor2_passport_path' => 'required|image|mimes:jpeg,png,jpg|max:10240',
            'guarantor2_signature_path' => 'required|image|mimes:jpeg,png,jpg|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        DB::beginTransaction();

        try {

            $profilePhotoPath = null;
            
            dd([
                'has_file' => $request->hasFile('profile_photo_path'),
                'file' => $request->file('profile_photo_path'),
            ]);

            if ($request->hasFile('profile_photo_path')) {
                $profilePhotoPath = time() . '_profile.jpg';
                $image = Image::read($request->file('profile_photo_path'));
                // Resize while maintaining the original aspect ratio
                $image->scale(width: 800);
                // Convert to JPEG and compress
                $image->toJpeg(75)->save(
                    public_path('build/assets/image/profile_photo/' . $profilePhotoPath)
                );
            }

            $idCardPath = null;
            if ($request->hasFile('id_card')) {
                $idCardPath = time() . '_idcard.jpg';
                $image = Image::read($request->file('id_card'));
                // Resize while maintaining aspect ratio
                $image->scale(width: 1600);
                // Convert to JPEG and compress
                $image->toJpeg(80);
                // Save the processed image to a temporary file
                $tempPath = storage_path('app/' . $idCardPath);
                $image->save($tempPath);
                // Store the compressed image securely
                Storage::disk('local')->put(
                    \App\Support\SecureDocument::storageDirectory('idcards') . '/' . $idCardPath,
                    file_get_contents($tempPath)
                );

                // Remove temporary file
                unlink($tempPath);
            }

            $user = User::create([
                'name' => $validated['name'],
                'middle_name' => $validated['middle_name'] ?? null,
                'last_name' => $validated['last_name'] ?? null,
                'email' => $validated['email'],
                'account_type' => 'applicant',
                'applicant_type' => $validated['applicant_type'] ?? null,
                'phone' => $validated['phone'],
                'gender' => $validated['gender'] ?? null,
                'dob' => $validated['dob'],
                'state_of_origin' => $validated['state_of_origin'] ?? null,
                'state_of_residence' => $validated['state_of_residence'] ?? null,
                'lga_of_origin' => $validated['lga_of_origin'] ?? null,
                'country' => $validated['country'] ?? null,
                'country_of_residence' => $validated['country_of_residence'] ?? null,
                'years_of_experience' => $validated['years_of_experience'] ?? null,
                'profile_photo_path' => $profilePhotoPath,
                'address' => $validated['address'] ?? null,
                'landmark' => $validated['landmark'] ?? null,
                'nin' => $validated['nin'] ?? null,
                'id_card' => $idCardPath,
                'bio' => $validated['bio'] ?? null,
                'specialties' => $validated['specialties'] ?? null,
                'password' => bcrypt($validated['password']),
                'religion' => $validated['religion'] ?? null,
                'marital_status' => $validated['marital_status'] ?? null,
                'qualification' => $validated['qualification'] ?? null,
                'form_completed' => true,
                'guarantors_forms_uploaded' => true,
                'guarantors_forms_uploaded_2' => true,
                'terms_accepted' => true,
            ]);

            $guarantor1PassportPath = null;
            if ($request->hasFile('guarantor1_passport_path')) {
                $guarantor1PassportPath = time() . '_guarantor1Passport.' . $request->file('guarantor1_passport_path')->getClientOriginalExtension();
                $request->file('guarantor1_passport_path')->storeAs(\App\Support\SecureDocument::storageDirectory('passports'), $guarantor1PassportPath, 'local');
            }

            $guarantor1SignaturePath = null;
            if ($request->hasFile('guarantor1_signature_path')) {
                $guarantor1SignaturePath = time() . '_guarantor1Signature.' . $request->file('guarantor1_signature_path')->getClientOriginalExtension();
                $request->file('guarantor1_signature_path')->storeAs(\App\Support\SecureDocument::storageDirectory('signatures'), $guarantor1SignaturePath, 'local');
            }

            Guarantor::create([
                'applicant_id' => $user->id,
                'guarantor1_first_name' => $validated['guarantor1_first_name'],
                'guarantor1_last_name' => $validated['guarantor1_last_name'],
                'guarantor1_email' => $validated['guarantor1_email'],
                'guarantor1_phone' => $validated['guarantor1_phone'],
                'guarantor1_nin' => $validated['guarantor1_nin'],
                'guarantor1_relationship' => $validated['guarantor1_relationship'],
                'guarantor1_occupation' => $validated['guarantor1_occupation'],
                'guarantor1_address' => $validated['guarantor1_address'],
                'guarantor1_passport_path' => $guarantor1PassportPath,
                'guarantor1_signature_path' => $guarantor1SignaturePath,
            ]);

            $guarantor2PassportPath = null;
            if ($request->hasFile('guarantor2_passport_path')) {
                $guarantor2PassportPath = time() . '_guarantor2Passport.' . $request->file('guarantor2_passport_path')->getClientOriginalExtension();
                $request->file('guarantor2_passport_path')->storeAs(\App\Support\SecureDocument::storageDirectory('passports'), $guarantor2PassportPath, 'local');
            }

            $guarantor2SignaturePath = null;
            if ($request->hasFile('guarantor2_signature_path')) {
                $guarantor2SignaturePath = time() . '_guarantor2Signature.' . $request->file('guarantor2_signature_path')->getClientOriginalExtension();
                $request->file('guarantor2_signature_path')->storeAs(\App\Support\SecureDocument::storageDirectory('signatures'), $guarantor2SignaturePath, 'local');
            }

            Guarantor2::create([
                'applicant_id' => $user->id,
                'guarantor2_first_name' => $validated['guarantor2_first_name'],
                'guarantor2_last_name' => $validated['guarantor2_last_name'],
                'guarantor2_email' => $validated['guarantor2_email'],
                'guarantor2_phone' => $validated['guarantor2_phone'],
                'guarantor2_nin' => $validated['guarantor2_nin'],
                'guarantor2_relationship' => $validated['guarantor2_relationship'],
                'guarantor2_occupation' => $validated['guarantor2_occupation'],
                'guarantor2_address' => $validated['guarantor2_address'],
                'guarantor2_passport_path' => $guarantor2PassportPath,
                'guarantor2_signature_path' => $guarantor2SignaturePath,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => 'There was an error submitting the form. Please try again.'], 500);
        }

        $this->notifyAdminsAndWelcome($user, isStaff: true);

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user]);
    }

    private function notifyAdminsAndWelcome(User $user, bool $isStaff = false): void
    {
        try {
            Mail::to($user->email)->send(new WelcomeEmail($user));

            $admins = User::where('user_role', '>=', 1)->get();
            foreach ($admins as $admin) {
                $admin->notify($isStaff ? new NewStaffRegisteredNotification($user) : new NewClientRegisteredNotification($user));
            }
        } catch (\Exception $e) {
            // Account creation must not fail because of a mail/notification problem.
        }
    }
}
