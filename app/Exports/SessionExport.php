<?php

namespace App\Exports;

use App\Models\Session;
use Maatwebsite\Excel\Concerns\FromCollection;

class SessionExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $id = '';

    public function __construct($id){
        $this->id = $id;
    }

    public function collection()
    {
        $session = Session::with([
            'history'=>function($history){
                $history->with([
                    'question'=>function($question){
                        $question->with([
                            'choice'=>function($choice){
                                $choice->with([
                                    'choice'=>function($choiceOriginal){
                                        $choiceOriginal->with('keys');
                                        $choiceOriginal->with('attachment');
                                    }
                                ]);
                            }
                        ]);
                        $question->with([
                            'question'=>function($questionOriginal){
                                $questionOriginal->with('point');
                            }
                        ]);
                        $question->with('answer');
                        $question->with([
                            'questionData'=>function($questionData){
                                $questionData->with([
                                    'questionData'=>function($questionDataOriginal){
                                        $questionDataOriginal->with('questionAttachment');
                                    }
                                ]);
                            }
                        ]);
                    }
                ]);
            }
        ])
        ->with('user')
        ->with('exam')
        ->where('exam_id', $this->id)->get();
        // dd($session);
        $collect = collect();
        foreach($session as $key=>$unit){
            $result= "";
            if ($unit->exam->minimum == null || $unit->exam->minimum == 0) {
                $result = "Lulus";
            }elseif($unit->rate <= $unit->exam->minimum){
                $result = "Lulus";
            }else{
                $result = "Tidak Lulus";
            }
            $collect->push(['No.'=>$key+1, 'Nama'=>$unit->user->name,'Ujian'=>$unit->exam->exam, 'Nilai'=>$unit->rate, 'KKM'=>$unit->exam->minimum, 'Hasil'=>$result, 'Tanggal'=>$unit->created_at]);
        }
        // dd($collect);
        return $collect;
    }
}
