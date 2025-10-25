// script.js
// UNO Arena - Complete game logic with UNO & Call UNO features
// Revisi lengkap sesuai permintaan: action-keeps-turn, wild/plus4 color pick => switch turn,
// pemain & bot UNO handling (bot 70% tekan UNO), Call UNO button (diciptakan via JS),
// pemain dapat mengambil kartu manual dari deck jika giliran tetap tetapi tidak punya playable cards.

// ---------------------------------------------
// --- KARTU & DECK ----------------------------
// ---------------------------------------------
const colors = ["red", "blue", "green", "yellow"];
const actionValues = ["skip", "reverse", "plus2"];
const values = [
  "0","1","2","3","4","5","6","7","8","9",
  ...actionValues
];

// createDeck: angka, aksi, wilds (4 wild, 4 plus4)
function createDeck() {
  const deck = [];
  for (const color of colors) {
    // 0 single
    deck.push({ color, value: "0", image: `${color}_0.png` });
    // 1-9 double
    for (let i = 1; i <= 9; i++) {
      deck.push({ color, value: `${i}`, image: `${color}_${i}.png` });
      deck.push({ color, value: `${i}`, image: `${color}_${i}.png` });
    }
    // action cards x2
    deck.push({ color, value: "skip", image: `${color}_skip.png` });
    deck.push({ color, value: "skip", image: `${color}_skip.png` });
    deck.push({ color, value: "reverse", image: `${color}_reverse.png` });
    deck.push({ color, value: "reverse", image: `${color}_reverse.png` });
    deck.push({ color, value: "plus2", image: `${color}_plus2.png` });
    deck.push({ color, value: "plus2", image: `${color}_plus2.png` });
  }
  // wilds
  for (let i = 0; i < 4; i++) {
    deck.push({ color: "wild", value: "wild", image: "wild.png" });
    deck.push({ color: "wild", value: "plus4", image: "plus_4.png" });
  }
  return deck;
}

// ---------------------------------------------
// --- STATE PERMAINAN -------------------------
// ---------------------------------------------
let gameDeck = [];
let playerHand = [];
let botHand = [];
let discardPile = []; // stack, top = last element
let currentPlayer = "player"; // 'player' | 'bot'
let playerBalance = 5000;
let currentBet = 0;
let isBettingPhase = true;

// UNO-related
let unoButtonTimer = null;
let unoButtonActive = false;
let botUnoForgotFlag = false; // true jika bot lupa UNO saat tinggal 1 kartu
let botSaidUno = false; // true jika bot shout UNO (no penalti)
let callUnoTimer = null;

// Turn control
let gameDirection = 1; // not heavily used for 2 players but kept
let isCardPlayedThisTurn = false; // track if player already acted to control draw-per-turn
let waitingForWildChoice = false; // block interactions while picking color

// ---------------------------------------------
// --- DOM ELEMENTS ----------------------------
// ---------------------------------------------
const playerHandEl = document.getElementById("player-hand");
const botHandEl = document.getElementById("bot-hand");
const deckEl = document.getElementById("deck");
const discardPileEl = document.getElementById("discard-pile");
const gameStatusEl = document.getElementById("game-status");
const unoButtonEl = document.getElementById("uno-button");
const unoButtonContainerEl = document.querySelector(".uno-button-container");
const playerCardCountEl = document.getElementById("player-card-count");
const botCardCountEl = document.getElementById("bot-card-count");
const playerBalanceDisplayEl = document.getElementById("player-balance-display");
const currentBetDisplayEl = document.getElementById("current-bet-display");
const bettingAreaEl = document.getElementById("betting-area");
const betInputEl = document.getElementById("bet-input");
const placeBetButtonEl = document.getElementById("place-bet-button");
const wildColorsEl = document.getElementById("wild-colors");
const gameOverModalEl = document.getElementById("game-over-modal");
const gameOverTitleEl = document.getElementById("game-over-title");
const gameOverMessageEl = document.getElementById("game-over-message");
const restartButtonEl = document.getElementById("restart-button");

