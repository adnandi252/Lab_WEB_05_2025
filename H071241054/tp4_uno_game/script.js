// VARIABEL GLOBAL 
let balance = 5000;
let currentBet = 0;
let deck = [];
let playerHand = [];
let botHand = [];
let discardPile = [];
let currentColor = '';
let currentValue = '';
let playerTurn = true;
let unoPressed = false;
let drawUsed = false;
let unoTimer = null;
let unoCountdown = 5;
let botCalledUno = false;
let botUnoTimer = null;
let canCatchBot = false;
let gameEnded = false;

// ELEMEN DOM
const betSection = document.getElementById("betSection");
const gameSection = document.getElementById("gameSection");
const balanceDisplay = document.getElementById("balance");
const betInput = document.getElementById("betAmount");
const startGameBtn = document.getElementById("startGameBtn");
const warning = document.getElementById("warning");

if (balanceDisplay) balanceDisplay.textContent = `$${balance}`;

// WELCOME PAGE
const mainBtn = document.getElementById("mainBtn");
const modal = document.getElementById("modal");
const closeBtn = document.getElementById("closeBtn");
const botBtn = document.getElementById("botBtn");
const friendBtn = document.getElementById("friendBtn");

if (mainBtn && modal) {
  mainBtn.addEventListener("click", () => modal.classList.remove("hidden"));
  if (closeBtn) closeBtn.addEventListener("click", () => modal.classList.add("hidden"));
}
if (botBtn) botBtn.addEventListener("click", () => window.location.href = "gameplay.html");
if (friendBtn) friendBtn.addEventListener("click", showFriendModeNotification);

// NOTIFIKASI MODE TEMAN
function showFriendModeNotification() {
  const notifModal = document.createElement("div");
  notifModal.className = "fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-50";
  notifModal.innerHTML = `
    <div class="bg-gray-900 p-8 rounded-lg shadow-2xl text-center max-w-md">
      <div class="text-6xl mb-4">🚧</div>
      <h2 class="text-3xl font-bold text-white mb-4">Mode Teman</h2>
      <p class="text-xl text-white mb-6">Fitur belum tersedia, masih dalam masa pengembangan!</p>
      <button id="friendModeOkBtn" class="bg-yellow-300 text-black px-8 py-3 rounded-lg font-bold text-xl hover:bg-yellow-500 transition">
        OK
      </button>
    </div>
  `;
  document.body.appendChild(notifModal);
  
  document.getElementById("friendModeOkBtn").addEventListener("click", () => {
    document.body.removeChild(notifModal);
  });
}

// TOMBOL START GAME
if (startGameBtn) {
  startGameBtn.addEventListener("click", () => {
    const bet = parseInt(betInput.value);
    if (isNaN(bet) || bet < 100 || bet > balance) {
      warning.classList.remove("hidden");
      return;
    }
    warning.classList.add("hidden");
    currentBet = bet;
    balance -= bet;

    const overlay = document.createElement("div");
    overlay.className = "fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-40";
    overlay.innerHTML = `<div class="text-white text-4xl font-bold animate-fade-in">Game Dimulai!</div>`;
    document.body.appendChild(overlay);
    
    setTimeout(() => {
      document.body.removeChild(overlay);
      betSection.classList.add("hidden");
      gameSection.classList.remove("hidden");
      initUNOGame();
    }, 1000);
  });
}

// INISIALISASI GAME
function initUNOGame() {
  try {
    document.getElementById("gameBalance").textContent = balance;
    document.getElementById("gameBet").textContent = currentBet;
    document.getElementById("potAmount").textContent = currentBet * 2;
    
    setupGameElements();
    createDeck();
    console.log("Setelah createDeck, deck.length:", deck.length);
    
    console.log("Akan memanggil startNewRound...");
    startNewRound();
    console.log("startNewRound selesai!");
  } catch (error) {
    console.error("Error di initUNOGame:", error);
  }
}

