const readline = require("readline");

const rl = readline.createInterface({
  input: process.stdin,
  output: process.stdout,
});

let daftarHari = ["minggu", "senin", "selasa", "rabu", "kamis", "jumat", "sabtu"];

rl.question("Masukkan hari: ", (hariInput) => {
  let hari = hariInput.toLowerCase();

  if (!daftarHari.includes(hari)) {
    console.log("Hari tidak valid. Harap masukkan nama hari dengan benar.");
    rl.close();
    return;
  }

  rl.question("Masukkan jumlah hari ke depan: ", (nInput) => {
    let n = parseInt(nInput);

    if (isNaN(n) || n < 0) {
      console.log("Input jumlah hari tidak valid. Harus berupa angka positif.");
      rl.close();
      return;
    }

    let indexHari = daftarHari.indexOf(hari);
    let hasilIndex = (indexHari + n) % 7;
    let hasilHari = daftarHari[hasilIndex];

    console.log(`${n} hari setelah ${hari} adalah ${hasilHari}`);

    rl.close();
  });
});
