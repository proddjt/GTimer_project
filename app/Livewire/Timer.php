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
        $this->dispatch('scrambleGenerated');
        $this->newTempScramble($this->puzzle);
    }

    public function newTempScramble($puzzle){
        dispatch(new GenerateScramble($puzzle));
    }

    public function updatedPuzzle()
    {
        $this->profile->update(['selectedPuzzle' => $this->puzzle]);
        $this->profile->save();
        $this->generateFirstScramble($this->puzzle);
        $this->dispatch('DOMRefresh');
    }

    #[On('timerStopped')]
    public function newScramble($time){
        if (!Auth::check()) {
            array_push($this->tempTimes, [$time, date('Y-m-d H:i:s'), $this->scramble]);
        }
        $this->scramble = $this->scrambleTable->scramble;
        $this->newTempScramble($this->puzzle);
        $this->dispatch('DOMRefresh');
    }

    public function deleteTime($index){
        if (!Auth::check()) {
            unset($this->tempTimes[$index]);
        }
        $this->dispatch('DOMRefresh');
    }
}