// SETUP ELEMEN GAME
function setupGameElements() {
  window.botCardsDiv = document.getElementById("botCards");
  window.playerCardsDiv = document.getElementById("playerCards");
  window.discardDiv = document.getElementById("discardPile");
  window.drawPileDiv = document.getElementById("drawPile");
  window.statusMessage = document.getElementById("statusMessage");
  window.drawBtn = document.getElementById("drawCard");
  window.skipBtn = document.getElementById("skipBtn");
  window.unoBtn = document.getElementById("unoBtn");
  window.callUnoBtn = document.getElementById("callUnoBtn");
  window.colorIndicator = document.getElementById('colorIndicator');
  window.colorText = document.getElementById('colorText');
  window.actionLog = document.getElementById('actionLog');
  window.unoTimerEl = document.getElementById('unoTimer');
  window.gameBalance = document.getElementById('gameBalance');
  window.botCardCount = document.getElementById('botCardCount');
  window.playerCardCount = document.getElementById('playerCardCount');

  // Hapus listener lama dengan cloneNode
  const newDrawBtn = drawBtn.cloneNode(true);
  drawBtn.parentNode.replaceChild(newDrawBtn, drawBtn);
  window.drawBtn = newDrawBtn;
  
  const newSkipBtn = skipBtn.cloneNode(true);
  skipBtn.parentNode.replaceChild(newSkipBtn, skipBtn);
  window.skipBtn = newSkipBtn;
  
  const newUnoBtn = unoBtn.cloneNode(true);
  unoBtn.parentNode.replaceChild(newUnoBtn, unoBtn);
  window.unoBtn = newUnoBtn;
  
  const newCallUnoBtn = callUnoBtn.cloneNode(true);
  callUnoBtn.parentNode.replaceChild(newCallUnoBtn, callUnoBtn);
  window.callUnoBtn = newCallUnoBtn;

  // Reset state tombol
  window.drawBtn.disabled = false;
  window.skipBtn.disabled = false;
  window.unoBtn.disabled = false;
  window.callUnoBtn.disabled = false;
  window.drawBtn.classList.remove("opacity-50");
  window.skipBtn.classList.add("hidden");
  window.unoBtn.classList.add("hidden");
  window.callUnoBtn.classList.add("hidden");
  window.unoTimerEl.classList.add("hidden");

  // Pasang event listeners
  window.drawBtn.addEventListener("click", handleDrawCard);
  window.skipBtn.addEventListener("click", handleSkipTurn);
  window.unoBtn.addEventListener("click", handleUnoPress);
  window.callUnoBtn.addEventListener("click", handleCatchBot);
  
  const newDrawPile = drawPileDiv.cloneNode(true);
  drawPileDiv.parentNode.replaceChild(newDrawPile, drawPileDiv);
  window.drawPileDiv = newDrawPile;
  window.drawPileDiv.addEventListener("click", handleDrawCard);
  
  // Ambil ulang deckCount setelah clone!
  window.deckCount = newDrawPile.querySelector('#deckCount');
  
  const newPlayerCards = playerCardsDiv.cloneNode(true);
  playerCardsDiv.parentNode.replaceChild(newPlayerCards, playerCardsDiv);
  window.playerCardsDiv = newPlayerCards;
  window.playerCardsDiv.addEventListener("click", handleCardClick);
}

// BUAT DECK
function createDeck() {
  const colors = ["red", "green", "blue", "yellow"];
  const values = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12"];
  
  deck = [];
  
  // Setiap warna punya 13 kartu
  colors.forEach(color => {
    values.forEach(value => {
      deck.push({ color, value });
    });
  });
  
  // Tambahkan Wild cards (2 kartu)
  deck.push({ color: "wild", value: "13" });
  deck.push({ color: "wild", value: "14" });
  
  shuffle(deck);
}

