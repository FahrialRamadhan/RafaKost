<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kamar;
use App\Models\User;
use App\Models\NotificationLog;
use App\Services\FonnteService;
use App\Services\SettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

class KamarController extends Controller
{
    public function index()
    {
        $kamars = Kamar::latest()->get();
        return view('admin.kamars.index', compact('kamars'));
    }

    public function create()
    {
        return view('admin.kamars.create');
    }

    private function uploadSingleKamarImage($file): ?string
    {
        if (! $file) {
            return null;
        }

        $uploadPath = base_path('../storage/kamars');

        if (! File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        $file->move($uploadPath, $filename);

        return 'kamars/' . $filename;
    }

    private function uploadKamarImages(Request $request): array
    {
        $paths = [];

        if (! $request->hasFile('images')) {
            return $paths;
        }

        foreach ($request->file('images') as $file) {
            $path = $this->uploadSingleKamarImage($file);

            if ($path) {
                $paths[] = $path;
            }
        }

        return $paths;
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

    private function getExistingImages(Kamar $kamar): array
    {
        if (! $kamar->images) {
            return [];
        }

        $images = json_decode($kamar->images, true);

        return is_array($images) ? $images : [];
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'lantai' => 'required|string|max:255',
            'kamar_mandi' => 'required|string|max:255',
            'harga_1_orang' => 'required|integer|min:0',
			'harga_2_orang' => 'required|integer|min:0',
            'status' => 'required|in:tersedia,terisi',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
			'description' => 'nullable|string',
        ]);

        $mainImage = $request->hasFile('image')
            ? $this->uploadSingleKamarImage($request->file('image'))
            : null;

        $galleryImages = $this->uploadKamarImages($request);

        if (! $mainImage && count($galleryImages) > 0) {
            $mainImage = $galleryImages[0];
        }

		$kamar = Kamar::create([
		    'nama' => $request->nama,
		    'lantai' => $request->lantai,
		    'kamar_mandi' => $request->kamar_mandi,
		    'harga' => $request->harga_1_orang,
		    'harga_1_orang' => $request->harga_1_orang,
		    'harga_2_orang' => $request->harga_2_orang,
		    'status' => $request->status,
		    'image' => $mainImage,
		    'images' => json_encode($galleryImages),
			'description' => $request->description,
		]);

        // Kalau admin tambah kamar baru dengan status tersedia,
        // boleh langsung kirim notif kamar kosong.
        if ($kamar->status === 'tersedia') {
            $this->sendEmptyRoomNotification($kamar);
        }

        return redirect()->route('kamars.index')->with('success', 'Kamar berhasil ditambahkan');
    }

    public function edit(Kamar $kamar)
    {
        return view('admin.kamars.edit', compact('kamar'));
    }

    public function update(Request $request, Kamar $kamar)
    {
		$request->validate([
		    'nama' => 'required|string|max:255',
		    'lantai' => 'required|string|max:255',
		    'kamar_mandi' => 'required|string|max:255',
		    'harga_1_orang' => 'required|integer|min:0',
		    'harga_2_orang' => 'required|integer|min:0',
		    'status' => 'required|in:tersedia,terisi',
		    'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
		    'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
			'description' => 'nullable|string',
		]);

        $oldStatus = $kamar->status;

        $existingImages = $this->getExistingImages($kamar);
        $newImages = $this->uploadKamarImages($request);
        $allImages = array_merge($existingImages, $newImages);

		$data = [
		    'nama' => $request->nama,
		    'lantai' => $request->lantai,
		    'kamar_mandi' => $request->kamar_mandi,
		    'harga' => $request->harga_1_orang,
		    'harga_1_orang' => $request->harga_1_orang,
		    'harga_2_orang' => $request->harga_2_orang,
		    'status' => $request->status,
		    'images' => json_encode($allImages),
			'description' => $request->description,
		];

        if ($request->hasFile('image')) {
            $this->deletePublicFile($kamar->image);
            $data['image'] = $this->uploadSingleKamarImage($request->file('image'));
        } elseif (! $kamar->image && count($allImages) > 0) {
            $data['image'] = $allImages[0];
        }

        $kamar->update($data);

        // Kirim notif hanya kalau status berubah dari terisi ke tersedia.
        if ($oldStatus !== 'tersedia' && $request->status === 'tersedia') {
            $this->sendEmptyRoomNotification($kamar->fresh());
        }

        return redirect()->route('kamars.index')->with('success', 'Kamar berhasil diupdate');
    }

