import dayjs from 'dayjs';
import duration from 'dayjs/plugin/duration';
dayjs.extend(duration);

let loader = document.querySelector('#loader');
let timerPage = document.querySelector('#timer-page');
let timer = document.querySelector('#timer');
let timerStart = false;
let isPressed = {};
let countdown = 1000;
let userReady = null;
let timerFunction = null;
let resetBtn = document.querySelector('#reset');
let penaltyBtn = document.querySelector('#penaltyBtn');
let dnfBtn = document.querySelector('#dnfBtn');
let collapseItems = document.querySelectorAll('.collapse-item');
let actualTime = "00.00";
let setLed = document.querySelector('#set');
let goLed = document.querySelector('#go');
let leftHand = document.querySelector('#leftHand');
let rightHand = document.querySelector('#rightHand');
let inputSelectionSwitch = document.querySelector('#inputSelection');
let inputSelectionText = document.querySelector('#inputSelectionText');
let inputSelection;

if(timerPage){
    document.addEventListener('DOMContentLoaded', () => {
        timerPage.classList.add('d-none');
        loader.classList.add('d-block');
        Livewire.dispatch('firstLoad');
        initializeBootstrap();
        loadSettings();
    })

    Livewire.on('DOMRefresh', () => {
        setTimeout(() => {
            let timer = document.querySelector('#timer');
            timer.innerText = actualTime;
            initializeBootstrap();
            loadSettings();
        }, 10);
    })

    Livewire.on('scrambleGenerated', () => {
        timerPage.classList.remove('d-none');
        timerPage.classList.add('d-block');
        loader.classList.remove('d-block');
        loader.classList.add('d-none');
        setTimeout(() => {
            let timer = document.querySelector('#timer');
            timer.innerText = actualTime;
            initializeBootstrap();
            loadSettings();
        }, 10);
    })

    collapseItems.forEach(item => {
        item.addEventListener('click', () => {
            timerPage.classList.add('d-none');
            timerPage.classList.remove('d-block');
            loader.classList.add('d-block');
            loader.classList.remove('d-none');
            Livewire.dispatch('setPuzzle', {puzzle: item.getAttribute('puzzle')});
            Livewire.dispatch('firstLoad');
            Livewire.dispatch('puzzleChanged');
        })
    });
    
       
    document.addEventListener('keydown', (e) => {
        isPressed[e.code] = true;
        if (isPressed['ControlLeft']){
            leftHand.classList.remove('hand-inactive');
            leftHand.classList.add('hand-active');
        }if (inputSelection == 'ctrl'){
            if (isPressed['ControlRight']){
                rightHand.classList.remove('hand-inactive');
                rightHand.classList.add('hand-active');
            }
        }else{
            if (isPressed['ShiftRight']){
                rightHand.classList.remove('hand-inactive');
                rightHand.classList.add('hand-active');
            }
        }
        if (inputSelection == 'ctrl'){
            if(isPressed['ControlLeft'] == true && isPressed['ControlRight'] == true && userReady == null && timerStart == false && timerFunction == null && timer.innerText == "00.00") {
                setLed.classList.add('set-led');
                countdown = 1000;
                userReady = setInterval(() => {
                    countdown -= 25;
                    if (countdown <= 250) {
                        goLed.classList.add('go-led');
                    }
                }, 25);
            }
            if (isPressed['ControlLeft'] == true && isPressed['ControlRight'] == true && timerStart == true) {
                clearInterval(timerFunction);
                if (Number(timer.innerText) >= 3600) {
                    actualTime = dayjs.duration(Number(timer.innerText), 'seconds').format('hh:mm:ss.SSS').slice(0, -1);
                }else if (Number(timer.innerText) >= 60) {
                    actualTime = dayjs.duration(Number(timer.innerText), 'seconds').format('mm:ss.SSS').slice(0, -1);
                }else{
                    actualTime = dayjs.duration(Number(timer.innerText), 'seconds').format('ss.SSS').slice(0, -1);
                }
                setLed.classList.remove('set-led-blink');
                goLed.classList.remove('go-led-blink');
                setLed.classList.add('inactive-led');
                goLed.classList.add('inactive-led');
                Livewire.dispatch('timerStopped', {time: timeToFloatSeconds(actualTime)});
                timerFunction = null;
                timerStart = false;
                resetBtn.classList.add('d-inline-block');
                resetBtn.classList.remove('d-none');
                penaltyBtn.classList.add('d-inline-block');
                penaltyBtn.classList.remove('d-none');
                dnfBtn.classList.add('d-inline-block');
                dnfBtn.classList.remove('d-none');
            }
        }else{
            if(isPressed['ControlLeft'] == true && isPressed['ShiftRight'] == true && userReady == null && timerStart == false && timerFunction == null && timer.innerText == "00.00") {
                setLed.classList.add('set-led');
                countdown = 1000;
                userReady = setInterval(() => {
                    countdown -= 25;
                    if (countdown <= 250) {
                        goLed.classList.add('go-led');
                    }
                }, 25);
            }
            if (isPressed['ControlLeft'] == true && isPressed['ShiftRight'] == true && timerStart == true) {
                clearInterval(timerFunction);
                if (Number(timer.innerText) >= 3600) {
                    actualTime = dayjs.duration(Number(timer.innerText), 'seconds').format('hh:mm:ss.SSS').slice(0, -1);
                }else if (Number(timer.innerText) >= 60) {
                    actualTime = dayjs.duration(Number(timer.innerText), 'seconds').format('mm:ss.SSS').slice(0, -1);
                }else{
                    actualTime = dayjs.duration(Number(timer.innerText), 'seconds').format('ss.SSS').slice(0, -1);
                }
                setLed.classList.remove('set-led-blink');
                goLed.classList.remove('go-led-blink');
                setLed.classList.add('inactive-led');
                goLed.classList.add('inactive-led');
                Livewire.dispatch('timerStopped', {time: timeToFloatSeconds(actualTime)});
                timerFunction = null;
                timerStart = false;
                resetBtn.classList.add('d-inline-block');
                resetBtn.classList.remove('d-none');
                penaltyBtn.classList.add('d-inline-block');
                penaltyBtn.classList.remove('d-none');
                dnfBtn.classList.add('d-inline-block');
                dnfBtn.classList.remove('d-none');
            }
        }
    })

    document.addEventListener('keyup', (e) => {
        isPressed[e.code] = false;
        if (!isPressed['ControlLeft']) {
            leftHand.classList.remove('hand-active');
            leftHand.classList.add('hand-inactive');
        }
        if (inputSelection == 'ctrl'){
            if (!isPressed['ControlRight']) {
                rightHand.classList.remove('hand-active');
                rightHand.classList.add('hand-inactive');
            }
            if (!isPressed['ControlLeft'] || !isPressed['ControlRight']) {
                clearInterval(userReady);
                userReady = null;
                setLed.classList.remove('set-led');
                goLed.classList.remove('go-led');
                setLed.classList.add('inactive-led');
                goLed.classList.add('inactive-led');
                if(countdown <= 250) {
                    timerStart = true;
                    countdown = 1000;
                    setLed.classList.remove('inactive-led');
                    setLed.classList.add('set-led-blink');
                    goLed.classList.remove('inactive-led');
                    goLed.classList.add('go-led-blink');
                    resetBtn.classList.remove('d-inline-block');
                    resetBtn.classList.add('d-none');
                    penaltyBtn.classList.remove('d-inline-block');
                    penaltyBtn.classList.add('d-none');
                    dnfBtn.classList.remove('d-inline-block');
                    dnfBtn.classList.add('d-none');
                    let startTime = 0.00;
                    timerFunction = setInterval(() => {
                        startTime += 10;
                        if (startTime < 60000){
                            timer.innerText = dayjs.duration(startTime, 'milliseconds').format('ss.SSS').slice(0, -1);
                        }else if(startTime < 3600000){
                            timer.innerText = dayjs.duration(startTime, 'milliseconds').format('mm:ss.SSS').slice(0, -1);
                        }else{
                            timer.innerText = dayjs.duration(startTime, 'milliseconds').format('hh:mm:ss.SSS').slice(0, -1);
                        }
                    }, 10)
                }
            }    
        }else{
            console.log(e.code);
            console.log(inputSelection);
            if (!isPressed['ShiftRight']){
                console.log('ciao');
                
                rightHand.classList.add('hand-inactive');
                rightHand.classList.remove('hand-active');
            }
            if (!isPressed['ControlLeft'] || !isPressed['ShiftRight']) {
                clearInterval(userReady);
                userReady = null;
                setLed.classList.remove('set-led');
                goLed.classList.remove('go-led');
                setLed.classList.add('inactive-led');
                goLed.classList.add('inactive-led');
                if(countdown <= 250) {
                    timerStart = true;
                    countdown = 1000;
                    setLed.classList.remove('inactive-led');
                    setLed.classList.add('set-led-blink');
                    goLed.classList.remove('inactive-led');
                    goLed.classList.add('go-led-blink');
                    resetBtn.classList.remove('d-inline-block');
                    resetBtn.classList.add('d-none');
                    penaltyBtn.classList.remove('d-inline-block');
                    penaltyBtn.classList.add('d-none');
                    dnfBtn.classList.remove('d-inline-block');
                    dnfBtn.classList.add('d-none');
                    let startTime = 0.00;
                    timerFunction = setInterval(() => {
                        startTime += 10;
                        if (startTime < 60000){
                            timer.innerText = dayjs.duration(startTime, 'milliseconds').format('ss.SSS').slice(0, -1);
                        }else if(startTime < 3600000){
                            timer.innerText = dayjs.duration(startTime, 'milliseconds').format('mm:ss.SSS').slice(0, -1);
                        }else{
                            timer.innerText = dayjs.duration(startTime, 'milliseconds').format('hh:mm:ss.SSS').slice(0, -1);
                        }
                    }, 10)
                }
            }
        }
    })

    resetBtn.addEventListener('click', () => {
        actualTime = 0
        timer.innerText = dayjs.duration(actualTime, 'milliseconds').format('ss.SSS').slice(0, -1);
    })
}