// MULAI ROUND BARU
function startNewRound() {
  try {
    playerHand = [];
    botHand = [];
    discardPile = [];
    playerTurn = true;
    unoPressed = false;
    botCalledUno = false;
    drawUsed = false;
    canCatchBot = false;
    gameEnded = false;
    
    stopUnoTimer();
    stopBotUnoTimer();
    
    console.log("Sebelum bagikan kartu, deck.length:", deck.length);
    
    for (let i = 0; i < 7; i++) {
      playerHand.push(deck.pop());
      botHand.push(deck.pop());
    }
    
    console.log("Setelah bagikan 14 kartu, deck.length:", deck.length);
    console.log("playerHand.length:", playerHand.length);
    console.log("botHand.length:", botHand.length);
    
    let firstCard = deck.pop();
    while (firstCard && (firstCard.color === "wild" || parseInt(firstCard.value) >= 10)) {
      deck.unshift(firstCard);
      shuffle(deck);
      firstCard = deck.pop();
    }
    
    console.log("Setelah ambil first card, deck.length:", deck.length);
    console.log("First card:", firstCard);
    
    discardPile.push(firstCard);
    currentColor = firstCard.color;
    currentValue = firstCard.value;
    
    logAction("Permainan dimulai! Giliran Anda.");
    
    console.log("Akan memanggil updateUI...");
    updateUI();
    console.log("updateUI selesai!");
  } catch (error) {
    console.error("Error di startNewRound:", error);
  }
}

// FUNGSI HELPER
function shuffle(array) {
  for (let i = array.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [array[i], array[j]] = [array[j], array[i]];
  }
}

function drawFromDeck() {
  if (deck.length === 0) {
    const topCard = discardPile.pop();
    shuffle(discardPile);
    deck = [...discardPile];
    discardPile = [topCard];
    logAction("Deck dikocok ulang!");
  }
  return deck.pop();
}

function canPlay(card) {
  if (card.color === "wild") return true;
  return card.color === currentColor || card.value === currentValue;
}

function hasPlayableCard(hand) {
  return hand.some(card => canPlay(card));
}

function interpretValue(v) {
  const map = { "10": "Skip", "11": "Reverse", "12": "+2", "13": "Wild", "14": "Wild +4" };
  return map[v] || v;
}

function logAction(msg) {
  actionLog.textContent = msg;
  setTimeout(() => actionLog.textContent = "", 4000);
}

function updateDeckCount() {
  console.log("updateDeckCount dipanggil, deck.length:", deck.length);
  console.log("deckCount element:", deckCount);
  
  if (deckCount) {
    deckCount.textContent = `Sisa: ${deck.length}`;
    console.log("deckCount.textContent setelah update:", deckCount.textContent);
  } else {
    console.error("Element deckCount tidak ditemukan!");
  }
}

// UNO TIMER
function startUnoTimer() {
  if (unoTimer) clearInterval(unoTimer);
  unoPressed = false;
  unoCountdown = 5;
  unoTimerEl.classList.remove("hidden");
  unoTimerEl.textContent = unoCountdown;
  
  unoTimer = setInterval(() => {
    unoCountdown--;
    unoTimerEl.textContent = unoCountdown;
    
    if (unoCountdown <= 0) {
      stopUnoTimer();
      if (playerHand.length === 1 && !unoPressed && !gameEnded) {
        showNotification('Bot Menangkap!', 'Bot menangkap Anda! Anda lupa teriak UNO! Denda +2 kartu!', 'danger');
        playerHand.push(drawFromDeck(), drawFromDeck());
        updateUI();
      }
    }
  }, 1000);
}

function stopUnoTimer() {
  if (unoTimer) {
    clearInterval(unoTimer);
    unoTimer = null;
  }
  unoTimerEl.classList.add("hidden");
}

// BOT UNO TIMER
function startBotUnoTimer() {
  canCatchBot = true;
  callUnoBtn.classList.remove("hidden");
  
  const botDelay = Math.floor(Math.random() * 3000) + 2000;
  
  if (botUnoTimer) clearTimeout(botUnoTimer);
  
  botUnoTimer = setTimeout(() => {
    if (botHand.length === 1 && !botCalledUno && canCatchBot && !gameEnded) {
      botCalledUno = true;
      canCatchBot = false;
      callUnoBtn.classList.add("hidden");
      
      showNotification('Bot berhasil teriak UNO!');
      logAction("Bot berhasil teriak UNO!");
    }
  }, botDelay);
}

