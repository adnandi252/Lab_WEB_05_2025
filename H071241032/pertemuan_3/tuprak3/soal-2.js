const readline = require("readline");

const rl = readline.createInterface({
  input: process.stdin,
  output: process.stdout,
});

function soal2() {
  let result;
  let diskon;
  rl.question("Masukkan harga barang: ", (harga) => {
    harga = parseFloat(harga);
    if (isNaN(harga) || harga <= 0) {
      console.log("Harga barang tidak valid");
      rl.close();
      return;
    }
    rl.question("Masukkan jenis barang (elektronik, pakaian, makanan, lainnya): ", (jenis) => {
      jenis = jenis.toLowerCase();
      if (jenis === "elektronik"){
        diskon = .1;
      } else if (jenis === "pakaian"){
        diskon = .2;
      } else if (jenis === "makanan"){
        diskon = .05;
      } else {
        diskon = 0;
      }
      result = harga - (harga * diskon);
      console.log(`Harga awal: Rp ${harga}`);
      console.log(`Diskon: ${diskon * 100}%`);
      console.log(`Harga setelah diskon: Rp ${result}`);
      rl.close();
    });
  });
}


soal2();