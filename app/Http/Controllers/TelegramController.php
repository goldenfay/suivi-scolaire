<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prof;
use Illuminate\Support\Facades\DB;

class TelegramController extends Controller
{


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:prof');
    }



    public function addChannel(Request $request, $classId, $profId)
    {
        $validatedData = $request->validate([
            'telegram_channel' => 'required|url',
        ]);

        $target = DB::table('class_prof_telegram')->where('class_id', '=', $classId);
        if ($target) {
            return response(json_encode([
                "flag" => "fail",
                "message" => "Un compte pour cette classe existe déjà"
            ]), 422);
        }
        DB::table('class_prof_telegram')->insert([
            'class_id' => $classId,
            'prof_id' => $profId,
            'telegram_channel' => $request->get('telegram_channel'),
        ]);


        return response(json_encode([
            "flag" => "success",
            "message" => "Compte Telegram ajouté avec succès."

        ]), 200);
    }

    public function deleteChannel($classId, $profId)
    {
        DB::table('class_prof_telegram')->delete([
            'class_id' => $classId,
            'prof_id' => $profId,
        ]);

        return response()->json([
            'message' => 'Compte Telegram supprimé avec succès.',
        ]);
    }
}