// We'll dynamically create Call-UNO button (so no HTML change required)
let callUnoButtonEl = null;
function ensureCallUnoButtonExists() {
  if (callUnoButtonEl) return;
  // create container adjacent to UNO button container
  const container = document.createElement("div");
  container.className = "call-uno-container hidden";
  container.style.marginTop = "0.5rem";
  const btn = document.createElement("button");
  btn.id = "call-uno-button";
  btn.textContent = "Call UNO";
  btn.className =
    "bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full text-lg";
  container.appendChild(btn);
  // append next to UNO container
  unoButtonContainerEl.parentElement.appendChild(container);
  callUnoButtonEl = btn;
  // add listener
  callUnoButtonEl.addEventListener("click", onCallUnoClicked);
}

// ---------------------------------------------
// --- HELPER / UTILITY ------------------------
// ---------------------------------------------
function shuffleDeck(deck) {
  for (let i = deck.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [deck[i], deck[j]] = [deck[j], deck[i]];
  }
}

// Refill gameDeck from discard except top if gameDeck empty
function refillDeckFromDiscardIfNeeded() {
  if (gameDeck.length === 0) {
    if (discardPile.length <= 1) return;
    const top = discardPile.pop();
    gameDeck = [...discardPile];
    shuffleDeck(gameDeck);
    discardPile = [top];
  }
}

/** return number of playable cards in given hand */
function countPlayable(hand) {
  if (discardPile.length === 0) return hand.length;
  const top = discardPile[discardPile.length - 1];
  return hand.reduce((acc, c) => acc + (canPlayCard(c) ? 1 : 0), 0);
}

/** Helper: checks if card can be played on top of discard */
function canPlayCard(card) {
  if (!card) return false;
  if (discardPile.length === 0) return true;
  const top = discardPile[discardPile.length - 1];
  // if top is wild but we stored chosen color in top.color, compare to that
  if (card.color === "wild") return true; // wild can always be played (we check +4 legality separately)
  return card.color === top.color || card.value === top.value;
}

/** Check legality of +4: only allowed if player has no other playable non-wild card */
function isLegalPlayPlus4(handLocal) {
  if (discardPile.length === 0) return true;
  const top = discardPile[discardPile.length - 1];
  return !handLocal.some((c) => c.color !== "wild" && (c.color === top.color || c.value === top.value));
}

// ---------------------------------------------
// --- RENDER / UI -----------------------------
// ---------------------------------------------
function renderHands() {
  // Player hand: show actual cards
  playerHandEl.innerHTML = "";
  playerHand.forEach((card, idx) => {
    const cardEl = document.createElement("div");
    cardEl.classList.add("card", "card-player");
    cardEl.style.backgroundImage = `url('kartu/${card.image}')`;
    cardEl.dataset.color = card.color;
    cardEl.dataset.value = card.value;
    cardEl.dataset.index = idx;
    playerHandEl.appendChild(cardEl);
  });

  // Bot hand: show backs
  botHandEl.innerHTML = "";
  botHand.forEach(() => {
    const cardEl = document.createElement("div");
    cardEl.classList.add("card", "card-bot");
    cardEl.style.backgroundImage = `url('kartu/card_back.png')`;
    botHandEl.appendChild(cardEl);
  });
}

function updateUI(statusText) {
  renderHands();

  if (discardPile.length > 0) {
    const top = discardPile[discardPile.length - 1];
    discardPileEl.style.backgroundImage = `url('kartu/${top.image}')`;
    // If top color set (after wild pick), we use that color for border
    if (top.color && top.color !== "wild") {
      discardPileEl.parentElement.style.borderColor = top.color;
      discardPileEl.parentElement.style.borderStyle = "solid";
      discardPileEl.parentElement.style.borderWidth = "2px";
    } else {
      discardPileEl.parentElement.style.borderColor = "transparent";
    }
  } else {
    discardPileEl.style.backgroundImage = "";
    discardPileEl.parentElement.style.borderColor = "transparent";
  }

  playerCardCountEl.textContent = playerHand.length;
  botCardCountEl.textContent = botHand.length;
  playerBalanceDisplayEl.textContent = `Saldo: $${playerBalance}`;
  currentBetDisplayEl.textContent = currentBet;

  if (statusText !== undefined) {
    gameStatusEl.textContent = statusText;
  }
}

// ---------------------------------------------
// --- DRAW / DEAL / GAME END ------------------
// ---------------------------------------------
function drawCards(hand, num) {
  for (let i = 0; i < num; i++) {
    refillDeckFromDiscardIfNeeded();
    if (gameDeck.length === 0) break;
    hand.push(gameDeck.pop());
  }
  // If player got more than 1 card, hide UNO timer/button
  if (playerHand.length > 1) {
    clearUnoTimer();
  }
  updateUI();
}