function timeToFloatSeconds(time) {
    time = time.replace(',', '.');
    const parts = time.split(':');

    let hours = 0;
    let minutes = 0;
    let seconds = 0;
    let centiseconds = 0;

    function explodeCenti(part) {
        const subParts = part.split('.');
        const secs = parseInt(subParts[0], 10) || 0;
        const centis = parseInt(subParts[1], 10) || 0;
        return [secs, centis];
    }

    if (parts.length === 3) {
        hours = parseInt(parts[0], 10);
        minutes = parseInt(parts[1], 10);
        [seconds, centiseconds] = explodeCenti(parts[2]);
    } else if (parts.length === 2) {
        minutes = parseInt(parts[0], 10);
        [seconds, centiseconds] = explodeCenti(parts[1]);
    } else if (parts.length === 1) {
        [seconds, centiseconds] = explodeCenti(parts[0]);
    }

    return (hours * 3600) + (minutes * 60) + seconds + (centiseconds / 100);
}

function initializeBootstrap(){
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    const penaltyModal = document.getElementById('penaltyModal')
    const dnfModal = document.getElementById('dnfModal')
    const penaltyBtn = document.getElementById('penaltyBtn')
    const dnfBtn = document.getElementById('dnfBtn')
    const settingsModal = document.getElementById('settingsModal')
    const settingsBtn = document.getElementById('settingsBtn')
    const exportModal = document.getElementById('exportModal')
    const exportBtn = document.getElementById('exportBtn')
    const deleteSessionModal = document.getElementById('deleteSessionModal')
    const deleteSessionBtn = document.getElementById('deleteSessionBtn')
    penaltyModal.addEventListener('shown.bs.modal', () => {
        penaltyBtn.focus()
    })
    dnfModal.addEventListener('shown.bs.modal', () => {
        dnfBtn.focus()
    })
    exportModal.addEventListener('shown.bs.modal', () => {
        exportBtn.focus()
    })
    deleteSessionModal.addEventListener('shown.bs.modal', () => {
        deleteSessionBtn.focus()
    })
    if (settingsBtn){
        settingsModal.addEventListener('shown.bs.modal', () => {
            settingsBtn.focus()
        })
    }
}

