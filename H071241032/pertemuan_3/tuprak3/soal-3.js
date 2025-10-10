const readline = require("readline");

const rl = readline.createInterface({
  input: process.stdin,
  output: process.stdout,
});

function soal3() {
  let hariArr = ["senin", "selasa", "rabu", "kamis", "jumat", "sabtu", "minggu"];
  rl.question("Masukkan hari : ", (hari) => {
    if (!hariArr.includes(hari)) {
        console.log("Hari yang dimasukkan tidak valid");
        rl.close();
        return;
      }
    rl.question("Masukkan hari yang akan datang : ", (jumlahHari) => {
      jumlahHari = parseInt(jumlahHari, 10);
      if (isNaN(jumlahHari) || jumlahHari <= 0) {
        console.log("Jumlah hari yang dimasukkan tidak valid");
        rl.close();
        return;
      }
      hari = hari.toLowerCase();
      let indexHari = (hariArr.indexOf(hari) + jumlahHari) % 7;
      console.log(`${jumlahHari} hari lagi adalah hari ${hariArr[indexHari]}`);
      rl.close();
    });
  });
}

soal3();