function stopBotUnoTimer() {
  if (botUnoTimer) {
    clearTimeout(botUnoTimer);
    botUnoTimer = null;
  }
  canCatchBot = false;
  callUnoBtn.classList.add("hidden");
}

// SHOW COLOR PICKER untuk kartu wild14
function showColorPicker(callback) {
  const modal = document.getElementById("colorPickerModal");
  modal.classList.remove("hidden");
  window.colorPickerCallback = callback;
}

window.selectColorFromModal = function(color) {
  const modal = document.getElementById("colorPickerModal");
  modal.classList.add("hidden");
  
  if (window.colorPickerCallback) {
    window.colorPickerCallback(color);
    window.colorPickerCallback = null;
  }
};

// SHOW NOTIFICATION
function showNotification(title, message = '', callback = null) {
  const modal = document.getElementById("notifModal");
  const content = document.getElementById("notifContent");
  const titleEl = document.getElementById("notifTitle");
  const messageEl = document.getElementById("notifMessage");
  const okBtn = document.getElementById("notifOkBtn");
  
  titleEl.textContent = title;
  messageEl.textContent = message;
  content.className = "bg-green-600 p-8 rounded-lg shadow-2xl text-center max-w-md";
  
  modal.classList.remove("hidden");
  
  okBtn.onclick = () => {
    modal.classList.add("hidden");
    if (callback) callback();
  };
}

// CEK KEMENANGAN
function checkWinCondition(hand, isPlayer) {
  if (hand.length === 0) {
    playerTurn = false;
    gameEnded = true;
    
    if (isPlayer) {
      balance += currentBet * 2;
      gameBalance.textContent = `${balance}`;
    }
    
    endGame(isPlayer ? "player" : "bot");
    return true;
  }
  return false;
}

// MAINKAN KARTU: inti logika 
function playCard(card, isPlayer = true) {
  if (gameEnded) return;
  
  const hand = isPlayer ? playerHand : botHand;
  const playerName = isPlayer ? "Anda" : "Bot";
  
  // Validasi Wild +4
  if (card.value === "14" && isPlayer) {
    const otherCards = hand.filter(c => c !== card);
    if (hasPlayableCard(otherCards)) {
      showNotification('Tidak Bisa!', 'Wild +4 hanya bisa dimainkan jika tidak ada kartu lain yang cocok!', 'danger');
      return;
    }
  }
  
  // Hapus kartu dari tangan
  hand.splice(hand.indexOf(card), 1);
  discardPile.push(card);
  
  // Tentukan warna untuk Wild cards
  if (card.color === "wild") {
    if (isPlayer) {
      showColorPicker((chosenColor) => {
        currentColor = chosenColor;
        currentValue = card.value;
        updateColorIndicator();
        
        drawUsed = false;
        skipBtn.classList.add("hidden");
        stopUnoTimer();
        
        const cardName = interpretValue(card.value);
        logAction(`${playerName} memainkan ${cardName} dan memilih ${currentColor.toUpperCase()}`);
        
        updateUI();
        
        if (checkWinCondition(hand, isPlayer)) return;
        
        if (hand.length === 1) {
          unoBtn.classList.remove("hidden");
          startUnoTimer();
        }
        
        handleCardEffect(card, isPlayer);
      });
      return;
    } else {
      // Bot pilih warna
      const colorCount = {};
      botHand.forEach(c => {
        if (c.color !== "wild") {
          colorCount[c.color] = (colorCount[c.color] || 0) + 1;
        }
      });
      currentColor = Object.keys(colorCount).length > 0 
        ? Object.keys(colorCount).reduce((a, b) => colorCount[a] > colorCount[b] ? a : b)
        : "red";
      
      logAction(`Bot memilih warna: ${currentColor.toUpperCase()}`);
      currentValue = card.value;
      updateColorIndicator();
    }
  } else {
    currentColor = card.color;
    currentValue = card.value;
    updateColorIndicator();
  }
  
  drawUsed = false;
  skipBtn.classList.add("hidden");
  stopUnoTimer();
  
  const cardName = interpretValue(card.value);
  logAction(`${playerName} memainkan ${cardName} ${card.color !== "wild" ? currentColor.toUpperCase() : ""}`);
  
  updateUI();
  
  if (checkWinCondition(hand, isPlayer)) return;
  
  if (isPlayer && hand.length === 1) {
    unoBtn.classList.remove("hidden");
    startUnoTimer();
  }
  
  if (!isPlayer && hand.length === 1) {
    startBotUnoTimer();
  }
  
  handleCardEffect(card, isPlayer);
}

