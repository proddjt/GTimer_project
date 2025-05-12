<?php

namespace App\Livewire;

use App\Models\Temp;
use App\Models\Profile;
use Livewire\Component;
use App\Exports\TimeExport;
use Livewire\Attributes\On;
use App\Jobs\GenerateScramble;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Timer extends Component
{
    public $puzzle;
    public $scramble;
    public $profile;
    public $scrambleTable;
    public $tempTimes = [];
    public $single = null;
    public $bestSingle = null;
    public $mo3 = null;
    public $bestMo3 = null;
    public $ao5 = null;
    public $bestAo5 = null;
    public $ao12 = null;
    public $bestAo12 = null;
    public $ao50 = null;
    public $bestAo50 = null;
    public $ao100 = null;
    public $bestAo100 = null;
    public $mean = null;
    public $userTimes;
    public $validTimes;
    public $timestamp;
    
    public function render()
    {
        return view('livewire.timer');
    }

    public function mount(){
        $this->profile = Profile::where('id', 1)->first();
        $this->puzzle = $this->profile->selectedPuzzle;
        $this->scrambleTable = Temp::where('id', 1)->first();
        $this->userTimes = Auth::check() ? Auth::user()->times()->where('puzzle', $this->puzzle)->get() : null;
        $this->timestamp = now()->timestamp;
    }

    public function exportSession($extension){
        if (!Auth::check()) {
            $array = $this->tempTimes;
            if ($extension == 'csv') {
                $filename = 'guest' . '-' . $this->puzzle . '-temp-times-' . date('d-m-Y') . '.csv';
            }else{
                
                $filename = 'guest' . '-' . $this->puzzle . '-temp-times-' . date('d-m-Y') . '.xlsx';
            }
        }else{
            $array = $this->userTimes->map(function ($item) {
                return array_values($item->toArray());
            })->toArray();
            if ($extension == 'csv') {
                $filename = Auth::user()->name . '-' . $this->puzzle . '-times-' . date('d-m-Y') . '.csv';
            }else{
                $filename = Auth::user()->name . '-' . $this->puzzle . '-times-' . date('d-m-Y') . '.xlsx';
            }
        }
        $export = new TimeExport([$array]);
        if ($extension == 'csv') {
            return Excel::download($export, $filename, \Maatwebsite\Excel\Excel::CSV);
        }else{
            return Excel::download($export, $filename, \Maatwebsite\Excel\Excel::XLSX);
        }
        
    }

    public function deleteSession(){
        if (!Auth::check()) {
            $this->tempTimes = [];
        }else{
            Auth::user()->times()->where('puzzle', $this->puzzle)->delete();
            $this->userTimes = Auth::user()->times()->where('puzzle', $this->puzzle)->get();
        }
        $this->calculateStatistics($this->tempTimes);
        $this->dispatch('DOMRefresh');
    }

    #[On('firstLoad')]
    public function generateFirstScramble()
    {
        $process = new Process([
            'tnoodle.bat',
            'scramble',
            '-p',
            $this->puzzle
        ]);
        $process->setWorkingDirectory(base_path("tnoodle-cli-win_x64\bin"));
        $process->run();
        $this->scramble = $process->getOutput();
        $process = new Process([
            'tnoodle.bat',
            'draw',
            '-p',
            $this->puzzle,
            '-s',
            $this->scramble,
            '-o',
            '../../public/img/scrambles/scramble.svg'
        ]);
        $process->setWorkingDirectory(base_path("tnoodle-cli-win_x64\bin"));
        $process->run();
        if(Auth::check()){
            $userTimes = Auth::user()->times()->where('puzzle', $this->puzzle)->get();
            $arrayForStats = $this->userTimes->map(function ($item) {
                return array_values($item->toArray());
            })->toArray();
        }else{
            $arrayForStats = $this->tempTimes;
        }
        foreach ($arrayForStats as $time) {
            $time[0] = $this->timeToFloatSeconds($time[0]);
        }
        $this->calculateStatistics($arrayForStats);
        $this->timestamp = now()->timestamp;
        $this->dispatch('scrambleGenerated');
        $this->newTempScramble($this->puzzle);
    }

    public function newTempScramble($puzzle){
        dispatch(new GenerateScramble($puzzle));
    }

    #[On('puzzleChanged')]
    public function puzzleChanged()
    {
        $this->profile->update(['selectedPuzzle' => $this->puzzle]);
        $this->profile->save();
        if (!Auth::check()) {
            $this->tempTimes = [];
        }
        if(Auth::check()){
            $this->userTimes = Auth::user()->times()->where('puzzle', $this->puzzle)->get();
            $arrayForStats = $this->userTimes->map(function ($item) {
                return array_values($item->toArray());
            })->toArray();
        }else{
            $arrayForStats = $this->tempTimes;
        }
        foreach ($arrayForStats as $time) {
            $time[0] = $this->timeToFloatSeconds($time[0]);
        }
        $this->calculateStatistics($arrayForStats);
        $this->dispatch('DOMRefresh');
    }

    #[On('timerStopped')]
    public function newScramble($time){
        if (!Auth::check()) {
            array_push($this->tempTimes, [$time, date('d-m-Y H:i:s'), $this->scramble, false, false]);
            $arrayForStats = $this->tempTimes;
        }else{
            Auth::user()->times()->create(['time' => $time, 'date' => date('d-m-Y H:i:s'), 'scramble' => $this->scramble, 'hasPenalty' => false, 'hasDNF' => false, 'puzzle' => $this->puzzle]);
            $this->userTimes = Auth::user()->times()->where('puzzle', $this->puzzle)->get();
            $arrayForStats = $this->userTimes->map(function ($item) {
                return array_values($item->toArray());
            })->toArray();
        }
        foreach ($arrayForStats as $time) {
            $time[0] = $this->timeToFloatSeconds($time[0]);
        }
        $this->calculateStatistics($arrayForStats);
        $this->scramble = $this->scrambleTable->scramble;
        rename(base_path("public\img\scrambles\scramble-temp.svg"), base_path("public\img\scrambles\scramble.svg"));
        $this->timestamp = now()->timestamp;
        $this->newTempScramble($this->puzzle);
        $this->dispatch('DOMRefresh');
    }

    public function deleteTime($index){
        if (!Auth::check()) {
            unset($this->tempTimes[$index]);
            $arrayForStats = $this->tempTimes;
        }else{
            $this->userTimes[$index]->delete();
            $this->userTimes = Auth::user()->times()->where('puzzle', $this->puzzle)->get();
            $arrayForStats = $this->userTimes->map(function ($item) {
                return array_values($item->toArray());
            })->toArray();
        }
        foreach ($arrayForStats as $time) {
            $time[0] = $this->timeToFloatSeconds($time[0]);
        }
        $this->calculateStatistics($arrayForStats);
        $this->dispatch('DOMRefresh');
    }

    #[On('setPuzzle')]
    public function setPuzzle($puzzle){
        $this->puzzle = $puzzle;
        $this->userTimes = Auth::check() ? Auth::user()->times()->where('puzzle', $this->puzzle)->get() : null;
    }

    public function addPenalty($index){
        if (!Auth::check()) {
            $this->tempTimes[$index][4] = false;
            $this->tempTimes[$index][3] = true;
            $arrayForStats = $this->tempTimes;
        }else{
            $this->userTimes[$index]->update(['hasPenalty' => true]);
            $this->userTimes[$index]->update(['hasDNF' => false]);
            $this->userTimes[$index]->save();
            $this->userTimes = Auth::user()->times()->where('puzzle', $this->puzzle)->get();
            $arrayForStats = $this->userTimes->map(function ($item) {
                return array_values($item->toArray());
            })->toArray();
        }
        $this->calculateStatistics($arrayForStats);
        $this->dispatch('DOMRefresh');
    }

    public function setDNF($index){
        if (!Auth::check()) {
            $this->tempTimes[$index][3] = false;
            $this->tempTimes[$index][4] = true;
            $arrayForStats = $this->tempTimes;
        }else{
            $this->userTimes[$index]->update(['hasDNF' => true]);
            $this->userTimes[$index]->update(['hasPenalty' => false]);
            $this->userTimes[$index]->save();
            $this->userTimes = Auth::user()->times()->where('puzzle', $this->puzzle)->get();
            $arrayForStats = $this->userTimes->map(function ($item) {
                return array_values($item->toArray());
            })->toArray();
        }
        $this->dispatch('DOMRefresh');
    }

    public function setOK($index){
        if (!Auth::check()){
            $this->tempTimes[$index][4] = false;
            $this->tempTimes[$index][3] = false;
            $arrayForStats = $this->tempTimes;
        }else{
            $this->userTimes[$index]->update(['hasDNF' => false]);
            $this->userTimes[$index]->update(['hasPenalty' => false]);
            $this->userTimes[$index]->save();
            $this->userTimes = Auth::user()->times()->where('puzzle', $this->puzzle)->get();
            $arrayForStats = $this->userTimes->map(function ($item) {
                return array_values($item->toArray());
            })->toArray();
        }
        $this->calculateStatistics($arrayForStats);
        $this->dispatch('DOMRefresh');
    }

    public function timeToFloatSeconds($time) {
        $time = str_replace(',', '.', $time);
        $parts = explode(':', $time);

        $hours = 0;
        $minutes = 0;
        $seconds = 0;
        $centiseconds = 0;

        if (count($parts) === 3) {
            $hours = (int) $parts[0];
            $minutes = (int) $parts[1];
            list($seconds, $centiseconds) = $this->explodeCenti($parts[2]);
        } elseif (count($parts) === 2) {
            $minutes = (int) $parts[0];
            list($seconds, $centiseconds) = $this->explodeCenti($parts[1]);
        } elseif (count($parts) === 1) {
            list($seconds, $centiseconds) = $this->explodeCenti($parts[0]);
        }

        return ($hours * 3600) + ($minutes * 60) + $seconds + ($centiseconds / 100);
    }

    public function explodeCenti($part) {
        if (strpos($part, '.') !== false) {
            list($s, $c) = explode('.', $part);
            return [(int) $s, (int) substr($c . '00', 0, 2)]; // assicura due cifre
        } else {
            return [(int) $part, 0];
        }
    }

    public function formatTime(float $seconds){
        $centiseconds = round(($seconds - floor($seconds)) * 100);
        $totalSeconds = floor($seconds);
        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $secs = $totalSeconds % 60;
        if ($seconds < 60) {
            return sprintf("%02d.%02d", $secs, $centiseconds);
        } elseif ($seconds < 3600) {
            return sprintf("%02d:%02d.%02d", $minutes, $secs, $centiseconds);
        } else {
            return sprintf("%02d:%02d:%02d.%02d", $hours, $minutes, $secs, $centiseconds);
        }
    }


    public function calculateStatistics($array){
        // AO100 AND BEST AO100
        if (count($array) >= 100) {
            $last100Times = array_slice($array, -100);
            $last100 = array_column(array_slice($array, -100), 0);
            $min = [];
            $max = [];
            $dnfCount = 0;
            foreach ($last100Times as $time) {
                if ($time[4] == true) {
                    $dnfCount++;
                    if ( $dnfCount < 5){
                        array_push($max, $time[0]);
                    }
                }
            }
            if ($dnfCount <= 0) {
                $max = array_slice(rsort($last100), 0, 5);
            }
            if ($dnfCount <= 5) {
                $min = array_slice(sort($last100), 0, 5);
                $minMax = array_merge($min, $max);
                $filtered = array_diff($last100, $minMax);
                $this->Ao100 = $this->formatTime(floor((array_sum($filtered) / count($filtered)) * 100) / 100);
            }else{
                $this->Ao100 = "DNF";
            }
            $this->bestAo100 = null;
            for ($i = 0; $i <= count($array) - 100; $i++) {
                $current100Times = array_slice($array, $i, 100);
                $current100 = array_column($current100Times, 0);
                $currentMin = [];
                $currentMax = [];
                $currentDnfCount = 0;
                foreach ($current100Times as $time) {
                    if ($time[4] == true) {
                        $currentDnfCount++;
                        if ( $currentDnfCount < 5){
                            array_push($currentMax, $time[0]);
                        }
                    }
                }
                if ($currentDnfCount <= 0) {
                    $currentMax = array_slice(rsort($current100), 0, 5);
                }
                if ($currentDnfCount <= 5) {
                    $currentMin = array_slice(sort($current100), 0, 5);
                    $currentMinMax = array_merge($currentMin, $currentMax);
                    $currentFiltered = array_diff($current100, $currentMinMax);
                    $currentAo100 = floor((array_sum($currentFiltered) / count($currentFiltered)) * 100) / 100;
                    if ($currentAo100 < $this->timeToFloatSeconds($this->bestAo100) || !$this->bestAo100) {
                        $this->bestAo100 = $this->formatTime($currentAo100);
                    }
                }
            }
        }else{
            $this->Ao100 = null;
            $this->bestAo100 = null;
        }

        // AO50 AND BEST AO50
        if (count($array) >= 50) {
            $last50Times = array_slice($array, -50);
            $last50 = array_column($last50Times, 0);
            $min = [];
            $max = [];
            $dnfCount = 0;
            foreach ($last50Times as $time) {
                if ($time[4] == true) {
                    $dnfCount++;
                    if ( $dnfCount < 3){
                        array_push($max, $time[0]);
                    }
                }
            }
            if ($dnfCount <= 0) {
                $max = array_slice(rsort($last50), 0, 3);
            }
            if ($dnfCount <= 3) {
                $min = array_slice(sort($last50), 0, 3);
                $minMax = array_merge($min, $max);
                $filtered = array_diff($last50, $minMax);
                $this->Ao50 = $this->formatTime(floor((array_sum($filtered) / count($filtered)) * 100) / 100);
            }else{
                $this->Ao50 = 'DNF';
            }
            $this->bestAo50 = null;
            for ($i = 0; $i <= count($array) - 50; $i++) {
                $current50Times = array_slice($array, $i, 50);
                $current50 = array_column($current50Times, 0);
                $currentMin = [];
                $currentMax = [];
                $currentDnfCount = 0;
                foreach ($current50Times as $time) {
                    if ($time[4] == true) {
                        $currentDnfCount++;
                        if ( $currentDnfCount < 3){
                            array_push($currentMax, $time[0]);
                        }
                    }
                }
                if ($currentDnfCount <= 0) {
                    $currentMax = array_slice(rsort($current50), 0, 3);
                }
                if ($currentDnfCount <= 3) {
                    $currentMin = array_slice(sort($current50), 0, 3);
                    $currentMinMax = array_merge($currentMin, $currentMax);
                    $currentFiltered = array_diff($current50, $currentMinMax);
                    $currentAo50 = floor((array_sum($currentFiltered) / count($currentFiltered)) * 100) / 100;
                    if($currentAo50 < $this->timeToFloatSeconds($this->bestAo50) || !$this->bestAo50){
                        $this->bestAo50 = $this->formatTime($currentAo50);
                    }
                }
            }
        }else{
            $this->Ao50 = null;
            $this->bestAo50 = null;
        }

        // AO12 AND BEST AO12
        if (count($array) >= 12) {
            $last12Times = array_slice($array, -12);
            $last12 = array_column($last12Times, 0);
            $min = 0;
            $max = 0;
            $dnfCount = 0;
            foreach ($last12Times as $time) {
                if ($time[4] == true) {
                    $max = $time[0];
                    $dnfCount++;
                }
            }
            if ($dnfCount <= 0) {
                $max = max($last12);
            }
            if ($dnfCount <= 1) {
                $min = min($last12);
                $filtered = array_diff($last12, [$min, $max]);
                $this->Ao12 = $this->formatTime(floor((array_sum($filtered) / count($filtered)) * 100) / 100);
            }else{
                $this->Ao12 = 'DNF';
            }
            $this->bestAo12 = null;
            for ($i = 0; $i <= count($array) - 12; $i++) {
                $current12Times = array_slice($array, $i, 12);
                $current12 = array_column($current12Times, 0);
                $currentMin = 0;
                $currentMax = 0;
                $currentDnfCount = 0;
                foreach ($current12Times as $time) {
                    if ($time[4] == true) {
                        $currentMax = $time[0];
                        $currentDnfCount++;
                    }
                }
                if ($currentDnfCount <= 0) {
                    $currentMax = max($current12);
                }
                if ($currentDnfCount <= 1) {
                    $currentMin = min($current12);
                    $currentFiltered = array_diff($current12, [$currentMin, $currentMax]);
                    $currentAo12 = floor((array_sum($currentFiltered) / count($currentFiltered)) * 100) / 100;
                    if ($currentAo12 < $this->timeToFloatSeconds($this->bestAo12) || !$this->bestAo12) {
                        $this->bestAo12 = $this->formatTime($currentAo12);
                    }
                }
            }
        }else{
            $this->Ao12 = null;
            $this->bestAo12 = null;
        }

        // AO5 AND BEST AO5
        if (count($array) >= 5) {
            $last5Times = array_slice($array, -5);
            $last5 = array_column($last5Times, 0);
            $min = 0;
            $max = 0;
            $dnfCount = 0;
            foreach ($last5Times as $time) {
                if ($time[4] == true) {
                    $max = $time[0];
                    $dnfCount++;
                }
            }
            if($dnfCount <= 0){
                $max = max($last5);
            }
            if ($dnfCount <= 1){
                $min = min($last5);
                $filtered = array_diff($last5, [$min, $max]);
                $this->Ao5 = $this->formatTime(floor((array_sum($filtered) / count($filtered)) * 100) / 100);;
            }else{
                $this->Ao5 = 'DNF';
            }
            $this->bestAo5 = null;
            for ($i = 0; $i <= count($array) - 5; $i++) {
                $current5Times = array_slice($array, $i, 5);
                $current5 = array_column($current5Times, 0);
                $currentMin = 0;
                $currentMax = 0;
                $currentDnfCount = 0;
                foreach ($current5Times as $time) {
                    if ($time[4] == true) {
                        $currentMax = $time[0];
                        $currentDnfCount++;
                    }
                }
                if ($currentDnfCount <= 0) {
                    $currentMax = max($current5);
                }
                if ($currentDnfCount <= 1) {
                    $currentMin = min($current5);
                    $currentFiltered = array_diff($current5, [$currentMin, $currentMax]);
                    $currentAo5 = floor((array_sum($currentFiltered) / count($currentFiltered)) * 100) / 100;
                    if ($currentAo5 < $this->timeToFloatSeconds($this->bestAo5) || !$this->bestAo5) {
                        $this->bestAo5 = $this->formatTime($currentAo5);
                    }
                }
            }
        }else{
            $this->Ao5 = null;
            $this->bestAo5 = null;
        }

        // MO3 AND BEST MO3
        if (count($array) >= 3) {
            $this->mo3 = null;
            $last3Times = array_slice($array, -3);
            $last3 = array_column($last3Times, 0);
            $sum = 0;
            foreach ($last3Times as $time) {
                if ($time[4] == true) {
                    $this->mo3 = 'DNF';
                }else if ($time[3] == true) {
                    $sum += $time[0] + 2;
                }else{
                    $sum += $time[0];
                }
            }
            if ($this->mo3 != 'DNF') {
                $this->mo3 = $this->formatTime(floor(($sum / count($last3)) * 100) / 100);
            }
            $this->bestMo3 = null;
            for ($i = 0; $i <= count($array) - 3; $i++) {
                $current3Times = array_slice($array, $i, 3);
                $current3 = array_column($current3Times, 0);
                $currentmo3 = 0;
                $sum = 0;
                foreach ($current3Times as $time) {
                    if ($time[4] == true) {
                        $currentmo3 = 'DNF';
                    }else if ($time[3] == true) {
                        $sum += $time[0] + 2;
                    }else{
                        $sum += $time[0];
                    }
                }
                if ($currentmo3 != 'DNF') {
                    $currentmo3 = floor(($sum / count($current3)) * 100) / 100;
                    if($currentmo3 < $this->timeToFloatSeconds($this->bestMo3) || !$this->bestMo3){
                        $this->bestMo3 = $this->formatTime($currentmo3);
                    }
                }
            }
        }else{
            $this->mo3 = null;
            $this->bestMo3 = null;
        }

        // SINGLE AND BEST SINGLE
        if (count($array) >= 1) {
            if ($array[array_key_last($array)][4] == true) {
                $this->single = 'DNF';
            }else if ($array[array_key_last($array)][3] == true) {
                $this->single = $this->formatTime($array[array_key_last($array)][0] + 2);
            }else{
                $this->single = $this->formatTime($array[array_key_last($array)][0]);
            }
            $this->bestSingle = null;
            for ($i = 0; $i < count($array); $i++) {
                if ($array[$i][4] == false){
                    if ($array[$i][3] == false) {
                        if ($array[$i][0] < $this->timeToFloatSeconds($this->bestSingle) || !$this->bestSingle) {
                            $this->bestSingle = $this->formatTime($array[$i][0]);
                        }
                    }else{
                        if ($array[$i][0] + 2 < $this->timeToFloatSeconds($this->bestSingle) || !$this->bestSingle) {
                            $this->bestSingle = $this->formatTime($array[$i][0] + 2);
                        }
                    }
                }
            }
            $dnfTotalCount = 0;
            foreach($array as $time) {
                if ($time[4] == true){
                    $dnfTotalCount++;
                }
            }
            $this->validTimes = count($array) - $dnfTotalCount;
            $count = 0;
            $sum = 0;
            foreach ($array as $time) {
                if ($time[4] == false){
                    if ($time[3] == true){
                        $sum += $time[0]+2;
                        $count++;
                    }else{
                        $sum += $time[0];
                        $count++;
                    }
                }
            }
            if ($count == 0) {
                $this->mean = null;
            }else{
                $this->mean = $this->formatTime(floor(($sum / $count) * 100) / 100);
            }
        }else{
            $this->single = null;
            $this->bestSingle = null;
            $this->mean = null;
        }
    }
}
