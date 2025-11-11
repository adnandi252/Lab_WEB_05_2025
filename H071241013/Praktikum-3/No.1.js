function countEvenNumber(s, e){
    let count = [];

    if (s > e) {
        console.log("s lebih besar dari e")
        return;
    }
    for(let i = s; i <= e; i++){
        if( i % 2 == 0){
            count.push(i);
        }
    }

    console.log("Output :" + count.length + " [" + count + "]")
}

countEvenNumber(15, 10)