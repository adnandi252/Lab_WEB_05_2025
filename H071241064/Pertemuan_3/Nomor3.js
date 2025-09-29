const readline = require("readline");

const rl = readline.createInterface({
  input: process.stdin,
  output: process.stdout
});

const hari = ["minggu", "senin", "selasa", "rabu", "kamis", "jumat", "sabtu"];

rl.question("Masukkan hari: ", (hariInput) => {
  const indexHari = hari.indexOf(hariInput.toLowerCase());

  if (indexHari === -1) {
    console.log("Hari tidak valid!");
    rl.close();
    return;
  }

  rl.question("Masukkan jumlah hari ke depan: ", (nInput) => {
    const n = parseInt(nInput);

    if (isNaN(n) || n < 0) {
      console.log("Input jumlah hari tidak valid!");
      rl.close();
      return;
    }

    const hasilIndex = (indexHari + n) % 7;
    console.log(`${n} hari setelah ${hariInput} adalah ${hari[hasilIndex]}`);
    rl.close();
  });
});