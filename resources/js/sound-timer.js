let timerInterval;
let seconds = 0;
let isRunning = false;

function formatTime(totalSeconds) {
    const hrs = String(Math.floor(totalSeconds / 3600)).padStart(2, '0');
    const mins = String(Math.floor((totalSeconds % 3600) / 60)).padStart(2, '0');
    const secs = String(totalSeconds % 60).padStart(2, '0');
    return `${hrs} h : ${mins} m : ${secs} s`;
}

function updateDisplay() {
    const display = document.getElementById('timer-display');
    if (display) {
        display.textContent = formatTime(seconds);
    }
}

function startTimer() {
    timerInterval = setInterval(() => {
        seconds++;
        updateDisplay();
    }, 1000);
}

function stopAndResetTimer() {
    clearInterval(timerInterval);
    timerInterval = null;
    seconds = 0;
    updateDisplay();
}

document.addEventListener('DOMContentLoaded', () => {
    const startBtn = document.getElementById('start-button');

    if (startBtn) {
        startBtn.addEventListener('click', () => {
            if (!isRunning) {
                startTimer();
                isRunning = true;
                startBtn.textContent = 'Finish';
            } else {
                stopAndResetTimer();
                isRunning = false;
                startBtn.textContent = 'Start';
                // Optional: kasih feedback
                alert('Timer finished and reset.');
            }
        });
    }
});