// mengatur kartu
function handleCardEffect(card, isPlayer) {
  if (gameEnded) return;
  
  const opponent = isPlayer ? botHand : playerHand;
  const opponentName = isPlayer ? "Bot" : "Anda";
  
  switch (card.value) {
    case "10": // Skip
      logAction(`${opponentName} di-skip!`);
      playerTurn = isPlayer;
      setTimeout(() => {
        if (!gameEnded) {
          updateUI();
          if (!playerTurn) botTurn();
        }
      }, 1500);
      break;
      
    case "11": // Reverse
      logAction(`${opponentName} di-skip (Reverse)!`);
      playerTurn = isPlayer;
      setTimeout(() => {
        if (!gameEnded) {
          updateUI();
          if (!playerTurn) botTurn();
        }
      }, 1500);
      break;
      
    case "12": // +2
      opponent.push(drawFromDeck(), drawFromDeck());
      logAction(`${opponentName} mengambil 2 kartu dan di-skip!`);
      playerTurn = isPlayer;
      setTimeout(() => {
        if (!gameEnded) {
          updateUI();
          if (!playerTurn) botTurn();
        }
      }, 1500);
      break;
      
    case "14": // Wild +4
      for (let i = 0; i < 4; i++) {
        opponent.push(drawFromDeck());
      }
      logAction(`${opponentName} mengambil 4 kartu dan di-skip!`);
      playerTurn = isPlayer;
      setTimeout(() => {
        if (!gameEnded) {
          updateUI();
          if (!playerTurn) botTurn();
        }
      }, 1500);
      break;
      
    default: // Kartu biasa
      playerTurn = !isPlayer;
      setTimeout(() => {
        if (!gameEnded) {
          updateUI();
          if (!playerTurn) botTurn();
        }
      }, 800);
  }
  
  updateUI();
}

// GILIRAN BOT
function botTurn() {
  if (!playerTurn && !gameEnded) {
    statusMessage.textContent = "Giliran Bot...";
    
    setTimeout(() => {
      if (gameEnded) return;
      
      const playableCards = botHand.filter(card => canPlay(card));
      
      if (playableCards.length > 0) {
        let cardToPlay = playableCards.find(c => ["10", "11", "12", "14"].includes(c.value)) 
                      || playableCards.find(c => c.value === "13")
                      || playableCards[0];
        
        if (cardToPlay.value === "14" && playableCards.length > 1) {
          const otherPlayable = playableCards.filter(c => c.value !== "14");
          if (otherPlayable.length > 0) {
            cardToPlay = otherPlayable[0];
          }
        }
        
        playCard(cardToPlay, false);
      } else {
        const drawnCard = drawFromDeck();
        botHand.push(drawnCard);
        logAction("Bot mengambil 1 kartu.");
        updateUI();
        
        if (canPlay(drawnCard)) {
          setTimeout(() => {
            if (!gameEnded) {
              logAction("Bot memainkan kartu yang baru ditarik!");
              playCard(drawnCard, false);
            }
          }, 1000);
        } else {
          playerTurn = true;
          setTimeout(() => {
            if (!gameEnded) updateUI();
          }, 1000);
        }
      }
    }, 1500);
  }
} 

