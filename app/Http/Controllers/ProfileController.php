<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        if ($request->user()->role === 'admin') {
            return view('profile.admin.edit', [
                'user' => $request->user(),
            ]);
        }

        return view('profile.user.edit', [
            'user' => $request->user(),
        ]);
    }

    private function uploadProfilePhoto(Request $request): ?string
    {
        if (! $request->hasFile('photo')) {
            return null;
        }

        $file = $request->file('photo');

        if (! $file || ! $file->isValid()) {
            return null;
        }

        $uploadPath = base_path('../storage/profile-photos');

        if (! File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        $file->move($uploadPath, $filename);

        return 'profile-photos/' . $filename;
    }

    private function deletePublicFile(?string $path): void
    {
        if (! $path) {
            return;
        }

        $filePath = base_path('../storage/' . $path);

        if (File::exists($filePath)) {
            File::delete($filePath);
        }
    }

	public function update(ProfileUpdateRequest $request): RedirectResponse
	{
	    $user = $request->user();
	
	    $validated = $request->validated();
	
	    unset($validated['photo'], $validated['remove_photo']);
	
	    if ($request->boolean('remove_photo')) {
	        $this->deletePublicFile($user->photo);
	        $validated['photo'] = null;
	    }
	
	    if ($request->hasFile('photo')) {
	        $file = $request->file('photo');
	
	        if ($file && $file->isValid()) {
	            $this->deletePublicFile($user->photo);
	
	            $photoPath = $this->uploadProfilePhoto($request);
	
	            if ($photoPath) {
	                $validated['photo'] = $photoPath;
	            }
	        }
	    }
	
	    $validated['phone'] = $request->phone;
	    $validated['notify_empty_room_email'] = $request->boolean('notify_empty_room_email');
	    $validated['notify_empty_room_whatsapp'] = $request->boolean('notify_empty_room_whatsapp');
	
	    $user->forceFill($validated);
	
	    if ($user->isDirty('email')) {
	        $user->email_verified_at = null;
	    }
	
	    $user->save();
	
	    return Redirect::route('profile.edit')->with('status', 'profile-updated');
	}
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        $this->deletePublicFile($user->photo);

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}