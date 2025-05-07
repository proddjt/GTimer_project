<?php

namespace App\Livewire;

use App\Models\Temp;
use App\Models\Profile;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Jobs\GenerateScramble;
use Illuminate\Support\Facades\Auth;
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
    public function render()
    {
        return view('livewire.timer');
    }

    public function mount(){
        $this->profile = Profile::where('id', 1)->first();
        $this->puzzle = $this->profile->selectedPuzzle;
        $this->scrambleTable = Temp::where('id', 1)->first();
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
        $this->scramble = str_replace(["\r", "\n"], '', $process->getOutput());
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
        $this->calculateStatistics();
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
        $this->dispatch('DOMRefresh');
    }

    #[On('timerStopped')]
    public function newScramble($time){
        if (!Auth::check()) {
            array_push($this->tempTimes, [$time, date('d-m-Y H:i:s'), $this->scramble, false]);
        }
        $this->calculateStatistics();
        $this->scramble = $this->scrambleTable->scramble;
        rename(base_path("public\img\scrambles\scramble-temp.svg"), base_path("public\img\scrambles\scramble.svg"));
        $this->newTempScramble($this->puzzle);
        $this->dispatch('DOMRefresh');
    }

    public function deleteTime($index){
        if (!Auth::check()) {
            unset($this->tempTimes[$index]);
        }
        $this->dispatch('DOMRefresh');
    }

    #[On('setPuzzle')]
    public function setPuzzle($puzzle){
        $this->puzzle = $puzzle;
    }

    public function addPenalty(){
        if (!Auth::check()) {
            $this->tempTimes[array_key_last($this->tempTimes)][0] += 2;
            $this->tempTimes[array_key_last($this->tempTimes)][3] = true;
        };
        $this->dispatch('DOMRefresh');
        $this->dispatch('resetTimer');
    }

    public function setDNF(){
        if (!Auth::check()) {
            $this->tempTimes[array_key_last($this->tempTimes)][0] = 'DNF';
            $this->tempTimes[array_key_last($this->tempTimes)][3] = true;
        };
        $this->dispatch('DOMRefresh');
        $this->dispatch('resetTimer');
    }

    public function calculateStatistics(){
        if (count($this->tempTimes) >= 100) {
            $last100 = array_column(array_slice($this->tempTimes, -100), 0);
            $min = min($last100);
            $max = max($last100);
            $filtered = array_diff($last100, [$min, $max]);
            $this->Ao100 = floor((array_sum($filtered) / count($filtered)) * 100) / 100;
            if($this->Ao100 < $this->bestAo100 || !$this->bestAo100){
                $this->bestAo100 = $this->Ao100;
            }
        }
        if (count($this->tempTimes) >= 50) {
            $last50 = array_column(array_slice($this->tempTimes, -50), 0);
            $min = min($last50);
            $max = max($last50);
            $filtered = array_diff($last50, [$min, $max]);
            $this->Ao50 = floor((array_sum($filtered) / count($filtered)) * 100) / 100;
            if($this->Ao50 < $this->bestAo50 || !$this->bestAo50){
                $this->bestAo50 = $this->Ao50;
            }
        }
        if (count($this->tempTimes) >= 12) {
            $last12 = array_column(array_slice($this->tempTimes, -12), 0);
            $min = min($last12);
            $max = max($last12);
            $filtered = array_diff($last12, [$min, $max]);
            $this->Ao12 = floor((array_sum($filtered) / count($filtered)) * 100) / 100;
            if($this->Ao12 < $this->bestAo12 || !$this->bestAo12){
                $this->bestAo12 = $this->Ao12;
            }
        }
        if (count($this->tempTimes) >= 5) {
            $last5 = array_column(array_slice($this->tempTimes, -5), 0);
            $min = min($last5);
            $dnfCount = 0;
            foreach ($last5 as $time) {
                if ($time == 'DNF') {
                    $max = 'DNF';
                    $dnfCount++;
                }
            }
            if($dnfCount <= 0){
                $max = max($last5);
            }
            if ($dnfCount == 1 || $dnfCount == 0){
                $filtered = array_diff($last5, [$min, $max]);
                $this->Ao5 = floor((array_sum($filtered) / count($filtered) * 100)) / 100;
                if($this->Ao5 < $this->bestAo5 || !$this->bestAo5){
                    $this->bestAo5 = $this->Ao5;
                }
            }else{
                $this->Ao5 = 'DNF';
            }
        }
        if (count($this->tempTimes) >= 3) {
            $last3 = array_column(array_slice($this->tempTimes, -5), 0);
            $this->mo3 = floor((array_sum($last3) / count($last3)) * 100) / 100;
            if($this->mo3 < $this->bestMo3 || !$this->bestMo3){
                $this->bestMo3 = $this->mo3;
            }
        }
        if (count($this->tempTimes) >= 1) {
            $this->single = $this->tempTimes[array_key_last($this->tempTimes)][0];
            if ($this->single < $this->bestSingle || !$this->bestSingle) {
                $this->bestSingle = $this->single;
            }
            $this->mean = floor((array_sum(array_column($this->tempTimes, 0)) / count($this->tempTimes)) * 100) / 100;
        } 
    }
}