// EVENT HANDLERS
function handleCardClick(e) {
  if (!playerTurn || gameEnded) return;
  
  const cardEl = e.target.closest("div[data-index]");
  if (!cardEl) return;
  
  const index = parseInt(cardEl.dataset.index);
  const card = playerHand[index];
  
  if (canPlay(card)) {
    playCard(card, true);
  } else {
    showNotification('Kartu Tidak Bisa Dimainkan!', 'Kartu tidak cocok dengan warna atau angka!', 'warning');
  }
}

function handleDrawCard() {
  if (!playerTurn || drawUsed || gameEnded) return;
  
  stopUnoTimer();
  const drawnCard = drawFromDeck();
  playerHand.push(drawnCard);
  drawUsed = true;
  
  logAction("Anda mengambil 1 kartu.");
  skipBtn.classList.remove("hidden");
  
  updateUI();
  
  if (canPlay(drawnCard)) {
    logAction("Kartu yang ditarik bisa dimainkan! Mainkan atau skip.");
  }
}

function handleSkipTurn() {
  if (!playerTurn || gameEnded) return;
  
  stopUnoTimer();
  playerTurn = false;
  drawUsed = false;
  skipBtn.classList.add("hidden");
  
  logAction("Anda melewati giliran.");
  updateUI();
  
  setTimeout(() => {
    if (!gameEnded) botTurn();
  }, 1000);
}

function handleUnoPress() {
  if (playerHand.length === 1 && !unoPressed && !gameEnded) {
    unoPressed = true;
    stopUnoTimer();
    unoBtn.classList.add("hidden");
    
    showNotification('UNO!', 'Anda berhasil teriak UNO!', 'success');
    logAction("Anda berhasil teriak UNO!");
  }
}

function handleCatchBot() {
  if (botHand.length === 1 && !botCalledUno && canCatchBot && !gameEnded) {
    stopBotUnoTimer();
    
    botHand.push(drawFromDeck(), drawFromDeck());
    
    botCalledUno = false;
    canCatchBot = false;
    callUnoBtn.classList.add("hidden");
    
    showNotification('Berhasil Menangkap!', 'Anda berhasil menangkap Bot! Bot mendapat denda +2 kartu karena telat teriak UNO!', 'success');
    logAction("Bot terkena denda +2 kartu karena lupa teriak UNO!");
    
    updateUI();
  } else {
    showNotification('Gagal!', 'Bot telah berhasil teriak UNO!', 'warning');
  }
}

// SHOW GAME OVER MODAL
function showGameOverModal(winner, hadiah, kalah) {
  const modal = document.getElementById("gameOverModal");
  const content = document.getElementById("gameOverContent");
  const title = document.getElementById("gameOverTitle");
  const betAmount = document.getElementById("gameOverBet");
  const resultLabel = document.getElementById("gameOverResultLabel");
  const resultAmount = document.getElementById("gameOverResult");
  const balanceAmount = document.getElementById("gameOverBalance");
  const playAgainBtn = document.getElementById("playAgainBtn");
  const exitGameBtn = document.getElementById("exitGameBtn");
  
  betAmount.textContent = currentBet;
  balanceAmount.textContent = balance;
  
  if (winner === "player") {
    content.className = "bg-green-600 p-10 shadow-2xl text-center max-w-lg transform transition-all";
    title.textContent = "SELAMAT! ANDA MENANG!";
    resultLabel.textContent = "Hadiah:";
    resultAmount.textContent = hadiah;
    resultAmount.className = "text-yellow-300 font-bold";
  } else {
    content.className = "bg-red-400 p-10 rounded-2xl shadow-2xl text-center max-w-lg transform transition-all";
    title.textContent = "BOT MENANG!";
    resultLabel.textContent = "Anda Kalah:";
    resultAmount.textContent = kalah;
    resultAmount.className = "text-red-300 font-bold";
  }
  
  modal.classList.remove("hidden");
  
  playAgainBtn.onclick = () => {
    modal.classList.add("hidden");
    if (balance >= 100) {
      resetTobetting();
    } else {
      showNotification('Saldo Tidak Cukup!', `Saldo Anda: ${balance}\n\nMinimum taruhan adalah $100. Anda akan dikembalikan ke halaman utama.`, 'danger', () => {
        window.location.href = "index.html";
      });
    }
  };
  
  exitGameBtn.onclick = () => {
    modal.classList.add("hidden");
    window.location.href = "index.html";
  };
}