/** Deal initial 7 cards each and set starting discard */
function dealCards() {
  playerHand = [];
  botHand = [];
  for (let i = 0; i < 7; i++) {
    refillDeckFromDiscardIfNeeded();
    playerHand.push(gameDeck.pop());
    refillDeckFromDiscardIfNeeded();
    botHand.push(gameDeck.pop());
  }
  // pick start card not an action/wild ideally
  let start = gameDeck.pop();
  let tries = 0;
  while ((start.value === "skip" || start.value === "reverse" || start.value === "plus2" || start.value === "plus4" || start.color === "wild") && tries < 50) {
    gameDeck.unshift(start);
    shuffleDeck(gameDeck);
    start = gameDeck.pop();
    tries++;
  }
  discardPile = [start];
}

function startNextRound() {
  gameDeck = createDeck();
  shuffleDeck(gameDeck);
  dealCards();
  currentPlayer = "player";
  isCardPlayedThisTurn = false;
  waitingForWildChoice = false;
  botUnoForgotFlag = false;
  botSaidUno = false;
  clearUnoTimer();
  hideCallUnoUI();
  bettingAreaEl.style.display = "none";
  gameOverModalEl.classList.add("hidden");
  gameStatusEl.textContent = "Giliran Anda. Ronde baru dimulai!";
  updateUI();
}


// End round
function finishRound(playerWon) {
  // Tampilkan pesan kemenangan/kekalahan
  if (playerWon) {
    playerBalance += currentBet;
    gameStatusEl.textContent = `Anda menang ronde ini! +$${currentBet}`;
  } else {
    playerBalance -= currentBet;
    gameStatusEl.textContent = `Anda kalah ronde ini. -$${currentBet}`;
  }

  currentBet = 0;

  // Jika saldo habis -> Game Over
  if (playerBalance <= 0) {
    gameOverTitleEl.textContent = "Game Over";
    gameOverMessageEl.textContent = "Saldo Anda habis. Mulai ulang untuk main lagi.";
    restartButtonEl.style.display = "block";
    gameOverModalEl.classList.remove("hidden");
  } else {
    // Tampilkan area taruhan lagi
    bettingAreaEl.style.display = "flex";
    gameStatusEl.textContent = "Pasang taruhan untuk ronde berikutnya.";
    updateUI();
  }

  // hide UNO / Call UNO
  hidePlayerUnoButton();
  hideCallUnoUI();
}




// ---------------------------------------------
// --- TURN CONTROL ----------------------------
// ---------------------------------------------
function switchTurnNormal() {
  currentPlayer = currentPlayer === "player" ? "bot" : "player";
  isCardPlayedThisTurn = false;
  updateUI(currentPlayer === "player" ? "Giliran Anda." : "Giliran Bot.");
  if (currentPlayer === "bot") {
    setTimeout(() => handleBotTurn(), 700);
  }
}

/** switch directly to specific player */
function switchTurnTo(target) {
  currentPlayer = target;
  isCardPlayedThisTurn = false;
  updateUI(currentPlayer === "player" ? "Giliran Anda." : "Giliran Bot.");
  if (currentPlayer === "bot") {
    setTimeout(() => handleBotTurn(), 700);
  }
}

// ---------------------------------------------
// --- UNO / CALL UNO LOGIC --------------------
// ---------------------------------------------
ensureCallUnoButtonExists();

// show/hide call UNO button container
function showCallUnoUI() {
  if (!callUnoButtonEl) ensureCallUnoButtonExists();
  if (callUnoButtonEl && callUnoButtonEl.parentElement) {
    callUnoButtonEl.parentElement.classList.remove("hidden");
  }
}
function hideCallUnoUI() {
  if (callUnoButtonEl && callUnoButtonEl.parentElement) {
    callUnoButtonEl.parentElement.classList.add("hidden");
  }
}

// show/hide player's UNO button container
function showPlayerUnoButton() {
  unoButtonContainerEl.classList.remove("hidden");
}
function hidePlayerUnoButton() {
  unoButtonContainerEl.classList.add("hidden");
}

function showTemporaryMessage(message, duration = 1000) {
  // Bersihkan timeout sebelumnya jika ada
  if (window.tempMessageTimer) {
    clearTimeout(window.tempMessageTimer);
  }
  gameStatusEl.textContent = message;
  window.tempMessageTimer = setTimeout(() => {
    gameStatusEl.textContent = "";
  }, duration);
}

