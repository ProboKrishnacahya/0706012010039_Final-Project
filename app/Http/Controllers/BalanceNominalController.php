<?php

namespace App\Http\Controllers;

use App\Models\BalanceHistory;
use App\Models\UserSetting;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\BalanceNominal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BalanceNominalController extends Controller
{
    public function index()
    {
        $balance_history = BalanceHistory::where('user_id', Auth::user()->id)->get();
        $user_setting = UserSetting::where('vendor_id', Auth::user()->id)->first();
        $balance = BalanceNominal::where('user_id', Auth::user()->id)->first();

        $data = ['user_setting' => $user_setting, 'balance' => $balance, 'balance_history' => $balance_history];

        return view('pages.credit.index', $data);
    }

    public function topUp(Request $request)
    {
        try {
            // Retrieve the balance record or create a new one if it doesn't exist
            $balance = BalanceNominal::where('user_id', Auth::user()->id)->first();

            // Jika sudah pernah top up sebelumnya
            if ($balance->credit) {
                $topUp = $request->credit + $balance->credit;
            } else {
                // Jika belum top up
                $topUp = $request->credit;
            }

            $imageName = "";

            if ($request->hasFile('transaction_proof')) {
                $image = $request->file('transaction_proof');
                $imageName = Str::random(40) . '.' . $image->getClientOriginalExtension();
                Storage::disk('public_uploads_transaction_proof')->put($imageName, file_get_contents($image));
            }

            $payload = ['credit' => $topUp, 'transaction_proof' => $imageName];

            BalanceNominal::where('user_id', Auth::user()->id)->update($payload);

            $isiUlang = ['user_id' => Auth::user()->id, 'credit' => $request->credit];
            BalanceHistory::create($isiUlang);

            return back();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to save data: ' . $e->getMessage()], 500);
        }
    }

    public function cashOut(Request $request)
    {
        try {
            // Retrieve the balance record or create a new one if it doesn't exist
            $balance = BalanceNominal::where('user_id', Auth::user()->id)->first();

            // Jika sudah pernah cash out sebelumnya
            if ($balance->credit) {
                $cashOut = $balance->credit - $request->credit;
            } else {
                // Jika belum cash out
                $cashOut = $request->credit;
            }

            BalanceNominal::where('user_id', Auth::user()->id)->update(['credit' => $cashOut]);

            $payload = ['user_id' => Auth::user()->id, 'credit' => $request->credit];
            BalanceHistory::create($payload);

            return back();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to save data: ' . $e->getMessage()], 500);
        }
    }
}