// END GAME
function endGame(winner) {
  gameEnded = true;
  stopUnoTimer();
  stopBotUnoTimer();
  playerTurn = false;
  
  drawBtn.disabled = true;
  skipBtn.disabled = true;
  unoBtn.disabled = true;
  callUnoBtn.disabled = true;
  drawBtn.classList.add("opacity-50");
  
  if (balanceDisplay) balanceDisplay.textContent = `${balance}`;
  
  setTimeout(() => {
    if (winner === "player") {
      showGameOverModal("player", currentBet * 2, 0);
    } else {
      if (balance >= 100) {
        showGameOverModal("bot", 0, currentBet);
      } else {
        showGameOverModal("gameover", 0, 0, "Saldo Anda habis. Game Over.");
      }
    }
  }, 300);
}

function resetTobetting() {
  // Reset variables
  currentBet = 0;
  playerHand = [];
  botHand = [];
  discardPile = [];
  currentColor = '';
  currentValue = '';
  playerTurn = true;
  unoPressed = false;
  drawUsed = false;
  botCalledUno = false;
  canCatchBot = false;
  gameEnded = false;
  
  stopUnoTimer();
  stopBotUnoTimer();
  
  // Buat deck baru SEBELUM reset UI
  deck = [];
  createDeck();
  
  gameSection.classList.add("hidden");
  betSection.classList.remove("hidden");
  
  if (balanceDisplay) balanceDisplay.textContent = `${balance}`;
  betInput.value = '';
  warning.classList.add("hidden");
}

// UPDATE UI
function updateUI() {
  if (gameEnded) return;
  
  botCardsDiv.innerHTML = botHand.map(() => 
    `<img src="assets/back.png" class="w-14 h-20 rounded shadow-md">`
  ).join("");
  botCardCount.textContent = botHand.length;
  
  playerCardsDiv.innerHTML = playerHand.map((card, i) => {
    const playable = canPlay(card) && playerTurn && !drawUsed;
    return `
      <div data-index="${i}" class="cursor-pointer transition-transform hover:scale-105">
        <img src="assets/${card.color}${card.value}.png" 
             onerror="this.src='assets/back.png'" 
             class="w-14 h-20 rounded shadow-md ${playable ? 'ring-2 ring-green-400' : ''}" 
             alt="${card.color} ${card.value}">
      </div>
    `;
  }).join("");
  playerCardCount.textContent = playerHand.length;
  
  const topCard = discardPile[discardPile.length - 1];
  discardDiv.innerHTML = `
    <img src="assets/${topCard.color}${topCard.value}.png" 
         onerror="this.src='assets/back.png'" 
         class="w-20 h-28 rounded shadow-md">
  `;
  
  statusMessage.textContent = playerTurn ? "🎮 Giliran Anda" : "🤖 Giliran Bot";
  updateColorIndicator();
  updateDeckCount();
  
  // Debug: cek apakah deckCount masih benar setelah semua update
  console.log("Setelah semua update UI, deckCount.textContent:", document.getElementById('deckCount').textContent);
  
  drawBtn.disabled = !playerTurn || drawUsed;
  drawBtn.classList.toggle("opacity-50", !playerTurn || drawUsed);
  
  skipBtn.classList.toggle("hidden", !playerTurn || !drawUsed);
  unoBtn.classList.toggle("hidden", playerHand.length !== 1);
  callUnoBtn.classList.toggle("hidden", botHand.length !== 1 || !playerTurn || botCalledUno);
  
  if (playerHand.length === 1 && !unoPressed && !unoTimer) {
    startUnoTimer();
  } else if (playerHand.length !== 1) {
    stopUnoTimer();
  }
  
  gameBalance.textContent = `${balance}`;
}

function updateColorIndicator() {
  colorIndicator.style.backgroundColor = currentColor;
  colorText.textContent = currentColor.toUpperCase();
}