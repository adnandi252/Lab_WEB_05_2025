const readline = require("readline");
const rl = readline.createInterface({
  input: process.stdin,
  output: process.stdout
});

const target = Math.floor(Math.random() * 100) + 1;
let percobaan = 0;


function tanya() {
  rl.question("Masukkan salah satu dari angka 1 sampai 100: ", (jawab) => {
    const angka = parseInt(jawab);
    percobaan++;

    if (angka === target) {
      console.log(`Selamat! kamu berhasil menebak angka ${target} dengan benar.`);
      console.log(`Sebanyak ${percobaan}x percobaan`);
      rl.close();
    } else if (angka > target) {
      console.log("Terlalu tinggi! Coba lagi.");
      tanya();
    } else {
      console.log("Terlalu rendah! Coba lagi.");
      tanya();
    }
  });
}

tanya();
