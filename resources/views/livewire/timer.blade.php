<div>
    <div class="container-fluid px-5 min-vh-100" id="timer-page">
        <div class="row px-5 py-2">
            <div class="col-6">
                <h1 class="fw-semibold fst-italic"><span class="text-highlight">GT</span>imer</h1>
            </div>
            <div class="col-6 d-flex justify-content-end align-items-center">
                @guest
                <a href="{{route('login')}}">
                    <button class="button-highlight" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="Accedi al tuo account" data-bs-placement="bottom"><i class="fa-solid fa-right-to-bracket pe-1"></i> Accedi</button>    
                </a>
                <a href="{{route('register')}}">
                    <button class="button-main mx-3" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="Crea il tuo account" data-bs-placement="bottom"><i class="fa-solid fa-plus pe-1"></i> Registrati</button>    
                </a>
                <button class="button-main" data-bs-toggle="modal" data-bs-target="#settingsModal" id="settingsBtn"><i class="fa-solid fa-gear pe-1"></i> Impostazioni</button>
                @endguest
                @auth
                <div class="dropdown">
                    <button class="button-highlight dropdown-toggle text-uppercase fw-semibold" type="button" data-bs-toggle="dropdown"><i class="fa-solid fa-user pe-1"></i> {{Auth::user()->name}}</button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" id="settingsBtn" data-bs-toggle="modal" data-bs-target="#settingsModal"><i class="fa-solid fa-gear pe-1"></i> Impostazioni</a></li>
                        <li><form action="{{route('logout')}}" method="POST">@csrf<button type="submit" class="dropdown-item"><i class="fa-solid fa-right-from-bracket pe-1"></i> Esci</button></form></li>
                    </ul>
                </div>
                @endauth
            </div>
        </div>
        <div class="row">
            <div class="col-10">
                <div class="row">
                    <div class="col-3 d-flex justify-content-end align-items-center">
                        <i class='bx bxs-hand bx-flip-horizontal hand hand-inactive' id="leftHand"></i>
                    </div>
                    <div class="col-6 d-flex justify-content-center align-items-center">
                        <p id="timer" class="fw-bold m-0"></p>
                    </div>
                    <div class="col-3 d-flex align-items-center">
                        <i class='bx bxs-hand hand hand-inactive' id="rightHand"></i>
                    </div>
                    <div class="col-12 d-flex justify-content-center align-items-center pb-3">
                        <div id="set" class="inactive-led rounded-circle mx-1"></div>
                        <div id="go" class="inactive-led rounded-circle mx-1"></div>
                    </div>
                    <div class="col-12 d-flex justify-content-center align-items-center">
                        <button id="reset" class="button-highlight d-inline-block" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="Reset timer" data-bs-placement="bottom">Reset</button>
                        <button class="button-main mx-4 d-inline-block" id="penaltyBtn" data-bs-toggle="modal" data-bs-target="#penaltyModal">+2</button>
                        <button class="button-main d-inline-block" id="dnfBtn" data-bs-toggle="modal" data-bs-target="#dnfModal">DNF</button>
                    </div>
                </div>
                <div class="row pt-3">
                    <div class="col-2 d-flex justify-content-center align-items-center">
                        <img src="/img/scrambles/scramble.svg?timestamp={{ now()->timestamp }}" alt="Immagine scramble" class="w-100">
                    </div>
                    <div class="col-10 d-flex justify-content-center align-items-center">
                        @if ($puzzle == 'mega')
                        <p class="m-0"><pre class="fw-semibold scramble text-center">{{$scramble}}</pre></p>
                        @else
                        <p class="fw-semibold scramble text-center m-0">{{$scramble}}</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-2 d-flex align-items-center justify-content-center flex-column">
                <div class="puzzle-box p-3 d-flex flex-column justify-content-center align-items-center">
                    <p class="d-inline-flex gap-1 px-5 m-0">
                        <button class="button-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#puzzleList">
                            <span class="cubing-icon main-puzzle
                            @if ($puzzle == 'three') event-333 
                            @elseif ($puzzle == 'two') event-222
                            @elseif ($puzzle == 'four') event-444
                            @elseif ($puzzle == 'five') event-555
                            @elseif ($puzzle == 'six') event-666
                            @elseif ($puzzle == 'seven') event-777
                            @elseif ($puzzle == 'skewb') event-skewb
                            @elseif ($puzzle == 'clock') event-clock
                            @elseif ($puzzle == 'mega') event-minx
                            @elseif ($puzzle == 'pyra') event-pyram
                            @elseif ($puzzle == 'sq1') event-sq1
                            @endif
                            " data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="Seleziona puzzle" data-bs-placement="bottom">
                        </span>
                    </button>
                    <div class="collapse p-1" id="puzzleList">
                        <div class="collapse-body row fs-2 justify-content-around align-items-center">
                            <a class="collapse-item col-3 d-flex justify-content-center align-items-center" href="#" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="3x3x3" data-bs-placement="bottom" puzzle="three"><span class="cubing-icon sec-puzzle event-333"></span></a>
                            <a class="collapse-item col-3 d-flex justify-content-center align-items-center" href="#" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="2x2x2" data-bs-placement="bottom" puzzle="two"><span class="cubing-icon sec-puzzle event-222"></span></a>
                            <a class="collapse-item col-3 d-flex justify-content-center align-items-center" href="#" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="4x4x4" data-bs-placement="bottom" puzzle="four"><span class="cubing-icon sec-puzzle event-444"></span></a>
                            <a class="collapse-item col-3 d-flex justify-content-center align-items-center" href="#" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="5x5x5" data-bs-placement="bottom" puzzle="five"><span class="cubing-icon sec-puzzle event-555"></span></a>
                            <a class="collapse-item col-3 d-flex justify-content-center align-items-center" href="#" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="6x6x6" data-bs-placement="bottom" puzzle="six"><span class="cubing-icon sec-puzzle event-666"></span></a>
                            <a class="collapse-item col-3 d-flex justify-content-center align-items-center" href="#" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="7x7x7" data-bs-placement="bottom" puzzle="seven"><span class="cubing-icon sec-puzzle event-777"></span></a>
                            <a class="collapse-item col-3 d-flex justify-content-center align-items-center" href="#" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="Skewb" data-bs-placement="bottom" puzzle="skewb"><span class="cubing-icon sec-puzzle event-skewb"></span></a>
                            <a class="collapse-item col-3 d-flex justify-content-center align-items-center" href="#" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="Clock" data-bs-placement="bottom" puzzle="clock"><span class="cubing-icon sec-puzzle event-clock"></span></a>
                            <a class="collapse-item col-3 d-flex justify-content-center align-items-center" href="#" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="Megaminx" data-bs-placement="bottom" puzzle="mega"><span class="cubing-icon sec-puzzle event-minx"></span></a>
                            <a class="collapse-item col-3 d-flex justify-content-center align-items-center" href="#" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="Pyraminx" data-bs-placement="bottom" puzzle="pyra"><span class="cubing-icon sec-puzzle event-pyram"></span></a>
                            <a class="collapse-item col-3 d-flex justify-content-center align-items-center" href="#" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="Square-1" data-bs-placement="bottom" puzzle="sq1"><span class="cubing-icon sec-puzzle event-sq1"></span></a>
                        </div>
                    </div>
                </p>
            </div>
        </div>   
    </div>
    <div class="row mb-2">
            <div class="col-10 mt-3 pb-3 p-2 times-box">
                <div class="row text-center text-uppercase">
                    <div class="col-12 fw-bold fs-4 d-flex justify-content-center align-items-center">
                        Lista tempi
                        <button class="button-highlight mx-4" id="exportBtn" data-bs-toggle="modal" data-bs-target="#exportModal"><i class="fa-solid fa-download pe-1"></i>Esporta sessione</button>
                        <button class="button-main" id="deleteSessionBtn" data-bs-toggle="modal" data-bs-target="#deleteSessionModal"><i class="fa-solid fa-trash-can pe-1"></i>Cancella sessione</button>
                    </div>
                    <div class="col-1 fw-semibold pt-2">N.</div>
                    <div class="col-2 fw-semibold pt-2">Tempo</div>
                    <div class="col-2 fw-semibold pt-2">Data e ora</div>
                    <div class="col-5 fw-semibold pt-2">Scramble</div>
                    <div class="col-2 fw-semibold pt-2">Azioni</div>
                </div>
                @guest
                @foreach ($tempTimes as $time)
                <div class="row pt-1 text-center align-items-center">
                    <div class="col-1 fw-semibold fst-italic fs-5">{{$loop->iteration}}.</div>
                    <div class="col-2 fw-semibold fst-italic fs-5">@if ($time[3]) @if ($time[0] >= 3600) {{$this->formatTime(($time[0]+2))}} @else {{$this->formatTime(($time[0]+2))}} @endif (P) @elseif ($time[4]) DNF @else @if ($time[0] >= 3600) {{$this->formatTime($time[0])}} @else {{$this->formatTime($time[0])}} @endif @endif</div>
                    <div class="col-2 fw-semibold fst-italic fs-5">{{$time[1]}}</div>
                    <div class="col-5 fw-semibold fst-italic fs-5">{{$time[2]}}</div>
                    <div class="col-2">
                        <button wire:click="deleteTime({{$loop->index}})" class="button-main"><i class="fa-solid fa-trash"></i></button>
                        <button wire:click="setDNF({{$loop->index}})" class="button-main fw-bold" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="Assegna DNF" data-bs-placement="top">DNF</button>
                        <button wire:click="addPenalty({{$loop->index}})" class="button-main fw-bold" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="Aggiungi +2" data-bs-placement="top">+2</button>
                        <button wire:click="setOK({{$loop->index}})" class="button-main fw-bold" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="No penalità" data-bs-placement="top">OK</button>
                    </div>
                </div>
                @endforeach
                @else
                @foreach ($userTimes as $time)
                <div class="row pt-2 text-center align-items-center">
                    <div class="col-1 fw-semibold fst-italic fs-5">{{$loop->iteration}}.</div>
                    <div class="col-2 fw-semibold fst-italic fs-5">@if ($time->hasPenalty) @if ($time->time >= 3600) {{$this->formatTime(($time->time+2))}} @else {{$this->formatTime(($time->time+2))}} @endif (P) @elseif ($time->hasDNF) DNF @else @if ($time->time >= 3600) {{$this->formatTime($time->time)}} @else {{$this->formatTime($time->time)}} @endif @endif</div>
                    <div class="col-2 fw-semibold fst-italic fs-5">{{$time->date}}</div>
                    <div class="col-5 fw-semibold fst-italic small">{{$time->scramble}}</div>
                    <div class="col-2 small">
                        <button wire:click="deleteTime({{$loop->index}})" class="button-main"><i class="fa-solid fa-trash"></i></button>
                        <button wire:click="setDNF({{$loop->index}})" class="button-main fw-bold" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="Assegna DNF" data-bs-placement="top">DNF</button>
                        <button wire:click="addPenalty({{$loop->index}})" class="button-main fw-bold" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="Aggiungi +2" data-bs-placement="top">+2</button>
                        <button wire:click="setOK({{$loop->index}})" class="button-main fw-bold" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="No penalità" data-bs-placement="top">OK</button>
                    </div>
                </div>
                @endforeach
                @endguest
            </div>
            <div class="col-2 mt-3 pb-3 p-3 statistics-box">
                <div class="d-flex flex-column justify-content-center align-items-center">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="text-uppercase text-center fw-bold pb-2">Statistiche e record</h5>
                            <div class="single-statistic my-2 p-1">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="fw-semibold text-center text-uppercase statistic-title">Sessione</div>
                                    </div>
                                </div>
                                <div class="row text-center">
                                    <div class="col-6 fw-semibold fst-italic statistic-title">N. solve</div>
                                    <div class="col-6 fw-semibold fst-italic statistic-title">Mean</div>
                                    @guest
                                    <div class="col-6 statistic-content">@if ($tempTimes){{$validTimes}}/{{count($tempTimes)}} @else 0 @endif</div>
                                    @else
                                    <div class="col-6 statistic-content">@if (!$userTimes->isEmpty()){{$validTimes}}/{{count($userTimes)}} @else 0 @endif</div>
                                    @endguest
                                    <div class="col-6 statistic-content">@if ($mean){{$mean}} @else --:-- @endif</div>
                                </div>
                            </div>
                            <div class="single-statistic my-2 p-1">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="fw-semibold text-center text-uppercase statistic-title">Singolo</div>
                                    </div>
                                </div>
                                <div class="row text-center">
                                    <div class="col-6 fw-semibold fst-italic statistic-title">Attuale</div>
                                    <div class="col-6 fw-semibold fst-italic statistic-title">Migliore</div>
                                    <div class="col-6 statistic-content">@if ($single && $single != 'DNF'){{$single}} @elseif ($single == 'DNF') DNF @else --:-- @endif</div>
                                    <div class="col-6 statistic-content">@if ($bestSingle){{$bestSingle}} @else --:-- @endif</div>
                                </div>
                            </div>
                            <div class="single-statistic my-2 p-1">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="fw-semibold text-center text-uppercase statistic-title">Mean of 3</div>
                                    </div>
                                </div>
                                <div class="row text-center">
                                    <div class="col-6 fw-semibold fst-italic statistic-title">Attuale</div>
                                    <div class="col-6 fw-semibold fst-italic statistic-title">Migliore</div>
                                    <div class="col-6 statistic-content">@if ($mo3 && $mo3 != 'DNF'){{$mo3}} @elseif ($mo3 == 'DNF') DNF @else --:-- @endif</div>
                                    <div class="col-6 statistic-content">@if ($bestMo3){{$bestMo3}} @else --:-- @endif</div>
                                </div>
                            </div>
                            <div class="single-statistic my-2 p-1">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="fw-semibold text-center text-uppercase statistic-title">Average of 5</div>
                                    </div>
                                </div>
                                <div class="row text-center">
                                    <div class="col-6 fw-semibold fst-italic statistic-title">Attuale</div>
                                    <div class="col-6 fw-semibold fst-italic statistic-title">Migliore</div>
                                    <div class="col-6 statistic-content">@if ($ao5 && $ao5 != 'DNF'){{$ao5}} @elseif ($ao5 == 'DNF') DNF @else --:-- @endif</div>
                                    <div class="col-6 statistic-content">@if ($bestAo5){{$bestAo5}} @else --:-- @endif</div>
                                </div>
                            </div>
                            <div class="single-statistic my-2 p-1">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="fw-semibold text-center text-uppercase statistic-title">Average of 12</div>
                                    </div>
                                </div>
                                <div class="row text-center">
                                    <div class="col-6 fw-semibold fst-italic statistic-title">Attuale</div>
                                    <div class="col-6 fw-semibold fst-italic statistic-title">Migliore</div>
                                    <div class="col-6 statistic-content">@if ($ao12 && $ao12 != 'DNF'){{$ao12}} @elseif ($ao12 == 'DNF') DNF @else --:-- @endif</div>
                                    <div class="col-6 statistic-content">@if ($bestAo12){{$bestAo12}} @else --:-- @endif</div>
                                </div>
                            </div>
                            <div class="single-statistic my-2 p-1">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="fw-semibold text-center text-uppercase statistic-title">Average of 50</div>
                                    </div>
                                </div>
                                <div class="row text-center">
                                    <div class="col-6 fw-semibold fst-italic statistic-title">Attuale</div>
                                    <div class="col-6 fw-semibold fst-italic statistic-title">Migliore</div>
                                    <div class="col-6 statistic-content">@if ($ao50 && $ao50 != 'DNF'){{$ao50}} @elseif ($ao50 == 'DNF') DNF @else --:-- @endif</div>
                                    <div class="col-6 statistic-content">@if ($bestAo50){{$bestAo50}} @else --:-- @endif</div>
                                </div>
                            </div>
                            <div class="single-statistic my-2 p-1">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="fw-semibold text-center text-uppercase statistic-title">Average of 100</div>
                                    </div>
                                </div>
                                <div class="row text-center">
                                    <div class="col-6 fw-semibold fst-italic statistic-title">Attuale</div>
                                    <div class="col-6 fw-semibold fst-italic statistic-title">Migliore</div>
                                    <div class="col-6 statistic-content">@if ($ao100 && $ao100 != 'DNF'){{$ao100}} @elseif ($ao100 == 'DNF') DNF @else --:-- @endif</div>
                                    <div class="col-6 statistic-content">@if ($bestAo100){{$bestAo100}} @else --:-- @endif</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>

