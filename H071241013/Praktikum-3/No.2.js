const readline = require('readline');
const rl = readline.createInterface({
  input: process.stdin,
  output: process.stdout
});
rl.question('Masukkan harga barang: ', (hargaBarang) => {
  rl.question('Masukkan jenis barang (Elektronik, Pakaian, Makanan,Lainnya): ', (jenisBarang) => {
    console.log("Lowercase:", jenisBarang.toLowerCase());
    hargaBarang = parseFloat(hargaBarang);
    let diskon = 0;
    let hargaSetelahDiskon = 0;
    switch (jenisBarang) {
      case "elektronik":
        diskon = 0.1;
        break;
      case "pakaian":
        diskon = 0.2;
        break;
      case "makanan":
        diskon = 0.05;
        break;
      default:
        diskon = 0;
        break;
    }
    hargaSetelahDiskon = hargaBarang - (hargaBarang * diskon);
    console.log("Harga awal: Rp " + hargaBarang);
    console.log("Diskon: " + (diskon * 100) + "%");
    console.log("Harga setelah diskon: Rp " + hargaSetelahDiskon);
    rl.close();
  });
});
