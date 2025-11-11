const readline = require("readline");

const rl = readline.createInterface({
  input: process.stdin,
  output: process.stdout
});

const hari = ["minggu", "senin", "selasa", "rabu", "kamis", "jumat", "sabtu"];

rl.question("Masukkan hari : ", (hariAwal) => {
  rl.question("Masukkan hari yang akan datang : ", (jumlahHariStr) => {
    let jumlahHari = parseInt(jumlahHariStr);

    hariAwal = hariAwal.toLowerCase();

    let indexAwal = hari.indexOf(hariAwal);

    let sisa = jumlahHari % 7;
    let indexHasil = (indexAwal + sisa) % 7;

    let hariTujuan = hari[indexHasil];

    console.log(`output: ${jumlahHari} hari setelah ${hariAwal} adalah ${hariTujuan}.`);

    rl.close();
  });
});
