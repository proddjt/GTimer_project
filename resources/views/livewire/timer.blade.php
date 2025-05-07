<div>
    <div class="container" id="timer-page">
        <div class="row">
            <div class="col-2">
                <img src="/img/scrambles/scramble.svg?timestamp={{ now()->timestamp }}" alt="Immagine scramble">
            </div>
            <div class="col-10">
                Qui c'è lo scramble {{$scramble}}
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <select name="puzzle" wire:model.live="puzzle" id="puzzle-select">
                    <option value="three" @if ($profile->selectedPuzzle == 'three') selected @endif>3x3x3</option>
                    <option value="two" @if ($profile->selectedPuzzle == 'two') selected @endif>2x2x2</option>
                    <option value="four" @if ($profile->selectedPuzzle == 'four') selected @endif>4x4x4</option>
                    <option value="five" @if ($profile->selectedPuzzle == 'five') selected @endif>5x5x5</option>
                    <option value="six" @if ($profile->selectedPuzzle == 'six') selected @endif>6x6x6</option>
                    <option value="seven" @if ($profile->selectedPuzzle == 'seven') selected @endif>7x7x7</option>
                    <option value="skewb" @if ($profile->selectedPuzzle == 'skewb') selected @endif>Skewb</option>
                    <option value="clock" @if ($profile->selectedPuzzle == 'clock') selected @endif>Clock</option>
                    <option value="mega" @if ($profile->selectedPuzzle == 'mega') selected @endif>Megaminx</option>
                    <option value="pyra" @if ($profile->selectedPuzzle == 'pyra') selected @endif>Pyraminx</option>
                    <option value="sq1" @if ($profile->selectedPuzzle == 'sq1') selected @endif>Square-1</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <p id="timer"></p>
                <button id="reset">Reset</button>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <p class="fw-bold">Lista di tempi</p>
                @foreach ($tempTimes as $time)
                    <div class="row">
                        <div class="col-1">{{$loop->iteration}}.</div><div class="col-11">{{$time[0]}} {{$time[1]}} {{$time[2]}} <button wire:click="deleteTime({{$loop->index}})">Delete</button></div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
