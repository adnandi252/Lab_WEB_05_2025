const readline = require("readline");

const rl = readline.createInterface({
  input: process.stdin,
  output: process.stdout,
});

function playGame() {
  let n = 0;
  let random = Math.floor(Math.random() * 100) + 1;
  function ask() {
    rl.question("Masukkan angka : ", (tebak) => {
      tebak = parseInt(tebak);
      if (isNaN(tebak) || tebak <= 0) {
        console.log("Angka yang dimasukkan tidak valid");
        ask();
        return;
      }

      n++;

      if (tebak < random) {
        console.log("Terlalu kecil");
        ask();
      } else if (tebak > random) {
        console.log("Terlalu besar");
        ask();
      } else {
        console.log(
          `Selamat, kamu berhasil menebak angka ${random} dengan benar`
        );
        console.log(`Jumlah percobaan kamu : ${n}`);
        rl.close();
      }
    });
  }

  ask();
}

playGame();
