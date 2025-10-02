function countEvenNumber(start, end) {
    if (start === undefined || end === undefined) {
        console.error("Input tidak boleh kosong!");
        return;
    }
    if (isNaN(start) || isNaN(end)) {
        console.error("Kedua inputan harus berupa angka!");
        return;
    }
    if (end < start) {
        console.error("Angka awal harus lebih kecil dari angka akhir. Perbaiki inputan!");
        return;
    }

    var result = [];
    for (let i = start; i <= end; i++) {
        if (i % 2 === 0) {
            result.push(i);
        }
    }
    return result;
}


a = countEvenNumber(5, 20);
console.log(a.length);
console.log(a)