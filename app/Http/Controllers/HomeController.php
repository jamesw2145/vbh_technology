<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\MeasureComment;
use App\MeasureDTL;
use App\MeasureHDR;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function formSubmit(Request $request) {
        $now = Carbon::now();

        $entry_id = $request->input('entry_id');
        $action_type = $request->input('action_type');

        $prod_date = $request->input('production_date');
        $doc_no = $request->input('doc_no');
        $technician = $request->input('technician');

        $measurements = $request->only(['item_id', 'hose_item_id', 'measure_type', 'measure_uom', 'hose_date_code', 'qty_produced']);
        $qty_produced = $request->input('qty_produced');

        $comment = $request->input('comment');

        $inspection1 = $request->input('inspection')[0];
        $inspection2 = $request->input('inspection')[1];
        
        if(!$entry_id) {
            $id = DB::select("SHOW TABLE STATUS LIKE 'measure_hdr'");
            $next_id=$id[0]->Auto_increment;
            $entry_id = $next_id;

            $primary_fields = [
                'entry_id' => $entry_id,
                'date_created' => $now,
                'created_by' => $technician,
            ];

            MeasureHDR::create(
                array_merge([
                    'production_date' => Carbon::parse($prod_date),
                    'doc_no' => $doc_no,
                    'technician' => $technician,
                ], $primary_fields)
            );

            MeasureComment::create(
                array_merge(['comment' => $comment], $primary_fields)
            );
        } else {
            $measure_hdr = MeasureHDR::where('entry_id', $entry_id)->first();
            $measure_hdr->update([
                    'production_date' => $now,
                    'doc_no' => $doc_no,
                    'technician' => $technician,
                ]
            );

            if($qty_produced) {
                $mesure_dtls = MeasureDTL::where('entry_id', $entry_id)->get();

                foreach ($mesure_dtls as $key => $value) {
                    $value->qty_produced = $qty_produced;
                    $value->save();
                }
            }            
            

            $measure_comment = MeasureComment::where('entry_id', $entry_id)->first();
            $measure_comment->update(
                ['comment' => $comment]
            );
        }

        $primary_fields = [
            'entry_id' => $entry_id,
            'date_created' => $now,
            'created_by' => $technician,
        ];

        $inspection1 = MeasureDTL::create(
            array_merge($measurements, $inspection1, $primary_fields)
        );

        $inspection2 = MeasureDTL::create(
            array_merge($measurements, $inspection2, $primary_fields)
        );

        return response()->json([
            'action_type' => $action_type,
            'entry_id' => $entry_id,
            'inspection1' => $inspection1,
            'inspection2' => $inspection2,
        ]);
    }
}