    private function sendEmptyRoomNotification(Kamar $kamar): void
    {
        $settings = app(SettingService::class);
        $fonnte = app(FonnteService::class);

        $users = User::query()
            ->where(function ($query) {
                $query->where('notify_empty_room_email', 1)
                    ->orWhere('notify_empty_room_whatsapp', 1);
            })
            ->get();

        foreach ($users as $user) {
			$template = $settings->get(
			    'template.empty_room',
			    'Halo {nama}, kamar {kamar} sekarang tersedia. Lantai: {lantai}. Kamar mandi: {kamar_mandi}. Harga: Rp {harga}. Silakan cek website Rafa Kost untuk melihat detail dan melakukan booking.'
			);
			
			$message = str_replace(
			    ['{nama}', '{kamar}', '{lantai}', '{kamar_mandi}', '{harga}'],
			    [
			        $user->name ?? 'Penyewa',
			        $kamar->nama ?? '-',
			        $kamar->lantai ?? '-',
			        $kamar->kamar_mandi ?? '-',
			        $kamar->harga ?? '-',
			    ],
			    $template
			);

            $sentForDate = today()->toDateString();

            if (
                $user->notify_empty_room_whatsapp
                && $user->phone
                && $settings->get('notification.whatsapp_enabled', false)
                && ! $this->alreadySentEmptyRoom($user->id, $kamar->id, 'whatsapp', $sentForDate)
            ) {
                $status = $fonnte->send($user->phone, $message);

                NotificationLog::create([
                    'booking_id' => null,
                    'user_id' => $user->id,
                    'kamar_id' => $kamar->id,
                    'channel' => 'whatsapp',
                    'type' => 'empty_room',
                    'target' => $user->phone,
                    'message' => $message,
                    'status' => $status ? 'success' : 'failed',
                    'response' => null,
                    'sent_for_date' => $sentForDate,
                    'sent_at' => now(),
                ]);
            }

            if (
                $user->notify_empty_room_email
                && $user->email
                && $settings->get('notification.email_enabled', false)
                && ! $this->alreadySentEmptyRoom($user->id, $kamar->id, 'email', $sentForDate)
            ) {
                try {
                    Mail::raw($message, function ($mail) use ($user, $kamar) {
                        $mail->to($user->email)
                            ->subject("Kamar {$kamar->nama} Rafa Kost Sekarang Tersedia");
                    });

                    NotificationLog::create([
                        'booking_id' => null,
                        'user_id' => $user->id,
                        'kamar_id' => $kamar->id,
                        'channel' => 'email',
                        'type' => 'empty_room',
                        'target' => $user->email,
                        'message' => $message,
                        'status' => 'success',
                        'response' => null,
                        'sent_for_date' => $sentForDate,
                        'sent_at' => now(),
                    ]);
                } catch (\Throwable $e) {
                    NotificationLog::create([
                        'booking_id' => null,
                        'user_id' => $user->id,
                        'kamar_id' => $kamar->id,
                        'channel' => 'email',
                        'type' => 'empty_room',
                        'target' => $user->email,
                        'message' => $message,
                        'status' => 'failed',
                        'response' => $e->getMessage(),
                        'sent_for_date' => $sentForDate,
                        'sent_at' => now(),
                    ]);
                }
            }
        }
    }

    private function alreadySentEmptyRoom(int $userId, int $kamarId, string $channel, string $sentForDate): bool
    {
        return NotificationLog::where('user_id', $userId)
            ->where('kamar_id', $kamarId)
            ->where('channel', $channel)
            ->where('type', 'empty_room')
            ->whereDate('sent_for_date', $sentForDate)
            ->exists();
    }

    public function destroy(Kamar $kamar)
    {
        $this->deletePublicFile($kamar->image);

        foreach ($this->getExistingImages($kamar) as $image) {
            $this->deletePublicFile($image);
        }

        $kamar->delete();

        return redirect()->route('kamars.index')->with('success', 'Kamar berhasil dihapus');
    }
}