const readline = require("readline");

const rl = readline.createInterface({
    input: process.stdin,
    output: process.stdout
});

function nextDay(hariSekarang, jumlahHari) {
    const days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];

    const indexSekarang = days.findIndex(
        d => d.toLowerCase() === hariSekarang.toLowerCase()
    );

    if (indexSekarang === -1) {
        return null;
    }

    const indexHasil = (indexSekarang + jumlahHari) % 7;
    return days[indexHasil];
}

rl.question("Masukkan hari sekarang: ", (hari) => {
    rl.question("Masukkan jumlah hari yang akan datang: ", (jumlah) => {
        const jumlahHari = parseInt(jumlah);

        if (isNaN(jumlahHari) || jumlahHari < 0) {
            console.log("Jumlah hari tidak valid. Harus berupa angka >= 0.");
            rl.close();
            return; // berhenti
        }

        const hasil = nextDay(hari, jumlahHari);
        if (hasil === null) {
            console.log("Nama hari tidak valid.");
        } else {
            console.log(jumlahHari, "hari setelah", hari, "adalah hari", hasil);
        }

        rl.close();
    });
});