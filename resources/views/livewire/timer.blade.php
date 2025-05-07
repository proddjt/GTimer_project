<div>
    <div class="container" id="timer-page">
        <div class="row">
            <div class="col-6">
                <h1 class="fw-semibold fst-italic"><span class="text-highlight">GT</span>imer</h1>
            </div>
            <div class="col-6 d-flex justify-content-end align-items-center">
                @guest
                <button class="button-highlight mx-3" data-bs-toggle="tooltip" data-bs-title="Accedi al tuo account" data-bs-placement="bottom"><i class="fa-solid fa-right-to-bracket"></i> Accedi</button>
                <button class="button-main" data-bs-toggle="tooltip" data-bs-title="Crea il tuo account" data-bs-placement="bottom"><i class="fa-solid fa-plus"></i> Registrati</button>    
                @endguest
                @auth
                <div class="dropdown">
                    <button class="button-highlight dropdown-toggle" type="button" data-bs-toggle="dropdown">{{Auth::user()->name}}</button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                    </ul>
                </div>
                @endauth
            </div>
        </div>
        <div class="row">
            <div class="col-2 d-flex justify-content-end align-items-center">
                <i class='bx bxs-hand bx-flip-horizontal hand hand-inactive' id="leftHand"></i>
            </div>
            <div class="col-8 d-flex justify-content-center align-items-center">
                <p id="timer" class="fw-bold m-0"></p>
            </div>
            <div class="col-2 d-flex align-items-center">
                <i class='bx bxs-hand hand hand-inactive' id="rightHand"></i>
            </div>
            <div class="col-12 d-flex justify-content-center align-items-center pb-5">
                <div id="set" class="inactive-led rounded-circle mx-1"></div>
                <div id="go" class="inactive-led rounded-circle mx-1"></div>
            </div>
            <div class="col-12 d-flex justify-content-center align-items-center">
                <button id="reset" class="button-highlight d-inline-block" data-bs-toggle="tooltip" data-bs-title="Reset timer" data-bs-placement="bottom">Reset</button>
                <button class="button-main mx-4 d-inline-block" id="penaltyBtn" data-bs-toggle="modal" data-bs-target="#penaltyModal">+2</button>
                <button class="button-main d-inline-block" id="dnfBtn" data-bs-toggle="modal" data-bs-target="#dnfModal">DNF</button>
            </div>
        </div>
        <div class="row pt-4">
            <div class="col-4 d-flex justify-content-center align-items-center">
                <img src="/img/scrambles/scramble.svg?timestamp={{ now()->timestamp }}" alt="Immagine scramble" class="w-50">
            </div>
            <div class="col-8 d-flex justify-content-center align-items-center">
                <p class="fs-3 fw-semibold">{{$scramble}}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <p class="d-inline-flex gap-1">
                    <button class="button-transparent fs-1" type="button" data-bs-toggle="collapse" data-bs-target="#puzzleList" >
                        <span class="cubing-icon
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
                        " data-bs-toggle="tooltip" data-bs-title="Seleziona puzzle" data-bs-placement="bottom">
                    </span>
                </button>
                <div class="collapse" id="puzzleList">
                    <div class="collapse-body d-flex fs-2 justify-content-between align-items-center">
                        <a class="collapse-item" href="#" data-bs-toggle="tooltip" data-bs-title="3x3x3" data-bs-placement="bottom" puzzle="three"><span class="cubing-icon event-333"></span></a>
                        <a class="collapse-item" href="#" data-bs-toggle="tooltip" data-bs-title="2x2x2" data-bs-placement="bottom" puzzle="two"><span class="cubing-icon event-222"></span></a>
                        <a class="collapse-item" href="#" data-bs-toggle="tooltip" data-bs-title="4x4x4" data-bs-placement="bottom" puzzle="four"><span class="cubing-icon event-444"></span></a>
                        <a class="collapse-item" href="#" data-bs-toggle="tooltip" data-bs-title="5x5x5" data-bs-placement="bottom" puzzle="five"><span class="cubing-icon event-555"></span></a>
                        <a class="collapse-item" href="#" data-bs-toggle="tooltip" data-bs-title="6x6x6" data-bs-placement="bottom" puzzle="six"><span class="cubing-icon event-666"></span></a>
                        <a class="collapse-item" href="#" data-bs-toggle="tooltip" data-bs-title="7x7x7" data-bs-placement="bottom" puzzle="seven"><span class="cubing-icon event-777"></span></a>
                        <a class="collapse-item" href="#" data-bs-toggle="tooltip" data-bs-title="Skewb" data-bs-placement="bottom" puzzle="skewb"><span class="cubing-icon event-skewb"></span></a>
                        <a class="collapse-item" href="#" data-bs-toggle="tooltip" data-bs-title="Clock" data-bs-placement="bottom" puzzle="clock"><span class="cubing-icon event-clock"></span></a>
                        <a class="collapse-item" href="#" data-bs-toggle="tooltip" data-bs-title="Megaminx" data-bs-placement="bottom" puzzle="mega"><span class="cubing-icon event-minx"></span></a>
                        <a class="collapse-item" href="#" data-bs-toggle="tooltip" data-bs-title="Pyraminx" data-bs-placement="bottom" puzzle="pyra"><span class="cubing-icon event-pyram"></span></a>
                        <a class="collapse-item" href="#" data-bs-toggle="tooltip" data-bs-title="Square-1" data-bs-placement="bottom" puzzle="sq1"><span class="cubing-icon event-sq1"></span></a>
                    </div>
                </div>
            </p>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="row text-center text-uppercase">
                    <div class="col-12 fw-bold fs-3">Statistiche e record</div>
                    <div class="row">
                        <div class="col-2">Singolo</div>
                        <div class="col-2">Mean of 3</div>
                        <div class="col-2">Average of 5</div>
                        <div class="col-2">Average of 12</div>
                        <div class="col-2">Average of 50</div>
                        <div class="col-2">Average of 100</div>
                    </div>
                    <div class="row">
                        <div class="col-2"><span class="fw-semibold">Attuale: </span>@if ($single){{number_format($single, 2)}} @else --:-- @endif <span class="fw-semibold">Migliore: </span>@if ($bestSingle){{number_format($bestSingle, 2)}} @else --:-- @endif</div>
                        <div class="col-2"><span class="fw-semibold">Attuale: </span>@if ($mo3){{number_format($mo3, 2)}} @else --:-- @endif <span class="fw-semibold">Migliore: </span>@if ($bestMo3){{number_format($bestMo3, 2)}} @else --:-- @endif</div>
                        <div class="col-2"><span class="fw-semibold">Attuale: </span>@if ($ao5){{number_format($ao5, 2)}} @else --:-- @endif <span class="fw-semibold">Migliore: </span>@if ($bestAo5){{number_format($bestAo5, 2)}} @else --:-- @endif</div>
                        <div class="col-2"><span class="fw-semibold">Attuale: </span>@if ($ao12){{number_format($ao12, 2)}} @else --:-- @endif <span class="fw-semibold">Migliore: </span>@if ($bestAo12){{number_format($bestAo12, 2)}} @else --:-- @endif</div>
                        <div class="col-2"><span class="fw-semibold">Attuale: </span>@if ($ao50){{number_format($ao50, 2)}} @else --:-- @endif <span class="fw-semibold">Migliore: </span>@if ($bestAo50){{number_format($bestAo50, 2)}} @else --:-- @endif</div>
                        <div class="col-2"><span class="fw-semibold">Attuale: </span>@if ($ao100){{number_format($ao100, 2)}} @else --:-- @endif <span class="fw-semibold">Migliore: </span>@if ($bestAo100){{number_format($bestAo100, 2)}} @else --:-- @endif</div>
                    </div>
                    <div class="row pt-3">
                        <div class="col-12">
                            <span class="fw-semibold">Numero solve: @if ($tempTimes){{count($tempTimes)}} @else 0 @endif</span>
                            <span class="fw-semibold">Media sessione: @if ($mean){{number_format($mean, 2)}} @else --:-- @endif</span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="row text-center text-uppercase pt-5">
                    <div class="col-12 fw-bold fs-3">Lista di tempi</div>
                    <div class="col-1">N.</div>
                    <div class="col-2">Tempo</div>
                    <div class="col-2">Data e ora</div>
                    <div class="col-5">Scramble</div>
                    <div class="col-2">Azioni</div>
                </div>
                @foreach ($tempTimes as $time)
                <div class="row pt-2 text-center align-items-center">
                    <div class="col-1">{{$loop->iteration}}.</div>
                    <div class="col-2">{{$time[0]}} @if ($time[3])(P) @endif</div>
                    <div class="col-2">{{$time[1]}}</div>
                    <div class="col-5">{{$time[2]}}</div>
                    <div class="col-2"><button wire:click="deleteTime({{$loop->index}})" class="button-main"><i class="fa-solid fa-trash"></i></button></div>
                </div>
                @endforeach
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
                    <p>Sei sicuro di voler aggiungere una penalità (+2)? L'operazione non può essere annullata</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="button-main" data-bs-dismiss="modal">Annulla</button>
                    <button type="button" class="button-highlight" wire:click="addPenalty" data-bs-dismiss="modal">Aggiungi +2</button>
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
                    <p>Sei sicuro di voler assegnare DNF? L'operazione non può essere annullata</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="button-main" data-bs-dismiss="modal">Annulla</button>
                    <button type="button" class="button-highlight" wire:click="setDNF" data-bs-dismiss="modal">Assegna DNF</button>
                </div>
            </div>
        </div>
    </div>
</div>
    