Livewire.on('resetTimer', () => {
    actualTime = 0.00;
    timer.innerText = actualTime.toFixed(2);
})

let passwordInput = document.querySelector('#floatingPassword');
let confirmPasswordInput = document.querySelector('#floatingConfirmPassword');
let showBtn = document.querySelector('#showPassword');
let showConfirmBtn = document.querySelector('#showConfirmPassword');

if (passwordInput){
    showBtn.addEventListener('click', () => {
        showBtn.classList.toggle('text-highlight');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
        } else {
            passwordInput.type = 'password';
        }
    })
}
if (confirmPasswordInput){
    showConfirmBtn.addEventListener('click', () => {
        showConfirmBtn.classList.toggle('text-highlight');
        if (confirmPasswordInput.type === 'password') {
            confirmPasswordInput.type = 'text';
        } else {
            confirmPasswordInput.type = 'password';
        }
    })
}

let modeSwitch = document.querySelector('#switchCheck');
let mode = localStorage.getItem('mode');

function setLightMode(){
    document.documentElement.style.setProperty('--bg', '#e9ecef')
    document.documentElement.style.setProperty('--text', '#212529')
    document.documentElement.style.setProperty('--text-hov', '#343a40')
    document.documentElement.style.setProperty('--text-invert', '#e9ecef')
    document.documentElement.style.setProperty('--bg-sec', '#dee2e6')
}

