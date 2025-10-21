const readline = require("readline");

const rl = readline.createInterface({
    input: process.stdin,
    output: process.stdout
});

const secretNumber = Math.floor(Math.random() * 100) + 1;
let attempts = 0;

function askGuess() {
    rl.question("Masukkan salah satu dari angka 1 sampai 100: ", (input) => {
        const guess = parseInt(input);
        attempts++;

    if (isNaN(guess) || guess < 1 || guess > 100) {
        console.log("Input tidak valid. Masukkan angka dalam rentang 1–100.");
        askGuess();
        return;
    }

    if (guess === secretNumber) {
    console.log('Selamat! Kamu menebak angka', secretNumber, 'dengan benar sebanyak', attempts + 'x percobaan');
    rl.close();
    } else if (guess > secretNumber) {
    console.log("Terlalu tinggi! Coba lagi.");
    askGuess();
    } else {
    console.log("Terlalu rendah! Coba lagi.");
    askGuess();
    }
    });
}

console.log("Selamat datang di permainan Tebak Angka!");
askGuess();