const readline = require("readline");

const rl = readline.createInterface({
  input: process.stdin,
  output: process.stdout
});

const angkaRahasia = Math.floor(Math.random() * 100) + 1;
let percobaan = 0;

console.log("Tebak angka antara 1 sampai 100");

function tanya() {
  rl.question("Masukkan tebakan: ", (jawaban) => {
    const angka = parseInt(jawaban);

    if (isNaN(angka) || angka < 1 || angka > 100) {
      console.log("Input tidak valid! Masukkan angka 1–100.");
      tanya();
      return;
    }

    percobaan++;

    if (angka < angkaRahasia) {
      console.log("Terlalu rendah! Coba lagi.");
      tanya();
    } else if (angka > angkaRahasia) {
      console.log("Terlalu tinggi! Coba lagi.");
      tanya();
    } else {
      console.log(`Selamat! Kamu berhasil menebak angka ${angkaRahasia}`);
      console.log(`Jumlah percobaan: ${percobaan}`);
      rl.close();
    }
  });
}

tanya();