function setDarkMode(){
    document.documentElement.style.setProperty('--text', '#e9ecef')
    document.documentElement.style.setProperty('--bg', '#212529')
    document.documentElement.style.setProperty('--text-hov', '#c0c0c0')
    document.documentElement.style.setProperty('--text-invert', '#212529')
    document.documentElement.style.setProperty('--bg-sec', '#3f4244')
}

if(modeSwitch){
    modeSwitch.addEventListener('click', () =>{
        if (mode === "light") {
            setDarkMode()
            localStorage.setItem('mode', 'dark')
            mode = localStorage.getItem('mode')
        }else{
            setLightMode()
            localStorage.setItem('mode', 'light')
            mode = localStorage.getItem('mode')
        }
    })
}

if(inputSelectionSwitch){
    inputSelectionSwitch.addEventListener('change', () =>{
        if (inputSelectionSwitch.checked === true) {
            inputSelectionText.innerText = 'CTRL + SHIFT'
            inputSelection = 'shift'
            localStorage.setItem('input-mode', 'shift');
        }else{
            inputSelectionText.innerText = 'CTRL + CTRL'
            inputSelection = 'ctrl'
            localStorage.setItem('input-mode', 'ctrl');
        }
    })
}

document.addEventListener('DOMContentLoaded', () => {
    loadSettings();
})