{{-- MODALI --}}
<div class="modal fade" id="penaltyModal" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalCenterTitle">Aggiungi penalità</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Sei sicuro di voler aggiungere una penalità (+2)?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="button-main" data-bs-dismiss="modal">Annulla</button>
                @guest
                <button type="button" class="button-highlight" wire:click="addPenalty({{array_key_last($tempTimes)}})" data-bs-dismiss="modal">Aggiungi +2</button>
                @else
                <button type="button" class="button-highlight" wire:click="addPenalty({{$userTimes->keys()->last()}})" data-bs-dismiss="modal">Aggiungi +2</button>
                @endguest
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="dnfModal" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalCenterTitle">Assegna DNF</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Sei sicuro di voler assegnare DNF?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="button-main" data-bs-dismiss="modal">Annulla</button>
                @guest
                <button type="button" class="button-highlight" wire:click="setDNF({{array_key_last($tempTimes)}})" data-bs-dismiss="modal">Assegna DNF</button>
                @else
                <button type="button" class="button-highlight" wire:click="setDNF({{$userTimes->keys()->last()}})" data-bs-dismiss="modal">Assegna DNF</button>
                @endguest
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="settingsModal" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Impostazioni</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-check form-switch pb-2">
                                <label class="form-check-label fw-semibold" for="switchCheck">Dark mode</label>
                                <input class="form-check-input" type="checkbox" role="switch" id="switchCheck">
                            </div>
                            <div class="form-check form-switch pb-2">
                                <label class="form-check-label fw-semibold" for="InputSelection" id="inputSelectionText"></label>
                                <input class="form-check-input" type="checkbox" role="switch" id="inputSelection">
                            </div>
                            <div class="pb-2">
                                <button id="timerSizeInc" class="button-transparent p-0"><i class="bi bi-plus-circle"></i></button>
                                <input type="number" value="" id="timerSize" step="25" min="50" max="250">
                                <button id="timerSizeDec" class="button-transparent p-0"><i class="bi bi-dash-circle pe-1"></i></button>
                                <label for="timerSize" class="fw-semibold">Dimensioni timer (px)</label>
                            </div>
                            <div class="pb-2">
                                <button id="scrambleSizeInc" class="button-transparent p-0"><i class="bi bi-plus-circle"></i></button>
                                <input type="number" value="" id="scrambleSize" step="5" min="10" max="30">
                                <button id="scrambleSizeDec" class="button-transparent p-0"><i class="bi bi-dash-circle pe-1"></i></button>
                                <label for="scrambleSize" class="fw-semibold">Dimensioni scramble (px)</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="button-main" data-bs-dismiss="modal">Chiudi</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="exportModal" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Esporta sessione</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Stai per esportare questa sessione. Seleziona il formato di esportazione</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="button-main" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="button-highlight" wire:click="exportSession('xlsx')" data-bs-dismiss="modal">XLSX</button>
                <button type="button" class="button-highlight" wire:click="exportSession('csv')" data-bs-dismiss="modal">CSV</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="deleteSessionModal" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Cancella sessione</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Sei sicuro di voler cancellare questa sessione? L'operazione non può essere annullata</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="button-main" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="button-highlight" wire:click="deleteSession()" data-bs-dismiss="modal">Cancella</button>
            </div>
        </div>
    </div>
</div>
</div>

