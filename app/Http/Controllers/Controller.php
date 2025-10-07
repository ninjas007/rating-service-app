<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use App\Models\Loger;
use App\Models\Patient;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function logError($e)
    {
        if (config('app.debug')) {
            dd($e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine());
        }
        Log::error($e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine());
    }

    public function logInfo($message)
    {
        if (config('app.debug') == false) {
            Log::info($message);
        }
    }

    public function saveLogDb($message, $data = [], $columnTableId = [])
    {
        if (isset($columnTableId['no_lab'])) {
            $loger = Loger::where('column_table_id->no_lab', $columnTableId['no_lab'])->first();

            if (count($data) == 0) {
                $loger->status = 'fixed';
                $loger->save();
                return;
            }

            if ($loger) {
                $loger->update([
                    'message' => $message,
                    'data' => json_encode($data),
                    'column_table_id' => json_encode($columnTableId),
                ]);
            } else {
                Loger::create([
                    'message' => $message,
                    'data' => json_encode($data),
                    'column_table_id' => json_encode($columnTableId),
                ]);
            }
        }
    }

    public function checkLoger($data = [])
    {
        if (isset($data['no_lab'])) {
            return Loger::where('column_table_id->no_lab', $data['no_lab'])->first();
        }
    }

    public function calculateAge($date)
    {
        $birthDate = new \DateTime($date);
        $now = new \DateTime();
        $interval = $now->diff($birthDate);

        return $interval->y;
    }

    public function calculateDayFromDateBirthText($date)
    {
        // tahun hari bulan
        $birthDate = new \DateTime($date);
        $now = new \DateTime();
        $interval = $now->diff($birthDate);

        return $interval->y . ' thn ' . $interval->m . ' bln ' . $interval->d . ' hr';
    }
}
