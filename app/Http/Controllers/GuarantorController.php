<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use App\Models\Guarantor;

use App\Models\Guarantor2;

use App\Models\GuarantorArchive;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;

use App\Models\User;

use Barryvdh\DomPDF\Facade\Pdf;





class GuarantorController extends Controller

{

    // Show guarantor form

    public function showForm($user_id, $name)

    {

        $user = Auth::user();

        $applicant = User::findOrFail($user_id);



        if ($user->id !== $applicant->id) {

            return redirect()->back()->with('error', 'Unauthorized access.');
        }



        if ($user->guarantors_forms_uploaded && $user->guarantors_forms_uploaded_2 && $user->terms_accepted) {

            return redirect()->route('applicantdashboard')->with('info', 'You have already uploaded the guarantor forms.');
        }



        // Retrieve guarantor data from separate models

        $guarantor1 = Guarantor::where('applicant_id', $user_id)->first();

        $guarantor2 = Guarantor2::where('applicant_id', $user_id)->first();



        return view('applicant.guarantors', compact('user', 'user_id', 'name', 'applicant', 'guarantor1', 'guarantor2'));
    }



    public function showFormguarantors_two($user_id, $name)

    {

        $user = Auth::user();

        $applicant = User::findOrFail($user_id);



        if ($user->id !== $applicant->id) {

            return redirect()->back()->with('error', 'Unauthorized access.');
        }



        if ($user->guarantors_forms_uploaded && $user->guarantors_forms_uploaded_2 && $user->terms_accepted) {

            return redirect()->route('applicantdashboard')->with('info', 'You have already uploaded the guarantor forms.');
        }



        // Retrieve guarantor data from separate models

        $guarantor1 = Guarantor::where('applicant_id', $user_id)->first();

        $guarantor2 = Guarantor2::where('applicant_id', $user_id)->first();



        return view('applicant.guarantors_two', compact('user', 'user_id', 'name', 'applicant', 'guarantor1', 'guarantor2'));
    }





    public function submitGuarantor1(Request $request)

    {

        $request->validate([

            'guarantor1_first_name' => 'required|string|max:255',

            'guarantor1_last_name' => 'required|string|max:255',

            'guarantor1_email' => 'nullable|email|max:255',

            'guarantor1_phone' => 'required|string|digits:11', // Exactly 11 digits

            'guarantor1_relationship' => 'required|string|max:100',

            'guarantor1_occupation' => 'required|string|max:255',

            'guarantor1_address' => 'required|string|max:500',

            'guarantor1_nin' => 'required|string|digits:11', // Exactly 11 digits

            'guarantor1_passport_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

            'guarantor1_signature_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

        ]);



        // Retrieve the authenticated user

        $user = Auth::user();



        // Check if Guarantor 1 exists or create a new instance

        $guarantor = Guarantor::firstOrNew(['applicant_id' => $user->id]);



        // Archive the existing guarantor data if present

        if ($guarantor->exists) {

            GuarantorArchive::create([

                'applicant_id' => $guarantor->applicant_id,

                'guarantor_number' => $guarantor->guarantor1_number,

                'first_name' => $guarantor->guarantor1_first_name,

                'last_name' => $guarantor->guarantor1_last_name,

                'email' => $guarantor->guarantor1_email,

                'phone' => $guarantor->guarantor1_phone,

                'relationship' => $guarantor->guarantor1_relationship,

                'occupation' => $guarantor->guarantor1_occupation,

                'address' => $guarantor->guarantor1_address,

                'nin' => $guarantor->guarantor1_nin,

                'passport_path' => $guarantor->guarantor1_passport_path,

                'signature_path' => $guarantor->guarantor1_signature_path,

            ]);
        }



        // Update guarantor data

        $guarantor->guarantor1_first_name = $request->guarantor1_first_name;

        $guarantor->guarantor1_last_name = $request->guarantor1_last_name;

        $guarantor->guarantor1_email = $request->guarantor1_email;

        $guarantor->guarantor1_phone = $request->guarantor1_phone;

        $guarantor->guarantor1_relationship = $request->guarantor1_relationship;

        $guarantor->guarantor1_occupation = $request->guarantor1_occupation;

        $guarantor->guarantor1_address = $request->guarantor1_address;

        $guarantor->guarantor1_nin = $request->guarantor1_nin;



        // Handle passport upload

        if ($request->hasFile('guarantor1_passport_path')) {

            $passportFile = $request->file('guarantor1_passport_path');

            $passportFilename = time() . '_passport.' . $passportFile->getClientOriginalExtension();

            $passportFile->move(public_path('build/assets/image/passports'), $passportFilename);

            $guarantor->guarantor1_passport_path = $passportFilename;
        }



        // Handle signature upload

        if ($request->hasFile('guarantor1_signature_path')) {

            $signatureFile = $request->file('guarantor1_signature_path');

            $signatureFilename = time() . '_signature.' . $signatureFile->getClientOriginalExtension();

            $signatureFile->move(public_path('build/assets/image/signatures'), $signatureFilename);

            $guarantor->guarantor1_signature_path = $signatureFilename;
        }



        // Save the guarantor data

        $guarantor->save();



        // Update the user's guarantors_forms_uploaded status

        $user->update(['guarantors_forms_uploaded' => true]);



        return redirect()->route('applicant.guarantors_two', [

            'user_id' => $user->id,

            'name' => $user->name,

        ])->with('success', 'Guarantor 1 information updated successfully.');
    }





    public function downloadGuarantor1Pdf($user_id)

    {

        $user = Auth::user();

        $applicant = User::findOrFail($user_id);



        // Ensure the authenticated user is the same as the applicant

        if ($user->id !== $applicant->id) {

            return redirect()->back()->with('error', 'Unauthorized access.');
        }



        $guarantor = Guarantor::where('applicant_id', $user_id)->first();



        if (!$guarantor) {

            return redirect()->back()->with('error', 'Guarantor 1 not found.');
        }



        // Pass both `guarantor` and `applicant` to the view

        $pdf = Pdf::loadView('pdfs.guarantor', compact('guarantor', 'applicant'));



        return $pdf->download('Guarantor1_' . $guarantor->first_name . '_' . $guarantor->last_name . '.pdf');
    }





    // GuarantorController.php

    public function showArchivedGuarantors($userId)

    {

        // Fetch archived guarantor records for the given user with pagination

        $user = User::with(['guarantors', 'guarantor2s'])->findOrFail($userId);

        $archivedGuarantors = GuarantorArchive::where('applicant_id', $userId)->paginate(10); // Set number per page



        return view('archived', compact('user', 'archivedGuarantors'));
    }
}