/** Start UNO timer for player when playerHand length === 1 */
function startUnoTimer() {
  clearUnoTimer();
  unoButtonActive = true;
  showPlayerUnoButton();
  showTemporaryMessage("UNO! Tekan tombol UNO dalam 5 detik!");
  unoButtonTimer = setTimeout(() => {
    if (unoButtonActive) {
      // Player forgot -> penalti +2
      gameStatusEl.textContent = "Terlambat! Penalti +2 kartu.";
      drawCards(playerHand, 2);
      unoButtonActive = false;
      hidePlayerUnoButton();
      updateUI();
    }
  }, 5000);
}

/** Clear player's UNO timer & hide button */
function clearUnoTimer() {
  if (unoButtonTimer) {
    clearTimeout(unoButtonTimer);
    unoButtonTimer = null;
  }
  unoButtonActive = false;
  hidePlayerUnoButton();
}

// When player clicks UNO button
unoButtonEl.addEventListener("click", () => {
  if (!unoButtonActive) return;
  clearUnoTimer();
  showTemporaryMessage("Anda menekan tombol UNO tepat waktu!");
});

// Handler for Call UNO clicked by player
function onCallUnoClicked() {
  // Only valid if bot forgot UNO and has exactly 1 card
  if (botUnoForgotFlag && botHand.length === 1) {
    // Player successfully called UNO on bot -> bot penalti +2
    drawCards(botHand, 2);
    botUnoForgotFlag = false;
    botSaidUno = false;
    gameStatusEl.textContent = "Anda memanggil UNO! Bot mendapat penalti +2 kartu.";
    hideCallUnoUI();
    if (callUnoTimer) {
      clearTimeout(callUnoTimer);
      callUnoTimer = null;
    }
    updateUI();
    // after penalti, continue normal flow: if it was player's turn before call, nothing change.
    return;
  } else {
    gameStatusEl.textContent = "Call UNO tidak valid saat ini.";
  }
}

// Bot reaching 1 card logic: decide whether bot shouts UNO or forgets
function handleBotReachedOneCard() {
  // 70% chance bot shouts UNO, 30% forgets
  const chance = Math.random();
  if (chance < 0.7) {
    botSaidUno = true;
    botUnoForgotFlag = false;
    // show a short message
    showTemporaryMessage("Bot teriak UNO!");
    // hide call UI if shown
    hideCallUnoUI();
  } else {
    botSaidUno = false;
    botUnoForgotFlag = true;
    // show call UNO button to player for 5 seconds
    showCallUnoUI();
    showTemporaryMessage("Bot lupa menekan UNO! Tekan Call UNO dalam 5 detik untuk menjatuhkan penalti.");
    // start callUnoTimer 5s: if player doesn't call in time, bot will get penalti automatically
    if (callUnoTimer) {
      clearTimeout(callUnoTimer);
      callUnoTimer = null;
    }
    callUnoTimer = setTimeout(() => {
      if (botUnoForgotFlag) {
        drawCards(botHand, 2);
        botUnoForgotFlag = false;
        gameStatusEl.textContent = "Waktu habis! Bot lupa UNO, bot mendapat penalti +2 kartu.";
        hideCallUnoUI();
        updateUI();
      }
    }, 5000);
  }
}

