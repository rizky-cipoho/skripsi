<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Traits\ExamTrait;
use App\Models\User;
use App\Models\History;
use App\Models\Lesson;
use App\Models\Session;
use App\Models\Exam;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    use ExamTrait;
    public function resultExam()
    {
        return History::where('user_id', Auth::user()->id)->get()->count();
    }
    public function authNow()
    {
        return User::with('attachment')->find(Auth::user()->id);
    }

    public function calculation($id)
    {
        $favorites = History::where('user_id', $id)->get();
        $favoriteArr = collect([]);
        foreach ($favorites as $favoriteData) {
            $favoriteArr->push($favoriteData->exam);
        }
        $favoriteCountBy = $favoriteArr->countBy('lesson_id');
        return $favoriteCountBy;
    }
    public function lessonRand($removeData)
    {
        $lesson = Lesson::get();
        return rand(0, $lesson->count() - 1);
    }
    public function seconds($recommend)
    {
        $calculation = $this->calculation(Auth::user()->id);
        $calculation->sortDesc();
        $reject = $this->favoriteProsess(Auth::user()->id);
        $remove = $calculation->reject(function ($value, $key) use ($reject) {
            return $key == $reject;
        });
        $rejectThen = collect([]);


        $lesson = Lesson::where('lesson', $recommend)->first();
        $lessonGet = Lesson::get();
        foreach ($remove as $key => $then) {
            $rejectThen->push($key);
        }
        if ($recommend != 'Semuanya') {
            $rejectThen->push($lesson->id);
        }
        // }
        $collect = collect([]);
        $userIdArr = collect([Auth::user()->id]);
        $history = History::where('user_id', Auth::user()->id)->get();

        $ages = collect([]);
        $now = date('Y');
        // dump((int)$now - 10);
        $sd = collect([]);
        $smp = collect([]);
        $sma = collect([]);
        $nowCountSD = (int)$now;
        $nowCountSMP = (int)$now - 13;
        $nowCountSMA = (int)$now - 16;
        $nowCountMahasiswa = (int)$now - 17;
        for ($i = 0; $i < 13; $i++) {
            $nowCountSD--;
            $sd->push($nowCountSD);
        }
        for ($i = 0; $i < 3; $i++) {
            $nowCountSMP--;
            $smp->push($nowCountSMP);
        }
        for ($i = 0; $i < 3; $i++) {
            $nowCountSMA--;
            $sma->push($nowCountSMA);
        }
        $userY = date('Y', Auth::user()->birth / 1000);
        if ($sd->contains($userY)) {
            $ages->push('SD');
        }elseif ($smp->contains($userY)) {
            $ages->push('SMP');
        }elseif ($sma->contains($userY)) {
            $ages->push('SMA');
        }else{
            $ages->push('Mahasiswa');
        }

        foreach ($lessonGet as $index) {
            $lesson = collect(["lesson" => "", "data" => collect([])]);
            $search = Exam::whereNotIn('lesson_id', $rejectThen)
                ->where('lesson_id', $index->id)
                ->where('remove', null)
                ->whereNotIn('id', $history->pluck('exam_id'))
                ->whereNotIn('user_id', $userIdArr)
                ->with('attachment')
                ->with('user')
                ->with('lesson')
                ->whereIn('tier', $ages)->get();
            foreach ($search as $unit) {
                if (!$search->isEmpty()) {
                    // dump($this->examNotReady($unit->id)->isEmpty());
                    if ($this->examNotReady($unit->id)->isEmpty()) {
                        $lesson['data']->push(...$search);
                        $lesson['lesson'] = $index->lesson;
                        $collect->push($lesson);
                    }
                }
                if ($lesson['data']->count() == 6) {
                    continue;
                }
            }
        }
        // dd($collect);
        // dd($lesson);           

        return $collect;
    }
    public function dashboard(Request $request)
    {
        $session = Session::with([
            'history' => function ($history) {
                $history->with([
                    'question' => function ($question) {
                        $question->with([
                            'choice' => function ($choice) {
                                $choice->with([
                                    'choice' => function ($choiceOriginal) {
                                        $choiceOriginal->with("keys");
                                        $choiceOriginal->with('attachment');
                                    }
                                ]);
                            }
                        ]);
                        $question->with([
                            'question' => function ($questionOriginal) {
                                $questionOriginal->with('point');
                            }
                        ]);
                        $question->with('answer');
                        $question->with([
                            'questionData' => function ($questionData) {
                                $questionData->with([
                                    'questionData' => function ($questionDataOriginal) {
                                        $questionDataOriginal->with('questionAttachment');
                                    }
                                ]);
                            }
                        ]);
                    }
                ]);
            }
        ])->where('user_id', Auth::user()->id)->get();
        // dd($session);
        $result = 0;
        $examArr = collect([]);
        foreach ($session as $unit) {
            if ($examArr->contains($unit->exam_id) == false) {
                $examArr->push($unit->exam_id);
                foreach ($unit->history->question as $question) {
                    if ($question->answer == null) {
                        continue;
                    }
                    foreach ($question->choice as $choice) {
                        if ($choice->choice->keys != null) {
                            if ($question->answer->choice_id == $choice->id) {
                                $result = $result + (int)$question->question->point->point;
                            }
                        }
                    }
                }
            }
        }
        return Inertia::render('Dashboard', [
            'authNow' => User::with('attachment')->find(Auth::user()->id),
            'resultExam' => $this->resultExam(),
            'favorite' => $this->favorite(),
            'recommendations' => $this->recommendations(),
            'seconds' => $this->seconds($this->favorite()),
            'point' => $result,
        ]);
    }
    public function search(Request $request)
    {
        $exam = Exam::where('uni_code', $request->dataSearch)->first();
        if ($exam == null) {
            return response()->json([
                'message' => "Kode Salah"
            ]);
        } else {
            return $exam;
        }
    }
    public function searchKeys($value)
    {
        $history = History::where('user_id', Auth::user()->id)->get();
        $collect = collect([]);
        foreach ($history as $unit) {
            $collect->push($unit->exam_id);
        }
        $ages = collect([]);
        $now = date('Y');
        // dump((int)$now - 10);
        $sd = collect([]);
        $smp = collect([]);
        $sma = collect([]);
        $nowCountSD = (int)$now - 5;
        $nowCountSMP = (int)$now - 16;
        $nowCountSMA = (int)$now - 14;
        $nowCountMahasiswa = (int)$now - 17;
        for ($i = 0; $i < 6; $i++) {
            $nowCountSD--;
            $sd->push($nowCountSD);
        }
        for ($i = 0; $i < 4; $i++) {
            $nowCountSMP++;
            $smp->push($nowCountSMP);
        }
        for ($i = 0; $i < 6; $i++) {
            $nowCountSMA--;
            $sma->push($nowCountSMA);
        }

        $userY = date('Y', Auth::user()->birth / 1000);
        if ($sd->contains($userY)) {
            $ages->push('SD');
            if ($smp->contains($userY)) {
                $ages->push('SMP');
            }
        } elseif ($smp->contains($userY)) {
            $ages->push('SMP');
            if ($sma->contains($userY)) {
                $ages->push('SMA');
            }
        } elseif ($sma->contains($userY)) {
            $ages->push('SMA');
            if ($nowCountMahasiswa <= $userY) {
                $ages->push('Mahasiswa');
            }
        } else {
            if ($userY <= $nowCountMahasiswa) {
                $ages->push('Mahasiswa');
            } else {
                $ages->push('SD');
            }
        }

        $exam = Exam::where('exam', 'like', "%" . $value . "%")
            ->whereNot('user_id', Auth::user()->id)
            ->whereNotIn('id', $collect)
            ->where('remove', null)
            ->with('attachment')
            ->with('user')
            ->with('lesson')
            ->whereIn('tier', $ages)->get();

        $arr = collect([]);
        foreach ($exam as $unit) {
            if ($this->examNotReady($unit->id)->isEmpty()) {
                $arr->push($unit);
            }
            if ($arr->count() == 20) {
                break;
            }
        }

        return Inertia::render('exam/search', [
            'data' => $arr,
        ]);
    }
}
