<?php

namespace App\Http\Controllers;

use App\Models\cr;
use Illuminate\Http\Request;
use App\Models\Signature;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class SignatureController extends Controller
{

    public function signature()
    {
        
        $signature = Signature::all();
        return view('signature.signature', compact('signature'));
    }

    public function addsignature()
    {
        return view('signature.addsignature');
    }
  
    public function addsignaturedata(Request $req)
    {
        $req->validate([
            'signature' => 'required', // Ensure the signature field is required
        ]);
          // Check if any signature already exists
     $existingSignature = Signature::first();

    if ($existingSignature) {
        // If a signature exists, return an error message
        return redirect()->route('signature')->with('danger', 'signature already exists');
    }


        $signature = new Signature();
        $signature->employee_id = Auth::user()->id;
        $signature->signature = $req->input('signature'); // Save the signature data
        $signature->save();

        return redirect()->route('signature')->with('success', 'Signature saved successfully');


      
    }
 
    public function deletesignature($id)
    {
        
            
        $model = Signature::findOrFail($id);
        $model->delete();
        return redirect()->route('signature')->with('success', 'Signature deleted successfully');  
    }

   
    public function edit(cr $cr)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\cr  $cr
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, cr $cr)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\cr  $cr
     * @return \Illuminate\Http\Response
     */
    public function destroy(cr $cr)
    {
        //
    }
}
