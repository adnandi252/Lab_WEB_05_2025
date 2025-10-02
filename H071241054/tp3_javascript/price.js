const readline = require("readline");
const rl = readline.createInterface({
    input: process.stdin,
    output: process.stdout
});

function askHarga() {
    rl.question("Masukkan harga barang: ", (harga) => {
        const hargaAwal = parseFloat(harga);

        if (isNaN(hargaAwal) || hargaAwal <= 0) {
            console.log("Harga tidak valid. Harus berupa angka positif.\n");
            askHarga();
        } else {
            askJenis(hargaAwal);
        }
    });
}

function askJenis(hargaAwal) {
    rl.question("Masukkan jenis barang: ", (jenis) => {
        const jenisBarang = jenis.trim().toLowerCase();

        if (["elektronik", "pakaian", "makanan"].includes(jenisBarang)) {
            console.log("Harga awal: Rp", hargaAwal);


        if (jenisBarang === "elektronik") {
            console.log("Diskon: 10%");
        } else if (jenisBarang === "pakaian") {
            console.log("Diskon: 20%");
        } else if (jenisBarang === "makanan") {
            console.log("Diskon: 5%");
        } 

        console.log("Harga setelah diskon: Rp", discount(hargaAwal, jenisBarang));
        rl.close();
        } else {
            console.log("Tidak ada diskon.");
            console.log("Harga: Rp" + hargaAwal);
            rl.close()
        }
    });
}

function discount(hargaAwal, jenisBarang) {
    let hargaAkhir = 0;
    if (jenisBarang === "elektronik") {
        hargaAkhir = hargaAwal - (hargaAwal * 0.1);
    } else if (jenisBarang === "pakaian") {
        hargaAkhir = hargaAwal - (hargaAwal * 0.2);
    } else if (jenisBarang === "makanan") {
        hargaAkhir = hargaAwal - (hargaAwal * 0.05);
    } else {
        hargaAkhir = hargaAwal;
    }
    return hargaAkhir;
}


askHarga();