// ---------------------------------------------
// --- HANDLE CARD PLAY ------------------------
// ---------------------------------------------
function handleCardPlay(cardObj, player) {
  // Block interactions if choosing wild color
  if (waitingForWildChoice) return;

  const hand = player === "player" ? playerHand : botHand;
  // find index in that player's hand (match first found)
  const index = hand.findIndex((c) => c.color === cardObj.color && c.value === cardObj.value);
  if (index === -1) return;

  // +4 legitimacy for players: cannot play +4 if you have other playable card
  if (player === "player" && cardObj.value === "plus4") {
    if (!isLegalPlayPlus4(playerHand)) {
      gameStatusEl.textContent = "Anda tidak boleh memainkan +4 jika masih punya kartu lain yang bisa dimainkan.";
      return;
    }
  }

  const playedCard = hand.splice(index, 1)[0];
  // push to discard (we will set color if wild chosen)
  discardPile.push(playedCard);
  isCardPlayedThisTurn = true;
  updateUI();

  // UNO: if after play a player's hand length ===1 => start UNO timer / bot handling
  if (player === "player") {
    if (playerHand.length === 1) {
      startUnoTimer();
    } else {
      clearUnoTimer();
    }
  } else {
    // bot
    if (botHand.length === 1) {
      // handle bot UNO behavior
      handleBotReachedOneCard();
    }
  }

  // Check win
  if (playerHand.length === 0 || botHand.length === 0) {
    if (playerHand.length === 0) finishRound(true);
    else finishRound(false);
    return;
  }

  // ACTIONS: plus2 / skip / reverse keep turn for player who played them
  if (playedCard.value === "plus2") {
    const targetHand = player === "player" ? botHand : playerHand;
    drawCards(targetHand, 2);
    updateUI();
    // message
    if (player === "player") gameStatusEl.textContent = "Anda memainkan +2. Bot mengambil 2 kartu. Giliran Anda tetap.";
    else gameStatusEl.textContent = "Bot memainkan +2. Anda mengambil 2 kartu. Bot tetap memiliki giliran.";

    // After playing +2, the player who played it keeps turn.
    // But rule addition: if the player who played +2 has no playable card left,
    // they should still be allowed to manually draw from the deck.
    // So we do NOT switch turn here. For bot, we schedule it to continue if it has playable cards.
    if (player === "bot") {
      setTimeout(() => {
        // Bot continues: either play if has playable or draw then pass
        handleBotTurn();
      }, 700);
    }
    // Do not switch turn
    return;
  }

  if (playedCard.value === "skip") {
    // skip acts like keep-turn for 2-player variant
    if (player === "player") {
      gameStatusEl.textContent = "Anda memainkan SKIP. Giliran Anda tetap.";
      updateUI();
    } else {
      gameStatusEl.textContent = "Bot memainkan SKIP. Bot tetap memiliki giliran.";
      updateUI();
      setTimeout(() => handleBotTurn(), 700);
    }
    // do not switch turn
    return;
  }

  if (playedCard.value === "reverse") {
    // reverse flips direction; in 2-player treat as skip (keep turn)
    gameDirection *= -1;
    if (player === "player") {
      gameStatusEl.textContent = "Anda memainkan REVERSE. Arah dibalik (efek seperti SKIP). Giliran Anda tetap.";
      updateUI();
    } else {
      gameStatusEl.textContent = "Bot memainkan REVERSE. Bot tetap memiliki giliran.";
      updateUI();
      setTimeout(() => handleBotTurn(), 700);
    }
    return;
  }

  // WILD / PLUS4: player chooses color (if player), or bot chooses color and apply penalty
  if (playedCard.color === "wild") {
    if (player === "player") {
      // Show color picker and set waitingForWildChoice; after color pick we shift turn to opponent
      waitingForWildChoice = true;
      showWildColorPicker(playedCard, "player");
      return;
    } else {
      // bot chooses color
      const chosen = pickBestColorForBot();
      discardPile[discardPile.length - 1].color = chosen;
      if (playedCard.value === "plus4") {
        drawCards(playerHand, 4);
        gameStatusEl.textContent = `Bot memainkan +4 dan memilih warna ${chosen.toUpperCase()}. Anda mengambil 4 kartu.`;
      } else {
        gameStatusEl.textContent = `Bot memainkan WILD dan memilih warna ${chosen.toUpperCase()}.`;
      }
      updateUI();
      // After bot plays wild/+4, the turn shifts to player
      setTimeout(() => switchTurnTo("player"), 700);
      return;
    }
  }

  // Otherwise number card -> switch turn normally
  switchTurnNormal();
}

// ---------------------------------------------
// --- WILD COLOR PICKER -----------------------
// ---------------------------------------------
function showWildColorPicker(card, actor = "player") {
  // actor === 'player' only used here
  if (actor !== "player") return;
  // open the modal (wildColorsEl)
  wildColorsEl.style.display = "flex";

  // remove previous handlers to avoid duplication
  wildColorsEl.querySelectorAll("div").forEach((d) => (d.onclick = null));

  wildColorsEl.querySelectorAll("div").forEach((picker) => {
    picker.onclick = () => {
      if (!waitingForWildChoice) return;
      const newColor = picker.dataset.color;
      // apply chosen color to top of discard
      if (discardPile.length > 0) {
        discardPile[discardPile.length - 1].color = newColor;
      }
      wildColorsEl.style.display = "none";
      waitingForWildChoice = false;
      updateUI(`Warna berubah menjadi ${newColor.toUpperCase()}.`);
      // If the chosen wild is plus4, bot draws 4
      const top = discardPile[discardPile.length - 1];
      if (top && top.value === "plus4") {
        drawCards(botHand, 4);
        updateUI("Bot mengambil 4 kartu karena +4.");
      }
      // After player chooses color for wild/+4, turn shifts to bot
      setTimeout(() => {
        switchTurnTo("bot");
      }, 400);
    };
  });
}

