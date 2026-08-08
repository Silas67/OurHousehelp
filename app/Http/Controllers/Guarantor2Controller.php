<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use App\Models\Guarantor2;

use App\Models\GuarantorArchive;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;

use App\Models\User;

use Barryvdh\DomPDF\Facade\Pdf;



class Guarantor2Controller extends Controller

{



    public function submitGuarantor2(Request $request)

    {

        $request->validate([

            'guarantor2_first_name' => 'required|string|max:255',

            'guarantor2_last_name' => 'required|string|max:255',

            'guarantor2_email' => 'nullable|email|max:255',

            'guarantor2_phone' => 'required|string|digits:11', // Updated to ensure exactly 11 digits

            'guarantor2_relationship' => 'required|string|max:100',

            'guarantor2_occupation' => 'required|string|max:255',

            'guarantor2_address' => 'required|string|max:500',

            'guarantor2_nin' => 'required|string|digits:11', // Exactly 11 digits

            'guarantor2_passport_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

            'guarantor2_signature_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

        ]);



        // Retrieve the authenticated user

        $user = Auth::user();



        // Check if Guarantor 2 exists

        $guarantor = Guarantor2::firstOrNew(['applicant_id' => $user->id, 'guarantor2_number' => 2]);



        // Archive the existing data before updating

        if ($guarantor->exists) {

            GuarantorArchive::create([

                'applicant_id' => $guarantor->applicant_id,

                'guarantor_number' => $guarantor->guarantor2_number,

                'first_name' => $guarantor->guarantor2_first_name,

                'last_name' => $guarantor->guarantor2_last_name,

                'email' => $guarantor->guarantor2_email,

                'phone' => $guarantor->guarantor2_phone,

                'relationship' => $guarantor->guarantor2_relationship,

                'occupation' => $guarantor->guarantor2_occupation,

                'address' => $guarantor->guarantor2_address,

                'nin' => $guarantor->guarantor2_nin,

                'passport_path' => $guarantor->guarantor2_passport_path,

                'signature_path' => $guarantor->guarantor2_signature_path,

            ]);
        }



        // Update the fields for Guarantor 2

        $guarantor->guarantor2_first_name = $request->guarantor2_first_name;

        $guarantor->guarantor2_last_name = $request->guarantor2_last_name;

        $guarantor->guarantor2_email = $request->guarantor2_email ?? null;

        $guarantor->guarantor2_phone = $request->guarantor2_phone;

        $guarantor->guarantor2_relationship = $request->guarantor2_relationship;

        $guarantor->guarantor2_occupation = $request->guarantor2_occupation;

        $guarantor->guarantor2_address = $request->guarantor2_address;

        $guarantor->guarantor2_nin = $request->guarantor2_nin;




        // Handle passport upload

        if ($request->hasFile('guarantor2_passport_path')) {

            $passportFile = $request->file('guarantor2_passport_path');

            $passportFilename = time() . '_passport.' . $passportFile->getClientOriginalExtension();

            $passportFile->move(public_path('build/assets/image/passports'), $passportFilename);

            $guarantor->guarantor2_passport_path = $passportFilename;
        }



        // Handle signature upload

        if ($request->hasFile('guarantor2_signature_path')) {

            $signatureFile = $request->file('guarantor2_signature_path');

            $signatureFilename = time() . '_signature.' . $signatureFile->getClientOriginalExtension();

            $signatureFile->move(public_path('build/assets/image/signatures'), $signatureFilename);

            $guarantor->guarantor2_signature_path = $signatureFilename;
        }


        // Save the guarantor data

        $guarantor->save();


        // Update the user's guarantors_forms_uploaded_2 status

        $user->update(['guarantors_forms_uploaded_2' => true]);

        // $user->guarantors_forms_uploaded_2 = true;

        // $user->save();


        return redirect()->route('terms.conditions', [

            'user_id' => $user->id,

            'name' => $user->name,

        ])->with('success', 'Guarantor 2 information updated successfully.');
    }









    // Download PDF for Guarantor 2

    public function downloadGuarantor2Pdf($user_id)

    {

        $user = Auth::user();

        $applicant = User::findOrFail($user_id);



        // Ensure the authenticated user is the same as the applicant

        if ($user->id !== $applicant->id) {

            return redirect()->back()->with('error', 'Unauthorized access.');
        }



        $guarantor = Guarantor2::where('applicant_id', $user_id)->first();



        if (!$guarantor) {

            return redirect()->back()->with('error', 'Guarantor 2 not found.');
        }



        // Pass both `guarantor` and `applicant` to the view

        $pdf = Pdf::loadView('pdfs.guarantor', compact('guarantor', 'applicant'));



        return $pdf->download('Guarantor2_' . $guarantor->first_name . '_' . $guarantor->last_name . '.pdf');
    }
}
