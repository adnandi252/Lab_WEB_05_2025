function countEvenNumbers(start, end) {
  const evenNumbers = [];
  if (end < start) {
    console.log ("inputan end tidak boleh lebih besar dari start")
    return;
  }
  for (let i = start; i <= end; i++) {
    if (i % 2 === 0) evenNumbers.push(i);
  }
  console.log(`${evenNumbers.length} [${evenNumbers.join(', ')}]`);
  
}

countEvenNumbers(3,2);
