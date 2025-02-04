<?php 

use App\Http\Controllers\BinController;
use Illuminate\Routing\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Bin;

Route::post('/trash-bin', function (Request $request) {
    $bin = new Bin();
    $bin->status = $request->input('fullness_status'); // "penuh" atau "tidak penuh"
    $bin->fill_percentage = $request->input('fill_percentage'); // Persentase
    $bin->save();
    return response()->json(['message' => 'Data berhasil disimpan!'], 200);
});


Route::post('/open-bin', [BinController::class, 'store']);