// ---------------------------------------------
// --- BOT AI HELPERS --------------------------
// ---------------------------------------------
function pickBestColorForBot() {
  const counts = { red: 0, blue: 0, green: 0, yellow: 0 };
  for (const c of botHand) {
    if (counts.hasOwnProperty(c.color)) counts[c.color]++;
  }
  let best = colors[0];
  let bestCount = -1;
  for (const col of colors) {
    if (counts[col] > bestCount) {
      bestCount = counts[col];
      best = col;
    }
  }
  if (bestCount <= 0) {
    return colors[Math.floor(Math.random() * colors.length)];
  }
  return best;
}

// ---------------------------------------------
// --- BOT TURN LOGIC --------------------------
// ---------------------------------------------
function handleBotTurn() {
  // If waiting for wild choice by player, bot waits
  if (waitingForWildChoice) return;

  // If it's not bot's turn, do nothing
  if (currentPlayer !== "bot") return;

  // decide playable cards
  const playable = botHand.filter((c) => canPlayCard(c));
  // prefer action (plus2/skip/reverse), else number, else wild+4 legal
  let chosen = null;
  chosen = playable.find((c) => ["plus2", "skip", "reverse"].includes(c.value)) || playable.find((c) => c.value !== "plus4") || playable[0];

  // special: if no playable but bot has plus4 and it's legal (no other playable), play it
  if (!chosen) {
    const plus4 = botHand.find((c) => c.value === "plus4");
    if (plus4) {
      const otherPlayable = botHand.filter((c) => c.value !== "plus4" && canPlayCard(c));
      if (otherPlayable.length === 0) {
        chosen = plus4;
      }
    }
  }

  if (chosen) {
    // play chosen
    // small delay for realism
    setTimeout(() => {
      handleCardPlay(chosen, "bot");
      // After playing, we must consider the "action-keeps-turn" rule:
      // - If the played card is action (plus2/skip/reverse), bot will continue in its own handleCardPlay (we scheduled there).
      // - If wild/plus4, handleCardPlay will pick color and switch to player.
      // - If number, handleCardPlay will switch to player normally.
    }, 350);
    return;
  }

  // No playable: draw one card (bot chooses to draw)
  drawCards(botHand, 1);
  updateUI("Bot mengambil kartu.");
  // After draw, check if the drawn card is playable; if yes, bot will play immediately
  setTimeout(() => {
    const playableAfter = botHand.filter((c) => canPlayCard(c));
    if (playableAfter.length > 0) {
      handleCardPlay(playableAfter[playableAfter.length - 1], "bot");
    } else {
      // pass turn to player
      switchTurnNormal();
    }
  }, 500);
}

// ---------------------------------------------
// --- EVENT LISTENERS (USER INTERACTIONS) -----
// ---------------------------------------------

// Place bet
placeBetButtonEl.addEventListener("click", () => {
  const bet = parseInt(betInputEl.value);
  if (isNaN(bet) || bet < 100 || bet > playerBalance) {
    alert("Taruhan tidak valid. Minimal $100 dan tidak melebihi saldo.");
    return;
  }
  currentBet = bet;
  isBettingPhase = false;
  bettingAreaEl.style.display = "none";
  startGame();
});

