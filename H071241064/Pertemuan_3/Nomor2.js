const readline = require("readline");

const rl = readline.createInterface({
  input: process.stdin,
  output: process.stdout
});

rl.question("Masukkan harga barang: ", (hargaInput) => {
  const harga = parseFloat(hargaInput);

  if (isNaN(harga) || harga <= 0) {
    console.log("Input harga tidak valid!");
    rl.close();
    return;
  }

  rl.question("Masukkan jenis barang (Elektronik, Pakaian, Makanan, Lainnya): ", (jenis) => {
    let diskon = 0;

    if (jenis.toLowerCase() === "elektronik") {
      diskon = 0.10;
    } else if (jenis.toLowerCase() === "pakaian") {
      diskon = 0.20;
    } else if (jenis.toLowerCase() === "makanan") {
      diskon = 0.05;
    }

    const hargaAkhir = harga - (harga * diskon);

    console.log(`Harga awal: Rp ${harga}`);
    console.log(`Diskon: ${diskon * 100}%`);
    console.log(`Harga setelah diskon: Rp ${hargaAkhir}`);

    rl.close();
  });
});