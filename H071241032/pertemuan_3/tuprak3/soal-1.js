function countEven(n1, n2) {
  if (typeof n1 !== "number" || typeof n2 !== "number") {
    return "Invalid input";
  }
  let even = [];
  for (let i = n1; i <= n2; i++) {
    if (i % 2 === 0) {
      even.push(i); 
    }
  }
  return `${even.length} [${even}]`;
}

console.log(countEven(1, 10));