// Click deck to draw (player manual)
deckEl.addEventListener("click", () => {
  if (isBettingPhase) return;
  if (currentPlayer !== "player") return;
  if (waitingForWildChoice) return;

  // Player can pick a card from deck if it's their turn.
  // Even if they previously played an action card (and rule says they keep turn), they may still draw if they have no playable cards.
  // To avoid multiple draws in a single decision, use isCardPlayedThisTurn flag.
  if (!isCardPlayedThisTurn) {
    drawCards(playerHand, 1);
    updateUI("Anda mengambil kartu dari deck.");
    isCardPlayedThisTurn = true;

    // If the drawn card is playable, player can manually click it to play; we won't auto-play.
    const drawn = playerHand[playerHand.length - 1];
    if (canPlayCard(drawn)) {
      // give message but do not auto-play
      updateUI("Kartu yang diambil bisa dimainkan. Klik untuk memainkannya.");
      // allow player to play it manually
    } else {
      // if drawn not playable, after short delay pass turn to bot
      setTimeout(() => {
        // If player still on turn and hasn't played, pass turn
        isCardPlayedThisTurn = false;
        if (currentPlayer === "player") {
          switchTurnNormal();
        }
      }, 1200);
    }
  } else {
    // If already performed an action this turn, disallow extra draw
    // However, to satisfy the request: if player played a keeping-turn action (plus2/skip/reverse) and has no playable cards,
    // we should still allow them to draw — we handle that by resetting isCardPlayedThisTurn to false when action-played left them with 0 playable cards.
    // So here we simply ignore.
    updateUI("Anda sudah mengambil atau memainkan kartu pada giliran ini.");
  }
});

// Click player's hand to play card
playerHandEl.addEventListener("click", (event) => {
  if (isBettingPhase) return;
  if (currentPlayer !== "player") return;
  if (waitingForWildChoice) return;

  const cardEl = event.target.closest(".card");
  if (!cardEl) return;

  // find the actual card object (match first occurrence)
  const color = cardEl.dataset.color;
  const value = cardEl.dataset.value;
  const found = playerHand.find((c) => c.color === color && c.value === value);
  if (!found) return;

  // Validate +4 legality
  if (found.value === "plus4") {
    if (!isLegalPlayPlus4(playerHand)) {
      updateUI("Tidak boleh memakai +4 jika masih punya kartu lain yang bisa dimainkan.");
      return;
    }
  }

  // If playable -> play
  if (canPlayCard(found)) {
    handleCardPlay(found, "player");
    // After a play, if player's action leaves them with turn but they have no playable cards left,
    // we should allow them to draw. We implement this by resetting isCardPlayedThisTurn = false when that situation occurs.
    // We'll check and adjust here:
    setTimeout(() => {
      // If player still currentPlayer and has no playable cards
      if (currentPlayer === "player") {
        const playableCount = countPlayable(playerHand);
        if (playableCount === 0) {
          // allow draw manually by resetting flag so clicking deck will work
          isCardPlayedThisTurn = false;
          updateUI("Anda tidak punya kartu yang bisa dimainkan. Silakan ambil dari deck.");
        } else {
          // if they do have playable card, keep normal behavior
          isCardPlayedThisTurn = false; // still allow draw only after they explicitly choose to draw
        }
      }
    }, 300);
  } else {
    updateUI("Kartu tidak cocok. Mainkan kartu lain atau ambil dari deck.");
  }
});

// Call UNO button is created dynamically; handler function defined earlier (onCallUnoClicked)

// Restart button
restartButtonEl.addEventListener("click", () => {
  playerBalance = 5000;
  currentBet = 0;
  gameOverModalEl.classList.add("hidden");
  bettingAreaEl.style.display = "flex";
  unoButtonContainerEl.style.display = "none";
  hideCallUnoUI();
  updateUI();
});


// ---------------------------------------------
// --- INIT / START GAME -----------------------
// ---------------------------------------------
function startGame() {
  gameDeck = createDeck();
  shuffleDeck(gameDeck);
  dealCards();
  currentPlayer = "player";
  isCardPlayedThisTurn = false;
  waitingForWildChoice = false;
  botUnoForgotFlag = false;
  botSaidUno = false;
  clearUnoTimer();
  hideCallUnoUI();
  bettingAreaEl.style.display = "none";
  gameStatusEl.textContent = "Giliran Anda. Pasang strategi!";
  updateUI();
}

// init view (no auto-start until player places bet)
updateUI();

// ---------------------------------------------
// --- Extra: expose some functions for debugging in console (optional)
// ---------------------------------------------
window.__uno = {
  createDeck,
  shuffleDeck,
  gameState: () => ({
    currentPlayer,
    playerHandLength: playerHand.length,
    botHandLength: botHand.length,
    discardTop: discardPile.length ? discardPile[discardPile.length - 1] : null,
    gameDeckLength: gameDeck.length,
  }),
  forceBotPlay: () => handleBotTurn(),
  startGame,
};
