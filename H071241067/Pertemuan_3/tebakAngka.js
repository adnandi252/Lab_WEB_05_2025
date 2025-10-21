const readline = require("readline");

const rl = readline.createInterface({
  input: process.stdin,
  output: process.stdout,
});

let target = Math.floor(Math.random() * 100) + 1; // angka acak 1–100
let percobaan = 0;

function tanyaTebakan() {
  rl.question("Masukkan angka antara 1 sampai 100: ", (input) => {
    let tebakan = parseInt(input);
    percobaan++;

    if (isNaN(tebakan) || tebakan < 1 || tebakan > 100) {
      console.log("Input tidak valid. Masukkan angka antara 1 sampai 100.");
      tanyaTebakan();
      return;
    }

    if (tebakan > target) {
      console.log("Terlalu tinggi! Coba lagi.");
      tanyaTebakan();
    } else if (tebakan < target) {
      console.log("Terlalu rendah! Coba lagi.");
      tanyaTebakan();
    } else {
      console.log(
        `Selamat! Kamu berhasil menebak angka ${target} dengan benar setelah ${percobaan}x percobaan.`
      );
      rl.close();
    }
  });
}

tanyaTebakan();