function loadSettings(){
    if (!localStorage.getItem('timer-size')){
        localStorage.setItem('timer-size', '175');
    }else{
        document.documentElement.style.setProperty('--timer-size', localStorage.getItem('timer-size') + 'px');
    }
    if(timerSizeInput){
        timerSizeInput.value = localStorage.getItem('timer-size');   
    }
    if (!localStorage.getItem('scramble-size')){
        localStorage.setItem('scramble-size', '20');
    }else{
        document.documentElement.style.setProperty('--scramble-size', localStorage.getItem('scramble-size') + 'px');
    }
    if(scrambleSizeInput){
        scrambleSizeInput.value = localStorage.getItem('scramble-size');   
    }
    if (mode == "dark"){
        if (modeSwitch){
            modeSwitch.checked = true;
        }
        setDarkMode();
    }else{
        if(modeSwitch){
            modeSwitch.checked = false;
        }
        setLightMode();
    }
    if (!localStorage.getItem('input-mode')){
        localStorage.setItem('input-mode', 'ctrl');
        inputSelectionText.innerText = 'CTRL + CTRL'
        inputSelectionSwitch.checked = true;
        inputSelection = 'ctrl'
    }else{
        inputSelection = localStorage.getItem('input-mode');
        if (inputSelection == 'ctrl'){
            inputSelectionText.innerText = 'CTRL + CTRL'
            inputSelectionSwitch.checked = false;
        }else{
            inputSelectionText.innerText = 'CTRL + SHIFT'
            inputSelectionSwitch.checked = true;
        }
    }
}

let timerSizeInput = document.querySelector('#timerSize');
if(timerSizeInput){
    timerSizeInput.addEventListener('change', () => {
        localStorage.setItem('timer-size', timerSizeInput.value);
        document.documentElement.style.setProperty('--timer-size', timerSizeInput.value + 'px');
    })
}

let timerSizeInc = document.querySelector('#timerSizeInc');
let timerSizeDec = document.querySelector('#timerSizeDec');
if(timerSizeInc){
    timerSizeInc.addEventListener('click', () => {
        if(Number(timerSizeInput.value) >= Number(timerSizeInput.max)){
            return;
        }else{
            timerSizeInput.value = Number(timerSizeInput.value) + Number(timerSizeInput.step);
            localStorage.setItem('timer-size', timerSizeInput.value);
            document.documentElement.style.setProperty('--timer-size', timerSizeInput.value + 'px');
        }
    })
    timerSizeDec.addEventListener('click', () => {
        if(Number(timerSizeInput.value) <= Number(timerSizeInput.min)){
            return;
        }else{
            timerSizeInput.value = Number(timerSizeInput.value) - Number(timerSizeInput.step);
            localStorage.setItem('timer-size', timerSizeInput.value);
            document.documentElement.style.setProperty('--timer-size', timerSizeInput.value + 'px');
        }
    })
}

let scrambleSizeInput = document.querySelector('#scrambleSize');
if(scrambleSizeInput){
    scrambleSizeInput.addEventListener('change', () => {
        localStorage.setItem('scramble-size', scrambleSizeInput.value);
        document.documentElement.style.setProperty('--scramble-size', scrambleSizeInput.value + 'px');
    })
}

let scrambleSizeInc = document.querySelector('#scrambleSizeInc');
let scrambleSizeDec = document.querySelector('#scrambleSizeDec');
if(scrambleSizeInc){
    scrambleSizeInc.addEventListener('click', () => {
        if(Number(scrambleSizeInput.value) >= Number(scrambleSizeInput.max)){
            return;
        }else{
            scrambleSizeInput.value = Number(scrambleSizeInput.value) + Number(scrambleSizeInput.step);
            localStorage.setItem('scramble-size', scrambleSizeInput.value);
            document.documentElement.style.setProperty('--scramble-size', scrambleSizeInput.value + 'px');
        }
    })
    scrambleSizeDec.addEventListener('click', () => {
        if(Number(scrambleSizeInput.value) <= Number(scrambleSizeInput.min)){
            return;
        }else{
            scrambleSizeInput.value = Number(scrambleSizeInput.value) - Number(scrambleSizeInput.step);
            localStorage.setItem('scramble-size', scrambleSizeInput.value);
            document.documentElement.style.setProperty('--scramble-size', scrambleSizeInput.value + 'px');
        }
    })
}