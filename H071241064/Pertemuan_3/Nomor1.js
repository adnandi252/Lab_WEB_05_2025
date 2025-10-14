function menghitung(mulai, akhir) {

  if (typeof mulai !== "number" || typeof akhir !== "number" || isNaN(mulai) || isNaN(akhir)) {
    console.error("Error: Input harus berupa angka!");
    return;
  }

  let jumlah = 0;
  let list = [];

  for (let i = mulai; i <= akhir; i++) {
    if (i % 2 === 0) {
      jumlah++;
      list.push(i);
    }
  }

  console.log("Jumlah bilangan genap:", jumlah);
  console.log("Daftar bilangan genap:", list);
}

menghitung(1, "l");