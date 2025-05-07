let loader = document.querySelector('#loader');
let timerPage = document.querySelector('#timer-page');
let timer = document.querySelector('#timer');
let timerStart = false;
let isPressed = {};
let countdown = 1000;
let userReady = null;
let timerFunction = null;
let resetBtn = document.querySelector('#reset');
let puzzleSelect = document.querySelector('#puzzle-select');
let actualTime = 0.00;

document.addEventListener('DOMContentLoaded', () => {
    timerPage.classList.add('d-none');
    loader.classList.add('d-block');
    Livewire.dispatch('firstLoad');
})

Livewire.on('DOMRefresh', () => {
    setTimeout(() => {
        let timer = document.querySelector('#timer');
        timer.innerText = actualTime.toFixed(2);
    }, 10);
})

Livewire.on('scrambleGenerated', () => {
    timerPage.classList.remove('d-none');
    timerPage.classList.add('d-block');
    loader.classList.remove('d-block');
    loader.classList.add('d-none');
    setTimeout(() => {
        let timer = document.querySelector('#timer');
        timer.innerText = actualTime.toFixed(2);
    }, 10);
})

puzzleSelect.addEventListener('change', () => {
    timerPage.classList.add('d-none');
    timerPage.classList.remove('d-block');
    loader.classList.add('d-block');
    loader.classList.remove('d-none');
    Livewire.dispatch('firstLoad');
})
    
document.addEventListener('keydown', (e) => {
    isPressed[e.code] = true;
    if(isPressed['ControlLeft'] == true && isPressed['ControlRight'] == true && userReady == null && timerStart == false && timerFunction == null && timer.innerText == "0.00") {
        timer.classList.add('red');
        countdown = 1000;
        userReady = setInterval(() => {
            countdown -= 25;
            if (countdown <= 250) {
                timer.classList.remove('red');
                timer.classList.add('green');
            }
        }, 25);
    }
    if (isPressed['ControlLeft'] == true && isPressed['ControlRight'] == true && timerStart == true) {
        clearInterval(timerFunction);
        actualTime = Number(timer.innerText);
        Livewire.dispatch('timerStopped', {time: actualTime});
        timerFunction = null;
        timerStart = false;
        resetBtn.classList.add('d-block');
        resetBtn.classList.remove('d-none');
    }
})
document.addEventListener('keyup', (e) => {
    isPressed[e.code] = false;
    if (!isPressed['ControlLeft'] || !isPressed['ControlRight']) {
        clearInterval(userReady);
        userReady = null;
        timer.classList.remove('red');
        timer.classList.remove('green');
        if(countdown <= 250) {
            timerStart = true;
            countdown = 1000;
            resetBtn.classList.remove('d-block');
            resetBtn.classList.add('d-none');
            let startTime = 0.00;
            timerFunction = setInterval(() => {
                startTime += 0.01;
                timer.innerText = startTime.toFixed(2);
            }, 10)
        }
    }
})

resetBtn.addEventListener('click', () => {
    actualTime = 0.00
    timer.innerText = actualTime.toFixed(2);
})