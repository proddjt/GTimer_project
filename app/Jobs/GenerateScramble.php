<?php

namespace App\Jobs;

use App\Models\Temp;
use Symfony\Component\Process\Process;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class GenerateScramble implements ShouldQueue
{
    use Queueable;

    private $puzzle;
    private $scramble;
    private $scrambleTable;

    /**
     * Create a new job instance.
     */
    public function __construct($puzzle)
    {
        $this->puzzle = $puzzle;
        $this->scrambleTable = Temp::where('id', 1)->first();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
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
        $this->scrambleTable->update(['scramble' => $this->scramble]);
        $this->scrambleTable->save();
    }
}
